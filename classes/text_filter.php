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
 * Turns interactive algorithm images (data-algorithm-run or the tiny_algorithm-run class) into diagrams with Run / Step controls
 * and draws the other algorithm images again with the current version of the editor.
 *
 * @package    filter_algorithm
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class text_filter extends \core_filters\text_filter {
    /** @var \WeakMap|null pages ($PAGE objects) that already load the viewer, so it is started once per page */
    protected static $pages = null;

    /**
     * Load the viewer module when the text contains an algorithm image; the text itself is left unchanged.
     *
     * @param string $text
     * @param array $options
     * @return string
     */
    public function filter($text, array $options = []) {
        global $PAGE;
        // Moodle's HTML cleaning removes the data attributes; the image then keeps only its tiny_algorithm-image class.
        // Interactive images also have the tiny_algorithm-image class, and data-algorithm-run starts with data-algorithm.
        if (!is_string($text) || (strpos($text, 'data-algorithm') === false && strpos($text, 'tiny_algorithm-image') === false)) {
            return $text;
        }
        self::$pages = self::$pages ?? new \WeakMap();
        if (!isset(self::$pages[$PAGE])) {
            self::$pages[$PAGE] = true;
            // The code language of the site and of the course of the page.
            $config = \tiny_algorithm\local\code_language::for_context($PAGE->context);
            $PAGE->requires->js_call_amd('filter_algorithm/viewer', 'init', [$config]);
        }
        return $text;
    }
}
