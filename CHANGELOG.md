# Changelog

## Unreleased

- Supports **Moodle 4.5 LTS** (`requires` lowered to 4.5, `supported` 4.5 – 5.3); tested on 4.5, 5.0 – 5.3.
- Moodle coding standard: phpcs, ESLint and stylelint report no problems; GitHub Actions (moodle-plugin-ci) added.
- New README and `description.html` for the Moodle plugins directory.

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
