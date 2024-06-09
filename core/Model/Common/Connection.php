<?php
/**
 * @package     core/Model
 * @subpackage  Common
 * @file        Connection
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-06-09 15:08:21
 * @version     1.0.0
 * @description
 */

declare(strict_types=1);

namespace Arch\Core\Model\Common;

use \PDO;

class Connection {
   private array $conf;
   private string $driver;
   private string $host;
   private string $username;
   private string $password;
   private string $database;
   private string $charset;
   private string $port;
   private string $prefix;
   private array $options;
   private string $dsn;
   public PDO $dbh;
   public function __construct(string $config = "default") {
      $config = empty($config) ? "default" : $config;
      $this->conf = DB[$config];
      $this->driver = $this->conf["driver"];
      $this->host = $this->conf["host"];
      $this->username = $this->conf["user"];
      $this->password = $this->conf["pass"];
      $this->database = $this->conf["database"];
      $this->charset = $this->conf["charset"];
      $this->port = $this->conf["port"];
      $this->prefix = $this->conf["prefix"];
      $this->options = $this->conf["options"];
   }


   public function getDsn(): string {
      $dsn = "";
      switch ($this->driver) {
         case 'mysql':
            $dsn = sprintf('mysql:host=%s;port=%u;dbname=%s;charset=%s',
               $this->host,
               $this->port,
               $this->database,
               $this->charset);
            break;
         case 'oci':
            $dsn = sprintf('oci:dbname=%s;charset=%s',
               $this->database,
               $this->charset);
            break;
         case 'firebird':
            $dsn = sprintf('firebird:dbname=%s;charset=%s',
               $this->database,
               $this->charset);
            break;
         case 'pgsql':
            $dsn = sprintf('pgsql:host=%s;port=%u;dbname=%s;user=%s;password=%s',
               $this->host,
               $this->port,
               $this->database,
               $this->username,
               $this->password);
            break;
         case 'sqlite':
            $dsn = sprintf('sqlite:%s',
               $this->database);
            break;
         case 'sybase':
         case 'mssql':
         case 'dblib':
            $dsn = sprintf("%s:host=%s;dbname=%s;charset=%s",
               $this->driver,
               $this->host,
               $this->database,
               $this->charset);
            break;
         case 'cubrid':
            $dsn = sprintf("cubrid:host=%s;port=%u;dbname=%s",
               $this->host,
               $this->port,
               $this->database);
            break;
         case '4D':
            $dsn = sprintf('4D:host=%s;port=%u;user=%s;password=%s;dbname=%s;charset=%s',
               $this->host,
               $this->port,
               $this->username,
               $this->password,
               $this->database,
               $this->charset);
            break;
      }

      return $dsn;
   }

   public function connect(): PDO|array {
      $dsn = $this->getDsn();
      if (!empty($this->username)) {
         $this->dbh = new PDO($dsn, $this->username, $this->password, $this->options);
         // echo ("Conectado a la base de datos {$this->database} en el servidor {$this->host}" . PHP_EOL);
      } else {
         $this->dbh = new PDO($dsn, null, null, $this->options);
      }
      if (!$this->dbh) {
         $errInfo = $this->dbh->errorInfo();
         return $errInfo;
      }
      return $this->dbh;

   }


   public function disconnect(): self {
      $this->dbh = null;
      return $this;
   }

   public function reconnect(): self {
      $this->disconnect();
      $this->connect();

      return $this;
   }
}