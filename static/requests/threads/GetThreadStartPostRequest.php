<?php

class GetThreadStartPostRequest extends Threads implements IRequest
{
	public function handle_request(array $data) : string
	{
		$startPost = $this->getStartPost($data['id']);
		
		$response = new JsonResponse();
		$response->append('data', $startPost);
		$response->append('success', !empty($startPost));

		return $response->to_json();
	}
}