<?php
/**
 * @package     dev/core
 * @subpackage  Model
 * @file        ORMd
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-06-09 15:01:24
 * @version     1.0.0
 * @description
 */

declare(strict_types=1);

namespace Arch\Core\Model;

use Arch\Core\Model\Statments\Create;
use Arch\Core\Model\Statments\Read;
use Arch\Core\Model\Statments\Update;
use Arch\Core\Model\Statments\Delete;
use Arch\Core\Model\Statments\Query;

class ORMd {
   private bool $debug = false;
   private string $config = 'default';
   public function __construct(string $config = 'default') {
      $this->config = $config;
   }

   public function debug(bool $debug = false): self {
      $this->debug = $debug;
      return $this;
   }

   public function create(): Create {
      return new Create($this->debug, $this->config);
   }

   public function read(): Read {
      return new Read($this->debug, $this->config);
   }

   public function update(): Update {
      return new Update($this->debug, $this->config);
   }

   public function delete(): Delete {
      return new Delete($this->debug, $this->config);
   }

   public function query(): Query {
      return new Query($this->debug, $this->config);
   }
}
