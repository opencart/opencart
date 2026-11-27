# SQL Server Additions

The 'sqlsrv' query objects have no additional methods specific to Microsoft SQL
Server. However, `limit()` and `offset()` behaviors are somewhat modified.

In general, `limit()` and `offset()` with Microsoft SQL Server are best
combined with `orderBy()`. The `limit()` and `offset()` methods on the
Microsoft SQL Server query objects will generate sqlsrv-specific variations of
`LIMIT ... OFFSET`:

- If only a `LIMIT` is present, it will be translated as a `TOP` clause.

- If both `LIMIT` and `OFFSET` are present, it will be translated as an
  `OFFSET ... ROWS FETCH NEXT ... ROWS ONLY` clause. In this case there *must*
  be an `ORDER BY` clause, as the limiting clause is a sub-clause of `ORDER
  BY`.
