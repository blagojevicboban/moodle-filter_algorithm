# Algorithm Viewer – Run / Step for Flowcharts (Moodle Filter)

**Algorithm Viewer** is a Moodle text filter that makes flowcharts created with
[tiny_algorithm](https://github.com/blagojevicboban/moodle-tiny_algorithm) **interactive for readers**. Wherever the filtered
text is shown (a Page, course summary, forum post, quiz text…), the diagram gets **Run**, **Step** and **Reset** buttons.

> Status: **alpha**. Tested on Moodle 5.3 with a Page resource and the course summary, as the admin user.

## Features

- **Run / Step / Reset**: Run the whole algorithm, or step through it one block at a time with the current block highlighted
  on the diagram.
- **Console with Input**: *Output* blocks print to a console, *Input* blocks show an input field.
- **Speed Slider**: Slow it down to follow the logic, or speed it up.
- **Zoom**: **−**, **+**, **100%** and **Fit** buttons or **Ctrl + mouse wheel**. Wide diagrams start fitted to the available
  width.
- **Runs in the Reader's Browser**: Nothing is sent to the server and nothing is stored.
- **Opt-in per Diagram**: Only diagrams that were inserted with the **Interactive** option are replaced. Other diagrams stay
  normal images. If the model cannot be read, the original image is left in place.
- **Languages**: English and Serbian (Latin).
- **Privacy Compliant**: Implements the Moodle Privacy API (null provider – no personal data stored).

## Related Plugins

- **[tiny_algorithm](https://github.com/blagojevicboban/moodle-tiny_algorithm)** – the TinyMCE flowchart editor that creates the
  diagrams. **Required**: this filter imports its `runner`, `render`, `model`, `interpreter` and `zoom` modules.

## Compatibility

| Moodle Version   | Status                                  |
|------------------|-----------------------------------------|
| Moodle 5.3       | ✅ Tested                               |
| Moodle 5.0 – 5.2 | ⚠️ Declared minimum is 5.0, not tested |
| Moodle 4.x       | ❌ Not supported (requires Moodle 5.0+) |

## Installation

1. Install **tiny_algorithm** first.
2. Place this plugin folder in `filter/algorithm` (Moodle 5.0) or `public/filter/algorithm` (Moodle 5.1 and later).
3. Log in as an administrator and go to **Site administration → Notifications** to complete the installation.
4. Go to **Site administration → Plugins → Filters → Manage filters** and set **Algorithm viewer (Run / Step)** to **On**.
   It can be switched off again for a single course or activity from the filter settings there.

The compiled AMD module is included in `amd/build`.

## Usage

1. In the TinyMCE editor open a diagram with the **Algorithm** button (or double-click an existing one).
2. Tick **Interactive** and click **Insert**, then save the page.
3. Readers now see the diagram with **Run / Step / Reset**, a speed slider, zoom buttons and a console.

A diagram inserted **without** the Interactive option stays a normal image; open it again, tick the option and insert it again
to change that.

## Permissions

The filter needs no capabilities and no settings. Who can create interactive diagrams is controlled by the editor plugin
(`tiny/algorithm:viewplugin`).

## Building from Source

If you modify `amd/src/viewer.js`, rebuild the AMD module from the Moodle root:

```bash
npx grunt amd --root=public/filter/algorithm
```

## Technical Requirements

- Moodle 5.0 or higher (5.3 tested)
- [tiny_algorithm](https://github.com/blagojevicboban/moodle-tiny_algorithm) installed
- Node.js 20+ (only for building AMD modules if you modify the source)

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for version history.

## License

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as
published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

See [LICENSE](LICENSE) for details.

---

© 2026 [Boban Blagojević](https://github.com/blagojevicboban)
