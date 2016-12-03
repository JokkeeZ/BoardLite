<?php require '../global.php';

$request = BoardCore::getRequestController();

// Is the request coming from this website?
$request->isCorrectReferer() or die();

// These are public data also, so no need for hiding them?
$board = BoardCore::getBoardController();
echo json_encode($board->getBoards());