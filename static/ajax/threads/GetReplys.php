<?php require '../../global.php';

$request->isCorrectReferer() or die;

// $_GET['id'] is set and contains value?
$request->issetAndNotEmpty('GET', 'id') or die;

// Ok, lets get some replies.
$data = $thread->getReplys($request->get['id']);
if (!empty($data)) {
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
