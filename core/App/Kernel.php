<?php
/**
 * @package     dev/core
 * @subpackage  App
 * @file        Kernel
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-06-08 14:24:55
 * @version     1.0.0
 * @description
 */

declare(strict_types=1);

namespace Arch\Core\App;

use DI\ContainerBuilder;

class Kernel {
   private static function loadConfig(): void {
      $config = __DIR__ . '/../../config/config.php';
      // var_dump($config);
      if (!file_exists($config)) {
         throw new \Exception('Archivo de configuración no encontrado');
      }
      if (!defined('APP_NAME')) {
         require_once $config;
      } else {
         throw new \Exception('No se ha definido la constante APP_NAME');
      }
   }

   private static function autoload() {
      $autoload = __DIR__ . '/../../vendor/autoload.php';
      // var_dump($autoload);
      if (!file_exists($autoload)) {
         throw new \Exception('Archivo de autoload no encontrado');
      }
      require_once $autoload;
   }

   private static function sessions() {
      if (session_status() == PHP_SESSION_NONE) {
         ini_set('session.cookie_httponly', 1);
         ini_set('session.use_only_cookies', 1);
         ini_set('session.use_strict_mode', 0);
         session_start();
      }
   }

   public static function container() {
      $containerBuilder = new ContainerBuilder();
      $containerBuilder->useAutowiring(true);
      $containerBuilder->addDefinitions(ARCH_CONFIG . 'container.php');
      $container = $containerBuilder->build();
      return $container;
   }

   public static function init() {
      self::loadConfig();
      self::autoload();
      self::sessions();
   }
}