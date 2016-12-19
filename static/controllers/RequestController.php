<?php defined('APP') or die;

/**
 * Provides functionality for filtering requests incase there is some script kiddies,
 * verifies for referer coming only from given url and also verifies that request is XMLHttpRequest if needed.
 * 
 * @author JokkeeZ
 * @version 1.0
 * @copyright Copyright © 2016 JokkeeZ
 */
class RequestController {
	
	/**
	 * Hold's all filtered $_POST request values accessed by POST request key.
	 */
	public $post = [];
	
	/**
	 * Hold's all filtered $_GET request values accessed by GET request key.
	 */
	public $get = [];

	/**
	 * Load's POST request into $post array escaped and stuff.
	 * @return void
	 */
	public function loadPostRequest() {
		foreach ($_POST as $k => $v) {
			$this->post[$k] = $this->filterRequest($v);
		}
	}

	/**
	 * Load's GET request into $get array escaped and stuff.
	 * @return void
	 */
	public function loadGetRequest() {
		foreach ($_GET as $k => $v) {
			$this->get[$k] = $this->filterRequest($v);
		}
	}

	/**
	 * Check's if array value isset and not empty accessed by,
	 * POST and GET. POST for $post array, and GET for $get array. 
	 * 
	 * @param string $type POST/GET
	 * @param mixed $value Key for accessing data.
	 * 
	 * @return boolean
	 */
	public function issetAndNotEmpty($type, $value) {
		if ($type != 'POST' && $type != 'GET') return false;
		
		if ($type == 'POST') {
			return isset($this->post[$value]) && !empty($this->post[$value]);
		}
		
		return isset($this->get[$value]) && !empty($this->get[$value]);
	}

	/**
	 * Check's if array value isset accessed by,
	 * POST and GET. POST for $post array, and GET for $get array.
	 *
	 * @param string $type POST/GET
	 * @param mixed $value Key for accessing data.
	 * 
	 * @return boolean
	 */
	public function requestIsSet($type, $value) {
		if ($type != 'POST' && $type != 'GET') return false;
			
		if ($type == 'POST') {
			return isset($this->post[$value]);
		}
		
		return isset($this->get[$value]);
	}

	/**
	 * Converts HTML into bit more secure format.
	 * @param string $value
	 * 
	 * @return string
	 */
	public function filterRequest($value) {
		return htmlspecialchars(strip_tags($value));
	}

	/**
	 * Verifies that HTTP_X_REQUESTED_WITH is XMLHttpRequest
	 * @return boolean
	 */
	public function isXMLHttpRequest() {
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
	}
	
	/**
	 * Verifies that HTTP_REFERER is coming from value set in config/Configuration.php file
	 * $_CONFIG['app_url'] holds that value.
	 *  
	 * @return boolean
	 */
	public function isCorrectReferer() {
		global $_CONFIG;
		return isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == $_CONFIG['app_url'];
	}
	
	/**
	 * Verifies that csrf token received with request matches with session token.
	 * 
	 * @return boolean
	 */
	public function verifyToken() {
		return $this->issetAndNotEmpty('POST', 'token') && hash_equals($_SESSION['token'], $this->post['token']);
	}
}
