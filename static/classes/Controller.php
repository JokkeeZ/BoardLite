<?php

/**
 * Simple abstract class for easy database access for classes and other often used functionality.
 *
 * @author JokkeeZ
 * @version 1.0
 *
 * @copyright Copyright © 2016 - 2018 JokkeeZ
 * @license Licensed under MIT License.
 */
abstract class Controller
{
	/**
	 * @var array $config Get's $_CONFIG array without defining global every single time.
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
			$_POST[$k] = $this->filterInput($v);
		}

		foreach ($_GET as $k => $v) {
			$_GET[$k] = $this->filterInput($v);
		}
	}

	/**
	 * Filters input for bad characters etc.
	 *
	 * @param string $input Input to be filtered
	 * @return string
	 */
	private function filterInput($input)
	{
		return htmlspecialchars(strip_tags($input));
	}

	/**
	 * Get's a new instance of the Database class.
	 *
	 * @return Database Returns a new instance of the Database class.
	 */
	protected function getDatabase() : Database
	{
		return new Database();
	}
}