<?php  namespace App\Eloquent; 

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;

/**
 * Class Encapsulator
 *
 * @author Original encapsulation pattern contributed by Kayla Daniels
 * @package App\Eloquent
 */
class Encapsulator
{
	private static $conn;

	private function __construct() {}

	static public function init()
	{
		$capsule = null;

		if (is_null(self::$conn))
		{
			$capsule = new Capsule;

			$capsule->addConnection([
				'driver'    => 'mysql',
				'host'      => DB_HOSTNAME,
				'database'  => DB_DATABASE,
				'username'  => DB_USERNAME,
				'password'  => DB_PASSWORD,
				'charset'   => 'utf8',
				'collation' => 'utf8_unicode_ci',
				'prefix'    => DB_PREFIX,
			]);

			$capsule->setEventDispatcher(new Dispatcher(new Container));

			$capsule->setAsGlobal();

			$capsule->bootEloquent();
		}
		return $capsule;
	}
}
