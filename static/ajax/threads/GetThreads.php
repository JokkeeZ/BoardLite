<?php require '../../global.php';

// Is request coming from this website?
$request->isCorrectReferer() or die;

// Load dem $_GET's
$request->loadGetRequest();
$request->issetAndNotEmpty('GET', 'prefix') or die;

$data = $thread->getThreads($request->get['prefix']);
if ($data) {
	echo json_encode([
		'data' => $data,
		'success' => true
	]);
}
else {
	echo json_encode([
		'data' => 'Cannot get threads.',
		'success' => false
	]);
}
