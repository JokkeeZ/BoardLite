<?php

// Used for verifying that files are accessed only via this file.
define('APP', '1');

define('FILE_PATH', dirname(__DIR__) . '\\uploads\\');
define('LANG_PATH', dirname(__DIR__) . '\\assets\\lang\\');

session_start();

require 'config/Configuration.php';
require 'config/Rules.php';

require 'Logger.php';

require 'Database.php';

require 'controllers/Controller.php';
require 'controllers/BoardController.php';
require 'controllers/FileController.php';
require 'controllers/ThreadController.php';
require 'controllers/LanguageController.php';
require 'controllers/AuthenticationController.php';

require 'IRequest.php';
require 'JsonResponse.php';

require 'requests/GetAppConfigRequest.php';
require 'requests/GetLanguageRequest.php';
require 'requests/GetRulesRequest.php';

require 'requests/auth/CreateUserRequest.php';
require 'requests/auth/LoginUserRequest.php';
require 'requests/auth/LogoutUserRequest.php';

require 'requests/boards/CreateBoardRequest.php';
require 'requests/boards/DeleteBoardRequest.php';
require 'requests/boards/GetBoardsRequest.php';
require 'requests/boards/UpdateBoardRequest.php';

require 'requests/threads/AddReplyRequest.php';
require 'requests/threads/CreateThreadRequest.php';
require 'requests/threads/DeleteThreadRequest.php';
require 'requests/threads/GetThreadRepliesRequest.php';
require 'requests/threads/GetThreadStartPostRequest.php';
require 'requests/threads/GetThreadsRequest.php';
require 'requests/threads/LockThreadRequest.php';

require 'RequestHandler.php';

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
