<?php defined('APP') or die;

class GetThreadStartPostRequest extends ThreadController implements IRequest {

	public function handle_request($data) : string {
		$startPost = $this->get_start_post($data['id']);
		
		$response = new JsonResponse();
		$response->append('data', $startPost);
		$response->append('success', !empty($startPost));

		return $response->to_json();
	}
}