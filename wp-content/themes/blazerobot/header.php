<?

/**
 * Header (Cabeçalho do site)
 */

if (!defined('WPINC')) {
  header('Location: /');
  exit;
} ?>

<!DOCTYPE html>

<html lang="pt-br" dir="ltr">

<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# website: http://ogp.me/ns/website#">
  <? inc('partials/head') ?>
</head>

<body <? body_class() ?> data-baseurl="<?= site_url() ?>">

  <header class="s-header">
    <div class="container">

    </div>
  </header>

  <div>