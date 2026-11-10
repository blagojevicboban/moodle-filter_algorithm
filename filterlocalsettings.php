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

/**
 * Local settings of the algorithm filter in a course, a category or an activity: the language of the generated code.
 *
 * @package    filter_algorithm
 * @copyright  2026 Boban Blagojević
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if (!class_exists('filter_local_settings_form')) {
    require_once($CFG->dirroot . '/filter/local_settings_form.php');
}

/**
 * Form of the local settings (Course, Filters, Algorithm viewer, Settings).
 *
 * @package    filter_algorithm
 * @copyright  2026 Boban Blagojević
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class algorithm_filter_local_settings_form extends filter_local_settings_form {
    /**
     * The fields: the language (or the one set higher up), and whether it replaces the language of the algorithms and is
     * locked for students.
     *
     * @param MoodleQuickForm $mform the form
     */
    protected function definition_inner($mform) {
        $offered = \tiny_algorithm\local\code_language::offered();
        $names = array_intersect_key(\tiny_algorithm\local\code_language::names(), array_flip($offered));
        $choices = ['' => get_string('codelang_inherit', 'filter_algorithm')] + $names;
        $mform->addElement('select', 'codelang', get_string('codelang', 'filter_algorithm'), $choices);
        $mform->addHelpButton('codelang', 'codelang', 'filter_algorithm');
        $mform->addElement('advcheckbox', 'codeforce', get_string('codeforce', 'filter_algorithm'));
        $mform->hideIf('codeforce', 'codelang', 'eq', '');
        $mform->addElement('advcheckbox', 'codelock', get_string('codelock', 'filter_algorithm'));
        $mform->hideIf('codelock', 'codelang', 'eq', '');
        $local = filter_get_local_config('algorithm', $this->context->id);
        $this->set_data(['codelang' => $local['codelang'] ?? '', 'codeforce' => $local['codeforce'] ?? 0,
            'codelock' => $local['codelock'] ?? 0]);
    }

    /**
     * Save the language; without a language the two options are removed too.
     *
     * @param stdClass $data form data
     */
    public function save_changes($data) {
        $data = (array) $data;
        if (($data['codelang'] ?? '') === '') {
            $data['codeforce'] = '';
            $data['codelock'] = '';
        } else {
            $data['codeforce'] = empty($data['codeforce']) ? '' : '1';
            $data['codelock'] = empty($data['codelock']) ? '' : '1';
        }
        parent::save_changes((object) $data);
    }
}
