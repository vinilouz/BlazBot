<?php

class CTR_Blaze
{

  // https://api-v2.blaze.com/roulette_games/recent
  // 0 - Branco
  // 1 - Vermelho
  // 2 - Preto

  /**
   * Construtor
   */
  function __construct()
  {
    /* Create Button on user profile */
    add_action('admin_enqueue_scripts', [$this, 'create_button_login_blaze']);

    /* Login on blaze */
    add_action('wp_ajax_nopriv_login_blaze', [$this, 'login_blaze']);
    add_action('wp_ajax_login_blaze', [$this, 'login_blaze']);
  }

  function create_button_login_blaze($hook)
  {
    // if ('profile.php' !== $hook) return;
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
    $curl = self::blaze($url, 'PUT', $fields);
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

  public static function trigger_bets($signal, $turn = 0)
  {
    $results = [];

    $users_args = ['meta_query' => [[
      'relation' => 'AND',
      [
        'key' => 'status',
        'value' => 1,
        'compare' => "=",
        'type' => 'numeric'
      ],
      [
        'key' => 'subscription',
        'value' => 1,
        'compare' => "=",
        'type' => 'numeric'
      ],
      [
        'key' => 'blaze_token',
        'value' => '',
        'compare' => "!=",
      ],
    ]]];

    $users_list =  get_users($users_args);
    foreach ($users_list as $k => $current_user) {
      $results[$k]['user_id'] = $current_user->ID;
      $gale = get_field('gales', "user_$current_user->ID") ?: 0;
      $s_win  = get_field('stop', "user_$current_user->ID") ? get_field('stop', "user_$current_user->ID")['win'] : 0;
      $s_loss = get_field('stop', "user_$current_user->ID") ? get_field('stop', "user_$current_user->ID")['loss'] : 0;
      $token   =  get_field('blaze', "user_$current_user->ID")['token'];
      $balance = self::wallet($token)->balance;

      if ($turn > $gale) continue;

      // Validate user
      if (
        ($token != '') &&
        ($s_loss == 0 || ($s_loss != 0 && $s_loss < $balance)) &&
        ($s_win == 0 || ($s_win != 0 && $s_win > $balance))
      ) {
        $results[$k]['bet'] = self::make_bet($current_user->ID, $signal['color'], $turn);
      }

      // Turn bot off on reach stop
      // if ($turn == $gale) {
      //   $balance = self::wallet($token)->balance;
      //   if ($s_loss >= $balance || $s_win <= $balance)
      //     update_field('status', 0, "user_$current_user->ID");
      // }
    }

    return $results;
  }

  public static function make_bet($user_id, $signal_color, $gale)
  {
    $result = null;

    $color = $signal_color == 'VERMELHO' ? 1 : 2;
    $token     = get_field('blaze', "user_$user_id")['token'];
    $wallet_id = get_field('blaze', "user_$user_id")['wallet_id'];
    switch ($gale) {
      case 1:
        $bet_value = get_field('bet_2', "user_$user_id")['color'];
        $bet_white = get_field('bet_2', "user_$user_id")['white'];
        break;
      case 2:
        $bet_value = get_field('bet_3', "user_$user_id")['color'];
        $bet_white = get_field('bet_3', "user_$user_id")['white'];
        break;
      default:
        $bet_value = get_field('bet_1', "user_$user_id")['color'];
        $bet_white = get_field('bet_1', "user_$user_id")['white'];
        break;
    }

    /** ========================== ENTRADA BLAZE */
    if ($bet_value >= 1.8) {
      $success = false;
      while (!$success) {
        $fields = array(
          'amount' => $bet_value,
          'color' => $color,
          'currency_type' => 'BRL',
          'free_bet' => false,
          'wallet_id' => $wallet_id
        );
        $url = 'https://blaze.com/api/roulette_bets';
        $curl = self::blaze($url, 'POST', $fields, $token);

        if (@$curl->id)
          $success = true;
        $result['color'] = true;
      }
    }

    if ($bet_white >= 1.8) {
      $success = false;
      while (!$success) {
        $fields = array(
          'amount' => $bet_white,
          'color' => 0,
          'currency_type' => 'BRL',
          'free_bet' => false,
          'wallet_id' => $wallet_id
        );
        $url = 'https://blaze.com/api/roulette_bets';
        $curl = self::blaze($url, 'POST', $fields, $token);

        if (@$curl->id)
          $success = true;
        $result['white'] = true;
      }
    }
    /** ========================== FIM ENTRADA BLAZE */

    return $result;
  }

  public static function blaze($url, $method, $fields, $token = null)
  {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    $data_string = json_encode($fields);
    if ($token) {
      $headers = [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json'
      ];
    } else {
      $headers = ['Content-Type: application/json'];
    }
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

  static function wallet($token)
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
