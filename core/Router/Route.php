<?php
/**
 * @package     dev/core
 * @subpackage  Router
 * @file        Route
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-06-08 15:43:59
 * @version     1.0.0
 * @description
 */

declare(strict_types=1);

namespace Arch\Core\Router;

use Arch\Core\Router\Http\Request;
use Arch\Core\Router\Http\Response;

// toma la url y la divide en partes [modulo, controlador, metodo, parametros]
class Route {
   private array $_url;
   private string $_module;
   private string $_controller;
   private string $_method;
   private array $_params;

   public function __construct() {
      $this->_url = $this->parse();
      $this->_module = APP_DEFAULT_MODULE;
      $this->_controller = APP_DEFAULT_CONTROLLER;
      $this->_method = APP_DEFAULT_ACCTION;
      $this->_params = [];

   }

   // obtiene la url y la divide en partes
   private function parse(): array {
      if (!isset($_GET['url'])) {
         $this->_url = [APP_DEFAULT_MODULE, APP_DEFAULT_CONTROLLER, APP_DEFAULT_ACCTION, []];
         return $this->_url;
      }
      $url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL, FILTER_NULL_ON_FAILURE);
      $url = explode('/', $url);
      $this->_url = array_filter($url);
      return $this->_url;
   }

   private function toCamelCase(string $string, bool $firstLower = false): string {
      if (strpos($string, '-') === false && strpos($string, '_') === false) {
         return $firstLower ? lcfirst($string) : ucfirst($string);
      }

      $parts = preg_split('/[-_]/', strtolower($string));

      $result = "";
      foreach ($parts as $key => $value) {
         if ($firstLower && $key == 0) {
            $result .= $value;
         } else {
            $result .= ucfirst($value);
         }
      }
      return $result;
   }

   // getters
   public function module(): string {
      $module = $this->_url[0] ?? APP_DEFAULT_MODULE;
      $this->_module = $this->toCamelCase($module);
      return $this->_module;
   }
   public function controller() {
      $controller = $this->_url[1] ?? APP_DEFAULT_CONTROLLER;
      $this->_controller = $this->toCamelCase($controller);
      return $this->_controller;
   }
   public function method() {
      $method = $this->_url[2] ?? APP_DEFAULT_ACCTION;
      $this->_method = $this->toCamelCase($method, true);
      return $this->_method;
   }
   public function params() {
      if (count($this->_url) > 3) {
         $this->_params = array_slice($this->_url, 3);
      }
      return $this->_params;
   }

}