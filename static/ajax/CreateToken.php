<?php require '../global.php';

$request->isCorrectReferer() or die;

$request->isXMLHttpRequest() or die;

$request->loadPostRequest();
$request->issetAndNotEmpty('POST', 'token_creation') or die;

$token = BoardCore::createToken();
echo json_encode([
	'token' => $token
]);