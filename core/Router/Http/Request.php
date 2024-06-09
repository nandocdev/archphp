<?php
/**
 * @package     core/Router
 * @subpackage  Http
 * @file        Request
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-06-08 15:57:23
 * @version     1.0.0
 * @description
 */

declare(strict_types=1);

namespace Arch\Core\Router\Http;

class Request {
   public string $url = '';
   public string $base = '';
   public string $method = '';
   public string $referer = '';
   public string $ip = '';
   public bool $ajax = false;
   public string $scheme = '';
   public string $user_agent = '';
   public string $type = '';
   public int $length = 0;
   public array $params;
   public array $body;
   public array $files;
   public array $cookies;
   public array $sessions;
   public array $headers;
   public array $server;
   public bool $secure = false;
   public string $accept = '';
   public string $proxy_ip = '';
   public string $host = '';

   public function __construct(array $config = []) {
      if (empty($config)) {
         $config['url'] = str_replace('@', '%40', self::getVars('REQUEST_URI', '/'));
         $config['base'] = self::getBase();
         $config['method'] = self::get_method();
         $config['referer'] = self::getVars('HTTP_REFERER');
         $config['ip'] = $_SERVER['REMOTE_ADDR'] ?: ($_SERVER['HTTP_X_FORWARDED_FOR'] ?: $_SERVER['HTTP_CLIENT_IP']);
         $config['ajax'] = self::isAjax();
         $config['scheme'] = self::getScheme();
         $config['user_agent'] = self::getVars('HTTP_USER_AGENT');
         $config['type'] = self::getVars('CONTENT_TYPE');
         $config['length'] = (int) self::getVars('CONTENT_LENGTH', '0');
         $config['params'] = self::setParams();
         $config['body'] = self::get_body();
         $config['files'] = $_FILES;
         $config['cookies'] = $_COOKIE;
         $config['sessions'] = $_SESSION;
         $config['headers'] = self::getHeaders();
         $config['server'] = $_SERVER;
         $config['secure'] = 'https' === self::getScheme();
         $config['accept'] = self::getVars('HTTP_ACCEPT');
         $config['proxy_ip'] = self::getProxyIpAddress();
         $config['host'] = self::getVars('HTTP_HOST');
      }
      $this->init($config);
   }


   private static function getHeaders(): array {
      if (!function_exists('getallheaders')) {
         $headers = [];
         foreach ($_SERVER as $name => $value) {
            if ('HTTP_' === substr($name, 0, 5)) {
               $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
         }
         return $headers;
      }
      return getallheaders();
   }

   public static function getVars(string $var, string $value = ""): string {
      return $_SERVER[$var] ?? $value;
   }

   // define el metodo de la peticion
   public static function get_method(): string {
      $method = self::getVars('REQUEST_METHOD', 'GET');
      if (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
         $method = $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];
      } else if (isset($_REQUEST['_method'])) {
         $method = $_REQUEST['_method'];
      }
      return strtoupper($method);
   }

   // obtiene el valor de las variables $_POST, $_PUT, $_DELETE. $_PATCH
   public static function get_body(): array {
      static $body;
      if (!is_null($body)) {
         return $body;
      }
      $method = self::get_method();
      if (in_array($method, ['POST', 'PUT', 'DELETE', 'PATCH'])) {
         $body = $_POST;
         if ($method === 'PUT') {
            parse_str(file_get_contents('php://input'), $body);
         } else if ($method === 'DELETE') {
            parse_str(file_get_contents('php://input'), $body);
         } else if ($method === 'PATCH') {
            parse_str(file_get_contents('php://input'), $body);
         }
         foreach ($body as $key => $value) {
            $body[$key] = self::cleanInput($value);
         }
      } else {
         $body = [];
      }
      return $body;
   }

   private static function setParams() {
      // evalua la variable global GET, elimina todos los elemenetos que contengan como clave url
      if (isset($_GET['url'])) {
         unset($_GET['url']);
      }
      return $_GET;
   }

   public function addParams(array $params): void {
      $this->params = array_merge($this->params, $params);
   }

