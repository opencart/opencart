# End-to-End Testing with testRigor
This directory contains the end-to-end testing setup for OpenCart using testRigor.

## Overview

testRigor is an AI-powered end-to-end testing platform that lets you write tests in plain English. 
This setup enables automated testing of OpenCart's admin and storefront flows from a user’s perspective.

```
tests/e2e/
├── README.md
├── testcases/          10 test cases
└── reusable-rules/     10 shared rules referenced by the test cases
```

## Coverage

| Test case | Covers |
|---|---|
| `TC-001-HOME-SMOKE` | Home page header, currency switcher, category menu, footer |
| `TC-002-CATALOG-MAJ` | Header search, product comparison, empty-result handling |
| `TC-003-PDP-CRIT` | Required product options block add-to-cart |
| `TC-004-PDP-MAJ` | Review submission and wish list, as a logged-in customer |
| `TC-005-CHECKOUT-CRIT` | Cart quantity update and removal, then guest COD checkout |
| `TC-006-CHECKOUT-CRIT` | Register during checkout, place order, verify order history |
| `TC-007-ACCOUNT-MAJ` | Register, edit, change password, re-login, address book, reset |
| `TC-008-ADMIN-CRIT` | Admin access control: unauthenticated, invalid, valid, logout |
| `TC-009-ADMIN-MAJ` | Product create → storefront → edit price → verify → delete |
| `TC-010-MARKETING-MAJ` | Admin creates a percentage coupon, shopper redeems it |

## How it works

`.github/workflows/testRigor_e2e_testing.yml` starts OpenCart and MySQL from
`docker-compose.yml`, waits for the first-boot installer (`upload/install/cli_install.php`),
then runs the suite identified by `TESTRIGOR_SUITE_ID` against that container over the CLI's
localhost tunnel.

**This directory is the source of truth.** Test cases and rules are uploaded from here on
every run, so edits made in the testRigor UI do not survive.

### Running the suite without changing it

`--test-cases-path` and `--rules-path` load the local files for that run only. Adding
`--explicit-mutations` makes the local set authoritative and **replaces** the stored suite —
test cases absent from the upload are deleted. The workflow passes it only on a push to
`4.x.x.x`, so a pull request never rewrites the shared suite before review.

A parse error anywhere in the upload aborts the whole run and leaves the suite untouched, so
a broken file cannot half-apply.

### URLs and credentials

The tests reach the store through the `storeUrl` and `adminUrl` test data, and log in with
`adminUsername` / `adminPassword`. CI overrides all four for the duration of the run with
`--variables-path`, pointing them at the container it just started. The values stored in the
suite are not modified, so a manual run from the testRigor UI still targets whatever
long-lived environment is configured there.

**A variables file replaces the variable set, it does not merge into it.** Send every key
the tests need, not just the ones being changed — a file containing only `adminPassword`
leaves `storeUrl` and `adminUrl` undefined, and every navigation silently goes nowhere. The
failure looks nothing like a URL problem: it surfaces as `Can't find button element by
descriptor 'Login'` on a blank page.

Note also that `--url` combined with `--explicit-mutations` appears to persist into the
suite's configured base URL, unlike the variables file. If manual runs start landing on the
wrong host, check the suite's URL setting.

## Naming

Test case files are named `TC-<NNN>-<AREA>-<SEVERITY> — <Area> — <description>.txt`, and the
filename becomes the test case name in testRigor. The number leads so the suite lists in
shopper-journey order.

- **Area**: `HOME`, `CATALOG`, `PDP`, `CHECKOUT`, `ACCOUNT`, `ADMIN`, `MARKETING`
- **Severity**: `SMOKE` (fast canary), `CRIT` (revenue paths and access control),
  `MAJ` (everything else)

Reusable rules are named after the rule itself, lowercase-kebab. **Rules take no
parameters** — a rule that needs a value reads a stored value the caller sets first:

```
save "iPhone" as "productName"
product-add-to-cart
```

