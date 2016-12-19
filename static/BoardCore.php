<?php defined('APP') or die();

/**
 * Core for whole backend functionality.
 * This class initializes controllers and services as 'get' provider for them.
 * 
 * @author JokkeeZ
 * @version 1.0
 * @copyright Copyright © 2016 JokkeeZ
 */
class BoardCore {

	private static $boardController;
	private static $fileController;
	private static $threadController;
	private static $requestController;
	private static $languageController;
	private static $authenticationController;
	private static $database;
	
	/**
	 * Get's an instance of BoardController class.
	 * @return An instance of BoardController.
	 */
	public static function getBoardController() {
		return self::$boardController;
	}

	/**
	 * Get's an instance of FileController class.
	 * @return An instance of FileController.
	 */
	public static function getFileController() {
		return self::$fileController;
	}

	/**
	 * Get's an instance of ThreadController class.
	 * @return An instance of ThreadController.
	 */
	public static function getThreadController() {
		return self::$threadController;
	}

	/**
	 * Get's an instance of RequestController class.
	 * @return An instance of RequestController.
	 */
	public static function getRequestController() {
		return self::$requestController;
	}

	/**
	 * Get's an instance of Database class.
	 * @return An instance of Database.
	 */
	public static function getDatabase() {
		return self::$database;
	}
	
	/**
	 * Get's an instance of LanguageController class.
	 * @return An instance of Database.
	 */
	public static function getLanguageController() {
		return self::$languageController;
	}

    /**
     * Get's an instance of AuthenticationController class.
     * @return An instance of AuthenticationController.
     */
	public static function getAuthenticationController() {
		return self::$authenticationController;
	}
	
	/**
	 * Initializes an new instances of controller classes.
	 * @return void
	 */
	public static function initialize() {
		global $_CONFIG;
		self::$database = new Database(
			$_CONFIG['db_conn_str'],
			$_CONFIG['db_host'],
			$_CONFIG['db_name'],
			$_CONFIG['db_user'],
			$_CONFIG['db_pass']
		);
		
		self::$boardController = new BoardController();
		self::$fileController = new FileController();
		self::$threadController = new ThreadController();
		self::$requestController = new RequestController();
		self::$languageController = new LanguageController();
		self::$authenticationController = new AuthenticationController();
	}
	
	/**
	 * Creates new random 32 bytes long token and saves it to session.
	 * @return string $_SESSION['token']
	 */
	public static function createToken() {
		if (empty($_SESSION['token'])) {
			$_SESSION['token'] = bin2hex(random_bytes(32));
		}
		return $_SESSION['token'];
	}
}
