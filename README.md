# OpenCart

[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.1-8892BF.svg?style=flat-square)](https://php.net/)
[![GitHub release](https://img.shields.io/github/v/release/opencart/opencart)](https://github.com/opencart/opencart)
[![Lint](https://github.com/opencart/opencart/actions/workflows/Lint.yml/badge.svg)](https://github.com/opencart/opencart/actions/workflows/Lint.yml)

OpenCart is a free, open source e-commerce platform for building and managing online stores.

This repository currently tracks OpenCart `4.1.0.4`. The deployable application lives in `upload/`; the repository root contains documentation, tooling, Docker assets, and CI configuration.

## Requirements

- PHP 8.1 or newer
- A supported database server and driver (`mysqli` is the default installation path)
- PHP extensions: `curl`, `gd`, `openssl`, `zip`, `zlib`, and either `mbstring` or `iconv`
- `file_uploads` enabled
- `session.auto_start` disabled
- Write access to the runtime storage directories and to the two `config.php` files during installation

## Repository layout

- `upload/` deployable OpenCart application
- `docs/` project documentation
- `tools/` developer tooling (`phpstan.phar`, `php-cs-fixer.phar`, Docker build context, API tooling)
- `.github/workflows/Lint.yml` CI checks

## Installation

- Fresh install: see [INSTALL.md](INSTALL.md)
- In-place upgrade: see [UPGRADE.md](UPGRADE.md)

## Local Docker environment

From the repository root:

```bash
docker compose up -d
```

The compose stack:

- builds the local PHP/Apache image from `tools/Dockerfile`
- mounts `./upload` into the container
- provisions MySQL 5.7
- runs `upload/install/cli_install.php` on first boot
- exposes OpenCart at `http://localhost/` and Adminer at `http://localhost:8080/`

Default bootstrap credentials:

- Admin username: `admin`
- Admin password: `admin`
- Database: `opencart`
- MySQL root password: `opencart`

The first successful bootstrap creates `upload/install.lock` to prevent repeated CLI installs.

## Development

Install or refresh dependencies from the repository root:

```bash
composer install
```

Composer installs runtime dependencies into `upload/system/storage/vendor/` and autoloads the local PHPStan extension from `tools/phpstan/`.

Common checks:

```bash
find upload -type f -name "*.php" ! -path 'upload/system/storage/vendor/*' -exec php -l -n {} +
php tools/php-cs-fixer.phar fix --dry-run --diff
php tools/phpstan.phar analyze --no-progress --memory-limit=1G
```

The default CI workflow runs syntax linting, PHP CS Fixer, and PHPStan across PHP 8.1-8.5.

## Reporting issues

Before opening a core bug report:

1. Search existing GitHub issues and discussions.
2. Confirm the problem is reproducible on a clean OpenCart install.
3. Rule out third-party extensions, themes, and server-specific misconfiguration.

Security issues should not be disclosed publicly before they are reproducible and triaged.

## Contributing

Fork the repository, make focused changes, and open a pull request with a clear description of the problem, fix, and any behavioural impact.

Please keep changes aligned with the existing project conventions and run the local quality checks before submitting.

## License

OpenCart is licensed under the [GNU General Public License version 3](LICENSE.md).

## Links

- [OpenCart website](https://www.opencart.com/)
- [OpenCart forums](https://forum.opencart.com/)
- [GitHub issues](https://github.com/opencart/opencart/issues)
- [GitHub discussions](https://github.com/opencart/opencart/discussions)
- [Change log](CHANGELOG.md)
