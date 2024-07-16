<?php
/**
 * @package     core/HexaORM
 * @subpackage  Statments
 * @file        Modify
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-07-11 08:37:21
 * @version     1.0.0
 * @description
 */

declare(strict_types=1);

namespace Arch\Core\HexaORM\Statments;

use Arch\Core\HexaORM\Base\Database;
use PDOStatement;

class Modify extends Database {
   private string $queryString;
   private array $params;
   private PDOStatement $statement;
   private bool $debug;
   private array $result;

   function __construct(string $config = 'default') {
      parent::__construct($config);
      $this->debug = false;
      $this->result = [];
   }

   public function setQuery(string $query): self {
      $this->queryString = $query;
      return $this;
   }

   // evalúa si la consulta es válida para la operacion update
   private function isValidQuery(): bool {
      if (!preg_match('/^UPDATE\s+\w+\s+SET\s+(\w+\s*=\s*:\w+,\s*)*\w+\s*=\s*:\w+\s+WHERE\s+\w+\s*=\s*:\w+$/', $this->queryString)) {
         return false;
      }
      return true;
   }

   public function setParams(array $params): self {
      foreach ($params as $key => $value) {
         $this->params[$key] = $value;
      }
      return $this;
   }


   public function execute(): array|bool|string {
      $this->dbh->beginTransaction();
      $this->statement = $this->dbh->prepare($this->queryString);
      try {
         $this->statement->execute($this->params);
         $this->dbh->commit();
         $this->result['data'] = $this->statement->fetchAll();
         return $this->result;
      } catch (\PDOException $e) {
         $this->dbh->rollBack();
         if ($this->debug) {
            return $e->getMessage();
         }
         return false;
      }
   }
}