<?php

/**
 * Template default para páginas sem template
 *
 */

if (!defined('WPINC')) {
    header('Location: /');
    exit;
}

get_header();

the_content();

get_footer();
