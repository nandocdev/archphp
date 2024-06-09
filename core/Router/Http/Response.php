<?php
/**
 * @package     core/Router
 * @subpackage  Http
 * @file        Response
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-06-08 15:57:38
 * @version     1.0.0
 * @description
 */

declare(strict_types=1);

namespace Arch\Core\Router\Http;

use Arch\Core\Router\Server\Sessions;
use Arch\Core\Router\Server\Cookies;
use Arch\Core\Libs\EngineView\Engine;
use Arch\Core\Libs\EngineView\Components\FlashMessages;


class Response {
   protected $body;
   protected $headers;
   protected $length;
   protected $status;
   protected $sends;
   protected $cookie;
   protected $session;
   public function __construct() {
      $this->body = "";
      $this->headers['Content-Type'] = 'text/html; charset=utf-8';
      $this->headers['X-Powered-By'] = APP_NAME;
      $this->headers['Content-Length'] = 0;
      $this->status = 200;
      $this->cookie = new Cookies();
      $this->session = new Sessions();
   }

   public function headers(string $key = "", string $value = ""): self {
      $this->headers[$key] = $value;
      return $this;
   }

   public function removeHeader(string $key): self {
      if (isset($this->headers[$key])) {
         unset($this->headers[$key]);
      }
      return $this;
   }

   public function status(int $code = 200): self {

      $this->status = (int) $code;
      return $this;
   }

   // set default content type text/plain; charset=UTF-8'
   public function contentType(string $type = 'text/plain; charset=UTF-8'): self {
      $this->headers['Content-Type'] = $type;
      return $this;
   }

   public function body(string $body = ""): self {
      if (!is_null($body)) {
         $this->body = $body;
         $this->headers['Content-Length'] = strlen($body);
      }
      return $this;
   }

   public function html(string $body = ""): self {
      $this->contentType('text/html; charset=utf-8');
      $this->body($body);
      return $this;
   }

   public function json(array $body = []) {
      if (is_array($body) || is_object($body)) {
         $this->contentType('application/json; charset=utf-8');
         $this->body(json_encode($body));
      }
      return $this;
   }

   public function text(string $body = ""): self {
      if (is_string($body)) {
         $this->contentType('text/plain; charset=utf-8');
         $this->body($body);
      }
      return $this;
   }

   public function xml(string $body = ""): self {
      if (is_string($body)) {
         $this->contentType('text/xml; charset=utf-8');
         $this->body($body);
      }
      return $this;
   }

   public function download(string $file, string $name = ""): self {
      if (is_null($name) || empty($name)) {
         $name = basename($file);
      }
      $this->headers('Content-Type', mime_content_type($file));
      $this->headers('Content-Disposition', 'attachment; filename=' . $name);
      $this->headers('Content-Length', (string) filesize($file));
      $this->body(file_get_contents($file));
      return $this;
   }

   public function redirect(string $url): self {
      // evalua si la cadena $url continene http o https
      if (!preg_match('/^http(s)?:\/\//', $url)) {
         $url = APP_URL . ltrim($url, '/');
      }

      if (filter_var($url, FILTER_VALIDATE_URL)) {
         $this->headers('Location', $url);
         $this->status(302);
      }

      if (headers_sent()) {
         $script = "";
         $script .= `<script type="text/javascript"> window.location.href = "$url"; </script>`;
         $script .= `<nonscript><meta http-equiv="refresh" content="0; url=$url" /></noscript>`;
         $this->body($script);
      } else {
         $this->status(302);
         $this->headers('Location', $url);
      }

      return $this;
   }

   public function back(): self {
      if (isset($_SERVER['HTTP_REFERER'])) {
         $this->redirect($_SERVER['HTTP_REFERER']);
      } else {
         $this->redirect(APP_URL);
      }
      return $this;
   }

   public function flash(string $type = "success", string $message = "", string $title = ""): self {
      $flash = new FlashMessages();
      $flash->addMessage($message, $type, $type, $title);
      return $this;
   }

   public function render(string $viewFile, array $params = [], string $layoutTemplate = "default"): self {
      $view = new Engine($viewFile, $params, $layoutTemplate);
      $this->html($view->render());
      return $this;
   }

   public function debug($data): self {
      \Kint\Kint::dump($data);
      return $this;
   }

   public function cookie(
      string $key = "",
      string $value = "",
      int $time = 0,
      string $path = "/",
      string $domain = "",
      bool $secure = false,
      bool $httponly = false): self {
      $this->cookie->set($key, $value, $time, $path, $domain, $secure, $httponly);
      return $this;
   }

   public function session(string $key = "", mixed $value = "", int $time = 0): self {
      $this->session->set($key, $value);
      return $this;
   }
   private function build(): self {
      if (is_null($this->body)) {
         $this->removeHeader('Content-Length');
         $this->removeHeader('Content-Type');
      } else {
         $this->headers('Content-Length', (string) strlen($this->body));
      }
      return $this;
   }

   public function __destruct() {
      $this->build();
      http_response_code($this->status);
      foreach ($this->headers as $key => $value) {
         header($key . ': ' . $value);
      }
      echo $this->body;
      return;
   }
}