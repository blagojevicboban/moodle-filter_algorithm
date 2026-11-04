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
import {restoreAttributes} from 'tiny_algorithm/embed';
import {deserialize} from 'tiny_algorithm/model';
import {AlgorithmRunner} from 'tiny_algorithm/runner';
import {renderProgram} from 'tiny_algorithm/render';
import {AlgorithmWorkspace} from 'tiny_algorithm/workspace';
import {deserializeTests} from 'tiny_algorithm/tests';
import {load as loadStrings} from 'tiny_algorithm/langstrings';
import {eventTypes} from 'core_filters/events';

// The class marks images whose attributes were removed by Moodle's HTML cleaning; they are read back from the SVG file.
const SELECTOR = 'img[data-algorithm][data-algorithm-run], img.tiny_algorithm-run';
// Images that are not interactive are drawn again from their model, so older diagrams get the current look (arrows).
const STATIC = 'img[data-algorithm]:not([data-algorithm-run]), img.tiny_algorithm-image:not(.tiny_algorithm-run)';
// Diagrams are built when they come near the screen, so a page with many of them opens quickly.
const MARGIN = '300px';
const seen = new WeakSet();
let initialised = false;
let observer = null;

/**
 * Replace an algorithm image with its interactive diagram.
 *
 * @param {HTMLImageElement} img
 */
const show = async(img) => {
    if (!img.isConnected || !(await restoreAttributes(img)) || !img.hasAttribute('data-algorithm-run')) {
        return;
    }
    let program;
    try {
        program = deserialize(img.getAttribute('data-algorithm'));
    } catch (e) {
        return; // Leave the static image in place when the model cannot be read.
    }
    const host = document.createElement('div');
    host.className = 'tiny_algorithm-viewer-host';
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
};

/**
 * Draw a static algorithm image again from the model it stores.
 *
 * @param {HTMLImageElement} img
 */
const redraw = async(img) => {
    if (!img.isConnected || !(await restoreAttributes(img))) {
        return;
    }
    if (img.hasAttribute('data-algorithm-run')) {
        show(img); // The SVG says the diagram is interactive after all.
        return;
    }
    try {
        const svg = renderProgram(deserialize(img.getAttribute('data-algorithm')));
        img.src = URL.createObjectURL(new Blob([svg], {type: 'image/svg+xml'}));
    } catch (e) {
        // Keep the saved picture when the model cannot be drawn.
    }
};

const handle = (img) => (img.matches(SELECTOR) ? show(img) : redraw(img));

const mount = (root) => {
    if (!root || !root.querySelectorAll) {
        return;
    }
    if (!observer && 'IntersectionObserver' in window) {
        observer = new IntersectionObserver((entries) => entries.filter((e) => e.isIntersecting).forEach((e) => {
            observer.unobserve(e.target);
            handle(e.target);
        }), {rootMargin: MARGIN});
    }
    const all = `${SELECTOR}, ${STATIC}`;
    const images = root.matches && root.matches(all) ? [root] : [...root.querySelectorAll(all)];
    images.filter((img) => !seen.has(img)).forEach((img) => {
        seen.add(img);
        if (observer) {
            observer.observe(img);
        } else {
            handle(img);
        }
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
