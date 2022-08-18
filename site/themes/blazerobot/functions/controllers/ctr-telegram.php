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
    // https://blazerobot.vip/blaze/v1/double_signals
    register_rest_route('blaze/v1', '/double_signals', [
      [
        'methods'  => WP_REST_Server::READABLE,
        'callback' => [$this, 'handler_get_double_signals'],
        'permission_callback' => '__return_true',
      ],
      [
        'methods'  => WP_REST_Server::CREATABLE,
        'callback' => [$this, 'handler_set_signals'],
        'permission_callback' => '__return_true',
      ],
    ]);

    // https://blazerobot.vip/blaze/v1/crash_signals
    register_rest_route('blaze/v1', '/crash_signals', [
      [
        'methods'  => WP_REST_Server::READABLE,
        'callback' => [$this, 'handler_get_crash_signals'],
        'permission_callback' => '__return_true',
      ],
      [
        'methods'  => WP_REST_Server::CREATABLE,
        'callback' => [$this, 'handler_set_crash_signals'],
        'permission_callback' => '__return_true',
      ],
    ]);
  }

  function handler_get_double_signals()
  {
    $response = get_field('signals_list', 'option');

    return rest_ensure_response($response);
  }

  function handler_get_crash_signals()
  {
    $response = get_field('signals_crash_list', 'option');

    return rest_ensure_response($response);
  }


  function handler_set_signals(WP_REST_Request $request)
  {
    $res = $request->get_body_params();
    $r = null;

    $date = new DateTime($res['date'], new DateTimeZone('UTC'));
    $date->setTimezone(new DateTimeZone('America/Sao_Paulo'));

    /**
     * CONFIG FOR VIP DOUBLE/SEM GALE 🐔
     * @see id 1695064830
     */
    if (strpos($res['message'], "𝘌𝘕𝘛𝘙𝘈𝘋𝘈 𝘊𝘖𝘕𝘍𝘐𝘙𝘔𝘈𝘋𝘈") !== false) {
      $color = str_contains($res['message'], '🔴') ? 'VERMELHO' : 'PRETO';

      $signal = [
        'id'      => $res['id'],
        'title'   => $res['title'],
        'date'    => $date->format('d/m/Y g:i a'),
        'color'   => $color,
      ];

      // Run the signal on Blaze
      $r = CTR_Blaze::trigger_double_bets($signal);

      // Save Signal
      add_row('signals_list', $signal, 'option');
    } elseif (strpos($res['message'], "𝗪𝗜𝗡") !== false) {
      // Update with win
      $list = get_field('signals_list', 'option');
      $search = array_filter($list, function ($var) {
        return ($var['id'] == '1695064830' && $var['result'] == '');
      });
      $last_signal = end($search);
      $last_signal['result'] = 'WIN';

      if (strpos($res['message'], "𝗕𝗥𝗔𝗡𝗖𝗢") !== false) {
        $last_signal['white'] = true;
      }
      update_row('signals_list', count($list), $last_signal, 'option');
    } elseif (strpos($res['message'], "𝗟𝗢𝗦𝗦") !== false) {
      // Update with loss
      $list = get_field('signals_list', 'option');
      $search = array_filter($list, function ($var) {
        return ($var['id'] == '1695064830' && $var['result'] == '');
      });
      $last_signal = end($search);
      $last_signal['result'] = 'LOSS';
      update_row('signals_list', count($list), $last_signal, 'option');
    }

    /**
     * BOT DOUBLE SEM GALE
     * @see id 1577414274
     */
    if (strpos($res['message'], "Apostar no") !== false) {
      $color = str_contains($res['message'], '🔴') ? 'VERMELHO' : 'PRETO';
      $signal = [
        'id'      => $res['id'],
        'title'   => $res['title'],
        'date'    => $date->format('d/m/Y g:i a'),
        'color'   => $color,
      ];

      // Run the signal on Blaze
      // $r = CTR_Blaze::trigger_double_bets($signal);

      // Save Signal
      add_row('signals_list', $signal, 'option');
    } elseif (strpos($res['message'], "WIN") !== false) {

      // Update with win
      $list = get_field('signals_list', 'option');
      $search = array_filter($list, function ($var) {
        return ($var['id'] == '1577414274' && $var['result'] == '');
      });
      $last_signal = end($search);
      $last_signal['result'] = 'WIN';

      update_row('signals_list', count($list), $last_signal, 'option');
    } elseif (strpos($res['message'], "LOSS") !== false) {

      // Update with loss
      $list = get_field('signals_list', 'option');

      $search = array_filter($list, function ($var) {
        return ($var['id'] == '1577414274' && $var['result'] == '');
      });
      $last_signal = end($search);
      $last_signal['result'] = 'LOSS';

      update_row('signals_list', count($list), $last_signal, 'option');
    }

    return rest_ensure_response($r);
  }

  /**
   * CONFIG FOR 💥𝙑𝙄𝙋 𝙁𝙐𝙉𝙄𝙇 𝘽𝙇𝘼𝙕𝙀💥
   * @see id 
   */
  function handler_set_crash_signals(WP_REST_Request $request)
  {
    $r = null;
    $res = $request->get_body_params();

    $date = new DateTime($res['date'], new DateTimeZone('UTC'));
    $date->setTimezone(new DateTimeZone('America/Sao_Paulo'));
    $list = get_field('signals_crash_list', 'option');

    if ($res['id'] == 1515446435) {
      if (str_contains($res['message'], '8977794405394022401')) {
        $search = array_filter($list, function ($var) {
          return ($var['id'] == '1515446435' && $var['result'] == '');
        });
        $last_signal = end($search);
        if ($last_signal['result']) {
  
          $signal = [
            'id'      => $res['id'],
            'title'   => $res['title'],
            'message' => $res['message'],
            'date'    => $date->format('d/m/Y g:i a')
          ];
  
          // Run the signal on Blaze
          $r = CTR_Blaze::trigger_crash_bets();
  
          // Save Signal
          add_row('signals_crash_list', $signal, 'option');
        } else {
          // Update with win
          $search = array_filter($list, function ($var) {
            return ($var['id'] == '1515446435' && $var['result'] == '');
          });
          $last_signal = end($search);
          $last_signal['result'] = 'WIN';
          update_row('signals_crash_list', count($list), $last_signal, 'option');
        }
      } elseif (str_contains($res['message'], '4573473239128342603')) {
        // Update with loss
        $last_signal['result'] = 'LOSS';
        update_row('signals_crash_list', count($list), $last_signal, 'option');
      }
    } elseif($res['id'] == 1612607467) {
      if (strpos($res['message'], "Apostar em") !== false) {
        $signal = [
          'id'      => $res['id'],
          'title'   => $res['title'],
          'message' => $res['message'],
          'date'    => $date->format('d/m/Y g:i a')
        ];
  
        // Run the signal on Blaze
        // $r = CTR_Blaze::trigger_crash_bets();
  
        // Save Signal
        $r['signal'] = add_row('signals_crash_list', $signal, 'option');
      } elseif (str_contains($res['message'], 'WIN')) {
        // Update with win
        $search = array_filter($list, function ($var) {
          return ($var['id'] == '1612607467' && $var['result'] == '');
        });
        $last_signal = end($search);
        $last_signal['result'] = 'WIN';
        $r['win'] = update_row('signals_crash_list', count($list), $last_signal, 'option');
      } elseif (str_contains($res['message'], 'LOSS')) {
        // Update with loss
        $search = array_filter($list, function ($var) {
          return ($var['id'] == '1612607467' && $var['result'] == '');
        });
        $last_signal = end($search);
        $last_signal['result'] = 'LOSS';
        $r['loss'] = update_row('signals_crash_list', count($list), $last_signal, 'option');
      }
    }

    return rest_ensure_response($r);
  }

  /**
   * CONFIG FOR BLAZE TECH WITH GALES
   * @see id 1299783467
   */
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
  //     $r = CTR_Blaze::trigger_double_bets($signal);

  //     // Save Signal
  //     add_row('signals_list', $signal, 'option');
  //   } elseif (strpos($res['message'], "🤞🏻 Façam a primeira proteção") !== false) {
  //     $list = get_field('signals_list', 'option');
  //     $last_signal = end($list);
  //     $last_signal['result'] = 'G1';

  //     $r = CTR_Blaze::trigger_double_bets($last_signal, 1);
  //     update_row('signals_list', count($list), $last_signal, 'option');

  //   } elseif (strpos($res['message'], "🤞🏻 Façam a segunda proteção") !== false) {
  //     $list = get_field('signals_list', 'option');
  //     $last_signal = end($list);
  //     $last_signal['result'] = 'G2';

  //     $r = CTR_Blaze::trigger_double_bets($last_signal, 2);
  //     update_row('signals_list', count($list), $last_signal, 'option');

  //   } elseif (strpos($res['message'], "✅✅✅") !== false) {
  //     $list = get_field('signals_list', 'option');
  //     $last_signal = end($list);
  //     $last_signal['result'] = $last_signal['result'] ?: 'SG';
  //     update_row('signals_list', count($list), $last_signal, 'option');

  //   } elseif (strpos($res['message'], "Não bateu! 😥") !== false) {
  //     $list = get_field('signals_list', 'option');
  //     $last_signal = end($list);
  //     $last_signal['result'] = 'LOSS';
  //     update_row('signals_list', count($list), $last_signal, 'option');
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
}

new CTR_Telegram();
