<?php
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
