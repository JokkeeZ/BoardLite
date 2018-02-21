<?php

class CreateBoardRequest extends Boards implements IRequest
{
	public function handle_request(array $data) : string
	{
		$success = $this->createBoard(
			$data['name'],
			$data['desc'],
			$data['prefix'],
			$data['tag']
		);
		
		$response = new JsonResponse();
		$response->append('success', $success);

		return $response->to_json();
	}
}