<?php defined('APP') or die;

/**
 * Provides functionality for login, register and logout actions.
 *
 * @author JokkeeZ
 * @version 1.1
 * @copyright Copyright © 2016 JokkeeZ
 */
class AuthenticationController extends Controller {

	/**
	 * Validates user with given password and username, if password doesn't match hash password,
	 * or name doesn't exists, returns false.
	 *
	 * @param string $name
	 * @param string $pass
	 * @return boolean
	 */
	public function login($name, $pass): bool {
		$stmt = $this->get_database()->prepare('SELECT * FROM users WHERE name = :name LIMIT 1');
		$stmt->execute([':name' => $name]);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		// Name found.
		if ($stmt->rowCount() > 0) {

			// Password matches.
			if (password_verify($pass, $row['pass'])) {
				$_SESSION['user'] = $row;

				// We don't need to store password on session.
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
	public function register($name, $pass):bool {
		if ($this->name_exists($name)) {
			return false;
		}

		$stmt = $this->get_database()->prepare('INSERT INTO users (name, pass, ip, rank) VALUES (:name, :pass, :ip, :rank)');
		$password = $this->create_password_hash($pass);

		$success = $stmt->execute([
			':name' => $name,
			':pass' => $password,
			':ip' => $_SERVER['REMOTE_ADDR'],
			':rank' => '0'
		]);

		if ($success == false) {
			echo 'WTF';
		}
		return $success;
	}
	
	/**
	 * Creates a BCrypt hashed password with cost that is set in Configuration.
	 *
	 * @param string $pass
	 * @return string
	 */
	private function create_password_hash($pass):string {
		return password_hash($pass, PASSWORD_BCRYPT, ['cost' => $this->config['app_hash_cost']]);
	}
	
	/**
	 * Checks if given username exists on database.
	 *
	 * @param string $name
	 * @return boolean
	 */
	private function name_exists($name):bool {
		$stmt = $this
			->get_database()
			->prepare('SELECT name FROM users WHERE name = :name');

		$result = $stmt->execute([':name' => $name]);
		return $stmt->rowCount() > 0;
	}

	/**
	 * Destroys current cookie session and clears $_SESSION array.
	 */
	public function logout() {
		session_destroy();
		$_SESSION = [];
	}
}
