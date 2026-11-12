# MySQL Additions

These 'mysql' query objects have additional MySQL-specific behaviors.

## SELECT

- `calcFoundRows()` to add or remove `SQL_CALC_FOUND_ROWS` flag
- `cache()` to add or remove `SQL_CACHE` flag
- `noCache()` to add or remove `SQL_NO_CACHE` flag
- `bigResult()` to add or remove `SQL_BIG_RESULT` flag
- `smallResult()` to add or remove `SQL_SMALL_RESULT` flag
- `bufferResult()` to add or remove `SQL_BUFFER_RESULT` flag
- `highPriority()` to add or remove `HIGH_PRIORITY` flag
- `straightJoin()` to add or remove `STRAIGHT_JOIN` flag

## INSERT

- `orReplace()` to add or remove `OR REPLACE`
- `highPriority()` to add or remove `HIGH_PRIORITY` flag
- `lowPriority()` to add or remove `LOW_PRIORITY` flag
- `ignore()` to add or remove `IGNORE` flag
- `delayed()` to add or remove `DELAYED` flag

In addition, the MySQL _Insert_ object has support for `ON DUPLICATE KEY UPDATE`:

- `onDuplicateKeyUpdate($col, $raw_value)` sets a raw value
- `onDuplicateKeyUpateCol($col, $value)` is a `col()` equivalent for the update
- `onDuplicateKeyUpdateCols($cols)` is a `cols()`equivalent for the update

Placeholders for bound values in the `ON DUPLICATE KEY UPDATE` portions will be
automatically suffixed with `__on_duplicate key` to deconflict them from the
insert placeholders.

## UPDATE

- `lowPriority()` to add or remove `LOW_PRIORITY` flag
- `ignore()` to add or remove `IGNORE` flag
- `where()` and `orWhere()` to add WHERE conditions flag
- `orderBy()` to add an ORDER BY clause flag
- `limit()` to set a LIMIT count

## DELETE

- `lowPriority()` to add or remove `LOW_PRIORITY` flag
- `ignore()` to add or remove `IGNORE` flag
- `quick()` to add or remove `QUICK` flag
- `orderBy()` to add an ORDER BY clause
- `limit()` to set a LIMIT count
