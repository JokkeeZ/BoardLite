<?php defined('APP') or die;

/**
 * Provides access for inserting, updating and selecting thread content from database.
 *
 * @author JokkeeZ
 * @version 1.1
 * @copyright Copyright © 2016 JokkeeZ
 */
class ThreadController extends Controller {

	/**
	 * Creates a new thread into database with given values.
	 *
	 * @param mixed $title
	 * @param string $message
	 * @param string $prefix
	 * @param string $img
	 * @return int
	 */
	public function createThread($title, $message, $prefix, $img):int {
	    global $board;
		$msgId = $board->getMessageCount() + 1;

		// Replace useless crap from image path, no need for saving that in db.
		$img = str_replace('../../', '', $img);

		$stmt = $this->getDatabase()->prepare('INSERT INTO threads (title, content, prefix, msg_id, img_url, ip) VALUES
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
			$board->updateMessageCount();
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
	 * @return int
	 */
	public function addReply($content, $threadId, $img):int {
	    global $board;
		$msgId = $board->getMessageCount() + 1;

		// Replace useless crap from image path, no need for saving that in db.
		$img = str_replace('../../', '', $img);

		if (!is_numeric($threadId))
			return 0;

		$stmt = $this->getDatabase()->prepare('INSERT INTO replys (thread_id, content, msg_id, ip, img_url) 
			VALUES (:id, :content, :msg_id, :ip, :img_url)');

		$stmt->execute([
		    ':id' => $threadId,
            ':content' => $content,
            ':msg_id' => $msgId,
            ':ip' => $_SERVER['REMOTE_ADDR'],
            ':img_url' => $img
        ]);

		if ($stmt) {
            $board->updateMessageCount();
			return $msgId;
		}
		return 0;
	}

	/**
	 * Get's thread start post by given thread id.
	 *
	 * @param number $threadId
	 * @return boolean or data fetched into array.
	 */
	public function getStartPost($threadId) {
		if (!is_numeric($threadId))
			return false;

		$stmt = $this->getDatabase()
			->prepare('SELECT id, title, content, posted, prefix, msg_id, img_url, ip FROM threads WHERE msg_id = :id LIMIT 1');
		
		$stmt->execute([':id' => $threadId]);

		if ($stmt->rowCount() > 0)
			return $stmt->fetch(PDO::FETCH_ASSOC);

		return false;
	}

	/**
	 * Get's thread replies by given thread id.
	 * 
	 * @param number $threadId
	 * @return array
	 */
	public function getReplys($threadId):array {
		if (!is_numeric($threadId))
			return [];

		$stmt = $this->getDatabase()->prepare('SELECT id, thread_id, content, posted, msg_id, img_url, ip FROM replys 
				WHERE thread_id = :id ORDER BY id');

		$stmt->execute([':id' => $threadId]);

		if ($stmt->rowCount() > 0)
			return $stmt->fetchAll(PDO::FETCH_ASSOC);

		return [];
	}

	/**
	 * Get's first 25 threads as fetched array.
	 * TODO: Pagination? Maybeh?
     *
	 * @param string $prefix
	 * @return array
	 */
	public function getThreads($prefix):array {
		$stmt = $this->getDatabase()->prepare('SELECT id, title, content, posted, prefix, msg_id, img_url, ip FROM threads 
				WHERE prefix = :prefix ORDER BY -id LIMIT 25');

		$stmt->execute([':prefix' => $prefix]);

		if ($stmt->rowCount() > 0)
			return $stmt->fetchAll(PDO::FETCH_ASSOC);

		return [];
	}

    /**
     * Deletes an thread with specific id.
     *
     * @param $id
     * @return bool
     */
	public function deleteThread($id):bool {
        $stmt = $this->getDatabase()->prepare('DELETE FROM threads WHERE msg_id = :id LIMIT 1;');
        return ($stmt->execute([':id' => $id]) && $this->deleteThreadReplies($id));
    }

    /**
     * Deletes all thread replies with specific thread id.
     *
     * @param $id
     * @return bool
     */
    private function deleteThreadReplies($id):bool {
        return $this->getDatabase()
            ->prepare('DELETE FROM replys WHERE thread_id = :id;')
            ->execute([':id' => $id]);
    }
}
