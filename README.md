# filter_algorithm – Run / Step for algorithm flowcharts

Moodle text filter that makes flowcharts created with
[tiny_algorithm](https://github.com/blagojevicboban/moodle-tiny_algorithm) interactive for readers.

A diagram that was inserted with the **Interactive** option (the image has `data-algorithm-run`) is replaced on the page by the
diagram itself with **Run**, **Step** and **Reset** buttons, a speed slider and a console. Input blocks show an input field in
the console. Execution happens entirely in the reader's browser; nothing is sent to the server. Diagrams without the option
stay normal images, and the image is left in place if the model cannot be read.

**Status: alpha.** Tested on Moodle 5.3 with the course summary view only (admin user).

## Requirements
- Moodle 5.0 or later
- [tiny_algorithm](https://github.com/blagojevicboban/moodle-tiny_algorithm) (the viewer imports its `runner`, `render` and
  `model` modules)

## Install
1. Install `tiny_algorithm` first.
2. Copy this folder to `filter/algorithm` (Moodle 5.0) or `public/filter/algorithm` (Moodle 5.1+) and run the upgrade.
3. *Site administration → Plugins → Filters → Manage filters*: set **Algorithm viewer (Run / Step)** to *On*
   (it can be switched off per course or activity).

The compiled AMD module is committed in `amd/build`; rebuild it with Moodle's `npx grunt amd --root=<path to this plugin>` after
changing `amd/src`.

## License
GNU GPL v3 or later, see [LICENSE](LICENSE).
