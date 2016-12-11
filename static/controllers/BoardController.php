<?php defined('APP') or die;

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
		$stmt = BoardCore::getDatabase()->prepare('SELECT * FROM boards');
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	/**
	 * Deletes board with given id.
	 *
	 * @param int $id
	 * @return boolean
	 */
	public function deleteBoard($id) {
		$stmt = BoardCore::getDatabase()->prepare('DELETE FROM boards WHERE id = :id LIMIT 1');
		return $stmt->execute([':id' => $id]);
	}
	
	/**
	 * Creates a new thread with given values.
	 *
	 * @param string $name
	 * @param string $desc
	 * @param string $prefix
	 * @param string $tag
	 * @return boolean
	 */
	public function createBoard($name, $desc, $prefix, $tag) {
		
		if ($this->prefixExists($prefix)) {
			return false;
		}
			
		$stmt = BoardCore::getDatabase()
			->prepare('INSERT INTO boards (name, description, prefix, tag) VALUES (:name, :desc, :prefix, :tag)');
		
		return $stmt->execute([':name' => $name, ':desc' => $desc, ':prefix' => $prefix, ':tag' => $tag]);
	}
	
	/**
	 * Checks if board exists with given prefix.
	 *
	 * @param string $prefix
	 * @return boolean
	 */
	public function prefixExists($prefix) {
		$stmt = BoardCore::getDatabase()
			->prepare('SELECT prefix FROM boards WHERE prefix = :prefix LIMIT 1');
		
		$stmt->execute([':prefix' => $prefix]);
		return $stmt->rowCount() > 0;
	}
}
