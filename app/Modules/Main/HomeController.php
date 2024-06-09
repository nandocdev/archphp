<?php
/**
 * @package     app/Modules
 * @subpackage  Home
 * @file        HomeController
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-06-08 16:05:37
 * @version     1.0.0
 * @description
 */

declare(strict_types=1);

namespace Arch\App\Modules\Main;

class HomeController {
   public function index($req, $res) {
      $res->render('home/index', []);
   }

   public function caracteristicas($req, $res) {
      $res->render('caracteristicas/index', []);
   }

   public function documentacion($req, $res) {
      $res->render('documentacion/index', []);
   }

   public function contacto($req, $res) {
      $res->render('contacto/index', []);
   }

}