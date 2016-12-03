<?php require '../global.php';

$request = BoardCore::getRequestController();

$request->isCorrectReferer() or die();

echo json_encode(['app_name' => $_CONFIG['app_name']]);
