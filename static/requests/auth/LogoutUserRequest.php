<?php defined('APP') or die;

class LogoutUserRequest extends AuthenticationController implements IRequest
{
	public function handle_request($data) : string
	{
		$success = $this->logout();

		$response = new JsonResponse();
		$response->append('success', $success);

		return $response->to_json();
	}
}
