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
 * Algorithm viewer filter.
 *
 * @package    filter_algorithm
 * @copyright  2026 Boban Blagojević
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace filter_algorithm;

/**
 * Turns interactive algorithm images (data-algorithm-run) into diagrams with Run / Step controls.
 *
 * @package    filter_algorithm
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class text_filter extends \core_filters\text_filter {
    /**
     * Load the viewer module when the text contains an interactive algorithm; the text itself is left unchanged.
     *
     * @param string $text
     * @param array $options
     * @return string
     */
    public function filter($text, array $options = []) {
        global $PAGE;
        if (!is_string($text) || strpos($text, 'data-algorithm-run') === false) {
            return $text;
        }
        $PAGE->requires->js_call_amd('filter_algorithm/viewer', 'init');
        return $text;
    }
}
