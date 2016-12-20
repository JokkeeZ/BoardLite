<?php require '../../global.php';

$request->isCorrectReferer() or die;

$request->isXMLHttpRequest() or die;

$request->verifyToken() or die;

$request->issetAndNotEmpty('POST', 'name') or die;
$request->issetAndNotEmpty('POST', 'desc') or die;
$request->issetAndNotEmpty('POST', 'prefix') or die;
$request->issetAndNotEmpty('POST', 'tag') or die;

$success = $board->createBoard(
	$request->post['name'],
	$request->post['desc'],
	$request->post['prefix'],
	$request->post['tag']
);

echo json_encode([
	'status' => $success
]);
