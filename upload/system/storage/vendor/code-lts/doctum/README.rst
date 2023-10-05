Doctum, a PHP API documentation generator. Fork of Sami
=========================================================

Curious about what Doctum generates? Have a look at the `MariaDB MySQL Kbs`_ or `Laravel`_ documentation.

Our badges
----------

.. image:: https://img.shields.io/badge/GitHub%20marketplace%20action-available-green.svg
    :alt: GitHub marketplace action
    :target: https://github.com/marketplace/actions/action-doctum

.. image:: https://codecov.io/gh/code-lts/doctum/branch/main/graph/badge.svg?branch=main
    :alt: Project code coverage by Codecov
    :target: https://codecov.io/gh/code-lts/doctum

.. image:: https://github.com/code-lts/doctum/actions/workflows/tests.yml/badge.svg?branch=main
    :alt: Project test suite
    :target: https://github.com/code-lts/doctum/actions/workflows/tests.yml

Installation
------------

.. caution::

    Doctum requires **PHP 7.2.20** or later.

Get Doctum as a `phar file`_:

.. code-block:: bash

    $ curl -O https://doctum.long-term.support/releases/latest/doctum.phar

You can also find some alternative phar versions:

- ``major``
- ``major``. ``minor`` (any version since 5.1)
- ``major``. ``minor``. ``patch``
- `latest <https://doctum.long-term.support/releases/latest/doctum.phar>`_
- `dev <https://doctum.long-term.support/releases/dev/doctum.phar>`_ (not always up to date)
- ``major``-dev
- ``major``. ``minor``-dev (most of the time it exists)
- ``major``. ``minor``. ``patch``-dev (sometimes it exists)

.. code-block:: bash

    $ curl -O https://doctum.long-term.support/releases/${version}/doctum.phar && chmod +x doctum.phar

Check that everything worked as expected by executing the ``doctum.phar`` file
without any arguments:

.. code-block:: bash

    $ doctum.phar

Since 5.3.0 the phar does not require to use `php` keyword anymore because the `shebang` was added to the phar file.
You can now call `doctum.phar` directly after adding execution rights onto the file.

You can use our `GitHub marketplace action <https://github.com/marketplace/actions/action-doctum>`_ into your GitHub CI.

Configuration
-------------

Before generating documentation, you must create a configuration file. Here is
the simplest possible one:

.. code-block:: php

    <?php

    return new Doctum\Doctum('/path/to/yourlib/src');

The configuration file must return an instance of ``Doctum\Doctum`` and the first
argument of the constructor is the path to the code you want to generate
documentation for.

Actually, instead of a directory, you can use any valid PHP iterator (and for
that matter any instance of the Symfony `Finder`_ class):

.. code-block:: php

    <?php

    use Doctum\Doctum;
    use Symfony\Component\Finder\Finder;

    $iterator = Finder::create()
        ->files()
        ->name('*.php')
        ->exclude('Resources')
        ->exclude('Tests')
        ->in('/path/to/yourlib/src');

    return new Doctum($iterator);

The ``Doctum`` constructor optionally takes an array of options as a second
argument:

.. code-block:: php

    return new Doctum($iterator, [
        'title'                => 'yourlib API',
        'language'             => 'en', // Could be 'fr'
        'build_dir'            => __DIR__ . '/build',
        'cache_dir'            => __DIR__ . '/cache',
        'source_dir'           => '/path/to/repository/',
        'remote_repository'    => new GitHubRemoteRepository('username/repository', '/path/to/repository'),
        'default_opened_level' => 2, // optional, 2 is the default value
    ]);

And here is how you can configure different versions:

