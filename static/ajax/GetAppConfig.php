<?php require '../global.php';

$request->isCorrectReferer() or die;
echo json_encode(['app_name' => $_CONFIG['app_name']]);
