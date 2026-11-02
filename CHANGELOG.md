# Changelog

## 2.0.0 – 2026-11-02 (stable)

- With tiny_algorithm 2.0.0: diagrams stored only in their SVG (read with the classes `tiny_algorithm-image` /
  `tiny_algorithm-run`), matrices, function tests in the exercises. Diagrams from 1.x keep working.
- Requires `tiny_algorithm` 2.0.0 (2026110203).

## 1.2.0 – 2026-11-02 (stable)

- **Fixed: interactive diagrams in forum posts, assignment text and other cleaned content.** The filter also loads the viewer
  for images with the `tiny_algorithm-run` class, and the viewer reads the model and the options from the SVG file when the
  cleaning removed the `data-algorithm` attributes (diagrams inserted with tiny_algorithm 1.2.0).
- Lazy mount: a diagram is built when it comes near the screen (IntersectionObserver), so long pages open quickly.
- CSS class of the viewer host renamed to `tiny_algorithm-viewer-host`.
- PHPUnit test with a text cleaned by `purify_html`.
- Requires `tiny_algorithm` 1.2.0 (2026110201).

## 1.1.0 – 2026-11-01 (stable)

- With tiny_algorithm 1.1.0: error messages in the user's language, keyboard use of the student editor, the running block
  marked by its border too.
- `privacy:metadata` mentions the display settings and the student's copy that the browser keeps in its local storage.
- PHPUnit tests for `text_filter` (the text is unchanged; the viewer is loaded only for interactive diagrams).
- Screenshots in `docs/screenshots/`.
- Requires `tiny_algorithm` 1.1.0 (2026110100).

## 1.0.0 – 2026-10-31 (stable)

- **Stable release.**
- The viewer loads the widget texts from the language files of tiny_algorithm before it builds the diagrams, so they follow
  the user's language and can be translated in AMOS; the generated code is also available in C++, JavaScript and Pascal.
- `amd/src/viewer.js` has the Moodle GPL boilerplate (Fixes #1).
- Requires `tiny_algorithm` 1.0.0 (2026103100).

## 0.5.0 – 2026-10-28 (alpha)

- Diagrams with `data-algorithm-edit="1"` get the student workspace of `tiny_algorithm` 0.5.0: edit an own copy, run the
  teacher's tests, restore the original, full screen and (when allowed) show the solution.
- Requires `tiny_algorithm` 0.5.0 (2026102800).

## 0.4.0 – 2026-10-16 (alpha)

- Supports **Moodle 4.5 LTS** (`requires` lowered to 4.5, `supported` 4.5 – 5.3); tested on 4.5, 5.0 – 5.3.
- Moodle coding standard: phpcs, ESLint and stylelint report no problems; GitHub Actions (moodle-plugin-ci) added.
- New README and `description.html` for the Moodle plugins directory.
- **Serbian Cyrillic (sr_cr)** language files and UI strings; the UI picks Latin or Cyrillic from the Moodle language.

## 0.3.0 – 2026-10-17 (alpha)

- Variable watch panel and a code panel that can be hidden, both honouring the per-diagram options (`data-algorithm-code="0"`,
  `data-algorithm-watch="0"`).
- The styles are now part of `tiny_algorithm` (they belong to its runner module).

## 0.2.0 – 2026-10-16 (alpha)

- Shows the generated code (Python, C, Java, C#) next to the diagram with a language selector, Copy and a Code toggle button.
  Two-column layout on wide screens, stacked on narrow ones.

## 0.1.0 – 2026-10-16 (alpha)

- Replaces diagrams inserted with the **Interactive** option (`data-algorithm-run`) with a diagram that has Run / Step / Reset,
  a speed slider, zoom buttons and a console.
- Input blocks show an input field in the console.
- Wide diagrams start fitted to the available width.
- Runs entirely in the reader's browser.
- English and Serbian (Latin) strings.
