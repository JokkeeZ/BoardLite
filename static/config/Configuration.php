<?php defined('APP') or die();

$_CONFIG = [];

// Database hostname
$_CONFIG['db_host'] = 'localhost';

// Database username
$_CONFIG['db_user'] = 'root';

// Database password
$_CONFIG['db_pass'] = '';

// Database name
$_CONFIG['db_name'] = 'board_lite';

// Database connection string
$_CONFIG['db_conn_str'] = 'mysql:host=%s;dbname=%s';

// Application name, used mainly in navigation bar and title
$_CONFIG['app_name'] = 'BoardLite';

// Used for verifying that request is coming on this website.
$_CONFIG['app_url'] = 'http://localhost/';
