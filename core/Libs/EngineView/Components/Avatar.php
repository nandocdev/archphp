<?php
/**
 * @package     Libs/EngineView
 * @subpackage  Components
 * @file        Avatar
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-06-09 12:28:41
 * @version     1.0.0
 * @description
 */

declare(strict_types=1);

namespace Arch\Core\Libs\EngineView\Components;

class Avatar {
   private string $text;
   private bool $round;
   private int $size;
   private string $bgColor;
   private string $fgColor;
   private string $font;
   private int $fontSize;
   private string $fontWeight;

   public function __construct(string $name, string $surname) {
      $this->setText($name, $surname);
      $this->round = true;
      $this->size = 100;
      $this->bgColor = '#ffffff';
      $this->fgColor = '#000000';
      $this->font = "Arial, sans-serif";
      $this->fontSize = 40;
      $this->fontWeight = 'bold';
   }

   public function setText(string $name, string $surname): self {
      $this->validateName($name);
      $this->validateName($surname);
      $this->text = strtoupper(substr($name, 0, 1) . substr($surname, 0, 1));
      return $this;
   }

   public function setRound(bool $round): self {
      $this->round = $round;
      return $this;
   }

   public function setSize(int $size): self {
      $this->validatePositiveInteger($size, 'El tamaño debe ser un entero positivo');
      $this->size = $size;
      return $this;
   }

   public function setBackgroundColor(string $bgColor): self {
      $this->validateHexColor($bgColor, 'El color de fondo debe estar en formato hexadecimal (#RRGGBB)');
      $this->bgColor = $bgColor;
      return $this;
   }

   public function setForegroundColor(string $fgColor): self {
      $this->validateHexColor($fgColor, 'El color de primer plano debe estar en formato hexadecimal (#RRGGBB)');
      $this->fgColor = $fgColor;
      return $this;
   }

   public function setFont(string $font): self {
      $this->font = $font;
      return $this;
   }

   public function setFontSize(int $fontSize): self {
      $this->validatePositiveInteger($fontSize, 'El tamaño de la fuente debe ser un entero positivo');
      $this->fontSize = $fontSize;
      return $this;
   }

   public function setFontWeight(string $fontWeight): self {
      if (!in_array($fontWeight, ['normal', 'bold'])) {
         throw new \InvalidArgumentException('El peso de la fuente debe ser normal o negrita');
      }
      $this->fontWeight = $fontWeight;
      return $this;
   }

   public function generate(): string {
      $svg = $this->generateSvgHeader();
      $svg .= $this->round ? $this->generateCircle() : $this->generateRect();
      $svg .= $this->generateText();
      $svg .= "</svg>";

      return $svg;
   }

   private function validateName(string $name): void {
      if (!preg_match('/^[a-zA-Z]+$/', $name)) {
         throw new \InvalidArgumentException('Nombre y apellido deben ser cadenas de texto que contengan solo letras');
      }
   }

   private function validateHexColor(string $color, string $message): void {
      if (!preg_match('/^#[a-f0-9]{6}$/i', $color)) {
         throw new \InvalidArgumentException($message);
      }
   }

   private function validatePositiveInteger(int $value, string $message): void {
      if ($value <= 0) {
         throw new \InvalidArgumentException($message);
      }
   }

   private function generateSvgHeader(): string {
      return "<svg xmlns='http://www.w3.org/2000/svg' width='{$this->size}px' height='{$this->size}px' viewBox='0 0 {$this->size} {$this->size}' version='1.1'>";
   }

   private function generateCircle(): string {
      return "<circle fill='{$this->bgColor}' cx='" . ($this->size / 2) . "' cy='" . ($this->size / 2) . "' r='" . ($this->size / 2) . "' />";
   }

   private function generateRect(): string {
      return "<rect fill='{$this->bgColor}' width='{$this->size}' height='{$this->size}' />";
   }

   private function generateText(): string {
      return "<text x='" . ($this->size / 2) . "' y='" . ($this->size / 2) . "' fill='{$this->fgColor}' font-family='{$this->font}' font-size='{$this->fontSize}' font-weight='{$this->fontWeight}' text-anchor='middle' alignment-baseline='central'>{$this->text}</text>";
   }
}