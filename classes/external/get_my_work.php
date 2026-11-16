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
 * The student's own copy of an exercise as the server keeps it, so it follows the student to another device or browser.
 *
 * @package    filter_algorithm
 * @copyright  2026 Boban Blagojević
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class get_my_work extends external_api {
    /**
     * Parameters.
     *
     * @return external_function_parameters
     */
    public static function execute_parameters(): external_function_parameters {
        return new external_function_parameters([
            'contextid' => new external_value(PARAM_INT, 'Context of the page with the exercise'),
            'workkey' => new external_value(PARAM_ALPHANUMEXT, 'Which exercise of the page'),
        ]);
    }

    /**
     * The copy of the current user.
     *
     * @param int $contextid
     * @param string $workkey
     * @return array
     */
    public static function execute(int $contextid, string $workkey): array {
        global $DB, $USER;
        $params = self::validate_parameters(self::execute_parameters(), ['contextid' => $contextid, 'workkey' => $workkey]);
        $context = \context::instance_by_id($params['contextid']);
        self::validate_context($context);
        require_capability('filter/algorithm:sharework', $context);
        $record = $DB->get_record('filter_algorithm_work', ['contextid' => $context->id,
            'workkey' => substr($params['workkey'], 0, 64), 'userid' => $USER->id], 'program, timemodified');
        return ['program' => $record ? (string) $record->program : '', 'timemodified' => $record ? (int) $record->timemodified : 0];
    }

    /**
     * Result.
     *
     * @return external_single_structure
     */
    public static function execute_returns(): external_single_structure {
        return new external_single_structure([
            'program' => new external_value(PARAM_RAW, 'The algorithm (JSON), empty when the server keeps no copy'),
            'timemodified' => new external_value(PARAM_INT, 'When the copy was saved, 0 when there is none'),
        ]);
    }
}
