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
 * Unit tests for the algorithm viewer filter.
 *
 * @package    filter_algorithm
 * @category   test
 * @copyright  2026 Boban Blagojević
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers     \filter_algorithm\text_filter
 */
final class text_filter_test extends \advanced_testcase {
    /**
     * Does the page load the viewer module?
     *
     * @return bool
     */
    protected function viewer_loaded(): bool {
        global $PAGE;
        return strpos($PAGE->requires->get_end_code(), 'filter_algorithm/viewer') !== false;
    }

    public function test_text_without_interactive_algorithm(): void {
        $this->resetAfterTest();
        $filter = new text_filter(\context_system::instance(), []);
        $text = '<p>Text</p><img src="data:image/svg+xml;base64,AAAA" alt="Algorithm" data-algorithm="AAAA">';
        $this->assertSame($text, $filter->filter($text));
        $this->assertSame('', $filter->filter(''));
        $this->assertFalse($this->viewer_loaded());
    }

    public function test_text_with_interactive_algorithm(): void {
        $this->resetAfterTest();
        $filter = new text_filter(\context_system::instance(), []);
        $text = '<p>Run it:</p><img src="x.svg" alt="Algorithm" data-algorithm="AAAA" data-algorithm-run="1">';
        $this->assertSame($text, $filter->filter($text));
        $this->assertTrue($this->viewer_loaded());
    }
}
