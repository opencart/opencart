# OpenCart

## Overview

OpenCart is a free open source ecommerce platform for online merchants. OpenCart provides a professional and reliable foundation to build a successful online store.

[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.0-8892BF.svg?style=flat-square)](https://php.net/)
[![GitHub release](https://img.shields.io/github/v/release/opencart/opencart)](https://github.com/opencart/opencart/releases)
[![Lint](https://github.com/opencart/opencart/actions/workflows/Lint.yml/badge.svg)](https://github.com/opencart/opencart/actions/workflows/Lint.yml)

## How to install

Please read the [installation instructions](INSTALL.md) included in the repository or download file.

## How to upgrade from previous versions

Please read the [upgrading instructions](UPGRADE.md) included in the repository or download file.

## Reporting a bug

Read the instructions below before you create a bug report.

1. Search the [OpenCart forum](https://forum.opencart.com/viewforum.php?f=201), ask the community if they have seen the bug or know how to fix it.
2. Check all open and closed issues on the [GitHub bug tracker](https://github.com/opencart/opencart/issues).
3. If your bug is related to the OpenCart core code then please create a bug report on GitHub.
4. READ the [changelog for the master branch](https://github.com/opencart/opencart/blob/master/CHANGELOG.md)
5. Use [Google](https://www.google.com) to search for your issue.
6. Make sure that your bug/issue is not related to your hosting environment.

If you are not sure about your issue, it is always best to ask the community on our [bug forum thread](https://forum.opencart.com/viewforum.php?f=201)

**Important!**

- If your bug report is not related to the core code (such as a 3rd party module or your server configuration) then the issue will be closed without a reason. You must contact the extension developer, use the forum or find a commercial partner to resolve a 3rd party code issue.
- If you would like to report a serious security bug please PM an OpenCart moderator/administrator on the forum. Please do not report concept/ideas/unproven security flaws - all security reports are taken seriously but you must include the EXACT details steps to reproduce it. Please DO NOT post security flaws in a public location.

## How to contribute

Read the full [CONTRIBUTING guide](CONTRIBUTING.md) for environment setup, coding standards, and pull-request expectations.

Fork the repository, make your changes, and [submit a pull request](https://github.com/opencart/opencart/wiki/Creating-a-pull-request). Please be explicit in commit and PR descriptions—empty or unclear messages may be rejected.

Your code must follow the [OpenCart coding standards](https://github.com/opencart/opencart/wiki/Coding-standards). Our automated scanners (syntax lint, PHPStan, php-cs-fixer) must pass before a PR can be merged.

## Local Development with Docker

This project includes a Docker-based environment for local development.

### Prerequisites

* You must have Docker and Docker Compose installed on your machine. If you're installing `Composer` separately from Docker, make sure to install `docker-compose-v2`. Using the older `docker-compose` version may cause the build process (specifically the make build step) to fail. [Docker Desktop](https://www.docker.com/products/docker-desktop/) is the easiest way to get them.
* You must have `make` installed on your system (usually pre-installed on macOS and Linux distributions).

> [!IMPORTANT]
>
> **For Windows Users:**
> It is **strongly recommended** to use the WSL 2 (Windows Subsystem for Linux) backend for Docker Desktop.
> **You should clone this project _inside_ your WSL distribution (e.g., Ubuntu 24.04) for best performance.**
> Access your project via `\\wsl$\Ubuntu-24.04\home\youruser\opencart` from Windows Explorer if needed.
> Without WSL 2, file system performance will be extremely slow, making the application nearly unusable.
> Docker Desktop will typically prompt you to enable WSL 2 during installation.

> [!NOTE]
>
> OpenCart itself does **not** use any `.env` file for its configuration.
> The provided `.env.docker` file is **only** for configuring the Docker Compose environment.
> To avoid confusion with classic development workflows, this file is named `.env.docker` and placed inside the `docker` directory.

### Getting Started

1. Clone the repository to your local machine.
2. Initialize the project:
    ```bash
    make init
    ```
3. Build the images:
    ```bash
    make build
    ```
4. Start all services:
    ```bash
    make up
    ```

After the process is complete, your OpenCart store will be available at `http://localhost`.

### Common Commands

* **To stop the environment:**
    ```bash
    make down
    ```
* **To view the logs from all services:**
    ```bash
    make logs
    ```
* **To enter the PHP container:**
    ```bash
    make php
    ```
* **To see all available commands:**
    ```bash
    make help
    ```

### Changing the PHP Version

The environment uses PHP 8.4 by default.
You can easily switch to a different version by editing the `PHP_VERSION` variable in the `docker/.env.docker` file.

For example, to use PHP 8.2, open `docker/.env.docker` and set:

```env
PHP_VERSION=8.2
```

After changing the version, rebuild the images:

```bash
make build
```

### Using Docker Compose Profiles for Optional Services

By default, only the core services (`apache`, `php`, `mysql`) are started.
Optional services such as **Adminer**, **Redis**, **Memcached**, and **PostgreSQL** can be enabled using [Docker Compose profiles](https://docs.docker.com/compose/profiles/).

To enable one or more optional services, use the `--profile` flag and specify your env file:

- **Start with Adminer:**
    ```bash
    make up profiles="adminer"
    ```
- **Start with Redis and Memcached:**
    ```bash
    make up profiles="redis memcached"
    ```
- **Start all optional services:**
    ```bash
   make up profiles="adminer redis memcached postgres"
    ```

> [!TIP]
>
> You can combine any profiles as needed for your development workflow.

## Versioning

The version is broken down into 4 points e.g 1.2.3.4 We use MAJOR.MINOR.FEATURE.PATCH to describe the version numbers.

A MAJOR is very rare, it would only be considered if the source was effectively re-written or a clean break was desired for other reasons. This increment would likely break most 3rd party modules.

A MINOR is when there are significant changes that affect core structures. This increment would likely break some 3rd party modules.

A FEATURE version is when new extensions or features are added (such as a payment gateway, shipping module etc). Updating a feature version is at a low risk of breaking 3rd party modules.

A PATCH version is when a fix is added, it should be considered safe to update patch versions e.g 1.2.3.4 to 1.2.3.5

## Releases

OpenCart will announce to developers 1 week prior to public release of FEATURE versions, this is to allow for testing of their own modules for compatibility. For bigger releases (ones that contain many core changes, features and fixes) an extended period will be considered following an announced release candidate (RC). Patch versions (which are considered safe to update with) may have a significantly reduced developer release period.

The master branch will always contain an "_rc" postfix of the next intended version. The next "_rc" version may change at any time.

Developer release source code will not change once tagged.

If a bug is found in an announced developer release that is significant (such as a major feature is broken) then the release will be pulled. A patch version will be issued to replace it, depending on the severity of the patch an extended testing period may be announced. If the developer release version was never made public then the preceding patch version tag will be removed.

To receive developer notifications about release information, sign up to the newsletter on the [OpenCart website](https://www.opencart.com) - located in the footer. Then choose the developer news option.

## License

[GNU General Public License version 3 (GPLv3)](https://github.com/opencart/opencart/blob/master/LICENSE.md)

## Links

- [OpenCart homepage](https://www.opencart.com/)
- [OpenCart forums](https://forum.opencart.com/)
- [OpenCart blog](https://www.opencart.com/index.php?route=feature/blog)
- [How to documents](http://docs.opencart.com/en-gb/introduction/)
- [Newsletter](https://newsletter.opencart.com/h/r/B660EBBE4980C85C)
- [Discussions](https://github.com/opencart/opencart/discussions)
- [Chat](https://teams.live.com/l/community/FEAMBRGMM2X2wz82gI)
