<?php
/**
 * @package     srv/dev
 * @subpackage  config
 * @file        config
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-06-07 21:18:50
 * @version     1.0.0
 * @description
 */


define('APP_NAME', 'ArchPHP');
define('APP_VERSION', '1.0.0');
define('APP_CREATOR', 'Fernando Castillo');
define('APP_CREATOR_EMAIL', 'ferncastillo@css.gob.pa');
define('APP_URL_CREATOR', 'https://nandocv.github.io/');
define('APP_TIMEZONE', 'America/Panama');
define('APP_CHARSET', 'UTF-8');
define('APP_LANGUAGE', 'es_PA');
define('APP_PREFIX', 'arch_');

$dev_host = ['localhost', 'dev.local', 'mihost.com'];

$prod_host = [];
$prod_host = [];
$url = '';

define('APP_ENV', isset($_SERVER['HTTP_HOST'], $dev_host) ? 'development' : 'production');
define('APP_DEBUG', isset($_SERVER['HTTP_HOST'], $dev_host) ? true : false);
if (isset($_SERVER['HTTP_HOST'])) {
   // APP ENVIRONMENT

   // obtiene el protocolo de la peticion
   define('APP_PROTOCOL', isset($_SERVER['HTTPS']) ? 'https://' : 'http://');

   // define url
   $url = APP_PROTOCOL . $_SERVER['HTTP_HOST'];
   if (!empty($_GET['url'])) {
      $query_string = '';
      if (count($_GET) > 1) {
         $query_string = '?';
         foreach ($_GET as $key => $value) {
            if ($key != 'url') {
               $query_string .= $key . '=' . $value . '&';
            }
         }
         $query_string = rtrim($query_string, '&');
      }
      $url .= str_replace($_GET['url'] . $query_string, '', urldecode($_SERVER['REQUEST_URI']));
   } else {
      $url .= $_SERVER['REQUEST_URI'];
   }

}

define('DS', DIRECTORY_SEPARATOR);
define('APP_URL', $url);
define('APP_PUBLIC', APP_URL . 'public/');
// define('DS', DIRECTORY_SEPARATOR);
define('APP_ROOT', dirname(__DIR__, 1) . DS);
define('ARCH_CORE', APP_ROOT . 'core' . DS);
define('ARCH_TMP', APP_ROOT . 'tmp' . DS);
define('ARCH_APP', APP_ROOT . 'app' . DS);
define('ARCH_APP_MODELS', ARCH_APP . 'Models' . DS);
define('ARCH_APP_MODULES', ARCH_APP . 'Modules' . DS);
define('ARCH_APP_SERVICES', ARCH_APP . 'Services' . DS);
define('ARCH_CONFIG', APP_ROOT . 'config' . DS);
define('ARCH_PUBLIC', APP_ROOT . 'public' . DS);
define('ARCH_PUBLIC_ASSETS', ARCH_PUBLIC . 'assets' . DS);
define('ARCH_PUBLIC_LAYOUTS', ARCH_PUBLIC . 'layouts' . DS);
define('ARCH_STORAGE', APP_ROOT . 'storage' . DS);
define('ARCH_VENDOR', APP_ROOT . 'vendor' . DS);
// definimos las rutas de los archivos temporales [cache, logs, sessions, uploads] dentro de la carpeta tmp
define('ARCH_TMP_CACHE', ARCH_TMP . 'cache' . DS);
define('ARCH_TMP_LOGS', ARCH_TMP . 'logs' . DS);
define('ARCH_TMP_SESSIONS', ARCH_TMP . 'sessions' . DS);
define('ARCH_STORAGE_UPLOADS', ARCH_STORAGE . 'Uploads' . DS);
define('ERROR_LOGS', ARCH_TMP_LOGS . date('Ymd') . '-' . strtolower(str_replace(' ', '', APP_PREFIX . APP_NAME)) . '_errors.log');

define('APP_STATUS', 'ACTIVE'); // [ACTIVE, INACTIVE, MANTENANCE]
define('APP_BREADCRUMB', true); //[true, false]
define('APP_DEFAULT_LAYOUT', 'default');
define('APP_DEFAULT_MODULE', 'main');
define('APP_DEFAULT_CONTROLLER', 'home');
define('APP_DEFAULT_ACCTION', 'index');
define('APP_BASE_NAMESPACE', 'Arch\\App\\Modules\\');

// Tipo de driver de correo
define('MAIL_DRIVER', 'smtp');

// Host del servidor de correo
define('MAIL_HOST', 'smtp-mail.outlook.com');

// Puerto del servidor de correo
define('MAIL_PORT', 587);

// Nombre de usuario del correo
define('MAIL_USERNAME', 'lmnotify@outlook.com');

// Contraseña del correo
define('MAIL_PASSWORD', 'WtBp{!299X&a');

// Tipo de encriptación del correo
define('MAIL_ENCRYPTION', 'STARTTLS');

// Dirección de correo remitente
define('MAIL_FROM_ADDRESS', 'lmnotify@outlook.com');

// Nombre del remitente
define('MAIL_FROM_NAME', APP_NAME);

// Codificación del correo
define('MAIL_CHARSET', 'UTF-8');


// Session
define('SESSION_NAME', md5(strtoupper(str_replace(' ', '', 'session_' . APP_NAME))));
define('COOKIE_NAME', md5(strtoupper(str_replace(' ', '', 'cookie_' . APP_NAME))));
define('SESSION_NOTIFY', md5(APP_PREFIX . 'notice'));
define('SESSION_DRIVER', 'file');
define('SESSION_LIFETIME', 120);
define('SESSION_COOKIE', strtoupper(str_replace(' ', '', APP_PREFIX . APP_NAME)));
define('SESSION_DOMAIN', null);
define('SESSION_SECURE', false);

// security
define('HASH_KEY', sha1('9Q2YC44NQwSDKYUYhkXU6y2WP')); // generar una nueva cadena aleatoria
define('HASH_ALGO', 'HS256');
define('HASH_COST', 10);
define('HASH_PWD', '12345678');

// PHP init configs
ini_set('server.admin', APP_CREATOR_EMAIL);
ini_set('display_errors', APP_DEBUG ? 1 : 0);
ini_set('error_reporting', APP_DEBUG ? E_ALL : 0);
ini_set('display_startup_errors', APP_DEBUG ? 1 : 0);
ini_set('date.timezone', APP_TIMEZONE);
ini_set('default_charset', APP_CHARSET);
ini_set('log_errors', 1);
setlocale(LC_ALL, 'es_PA');
setlocale(LC_TIME, 'es_PA.UTF-8');

$dbs = ARCH_CONFIG . 'database.php';
if (!file_exists($dbs)) {
   throw new \Exception("No se encontro el archivo de configuracion de la base de datos", 1);
}

require_once $dbs;
