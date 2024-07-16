<?php
/**
 * @package     core/Libs
 * @subpackage  EngineView
 * @file        Engine
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-06-09 12:33:03
 * @version     1.0.0
 * @description
 */

declare(strict_types=1);

namespace Arch\Core\Libs\EngineView;

use Arch\Core\Libs\EngineView\Components\Avatar;
use Arch\Core\Libs\EngineView\Components\JSLoader;
use Arch\Core\Libs\EngineView\Components\FlashMessages;
use Arch\Core\Router\Route;


class Engine {
   private string $dir_view;
   private string $vPath;
   public array $data;
   private string $layout;
   private string $js;
   private JSLoader $jsLoader;
   private FlashMessages $flashMessages;
   private Route $route;
   public function __construct(string $dir_view, array $data = [], string $layout = 'default') {
      $this->route = new Route();
      $this->dir_view = $this->setViewDir($dir_view);
      $this->js = dirname($this->dir_view) . DS . 'js' . DS;
      $this->data = $this->setData($data);
      $this->layout = $this->setLayoutDir($layout);
      $this->jsLoader = new JSLoader();
      $this->flashMessages = new FlashMessages();
   }

   private function setData(array $data): array {
      $this->data = $data;
      $this->setBreadcrumb();
      return $this->data;
   }
   private function setBreadcrumb(): void {
      $route = new Route();
      $this->data['breadcrumb'] = [$route->module(), $route->controller(), $route->method()];
   }
   private function setViewDir(string $dir_view): string {
      $modules = $this->route->module();
      if (defined('ERROR_HANDLER')) {
         $modules = 'Errors';
      }
      $dir_view = ARCH_APP_MODULES . $modules . DS . 'Views' . DS . $dir_view . '.view.phtml';

      if (!file_exists($dir_view)) {
         throw new \Exception("El directorio de vistas no existe: $dir_view");
      }

      // obten vPath eliminando el archivo de la vista del path
      $this->vPath = dirname($dir_view);

      return $dir_view;
   }

   private function setLayoutDir(string $layout): string {
      $layout = ARCH_PUBLIC_LAYOUTS . $layout . DS . 'index.layout.phtml';
      if (!file_exists($layout)) {
         throw new \Exception("El directorio de layouts no existe: $layout");
      }
      return $layout;
   }

   private function phpFileOutput(string $file, array $data = []): string {
      ob_start();
      extract($data);
      require_once $file;
      return ob_get_clean();
   }

   private function renderView(): string {
      return $this->phpFileOutput($this->dir_view, $this->data);
   }

   private function renderLayout(): string {
      return $this->phpFileOutput($this->layout, $this->data);
   }

   private function loadjs(): array {
      if (file_exists($this->js)) {
         $js = $this->jsLoader->load_js($this->js);
         $this->data['js'] = $js;
      }
      return $this->data;
   }

   public function render(): string {
      $this->loadjs();
      $view = $this->renderView();
      $layout = $this->renderLayout();
      $html = str_replace('@content', $view, $layout);
      return $html;
   }

   public function import(string $filename): void {
      $partial = $this->vPath . DS . 'partials' . DS . $filename . '.phtml';
      if (!file_exists($partial)) {
         throw new \Exception("El archivo parcial no existe: $partial");
      }
      require_once $partial;
   }

   public function avatar(string $name, string $surename): Avatar {
      return new Avatar($name, $surename);
   }

   public function alerts(): array {
      return $this->flashMessages->getMessages();
   }

}