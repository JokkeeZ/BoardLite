<?php defined('APP') or die;

class GetBoardsRequest extends BoardController implements IRequest
{
	public function handle_request($data) : string
	{
		$response = new JsonResponse();
		$response->init_data($this->get_boards());

		return $response->to_json();
	}
}