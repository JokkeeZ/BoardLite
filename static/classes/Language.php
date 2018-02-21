<?php

/**
 * Class used for selecting language which will be used on website.
 *
 * @author JokkeeZ
 * @version 1.2
 *
 * @copyright Copyright © 2016 - 2018 JokkeeZ
 * @license Licensed under MIT License.
 */
class Language extends Controller
{
	/**
	 * Get's .json file contents from current language file.
	 *
	 * @return string
	 */
	public function getLanguageJson() : string
	{
		$contents = file_get_contents(LANG_PATH . $this->config['app_lang'] . '.json');

		if (strlen($contents) > 0)
			return $contents;

		// Fallback to en_US
		return file_get_contents(LANG_PATH . 'en_US.json');
	}
}
