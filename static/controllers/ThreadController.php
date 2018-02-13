<?php defined('APP') or die;

/**
 * Provides access for inserting, updating and selecting thread content from database.
 *
 * @author JokkeeZ
 * @version 1.2
 * @copyright Copyright © 2016 - 2018 JokkeeZ
 */
class ThreadController extends Controller
{
	/**
	 * Creates a new thread into database with given values.
	 */
	public function create_thread($title, $message, $prefix, $img) : int
	{
		$board = new BoardController();
		$msgId = $board->get_message_count() + 1;

		// We dont need to save useless slashes into db.
		$img = str_replace('../../', '', $img);

		$stmt = $this->get_database()->prepare('INSERT INTO threads (title, content, prefix, msg_id, img_url, ip) VALUES
			(:title, :msg, :prefix, :msg_id, :img_url, :ip)');

		$success = $stmt->execute([
			':title' => $title,
			':msg' => $message,
			':prefix' => $prefix,
			':msg_id' => $msgId,
			':img_url' => $img,
			':ip' => $_SERVER['REMOTE_ADDR']
		]);

		if ($success) {
			$board->update_message_count();
			return $msgId;
		}

		return 0;
	}

	/**
	 * Creates a new reply into database with given values.
	 */
	public function add_reply($content, $threadId, $img) : int
	{
		$board = new BoardController();
		$msgId = $board->get_message_count() + 1;

		// Replace useless crap from image path, no need for saving that in db.
		$img = str_replace('../../', '', $img);

		if (!is_numeric($threadId))
			return 0;

		$stmt = $this->get_database()->prepare('INSERT INTO replies (thread_id, content, msg_id, ip, img_url) 
			VALUES (:id, :content, :msg_id, :ip, :img_url)');

		$stmt->execute([
			':id' => $threadId,
			':content' => $content,
			':msg_id' => $msgId,
			':ip' => $_SERVER['REMOTE_ADDR'],
			':img_url' => $img
		]);

		if ($stmt) {
			$board->update_message_count();
			return $msgId;
		}

		return 0;
	}

	/**
	 * Gets thread start post by given thread id.
	 */
	public function get_start_post($threadId)
	{
		if (!is_numeric($threadId))
			return false;

		$stmt = $this->get_database()->prepare('SELECT * FROM threads WHERE msg_id = :id LIMIT 1');
		$stmt->execute([':id' => $threadId]);

		if ($stmt->rowCount() > 0)
			return $stmt->fetch(PDO::FETCH_ASSOC);

		return false;
	}

	/**
	 * Gets thread replies by given thread id.
	 */
	public function get_replies($threadId)
	{
		if (!is_numeric($threadId))
			return false;

		$stmt = $this->get_database()->prepare('SELECT * FROM replies WHERE thread_id = :id ORDER BY id');
		$stmt->execute([':id' => $threadId]);

		if ($stmt->rowCount() > 0)
			return $stmt->fetchAll(PDO::FETCH_ASSOC);

		return false;
	}

	/**
	 * Updates thread prefixes in to new prefix.
	 */
	public function update_thread_prefixes($oldPrefix, $newPrefix)
	{
		$stmt = $this->get_database()->prepare('UPDATE threads SET prefix = :new WHERE prefix = :old');
		$stmt->execute([':new' => $newPrefix, ':old' => $oldPrefix]);
	}

	/**
	 * Gets threads from board with prefix.
	 */
	public function get_threads($prefix) : array
	{
		$stmt = $this->get_database()->prepare('SELECT * FROM threads WHERE prefix = :prefix ORDER BY -id;');
		$stmt->execute([':prefix' => $prefix]);

		if ($stmt->rowCount() > 0)
			return $stmt->fetchAll(PDO::FETCH_ASSOC);

		return [];
	}

	/**
	 * Sets given thread new lock state.
	 */
	public function set_thread_lock_state($id, $state) : bool
	{
		$stmt = $this->get_database()->prepare('UPDATE threads SET locked = :locked WHERE id = :id');
		$stmt->execute([':locked' => $state, ':id' => $id]);

		return $stmt->rowCount() > 0;
	}

	/**
	 * Deletes an thread with specific id.
	 */
	public function delete_thread($id) : bool
	{
		$stmt = $this->get_database()->prepare('DELETE FROM threads WHERE msg_id = :id LIMIT 1;');
		return ($stmt->execute([':id' => $id]) && $this->delete_thread_replies($id));
	}

	/**
	 * Deletes all threads and replies on board with prefix.
	 */
	public function delete_threads_with_prefix($prefix)
	{
		$stmt = $this->get_database()->prepare('SELECT msg_id FROM threads WHERE prefix = :prefix');
		$stmt->execute([':prefix' => $prefix]);

		if ($stmt->rowCount() > 0) {
			$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach ($rows as $rowK => $rowV) {
				foreach ($rowV as $k => $v) {
					$this->delete_thread($v);
				}
			}
		}
	}

	/**
	 * Deletes all thread replies with specific thread id.
	 */
	private function delete_thread_replies($id) : bool
	{
		return $this->get_database()
			->prepare('DELETE FROM replies WHERE thread_id = :id')
			->execute([':id' => $id]);
	}
}
