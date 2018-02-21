<?php

class CreateUserRequest extends Authentication implements IRequest
{
	public function handle_request(array $data) : string
	{
		$success = $this->register($data['name'], $data['pass']);

		$response = new JsonResponse();
		$response->append('success', $success);

		return $response->to_json();
	}
}