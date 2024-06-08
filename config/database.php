<?php
/**
 * @package     srv/dev
 * @subpackage  config
 * @file        database
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-06-07 21:35:57
 * @version     1.0.0
 * @description
 */

$db = [];
$db['default']['driver'] = "mysql";
$db['default']['host'] = APP_DEBUG ? "127.0.0.1" : "127.0.0.1";
$db['default']['user'] = APP_DEBUG ? "desarrollo" : "desarrollo";
$db['default']['pass'] = APP_DEBUG ? "1q2w3e4r5t" : "1q2w3e4r5t";
$db['default']['database'] = APP_DEBUG ? "dnasa0_20240401" : "dnasa0_20240401";
$db['default']['charset'] = "utf8";
$db['default']['port'] = "3306";
$db['default']['prefix'] = '';
$db['default']['options'] = [
   PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
   PDO::ATTR_EMULATE_PREPARES => false,
   PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];



// configuraciones de la base de datos
define('DB', $db);