<?php defined('APP') or die;

/**
 * Represents frontend to backend request.
 *
 * @author JokkeeZ
 * @version 1.0
 * @copyright Copyright © 2018 JokkeeZ
 */
interface IRequest {

	/**
	 * Handles received request and returns output as JSON string
	 */
	public function handle_request($data) : string;
}