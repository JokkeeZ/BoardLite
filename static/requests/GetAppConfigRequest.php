<?php

class GetAppConfigRequest extends Controller implements IRequest
{
	public function handle_request(array $data) : string
	{
		$response = new JsonResponse();
		$response->append('app_name', $this->config['app_name']);

		return $response->to_json();
	}
}