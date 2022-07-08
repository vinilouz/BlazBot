<?php

class CTR_Blaze
{
  /**
   * Construtor
   */
  public function __construct()
  {
    /* Create Button on user profile */
    add_action('admin_enqueue_scripts', [$this, 'create_button_login_blaze']);

    /* Login on blaze */
    add_action('wp_ajax_nopriv_login_blaze', [$this, 'login_blaze']);
    add_action('wp_ajax_login_blaze', [$this, 'login_blaze']);
  }

  function create_button_login_blaze($hook)
  {
    if ('profile.php' !== $hook) return;
    wp_enqueue_script('user_blaze_script', theme_url('admin/public/js/user.js'), ['jquery'], date('his'));
    wp_enqueue_style('user_blaze_style', theme_url('admin/public/css/user.css'), false, date('his'));
  }

  function login_blaze()
  {
    $data = $_POST;

    if (empty($data['user']) || empty($data['pass']))
      return wp_send_json_error(['msg' => __('Preencha o email e senha', 'blazerobot')]);

    // Validate credentials
    $fields = ['username' => $data['user'], 'password' => $data['pass']];
    $url = 'https://blaze.com/api/auth/password';
    $curl = $this->blaze($url, 'PUT', $fields);
    @$token = $curl->access_token;

    // on invalid
    if (!$token) return wp_send_json_error(['msg' => __('Dados incorretos ou conta inexistente.', 'blazerobot')]);

    $wallet_id = $this->wallet($token)->id;
    if (!$wallet_id) return wp_send_json_error(['msg' => __('Erro ao obter dados', 'blazerobot')]);

    // On success
    $uid = get_current_user_id();
    $blaze = [
      'email'     => $data['user'],
      'password'  => $data['pass'],
      'token'     => $token,
      'wallet_id' => $wallet_id
    ];
    update_field('blaze', $blaze, "user_$uid");

    return wp_send_json_success([
      'token' => $token,
      'wallet_id' => $wallet_id,
      'msg' => __('Credenciais corretas.', 'blazerobot')
    ]);
  }

  public static function make_bet($signal)
  {
  }

  // https://api-v2.blaze.com/roulette_games/recent
  // 0 - Branco
  // 1 - Vermelho
  // 2 - Preto

  // 
  function blaze($url, $method, $fields)
  {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    $data_string = json_encode($fields);
    $headers = ['Content-Type: application/json'];
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
    curl_setopt($curl, CURLINFO_HEADER_OUT, true);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //IMP if the url has https and you don't want to verify source certificate
    $curl_response = curl_exec($curl);
    $response = json_decode($curl_response);
    curl_close($curl);
    return $response;
  }

  function wallet($token)
  {
    $curl = curl_init('https://blaze.com/api/wallets');
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    $headers = [
      'Authorization: Bearer ' . $token,
      'Content-Type: application/json'
    ];
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
    curl_setopt($curl, CURLINFO_HEADER_OUT, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //IMP if the url has https and you don't want to verify source certificate
    $curl_response = curl_exec($curl);
    $response = json_decode($curl_response);
    curl_close($curl);
    return $response[0];
  }
}

new CTR_Blaze();
