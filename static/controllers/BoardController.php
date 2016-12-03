<?php defined('APP') or die();

/**
 * Class used mainly for getting and updating global message ID and boards.
 * 
 * @author JokkeeZ
 * @version 1.0
 * @copyright Copyright © 2016 JokkeeZ
 */
class BoardController {
	
	/**
	 * Updates message count by adding number 1 to it.
	 * Used after reply/thread adding query is done.
	 * 
	 * @return void
	 */
	public function updateMessageCount() {
		BoardCore::getDatabase()
			->prepare('UPDATE data SET message_count = message_count + 1')
			->execute();
	}

	/**
	 * Get's global message ID from `data` table.
	 * Used for creating new threads and replies by adding 1 to this integer.
	 *
	 * @return int
	 */
	public function getMessageCount() {
		return BoardCore::getDatabase()
			->query('SELECT message_count FROM data')
			->fetch(PDO::FETCH_ASSOC)['message_count'];		
	}

	
	/**
	 * Queries data from boards table and fetch's them into an array.
	 * 
	 * @return $mixed array
	 */
	public function getBoards() {
		return BoardCore::getDatabase()
			->query('SELECT * FROM boards')
			->fetchAll(PDO::FETCH_ASSOC);
	}
}