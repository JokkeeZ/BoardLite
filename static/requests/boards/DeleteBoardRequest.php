<?php defined('APP') or die;

class DeleteBoardRequest extends BoardController implements IRequest
{
	public function handle_request($data) : string
	{
		$response = new JsonResponse();
		
		$prefix = $this->get_prefix_with_id($data['id']);
		if ($prefix == null) {
			$response->append('success', false);
			return $response->to_json();
		}

		$threads = new ThreadController();
		$rr = $threads->delete_threads_with_prefix($prefix);

		$success = $this->delete_board($data['id']);
	
		$response->append('success', $success);
		return $response->to_json();
	}
}
