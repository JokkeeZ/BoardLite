<?php

/**
 * Provides access for inserting, updating and selecting thread content from database.
 *
 * @author JokkeeZ
 * @version 1.2
 *
 * @copyright Copyright © 2016 - 2018 JokkeeZ
 * @license Licensed under MIT License.
 */
class Threads extends Controller
{
	/**
	 * Creates a new thread into database with given values.
	 *
	 * @param string $title Thread title (if set)
	 * @param string $message Thread content
	 * @param string $prefix Board prefix
	 * @param string $img Image url (if set)
	 *
	 * @return int If success, returns created thread id; otherwise 0.
	 */
	public function createThread($title, $message, $prefix, $img) : int
	{
		$boards = new Boards();
		$msgId = $boards->getMessageCount() + 1;

		// We dont need to save useless slashes into db.
		$img = str_replace('../../', '', $img);

		$stmt = $this->getDatabase()->prepare(
			'INSERT INTO threads (title, content, prefix, msg_id, img_url, ip) 
			VALUES (:title, :msg, :prefix, :msg_id, :img_url, :ip)'
		);

		$success = $stmt->execute([
			':title' => $title,
			':msg' => $message,
			':prefix' => $prefix,
			':msg_id' => $msgId,
			':img_url' => $img,
			':ip' => $_SERVER['REMOTE_ADDR']
		]);

		if ($success) {
			$boards->updateMessageCount();
			return $msgId;
		}

		return 0;
	}

	/**
	 * Creates a new reply into database with given values.
	 *
	 * @param string $content Reply content.
	 * @param int $threadId Thread id.
	 * @param string $img Image url (if set)
	 *
	 * @return int If success, returns created reply id; otherwise 0.
	 */
	public function addReply($content, $threadId, $img) : int
	{
		$boards = new Boards();
		$msgId = $boards->getMessageCount() + 1;

		// Replace useless crap from image path, no need for saving that in db.
		$img = str_replace('../../', '', $img);

		if (!is_numeric($threadId))
			return 0;

		$stmt = $this->getDatabase()->prepare(
			'INSERT INTO replies (thread_id, content, msg_id, ip, img_url) 
			VALUES (:id, :content, :msg_id, :ip, :img_url)'
		);

		$stmt->execute([
			':id' => $threadId,
			':content' => $content,
			':msg_id' => $msgId,
			':ip' => $_SERVER['REMOTE_ADDR'],
			':img_url' => $img
		]);

		if ($stmt) {
			$boards->updateMessageCount();
			return $msgId;
		}

		return 0;
	}

	/**
	 * Gets thread start post by given thread id.
	 *
	 * @param int $threadId Thread id.
	 * @return bool|object If success, returns object containing start post; otherwise false.
	 */
	public function getStartPost($threadId)
	{
		if (!is_numeric($threadId))
			return false;

		$stmt = $this->getDatabase()->prepare('SELECT * FROM threads WHERE msg_id = :id LIMIT 1');
		$stmt->execute([':id' => $threadId]);

		if ($stmt->rowCount() > 0)
			return $stmt->fetch(PDO::FETCH_ASSOC);

		return false;
	}

	/**
	 * Gets thread replies by given thread id.
	 *
	 * @param int $threadId Thread id, where this reply will be posted.
	 * @return bool|array If success, returns array containing replies; otherwise false.
	 */
	public function getReplies($threadId)
	{
		if (!is_numeric($threadId))
			return false;

		$stmt = $this->getDatabase()->prepare('SELECT * FROM replies WHERE thread_id = :id ORDER BY id');
		$stmt->execute([':id' => $threadId]);

		if ($stmt->rowCount() > 0)
			return $stmt->fetchAll(PDO::FETCH_ASSOC);

		return false;
	}

	/**
	 * Updates thread prefixes in to new prefix.
	 *
	 * @param string $oldPrefix Old prefix.
	 * @param string $newPrefix New prefix.
	 */
	public function updateThreadPrefixes($oldPrefix, $newPrefix)
	{
		$stmt = $this->getDatabase()->prepare('UPDATE threads SET prefix = :new WHERE prefix = :old');
		$stmt->execute([':new' => $newPrefix, ':old' => $oldPrefix]);
	}

	/**
	 * Gets threads from board with prefix.
	 *
	 * @param string $prefix Board prefix where threads will be queried.
	 * @return array If success, returns threads; otherwise empty array.
	 */
	public function getThreads($prefix) : array
	{
		$stmt = $this->getDatabase()->prepare('SELECT * FROM threads WHERE prefix = :prefix ORDER BY -id;');
		$stmt->execute([':prefix' => $prefix]);

		if ($stmt->rowCount() > 0)
			return $stmt->fetchAll(PDO::FETCH_ASSOC);

		return array();
	}

	/**
	 * Sets given thread new lock state.
	 *
	 * @param int $id Thread id.
	 * @param string $state New thread lock state.
	 *
	 * @return bool If success, returns true; otherwise false.
	 */
	public function setThreadLockState($id, $state) : bool
	{
		$stmt = $this->getDatabase()->prepare('UPDATE threads SET locked = :locked WHERE id = :id');
		$stmt->execute([':locked' => $state, ':id' => $id]);

		return $stmt->rowCount() > 0;
	}

	/**
	 * Deletes an thread with specific id.
	 *
	 * @param int $id Thread id.
	 * @return bool If deleted successfully, returns true; otherwise false.
	 */
	public function deleteThread($id) : bool
	{
		$stmt = $this->getDatabase()->prepare('DELETE FROM threads WHERE msg_id = :id LIMIT 1;');
		return ($stmt->execute([':id' => $id]) && $this->deleteThreadReplies($id));
	}

	/**
	 * Deletes all threads and replies on board with prefix.
	 *
	 * @param string $prefix
	 */
	public function deleteThreadsWithPrefix($prefix)
	{
		$stmt = $this->getDatabase()->prepare('SELECT msg_id FROM threads WHERE prefix = :prefix');
		$stmt->execute([':prefix' => $prefix]);

		if ($stmt->rowCount() > 0) {
			$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach ($rows as $rowK => $rowV) {
				foreach ($rowV as $k => $v) {
					$this->deleteThread($v);
				}
			}
		}
	}

	/**
	 * Deletes all thread replies with specific thread id.
	 *
	 * @param int $id Thread id.
	 * @return bool If deleted successfully, returns true; otherwise false.
	 */
	private function deleteThreadReplies($id) : bool
	{
		return $this->getDatabase()
			->prepare('DELETE FROM replies WHERE thread_id = :id')
			->execute([':id' => $id]);
	}
}
