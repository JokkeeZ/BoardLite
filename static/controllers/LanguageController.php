<?php defined('APP') or die;

/**
 * Class used for selecting language which will be used on website.
 *
 * @author JokkeeZ
 * @version 1.1
 * @copyright Copyright © 2016 JokkeeZ
 */
class LanguageController extends Controller {
	
	/**
	 * Get's .json file contents from given $_CONFIG['app_lang'] file.
	 *
	 * @return string
	 */
	public function getContents():string {
		// Path for language file
		$path = $_SERVER['DOCUMENT_ROOT'] . '/assets/lang/' . $this->config['app_lang'] . '.json';

		if (file_exists($path)) {
			return file_get_contents($path);
		}
		
		// Let's return en_US then, since it's default language.
		return file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/assets/lang/en_US.json');
	}
}
