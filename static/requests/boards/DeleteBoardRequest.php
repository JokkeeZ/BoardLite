<?php

class DeleteBoardRequest extends Boards implements IRequest
{
	public function handle_request(array $data) : string
	{
		$response = new JsonResponse();
		
		$prefix = $this->getPrefixWithId($data['id']);
		if ($prefix == null) {
			$response->append('success', false);
			return $response->to_json();
		}

		$threads = new Threads();
		$rr = $threads->deleteThreadsWithPrefix($prefix);

		$success = $this->deleteBoard($data['id']);
	
		$response->append('success', $success);
		return $response->to_json();
	}
}
