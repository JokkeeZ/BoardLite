<?php defined('APP') or die;

/**
 * Provides functionality for login, register and logout actions.
 *
 * @author JokkeeZ
 * @version 1.0
 * @copyright Copyright © 2016 JokkeeZ
 */
class AuthenticationController {
	
	/**
	 * Validates user with given password and username, if password doesn't match hash password,
	 * or name doesn't exists, returns false.
	 *
	 * @param string $name
	 * @param string $pass
	 * @return boolean
	 */
	public function login($name, $pass) {
		$stmt = BoardCore::getDatabase()->prepare('SELECT id, name, pass, rank, ip FROM users WHERE name = :name LIMIT 1');
		$stmt->execute([':name' => $name]);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		
		// Name found.
		if ($stmt->rowCount() > 0) {
			
			// Password matches.
			if (password_verify($pass, $row['pass'])) {
				$_SESSION['user'] = $row;
				
				// We dont need to store password on session.
				unset($_SESSION['user']['pass']);
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Registers a new user with given values, if name already exists, returns false.
	 *
	 * @param string $name
	 * @param string $pass
	 *
	 * @return boolean
	 */
	public function register($name, $pass) {
		if ($this->nameExists($name))
			return false;
		
		$stmt = BoardCore::getDatabase()->prepare('INSERT INTO users (name, pass, ip, rank) VALUES (:name, :pass, :ip, :rank)');
		
		$password = $this->createPasswordHash($pass);
		
		$success = $stmt->execute([
			':name' => $name,
			':pass' => $password,
			':ip' => $_SERVER['REMOTE_ADDR'],
			':rank' => '0'
		]);
		
		return $success;
	}
	
	/**
	 * Creates a BCrypt hashed password with cost set in Configuration.
	 *
	 * @param string $pass
	 * @return string
	 */
	private function createPasswordHash($pass) {
		global $_CONFIG;
		return password_hash($pass, PASSWORD_BCRYPT, ['cost' => $_CONFIG['app_hash_cost']]);
	}
	
	/**
	 * Checks if given username exists on database.
	 *
	 * @param string $name
	 * @return boolean
	 */
	private function nameExists($name) {
		$stmt = BoardCore::getDatabase()->prepare('SELECT name FROM users WHERE name = :name LIMIT 1');
		$stmt->execute([':name' => $name]);
		return $stmt->rowCount() > 0;
	}
}
