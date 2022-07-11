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

  function handler_set_signals(WP_REST_Request $request)
  {
    $res = $request->get_body_params();

    // Blaze Tech && Buzz
    if ($res['id'] == 1299783467 || $res['id'] == 1785180053) {
      if (strpos($res['message'], "Oportunidade encontrada") !== false) {
        $re = '/(?<=Apostar em ).*(?= )/m';
        preg_match_all($re, $res['message'], $matches, PREG_SET_ORDER, 0);
        $color = $matches[0][0];

        $signal = [
          'id'      => $res['id'],
          'title'   => $res['title'],
          'date'    => date("d-m-Y H:i:s"),
          'color'   => $color,
        ];

        // Run the signal on Blaze
        // CTR_Blaze::trigger_bets($signal);

        // Save Signal
        $res = add_row('signals_list', $signal, 'option');
      } elseif (strpos($res['message'], "🤞🏻 Façam a primeira proteção") !== false) {
        $list = get_field('signals_list', 'option');
        $last_signal = end($list);
        $last_signal['result'] = 'G1';

        // CTR_Blaze::trigger_bets($last_signal,1);
        update_row('signals_list', count($list), $last_signal, 'option');
      } elseif (strpos($res['message'], "🤞🏻 Façam a segunda proteção") !== false) {
        $list = get_field('signals_list', 'option');
        $last_signal = end($list);
        $last_signal['result'] = 'G2';

        // CTR_Blaze::trigger_bets($last_signal,2);
        update_row('signals_list', count($list), $last_signal, 'option');
      } elseif (strpos($res['message'], "✅✅✅") !== false) {
        $list = get_field('signals_list', 'option');
        $last_signal = end($list);
        $last_signal['result'] = $last_signal['result'] ?: 'SG';
        update_row('signals_list', count($list), $last_signal, 'option');
      } elseif (strpos($res['message'], "Não bateu! 😥") !== false) {
        $list = get_field('signals_list', 'option');
        $last_signal = end($list);
        $last_signal['result'] = 'LOSS';
        update_row('signals_list', count($list), $last_signal, 'option');
      }

      if (strpos($res['message'], "GREEN NO BRANCO") !== false) {
        $list = get_field('signals_list', 'option');
        $last_signal = end($list);
        $last_signal['white'] = true;
        update_row('signals_list', count($list), $last_signal, 'option');
      }
    }

    return rest_ensure_response($res);
  }

  function get_color($signal)
  {
    // Blaze Tech
    if (strpos($signal['message'], "Oportunidade encontrada") !== false) {
      $re = '/(?<=Apostar em ).*(?= )/m';
      preg_match_all($re, $signal['message'], $matches, PREG_SET_ORDER, 0);

      return $matches[0][0];
    }

    return null;
  }
}

new CTR_Telegram();
