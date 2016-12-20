<?php defined('APP') or die;

/**
 * Provides functionality for filtering requests incase there is some script kiddies,
 * verifies for referer coming only from given url and also verifies that request is XMLHttpRequest if needed.
 * 
 * @author JokkeeZ
 * @version 1.1
 * @copyright Copyright © 2016 JokkeeZ
 */
class RequestController extends Controller {
	
	/**
	 * Hold's all filtered $_POST request values accessed by POST request key.
	 */
	public $post = [];
	
	/**
	 * Hold's all filtered $_GET request values accessed by GET request key.
	 */
	public $get = [];

    /**
     * Initializes an new instance of RequestController with default values.
     */
	public function __construct() {
        parent::__construct();

        $this->loadGetRequest();
        $this->loadPostRequest();
    }

    /**
     * Load's POST request into $post array escaped and stuff.
     * @return void
     */
    private function loadPostRequest() {
        foreach ($_POST as $k => $v) {
            $this->post[$k] = $this->filterRequest($v);
        }
    }

    /**
     * Load's GET request into $get array escaped and stuff.
     * @return void
     */
    private function loadGetRequest() {
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
	 * @return boolean
	 */
	public function issetAndNotEmpty($type, $value):bool {
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
	 * @return boolean
	 */
	public function requestIsSet($type, $value):bool {
		if ($type != 'POST' && $type != 'GET') return false;
			
		if ($type == 'POST') {
			return isset($this->post[$value]);
		}
		
		return isset($this->get[$value]);
	}

	/**
	 * Converts HTML into bit more secure format.
     *
	 * @param string $value
	 * @return string
	 */
	public function filterRequest($value):string {
		return htmlspecialchars(strip_tags($value));
	}

	/**
	 * Verifies that HTTP_X_REQUESTED_WITH is XMLHttpRequest
     *
	 * @return boolean
	 */
	public function isXMLHttpRequest():bool {
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
	}
	
	/**
	 * Verifies that HTTP_REFERER is coming from value set in config/Configuration.php file
	 * $_CONFIG['app_url'] holds that value.
	 *  
	 * @return boolean
	 */
	public function isCorrectReferer():bool {
		return isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == $this->config['app_url'];
	}
	
	/**
	 * Verifies that csrf token received with request matches with session token.
	 * 
	 * @return boolean
	 */
	public function verifyToken():bool {
		return $this->issetAndNotEmpty('POST', 'token') && hash_equals($_SESSION['token'], $this->post['token']);
	}

    /**
     * Creates random 32 bytes long token and saves it to session.
     *
     * @return string
     */
    public function createToken():string {
        if (empty($_SESSION['token'])) {
            $_SESSION['token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['token'];
    }
}
