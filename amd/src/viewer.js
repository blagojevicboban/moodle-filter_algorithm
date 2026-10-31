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
 * Replaces interactive algorithm images with a diagram that can be run and stepped through,
 * or, when the teacher allowed it, with a workspace where students edit and test their own copy.
 *
 * @module filter_algorithm/viewer
 * @copyright  2026 Boban Blagojević
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
import {deserialize} from 'tiny_algorithm/model';
import {AlgorithmRunner} from 'tiny_algorithm/runner';
import {AlgorithmWorkspace} from 'tiny_algorithm/workspace';
import {deserializeTests} from 'tiny_algorithm/tests';
import {load as loadStrings} from 'tiny_algorithm/langstrings';
import {eventTypes} from 'core_filters/events';

const SELECTOR = 'img[data-algorithm][data-algorithm-run]';
let initialised = false;

const mount = (root) => {
    if (!root || !root.querySelectorAll) {
        return;
    }
    root.querySelectorAll(SELECTOR).forEach((img) => {
        let program;
        try {
            program = deserialize(img.getAttribute('data-algorithm'));
        } catch (e) {
            return; // Leave the static image in place when the model cannot be read.
        }
        const host = document.createElement('div');
        host.className = 'alg-viewer-host';
        img.replaceWith(host);
        const opts = {
            lang: document.documentElement.lang,
            showCode: img.getAttribute('data-algorithm-code') !== '0',
            showWatch: img.getAttribute('data-algorithm-watch') !== '0',
        };
        if (img.getAttribute('data-algorithm-edit') !== '1') {
            new AlgorithmRunner(host, program, opts);
            return;
        }
        let solution = null;
        try {
            const text = img.getAttribute('data-algorithm-solution');
            solution = text ? deserialize(text) : null;
        } catch (e) {
            solution = null; // A broken solution only hides the Show solution button.
        }
        new AlgorithmWorkspace(host, {
            ...opts,
            starter: program,
            solution,
            tests: deserializeTests(img.getAttribute('data-algorithm-tests')),
            showSolution: img.getAttribute('data-algorithm-showsolution') === '1',
            scope: (window.M && window.M.cfg && window.M.cfg.contextid) || '',
        });
    });
};

export const init = async() => {
    await loadStrings();
    mount(document);
    if (!initialised) {
        initialised = true;
        document.addEventListener(eventTypes.filterContentUpdated, (e) => {
            Array.from((e.detail && e.detail.nodes) || []).forEach(mount);
        });
    }
};
