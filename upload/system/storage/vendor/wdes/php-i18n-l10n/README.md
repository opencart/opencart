# php-I18n-L10n

PHP library/api to provide Internationalisation and Localisation

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/2c22d6f405d143d8b65d0e1d875dd701)](https://www.codacy.com/gh/wdes/php-I18n-L10n/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=wdes/php-I18n-L10n&amp;utm_campaign=Badge_Grade)
![Lint and analyse files](https://github.com/wdes/php-I18n-L10n/workflows/Lint%20and%20analyse%20files/badge.svg?branch=main)
![Run phpunit tests](https://github.com/wdes/php-I18n-L10n/workflows/Run%20phpunit%20tests/badge.svg?branch=main)
[![codecov](https://codecov.io/gh/wdes/php-I18n-L10n/branch/main/graph/badge.svg)](https://codecov.io/gh/wdes/php-I18n-L10n)
[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2Fwdes%2Fphp-I18n-L10n.svg?type=shield)](https://app.fossa.io/projects/git%2Bgithub.com%2Fwdes%2Fphp-I18n-L10n?ref=badge_shield)
[![HitCount](https://hits.dwyl.com/wdes/php-I18n-L10n.svg?style=flat)](https://hits.dwyl.com/wdes/php-I18n-L10n)
![Packagist](https://img.shields.io/packagist/l/wdes/php-I18n-L10n.svg)
[![Latest Stable Version](https://poser.pugx.org/wdes/php-I18n-L10n/v/stable)](https://packagist.org/packages/wdes/php-I18n-L10n)

## License

[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2Fwdes%2Fphp-I18n-L10n.svg?type=large)](https://app.fossa.io/projects/git%2Bgithub.com%2Fwdes%2Fphp-I18n-L10n?ref=badge_large)

## About

We use the [`phpmyadmin/twig-i18n-extension`](https://github.com/phpmyadmin/twig-i18n-extension#readme) for the Twig extension.

## How to use

```sh
composer require wdes/php-i18n-l10n
```

Have a look at example file [example/simple.php](example/simple.php)

### Example without a MO file

```php
<?php

declare(strict_types = 1);

// Can be removed :)

require_once __DIR__ . '/../vendor/autoload.php';

use Wdes\phpI18nL10n\plugins\MoReader;
use Wdes\phpI18nL10n\Launcher;
use Wdes\phpI18nL10n\Twig\Extension\I18n as ExtensionI18n;
use Twig\Environment as TwigEnvironment;
use Twig\Loader\ArrayLoader as TwigLoader;

$moReader = new MoReader();
$moReader->setTranslations(
    [
        'Homepage' => 'Page d\'accueil',
    ]
);
// Load the translation plugin
Launcher::setPlugin($moReader);

$twig = new TwigEnvironment(new TwigLoader());
$twig->addExtension(new ExtensionI18n());
// You can use a file instead, see the example using a mo file
$templateContents = <<<HTML
<html>
    <title>{% trans %}Homepage{% endtrans %}</title>
    <body>
        {% trans %}Homepage{% endtrans %}
    </body>
</html>
HTML;
echo $twig->createTemplate($templateContents)->render([]);
```

### Example with a MO file

```php
<?php

declare(strict_types = 1);

// Can be removed :)

require_once __DIR__ . '/../vendor/autoload.php';

use Wdes\phpI18nL10n\plugins\MoReader;
use Wdes\phpI18nL10n\Launcher;
use Wdes\phpI18nL10n\Twig\Extension\I18n as ExtensionI18n;
use Twig\Environment as TwigEnvironment;
use Twig\Loader\FilesystemLoader as TwigLoaderFilesystem;

$dataDir  = __DIR__ . '/locale/';
$moReader = new MoReader();
$moReader->readFile($dataDir . 'fr.mo'); // Load the file you want (a specific language for example)
// Load the translation plugin
Launcher::setPlugin($moReader);

$loader = new TwigLoaderFilesystem([ __DIR__ . '/templates/' ]); // Load all templates from the dir
$twig   = new TwigEnvironment($loader);

$twig->addExtension(new ExtensionI18n());
echo $twig->render(
    'homepage.twig', // Can be found in the templates directory
    [
        'keyForTwig' => 'theValue', // Just an example line ;)
        'say' => 'Hello world'
    ]
);

```

### Scripts

This package includes some scripts that can be usefull [scripts/tools](scripts/tools)
Here is an example to use them : [scripts/update-example.sh](scripts/update-example.sh)
