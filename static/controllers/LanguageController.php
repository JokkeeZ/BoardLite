<?php defined('APP') or die;

/**
 * Class used for selecting language which will be used on website.
 * 
 * @author JokkeeZ
 * @version 1.0
 * @copyright Copyright © 2016 JokkeeZ
 */
class LanguageController {
	
	/**
	 * Get's .json file contents from given $_CONFIG['app_lang'] file.
	 * 
	 * @return string
	 */
	public function getContents() {
		global $_CONFIG;
		
		// Path for language file
		$path = '../../assets/lang/' . $_CONFIG['app_lang'] . '.json';
		if (file_exists($path)) {
			return file_get_contents($path);
		}
		
		// Let's return en_US then, since it's default language.
		return file_get_contents('../../assets/lang/en_US.json');
	}	
}