<?php

class DeleteThreadRequest extends Threads implements IRequest
{
	public function handle_request(array $data) : string
	{
		$success = $this->deleteThread($data['id']);

		$response = new JsonResponse();
		$response->append('success', $success);

		return $response->to_json();
	}
}