.. code-block:: php

    <?php

    use Doctum\Doctum;
    use Doctum\RemoteRepository\GitHubRemoteRepository;
    use Doctum\Version\GitVersionCollection;
    use Symfony\Component\Finder\Finder;

    $dir = '/path/to/yourlib/src';
    $iterator = Finder::create()
        ->files()
        ->name('*.php')
        ->exclude('Resources')
        ->exclude('Tests')
        ->in($dir);

    // generate documentation for all v2.0.* tags, the 2.0 branch, and the main one
    $versions = GitVersionCollection::create($dir)
        // In a non case-sensitive way, tags containing "PR", "RC", "BETA" and "ALPHA" will be filtered out
        // To change this, use: `$versions->setFilter(static function (string $version): bool { // ... });`
        ->addFromTags('v2.0.*')
        ->add('2.0', '2.0 branch')
        ->add('main', 'main branch');

    return new Doctum($iterator, [
        'versions'             => $versions,
        'title'                => 'yourlib API',
        'language'             => 'en', // Could be 'fr'
        'build_dir'            => __DIR__ . '/../build/sf2/%version%',
        'cache_dir'            => __DIR__ . '/../cache/sf2/%version%',
        'source_dir'           => dirname($dir) . '/',
        'remote_repository'    => new GitHubRemoteRepository('yourorg/yourlib', dirname($dir)),
        'default_opened_level' => 2, // optional, 2 is the default value
    ]);


And here is how you can configure a footer link below the Doctum link:

All `footer_link` keys are optional.

.. code-block:: php

    <?php

    use Doctum\Doctum;
    use Symfony\Component\Finder\Finder;

    $dir = '/path/to/yourlib/src';
    $iterator = Finder::create()
        ->files()
        ->name('*.php')
        ->exclude('Resources')
        ->exclude('Tests')
        ->in($dir);

    return new Doctum($iterator, [
        'title'                => 'yourlib API',
        'source_dir'           => dirname($dir) . '/',
        'remote_repository'    => new GitHubRemoteRepository('yourorg/yourlib', dirname($dir)),
        'footer_link'          => [
            'href'        => 'https://github.com/code-lts/doctum',
            'rel'         => 'noreferrer noopener',
            'target'      => '_blank',
            'before_text' => 'You can edit the configuration',
            'link_text'   => 'on this', // Required if the href key is set
            'after_text'  => 'repository',
        ],
    ]);

To enable `OpenSearch <https://en.wikipedia.org/wiki/OpenSearch>`_ feature in your users browsers:

.. code-block:: php

    <?php

    use Doctum\Doctum;
    use Symfony\Component\Finder\Finder;

    $dir = '/path/to/yourlib/src';
    $iterator = Finder::create()
        ->files()
        ->name('*.php')
        ->exclude('Resources')
        ->exclude('Tests')
        ->in($dir);

    return new Doctum($iterator, [
        'title'    => 'Project Api Documentation',
        // Necessary to enable the opensearch.xml file generation
        'base_url' => 'https://apidocs.company.tld/',
        // If you have a favicon
        // 'favicon' => 'https://company.tld/favicon.ico',
        // ... more configs
    ]);

You can find more configuration examples under the ``examples/`` directory of
the source code.

Doctum only documents the public API (public properties and methods); override
the default configured ``filter`` to change this behavior:

.. code-block:: php

    <?php

    use Doctum\Parser\Filter\TrueFilter;

    $doctum = new Doctum(...);
    // document all methods and properties
    $doctum['filter'] = function () {
        return new TrueFilter();
    };

Rendering
---------

Now that we have a configuration file, let's generate the API documentation:

.. code-block:: bash

    $ doctum.phar update /path/to/config.php

The generated documentation can be found under the configured ``build/``
directory (note that the client side search engine does not work on Chrome due
to JavaScript execution restriction, unless Chrome is started with the
"--allow-file-access-from-files" option -- it works fine in Firefox).

By default, Doctum is configured to run in "incremental" mode. It means that when
running the ``update`` command, Doctum only re-generates the files that needs to
be updated based on what has changed in your code since the last execution.

Doctum also detects problems in your phpdoc and can tell you what you need to fix
if you add the ``-v`` option:

.. code-block:: bash

    $ doctum.phar update /path/to/config.php -v

Creating a Theme
----------------

If the default themes do not suit your needs, you can very easily create a new
one, or just override an existing one.

A theme is just a directory with a ``manifest.yml`` file that describes the
theme (this is a YAML file):

.. code-block:: yaml

    name:   markdown-custom
    parent: default

The above configuration creates a new ``markdown-custom`` theme based on the
``default`` built-in theme. To override a template, just create a file with
the same name as the original one. For instance, here is how you can extend the
default class template to prefix the class name with "Class " in the class page
title:

