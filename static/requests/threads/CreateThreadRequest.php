<?php defined('APP') or die;

class CreateThreadRequest extends ThreadController implements IRequest {

	public function handle_request($data) : string {
		$file = new FileController();
		$uploaded = $file->upload();
		
		$target_file = '';
		if ($uploaded) {
			$target_file = '../../uploads/' . $file->get_name();
		}

		$thread = $this->create_thread(
			((empty($data['title'])) ? '' : $data['title']),
			$data['message'],
			$data['prefix'],
			$target_file
		);

		$response = new JsonResponse();
		$response->append('data', $thread);

		if (!isset($_FILES['file']))
			$response->append('success', true);
		else
			$response->append('success', $uploaded);

		return $response->to_json();
	}
}