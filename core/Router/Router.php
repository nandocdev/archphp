<?php
/**
 * @package     dev/core
 * @subpackage  Router
 * @file        Router
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-06-08 15:43:42
 * @version     1.0.0
 * @description
 */

declare(strict_types=1);

namespace Arch\Core\Router;

use Arch\Core\Router\Route;
use Arch\Core\Router\Http\Request;
use Arch\Core\Router\Http\Response;

//  recibe las partes de la url y lo convierte en una ruta para ca
class Router {
   private Route $_route;
   private Request $_request;
   private Response $_response;

   public function __construct() {
      $this->_route = new Route();
      $this->_request = new Request();
      $this->_response = new Response();
   }

   // obtiene la ruta del controlador y metodo
   private function setParams(): array {
      if ($this->_route->params()) {
         $this->_request->addParams($this->_route->params());
      }
      (array) $params = [];
      $params['req'] = $this->_request;
      $params['res'] = $this->_response;
      return $params;
   }

   // obtiene el namespace del controlador
   private function getNamespace(): string {
      $namespace = APP_BASE_NAMESPACE . $this->_route->module() . '\\Controllers\\' . $this->_route->controller() . 'Controller';
      // evalua si el namespace es valido
      if (!class_exists($namespace)) {
         throw new \Exception("Namespace no encontrado: $namespace");
      }
      return $namespace;
   }

   private function getMethod(): string {
      $method = $this->_route->method();
      // evalua si el metodo es valido
      if (!method_exists($this->getNamespace(), $method)) {
         throw new \Exception("Metodo no encontrado: $method");
      }
      return $method;
   }

   // ejecuta el controlador y metodo
   public function run() {
      $namespace = $this->getNamespace();
      $method = $this->getMethod();
      $params = $this->setParams();
      $controller = new $namespace($params);
      call_user_func_array([$controller, $method], $params);
   }

}