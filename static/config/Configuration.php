<?php defined('APP') or die();

$_CONFIG = [];

/**
 * Database hostname, usually localhost.
 */
$_CONFIG['db_host'] = 'localhost';

/**
 * Database username, usually root. (MySQL username, if using MySQL)
 */
$_CONFIG['db_user'] = 'root';

/**
 * Database password, can be empty. (MySQL password, if using MySQL)
 * EMPTY MYSQL PASSWORD IS NOT GOOD FOR SECURITY!
 */
$_CONFIG['db_pass'] = '';

/**
 * Database name, used for getting data from right tables inside this database.
 */
$_CONFIG['db_name'] = 'board_lite';

/**
 * If you're using MySQL, you don't need to change this.
 */
$_CONFIG['db_conn_str'] = 'mysql:host=%s;dbname=%s';

/**
 * Application name, used for website title, navigation bar brand and in future maybe something.
 */
$_CONFIG['app_name'] = 'BoardLite';

/**
 * Used for verifying that request is coming from this website.
 */
$_CONFIG['app_url'] = 'http://localhost/';

/**
 * Set's language for your application, available languages so far: en_US, fi_FI
 *
 * You can always add your own language:
 * 1. Create lang file on 'assets/lang/lang-lang.json', look example from existing files.
 * 2. Add that 'lang-lang' here, without .json ending.
 * 3. Good to go.
 */
$_CONFIG['app_lang'] = 'en_US';

/**
 * Set's cost for user password hash, created in registration.
 */
$_CONFIG['app_hash_cost'] = 10; 
