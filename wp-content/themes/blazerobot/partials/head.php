<?php

/**
 * Head - Tudo dentro da tag <head>
 */

if (!defined('WPINC')) {
  header('Location: /');
  exit;
}
?>
<!-- Meta X-UA-Compatible -->
<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" /><![endif]-->

<!-- Charset -->
<meta charset="utf-8" />

<!-- Viewport -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<meta name="theme-color" content="#ffffff">


<?php wp_head() ?>