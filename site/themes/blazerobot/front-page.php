<?php
if (!defined('WPINC')) {
  header('Location: /');
  exit;
}

if (!is_user_logged_in()) {
  wp_redirect(wp_login_url());
} else {
  $panel_link = get_permalink(get_page_by_template('panel'));
  wp_redirect($panel_link);
}

exit;
?>


<main class="main-home">
  <?php $list = get_field('signals_list', 'option');
  if ($list) : ?>
    <section class="s-hero-home">
      <div class="row">

        <div class="wrapper">
          <h1>strategy_1</h1><br>
          <?php // do_shortcode('[strategy_1]')
          ?>
        </div>

        <br>

        <div class="wrapper">
          <h1>strategy_2</h1><br>
          <?php // do_shortcode('[strategy_2]') 
          ?>
        </div>

      </div>
    </section>
  <?php endif ?>
</main>

<br><br><br>
<?php
/**
 * Teste entrada
 */
// $turn = 0;
// $signal['color'] = 'VERMELHO';
// CTR_Blaze::make_double_bet(1, $signal['color'], $turn);

// $turn = 0;
// CTR_Blaze::make_crash_bet(1, $turn);

// $list = get_field('signals_list', 'option');

// $new = array_filter($list, function ($var) {
//   return ($var['id'] == '1785180053' && $var['result'] == '');
// });
// end($new)
?>
<br><br><br>

<?php get_footer() ?>