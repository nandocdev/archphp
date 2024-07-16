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

// recibe una consulta parseada y la convierte en una consulta de conteo
class Pagination extends Database {
   public int $page;
   public int $limit;
   public int $offset;
   public int $records;
   public int $pages;
   private array $arrQuery;
   private string $strQuery;
   private array $params;
   private array $result;
   const ORDER = [
      'SELECT', 'FROM', 'INNER', 'LEFT', 'RIGHT', 'FULL', 'WHERE', 'GROUP BY', 'HAVING', 'ORDER BY', 'LIMIT', 'OFFSET'
   ];

   public function __construct(string $config = 'default') {
      parent::__construct($config);
      $this->page = 1;
      $this->limit = 10;
      $this->offset = 0;
      $this->records = 0;
      $this->pages = 0;
      $this->arrQuery = [];
      $this->params = [];
      $this->result = [];
   }

   // recibe la consulta sql definida por el usuario
   public function setQuery(array $query): self {
      $this->arrQuery = $query;
      $this->arrQuery['SELECT'] = 'SELECT COUNT(*) AS total ';
      $this->strQuery = $this->buildQuery();
      return $this;
   }
   // toma el arreglo de consultas y las une en una sola consulta
   private function buildQuery(): string {
      $this->strQuery = '';
      foreach (self::ORDER as $key) {
         if (isset($this->arrQuery[$key])) {
            $this->strQuery .= $this->arrQuery[$key] . ' ';
         }
      }
      return rtrim($this->strQuery);
   }

   // recibe los parametros de la consulta sql
   public function setParams(array $params): self {
      $this->params = $params;
      return $this;
   }

   // ejecuta la consulta de conteo
   public function count(): self {
      $stmt = $this->dbh->prepare($this->strQuery);
      try {
         $stmt->execute($this->params);
         $this->records = $stmt->fetchColumn();
         return $this;
      } catch (\Throwable $th) {
         $this->records = 0;
         return $this;
      }
   }

   // calcula el numero de paginas
   public function pages(): self {
      $this->pages = intval(ceil($this->records / $this->limit)) + 1;
      return $this;
   }

   // calcula el offset
   public function offset(): self {
      $this->offset = ($this->page - 1) * $this->limit;
      return $this;
   }

   // define la pagina actual
   public function setPage(int $page): self {
      $this->page = $page;
      return $this;
   }

   // define el limite de registros por pagina
   public function setLimit(int $limit): self {
      $this->limit = $limit;
      return $this;
   }

   // retorna los datos de la paginacion
   public function getPagination(): array {
      return [
         'page' => $this->page,
         'limit' => $this->limit,
         'offset' => $this->offset,
         'records' => $this->records,
         'pages' => $this->pages
      ];
   }

}