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

use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\approved_userlist;
use core_privacy\local\request\userlist;
use core_privacy\local\request\writer;
use filter_algorithm\external\get_class_work;
use filter_algorithm\external\save_work;
use filter_algorithm\privacy\provider;

/**
 * Unit tests of the class view: students keep their copies, teachers see them (only their groups in separate groups),
 * students cannot see the class, and the privacy API exports and deletes the copies.
 *
 * @package    filter_algorithm
 * @category   test
 * @copyright  2026 Boban Blagojević
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers     \filter_algorithm\external\save_work
 * @covers     \filter_algorithm\external\get_class_work
 * @covers     \filter_algorithm\privacy\provider
 */
final class class_work_test extends \advanced_testcase {
    /** An algorithm as the browser sends it. */
    const PROGRAM = '{"version":1,"name":"P","functions":[{"name":"Main","params":[],"returnType":null,"body":[]}]}';

    /**
     * A course with a page, a teacher and two students.
     *
     * @return array [context of the page, teacher, student 1, student 2, course]
     */
    protected function setup_course(): array {
        $gen = $this->getDataGenerator();
        $course = $gen->create_course();
        $page = $gen->create_module('page', ['course' => $course->id]);
        $teacher = $gen->create_and_enrol($course, 'editingteacher');
        $s1 = $gen->create_and_enrol($course, 'student', ['firstname' => 'Ana']);
        $s2 = $gen->create_and_enrol($course, 'student', ['firstname' => 'Milan']);
        return [\context_module::instance($page->cmid), $teacher, $s1, $s2, $course];
    }

    public function test_students_save_and_teachers_see(): void {
        $this->resetAfterTest();
        [$context, $teacher, $s1, $s2] = $this->setup_course();
        $this->setUser($s1);
        $this->assertTrue(save_work::execute($context->id, 'wabc', self::PROGRAM)['saved']);
        // A second save replaces the copy.
        save_work::execute($context->id, 'wabc', self::PROGRAM);
        $this->setUser($s2);
        save_work::execute($context->id, 'wabc', self::PROGRAM);
        save_work::execute($context->id, 'wother', self::PROGRAM);
        $this->setUser($teacher);
        $copies = get_class_work::execute($context->id, 'wabc')['copies'];
        $this->assertCount(2, $copies);
        $this->assertEqualsCanonicalizing([$s1->id, $s2->id], array_column($copies, 'userid'));
        $this->assertSame(self::PROGRAM, $copies[0]['program']);
        // Back to the original: the copy is removed.
        $this->setUser($s1);
        $this->assertFalse(save_work::execute($context->id, 'wabc', '')['saved']);
        $this->setUser($teacher);
        $this->assertCount(1, get_class_work::execute($context->id, 'wabc')['copies']);
    }

    public function test_students_cannot_see_the_class_and_send_only_algorithms(): void {
        $this->resetAfterTest();
        [$context, , $s1] = $this->setup_course();
        $this->setUser($s1);
        try {
            save_work::execute($context->id, 'wabc', '{"not": "an algorithm"}');
            $this->fail('A text that is no algorithm was kept');
        } catch (\invalid_parameter_exception $e) {
            $this->assertStringContainsString('Not an algorithm', $e->getMessage());
        }
        $this->expectException(\required_capability_exception::class);
        get_class_work::execute($context->id, 'wabc');
    }

    public function test_separate_groups(): void {
        $this->resetAfterTest();
        [$context, , $s1, $s2, $course] = $this->setup_course();
        $gen = $this->getDataGenerator();
        $DB = $GLOBALS['DB'];
        $DB->set_field('course', 'groupmode', SEPARATEGROUPS, ['id' => $course->id]);
        $DB->set_field('course', 'groupmodeforce', 1, ['id' => $course->id]);
        $teacher = $gen->create_and_enrol($course, 'teacher');
        $group = $gen->create_group(['courseid' => $course->id]);
        $gen->create_group_member(['groupid' => $group->id, 'userid' => $teacher->id]);
        $gen->create_group_member(['groupid' => $group->id, 'userid' => $s1->id]);
        foreach ([$s1, $s2] as $s) {
            $this->setUser($s);
            save_work::execute($context->id, 'wabc', self::PROGRAM);
        }
        // A teacher without "access all groups" sees only the students of their group.
        assign_capability(
            'moodle/site:accessallgroups',
            CAP_PROHIBIT,
            $DB->get_field('role', 'id', ['shortname' => 'teacher']),
            \context_course::instance($course->id)->id,
            true
        );
        $this->setUser($teacher);
        $copies = get_class_work::execute($context->id, 'wabc')['copies'];
        $this->assertSame([(int) $s1->id], array_column($copies, 'userid'));
    }

    public function test_privacy(): void {
        $this->resetAfterTest();
        [$context, , $s1, $s2] = $this->setup_course();
        foreach ([$s1, $s2] as $s) {
            $this->setUser($s);
            save_work::execute($context->id, 'wabc', self::PROGRAM);
        }
        $this->setUser();
        $contexts = provider::get_contexts_for_userid($s1->id);
        $this->assertSame([$context->id], array_map('intval', $contexts->get_contextids()));
        $users = new userlist($context, 'filter_algorithm');
        provider::get_users_in_context($users);
        $this->assertEqualsCanonicalizing([$s1->id, $s2->id], $users->get_userids());
        provider::export_user_data(new approved_contextlist($s1, 'filter_algorithm', [$context->id]));
        $data = writer::with_context($context)->get_data([get_string('privacy:path', 'filter_algorithm')]);
        $this->assertSame(self::PROGRAM, $data->copies[0]->algorithm);
        provider::delete_data_for_user(new approved_contextlist($s1, 'filter_algorithm', [$context->id]));
        $this->assertSame(1, $GLOBALS['DB']->count_records('filter_algorithm_work'));
        provider::delete_data_for_users(new approved_userlist($context, 'filter_algorithm', [$s2->id]));
        $this->assertSame(0, $GLOBALS['DB']->count_records('filter_algorithm_work'));
    }
}
