# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [v4.0.0] - 2021-03-31

- Change `BasePlugin::__construct` signature to `__construct(array $options = [])` (make `$options` optional)
- Add `getTranslations` and `setTranslations` to `MoReader` class
- Add a new even simpler example using `setTranslations`
- Remove options array sent to `MoReader`, it was useless

## [v3.0.0] - 2021-03-24

- Use `phpmyadmin/twig-i18n-extension` for Twig translation tag and pipe support
- Drop Twig 2
- Support Twig 3
- Drop PHP 7.1
- Require PHP `>=` `7.2.9`
- Add `Launcher::setPlugin($moReader);`
- Remove direct access to `Launcher::$plugin`
- Applied `wdes/coding-standard` on the code-base

## [v2.1.0] - 2020-12-01

- Add support for PHP 8.0

## [v2.0.0] - 2020-03-30
### Added
- Add support for phpunit 8 and 9
- Add a .gitattributes file
- Improved code coverage

### Changed
- Upgraded squizlabs/php_codesniffer from 3.3.x to 3.5.x
- Upgraded phpstan/phpstan from 0.11.8+ to 0.12+
- BREAKING CHANGE rename namespace to Wdes\phpI18nL10n

### Fixed
- Test phpunit suite
- .editorconfig for *.neon files

### Removed
- twig/extensions dependency
- TravisCI setup
- scripts directory from dist version
- dev files from dist version
- example from dist version
- Utils class
- CI scripts
- Some useless files

## [v1.0.0] - 2019-06-08
### Added
- First stable version

[Unreleased]: https://github.com/wdes/php-I18n-L10n/compare/v1.0.0...HEAD
[v1.0.0]: https://github.com/wdes/php-I18n-L10n/releases/tag/v1.0.0
