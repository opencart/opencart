#!/usr/bin/env bash
set -e

export PATH="$HOME/.vite-plus/bin:$PATH"
vp install
node_modules/.bin/playwright install chromium firefox webkit
