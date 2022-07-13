<?php

if (!defined('WPINC')) {
  header('Location: /');
  exit;
}

/*--------------------------------------------------------------------------------------
 *
 * Includes das funções do site
 *
 *-------------------------------------------------------------------------------------*/
if (!defined('FUNCTIONS_DIR')) {
  define('FUNCTIONS_DIR', get_template_directory() . '/functions');
}

/**
 * Funções abstraídas para usar no WP
 */
require_once FUNCTIONS_DIR . '/abstract-functions.php';

/*--------------------------------------------------------------------------------------
 *
 * Arquivos do tema
 *
 *-------------------------------------------------------------------------------------*/

require_once FUNCTIONS_DIR . '/taxonomies.php';
require_once FUNCTIONS_DIR . '/custom-post-types.php';
require_once FUNCTIONS_DIR . '/controllers.php';
