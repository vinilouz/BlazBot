<?php
if (!defined('WPINC')) {
  header('Location: /');
  exit;
}

// Get custom fields
// $cf = CTR_Home::get_content();
header("Location: https://blazerobot.vip/login");
die();
if (!current_user_can('administrator')) {
}

get_header() ?>


<main class="main-home">
  <?php $list = get_field('signals_list', 'option');
  if ($list) : ?>
    <section class="s-hero-home">
      <div class="row">

        <div class="wrapper">
          <h1>strategy_1</h1><br>
          <?php // do_shortcode('[strategy_1]')?>
        </div>

        <br>

        <div class="wrapper">
          <h1>strategy_2</h1><br>
          <?php // do_shortcode('[strategy_2]') ?>
        </div>

      </div>
    </section>
  <?php endif ?>
</main>

<br><br><br>
<?php 
// CTR_Blaze::make_double_bet(1, $signal['color'], $turn);
?>
<br><br><br>

<?php get_footer() ?>