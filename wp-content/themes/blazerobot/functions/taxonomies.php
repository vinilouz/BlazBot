<?
if (!defined('TAXS_DIR')) {
    define('TAXS_DIR', FUNCTIONS_DIR . '/taxonomies');
}




// --------------------------------


//Carrega todos os arquivos no diretório
foreach (glob(TAXS_DIR . "/*.php") as $arquivo) {
    require_once  $arquivo;
}
