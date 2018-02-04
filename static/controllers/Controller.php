<?php defined('APP') or die;

/**
 * Simple abstract class for easy database access for controllers and other often used functionality.
 *
 * @author JokkeeZ
 * @version 1.0
 * @copyright Copyright © 2016 - 2018 JokkeeZ
 */
abstract class Controller
{
	/**
	 * Get's $_CONFIG array without defining global every single time.
	 */
	protected $config = [];

	/**
	 * Initializes a new instance of the Controller class, with default values.
	 */
	public function __construct()
	{
		global $_CONFIG;
		$this->config = $_CONFIG;

		foreach ($_POST as $k => $v) {
			$_POST[$k] = $this->filter_input($v);
		}

		foreach ($_GET as $k => $v) {
			$_GET[$k] = $this->filter_input($v);
		}
	}

	/**
	 * Filters input for bad characters etc.
	 */
	private function filter_input($input)
	{
		return htmlspecialchars(strip_tags($input));
	}

	/**
	 * Get's a new instance of the Database class.
	 */
	protected function get_database() : Database
	{
		return new Database();
	}
}