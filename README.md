# Algorithm Viewer - Run / Step for Flowcharts (Moodle Filter)

[![Moodle Plugin CI](https://github.com/blagojevicboban/moodle-filter_algorithm/actions/workflows/ci.yml/badge.svg)](https://github.com/blagojevicboban/moodle-filter_algorithm/actions/workflows/ci.yml)
[![Moodle](https://img.shields.io/badge/Moodle-4.5%20to%205.3-orange.svg)](https://moodle.org)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-purple.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](LICENSE)

**Algorithm Viewer** is a Moodle text filter that makes flowcharts created with
[tiny_algorithm](https://github.com/blagojevicboban/moodle-tiny_algorithm) **interactive for readers**. Wherever the filtered
text is shown (a Page, course summary, forum post, quiz text…), the diagram gets **Run**, **Step** and **Reset** buttons.

> Status: **alpha**.

## ✨ Features

| Feature | Description |
|---------|-------------|
| ▶️ **Run / Step / Reset** | Run the whole algorithm or step one block at a time, the current block is highlighted |
| ⌨️ **Console with Input** | *Output* blocks print to a console, *Input* blocks show an input field |
| 💻 **Code Beside the Diagram** | Python, C, Java or C# with a language selector and **Copy**; below the diagram on narrow pages |
| 🔍 **Variable Watch** | Value of every variable at the current step, changed values highlighted |
| 🐢 **Speed Slider** | Slow down to follow the logic, or speed up |
| 🔎 **Zoom** | **−**, **+**, **100%**, **Fit** or **Ctrl + mouse wheel**; wide diagrams start fitted |
| 🧳 **Runs in the Browser** | Nothing is sent to the server, nothing is stored |
| ✅ **Opt-in per Diagram** | Only diagrams inserted with **Interactive** are replaced; others stay normal images |
| 🌐 **Languages** | English and Serbian (Latin) |
| 🔒 **Privacy** | GDPR compliant (Privacy API, no personal data stored) |

## 📋 Requirements

- **Moodle**: 4.5 LTS or newer (4.5, 5.0, 5.1, 5.2 and 5.3 tested)
- **PHP**: 8.2+
- **[tiny_algorithm](https://github.com/blagojevicboban/moodle-tiny_algorithm)**: must be installed first (this filter reuses its
  `runner`, `render`, `model`, `interpreter` and `zoom` modules)

## 🚀 Installation

### Via Git
```bash
cd /path/to/moodle/public/filter          # Moodle 5.1+; use filter/ in the Moodle root before 5.1
git clone https://github.com/blagojevicboban/moodle-filter_algorithm.git algorithm
php admin/cli/upgrade.php
```

### Via Download
1. Install **tiny_algorithm** first
2. Download the [latest release](https://github.com/blagojevicboban/moodle-filter_algorithm/releases)
3. Extract to `filter/algorithm` (`public/filter/algorithm` in Moodle 5.1+)
4. Visit Site Administration → Notifications

Then go to **Site administration → Plugins → Filters → Manage filters** and set **Algorithm viewer (Run / Step)** to **On**.
It can be switched off for a single course or activity there as well. The compiled AMD module is included in `amd/build`.

## 📖 Usage

### For Teachers

1.  In the TinyMCE editor open a diagram with the **Algorithm** button (or double-click an existing one).
2.  Tick **Interactive** and click **Insert**, then save the page.
3.  To change an old diagram, open it again, tick the option and insert it again.

### For Students

1.  Open the page: the diagram has **Run / Step / Reset**, a speed slider, zoom buttons, a console and the generated code.
2.  Use **Code** and **Variables** to hide or show the panels (the choice is remembered).
3.  Type values into the console when an *Input* block asks for them.

If the model cannot be read, the original image is left in place.

## 🔑 Permissions

The filter needs no capabilities and no settings. Who can create interactive diagrams is controlled by the editor plugin
(`tiny/algorithm:viewplugin`).

## 🛠️ Development

```bash
# Rebuild the AMD module after changing amd/src/viewer.js (from the Moodle root)
npx grunt amd --root=public/filter/algorithm
```

## 📁 Structure

```
filter/algorithm/
├── amd/
│   ├── src/          # viewer.js
│   └── build/        # Compiled module
├── classes/          # text_filter, privacy provider
├── lang/             # en, sr_lt
├── styles.css
└── version.php       # Plugin metadata
```

## 🌍 Languages

- 🇬🇧 English
- 🇷🇸 Srpski (Serbian, Latin)

## 📝 Changelog

See [CHANGELOG.md](CHANGELOG.md).

## 📄 License

GNU GPL v3 or later - see [LICENSE](LICENSE).

## 👤 Author

**Boban Blagojević**  
[GitHub](https://github.com/blagojevicboban)

## 🙏 Credits

- [tiny_algorithm](https://github.com/blagojevicboban/moodle-tiny_algorithm) - the editor and runner this filter builds on
- [Flowgorithm](https://www.flowgorithm.org/) - the flowchart style that inspired the project

---

Made with ❤️ for teaching programming
