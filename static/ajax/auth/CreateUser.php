<?php require '../../global.php';

$request->isCorrectReferer() or die;

$request->isXMLHttpRequest() or die;

$request->verifyToken() or die;

$request->issetAndNotEmpty('POST', 'name') or die;

$request->issetAndNotEmpty('POST', 'pass') or die;

$success = $auth->register($request->post['name'], $request->post['pass']);

echo json_encode([
	'status' => $success
]);
