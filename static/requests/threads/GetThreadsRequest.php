<?php defined('APP') or die;

class GetThreadsRequest extends ThreadController implements IRequest {

	public function handle_request($data) : string {
		$threads = $this->get_threads($data['prefix']);

		$response = new JsonResponse();
		$response->append('data', $threads);
		$response->append('success', !empty($threads));

		return $response->to_json();
	}
}