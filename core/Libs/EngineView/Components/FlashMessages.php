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
   private array $icons = [
      'info' => 'fa fa-circle-info me-1',
      'success' => 'fa fa-circle-check me-1',
      'warning' => 'fa fa-triangle-exclamation me-1',
      'danger' => 'fa fa-circle-xmark me-1',
   ];

   public function __construct() {
      $this->messages = $this->existSession() ? $_SESSION['flash_messages'] : [];
      unset($_SESSION['flash_messages']);
   }

   private function existSession(): bool {
      return isset($_SESSION['flash_messages']) && !empty($_SESSION['flash_messages']);
   }

   public function addMessage(string $message, string $type = 'info', string $title = 'Información'): self {
      $this->messages[] = [
         'message' => $message,
         'type' => $type,
         'icon' => $this->icons[$type] ?? $this->icons['info'],
         'title' => $title
      ];
      $_SESSION['flash_messages'] = $this->messages;
      return $this;
   }

   public function getMessages(): array {
      return $this->messages;
   }

   public function hasMessages(): bool {
      return !empty($this->messages);
   }
}