<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace filter_algorithm\external;

use core_external\external_api;
use core_external\external_function_parameters;
use core_external\external_single_structure;
use core_external\external_value;

/**
 * Keep the student's copy of an exercise, so the teacher sees it in the class view. An empty copy (back to the
 * original) is removed.
 *
 * @package    filter_algorithm
 * @copyright  2026 Boban Blagojević
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class save_work extends external_api {
    /** Longest copy that is kept (JSON). */
    const MAX_LENGTH = 300000;

    /**
     * Parameters.
     *
     * @return external_function_parameters
     */
    public static function execute_parameters(): external_function_parameters {
        return new external_function_parameters([
            'contextid' => new external_value(PARAM_INT, 'Context of the page with the exercise'),
            'workkey' => new external_value(PARAM_ALPHANUMEXT, 'Which exercise of the page'),
            'program' => new external_value(PARAM_RAW, 'The algorithm (JSON), empty to remove the copy'),
        ]);
    }

    /**
     * Keep or remove the copy.
     *
     * @param int $contextid
     * @param string $workkey
     * @param string $program
     * @return array
     */
    public static function execute(int $contextid, string $workkey, string $program): array {
        global $DB, $USER;
        $params = self::validate_parameters(
            self::execute_parameters(),
            ['contextid' => $contextid, 'workkey' => $workkey, 'program' => $program]
        );
        $context = \context::instance_by_id($params['contextid']);
        self::validate_context($context);
        require_capability('filter/algorithm:sharework', $context);
        $key = substr($params['workkey'], 0, 64);
        $where = ['contextid' => $context->id, 'workkey' => $key, 'userid' => $USER->id];
        if ($params['program'] === '') {
            $DB->delete_records('filter_algorithm_work', $where);
            return ['saved' => false];
        }
        $model = json_decode($params['program'], true, 64);
        if (strlen($params['program']) > self::MAX_LENGTH || !is_array($model) || empty($model['functions'])) {
            throw new \invalid_parameter_exception('Not an algorithm');
        }
        $record = $DB->get_record('filter_algorithm_work', $where);
        if ($record) {
            $record->program = $params['program'];
            $record->timemodified = time();
            $DB->update_record('filter_algorithm_work', $record);
        } else {
            $DB->insert_record('filter_algorithm_work', (object) ($where + ['program' => $params['program'],
                'timemodified' => time()]));
        }
        return ['saved' => true];
    }

    /**
     * What is returned.
     *
     * @return external_single_structure
     */
    public static function execute_returns(): external_single_structure {
        return new external_single_structure([
            'saved' => new external_value(PARAM_BOOL, 'True when the copy is kept, false when it was removed'),
        ]);
    }
}
