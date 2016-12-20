<?php require '../../global.php';

// Is request coming from this website?
$request->isCorrectReferer() or die;

$request->issetAndNotEmpty('GET', 'prefix') or die;

$data = $thread->getThreads($request->get['prefix']);
if (!empty($data)) {
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
