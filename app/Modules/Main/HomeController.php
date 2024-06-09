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
      echo "Hello World!";
   }
}