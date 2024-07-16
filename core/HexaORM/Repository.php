<?php
/**
 * @package     dnasa/core
 * @subpackage  HexaORM
 * @file        Repository
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-07-11 08:36:46
 * @version     1.0.0
 * @description
 */

declare(strict_types=1);

namespace Arch\Core\HexaORM;

use Arch\Core\HexaORM\Statments\Read;
use Arch\Core\HexaORM\Statments\Create;
use Arch\Core\HexaORM\Statments\Modify;
use Arch\Core\HexaORM\Statments\Remove;

class Repository {
   // define metodos del crud: create, read, update, delete

   protected string $config;
   public function __construct(string $config = 'default') {
      $this->config = $config;
   }

   public function create(): Create {
      return new Create($this->config);
   }

   public function read(): Read {
      return new Read($this->config);
   }

   public function modify(): Modify {
      return new Modify($this->config);
   }

   public function remove(): Remove {
      return new Remove($this->config);
   }

}