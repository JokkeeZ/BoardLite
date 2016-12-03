<?php

// Used for verifying that files are accessed only via this file.
define('APP', '1');

require '../config/Configuration.php';
require '../Database.php';
require '../controllers/BoardController.php';
require '../controllers/FileController.php';
require '../controllers/ThreadController.php';
require '../controllers/RequestController.php';
require '../config/Rules.php';
require '../BoardCore.php';

// From now on, every class can be used via BoardCore class, see below.
BoardCore::initialize();

/*
BoardCore::getDatabase();
BoardCore::getBoardController();
BoardCore::getFileController();
BoardCore::getThreadController();
BoardCore::getRequestController();
*/
