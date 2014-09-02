## Important things to note about testing
* Tests should only be run on a test system, never a live one.
* They have been created to help developers test and improve not only OpenCart but also modules too.

## Requirements:
* [GIT](http://git-scm.com/)
* [Composer](https://getcomposer.org/download/)
* A bit of command line knowledge
* Selenium (optional)

## Instructions
* Install Git (most developers will already have this!)
* Install composer
* Git clone OpenCart (you cannot download the ZIP as this will not include the tests).
* Install OpenCart as normal
* Go to your command line (Windows > Run > Cmd), Shell, or Git Bash
* Change directory to /tests/phpunit/ folder
* Type in the command line: composer update (this will create you a vendor folder and download all dependencies)
* Composer will now pull in all of the required externals/dependencies
* run: vendor\bin\phpunit --bootstrap bootstrap.php opencart\admin to run tests in admin folder
* run: vendor\bin\phpunit --bootstrap bootstrap.php opencart\catalog to run tests in catalog folder
* run: vendor\bin\phpunit --bootstrap bootstrap.php opencart\system to run tests in system folder

## Selenium instructions

Running Acceptance (Functional) Tests with Selenium requires a standalone selenium server on your machine.
The server can be downloaded from [here](http://code.google.com/p/selenium/downloads/list). Before starting your Selenium Tests
you have to run the standalone server: `java -jar selenium-server-standalone-2.32.0.jar`. Writing Selenium Tests requires you to extend the OpenCartSeleniumTest class.

* run: vendor\bin\phpunit --bootstrap bootstrap.php selenium\catalog to run tests in system folder

## Please READ!
The tests are still under development, there is hundreds of them to do.

The tests will drop and recreate tables in database specified in config.php and admin/config.php

## Jenkins Users
You will also see a build.xml file inside the project root which you can use to configure a Jenkins build.

## We need you
If you understand testing, then you know how important it is to any project. If you have a suggestion then we would really like to hear it!

Forum thread: http://forum.opencart.com/viewtopic.php?f=177&t=124532

Please help by contributing to writing unit tests and submitting a pull request!