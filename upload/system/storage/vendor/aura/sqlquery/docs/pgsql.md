# PostgreSQL Additions

These 'pgsql' query objects have additional PostgreSQL-specific behaviors.

## INSERT

- `returning()` to add a `RETURNING` clause

### Last Insert IDs and Table Inheritance

PostgreSQL determines the default sequence name for the last inserted ID by
concatenating the table name, the column name, and a `seq` suffix, using
underscore separators (e.g. `table_col_seq`).

However, when inserting into an extended or inherited table, the parent table is
used for the sequence name, not the child (insertion) table. This package allows
you to override the default last-insert-id name with the method
`setLastInsertIdNames()` on both _QueryFactory_ and the _Insert_ object itself.
Pass an array of `inserttable.col` keys mapped to `parenttable_col_seq` values,
and the _Insert_ object will use the mapped sequence names instead of the
default names.

```php
$queryFactory->setLastInsertIdNames([
    'child.id' => 'parent_id_seq'
]);

$insert = $queryFactory->newInsert();
$insert->into('child');
// ...
$seq = $insert->getLastInsertIdName('id');
```

The `$seq` name is now `parent_id_seq`, not `child_id_seq` as it would have been
by default.

## UPDATE

- `returning()` to add a `RETURNING` clause

## DELETE

- `returning()` to add a `RETURNING` clause
