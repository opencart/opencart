#!/usr/bin/env bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

set -e

cd $DIR/static

php -S 0.0.0.0:8080 &

PID=$!

function cleanup() {
  kill $PID
}

trap cleanup EXIT

cd $DIR

yarn testcafe firefox:headless e2e/
