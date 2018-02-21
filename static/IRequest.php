<?php

/**
 * Represents frontend to backend request.
 *
 * @author JokkeeZ
 * @version 1.0
 *
 * @copyright Copyright © 2018 JokkeeZ
 * @license Licensed under MIT License.
 */
interface IRequest
{
	/**
	 * Handles received request.
	 *
	 * @param array $data If POST request, $_POST array; otherwise $_GET
	 * @return string Returns backend response as JSON string.
	 */
	public function handle_request(array $data) : string;
}