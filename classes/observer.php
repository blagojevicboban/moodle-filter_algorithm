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

namespace filter_algorithm;

/**
 * Removes the students' copies of exercises whose page (activity or course) was deleted.
 *
 * @package    filter_algorithm
 * @copyright  2026 Boban Blagojević
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class observer {
    /**
     * An activity was deleted: its copies go with it (its context is gone when the event is triggered; the event
     * itself is in the context of the course).
     *
     * @param \core\event\course_module_deleted $event
     */
    public static function course_module_deleted(\core\event\course_module_deleted $event): void {
        self::remove_orphans();
    }

    /**
     * A course was deleted: the copies of its pages, whose contexts are gone, are removed.
     *
     * @param \core\event\course_deleted $event
     */
    public static function course_deleted(\core\event\course_deleted $event): void {
        self::remove_orphans();
    }

    /**
     * Remove the copies whose context no longer exists.
     */
    public static function remove_orphans(): void {
        global $DB;
        $DB->delete_records_select('filter_algorithm_work', 'NOT EXISTS (SELECT 1 FROM {context} c WHERE c.id = contextid)');
    }
}
