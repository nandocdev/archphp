<?php
/**
 * @package     core/Model
 * @subpackage  Statments
 * @file        Query
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-06-09 15:09:48
 * @version     1.0.0
 * @description
 */

declare(strict_types=1);

namespace Arch\Core\Model\Statments;

use Arch\Core\Model\Common\Connection;

class Query extends Connection {
   protected $debug = false;
   private \PDOStatement $sth;
   public function __construct(bool $debug = false, string $config = 'default') {
      parent::__construct($config);
      $this->connect();
      $this->debug = $debug;
   }

   public function excecute(string $sql, array $params = []): array {
      $sth = $this->dbh->prepare($sql);
      if ($sth->execute($params)) {
         return $sth->fetchAll(\PDO::FETCH_ASSOC);
      } else {
         return $sth->errorInfo()[2];
      }
   }
   protected function dump() {
      if ($this->debug) {
         echo '<pre>';
         $this->sth->debugDumpParams();
         echo '</pre>';
      }
   }
}