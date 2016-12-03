<?php require '../global.php';

$request = BoardCore::getRequestController();
$file = BoardCore::getFileController();
$thread = BoardCore::getThreadController();

// Is request coming from this website?
$request->isCorrectReferer() or die();

// Is request XMLHttpRequest?
$request->isXMLHttpRequest() or die();

// Let's filter $_POST values, *sigh* script kids.
$request->loadPostRequest();

// Title content can be empty, only newgays using titles :--D
$request->requestIsSet('POST', 'title') or die();

// Cannot be empty, we need dat message and prefix.
$request->issetAndNotEmpty('POST', 'message') or die();
$request->issetAndNotEmpty('POST', 'prefix') or die();

$uploaded = $file->upload();
$target_file = '';
if ($uploaded) {
	$target_file = '../../uploads/' . basename($_FILES['file']['name']);
}

$data = $thread->createThread(
	((empty($request->post['title'])) ? '' : $request->post['title']),
	$request->post['message'],
	$request->post['prefix'],
	$target_file
);

if (!isset($_FILES['file'])) {
	echo json_encode([
		'data' => $data,
		'success' => true
	]);
}
else {
	echo json_encode([
		'data' => $data,
		'success' => $uploaded
	]);
}
