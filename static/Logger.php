<?php

/**
 * Class used for logging stuff on debug sessions.
 * 
 * @author JokkeeZ
 * @version 1.0
 * @copyright Copyright © 2018 JokkeeZ
 */
class Logger {
	private static $file = 'logs.log';

	/**
	 * Writes string into log file.
	 */
	public static function write_data($data) {
		file_put_contents(self::$file, $data, FILE_APPEND | LOCK_EX);
	}
}
