# UPDATE

Build an _Update_ query using the following methods. They do not need to
be called in any particular order, and may be called multiple times.

```php
$update = $queryFactory->newUpdate();

$update
    ->table('foo')                  // update this table
    ->cols([                        // bind values as "SET bar = :bar"
        'bar',
        'baz',
    ])
    ->set('ts', 'NOW()')            // raw value as "(ts) VALUES (NOW())"
    ->where('zim = :zim')           // AND WHERE these conditions
    ->where('gir = :gir', ['gir' => 'gir_val'])      // bind this value to the condition
    ->orWhere('gir = :gir')         // OR WHERE these conditions
    ->bindValue('bar', 'bar_val')   // bind one value to a placeholder
    ->bindValues([                  // bind these values to the query
        'baz' => 99,
        'zim' => 'dib',
        'gir' => 'doom',
    ]);
```

The `cols()` method allows you to pass an array of key-value pairs where the
key is the column name and the value is a bind value (not a raw value):

```php
$update = $queryFactory->newUpdate();

$update->table('foo')           // update this table
    ->cols([                    // update these columns and bind these values
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
$sth = $pdo->prepare($update->getStatement())

// execute with bound values
$sth->execute($update->getBindValues());
```
