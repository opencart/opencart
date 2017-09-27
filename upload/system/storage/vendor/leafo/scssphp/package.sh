#!/bin/sh

# creates tar.gz for current version

TARGET_DIR="site/www/src"

VERSION=`./pscss -v | sed -n 's/^v\(.*\)$/\1/p'`
OUT_DIR="tmp/scssphp"
TMP=`dirname $OUT_DIR`

mkdir -p $OUT_DIR
tar -c `git ls-files` | tar -C $OUT_DIR -x 

rm $OUT_DIR/.gitignore
rm $OUT_DIR/package.sh
rm $OUT_DIR/todo
rm -r $OUT_DIR/site

OUT_PATH="$TARGET_DIR/scssphp-$VERSION.tar.gz"
tar -czf "$OUT_PATH" -C $TMP scssphp/
echo "Wrote $OUT_PATH"

rm -r $TMP

