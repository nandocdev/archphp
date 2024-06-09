<?php
/**
 * @package     core/Router
 * @subpackage  Server
 * @file        Sessions
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-06-08 15:58:36
 * @version     1.0.0
 * @description
 */

declare(strict_types=1);

namespace Arch\Core\Router\Server;

class Sessions {
   public static function start(): void {
      if (session_status() === PHP_SESSION_NONE) {
         session_start();
      }
   }

   public static function set(string $key, $value): void {
      $_SESSION[$key] = $value;
   }

   public static function get(string $key) {
      return $_SESSION[$key] ?? null;
   }

   public static function exists(string $key): bool {
      return isset($_SESSION[$key]);
   }

   public static function delete(string $key): void {
      unset($_SESSION[$key]);
   }

   public static function destroy(): void {
      if (session_status() === PHP_SESSION_ACTIVE) {
         session_unset();
         session_destroy();
      }
   }

   public static function regenerate(bool $deleteOldSession = false): void {
      session_regenerate_id($deleteOldSession);
   }

   public function serialize(): string {
      return serialize($_SESSION);
   }
}