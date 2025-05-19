#!/usr/bin/env bash

set -e

echo "= Cleaning previous builds"
rm -rf themes/daux/js
rm -rf daux_libraries/fonts/*
rm -rf daux_libraries/katex.min.css

echo "= Linting"
yarn lint:js

echo "= Adding Katex"
cp node_modules/katex/dist/katex.min.css daux_libraries/katex.min.css
cp -r node_modules/katex/dist/fonts/* daux_libraries/fonts

echo "= Building CSS"
yarn crafty run

echo "= Building Daux Theme JS"
yarn webpack
