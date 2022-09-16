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
    // https://blazerobot.vip/wp-json/blaze/v1/double_signals

    // register_rest_route('blaze/v1', '/double_signals', [
    //   [
    //     'methods'  => WP_REST_Server::READABLE,
    //     'callback' => [$this, 'handler_get_double_signals'],
    //     'permission_callback' => '__return_true',
    //   ],
    //   [
    //     'methods'  => WP_REST_Server::CREATABLE,
    //     'callback' => [$this, 'handler_set_double_signals'],
    //     'permission_callback' => '__return_true',
    //   ],
    // ]);

    // https://blazerobot.vip/wp-json/blaze/v1/crash_signals

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


  function handler_get_crash_signals()
  {
    $args = array(
      'posts_per_page'   => -1,
      'post_type'        => 'crash_signal',
    );
    $the_query = new WP_Query($args);
    $response = null;
    foreach ($the_query->posts as $k => $post) {
      $response[$k]['room']    = get_field('room', $post->ID);
      $response[$k]['message'] = get_field('message', $post->ID);
      $response[$k]['time']    = get_field('time', $post->ID);
      $response[$k]['result']  = get_field('result', $post->ID);
    }
    return rest_ensure_response($response);
  }


  function handler_set_crash_signals(WP_REST_Request $request)
  {
    $r = null;
    $res = $request->get_body_params();

    $date = new DateTime($res['date'], new DateTimeZone('UTC'));
    $date->setTimezone(new DateTimeZone('America/Sao_Paulo'));
    $time = $date->format('d/m/Y H:i:s');

    /**
     * CONFIG FOR 💥𝙑𝙄𝙋 𝙁𝙐𝙉𝙄𝙇 𝘽𝙇𝘼𝙕𝙀💥
     * @see id 1515446435
     */
    // if ($res['id'] == "1515446435") {
    $stickerTrigger = '5177118037744026031';
    $stickerWin = '5172775546634895973';
    $stickerLoss = '5172641062618923383';

    if (str_contains($res['message'], $stickerTrigger)) {
      // Run the signal on Blaze
      $r['trigger'] = CTR_Blaze::trigger_crash_bets();
      $r['save'] = $this->create_crash_signal($res['title'], $res['message'], $time);
    } elseif (str_contains($res['message'], $stickerWin)) {

      $last_signal = wp_get_recent_posts('numberposts=1&post_type=crash_signal');
      $_ID = end($last_signal)['ID'];
      $r['WIN'] = update_field('result', 'WIN', $_ID);
    } elseif (str_contains($res['message'], $stickerLoss)) {

      $last_signal = wp_get_recent_posts('numberposts=1&post_type=crash_signal');
      $_ID = end($last_signal)['ID'];
      $r['LOSS'] = update_field('result', 'LOSS', $_ID);
    }
    // }

    return rest_ensure_response($r);
  }


  function create_crash_signal($title, $message, $time)
  {
    $post_title = "$title - $time";
    $post_id = wp_insert_post(array(
      'post_type'    => 'crash_signal',
      'post_title'   => $post_title,
      'post_content' => $message,
      'post_status'  => 'publish',
      'ping_status'  => 'closed',
      'comment_status' => 'closed',
    ));

    if ($post_id) {
      add_post_meta($post_id, 'room', $title);
      add_post_meta($post_id, 'time', $time);
      add_post_meta($post_id, 'message', $message);
    }

    return $post_id ?: false;
  }
}

new CTR_Telegram();
