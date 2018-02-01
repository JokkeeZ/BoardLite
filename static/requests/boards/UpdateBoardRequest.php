<?php defined('APP') or die;

class UpdateBoardRequest extends BoardController implements IRequest {

	public function handle_request($data) : string {
		$success = $this->update_board(
			$data['id'],
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