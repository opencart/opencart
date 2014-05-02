## Requirements:
* GIT
* Composer
* Command line

## Instructions
* Download and extract OpenCart inside project root
* Go to command line, change directory to tests folder (extracted inside the opencart zip)
* Type in the command line: composer update
* Composer will now pull in all of the required externals/dependencies
* Go to bootstrap.php (inside /tests/opencart) and update database's connection settings.
* DO NOT EVER USE A PRODUCTION DATABASE AS TABLES WILL BE DROPPED AND RE-CREATED!
* run: vendor\bin\phpunit --bootstrap opencart\bootstrap.php opencart
