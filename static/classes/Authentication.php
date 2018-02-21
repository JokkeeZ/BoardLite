<?php

/**
 * Provides functionality for login, register and logout actions.
 *
 * @author JokkeeZ
 * @version 1.1
 *
 * @copyright Copyright © 2016 - 2018 JokkeeZ
 * @license Licensed under MIT License.
 */
class Authentication extends Controller
{
	/**
	 * Validates user with given password and username, if password doesn't match hash password,
	 * or name doesn't exists, returns false.
	 *
	 * @param string $name
	 * @param string $pass
	 *
	 * @return bool If login success, returns true; otherwise false.
	 */
	public function login(string $name, string $pass) : bool
	{
		$stmt = $this->getDatabase()->prepare('SELECT * FROM users WHERE name = :name LIMIT 1');
		$stmt->execute([':name' => $name]);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($stmt->rowCount() > 0 && password_verify($pass, $row['pass'])) {
			$_SESSION['user'] = $row;

			// We don't need to store password on session.
			unset($_SESSION['user']['pass']);
			return true;
		}

		return false;
	}


	/**
	 * Registers a new user with given values.
	 *
	 * @param string $name Username
	 * @param string $pass Password
	 *
	 * @return bool If name already exists, returns false; otherwise true.
	 */
	public function register(string $name, string $pass) : bool
	{
		if ($this->usernameExists($name))
			return false;

		$stmt = $this->getDatabase()->prepare(
			'INSERT INTO users (name, pass, ip, rank) VALUES (:name, :pass, :ip, :rank)'
		);

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
	 * Creates a BCrypt hashed password with cost that is set in Configuration.
	 *
	 * @param string $password Password
	 * @return string Returns hashed password.
	 */
	private function createPasswordHash(string $password) : string
	{
		return password_hash($password, PASSWORD_BCRYPT, [
			'cost' => $this->config['app_hash_cost']]
		);
	}
	
	/**
	 * Checks if given username exists on database.
	 *
	 * @param string $name Username
	 * @return bool If username exists, returns true; otherwise false.
	 */
	private function usernameExists(string $name) : bool
	{
		$stmt = $this->getDatabase()->prepare('SELECT name FROM users WHERE name = :name');
		$stmt->execute([':name' => $name]);

		return $stmt->rowCount() > 0;
	}

	/**
	 * Destroys current cookie session and clears $_SESSION array.
	 */
	public function logout()
	{
		session_destroy();
		$_SESSION = [];
	}
}
