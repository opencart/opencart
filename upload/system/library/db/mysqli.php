<?php
namespace Opencart\System\Library\DB;
/**
 * Class MySQLi
 *
 * @package
 */
class MySQLi {
	/**
	 * @var object|\mysqli|null
	 */
	private object|null $connection;

	/**
	 * Constructor
	 *
	 * @param    string  $hostname
	 * @param    string  $username
	 * @param    string  $password
	 * @param    string  $database
	 * @param    string  $port
	 */
	public function __construct(string $hostname, string $username, string $password, string $database, string $port = '', string $sslKey='', string $sslCert='', string $sslCa='') {
		if (!$port) {
			$port = '3306';
		}
		$tempSSLKeyFile = null;
		$tempSSLCertFile = null;
		$tempSSLCaFile = null;
		if($sslKey){
			$tempSSLKeyFile = tempnam(sys_get_temp_dir(), 'mysqli_key_');
			$fileHandle = fopen($tempSSLKeyFile, 'w');
			fwrite($fileHandle, $sslKey);
			fclose($fileHandle);
		}
		if($sslCert){
			$tempSSLCertFile = tempnam(sys_get_temp_dir(), 'mysqli_cert_');
			$fileHandle = fopen($tempSSLCertFile, 'w');
			fwrite($fileHandle, $sslCert);
			fclose($fileHandle);
		}
		if($sslCa){
			$tempSSLCaFile = tempnam(sys_get_temp_dir(), 'mysqli_ca_');
			$fileHandle = fopen($tempSSLCaFile, 'w');
			fwrite($fileHandle,'-----BEGIN CERTIFICATE-----' . PHP_EOL . $sslCa . PHP_EOL . '-----END CERTIFICATE-----');
			fclose($fileHandle);
		}
		

		try {
			
			$this->connection =  mysqli_init();
			$this->connection->ssl_set($tempSSLKeyFile, $tempSSLCertFile, $tempSSLCaFile, null, null);

			if ($tempSSLCaFile || $tempSSLCertFile || $tempSSLKeyFile) {
				$this->connection->real_connect($hostname, $username, $password, $database, $port,null,MYSQLI_CLIENT_SSL);
			}else{
				$this->connection->real_connect($hostname, $username, $password, $database, $port,null);
			}

			$this->connection->set_charset('utf8mb4');
			
			$this->query("SET SESSION sql_mode = 'NO_ZERO_IN_DATE,NO_ENGINE_SUBSTITUTION'");
			$this->query("SET FOREIGN_KEY_CHECKS = 0");

			// Sync PHP and DB time zones
			$this->query("SET `time_zone` = '" . $this->escape(date('P')) . "'");
		} catch (\mysqli_sql_exception $e) {
			throw new \Exception('Error: Could not make a database link using ' . $username . '@' . $hostname . '!<br/>Message: ' . $e->getMessage());
		}
	}

	/**
	 * Query
	 *
	 * @param    string  $sql
	 *
	 * @return   bool|object
	 */
	public function query(string $sql): bool|object {
		try {
			$query = $this->connection->query($sql);

			if ($query instanceof \mysqli_result) {
				$data = [];

				while ($row = $query->fetch_assoc()) {
					$data[] = $row;
				}

				$result = new \stdClass();
				$result->num_rows = $query->num_rows;
				$result->row = isset($data[0]) ? $data[0] : [];
				$result->rows = $data;

				$query->close();

				unset($data);

				return $result;
			} else {
				return true;
			}
		} catch (\mysqli_sql_exception $e) {
			throw new \Exception('Error: ' . $this->connection->error  . '<br/>Error No: ' . $this->connection->errno . '<br/>' . $sql);
		}
	}

	/**
	 * Escape
	 *
	 * @param    string  value
	 *
	 * @return   string
	 */
	public function escape(string $value): string {
		return $this->connection->real_escape_string($value);
	}
	
	/**
	 * countAffected
	 *
	 * @return   int
	 */
	public function countAffected(): int {
		return $this->connection->affected_rows;
	}

	/**
	 * getLastId
	 *
	 * @return   int
	 */
	public function getLastId(): int {
		return $this->connection->insert_id;
	}
	
	/**
	 * isConnected
	 *
	 * @return   bool
	 */
	public function isConnected(): bool {
		return $this->connection;
	}

	/**
	 * Destructor
	 *
	 * Closes the DB connection when this object is destroyed.
	 *
	 */
	public function __destruct() {
		if ($this->connection) {
			$this->connection->close();

			$this->connection = null;
		}
	}
}
