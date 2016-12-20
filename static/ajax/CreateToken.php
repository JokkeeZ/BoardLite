<?php require '../global.php';

$request->isCorrectReferer() or die;

$request->isXMLHttpRequest() or die;

$request->issetAndNotEmpty('POST', 'token_creation') or die;

$token = $request->createToken();
echo json_encode([
	'token' => $token
]);
