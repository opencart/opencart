@@@@@@@ TODO FINISH @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@


* Installing the testing framework

navigate to the tests/ folder and run 
composer update

To run the tests:
vendor/bin/phpunit --bootstrap opencart/bootstrap.php opencart

Settings of the database used for testing are in opencart/bootstrap.php. The database is deleted and recreated for each test case.



''''''''''''''''''''''''''''''''




Requirements:
GIT
Composor
Command line

Instructions
Download and extract OpenCart inside project root
Go to command line, change directory to tests folder (extracted inside the opencart zip)
Type in the command line: composor update
Composer will now pull in all of the required externals/dependancies
Go to bootstrap.php (inside tests/opencart/bootstrap.php) to update the connection settings.
DO NOT EVER USE A PRODUCTION DATABASE AS TABLES WILL BE DROPPED AND RE-CREATED!
Command line to the tests folder (opencart/tests) and run:
vendor\bin\phpunit –bootstrap opencart\bootstrap.php opencart