Parameterised rule names (a `_paramName_` placeholder in the filename, invoked as
`product-add-to-cart-"iPhone"`) are rejected by the CLI with
`Unrecognizable instruction, can't detect action`.

## Test data

Four keys, all configured in the suite's Test Data section and all overridden by CI for the
duration of a run:

| Key | Purpose | CI value |
|---|---|---|
| `storeUrl` | Storefront base URL | the container just started |
| `adminUrl` | Admin panel URL | the container just started |
| `adminUsername` | Admin account | secret `ADMIN_USERNAME`, default `admin` |
| `adminPassword` | Admin password | secret `ADMIN_PASSWORD`, default `admin` |

The defaults match what `docker-compose.yml` installs, so CI needs no secrets to run against
the container. Set the secrets only when pointing the workflow at an environment provisioned
with different credentials.

**Customer passwords are not test data.** `customer-register-new` generates one per run and
saves it as `currentPassword`; the account lifecycle test generates a second one when it
changes the password and re-saves `currentPassword` to match. The generated form is
`[A-Z][a-z]{6}[0-9]{2}[!@#]` — upper, lower, digit and symbol — so it satisfies any
combination of OpenCart's configurable password rules (`config_password_*`). Nothing in test
data is ever written to.

## Running locally

### First time: a testRigor account and suite

1. Sign up at [testRigor](https://www.testrigor.com/) and choose the **Public Open Source**
   plan.
2. Verify your email, log in, and create a test suite.
3. Add the four test data keys listed above under the suite's **Test Data**.

The suite ID is in its URL — for
`https://app.testrigor.com/test-suites/oJvBxgjY5A8Pw48hj/test-cases` it is
`oJvBxgjY5A8Pw48hj`.

### Running the suite

```bash
# Start OpenCart from this checkout (first boot runs the installer, ~2 minutes)
docker compose up -d --build opencart mysql
curl -fsS -o /dev/null -L http://localhost && echo "OpenCart is up"

# Run the suite. Without --explicit-mutations this uses your local files
# without overwriting the stored suite.
npx -y testrigor-cli test-suite run <SUITE_ID> \
  --token <CI_TOKEN> \
  --localhost \
  --url "http://localhost" \
  --test-cases-path "tests/e2e/testcases/**/*.txt" \
  --rules-path "tests/e2e/reusable-rules/**/*.txt" \
  --verbose

docker compose down -v
rm -f upload/install.lock
```

`CI_TOKEN` comes from **CI/CD Integration** in the suite sidebar. Add `--async` to start a
run without waiting for it.

If the suite's test data points somewhere other than the container, override it for the run
with a `--variables-path` file, as CI does. Include **all four** keys — the file replaces the
variable set rather than merging into it:

```json
{
  "storeUrl": "http://localhost",
  "adminUrl": "http://localhost/admin",
  "adminUsername": "admin",
  "adminPassword": "admin"
}
```

The admin is at <http://localhost/admin> with the credentials in `docker-compose.yml`.
`upload/install.lock` is written by the container on first boot and is local state.

## CI configuration

### Required

- Variable `TESTRIGOR_SUITE_ID`: the testRigor test suite identifier
- Secret `TESTRIGOR_CI_TOKEN`: the testRigor auth token, from the suite's CI/CD Integration
  page

Without both, the job skips with a notice; expected on forks and on pull requests from
forks, which never receive secrets.

### Optional

- Secret `ADMIN_USERNAME`: defaults to `admin`
- Secret `ADMIN_PASSWORD`: defaults to `admin`

The defaults match the credentials `docker-compose.yml` installs the store with, so CI works
without them. Set them only when running the workflow against a store provisioned
differently.

Automatic runs are limited to `4.x.x.x`: pushes to it, and pull requests targeting it from
any branch. A manual **Run workflow** is allowed from any branch except `master` and
`3.0.x.x`, which are on a different major and cannot pass, so a topic branch based on
`4.x.x.x` can be dispatched directly.

## Learn more

- [testRigor documentation](https://testrigor.com/docs/)
- [testRigor CLI reference](https://testrigor.com/command-line)
