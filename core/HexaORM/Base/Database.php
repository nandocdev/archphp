<?php
/**
 * @package     core/HexaORM
 * @subpackage  Base
 * @file        Database
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-07-11 08:38:28
 * @version     1.0.0
 * @description
 */

declare(strict_types=1);

namespace Arch\Core\HexaORM\Base;

use PDO;
use PDOException;
use PDOStatement;

abstract class Database {
   private string $driver;
   private string $host;
   private string $user;
   private string $pass;
   private string $database;
   private string $charset;
   private string $port;
   private string $prefix;
   private array $options;
   private string $dsn;
   protected PDO|null $dbh;
   protected PDOException $error;


   // constructor de la clase, inicializa los parametros de conexion
   public function __construct(string $config = 'default') {
      $db = DB[$config];
      $this->driver = $db['driver'];
      $this->host = $db['host'];
      $this->user = $db['user'];
      $this->pass = $db['pass'];
      $this->database = $db['database'];
      $this->charset = $db['charset'];
      $this->port = $db['port'];
      $this->prefix = $db['prefix'];
      $this->options = $db['options'];
      $this->dsn = $this->driver . ':host=' . $this->host . ';dbname=' . $this->database . ';charset=' . $this->charset;
      $this->connect();
   }

   // metodo abstracto para conectar a la base de datos
   private function connect(): PDO|PDOException {
      try {
         $this->dbh = new PDO($this->dsn, $this->user, $this->pass, $this->options);
         return $this->dbh;
      } catch (PDOException $e) {
         $this->error = $e;
         return $this->error;
      }
   }

   // metodo abstracto para desconectar de la base de datos
   private function disconnect(): void {
      $this->dbh = null;
   }

   public function __destruct() {
      $this->disconnect();
   }

}