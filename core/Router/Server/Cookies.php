<?php
/**
 * @package     core/Router
 * @subpackage  Server
 * @file        Cookies
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-06-08 15:58:30
 * @version     1.0.0
 * @description
 */

declare(strict_types=1);

namespace Arch\Core\Router\Server;

class Cookies {
   public static function set(
      string $name,
      string $value,
      int $expire = 0,
      string $path = "/",
      string $domain = "",
      bool $secure = false,
      bool $httponly = false
   ): void {
      setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
   }

   public static function get(string $name): ?string {
      return $_COOKIE[$name] ?? null;
   }

   public static function delete(
      string $name,
      string $path = "/",
      string $domain = "",
      bool $secure = false,
      bool $httponly = false
   ): void {
      setcookie($name, "", time() - 3600, $path, $domain, $secure, $httponly);
      unset($_COOKIE[$name]);
   }

   public static function exists(string $name): bool {
      return isset($_COOKIE[$name]);
   }
}