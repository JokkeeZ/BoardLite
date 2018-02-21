<?php

class GetThreadsRequest extends Threads implements IRequest
{
	public function handle_request(array $data) : string
	{
		$threads = $this->getThreads($data['prefix']);

		$response = new JsonResponse();
		$response->append('data', $threads);
		$response->append('success', !empty($threads));

		return $response->to_json();
	}
}