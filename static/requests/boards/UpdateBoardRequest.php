<?php

class UpdateBoardRequest extends Boards implements IRequest
{
	public function handle_request(array $data) : string
	{
		$oldPrefix = $this->getPrefixWithId($data['id']);

		$success = $this->updateBoard(
			$data['id'],
			$data['name'],
			$data['desc'],
			$data['prefix'],
			$data['tag']
		);

		$threads = new Threads();
		$threads->updateThreadPrefixes($oldPrefix, $data['prefix']);
		
		$response = new JsonResponse();
		$response->append('success', $success);

		return $response->to_json();
	}
}
