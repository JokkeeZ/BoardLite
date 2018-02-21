<?php

class GetRulesRequest implements IRequest
{
	public function handle_request(array $data) : string
	{
		global $_RULES;
		
		$response = new JsonResponse();
		$response->init_data($_RULES);

		return $response->to_json();
	}
}