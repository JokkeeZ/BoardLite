<?php

class LogoutUserRequest extends Authentication implements IRequest
{
	public function handle_request(array $data) : string
	{
		$success = $this->logout();

		$response = new JsonResponse();
		$response->append('success', $success);

		return $response->to_json();
	}
}
