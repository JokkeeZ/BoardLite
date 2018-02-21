<?php

/**
 * Class used for handling frontend -> backend requests.
 * 
 * @author JokkeeZ
 * @version 1.0
 *
 * @copyright Copyright © 2018 JokkeeZ
 * @license Licensed under MIT License.
 */
class RequestHandler
{
	private static $requests;

	/**
	 * Initializes available requests.
	 */
	public static function initialize()
	{
		self::$requests = [
			// App
			'get_config' => new GetAppConfigRequest(),
			'get_lang' => new GetLanguageRequest(),
			'get_rules' => new GetRulesRequest(),

			// Auth
			'create_user' => new CreateUserRequest(),
			'login_user' => new LoginUserRequest(),
			'logout_user' => new LogoutUserRequest(),

			// Boards
			'create_board' => new CreateBoardRequest(),
			'delete_board' => new DeleteBoardRequest(),
			'get_boards' => new GetBoardsRequest(),
			'update_board' => new UpdateBoardRequest(),

			// Threads
			'add_reply' => new AddReplyRequest(),
			'create_thread' => new CreateThreadRequest(),
			'delete_thread' => new DeleteThreadRequest(),
			'get_thread_replies' => new GetThreadRepliesRequest(),
			'get_threads' => new GetThreadsRequest(),
			'get_thread_start_post' => new GetThreadStartPostRequest(),
			'lock_thread' => new LockThreadRequest()
		];
	}

	/**
	 * Handles incoming request with given input $data.
	 *
	 * @param string $request Incoming request from frontedn. (Some of the requests defined above.)
	 * @param array $data If GET request, $_GET array. If POST, $_POST array.
	 */
	public static function handle_request($request, $data)
	{
		if (array_key_exists($request, self::$requests)) {
			exit(self::$requests[$request]->handle_request($data));
		}
	}
}