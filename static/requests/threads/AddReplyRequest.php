<?php

class AddReplyRequest extends Threads implements IRequest
{
	public function handle_request(array $data) : string
	{
		$file = new File();
		$uploaded = $file->upload();
		
		$target_file = '';
		if ($uploaded)
			$target_file = '../../uploads/' . $file->getName();

		$replyData = $this->addReply(
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