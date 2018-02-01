<?php defined('APP') or die;

class LoginUserRequest extends AuthenticationController implements IRequest {

	public function handle_request($data) : string {
		$success = $this->login($data['name'], $data['pass']);

		$response = new JsonResponse();
		$response->append('success', $success);
		$response->append('user', $success ? $_SESSION['user'] : null);

		return $response->to_json();
	}
}