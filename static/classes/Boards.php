<?php

/**
 * Class used mainly for getting and updating global message ID and boards.
 *
 * @author JokkeeZ
 * @version 1.2
 *
 * @copyright Copyright © 2016 - 2018 JokkeeZ
 * @license Licensed under MIT License.
 */
class Boards extends Controller
{
	/**
	 * Updates message count by adding number 1 to it.
	 * Used after reply/thread adding query is done.
	 */
	public function updateMessageCount()
	{
		$this->getDatabase()->query('UPDATE data SET message_count = message_count + 1');
	}

	/**
	 * Get's global message ID from `data` table.
	 * Used for creating new threads and replies by adding 1 to this integer.
	 *
	 * @return int Returns message count.
	 */
	public function getMessageCount() : int
	{
		return $this->getDatabase()
			->query('SELECT message_count FROM data')
			->fetch(PDO::FETCH_ASSOC)['message_count'];
	}

	
	/**
	 * Queries data from boards table and fetch's them into an array.
	 *
	 * @return array Returns boards.
	 */
	public function getBoards() : array
	{
		return $this->getDatabase()
			->query('SELECT * FROM boards')
			->fetchAll(PDO::FETCH_ASSOC);
	}
	
	/**
	 * Deletes board with specific id.
	 *
	 * @param int $id Board id.
	 * @return bool If board was deleted, returns true; otherwise false.
	 */
	public function deleteBoard($id) : bool
	{
		$stmt = $this->getDatabase()->prepare('DELETE FROM boards WHERE id = ? LIMIT 1');
		return $stmt->execute([$id]);
	}
	
	/**
	 * Creates a new thread with given values.
	 *
	 * @param string $name
	 * @param string $desc
	 * @param string $prefix
	 * @param string $tag
	 *
	 * @return bool If board was created returns true; otherwise false.
	 */
	public function createBoard($name, $desc, $prefix, $tag) : bool
	{
		if ($this->prefixExists($prefix))
			return false;

		$stmt = $this->getDatabase()->prepare(
			'INSERT INTO boards (name, description, prefix, tag) VALUES (:name, :desc, :prefix, :tag)'
		);

		return $stmt->execute([
			':name' => $name,
			':desc' => $desc,
			':prefix' => $prefix,
			':tag' => $tag
		]);
	}
	
	/**
	 * Checks if board exists with given prefix.
	 *
	 * @param $prefix
	 * @return bool If prefix exists, returns true; otherwise false.
	 */
	public function prefixExists($prefix) : bool
	{
		$stmt = $this->getDatabase()
			->prepare('SELECT prefix FROM boards WHERE prefix = :prefix LIMIT 1');

		$stmt->execute([':prefix' => $prefix]);
		return $stmt->rowCount() > 0;
	}

	/**
	 * Gets prefix with board id.
	 *
	 * @param int $boardId
	 * @return string Returns board prefix.
	 */
	public function getPrefixWithId($boardId) : string
	{
		$stmt = $this->getDatabase()
			->prepare('SELECT prefix FROM boards WHERE id = :id LIMIT 1');

		$stmt->execute([':id' => $boardId]);
		if ($stmt->rowCount() <= 0)
			return null;

		$row = $stmt->fetch();
		return $row['prefix'];
	}

	/**
	 * Updates specific board with data.
	 *
	 * @param $id
	 * @param $name
	 * @param $desc
	 * @param $prefix
	 * @param $tag
	 *
	 * @return bool
	 */
	public function updateBoard($id, $name, $desc, $prefix, $tag) : bool
	{
		$stmt = $this->getDatabase()->prepare(
			'UPDATE boards SET name = :name, description = :desc, prefix = :prefix, tag = :tag WHERE id = :id'
		);

		return $stmt->execute([
			':id' => $id,
			':name' => $name,
			':desc' => $desc,
			':prefix' => $prefix,
			':tag' => $tag
		]);
	}
}
