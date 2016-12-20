<?php

// Used for verifying that files are accessed only via this file.
define('APP', '1');

// Start cookie session, used for saving token.
session_start();

require 'config/Configuration.php';
require 'Database.php';
require 'controllers/Controller.php';
require 'controllers/BoardController.php';
require 'controllers/FileController.php';
require 'controllers/ThreadController.php';
require 'controllers/RequestController.php';
require 'controllers/LanguageController.php';
require 'controllers/AuthenticationController.php';
require 'config/Rules.php';

// Set some global variables used in /ajax/ folder on requests.
$request = new RequestController();
$board = new BoardController();
$thread = new ThreadController();
$file = new FileController();
$lang = new LanguageController();
$auth = new AuthenticationController();
