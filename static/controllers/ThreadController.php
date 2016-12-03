<?php defined('APP') or die();

/**
 * Provides access for inserting, updating and selecting thread content from database.
 * 
 * @author JokkeeZ
 * @version 1.0
 * @copyright Copyright © 2016 JokkeeZ
 */
class ThreadController {

	/**
	 * Creates a new thread into database with given values.
	 * 
	 * @param mixed $title
	 * @param string $message
	 * @param string $prefix
	 * @param string $img
	 * 
	 * @return number $msgId or 0 if failed.
	 */
	public function createThread($title, $message, $prefix, $img) {
		$msgId = BoardCore::getBoardController()->getMessageCount() + 1;

		// Replace useless crap from image path, no need for saving that in db.
		$img = str_replace('../../', '', $img);

		$stmt = BoardCore::getDatabase()->prepare('INSERT INTO threads (title, content, prefix, msg_id, img_url, ip) VALUES
			(:title, :msg, :prefix, :msg_id, :img_url, :ip)');
		
		$stmt->bindParam(':title', $title);
		$stmt->bindParam(':msg', $message);
		$stmt->bindParam(':prefix', $prefix);
		$stmt->bindParam(':msg_id', $msgId);
		$stmt->bindParam(':img_url', $img);
		$stmt->bindParam(':ip', $_SERVER['REMOTE_ADDR']);

		$stmt->execute();

		if ($stmt) {
			BoardCore::getBoardController()->updateMessageCount();
			return $msgId;
		}
		return 0;
	}

	/**
	 * Creates a new reply into database with given values.
	 * 
	 * @param string $content
	 * @param number $threadId
	 * @param string $img
	 * 
	 * @return number $msgId or 0 if failed.
	 */
	public function addReply($content, $threadId, $img) {
		$msgId = BoardCore::getBoardController()->getMessageCount() + 1;

		// Replace useless crap from image path, no need for saving that in db.
		$img = str_replace('../../', '', $img);

		if (!is_numeric($threadId))
			return 0;

		$stmt = BoardCore::getDatabase()->prepare('INSERT INTO replys (thread_id, content, msg_id, ip, img_url) 
			VALUES (:id, :content, :msg_id, :ip, :img_url)');

		$stmt->bindParam(':id', $threadId);
		$stmt->bindParam(':content', $content);
		$stmt->bindParam(':msg_id', $msgId);
		$stmt->bindParam(':ip', $_SERVER['REMOTE_ADDR']);
		$stmt->bindParam(':img_url', $img);

		$stmt->execute();

		if ($stmt) {
			BoardCore::getBoardController()->updateMessageCount();
			return $msgId;
		}
		return 0;
	}

	/**
	 * Get's thread start post by given thread id.
	 * 
	 * @param number $threadId
	 * 
	 * @return boolean or data feched into array.
	 */
	public function getStartPost($threadId) {
		if (!is_numeric($threadId)) {
			return false;
		}

		$stmt = BoardCore::getDatabase()
			->prepare('SELECT id, title, content, posted, prefix, msg_id, img_url FROM threads WHERE msg_id = :id LIMIT 1');
		
		$stmt->execute([':id' => $threadId]);

		if ($stmt) {
			return $stmt->fetch(PDO::FETCH_ASSOC);
		}

		return false;
	}

	/**
	 * Get's thread replies by given thread id.
	 * 
	 * @param number $threadId
	 * 
	 * @return boolean or data fetched into array.
	 */
	public function getReplys($threadId) {
		if (!is_numeric($threadId)) {
			return false;
		}

		$stmt = BoardCore::getDatabase()
			->prepare('SELECT id, thread_id, content, posted, msg_id, img_url FROM replys 
				WHERE thread_id = :id ORDER BY id');

		$stmt->execute([':id' => $threadId]);

		if ($stmt->rowCount() > 0)
			return $stmt->fetchAll(PDO::FETCH_ASSOC);

		return false;
	}

	/**
	 * Get's first 25 threads as fetched array.
	 * 
	 * @param string $prefix
	 * 
	 * @return array or boolean FALSE if didn't success.
	 */
	public function getThreads($prefix) {
		$stmt = BoardCore::getDatabase()
			->prepare('SELECT id, title, content, posted, prefix, msg_id, img_url FROM threads 
				WHERE prefix = :prefix ORDER BY -id LIMIT 25');

		$stmt->execute([':prefix' => $prefix]);

		if ($stmt->rowCount() > 0)
			return $stmt->fetchAll(PDO::FETCH_ASSOC);

		return false;
	}
}