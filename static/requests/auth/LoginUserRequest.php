<?php

class LoginUserRequest extends Authentication implements IRequest
{
	public function handle_request(array $data) : string
	{
		$success = $this->login($data['name'], $data['pass']);

		$response = new JsonResponse();
		$response->append('success', $success);
		$response->append('user', $success ? $_SESSION['user'] : null);

		return $response->to_json();
	}
}