<?php require '../../global.php';

// Is request coming from this website?
$request->isCorrectReferer() or die;

// Is request XMLHttpRequest?
$request->isXMLHttpRequest() or die;

// Let's filter $_POST values, *sigh* script kids.
$request->loadPostRequest();

$request->verifyToken() or die;

// Does $_POST contain values we need?
$request->issetAndNotEmpty('POST', 'message') or die;
$request->issetAndNotEmpty('POST', 'thread_id') or die;

// Did file get uploaded if there was any?
$uploaded = $file->upload();
$target_file = '';

// If file did get uploaded, set $target_file so we can pass that file name to db.
if ($uploaded) {
	$target_file = '../../uploads/' . basename($_FILES['file']['name']);
}

// Add new reply to db.
$data = $thread->addReply(
	$request->post['message'],
	$request->post['thread_id'],
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
