<?php

class LockThreadRequest extends Threads implements IRequest
{
	public function handle_request(array $data) : string
	{
		$success = $this->setThreadLockState($data['id'], $data['state']);

		$response = new JsonResponse();
		$response->append('success', $success);
		
		return $response->to_json();
	}
}