<?php

// Used for verifying that files are accessed only via this file.
define('APP', '1');

// Start cookie session, used for saving token.
session_start();

require 'config/Configuration.php';
require 'Database.php';
require 'controllers/BoardController.php';
require 'controllers/FileController.php';
require 'controllers/ThreadController.php';
require 'controllers/RequestController.php';
require 'controllers/LanguageController.php';
require 'controllers/AuthenticationController.php';
require 'config/Rules.php';
require 'BoardCore.php';

// From now on, every class can be used via BoardCore class, see below.
BoardCore::initialize();

// This is needed on every request.
$request = BoardCore::getRequestController();

$board = BoardCore::getBoardController();

$thread = BoardCore::getThreadController();

$file = BoardCore::getFileController();

$lang = BoardCore::getLanguageController();

$auth = BoardCore::getAuthenticationController();
