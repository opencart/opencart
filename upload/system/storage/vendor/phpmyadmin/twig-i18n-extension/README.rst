Twig i18n Extension
===================

The ``i18n`` extension adds `gettext`_ support to Twig. It defines one tag,
``trans``.

Code status
-----------

.. image:: https://github.com/phpmyadmin/twig-i18n-extension/workflows/Run%20tests/badge.svg?branch=master
    :alt: Tests
    :target: https://github.com/phpmyadmin/twig-i18n-extension/actions

.. image:: https://codecov.io/gh/phpmyadmin/twig-i18n-extension/branch/master/graph/badge.svg
    :alt: Code coverage
    :target: https://codecov.io/gh/phpmyadmin/twig-i18n-extension

Installation
------------

This library can be installed via Composer running the following from the
command line:

.. code-block:: bash

    composer require phpmyadmin/twig-i18n-extension

Configuration
-------------

You need to register this extension before using the ``trans`` block

.. code-block:: php

    use PhpMyAdmin\Twig\Extensions\I18nExtension;

    $twig->addExtension(new I18nExtension());

Note that you must configure the ``gettext`` extension before rendering any
internationalized template. Here is a simple configuration example from the
PHP `documentation`_

.. code-block:: php

    // Set language to French
    putenv('LC_ALL=fr_FR');
    setlocale(LC_ALL, 'fr_FR');

    // Specify the location of the translation tables
    bindtextdomain('myAppPhp', 'includes/locale');
    bind_textdomain_codeset('myAppPhp', 'UTF-8');

    // Choose domain
    textdomain('myAppPhp');

.. caution::

    The ``i18n`` extension only works if the PHP `gettext`_ extension is
    enabled.

Usage
-----

Use the ``trans`` block to mark parts in the template as translatable:

.. code-block:: twig

    {% trans "Hello World!" %}

    {% trans string_var %}

    {% trans %}
        Hello World!
    {% endtrans %}

In a translatable string, you can embed variables:

.. code-block:: twig

    {% trans %}
        Hello {{ name }}!
    {% endtrans %}

During the gettext lookup these placeholders are converted. ``{{ name }}`` becomes ``%name%`` so the gettext ``msgid`` for this string would be ``Hello %name%!``.

.. note::

    ``{% trans "Hello {{ name }}!" %}`` is not a valid statement.

If you need to apply filters to the variables, you first need to assign the
result to a variable:

.. code-block:: twig

    {% set name = name|capitalize %}

    {% trans %}
        Hello {{ name }}!
    {% endtrans %}

To pluralize a translatable string, use the ``plural`` block:

.. code-block:: twig

    {% trans %}
        Hey {{ name }}, I have one apple.
    {% plural apple_count %}
        Hey {{ name }}, I have {{ count }} apples.
    {% endtrans %}

The ``plural`` tag should provide the ``count`` used to select the right
string. Within the translatable string, the special ``count`` variable always
contain the count value (here the value of ``apple_count``).

To add notes for translators, use the ``notes`` block:

.. code-block:: twig

    {% trans %}
        Hey {{ name }}, I have one apple.
    {% plural apple_count %}
        Hey {{ name }}, I have {{ count }} apples.
    {% notes %}
        This is shown in the user menu. This string should be shorter than 30 chars
    {% endtrans %}

You can use ``notes`` with or without ``plural``. Once you get your templates compiled you should
configure the ``gettext`` parser to get something like this: ``xgettext --add-comments=notes``

Within an expression or in a tag, you can use the ``trans`` filter to translate
simple strings or variables:

.. code-block:: twig

    {{ var|default(default_value|trans) }}

Complex Translations within an Expression or Tag
------------------------------------------------

Translations can be done with both the ``trans`` tag and the ``trans`` filter.
The filter is less powerful as it only works for simple variables or strings.
For more complex scenario, like pluralization, you can use a two-step
strategy:

.. code-block:: twig

    {# assign the translation to a temporary variable #}
    {% set default_value %}
        {% trans %}
          Hey {{ name }}, I have one apple.
        {% plural apple_count %}
          Hey {{ name }}, I have {{ count }} apples.
        {% endtrans %}
    {% endset %}

    {# use the temporary variable within an expression #}
    {{ var|default(default_value|trans) }}

Extracting Template Strings
---------------------------

If you use the Twig I18n extension, you will probably need to extract the
template strings at some point.

Using Poedit 2
~~~~~~~~~~~~~~

Poedit 2 has native support for extracting from Twig files and no extra
setup is necessary (Pro version).

Using ``xgettext`` or Poedit 1
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Unfortunately, the ``xgettext`` utility does not understand Twig templates
natively and neither do tools based on it such as free versions of Poedit.
But there is a simple workaround: as Twig converts templates to
PHP files, you can use ``xgettext`` on the template cache instead.

Create a script that forces the generation of the cache for all your
templates. Here is a simple example to get you started

.. code-block:: php

    use Twig\Environment;
    use Twig\Loader\FilesystemLoader;
    use PhpMyAdmin\Twig\Extensions\I18nExtension;

    $tplDir = __DIR__ . '/templates';
    $tmpDir = '/tmp/cache/';
    $loader = new FilesystemLoader($tplDir);

    // force auto-reload to always have the latest version of the template
    $twig = new Environment($loader, [
        'auto_reload' => true,
        'cache' => $tmpDir,
    ]);
    $twig->addExtension(new I18nExtension());
    // configure Twig the way you want

    // iterate over all your templates
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($tplDir), RecursiveIteratorIterator::LEAVES_ONLY) as $file)
    {
        // force compilation
        if ($file->isFile()) {
            $twig->loadTemplate(str_replace($tplDir . '/', '', $file));
        }
    }

Use the standard ``xgettext`` utility as you would have done with plain PHP
code:

.. code-block:: text

    xgettext --default-domain=messages -p ./locale --from-code=UTF-8 -n --omit-header -L PHP /tmp/cache/*.php

Another workaround is to use `Twig Gettext Extractor`_ and extract the template
strings right from `Poedit`_.

.. _`gettext`:                https://www.php.net/gettext
.. _`documentation`:          https://www.php.net/manual/en/function.gettext.php
.. _`Twig Gettext Extractor`: https://github.com/umpirsky/Twig-Gettext-Extractor#readme
.. _`Poedit`:                 https://poedit.net/

History
-------

This project was forked in 2019 by the phpMyAdmin team, since it was abandoned by the
`Twig project`_ but was still in use for phpMyAdmin.

.. _`Twig project`: https://github.com/twigphp/Twig-extensions

If you find this work useful, or have a pull request to contribute, please find us on
`Github`_.

.. _`Github`: https://github.com/phpmyadmin/twig-i18n-extension/
