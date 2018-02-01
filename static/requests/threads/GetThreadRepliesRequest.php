<?php defined('APP') or die;

class GetThreadRepliesRequest extends ThreadController implements IRequest {

	public function handle_request($data) : string {
		$replies = $this->get_replies($data['id']);
		
		$response = new JsonResponse();
		$response->append('data', empty($replies) ? '' : $replies);
		$response->append('success', !empty($replies));

		return $response->to_json();
	}
}
