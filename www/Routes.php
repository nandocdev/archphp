<?php
/**
 * @package     srv/dev
 * @subpackage  www
 * @file        Routes
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-06-08 15:43:11
 * @version     1.0.0
 * @description
 */

declare(strict_types=1);

use Arch\Core\Router\Router;

Router::get('/', ['Arch\App\Modules\Home\HomeController', 'index']);


Router::submit();