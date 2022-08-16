<?php
if (!defined('WPINC')) {
  header('Location: /');
  exit;
}

// Get custom fields
// $cf = CTR_Home::get_content();
if (!current_user_can('administrator')) {
  header("Location: https://blazerobot.vip/login");
  die();
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
/**
 * Teste entrada
 */
// $turn = 0;
// $signal['color'] = 'VERMELHO';
// CTR_Blaze::make_double_bet(1, $signal['color'], $turn);


/**
 * Teste login
 */
// localStorage.getItem('ACCESS_TOKEN');
// localStorage.getItem('selected_wallet_v2');
// $ctr_blaze = new CTR_Blaze();
// $ctr_blaze->login_blaze()


$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => "https://blaze.com/api/auth/password",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "PUT",
  CURLOPT_POSTFIELDS => "{\n    \"vinilouz@gmail.com\": \"\",\n    \"Senha123\": \"\"\n}",
  CURLOPT_HTTPHEADER => [
    "accept: application/json, text/plain, */*",
    "accept-encoding: gzip, deflate, br",
    "accept-language: pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7",
    "content-type: application/json;charset=UTF-8",
    "origin: https://blaze.com",
    "referer: https://blaze.com/pt/?modal=auth&tab=login",
    "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.102 Safari/537.36",
    "x-captcha-response: undefined",
    "x-client-language: pt",
    "x-client-version: c9d9c023"
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  pre("cURL Error #:" . $err);
} else {
  pre($response);
}
?>
<br><br><br>

<?php get_footer() ?>