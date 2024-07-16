<?php
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
   private string $config;
   private string $strQuery;
   private array $arrQuery;
   private array $params;
   private array $result;
   const ORDER = [
      'SELECT', 'FROM', 'INNER', 'LEFT', 'RIGHT', 'FULL', 'WHERE', 'GROUP BY', 'HAVING', 'ORDER BY', 'LIMIT', 'OFFSET'
   ];

   function __construct(string $config = 'default') {
      $this->config = $config;
      parent::__construct($this->config);
      $this->strQuery = '';
      $this->arrQuery = [];
      $this->params = [];
   }

   // recibe la consulta sql definida por el usuario
   public function setQuery(string $query): self {
      $this->strQuery = $query;
      $this->parseQuery();
      return $this;
   }

   // recibe los parametros de la consulta sql
   public function setParams(array $params): self {
      foreach ($params as $key => $value) {
         $this->params[$key] = $value;
      }
      return $this;
   }

   // separa la consulta definida por el usuario en partes
   private function parseQuery(): void {
      $this->arrQuery = [];
      $query = explode(' ', $this->strQuery);
      $key = ''; // Inicializar como cadena vacía
      foreach ($query as $value) {
         $upperValue = strtoupper($value);
         if (in_array($upperValue, self::ORDER)) {
            $key = $upperValue;
            if (!isset($this->arrQuery[$key])) {
               $this->arrQuery[$key] = "$key ";
            }
         } else {
            if ($key === '') {
               // Manejo del caso donde no hay clave inicial
               continue;
            }
            $this->arrQuery[$key] .= "$value ";
         }
      }

      // Eliminar espacios adicionales al final de cada valor en el array
      foreach ($this->arrQuery as $k => $v) {
         $this->arrQuery[$k] = rtrim($v);
      }
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


   // retorna una instancia de la clase Pagination para definir los datos de la paginación
   public function paginate(int $page, int $limit): self {
      $paginate = new Pagination($this->config);
      $result = $paginate->setQuery($this->arrQuery)
         ->setParams($this->params)
         ->setPage($page)
         ->setLimit($limit)
         ->count()
         ->pages()
         ->offset()
         ->getPagination();
      $this->arrQuery['LIMIT'] = 'LIMIT :limit';
      $this->arrQuery['OFFSET'] = 'OFFSET :offset';
      $this->params['limit'] = $result['limit'];
      $this->params['offset'] = $result['offset'];
      $this->result['page'] = $result['page'];
      $this->result['records'] = $result['records'];
      $this->result['pages'] = $result['pages'];
      return $this;
   }

   // ejecuta la consulta sql, retorna un arreglo con los resultados y datos de paginacion
   public function execute(): array {
      $stmt = $this->dbh->prepare($this->buildQuery());
      try {
         $stmt->execute($this->params);
         $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
         $this->result['data'] = $result;
         return $this->result;
      } catch (\PDOException $e) {
         return [
            'error' => $e->getMessage(),
         ];
      }
   }
}