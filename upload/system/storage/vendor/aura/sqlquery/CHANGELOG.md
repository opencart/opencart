# CHANGELOG 

## 2.7.1

Hygiene release: update README.

## 2.7.0

- [DOC] Numerous docblock and README updates.

- [ADD] Add various `Select::reset*()` methods. Fixes #84, #95, #94, #91.

- [FIX] On SELECT, allow OFFSET even when LIMIT not specified. Fixes #88.

- [FIX] On SELECT, allow `join*()` before `from*()`. Joins-before-from are added
  to the first from. If no from is ever added, the joins will never be built
  into the statement. Fixes #69, #90.

- [BRK] Bumped the minimum version to PHP 5.3.9 (vs 5.3.0). Fixes #74. This is
  to address a language-level bug in PHP. Technically I think this is a BC
  break, but I hope it is understandable, given that PHP 5.3.x is end-of-life,
  and that Aura.SqlQuery itself simply will not operate on versions earlier
  than that. Updated README to reflect the version requirement.


## 2.6.0

- (DOC) Docblock and README updates; in particular, add an `@method getStatement()` to the QueryInterface for IDE auto-completion.

- (ADD) Select::hasCols() reports if there are any columsn in the Select.

- (ADD) Select::getCols() gets the existing columns in the Select.

- (ADD) Select::removeCol() removes a previously-added column.

- (FIX) Select::reset() now properly resets the table refs for a UNION.

- (FIX) Select::forUpdate() is now fluent.

## 2.5.0

- Docblock and README updates

- The Common\Select class, when binding values from a subselect, now checks for
  `instanceof SubselectInterface` instead of `self`; the Select class now
  implements SubselectInterface, so this should not be a BC break.

- Subselects bound as where/having/etc conditions should now retain ?-bound
  params.

## 2.4.2

This release modifies the testing structure and updates other support files.

## 2.4.1

This release fixes Insert::addRows() so that adding only one row generates the correct SQL statement.

## 2.4.0

This release incorporates two feature additions and one fix.

- ADD: The _Insert_ objects now support multiple-row inserts with the new `addRow()` and `addRows()` methods.

- ADD: The MySQL _Insert_ object now supports `ON DUPLICATE KEY UPDATE` functionality with the new `onDuplicateKeyUpdate*()` methods.

- FIX: The _Select_ methods regarding paging now interact better with LIMIT and OFFSET; in particular, both `setPaging()` now re-calculates the LIMIT and OFFSET values.

## 2.3.0

This release has several new features.

1. The various `join()` methods now have an extra `$bind` param that allows you to bind values to ?-placeholders in the condition, just as with `where()` and `having()`.

2. The _Select_ class now tracks table references internally, and will throw an exception if you try to use the same table name or alias more than once.

3. The method `getStatement()` has been added to all queries, to allow you to get the text of the statement being built. Among other things, this is to avoid exception-related blowups related to PHP's string casting.

4. When binding a value to a sequential placeholder in `where()`, `having()`, etc, the _Select_ class now examind the value to see if it is a query object. If so, it converts the object to a string and replaces the ?-placeholder inline with the string instead of attempting to bind it proper. It also binds the existing sequential placholder values into the current _Select_ in a non-conflicting fashion. (Previously, no binding from the sub-select took place at all.)

5. In `fromSubSelect()` and `joinSubSelect()`, the _Select_ class now binds the sub-select object sequential values to the current _Select_ in a non-conflicting fashion.  (Previously, no binding from the sub-select took place at all.)

The change log follows:

- REF: Extract rebuilding of condition and binding sequential values.

- FIX: Allow binding of values as part of join() methods. Fixes #27.

- NEW: Method Select::addTableRef(), to track table references and prevent double-use of aliases. Fixes #38.

- REF: Extract statement-building to AbstractQuery::getStatement() method. Fixes #30.

- FIX: #47, if value for sequential placeholder is a Query, place it as a string inline

- ADD: Sequential-placeholder prefixing

- ADD: bind values from sub-selects, and modify indenting

- ADD: QueryFactory now sets the sequntial bind prefix

- FIX: Fix line endings in queries to be sure tests will pass on windows and mac. Merge pull request #53 from ksimka/fix-tests-remove-line-endings: Fixed tests for windows.

- Merge pull request #50 from auraphp/bindonjoin: Allow binding of values as part of join() methods.

- Merge pull request #51 from auraphp/aliastracking: Add table-reference tracking to disallow duplicate references.

- Merge pull request #52 from auraphp/bindsubselect. Bind Values From Sub-Selects.

- DOC: Update documentation and support files.

## 2.2.0

To avoid mixing numbered and names placeholders, we now convert numbered ? placeholders in where() and having() to :_#_ named placeholders. This is because PDO is really touchy about sequence numbers on ? placeholders. If we have bound values [:foo, :bar, ?, :baz], the ? placeholder is not number 1, it is number 3. As it is nigh impossible to keep track of the numbering when done out-of-order, we now do a braindead check on the where/having condition string to see if it has ? placholders, and replace them with named :_#_ placeholders, where # is the current count of the $bind_values array.


## 2.1.0

- ADD: Select::fromRaw() to allow raw FROM clause strings.

- CHG: In Select, quote the columns at build time, not add time.

- CHG: In Select, retain columns keyed on their aliases (when given).

- DOC: Updates to README and docblocks.

## 2.0.0

Initial 2.0 stable release.

- The package has been renamed from Sql_Query to SqlQuery, in line with the new Aura naming standards.

- Now compatible with PHP 5.3!

- Refactored traits into interfaces (thanks @mindplay-dk).

- Refactored the internal build process (thanks again @mindplay-dk).

- Added Select::leftJoin()/innerJoin() methods (thanks @stanlemon).

- Methods bindValue() and bindValues() are now fluent (thanks @karikt).

- Select now throws an exception when there are no columns selected.

- In joins, the condition type (ON or USING) may now be part of the condition.

- Extracted new class, Quoter, for quoting identifer names.

- Extracted new class, AbstractDmlQuery, for Insert/Update/Delete queries.

- Select::cols() now accepts `colname => alias` pairs mixed in with sequential colname values.

- Added functionality to map last-insert-id names to alternative sequence names, esp. for Postgres and inherited/extended tables. Cf. QueryFactory::setLastInsertIdNames() and Insert::setLastInsertIdNames().

## 2.0.0-beta1

Initial 2.0 beta release.

