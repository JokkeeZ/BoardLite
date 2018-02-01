<?php defined('APP') or die;

class CreateUserRequest extends AuthenticationController implements IRequest {

	public function handle_request($data) : string {
		$success = $this->register($data['name'], $data['pass']);

		$response = new JsonResponse();
		$response->append('success', $success);

		return $response->to_json();
	}
}