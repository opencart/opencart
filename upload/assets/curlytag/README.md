# curlytag

[![npm version](https://img.shields.io/npm/v/@curlytag/curlytag)](https://www.npmjs.com/package/@curlytag/curlytag)
[![npm downloads](https://img.shields.io/npm/dm/@curlytag/curlytag)](https://www.npmjs.com/package/@curlytag/curlytag)
[![license](https://img.shields.io/npm/l/@curlytag/curlytag)](LICENSE)
[![CI](https://github.com/curlytag/curlytag/actions/workflows/ci.yml/badge.svg)](https://github.com/curlytag/curlytag/actions/workflows/ci.yml)

CurlyTag - Open Source browser JavaScript template engine.

## Installation

**npm (browser project)**

```sh
npm install @curlytag/curlytag
```

```js
import { curlytag } from '@curlytag/curlytag';
```

**CDN (browser)**

```html
<html>
  <head>
    <!-- preload the module so it's ready before the script runs -->
    <link rel="modulepreload" href="https://cdn.jsdelivr.net/npm/@curlytag/curlytag/curlytag.js">
  </head>
  <body>
    <!-- your content -->

    <script type="module">
      import { curlytag } from 'https://cdn.jsdelivr.net/npm/@curlytag/curlytag/curlytag.js';

      curlytag.parse('Hello, {{ name }}!', { name: 'World' });
    </script>
  </body>
</html>
```

## Quick Start

```js
curlytag.parse('Hello, {{ name }}!', { name: 'World' });
// → Hello, World!
```

`render()` loads a `.html` template using the browser's `fetch()` API:

```js
curlytag.addPath('/views/');
const html = await curlytag.render('home', { title: 'Welcome' });
```

Full documentation: [curlytag.com](https://www.curlytag.com/)

## Development

This project uses [Vite+](https://viteplus.dev/) for formatting (Oxfmt and ESLint Stylistic), linting (Oxlint), browser tests (Vitest and Playwright), and commit hooks.

### Using Dev Container (recommended)

Open the project in VS Code and select **"Reopen in Container"**. The container will automatically:

- Install Node.js (LTS)
- Install `vp` CLI (Vite+)
- Install project dependencies

After the container starts, you're ready to work.

### Without Dev Container

1. Install [Vite+](https://viteplus.dev/guide/):

    **macOS / Linux:**

    ```bash
    curl -fsSL https://vite.plus | bash
    ```

    **Windows:**

    ```powershell
    irm https://vite.plus/ps1 | iex
    ```

    Alternatively, download and run `vp-setup.exe` from [setup.viteplus.dev](https://setup.viteplus.dev/).

> [!NOTE]
>
> `vp-setup.exe` is not yet code-signed. Your browser may show a warning when downloading. Click "..." → "Keep" → "Keep anyway" to proceed. If Windows Defender SmartScreen blocks the file when you run it, click "More info" → "Run anyway".

2. Install dependencies:

    ```bash
    vp install
    ```

3. Install the browsers for the test suite:

    ```bash
    vp exec playwright install chromium firefox webkit
    ```

4. Set up commit hooks:

    ```bash
    vp config
    ```

### Commands

```bash
vp run check                     # Format, lint, and validate JavaScript style
vp run fmt                       # Format with Oxfmt and ESLint Stylistic
vp run lint                      # Lint with Oxlint
vp test --project browser        # Run Chromium, Firefox, and WebKit tests once
```

### Test Layout

Tests are organized by feature and should stay split across small files instead of growing a shared catch-all suite.

- `tests/output/` contains plain output and `{{ }}` variable rendering tests.
- `tests/filters/` contains filter tests, with array filters in `tests/filters/array/`.
- `tests/tags/` contains tag behavior tests.
- `tests/render.test.js` and `tests/add-filter.test.js` stay at the top level because they cover broader integration behavior.

When adding a new filter or tag, prefer creating or extending a focused file in the matching directory rather than restoring cases to a monolithic `curlytag.test.js` file.

### Playground

Run the playground in development mode:

```bash
vp dev
```

Vite serves the playground from `playground/` using the config in `vite.config.ts`. Open the local URL printed by the command to work on the editors and examples.

### Development Workflow

Run tests in watch mode — tests re-run automatically on file changes:

```bash
vp test --project browser --watch
```

Run tests with a browser UI for interactive exploration:

```bash
vp test --project browser --ui --watch
```

> [!NOTE]
>
> The UI starts at `http://localhost:51204/__vitest__/` and stays open as long as the process is running. Always use `--watch` together with `--ui`, otherwise the server exits right after the test run.
