<?php

class GetBoardsRequest extends Boards implements IRequest
{
	public function handle_request(array $data) : string
	{
		$response = new JsonResponse();
		$response->init_data($this->getBoards());

		return $response->to_json();
	}
}