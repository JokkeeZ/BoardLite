<?php

class LockThreadRequest extends ThreadController implements IRequest
{
	public function handle_request($data) : string
	{
		$success = $this->set_thread_lock_state($data['id'], $data['state']);

		$response = new JsonResponse();
		$response->append('success', $success);
		
		return $response->to_json();
	}
}