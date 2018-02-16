<?php
define('APP', 1);
define('DEBUG', true);

require '../static/JsonResponse.php';

$configTemplate =
[
	"<?php defined('APP') or die;",
	"%s = [",
	"	'db_host' => '%s',",
	"	'db_user' => '%s',",
	"	'db_pass' => '%s',",
	"	'db_name' => 'board_lite',",
	"	'db_conn_str' => 'mysql:host=%s;dbname=%s',",
	"	'app_name' => '%s',",
	"	'app_lang' => '%s',",
	"	'app_hash_cost' => 10",
	"];"
];

function createConfigurationFile($values)
{
	global $configTemplate;
	file_put_contents('../static/config/Configuration.php', sprintf(implode(PHP_EOL, $configTemplate),
		'$_CONFIG',
		$values['dbHost'],
		$values['dbUser'],
		$values['dbPass'],
		'%s', '%s',
		$values['appName'],
		$values['appLang']
	));
}

function databaseExec($query, $dbname = '')
{
	$response = new JsonResponse();

	try
	{
		$pdo = new PDO('mysql:host=' . $_POST['dbHost'] . $dbname, $_POST['dbUser'], $_POST['dbPass']);
		$pdo->exec($query);
	}
	catch (PDOException $e)
	{
		$response->append('error', $e->getMessage());
		die($response->to_json());
	}
}

function getDatabaseInstance() : PDO
{
	$response = new JsonResponse();

	try
	{
		$pdo = new PDO('mysql:host=' . $_POST['dbHost'] . ';dbname=board_lite', $_POST['dbUser'], $_POST['dbPass']);
		
		$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $pdo;
	}
	catch (PDOException $e)
	{
		$response->append('error', $e->getMessage());
		die($response->to_json());
	}
}

$response = new JsonResponse();

if (empty($_POST['submit']) && !isset($_POST['submit'])) die;

foreach ($_POST as $k => $v)
{
	if (!isset($_POST[$k]))
	{
		$response->append('error', $k . ' cannot be empty.');
		die($response->to_json());
	}
}

createConfigurationFile($_POST);

$sqlFile = 'board_lite.sql';

if (!file_exists($sqlFile))
{
	$response->append('error', 'board_lite.sql doesn\'t exist.');
	die($response->to_json());
}

databaseExec('CREATE DATABASE board_lite;');
databaseExec(file_get_contents($sqlFile), ';dbname=board_lite');

$stmt = getDatabaseInstance()->prepare('INSERT INTO users (name, pass, ip, rank) VALUES (:name, :pass, :ip, \'2\')');
$stmt->execute([
	':name' => $_POST['aName'],
	':pass' => password_hash($_POST['aPass'], PASSWORD_BCRYPT, ['cost' => 10]),
	':ip'   => $_SERVER['REMOTE_ADDR']
]);

// http://php.net/manual/en/function.rmdir.php#110489
function delTree($dir)
{
	$files = array_diff(scandir($dir), array('.','..'));
	
	foreach ($files as $file)
	{
		(is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
	}

	return rmdir($dir);
} 

if ($stmt)
{
	if (!DEBUG)
	{
		// Delete install directory.
		delTree(__DIR__);
	}
	
	$response->append('error', 0);
	die($response->to_json());
}
?>
