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

use filter_algorithm\external\get_my_work;
use filter_algorithm\external\save_work;

/**
 * Unit tests of the student's own copy on the server: it is read back (to continue on another device), only by its
 * owner, and it is removed with its activity.
 *
 * @package    filter_algorithm
 * @category   test
 * @copyright  2026 Boban Blagojević
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers     \filter_algorithm\external\get_my_work
 * @covers     \filter_algorithm\observer
 */
final class my_work_test extends \advanced_testcase {
    /** An algorithm as the browser sends it. */
    const PROGRAM = '{"version":1,"name":"P","functions":[{"name":"Main","params":[],"returnType":null,"body":[]}]}';

    public function test_a_student_reads_back_only_their_own_copy(): void {
        $this->resetAfterTest();
        $gen = $this->getDataGenerator();
        $course = $gen->create_course();
        $page = $gen->create_module('page', ['course' => $course->id]);
        $context = \context_module::instance($page->cmid);
        $s1 = $gen->create_and_enrol($course, 'student');
        $s2 = $gen->create_and_enrol($course, 'student');
        $this->setUser($s1);
        $this->assertSame(['program' => '', 'timemodified' => 0], get_my_work::execute($context->id, 'wabc'));
        save_work::execute($context->id, 'wabc', self::PROGRAM);
        $mine = get_my_work::execute($context->id, 'wabc');
        $this->assertSame(self::PROGRAM, $mine['program']);
        $this->assertGreaterThan(0, $mine['timemodified']);
        $this->assertSame('', get_my_work::execute($context->id, 'wother')['program']);
        $this->setUser($s2);
        $this->assertSame('', get_my_work::execute($context->id, 'wabc')['program']);
        // Back to the original: the copy is removed.
        $this->setUser($s1);
        save_work::execute($context->id, 'wabc', '');
        $this->assertSame('', get_my_work::execute($context->id, 'wabc')['program']);
    }

    public function test_a_guest_cannot_read_copies(): void {
        $this->resetAfterTest();
        $gen = $this->getDataGenerator();
        $course = $gen->create_course();
        $page = $gen->create_module('page', ['course' => $course->id]);
        $other = $gen->create_user();
        $this->setUser($other);
        $this->expectException(\require_login_exception::class);
        get_my_work::execute(\context_module::instance($page->cmid)->id, 'wabc');
    }

    public function test_copies_go_with_their_activity(): void {
        global $DB;
        $this->resetAfterTest();
        $gen = $this->getDataGenerator();
        $course = $gen->create_course();
        $keep = $gen->create_module('page', ['course' => $course->id]);
        $gone = $gen->create_module('page', ['course' => $course->id]);
        $s1 = $gen->create_and_enrol($course, 'student');
        $this->setUser($s1);
        save_work::execute(\context_module::instance($keep->cmid)->id, 'wabc', self::PROGRAM);
        save_work::execute(\context_module::instance($gone->cmid)->id, 'wabc', self::PROGRAM);
        $this->setAdminUser();
        $cmactions = class_exists(\core_courseformat\formatactions::class) &&
            method_exists(\core_courseformat\local\cmactions::class, 'delete');
        if ($cmactions) {
            \core_courseformat\formatactions::cm($course)->delete($gone->cmid);
        } else {
            course_delete_module($gone->cmid); // Moodle 4.5 to 5.1.
        }
        $this->assertSame(1, $DB->count_records('filter_algorithm_work'));
        delete_course($course, false);
        $this->assertSame(0, $DB->count_records('filter_algorithm_work'));
    }
}
