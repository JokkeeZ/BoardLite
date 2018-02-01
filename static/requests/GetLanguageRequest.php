<?php defined('APP') or die;

class GetLanguageRequest extends LanguageController implements IRequest {

	public function handle_request($data) : string {
		return $this->get_language_json();
	}
}