.. code-block:: twig

    {# pages/class.twig #}

    {% extends 'default/pages/class.twig' %}

    {% block title %}Class {{ parent() }}{% endblock %}

If you are familiar with Twig, you will be able to very easily tweak every
aspect of the templates as everything has been well isolated in named Twig
blocks.

A theme can also add more templates and static files. Here is the manifest for
the default theme:

.. code-block:: yaml

    name: default

    static:
        'css/doctum.css': 'css/doctum.css'
        'css/bootstrap.min.css': 'css/bootstrap.min.css'
        'css/bootstrap-theme.min.css': 'css/bootstrap-theme.min.css'
        'fonts/doctum-font.css': 'fonts/doctum-font.css'
        'fonts/doctum.woff': 'fonts/doctum.woff'
        'fonts/doctum.woff2': 'fonts/doctum.woff2'
        'fonts/doctum.ttf': 'fonts/doctum.ttf'
        'fonts/doctum.svg': 'fonts/doctum.svg'
        'fonts/doctum.eot': 'fonts/doctum.eot'
        'js/jquery-3.5.1.slim.min.js': 'js/jquery-3.5.1.slim.min.js'
        'js/bootstrap.min.js': 'js/bootstrap.min.js'
        'js/autocomplete.min.js': 'js/autocomplete.min.js'

    global:
        'index.twig':      'index.html'
        'doc-index.twig':  'doc-index.html'
        'namespaces.twig': 'namespaces.html'
        'classes.twig':    'classes.html'
        'interfaces.twig': 'interfaces.html'
        'traits.twig':     'traits.html'
        'opensearch.twig': 'opensearch.xml'
        'search.twig':     'search.html'
        'doctum.js.twig':  'doctum.js'

    namespace:
        'namespace.twig': '%s.html'

    class:
        'class.twig': '%s.html'


Files are contained into sections, depending on how Doctum needs to treat them:

* ``static``: Files are copied as is (for assets like images, stylesheets, or
  JavaScript files);

* ``global``: Templates that do not depend on the current class context;

* ``namespace``: Templates that should be generated for every namespace;

* ``class``: Templates that should be generated for every class.

.. _Finder: https://symfony.com/doc/current/components/finder.html
.. _phar file: https://doctum.long-term.support/releases/latest/doctum.phar
.. _MariaDB MySQL Kbs: https://williamdes.github.io/mariadb-mysql-kbs/
.. _Laravel: https://laravel.com/api/master/index.html

Search Index
~~~~~~~~~~~~

The autocomplete and search functionality of Doctum is provided through a
search index that is generated based on the classes, namespaces, interfaces,
and traits of a project. You can customize the search index by overriding the
``search_index_extra`` block of ``doctum.js.twig``.

The ``search_index_extra`` allows you to extend the default theme and add more
entries to the index. For example, some projects implement magic methods that
are dynamically generated at runtime. You might wish to document these methods
while generating API documentation and add them to the search index.

Each entry in the search index is a JavaScript object that contains the
following keys:

type
    The type associated with the entry. Built-in types are "Class",
    "Namespace", "Interface", "Trait". You can add additional types specific
    to an application, and the type information will appear next to the search
    result.

name
    The name of the entry. This is the element in the index that is searchable
    (e.g., class name, namespace name, etc).

fromName
    The parent of the element (if any). This can be used to provide context for
    the entry. For example, the fromName of a class would be the namespace of
    the class.

fromLink
    The link to the parent of the entry (if any). This is used to link a child
    to a parent. For example, this would be a link from a class to the class
    namespace.

doc
    A short text description of the entry.

One such example of when overriding the index is useful could be documenting
dynamically generated API operations of a web service client. Here's a simple
example that adds dynamically generated API operations for a web service client
to the search index:

.. code-block:: twig

    {% extends "default/doctum.js.twig" %}

    {% block search_index_extra %}
        {% for operation in operations -%}
            {
                type: 'Operation'|trans,
                link: operation.path,
                name: operation.name,
                doc: operation.doc,
            }|json_encode|raw
        {%- endfor %}
    {% endblock %}

This example assumes that the template has a variable ``operations`` available
which contains an array of operations.

.. note::

    Always include a trailing comma for each entry you add to the index. Doctum
    will take care of ensuring that trailing commas are handled properly.
