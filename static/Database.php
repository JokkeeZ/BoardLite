<?php defined('APP') or die();

/**
 * Simple Database class using PDO.
 * 
 * @author JokkeeZ
 * @version 1.0
 * @copyright Copyright © 2016 JokkeeZ
 */
class Database extends PDO {
	
	/**
	 * Initializes a new instance of Database with given values.
	 * @param string $connString
	 * @param string $host
	 * @param string $name
	 * @param string $user
	 * @param string $pass
	 */
	public function __construct($connString, $host, $name, $user, $pass) {
		try {
			global $_CONFIG;
			parent::__construct(sprintf($connString, $host, $name), $user, $pass);
			$this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} 
		catch (PDOException $e) {
			//DO SOMETHING WITH THIS EXCEPTION, MAYBE LOG IT IN THE FILE..
			//echo $e->getMessage();
		}
	}
}
