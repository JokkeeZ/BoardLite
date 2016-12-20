<?php require '../../global.php';

$request->isCorrectReferer() or die;

$request->isXMLHttpRequest() or die;

$request->verifyToken() or die;

$request->issetAndNotEmpty('POST', 'session_close') or die;

$success = $auth->logout();
echo json_encode([
	'status' => $success
]);
