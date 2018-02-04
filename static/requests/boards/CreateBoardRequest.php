<?php defined('APP') or die;

class CreateBoardRequest extends BoardController implements IRequest
{
	public function handle_request($data) : string
	{
		$success = $this->create_board(
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