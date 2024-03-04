# phpstorm-stubs 

[![official JetBrains project](http://jb.gg/badges/official.svg)](https://confluence.jetbrains.com/display/ALL/JetBrains+on+GitHub)
[![License](https://img.shields.io/badge/License-Apache%202.0-blue.svg)](https://www.apache.org/licenses/LICENSE-2.0.html)
[![Total Downloads](https://poser.pugx.org/jetbrains/phpstorm-stubs/downloads)](https://packagist.org/packages/jetbrains/phpstorm-stubs)

[![PhpStorm Stubs Tests](https://github.com/JetBrains/phpstorm-stubs/actions/workflows/main.yml/badge.svg)](https://github.com/JetBrains/phpstorm-stubs/actions/workflows/main.yml)
[![PhpStorm Stubs PECL Test](https://github.com/JetBrains/phpstorm-stubs/actions/workflows/testPeclExtensions.yml/badge.svg)](https://github.com/JetBrains/phpstorm-stubs/actions/workflows/testPeclExtensions.yml)
[![PhpStorm Stubs Check Links](https://github.com/JetBrains/phpstorm-stubs/actions/workflows/testLinks.yml/badge.svg)](https://github.com/JetBrains/phpstorm-stubs/actions/workflows/testLinks.yml)

STUBS are normal, syntactically correct PHP files that contain function & class signatures, constant definitions, etc. for all built-in PHP stuff and most standard extensions. Stubs need to include complete [PHPDOC], especially proper @return annotations.

An IDE needs them for completion, code inspection, type inference, doc popups, etc. Quality of most of these services depend on the quality of the stubs (basically their PHPDOC @annotations).

Note that the stubs for “non-standard” extensions are provided as is. (Non-Standard extensions are the ones that are not part of PHP Core or are not Bundled/External - see the complete list [here](http://php.net/manual/en/extensions.membership.php).)

The support for such “non-standard” stubs is community-driven, and we only validate their PHPDoc. We do not check whether a stub matches the actual extension or whether the provided descriptions are correct.

Please note that currently there are no tests for the thrown exceptions so @throws tags should be checked manually according to official docs or PHP source code

[Relevant open issues]

### Contribution process
[Contribution process](CONTRIBUTING.md)

### Updating the IDE
Have a full copy of the .git repo within an IDE and provide its path in `Settings | Languages & Frameworks | PHP | PHP Runtime | Advanced settings | Default stubs path`. It should then be easily updatable both ways via normal git methods.

### Extensions enabled by default
The set of extensions enabled by default in PhpStorm can change anytime without prior notice. To learn how to view the enabled extensions, look [here](https://blog.jetbrains.com/phpstorm/2017/03/per-project-php-extension-settings-in-phpstorm-2017-1/).

### How to run tests
1. Execute `docker-compose -f docker-compose.yml run test_runner composer install --ignore-platform-reqs`
2. Execute `docker-compose -f docker-compose.yml run -e PHP_VERSION=8.0 test_runner vendor/bin/phpunit --testsuite PHP_8.0`

### How to update stub map
Execute `docker-compose -f docker-compose.yml run test_runner /usr/local/bin/php tests/Tools/generate-stub-map` and commit the resulting `PhpStormStubsMap.php`

### License
[Apache 2]

contains material by the PHP Documentation Group, licensed with [CC-BY 3.0] 

[PHPDOC]:https://github.com/phpDocumentor/fig-standards/blob/master/proposed/phpdoc.md
[Apache 2]:https://www.apache.org/licenses/LICENSE-2.0
[Relevant open issues]:https://youtrack.jetbrains.com/issues/WI?q=%23Unresolved+Subsystem%3A+%7BPHP+lib+stubs%7D+order+by%3A+votes+
[CC-BY 3.0]:https://www.php.net/manual/en/cc.license.php
