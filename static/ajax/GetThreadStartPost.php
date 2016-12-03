<?php require '../global.php';

$request = BoardCore::getRequestController();
$thread = BoardCore::getThreadController();

// Is request coming from this website?
$request->isCorrectReferer() or die();

// Load dem $_GET's
$request->loadGetRequest();
$request->issetAndNotEmpty('GET', 'id') or die();

$data = $thread->getStartPost($request->get['id']);
if ($data) {
	echo json_encode([
		'data' => $data,
		'success' => true
	]);
}
else {
	echo json_encode([
		'data' => 'Cannot get thread start post.',
		'success' => false
	]);
}
