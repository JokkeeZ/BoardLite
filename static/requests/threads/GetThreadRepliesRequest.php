<?php

class GetThreadRepliesRequest extends Threads implements IRequest
{
	public function handle_request(array $data) : string
	{
		$replies = $this->getReplies($data['id']);
		
		$response = new JsonResponse();
		$response->append('data', empty($replies) ? '' : $replies);
		$response->append('success', !empty($replies));

		return $response->to_json();
	}
}
