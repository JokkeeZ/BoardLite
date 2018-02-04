<?php defined('APP') or die;

class AddReplyRequest extends ThreadController implements IRequest
{
	public function handle_request($data) : string
	{
		$file = new FileController();
		$uploaded = $file->upload();
		
		$target_file = '';
		if ($uploaded)
			$target_file = '../../uploads/' . $file->get_name();

		$replyData = $this->add_reply(
			$data['message'],
			$data['thread_id'],
			$target_file
		);

		$response = new JsonResponse();
		$response->append('data', $replyData);

		if (!isset($_FILES['file']))
			$response->append('success', true);
		else
			$response->append('success', $uploaded);

		return $response->to_json();
	}
}