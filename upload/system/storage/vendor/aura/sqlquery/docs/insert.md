# INSERT

## Single-Row Insert

Build an _Insert_ query using the following methods. They do not need to
be called in any particular order, and may be called multiple times.

```php
$insert = $queryFactory->newInsert();

$insert
    ->into('foo')                   // INTO this table
    ->cols([                        // bind values as "(col) VALUES (:col)"
        'bar',
        'baz',
    ])
    ->set('ts', 'NOW()')            // raw value as "(ts) VALUES (NOW())"
    ->bindValue('foo', 'foo_val')   // bind one value to a placeholder
    ->bindValues([                  // bind these values
        'bar' => 'foo',
        'baz' => 'zim',
    ]);
```

The `cols()` method allows you to pass an array of key-value pairs where the
key is the column name and the value is a bind value (not a raw value):

```php
$insert = $queryFactory->newInsert();

$insert->into('foo')             // insert into this table
    ->cols([                     // insert these columns and bind these values
        'foo' => 'foo_value',
        'bar' => 'bar_value',
        'baz' => 'baz_value',
    ]);
```

Once you have built the query, pass it to the database connection of your
choice as a string, and send the bound values along with it.

```php
// the PDO connection
$pdo = new PDO(...);

// prepare the statement
$sth = $pdo->prepare($insert->getStatement());

// execute with bound values
$sth->execute($insert->getBindValues());

// get the last insert ID
$name = $insert->getLastInsertIdName('id');
$id = $pdo->lastInsertId($name);
```

## Multiple-Row (Bulk) Insert

If you want to do a multiple-row or bulk insert, call the `addRow()` method
after finishing the first row, then build the next row you want to insert. The
columns in the rows after the first will be inserted in the same order as the
first row.

```php
$insert = $queryFactory->newInsert();

// insert into this table
$insert->into('foo');

// set up the first row
$insert->cols([
    'bar' => 'bar-0',
    'baz' => 'baz-0'
]);
$insert->set('ts', 'NOW()');

// set up the second row. the columns here are in a different order
// than in the first row, but it doesn't matter; the INSERT object
// keeps track and builds them the same order as the first row.
$insert->addRow();
$insert->set('ts', 'NOW()');
$insert->cols([
    'bar' => 'bar-1',
    'baz' => 'baz-1'
]);

// set up further rows ...
$insert->addRow();
// ...

// execute a bulk insert of all rows
$pdo = new PDO(...);
$sth = $pdo->prepare($insert->getStatement());
$sth->execute($insert->getBindValues());
```

> N.b.: If you add a row and do not specify a value for a column that was
> present in the first row, the _Insert_ will throw an exception.

If you pass an array of column key-value pairs to `addRow()`, they will be
bound to the next row, thus allowing you to skip setting up the first row
manually with `col()` and `cols()`:

```php
// set up the first row
$insert->addRow([
    'bar' => 'bar-0',
    'baz' => 'baz-0'
]);
$insert->set('ts', 'NOW()');

// set up the second row
$insert->addRow([
    'bar' => 'bar-1',
    'baz' => 'baz-1'
]);
$insert->set('ts', 'NOW()');

// etc.
```

If you only need to use bound values, and do not need to set raw values, and
have the entire data set as an array already, you can use `addRows()` to add
them all at once:

```php
$rows = [
    [
        'bar' => 'bar-0',
        'baz' => 'baz-0'
    ),
    [
        'bar' => 'bar-1',
        'baz' => 'baz-1'
    ],
];
$insert->addRows($rows);
```

> N.b.: SQLite 3.7.10 and earlier do not support the "standard" multiple-row
> insert syntax. Thus, bulk inserts with _Insert_ object will not work on those
> earlier versions of SQLite. We suggest wrapping multiple INSERT operations
> with a transaction as an alternative.
