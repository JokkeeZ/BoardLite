<?php

/**
 * Provides functionality to install application.
 *
 * @version 1.0
 * @copyright Copyright © 2018 JokkeeZ
 *
 * @license Licensed under MIT License.
 */
class Installer
{
	/**
	 * @var PDO $database PDO instance.
	 */
	private static $databaseInstance;

	private final function __construct() { }
	private final function __clone() { }

	/**
	 * Setups PDO database instance.
	 *
	 * @param array Configuration
	 * @param bool $databaseCreated Determines if database tables have been created.
	 */
	public static function setupDatabase(array $config, bool $databaseCreated)
	{
		$dsn = $config['db_dsn'];
		if (!$databaseCreated) {
			$values = explode(';', $dsn);
			$dsn = $values[0];
		}

		self::$databaseInstance = new PDO($dsn,
			$config['db_user'],
			$config['db_pass']
		);

		self::$databaseInstance->setAttribute(
			PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION
		);

		Logger::write_data('DSN true:' . $dsn);
	}

	/**
	 * Gets PDO database instance.
	 *
	 * @return PDO Returns PDO instance.
	 */
	public static function getDatabase() : PDO
	{
		return self::$databaseInstance;
	}

	/**
	 * Checks $_POST array for empty entries.
	 *
	 * @return bool Returns false if empty entries found; otherwise true.
	 */
	public static function validatePostRequest() : bool
	{
		if (!empty($_POST['submit']) && isset($_POST['submit'])) {
			foreach ($_POST as $k => $v) {
				if (!isset($_POST[$k])) {
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Inserts a admin account to the database with given username and password.
	 *
	 * @return PDOStatement
	 */
	public static function createAdminAccount() : PDOStatement
	{
		$stmt = self::$databaseInstance->prepare(
			'INSERT INTO users (name, pass, ip, rank) VALUES (:name, :pass, :ip, \'2\')'
		);

		$stmt->execute([
			':name' => $_POST['aName'],
			':pass' => password_hash($_POST['aPass'], PASSWORD_BCRYPT, ['cost' => 10]),
			':ip'   => $_SERVER['REMOTE_ADDR']
		]);

		return $stmt;
	}

	/**
	 * Deletes this installation directory and all it's files and child directories.
	 *
	 * @param string $dir Directory path
	 * @return bool Returns true if directory was deleted; otherwise false.
	 */
	public static function deleteDirectory(string $dir) : bool
	{
		$files = array_diff(scandir($dir), array('.', '..'));

		foreach ($files as $file) {
			if (is_dir("$dir/$file")) {
				self::deleteDirectory("$dir/$file");
			} else {
				unlink("$dir/$file");
			}
		}

		return rmdir($dir);
	}

	/**
	 * Creates configuration file to the ../static/config/ directory.
	 *
	 * @param array $configTemplate Configuration file template
	 * @return bool|int If file was created, returns number of bytes written; otherwise bool false.
	 */
	public static function createConfigurationFile(array $configTemplate)
	{
		return file_put_contents('../static/config/Configuration.php', sprintf(implode(PHP_EOL, $configTemplate),
			'$_CONFIG',
			$_POST['dbUser'],
			$_POST['dbPass'],
			$_POST['dbHost'],
			$_POST['appName'],
			$_POST['appLang']
		));
	}
}