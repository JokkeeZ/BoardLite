<?php defined('APP') or die;

/**
 * Represents backend -> frontend response for requests.
 * 
 * @author JokkeeZ
 * @version 1.0
 * @copyright Copyright © 2018 JokkeeZ
 */
class JsonResponse {
	private $content = [];

	public function append($key, $value) {
		$this->content[$key] = $value;
	}

	public function init_data($data):JsonResponse {
		$this->content = $data;
		return $this;
	}

	public function to_json():string {
		return json_encode($this->content);
	}
}