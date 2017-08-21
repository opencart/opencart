CHANGELOG for 2.x
=================

* 2.5.1 (2017-03-25)

    * Fix circular references when resolving with a promise which follows
      itself (#94).

* 2.5.0 (2016-12-22)

    * Revert automatic cancellation of pending collection promises once the
      output promise resolves. This was introduced in 42d86b7 (PR #36, released
      in [v2.3.0](https://github.com/reactphp/promise/releases/tag/v2.3.0)) and
      was both unintended and backward incompatible.

      If you need automatic cancellation, you can use something like:

      ```php
      function allAndCancel(array $promises)
      {
           return \React\Promise\all($promises)
               ->always(function() use ($promises) {
                   foreach ($promises as $promise) {
                       if ($promise instanceof \React\Promise\CancellablePromiseInterface) {
                           $promise->cancel();
                       }
                   }
              });
      }
      ```
    * `all()` and `map()` functions now preserve the order of the array (#77).
    * Fix circular references when resolving a promise with itself (#71).

* 2.4.1 (2016-05-03)

    * Fix `some()` not cancelling pending promises when too much input promises
      reject (16ff799).

* 2.4.0 (2016-03-31)

    * Support foreign thenables in `resolve()`.
      Any object that provides a `then()` method is now assimilated to a trusted
      promise that follows the state of this thenable (#52).
    * Fix `some()` and `any()` for input arrays containing not enough items
      (#34).

* 2.3.0 (2016-03-24)

    * Allow cancellation of promises returned by functions working on promise
      collections (#36).
    * Handle `\Throwable` in the same way as `\Exception` (#51 by @joshdifabio).

* 2.2.2 (2016-02-26)

    * Fix cancellation handlers called multiple times (#47 by @clue).

* 2.2.1 (2015-07-03)

    * Fix stack error when resolving a promise in its own fulfillment or
      rejection handlers.

* 2.2.0 (2014-12-30)

    * Introduce new `ExtendedPromiseInterface` implemented by all promises.
    * Add new `done()` method (part of the `ExtendedPromiseInterface`).
    * Add new `otherwise()` method (part of the `ExtendedPromiseInterface`).
    * Add new `always()` method (part of the `ExtendedPromiseInterface`).
    * Add new `progress()` method (part of the `ExtendedPromiseInterface`).
    * Rename `Deferred::progress` to `Deferred::notify` to avoid confusion with
      `ExtendedPromiseInterface::progress` (a `Deferred::progress` alias is
      still available for backward compatibility)
    * `resolve()` now always returns a `ExtendedPromiseInterface`.

* 2.1.0 (2014-10-15)

    * Introduce new `CancellablePromiseInterface` implemented by all promises.
    * Add new `cancel()` method (part of the `CancellablePromiseInterface`).

* 2.0.0 (2013-12-10)

    New major release. The goal is to streamline the API and to make it more
    compliant with other promise libraries and especially with the new upcoming
    [ES6 promises specification](https://github.com/domenic/promises-unwrapping/).

    * Add standalone Promise class.
    * Add new `race()` function.
    * BC break: Bump minimum PHP version to PHP 5.4.
    * BC break: Remove `ResolverInterface` and `PromiseInterface` from 
      `Deferred`.
    * BC break: Change signature of `PromiseInterface`.
    * BC break: Remove `When` and `Util` classes and move static methods to
      functions.
    * BC break: `FulfilledPromise` and `RejectedPromise` now throw an exception
      when initialized with a promise instead of a value/reason.
    * BC break: `Deferred::resolve()` and `Deferred::reject()` no longer return
      a promise.
