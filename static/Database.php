<?php defined('APP') or die;

/**
 * Simple Database class using PDO.
 * 
 * @author JokkeeZ
 * @version 1.1
 * @copyright Copyright © 2016 - 2018 JokkeeZ
 */
class Database extends PDO
{
	/**
	 * Initializes a new instance of the Database class with given values.
	 */
	public function __construct()
	{
		global $_CONFIG;

		try {
			parent::__construct(sprintf(
				$_CONFIG['db_conn_str'],
				$_CONFIG['db_host'],
				$_CONFIG['db_name']),
				$_CONFIG['db_user'],
				$_CONFIG['db_pass']);

			$this->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			$this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch (PDOException $e) {
			Logger::write_data($e->getMessage());
			die;
		}
	}
}
