<?php require '../global.php';

$request = BoardCore::getRequestController();
$thread = BoardCore::getThreadController();

// Load dem $_GET's
$request->loadGetRequest();

// $_GET['id'] is set and contains value?
$request->issetAndNotEmpty('GET', 'id') or die();

// Ok, lets get some replies.
$data = $thread->getReplys($request->get['id']);
if ($data != null) {
	echo json_encode([
		'data' => $data,
		'success' => true
	]);
}
else {
	echo json_encode([
		'data' => 'Cannot get thread replys.',
		'success' => false
	]);
}