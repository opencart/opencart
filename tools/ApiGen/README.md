# Smart and Readable Documentation for PHP projects

ApiGen is easy to use and modern API doc generator **supporting all PHP 8.2 features**.


## Features

- phpDoc
  - [all types supported by PHPStan](https://phpstan.org/writing-php-code/phpdoc-types)
  - [generic class declarations](https://phpstan.org/blog/generics-in-php-using-phpdocs)
  - [local type aliases](https://phpstan.org/writing-php-code/phpdoc-types#local-type-aliases)
- PHP 8.2
  - [constants in traits](https://wiki.php.net/rfc/constants_in_traits)
  - [fetch enum properties in const expressions](https://wiki.php.net/rfc/fetch_property_in_const_expressions)
  - [disjunctive normal form types](https://wiki.php.net/rfc/dnf_types)
  - [readonly classes](https://wiki.php.net/rfc/readonly_classes)
  - [true](https://wiki.php.net/rfc/true-type), [false and null types](https://wiki.php.net/rfc/null-false-standalone-types)
- PHP 8.1
  - [enums](https://wiki.php.net/rfc/enumerations)
  - [pure intersection types](https://wiki.php.net/rfc/pure-intersection-types)
  - [never type](https://wiki.php.net/rfc/noreturn_type)
  - [final class constants](https://wiki.php.net/rfc/final_class_const)
  - [new in initializers](https://wiki.php.net/rfc/new_in_initializers)
  - [readonly properties](https://wiki.php.net/rfc/readonly_properties_v2)
- PHP 8.0
  - [constructor property promotion](https://wiki.php.net/rfc/constructor_promotion)
  - [union types](https://wiki.php.net/rfc/union_types_v2)
  - [mixed type](https://wiki.php.net/rfc/mixed_type_v2)
  - [static return type](https://wiki.php.net/rfc/static_return_type)
- PHP 7.4
  - [typed properties](https://wiki.php.net/rfc/typed_properties_v2)
- PHP 7.2
  - [object type](https://wiki.php.net/rfc/object-typehint)
- PHP 7.1
  - [nullable types](https://wiki.php.net/rfc/nullable_types)
  - [iterable type](https://wiki.php.net/rfc/iterable)
  - [void type](https://wiki.php.net/rfc/void_return_type)
  - [class constant visibility](https://wiki.php.net/rfc/class_const_visibility)
- PHP 7.0
  - [scalar types](https://wiki.php.net/rfc/scalar_type_hints_v5)
  - [return types](https://wiki.php.net/rfc/return_types)
- PHP 5.6
  - [constant scalar expressions](https://wiki.php.net/rfc/const_scalar_exprs)
  - [variadic functions](https://wiki.php.net/rfc/variadics)
- PHP 5.4
  - [traits](https://wiki.php.net/rfc/horizontalreuse)
  - [callable type](https://wiki.php.net/rfc/callable)
  - [binary integer notation](https://wiki.php.net/rfc/binnotation4ints)


## Built on Shoulders of Giants

- [nikic/php-parser](https://github.com/nikic/PHP-Parser)
- [phpstan/phpdoc-parser](https://github.com/phpstan/phpdoc-parser)
- [latte/latte](https://github.com/nette/latte)
- [league/commonmark](https://github.com/thephpleague/commonmark)


## Install

### With Docker

ApiGen is available as [apigen/apigen](https://hub.docker.com/r/apigen/apigen) Docker image which you can directly use.

```bash
docker run --rm --interactive --tty --volume "$PWD:$PWD" --workdir "$PWD" \
  apigen/apigen:edge \
  src --output docs
```


### With Phar

This will install ApiGen phar binary to `tools/apigen`.

```bash
mkdir -p tools
curl -L https://github.com/ApiGen/ApiGen/releases/latest/download/apigen.phar -o tools/apigen
chmod +x tools/apigen
tools/apigen src --output docs
```


### With Composer

This will install ApiGen to `tools/apigen` directory with executable entry point available in `tools/apigen/bin/apigen`.

```bash
composer create-project --no-dev apigen/apigen:^7.0@alpha tools/apigen
tools/apigen/bin/apigen src --output docs
```


## Usage

Generate API docs by passing source directories and destination option:

```bash
apigen src --output docs
```


## Configuration

ApiGen can be configured with `apigen.neon` configuration file.

```neon
parameters:
  # string[], passed as arguments in CLI, e.g. ['src']
  paths: []

  # string[], --include in CLI, included files mask, e.g. ['*.php']
  include: ['*.php']

  # string[], --exclude in CLI, excluded files mask, e.g. ['tests/**']
  exclude: []

  # bool, should protected members be excluded?
  excludeProtected: false

  # bool, should private members be excluded?
  excludePrivate: true

  # string[], list of tags used for excluding class-likes and members
  excludeTagged: ['internal']

  # string, --output in CLI
  outputDir: '%workingDir%/api'

  # string | null, --theme in CLI
  themeDir: null

  # string, --title in CLI
  title: 'API Documentation'

  # string, --base-url in CLI
  baseUrl: ''

  # int, --workers in CLI, number of processes that will be forked for parallel rendering
  workerCount: 8

  # string, --memory-limit in CLI
  memoryLimit: '512M'
```
