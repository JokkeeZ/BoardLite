<?php

/**
 * Represents backend -> frontend response for requests.
 * 
 * @author JokkeeZ
 * @version 1.0
 *
 * @copyright Copyright © 2018 JokkeeZ
 * @license Licensed under MIT License.
 */
class JsonResponse
{
	private $content = [];

	public function append($key, $value)
	{
		$this->content[$key] = $value;
	}

	public function init_data($data)
	{
		$this->content = $data;
	}

	public function to_json() : string
	{
		return json_encode($this->content);
	}
}