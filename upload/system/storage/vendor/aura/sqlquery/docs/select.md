# SELECT

## Building A Query

Build a _Select_ query using the following methods. They do not need to
be called in any particular order, and may be called multiple times.

First, create a new SELECT object with the query factory:

```php
$select = $queryFactory->newSelect();
```

## Columns

To add columns to the select, use the `cols()` method.

```php
$select->cols([
        'id',                       // column name
        'name AS namecol',          // one way of aliasing
        'col_name' => 'col_alias',  // another way of aliasing
        'COUNT(foo) AS foo_count'   // embed calculations directly
    ])
```

Other related methods:

- `removeCol($alias) : null` -- Removes a column from the SELECT.
- `hasCol($alias) : bool` -- Will a column be SELECTed with this query?
- `hasCols() : bool` -- Does the SELECT have any columns in it at all?
- `getCols() : array` -- Returns the columns named in the SELECT.


## FROM

To add a FROM clause, call the `from()` method as needed:

```php
// FROM foo, "bar" as "b"
$select
    ->from('foo')           // table name
    ->from('bar AS b');     // alias the table as desired
```

The table names will automatically be quoted for you. If you don't want
quoting applied, use the `fromRaw()` method instead.

If you want to SELECT FROM a subselect, do so by calling `fromSubSelect()`.
Pass both the subselect query string, and an alias for the subselect:

```php
// FROM (SELECT ...) AS "my_sub"
$select->fromSubSelect('SELECT ...', 'my_sub');
```

You can also pass a SELECT object as the subselect, instead of a query string.
This allows you to create an entire SELECT query and use it as a subselect.


## JOIN

To add a JOIN clause, call the `join()` method as needed:

```php
// LEFT JOIN doom AS d ON foo.id = d.foo_id
$select->join(
    'LEFT',             // the join-type
    'doom AS d',        // join to this table ...
    'foo.id = d.foo_id' // ... ON these conditions
);
```

For convenience, the methods `leftJoin()` and `innerJoin()` exist to allow you
to elmininate the join-type argument for LEFT and INNER joins, respectively.

As with FROM, you can join to a subselect using `joinSubSelect()`:

```php
// INNER JOIN (SELECT ...) AS subjoin ON subjoin.id = foo.id
$select->joinSubSelect(
    'INNER',                    // left/inner/natural/etc
    'SELECT ...',               // the subselect to join on
    'subjoin',                  // AS this name
    'subjoin.id = foo.id'       // ON these conditions
);
```

Also as with FROM, you can pass a SELECT object instead of a query string as the
subselect.

Finally, all of the `*join*()` methods take an optional final argument, a
sequential array of values to bind to sequential question-mark placeholders in
the condition clause.


## WHERE

To add WHERE clauses, call the `where()` method as needed. Subsequent calls to
`where()` will AND the condition, unless you call `orWhere()`, in which case it
will OR the condition.

```php
    ->where('bar > :bar')           // WHERE bar > :bar
    ->where('zim = :zim')           // AND zim = :ZIM
    ->orWhere('baz < :baz')         // OR baz < :baz
```

The `*where()` and `*having()` methods take a trailing trailing argument of a
placeholder-to-value array, which will be bound to the query right then.

    // bind 'zim_val' to the :zim placeholder
    ->where('zim = :zim', ['zim' => 'zim_val'])
    
You can also use `IN` conditions by binding an array to the placeholder.

```php
    ->where('bar IN (:bar)', ['bar' => [1, 2, 3]])
```

    // bind values to the :zims placeholder
    ->where('zims IN (:zims)', ['zims' => ['zim_val', 'zim_val2', 'zim_val3']])


## GROUP BY

```php
    ->groupBy(['dib'])              // GROUP BY these columns
```

## HAVING

```php
    ->having('foo = :foo')          // AND HAVING these conditions
    ->having('bar > :bar', ['bar' => 'bar_val'])  // bind 'bar_val' to the :bar placeholder
    ->orHaving('baz < :baz')        // OR HAVING these conditions
```

The `*where()` and `*having()` methods take an arbitrary number of
trailing arguments, each of which is a value to bind to a sequential question-
mark placeholder in the condition clause.

## ORDER BY

```php
    ->orderBy(['baz ASC'])          // ORDER BY these columns
```

## LIMIT, OFFSET, and Paging

```php
    ->limit(10)                     // LIMIT 10
    ->offset(40)                    // OFFSET 40
    public function page($page)
    public function getPage()
    public function setPaging($paging)
    public function getPaging()
```

## UNION

```php
    ->union()                       // UNION with a followup SELECT
    ->unionAll()                    // UNION ALL with a followup SELECT
```

## Flags

```php
    ->forUpdate()                   // FOR UPDATE
    ->distinct()                    // SELECT DISTINCT
    ->isDistinct()                  // returns true if query is DISTINCT
```

## Binding Values

```php
    ->bindValue('foo', 'foo_val')   // bind one value to a placeholder
    ->bindValues([                  // bind these values to named placeholders
        'bar' => 'bar_val',
        'baz' => 'baz_val',
    ]);
```

## Inspecting The Query


## Resetting Query Elements

The _Select_ class comes with the following methods to "reset" various clauses
a blank state. This can be useful when reusing the same query in different
variations (e.g., to re-issue a query to get a `COUNT(*)` without a `LIMIT`, to
find the total number of rows to be paginated over).

- `resetCols()` removes all columns
- `resetTables()` removes all `FROM` and `JOIN` clauses
- `resetWhere()`, `resetGroupBy()`, `resetHaving()`, and `resetOrderBy()`
  remove the respective clauses
- `resetUnions()` removes all `UNION` and `UNION ALL` clauses
- `resetFlags()` removes all database-engine-specific flags
- `resetBindValues()` removes all values bound to named placeholders

    public function reset()
    public function resetWhere()
    public function resetGroupBy()
    public function resetHaving()
    public function resetOrderBy()

## Issuing The Query

Once you have built the query, pass it to the database connection of your
choice as a string, and send the bound values along with it.

```php
// a PDO connection
$pdo = new PDO(...);

// prepare the statement
$sth = $pdo->prepare($select->getStatement());

// bind the values and execute
$sth->execute($select->getBindValues());

// get the results back as an associative array
$result = $sth->fetch(PDO::FETCH_ASSOC);
```
