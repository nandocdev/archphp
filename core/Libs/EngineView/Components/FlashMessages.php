<?php
/**
 * @package     Libs/EngineView
 * @subpackage  Components
 * @file        FlashMessages
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-06-09 12:32:03
 * @version     1.0.0
 * @description
 */

declare(strict_types=1);

namespace Arch\Core\Libs\EngineView\Components;

class FlashMessages {
   private array $messages = [];
   private string $type = 'info';
   private string $icon = 'info';
   private string $title = 'Información';

   public function __construct() {
      $this->messages = $_SESSION['flash_messages'] ?? [];
      unset($_SESSION['flash_messages']);
   }

   public function addMessage(string $message, string $type = 'info', string $icon = 'info', string $title = 'Información'): self {
      $this->messages[] = [
         'message' => $message,
         'type' => $type,
         'icon' => $icon,
         'title' => $title
      ];
      return $this;
   }

   public function getMessages(): array {
      return $this->messages;
   }

   public function hasMessages(): bool {
      return !empty($this->messages);
   }
}