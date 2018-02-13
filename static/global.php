<?php

// Used for verifying that files are accessed only via this file.
define('APP', 1);

define('FILE_PATH', dirname(__DIR__) . '\\uploads\\');
define('LANG_PATH', dirname(__DIR__) . '\\assets\\lang\\');

// If application is not installed
if (file_exists('../install/index.html') && !file_exists('config/Configuration.php')) {
	die(json_encode(['error' => 'install']));
}

session_start();

require_once 'config/Configuration.php';
require_once 'config/Configuration.php';
require_once 'config/Rules.php';

require_once 'Logger.php';
require_once 'Database.php';

require_once 'controllers/Controller.php';
require_once 'controllers/BoardController.php';
require_once 'controllers/FileController.php';
require_once 'controllers/ThreadController.php';
require_once 'controllers/LanguageController.php';
require_once 'controllers/AuthenticationController.php';

require_once 'IRequest.php';
require_once 'JsonResponse.php';

require_once 'requests/GetAppConfigRequest.php';
require_once 'requests/GetLanguageRequest.php';
require_once 'requests/GetRulesRequest.php';

require_once 'requests/auth/CreateUserRequest.php';
require_once 'requests/auth/LoginUserRequest.php';
require_once 'requests/auth/LogoutUserRequest.php';

require_once 'requests/boards/CreateBoardRequest.php';
require_once 'requests/boards/DeleteBoardRequest.php';
require_once 'requests/boards/GetBoardsRequest.php';
require_once 'requests/boards/UpdateBoardRequest.php';

require_once 'requests/threads/AddReplyRequest.php';
require_once 'requests/threads/CreateThreadRequest.php';
require_once 'requests/threads/DeleteThreadRequest.php';
require_once 'requests/threads/GetThreadRepliesRequest.php';
require_once 'requests/threads/GetThreadStartPostRequest.php';
require_once 'requests/threads/GetThreadsRequest.php';
require_once 'requests/threads/LockThreadRequest.php';

require_once('RequestHandler.php');

RequestHandler::initialize();

try {
	if (!empty($_POST)) {
		RequestHandler::handle_request($_POST['request'], $_POST);
		return;
	}
	else if (!empty($_GET)) {
		RequestHandler::handle_request($_GET['request'], $_GET);
		return;
	}
}
catch (Exception $e) {
	Logger::write_data($e->getMessage());
}
