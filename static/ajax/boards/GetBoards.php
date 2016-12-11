<?php require '../../global.php';

$request->isCorrectReferer() or die;

echo json_encode($board->getBoards());