   public static function getProxyIpAddress(): string {
      static $forwarded = [
      'HTTP_CLIENT_IP',
      'HTTP_X_FORWARDED_FOR',
      'HTTP_X_FORWARDED',
      'HTTP_X_CLUSTER_CLIENT_IP',
      'HTTP_FORWARDED_FOR',
      'HTTP_FORWARDED',
      ];

      $flags = \FILTER_FLAG_NO_PRIV_RANGE | \FILTER_FLAG_NO_RES_RANGE;

      foreach ($forwarded as $key) {
         if (\array_key_exists($key, $_SERVER)) {
            sscanf($_SERVER[$key], '%[^,]', $ip);
            if (false !== filter_var($ip, \FILTER_VALIDATE_IP, $flags)) {
               return $ip;
            }
         }
      }

      return '';
   }

   public static function getScheme(): string {
      if (
         (isset($_SERVER['HTTPS']) && 'on' === strtolower($_SERVER['HTTPS']))
         || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && 'https' === $_SERVER['HTTP_X_FORWARDED_PROTO'])
         || (isset($_SERVER['HTTP_FRONT_END_HTTPS']) && 'on' === $_SERVER['HTTP_FRONT_END_HTTPS'])
         || (isset($_SERVER['REQUEST_SCHEME']) && 'https' === $_SERVER['REQUEST_SCHEME'])
      ) {
         return 'https';
      }

      return 'http';
   }

   public static function parseParams(string $url): array {
      $params = [];
      $args = parse_url($url);
      if (isset($args['query'])) {
         parse_str($args['query'], $params);
      }

      return $params;
   }

   public static function getClientIp(): string {
      return $_SERVER['REMOTE_ADDR'] ?: ($_SERVER['HTTP_X_FORWARDED_FOR'] ?: $_SERVER['HTTP_CLIENT_IP']);
   }

   private static function cleanInput(string $input): string {
      $search = array(
         '@<script[^>]*?>.*?</script>@si',
         '@<[\ /\!]*?[^<>]*?>@si',
         '@<style[^>]*?>.*?</style>@siU',
         '@
      <![\s\S]*?--[ \t\n\r]*>@',
         '@SELECT\s+.*?\s+FROM\s+.*?\s+WHERE\s+.*?\s+LIKE\s+.*?@si',
      );
      $output = preg_replace($search, '', $input);
      return $output;
   }

   public static function getBase(): string {
      $url = strtolower(explode('/', $_SERVER['SERVER_PROTOCOL'])[0]) . '://' . $_SERVER['HTTP_HOST'];
      if (!empty($_GET['url'])) {
         $query_string = '';
         if (count($_GET) > 1) {
            $query_string = '?';
            foreach ($_GET as $key => $value) {
               if ($key != 'url') {
                  $query_string .= $key . '=' . $value . '&';
               }
            }
            $query_string = rtrim($query_string, '&');
         }
         $url .= str_replace($_GET['url'] . $query_string, '', urldecode($_SERVER['REQUEST_URI']));
      } else {
         $url .= $_SERVER['REQUEST_URI'];
      }
      return $url;
   }

   public static function isAjax(): bool {
      return 'XMLHttpRequest' === self::getVars('HTTP_X_REQUESTED_WITH');
   }

   public function init(array $property = []): void {
      foreach ($property as $key => $value) {
         $this->$key = $value;
      }

      if ('/' !== $this->base && '' !== $this->base && 0 === strpos($this->url, $this->base)) {
         $this->url = substr($this->url, \strlen($this->base));
      }

      if (empty($this->url)) {
         $this->url = '/';
      } else {
         $_GET = array_merge($_GET, self::parseParams($this->url));
         $this->params[] = $_GET;
      }

      if (0 === strpos($this->type, 'application/json')) {
         $body = self::get_method();
         if ('' !== $body && null !== $body) {
            $data = json_decode($body, true);
            if (is_array($data)) {
               $this->body[] = $data;
            }
         }
      }
   }

   public function __set(string $key, mixed $value) {
      $this->$key = $value;
   }

   public function __get(string $key) {
      return $this->$key;
   }

   public function __add(string $key, mixed $value) {
      if (isset($this->$key)) {
         if (is_array($this->$key)) {
            $this->$key[] = $value;
         } else {
            $this->$key .= $value;
         }
      } else {
         $this->$key = $value;
      }
   }

}