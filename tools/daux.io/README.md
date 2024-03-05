# Daux.io

[![Latest Version](https://img.shields.io/github/release/dauxio/daux.io.svg?style=flat-square)](https://github.com/dauxio/daux.io/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://github.com/dauxio/daux.io/blob/master/LICENSE.md)
![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/dauxio/daux.io/php.yml?style=flat-square&logo=github)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=dauxio_daux.io&metric=coverage)](https://sonarcloud.io/dashboard?id=dauxio_daux.io)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=dauxio_daux.io&metric=alert_status)](https://sonarcloud.io/dashboard?id=dauxio_daux.io)
[![Packagist Downloads](https://img.shields.io/packagist/dm/daux/daux.io?style=flat-square&logo=packagist)](https://packagist.org/packages/daux/daux.io)
[![Docker Pulls](https://img.shields.io/docker/pulls/daux/daux.io?style=flat-square&logo=docker)](https://hub.docker.com/r/daux/daux.io)

**Daux.io** is a documentation generator that uses a simple folder structure and Markdown files to create custom documentation on the fly. It helps you create great looking documentation in a developer friendly way.

## Features

-   100% Mobile Responsive
-   CommonMark compliant (a Markdown specification)
-   Supports Markdown tables
-   Auto created homepage/landing page
-   Auto Syntax Highlighting
-   Auto Generated Navigation
-   4 Built-In Themes or roll your own
-   Functional, Flat Design Style
-   Shareable/Linkable SEO Friendly URLs
-   Built On Bootstrap
-   No Build Step
-   Git/SVN Friendly
-   Supports Google Analytics and Piwik Analytics
-   Static Output Generation

## Demos

This is a list of sites using Daux.io:

-   With a custom theme:
    -   [Crafty](https://swissquote.github.io/crafty)
    -   [Pixolution flow](https://docs.pixolution.org) \* [Soisy](https://doc.soisy.it/)
    -   [Vulkan Tutorial](https://vulkan-tutorial.com) \* [3Q](https://docs.3q.video/)
    -   [The Advanced RSS Environment](https://thearsse.com/manual/)
-   With the default Theme
    -   [Daux.io](https://daux.io/)
        _ [DoctrineWatcher](https://dsentker.github.io/WatcherDocumentation/)
        _ [DrupalGap](http://docs.drupalgap.org/8/)
    -   [ICADMIN: An admin panel powered by CodeIgniter.](http://istocode.com/shared/ic-admin/)
    -   [Munee: Standalone PHP 5.3 Asset Optimisation & Manipulation](http://mun.ee)
    -   [Nuntius: A PHP framework for bots](https://roysegall.github.io/nuntius-bot/)

Do you use Daux.io? Send me a pull request or open an [issue](https://github.com/dauxio/daux.io/issues) and I will add you to the list.

## Install

### PHP and Composer

If you have PHP and Composer installed, you can install the dependency

```bash
composer global require daux/daux.io

# Next to your `docs` folder, run
daux generate
```

You can then use the `daux` command line to generate your documentation.

If the command isn't found, ensure your `$PATH` contains `~/.composer/vendor/bin`

### Docker

Or if you wish to use Docker, the start of the command will be :

```bash
docker run --rm -it -w /build -v "$PWD":/build -u "$(id -u):$(id -g)" daux/daux.io daux
```

## Run on a server

Download this repository as a zip, unpack, and put your documentation in the `docs` folder, you can then serve it with Apache or Nginx.

## `daux`

The command line tool has two commands: `generate` and `serve`, running Daux.io without an argument will automatically run the `generate` command.

You can run `daux --help` to get more details about each command.

## Folders

By default, the generator will look for folders in the `docs` folder. Add your folders inside the `docs` folder. This project contains some example folders and files to get you started.

You can nest folders any number of levels to get the exact structure you want. The folder structure will be converted to the nested navigation.

If you'd prefer to keep your docs somewhere else (like outside of the daux.io root directory) you can specify your docs path in the `global.json` file.

## Files

The generator will look for Markdown files (`*.md` and `*.markdown`) inside the `docs` folder and any of the subfolders within `docs`.

You must use underscores instead of spaces. Here are some example file names and what they will be converted to:

**Good:**

-   01_Getting_Started.md = Getting Started
-   API_Calls.md = API Calls
-   200_Something_Else-Cool.md = Something Else-Cool
-   \_5_Ways_to_Be_Happy.md = 5 Ways To Be Happy

**Bad:**

-   File Name With Space.md = FAIL

## Sorting

To sort your files and folders in a specific way, you can prefix them with a number and underscore, e.g. `/docs/01_Hello_World.md` and `/docs/05_Features.md` This will list _Hello World_ before _Features_, overriding the default alpha-numeric sorting. The numbers will be stripped out of the navigation and urls. For the file `6 Ways to Get Rich`, you can use `/docs/_6_Ways_to_Get_Rich.md`

## Landing page

If you want to create a beautiful landing page for your project, simply create a `index.md` file in the root of the `/docs` folder. This file will then be used to create a landing page. You can also add a tagline and image to this page using the config file like this:

```json
{
    "title": "Daux.io",
    "tagline": "The Easiest Way To Document Your Project",
    "image": "app.png"
}
```

Note: The image can be a local or remote image. Use the convention `<base_url>` to refer to the root directory of the Daux instance.

## Section landing page

If you are interested in having a landing page for a subsection of your docs, all you need to do is add an `index.md` file to the folder. For example, `/docs/01_Examples` has a landing page for that section since there exists a `/docs/01_Examples/index.md` file. If you wish to have an index page for a section without a landing page format, use the name `_index.md`

## Configuration

To customize the look and feel of your documentation, you can create a `config.json` file in the of the `/docs` folder.
The `config.json` file is a simple JSON object that you can use to change some of the basic settings of the documentation.

### Title

Change the title bar in the docs

```json
{
    "title": "Daux.io"
}
```

### Themes

We have 4 built-in Bootstrap themes. To use one of the themes, just set the `theme` option to one of the following:

-   daux-blue
-   daux-green
-   daux-navy
-   daux-red

```json
{
    "html": { "theme": "daux-green" }
}
```

### More options

Many other options are available:

-   [Global options](http://daux.io/Configuration/index)
-   [HTML Options](http://daux.io/Configuration/Html_export)
-   [Confluence options](http://daux.io/Configuration/Confluence_upload)

## Running Remotely

Copy the files from the repo to a web server that can run PHP 8.1.0 or newer.

## Running Locally

There are several ways to run the docs locally.
The recommended way is to run `daux serve` which will execute PHP's embedded server.

By default the server will run at: <a href="http://localhost:8085" target="_blank">http://localhost:8085</a>

This is really only intended be used when you are writing/updating a ton of docs and want to preview the changes locally.

## Generating a set of static files

These can be uploaded to a static site hosting service such as pages.github.com

Generating a complete set of pages, with navigation

```bash
daux --source=docs --destination=static
```

## Running on IIS

If you have set up a local or remote IIS web site, you may need a `web.config` with:

-   A rewrite configuration, for handling clean urls.
-   A mime type handler for less files, if using a custom theme.

### Clean URLs

The `web.config` needs an entry for `<rewrite>` under `<system.webServer>`:

```xml
<configuration>
    <system.webServer>
        <rewrite>
            <rules>
                <rule name="Main Rule" stopProcessing="true">
                    <match url=".*" />
                    <conditions logicalGrouping="MatchAll">
                        <add
                            input="{REQUEST_FILENAME}"
                            matchType="IsFile"
                            negate="true"
                        />
                        <add
                            input="{REQUEST_FILENAME}"
                            matchType="IsDirectory"
                            negate="true"
                        />
                    </conditions>
                    <action
                        type="Rewrite"
                        url="index.php"
                        appendQueryString="false"
                    />
                </rule>
            </rules>
        </rewrite>
    </system.webServer>
</configuration>
```

To use clean URLs on IIS 6, you will need to use a custom URL rewrite module, such as [URL Rewriter](http://urlrewriter.net/).

## PHP Requirements

Daux.io is compatible with the [officially supported](https://www.php.net/supported-versions.php) PHP versions; 8.1.0 and up.

### Extensions

Daux.io needs the following PHP extensions to work : `php-mbstring` and `php-xml`.

If you use non-english characters in your page names, it is recommended to install the `php-intl` extension as well.

## Support

If you need help using Daux.io, or have found a bug, please create an issue on the <a href="https://github.com/dauxio/daux.io/issues" target="_blank">GitHub repo</a>.
