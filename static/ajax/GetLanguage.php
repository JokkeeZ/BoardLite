<?php require '../global.php';

$request->isCorrectReferer() or die;

echo $lang->getContents();
