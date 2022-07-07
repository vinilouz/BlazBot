<?

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

    $signal = [
      'id'      => $res['id'],
      'title'   => $res['title'],
      'date'    => date("d-m-Y H:i:s"),
      'color'   => $this->filter_message($res['message']),
      'message' => $res['message'],
    ];

    // Run the signal on Blaze
    CTR_Blaze::make_bet($signal);

    // Save Signal
    $row = add_row('signals_list', $signal, 'option');

    return rest_ensure_response($row);
  }

  function filter_message($message)
  {

    // Blaze Tech
    if (strpos($message, "Oportunidade encontrada") !== false) {
      $re = '/(?<=Apostar em ).*(?= )/m';
      preg_match_all($re, $message, $matches, PREG_SET_ORDER, 0);

      return $matches[0][0];
    }

    return null;
  }
}

new CTR_Telegram();
