<?php
/**
 * @package     srv/dev
 * @subpackage  public
 * @file        index
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-06-08 14:43:23
 * @version     1.0.0
 * @description
 */
$kernel = __DIR__ . '/../core/App/Kernel.php';
if (!file_exists($kernel)) {
   throw new \Exception('Archivo de kernel no encontrado');
}

require_once $kernel;
Arch\Core\App\Kernel::init();
$container = Arch\Core\App\Kernel::container();

\Kint::dump($container);

set_error_handler('Arch\Core\Handler\Exceptions::errorHandler');
set_exception_handler('Arch\Core\Handler\Exceptions::exceptionHandler');

$router = $container->get('router');