<?php
define('DEV_MODE', true);

require_once '../static/JsonResponse.php';
require_once '../static/Logger.php';

// Template for configuration file.
$configTemplate = [
	"<?php",
	"%s = [",
	"	'db_user' => '%s',",
	"	'db_pass' => '%s',",
	"	'db_dsn' => 'mysql:host=%s;dbname=board_lite;charset=utf8mb4',",
	"	'app_name' => '%s',",
	"	'app_lang' => '%s',",
	"	'app_hash_cost' => 10,",
	"	'app_installed' => true",
	"];"
];

// Setup object for response.
$response = new JsonResponse();

// Check if configuration file already exists.
$configurationFile = '../static/config/Configuration.php';
if (file_exists($configurationFile)) {
	$response->append('error', 'App already installed.');
	exit($response->to_json());
}

require_once 'Installer.php';

// Validate post request.
if (!Installer::validatePostRequest()) {
	$response->append('error', 'POST entries cannot be empty.');
	exit($response->to_json());
}

// Create a configuration file.
if (!Installer::createConfigurationFile($configTemplate)) {
	// Failed to create file.
	$response->append('error', 'Creating configuration file failed.');
	exit($response->to_json());
}

// Check if .sql file exists.
$sqlFile = 'board_lite.sql';
if (!file_exists($sqlFile)) {
	$response->append('error', 'board_lite.sql does not exist.');
	exit($response->to_json());
}

// Require configuration file
require_once '../static/config/Configuration.php';

// Setup database instance.
Installer::setupDatabase($_CONFIG, false);

// Create database
Installer::getDatabase()->exec('CREATE DATABASE board_lite;');

// Setup database instance for table queries.
Installer::setupDatabase($_CONFIG, true);

// Create tables.
Installer::getDatabase()->exec(file_get_contents($sqlFile));

// Insert admin user in to users table.
Installer::createAdminAccount();

// Is installer using dev mode?
if (!DEV_MODE) {
	// Yup, delete installation directory.
	Installer::deleteDirectory(__DIR__);
}

// No errors, installed successfully.
$response->append('error', 0);
exit($response->to_json());
