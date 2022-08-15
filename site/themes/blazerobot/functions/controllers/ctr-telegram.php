<?php

class CTR_Telegram
{

  /**
   * Construtor
   */
  public function __construct()
  {
    add_action('rest_api_init', [$this, 'create_routes']);
  }

  public function create_routes()
  {
    register_rest_route('blaze/v1', 'signals', [
      [
        'methods'  => WP_REST_Server::READABLE,
        'callback' => [$this, 'handler_get_signals'],
        'permission_callback' => '__return_true',
      ],
      [
        'methods'  => WP_REST_Server::CREATABLE,
        'callback' => [$this, 'handler_set_signals'],
        'permission_callback' => '__return_true',
      ],
    ]);
  }

  function handler_get_signals()
  {
    $response = get_field('signals_list', 'option');

    return rest_ensure_response($response);
  }

  /**
   * CONFIG FOR VIP DOUBLE/SEM GALE 🐔
   * @see id 
   */
  function handler_set_signals_session_nogale(WP_REST_Request $request)
  {
    $res = $request->get_body_params();

    $r = null;

    // BOT DOUBLE SEM GALE
    if (strpos($res['message'], "ENTRADA CONFIRMADA") !== false) {
      $re = '/(?<=MARCAR PRETO ).*/m';
      preg_match_all($re, $res['message'], $matches, PREG_SET_ORDER, 0);
      $color = $matches[0][0] == '🔴' ? 'VERMELHO' : 'PRETO';

      $date = new DateTime($res['date'], new DateTimeZone('UTC'));
      $date->setTimezone(new DateTimeZone('America/Sao_Paulo'));

      $signal = [
        'id'      => $res['id'],
        'title'   => $res['title'],
        'date'    => $date->format('d/m/Y g:i a'),
        'color'   => $color,
      ];

      // Run the signal on Blaze
      $r = CTR_Blaze::trigger_bets($signal);

      // Save Signal
      add_row('signals_list', $signal, 'option');
    } elseif (strpos($res['message'], "WIN") !== false) {
      // Update with win
      $list = get_field('signals_list', 'option');
      $last_signal = end($list);
      $last_signal['result'] = 'WIN';
      if (strpos($res['message'], "BRANCO") !== false) {
        $last_signal['white'] = true;
      }
      update_row('signals_list', count($list), $last_signal, 'option');
    } elseif (strpos($res['message'], "LOSS") !== false) {
      // Update with loss
      $list = get_field('signals_list', 'option');
      $last_signal = end($list);
      $last_signal['result'] = 'LOSS';
      update_row('signals_list', count($list), $last_signal, 'option');
    }

    return rest_ensure_response($r);
  }

  /**
   * CONFIG FOR BLAZE BOT DOUBLE SEM GALE
   * @see id 1577414274
   */
  // function handler_set_signals_no_gale(WP_REST_Request $request)
  // {
  //   $res = $request->get_body_params();

  //   $r = null;

  //   // BOT DOUBLE SEM GALE
  //   if (strpos($res['message'], "Apostar no") !== false) {
  //     $re = '/(?<=Apostar no ).*(?= após)/m';
  //     preg_match_all($re, $res['message'], $matches, PREG_SET_ORDER, 0);
  //     $color = $matches[0][0] == '🔴' ? 'VERMELHO' : 'PRETO';

  //     $date = new DateTime($res['date'], new DateTimeZone('UTC'));
  //     $date->setTimezone(new DateTimeZone('America/Sao_Paulo'));

  //     $signal = [
  //       'id'      => $res['id'],
  //       'title'   => $res['title'],
  //       'date'    => $date->format('d/m/Y g:i a'),
  //       'color'   => $color,
  //     ];

  //     // Run the signal on Blaze
  //     $r = CTR_Blaze::trigger_bets($signal);

