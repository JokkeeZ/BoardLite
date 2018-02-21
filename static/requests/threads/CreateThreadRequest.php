<?php

class CreateThreadRequest extends Threads implements IRequest
{
	public function handle_request(array $data) : string
	{
		$file = new File();
		$uploaded = $file->upload();
		
		$target_file = '';
		if ($uploaded) {
			$target_file = '../../uploads/' . $file->getName();
		}

		$thread = $this->createThread(
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