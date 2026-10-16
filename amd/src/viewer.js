/**
 * Replaces interactive algorithm images with a diagram that can be run and stepped through.
 *
 * @module filter_algorithm/viewer
 */
import {deserialize} from 'tiny_algorithm/model';
import {AlgorithmRunner} from 'tiny_algorithm/runner';
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
        new AlgorithmRunner(host, program, {lang: document.documentElement.lang});
    });
};

export const init = () => {
    mount(document);
    if (!initialised) {
        initialised = true;
        document.addEventListener(eventTypes.filterContentUpdated, (e) => {
            Array.from((e.detail && e.detail.nodes) || []).forEach(mount);
        });
    }
};
