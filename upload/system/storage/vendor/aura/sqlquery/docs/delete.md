# DELETE

Build a _Delete_ query using the following methods. They do not need to
be called in any particular order, and may be called multiple times.

```php
$delete = $queryFactory->newDelete();

$delete
    ->from('foo')                   // FROM this table
    ->where('zim = :zim')           // AND WHERE these conditions
    ->orWhere('gir = :gir')         // OR WHERE these conditions
    ->bindValue('bar', 'bar_val')   // bind one value to a placeholder
    ->bindValues([                  // bind these values to the query
        'baz' => 99,
        'zim' => 'dib',
        'gir' => 'doom',
    ]);
```

Once you have built the query, pass it to the database connection of your
choice as a string, and send the bound values along with it.

```php
// the PDO connection
$pdo = new PDO(...);

// prepare the statement
$sth = $pdo->prepare($delete->getStatement())

// execute with bound values
$sth->execute($delete->getBindValues());
```