  //     // Save Signal
  //     add_row('signals_list', $signal, 'option');
  //   } elseif (strpos($res['message'], "WIN") !== false) {
  //     // Update with win
  //     $list = get_field('signals_list', 'option');
  //     $last_signal = end($list);
  //     $last_signal['result'] = 'WIN';
  //     update_row('signals_list', count($list), $last_signal, 'option');
  //   } elseif (strpos($res['message'], "LOSS") !== false) {
  //     // Update with loss
  //     $list = get_field('signals_list', 'option');
  //     $last_signal = end($list);
  //     $last_signal['result'] = 'LOSS';
  //     update_row('signals_list', count($list), $last_signal, 'option');
  //   }

  //   return rest_ensure_response($r);
  // }

  /**
   * CONFIG FOR BLAZE TECH WITH GALES
   * @see id 1299783467
   */
  // function handler_set_signals_tech_gale(WP_REST_Request $request)
  // {
  //   $res = $request->get_body_params();

  //   $r = null;

  //   // Blaze Tech && Buzz
  //   // if ($res['id'] == 1299783467 || $res['id'] == 1785180053) {
  //   if (strpos($res['message'], "Oportunidade encontrada") !== false) {
  //     $re = '/(?<=Apostar em ).*(?= )/m';
  //     preg_match_all($re, $res['message'], $matches, PREG_SET_ORDER, 0);
  //     $color = $matches[0][0];

  //     $date = new DateTime($res['date'], new DateTimeZone('UTC'));
  //     $date->setTimezone(new DateTimeZone('America/Sao_Paulo'));

  //     $signal = [
  //       'id'      => $res['id'],
  //       'title'   => $res['title'],
  //       'date'    => $date->format('d/m/Y g:i a'),
  //       'color'   => $color,
  //     ];

  //     // Run the signal on Blaze
  //     $r = CTR_Blaze::trigger_bets($signal);

  //     // Save Signal
  //     add_row('signals_list', $signal, 'option');
  //   } elseif (strpos($res['message'], "🤞🏻 Façam a primeira proteção") !== false) {
  //     $list = get_field('signals_list', 'option');
  //     $last_signal = end($list);
  //     $last_signal['result'] = 'G1';

  //     $r = CTR_Blaze::trigger_bets($last_signal, 1);
  //     update_row('signals_list', count($list), $last_signal, 'option');

  //     $this->update_strategy(0);

  //   } elseif (strpos($res['message'], "🤞🏻 Façam a segunda proteção") !== false) {
  //     $list = get_field('signals_list', 'option');
  //     $last_signal = end($list);
  //     $last_signal['result'] = 'G2';

  //     $r = CTR_Blaze::trigger_bets($last_signal, 2);
  //     update_row('signals_list', count($list), $last_signal, 'option');

  //     $this->update_strategy(0);

  //   } elseif (strpos($res['message'], "✅✅✅") !== false) {
  //     $list = get_field('signals_list', 'option');
  //     $last_signal = end($list);
  //     $last_signal['result'] = $last_signal['result'] ?: 'SG';
  //     update_row('signals_list', count($list), $last_signal, 'option');

  //     $this->update_strategy(0);

  //   } elseif (strpos($res['message'], "Não bateu! 😥") !== false) {
  //     $list = get_field('signals_list', 'option');
  //     $last_signal = end($list);
  //     $last_signal['result'] = 'LOSS';
  //     update_row('signals_list', count($list), $last_signal, 'option');

  //     $this->update_strategy(1);
  //   }

  //   if (strpos($res['message'], "GREEN NO BRANCO") !== false) {
  //     $list = get_field('signals_list', 'option');
  //     $last_signal = end($list);
  //     $last_signal['white'] = true;
  //     update_row('signals_list', count($list), $last_signal, 'option');
  //   }
  //   // }

  //   return rest_ensure_response($r);
  // }

  // function update_strategy($val)
  // {
  //   $users_args = ['meta_query' => [[
  //     'relation' => 'AND',
  //     [
  //       'key' => 'strategy',
  //       'value' => 'critshot',
  //       'compare' => "=",
  //     ]
  //   ]]];
  //   $users_list =  get_users($users_args);
  //   foreach ($users_list as $k => $current_user) {
  //     update_field('strategy_critshot_field', $val, "user_$current_user->ID");
  //   }
  // }
}

new CTR_Telegram();
