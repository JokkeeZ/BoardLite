<?php defined('APP') or die;

class UpdateBoardRequest extends BoardController implements IRequest
{
	public function handle_request($data) : string
	{
		$oldPrefix = $this->get_prefix_with_id($data['id']);

		$success = $this->update_board(
			$data['id'],
			$data['name'],
			$data['desc'],
			$data['prefix'],
			$data['tag']
		);

		$threads = new ThreadController();
		$threads->update_thread_prefixes($oldPrefix, $data['prefix']);
		
		$response = new JsonResponse();
		$response->append('success', $success);

		return $response->to_json();
	}
}
