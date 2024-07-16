<?php
/**
 * @package     core/HexaORM
 * @subpackage  Helpers
 * @file        Pagination
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-07-14 12:47:46
 * @version     1.0.0
 * @description
 */

declare(strict_types=1);

namespace Arch\Core\HexaORM\Helpers;

use Arch\Core\HexaORM\Base\Database;
use PDOStatement;

class Pagination extends Database {
   private string $queryString;
   private array $params;
   private PDOStatement $statement;
   private int $page;
   private int $limit;
   private int $totalPages;
   private int $totalRecords;
   private string $config;

   public function __construct(string $query, array $params, string $config = 'default') {
      parent::__construct($config);
      $this->queryString = $query;
      $this->params = $params;
   }

   // confierte la consulta en una consulta de conteo
   private function countQuery(): string {
      $query = strtoupper($this->queryString);
      $query = explode(' ', $query);
      $select = array_search('SELECT', $query);
      $from = array_search('FROM', $query);
      $limit = array_search('LIMIT', $query);
      $query[$select] = 'SELECT COUNT(*)';
      $query = array_slice($query, $select, $from - $select);
      $query = implode(' ', $query);
      return $query;
   }

   // ejecuta la consulta de conteo, obtiene la cantidad de registros y retorna la consulta
   public function count(): PDOStatement|bool|string {
      $this->statement = $this->dbh->prepare($this->queryString);
      try {
         $this->statement->execute($this->params);
         $this->dbh->commit();
         return $this->statement;
      } catch (\PDOException $e) {
         return $e->getMessage();
      }
   }

   // ====================================================================================================
   // Paginación, definimos metodos que van a calcular el total de paginas y el offset
   // ====================================================================================================
   public function paginate(int $page, int $limit): self {
      $offset = ($page - 1) * $limit;
      $this->queryString .= " LIMIT $limit OFFSET $offset";
      return $this;
   }

   /**
    * SELECT
    * [ALL | DISTINCT | DISTINCTROW ]
    * [HIGH_PRIORITY]
    * [STRAIGHT_JOIN]
    * [SQL_SMALL_RESULT] [SQL_BIG_RESULT] [SQL_BUFFER_RESULT]
    * [SQL_NO_CACHE] [SQL_CALC_FOUND_ROWS]
    * select_expr [, select_expr] ...
    * [into_option]
    * [FROM table_references
    *   [PARTITION partition_list]]
    * [WHERE where_condition]
    * [GROUP BY {col_name | expr | position}, ... [WITH ROLLUP]]
    * [HAVING where_condition]
    * [WINDOW window_name AS (window_spec)
    *     [, window_name AS (window_spec)] ...]
    * [ORDER BY {col_name | expr | position}
    *   [ASC | DESC], ... [WITH ROLLUP]]
    * [LIMIT {[offset,] row_count | row_count OFFSET offset}]
    * [into_option]
    * [FOR {UPDATE | SHARE}
    *     [OF tbl_name [, tbl_name] ...]
    *     [NOWAIT | SKIP LOCKED]
    *   | LOCK IN SHARE MODE]
    * [into_option]
    */

   // lista ordenadamente 



}

/****************************** */
// ===============================================================================================================
/****************************** */

/**
 * @package     core/HexaORM
 * @subpackage  Statments
 * @file        Read
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-07-11 08:37:28
 * @version     1.0.0
 * @description
 */

declare(strict_types=1);

namespace Arch\Core\HexaORM\Statments;

use Arch\Core\HexaORM\Base\Database;
use PDOStatement;
use Arch\Core\HexaORM\Helpers\Pagination;

class Read extends Database {
   private string $queryString;
   private array $params;
   private PDOStatement $statement;
   private bool $debug;
   private string $config;
   private array $statmentOrder;
   private array $parsedQuery;

   function __construct(string $config = 'default') {
      $this->config = $config;
      parent::__construct($this->config);
      $this->debug = false;
      $this->params = [];
      $this->statmentOrder = [
         'SELECT',
         'FROM',
         'INNER',
         'LEFT',
         'RIGHT',
         'FULL',
         'WHERE',
         'GROUP BY',
         'HAVING',
         'ORDER BY',
         'LIMIT',
         'OFFSET'
      ];
   }

   public function setQuery(string $query): self {
      $this->queryString = $query;
      return $this;
   }

   // @method parseQuery: basado en el orden de las clausulas de SQL, separa la consulta en partes
   // @param void
   // @return array
   private function parseQuery(): array {
      $query = strtoupper($this->queryString);
      $query = explode(' ', $query);
      $parsedQuery = [];
      $index = 0;
      foreach ($query as $key => $value) {
         if (in_array($value, $this->statmentOrder)) {
            $index = array_search($value, $this->statmentOrder);
            $parsedQuery[$index] = $value;
         } else {
            $parsedQuery[$index] .= ' ' . $value;
         }
      }
      ksort($parsedQuery);
      return $parsedQuery;
   }



   public function setParams(string $key, mixed $value): self {
      $this->params[$key] = $value;
      return $this;
   }


   public function paginate(int $page, int $limit): self {
      $pagination = new Pagination($this->queryString, $this->params, $page, $limit, $this->config);
      $pageData = $pagination->paginate();
      return $this;
   }

   private function addLimitOffsetParams(int $limit, int $offset): void {
      $this->params['limit'] = $limit;
      $this->params['offset'] = $offset;
   }

   private function addLimitOfsfsetQuery(): void {
      $query = explode(' ', $this->queryString);
      $select = array_search('SELECT', $query);
      $from = array_search('FROM', $query);

   }

   // private function addLimitOffset(): string {
   //    $query = strtoupper($this->queryString);
   //    $query = explode(' ', $query);
   //    $select = array_search('SELECT', $query);
   //    $from = array_search('FROM', $query);
   //    $limit = array_search('LIMIT', $query);
   //    $query = array_slice($query, $select, $from - $select);
   //    $query = implode(' ', $query);
   //    return $query;
   // }

   public function execute(): PDOStatement|bool|string {
      $this->statement = $this->dbh->prepare($this->queryString);
      try {
         $this->statement->execute($this->params);
         $this->dbh->commit();
         return $this->statement;
      } catch (\PDOException $e) {
         if ($this->debug) {
            return $e->getMessage();
         }
         return false;
      }
   }

   public function select(string $sql, array $params = []): array {
      $this->sth = $this->dbh->prepare($sql);
      if ($this->sth->execute($params)) {
         return $this->sth->fetchAll(\PDO::FETCH_ASSOC);
      } else {
         // new DatabaseException($this->sth->errorInfo()[2]);
         return $this->sth->errorInfo()[2];
      }
   }
}