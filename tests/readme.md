## Important things to note about testing
* Tests should only be run on a test system, never a live one.
* They have been created to help developers test and improve not only OpenCart but also modules too.

## Requirements:
* [GIT](http://git-scm.com/)
* [Composer](https://getcomposer.org/download/)
* A bit of command line knowledge

## Instructions
* Download and unzip OpenCart with the [test suite included](https://github.com/opencart/opencart/archive/2.0-testing-suite.zip)
* Go to your command line (Windows > Run > Cmd), Shell, or Git Bash here
* Change directory to /tests folder (extracted inside the opencart zip)
* Type in the command line: composer update (this will create you a vendor folder and download all dependencies)
* Composer will now pull in all of the required externals/dependencies
* Go to bootstrap.php (inside /tests/opencart) and update database's connection settings.
* run: vendor\bin\phpunit --bootstrap opencart\bootstrap.php opencart\admin to run tests in admin folder
* run: vendor\bin\phpunit --bootstrap opencart\bootstrap.php opencart\catalog to run tests in catalog folder
* run: vendor\bin\phpunit --bootstrap opencart\bootstrap.php opencart\system to run tests in system folder