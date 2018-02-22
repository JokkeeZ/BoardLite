<?php

define('FILE_PATH', dirname(__DIR__) . '/uploads/');
define('LANG_PATH', dirname(__DIR__) . '/assets/lang/');

$error = json_encode(['error' => 'install']);

if (!file_exists('config/Configuration.php')) {
	exit($error);
}

require_once 'config/Configuration.php';

if (file_exists('../install/index.html') && (empty($_CONFIG['app_installed']) || !$_CONFIG['app_installed'])) {
	exit($error);
}

session_start();

require_once 'Rules.php';

require_once 'Logger.php';
require_once 'Database.php';

require_once 'classes/Controller.php';
require_once 'classes/Boards.php';
require_once 'classes/File.php';
require_once 'classes/Threads.php';
require_once 'classes/Language.php';
require_once 'classes/Authentication.php';

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

require_once 'RequestHandler.php';

RequestHandler::initialize();

try {
	if (!empty($_POST)) {
		RequestHandler::handle_request($_POST['request'], $_POST);
	}
	else if (!empty($_GET)) {
		RequestHandler::handle_request($_GET['request'], $_GET);
	}
}
catch (Exception $e) {
	Logger::write_data($e->getTraceAsString());
}
