<?php

class GetLanguageRequest extends Language implements IRequest
{
	public function handle_request(array $data) : string
	{
		return $this->getLanguageJson();
	}
}