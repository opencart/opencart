# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.x.x] - YYYY-MM-DD

## [1.5.0] - 2022-02-18

- Allow more versions of symfony/console (`^5|^6`)
- GithubErrorFormatter > Allow any ErrorFormatter
- Assure Gitlab reports have integer line properties
- Make `Error` class `line` property nullable
- GitLab 13.x expects severity field in code quality reports

## [1.4.0] - 2021-03-24

- Upgrade `ondram/ci-detector` to 4.0
- Copy an upstream change, make the test suite run offline
- Apply `wdes/coding-standard`
- Update `.gitattributes` to remove `.editorconfig` for releases

## [1.3.1] - 2020-11-29

- Fix RawTextErrorFormatter
- Fix a PHP 8.0 unit test failing on a now uppercase letter

## [1.3.0] - 2020-11-29

- Fix `OutputFormat::checkOutputFormatIsValid`
- Add `Utils::isCiDetected` to detect CIs

## [1.2.1] - 2020-11-29

- Fix and improve the wording of FormatNotFoundException message
- Improve composer keywords

## [1.2.0] - 2020-11-29

- Drop `ERASE_TO_LINE_END_2` and `ERASE_TO_LINE_END_1` constants from `AnsiEscapeSequences` because they where broken.
- Add `ERASE_TO_LINE_END` to `AnsiEscapeSequences`
- Add `ErrorFormatter\RawTextErrorFormatter` that reflects a Doctum error output format
- Add `OutputFormat` to handle the input/output user choice

## [1.1.0] - 2020-11-27

- Add `isDecorated` method on `SymfonyOutput` class and `Output` interface.
- Fix missing `strict_types` on `Error` class
- Add `AnsiEscapeSequences` to help with some often used sequences

## [1.0.0] - 2020-11-26

- Initial version with support for `ErrorFormatter` classes from phpstan and more classes
- Fixed imported tests
- Added GitHub actions
- Added phpstan
- Removed some useless imported code
- Made the imported source code compatible with PHP 7.1
