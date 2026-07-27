<?php
/**
 * Abstract class for registering controllers
 * 
 * @version 1.0.0
 */

namespace lsc\blocks\app\abstracts;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

abstract class LSCAbstractController {
 
  function __construct() {
     add_action('rest_api_init', [$this, 'register']);
  }
}