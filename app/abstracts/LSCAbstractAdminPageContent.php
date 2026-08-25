<?php
/**
 * Abstract for registering admin page content
 * 
 * @version 1.0.0
 */

namespace lsc\blocks\app\abstracts;

if( ! defined('ABSPATH')) {
  exit;
}

abstract class LSCAbstractAdminPageContent {
  abstract public function render(): void;
}