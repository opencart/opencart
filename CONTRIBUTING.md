# Contributing to OpenCart

Thanks for your interest in improving OpenCart! This guide explains the workflow, tooling, and expectations we follow in this repository so your pull requests can be reviewed and merged quickly.

## 1. Before You Start
- **Search first:** look through existing [issues](https://github.com/opencart/opencart/issues), [discussions](https://github.com/opencart/opencart/discussions), and the [forum](https://forum.opencart.com/viewforum.php?f=201) before opening a new ticket.
- **Security reports:** never open public issues for vulnerabilities. Follow the instructions in `README.md` ("Reporting a bug" section) and contact the moderator team privately.
- **Large features:** discuss significant changes in an issue or discussion thread before coding to confirm direction and scope.

## 2. Development Environment
OpenCart 4.1 requires PHP 8.0+.

### Docker workflow (recommended)
Use the supplied `Makefile` wrappers around `docker compose`:
```bash
make init        # first run only – copies docker/.env.docker.example
make build       # build PHP/Apache/MySQL images
make up          # start the stack (add `profiles="redis"` etc. to include extras)
make php         # open a shell in the PHP container as www-data
make logs        # tail all service logs
make down        # stop and remove containers
```
Once the stack is running, visit `http://localhost`, complete the installer (creates `upload/config.php` + `upload/admin/config.php`), and delete `upload/install/`.

### Native / custom setups
If you run OpenCart without Docker you must still provide:
- PHP 8.0+ with extensions: `curl`, `gd`, `zip` (see `INSTALL.md` for OS-specific notes).
- MySQL (or compatible) database.
- Web server pointing to `upload/` as the document root.

Install PHP dependencies locally by running:
```bash
composer install --no-interaction
```
This installs libraries into `upload/system/storage/vendor/` as configured in `composer.json`.

## 3. Coding Standards & Tooling
Run the same checks as CI before opening a PR (see `.github/workflows/Lint.yml`). From the repo root:
```bash
# Syntax lint (skips bundled vendor code)
find upload -type f -name "*.php" ! -path 'upload/system/storage/vendor/*' -exec php -l -n {} +

# Static analysis (level 6, uses tools/phpstan.phar)
php tools/phpstan.phar analyze --no-progress

# Code style (php-cs-fixer)
php tools/php-cs-fixer.phar fix --dry-run --diff --ansi
```
If php-cs-fixer reports issues, run it again without `--dry-run` to apply the fixes and commit the result.

### Framework conventions
- Follow the custom MVC-A pattern (`controller`, `model`, `view`, `language`) described in `docs/developer/` and summarized in `.github/copilot-instructions.md`.
- Use the loader/registry services (`$this->load`, `$this->config`, `$this->db`, etc.) instead of manual includes.
- Reference existing models (e.g., `catalog/model/catalog/product`) before writing raw SQL.
- Namespace files under `Opencart\{Application}\...` according to their location (`catalog`, `admin`, `extension`).

## 4. Git & Pull Requests
1. **Fork** the repository and clone your fork.
2. **Create a branch** from `master`, e.g. `feature/cart-api` or `fix/seo-url-regression`.
3. **Commit clearly:** use imperative subject lines and include context in the body when needed.
4. **Keep diffs focused:** avoid formatting-only changes unless that is the purpose of the PR.
5. **Rebase** onto the latest `master` before opening or updating your PR to keep history clean.

When opening a PR:
- Fill out the template (if provided) with motivation, testing notes, and any regressions mitigated.
- Reference related issues using `Fixes #1234` where appropriate.
- Attach screenshots or short clips for UI changes.

## 5. Translations
String translations are managed through Crowdin (`crowdin.yml`). Please submit translation updates via the [OpenCart Crowdin project](https://crowdin.com/project/opencart) instead of direct pull requests. PRs that edit `upload/catalog/language/*` or `upload/admin/language/*` files will generally be closed in favor of the official localization workflow.

## 6. Documentation & Developer Resources
- **Install/upgrade:** `INSTALL.md`, `UPGRADE.md`.
- **Docker details:** `README.md` and `docker/` directory.
- **Framework internals:** `docs/developer/**` (module guide, loader usage, etc.).
- **Coding standards:** https://github.com/opencart/opencart/wiki/Coding-standards
- **Module development:** https://docs.opencart.com/en-gb/developer/module/

## 7. Release & Versioning Expectations
The project follows `MAJOR.MINOR.FEATURE.PATCH` (see `README.md`). If your change breaks backward compatibility or introduces new extension points, call it out explicitly in the PR description and update `CHANGELOG.md` when requested by maintainers.

## 8. Submitting Bug Fixes
- Add regression tests when practical (e.g., covering model/controller edge cases).
- Explain reproduction steps in the PR and link any forum/issue references.
- Confirm whether the fix impacts both `catalog/` and `admin/` contexts.

## 9. Feature Requests & Extensions
Core stays intentionally slim. Consider whether your idea belongs in an extension under `upload/extension/` and follow the module guidelines. Avoid modifying `system/` core classes unless directed; prefer events or extension hooks.

## 10. Code of Conduct
Be respectful in issues, PRs, discussions, and community forums. The maintainer team may close reports that are abusive, spammy, or unrelated to OpenCart core.

---
We appreciate every contribution—documentation, bug fixes, features, translations, and triage all help keep OpenCart healthy. Thank you for helping us build an excellent e-commerce platform!
