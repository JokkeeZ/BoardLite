<?php defined('APP') or die;

class DeleteThreadRequest extends ThreadController implements IRequest {

	public function handle_request($data) : string {
		$success = $this->delete_thread($data['id']);

		$response = new JsonResponse();
		$response->append('success', $success);

		return $response->to_json();
	}
}