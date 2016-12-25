<?php
namespace TestsUtils;

include '/opt/lampp/htdocs/opencart/upload/config.php';
include '/opt/lampp/htdocs/opencart/upload/system/library/db.php';

include '/opt/lampp/htdocs/opencart/upload/system/library/db/mpdo.php';
include '/opt/lampp/htdocs/opencart/upload/system/library/db/mssql.php';
include '/opt/lampp/htdocs/opencart/upload/system/library/db/mysql.php';
include '/opt/lampp/htdocs/opencart/upload/system/library/db/mysqli.php';
include '/opt/lampp/htdocs/opencart/upload/system/library/db/postgre.php';

/**
 * Convenient class to create a new connection
 * Warning you must close the connection for your own after the use.
 *
 * @author Rui Carlos Lorenzetti da Silva
 *
 */
class DBUtils{

	/**
	 * return the configurated db connection
	 * Have to exists config.php configurated
	 *
	 * @return DB
	 */
	static public function getNewConnection(){

		$db = new \DB(DB_DRIVER, DB_HOSTNAME,DB_USERNAME, DB_PASSWORD,DB_DATABASE,DB_PORT);
		
		return $db;

	}

}



