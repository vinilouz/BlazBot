<?php
if (!defined('WPINC')) {
    header('Location: /');
    exit;
}

// --------------------------------

if (!defined('CPTS_DIR')) {
    define('CPTS_DIR', FUNCTIONS_DIR . '/custom-post-types');
}

//Carrega todos os arquivos no diretório
foreach (glob(CPTS_DIR . "/*.php") as $arquivo) {
    require_once  $arquivo;
}
