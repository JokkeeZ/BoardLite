<?php defined('APP') or die;

class DeleteBoardRequest extends BoardController implements IRequest {

	public function handle_request($data) : string {
		$success = $this->delete_board($data['id']);
	
		$response = new JsonResponse();
		$response->append('success', $success);

		return $response->to_json();
	}
}