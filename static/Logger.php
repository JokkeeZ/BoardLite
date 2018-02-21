<?php

/**
 * Class used for logging stuff on debug sessions.
 * 
 * @author JokkeeZ
 * @version 1.0
 *
 * @copyright Copyright © 2018 JokkeeZ
 * @license Licensed under MIT License.
 */
class Logger
{
	/**
	 * Writes string into log file.
	 *
	 * @param mixed $data Data to be appended.
	 * @return void
	 */
	public static function write_data($data)
	{
		file_put_contents('logs.log', $data . PHP_EOL, FILE_APPEND | LOCK_EX);
	}
}
