<?php require '../../global.php';

$request->isCorrectReferer() or die;

$request->isXMLHttpRequest() or die;

$request->loadPostRequest();

$request->verifyToken() or die;

$request->issetAndNotEmpty('POST', 'id') or die;

$success = $board->deleteBoard($request->post['id']);

echo json_encode([
	'status' => $success
]);