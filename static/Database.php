<?php defined('APP') or die();

/**
 * Simple Database class using PDO.
 * 
 * @author JokkeeZ
 * @version 1.1
 * @copyright Copyright © 2016 JokkeeZ
 */
class Database extends PDO {
	
	/**
	 * Initializes a new instance of Database with given values.
	 */
	public function __construct() {
		try {
		    global $_CONFIG;
			parent::__construct(sprintf($_CONFIG['db_conn_str'], $_CONFIG['db_host'], $_CONFIG['db_name']), $_CONFIG['db_user'], $_CONFIG['db_pass']);
			$this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch (PDOException $e) {
		    // TODO: THIS IS ONLY USED IN DEVELOPMENT, DO NOT SHOW EXCEPTIONS ON ACTUAL APP.
            echo $e->getMessage();
		}
	}
}
