<?php
/**
 * @package     Libs/EngineView
 * @subpackage  Components
 * @file        JSLoader
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-06-09 12:27:30
 * @version     1.0.0
 * @description
 */

declare(strict_types=1);

namespace Arch\Core\Libs\EngineView\Components;

class JSLoader {
   public function load_js($dir_view) {

      $path_view = realpath($dir_view);
      // recorre los archivos para aobtemer solo los js
      $files = scandir($path_view);
      $codigo = '';
      foreach ($files as $key => $value) {
         if (strpos($value, '.js') !== false) {
            if ($value != '.' && $value != '..') {
               $code = file_get_contents($path_view . DS . $value);
               $code = $this->eliminarComentarios($code);
               $code = $this->eliminarEspacios($code);
               $code = $this->minificarCodigo($code);

               $codigo .= " " . $code;
            }
         }
      }
      return $codigo;
   }

   private function ofuscarCodigo($codigo): string {
      $ofuscado = base64_encode($codigo);
      $ofuscado = str_replace('=', '', $ofuscado);
      $ofuscado = str_replace('+', '', $ofuscado);
      $ofuscado = str_replace('/', '', $ofuscado);

      return $ofuscado;
   }

   private function eliminarComentarios($codigo): string {
      $codigo = preg_replace('/\/\*.*?\*\//s', "", $codigo);
      $codigo = preg_replace('/\/\/.*?[\r\n]/', "", $codigo);
      return $codigo;
   }

   private function eliminarEspacios($codigo): string {
      $codigo = preg_replace('/[ \t]+/', ' ', $codigo);
      $codigo = preg_replace('/[\r\n]+/', " ", $codigo);
      $codigo = preg_replace('/\n\s+/', " ", $codigo);

      return $codigo;
   }

   private static function minificarCodigo($codigo): string {
      $codigo = preg_replace('/(\n)\s*\}/', "}", $codigo);
      $codigo = preg_replace('/(\n)\s*\{/', "{", $codigo);
      $codigo = preg_replace('/(\n)\s*\,/', ",", $codigo);
      $codigo = preg_replace('/(\n)\s*\>/', ">", $codigo);
      $codigo = preg_replace('/(\n)\s*\</', "<", $codigo);
      $codigo = preg_replace('/(\n)\s*\+/', "+", $codigo);
      $codigo = preg_replace('/(\n)\s*\-/', "-", $codigo);
      $codigo = preg_replace('/(\n)\s*\=/', "=", $codigo);
      $codigo = preg_replace('/(\n)\s*\!/', "!", $codigo);
      $codigo = preg_replace('/(\n)\s*\"/', "\"", $codigo);
      $codigo = preg_replace('/(\n)\s*\&/', "&", $codigo);
      $codigo = preg_replace('/(\n)\s*\|/', "|", $codigo);
      $codigo = preg_replace('/(\n)\s*\(/', "(", $codigo);
      $codigo = preg_replace('/(\n)\s*\)/', ")", $codigo);
      $codigo = preg_replace('/(\n)\s*\[/', "[", $codigo);
      $codigo = preg_replace('/(\n)\s*\]/', "]", $codigo);
      $codigo = preg_replace('/(\n)\s*\{/', "{", $codigo);
      $codigo = preg_replace('/(\n)\s*\}/', "}", $codigo);
      $codigo = preg_replace('/(\n)\s*\;/', ";", $codigo);

      return $codigo;
   }
}