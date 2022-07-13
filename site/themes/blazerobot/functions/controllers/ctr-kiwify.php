<?php

class CTR_Kiwify
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
    // http://localhost.robot2/wp-json/kiwify/v1/create_account
    register_rest_route('kiwify/v1', 'create_account', [
      [
        'methods'  => WP_REST_Server::CREATABLE,
        'callback' => [$this, 'handler_create_account'],
        'permission_callback' => '__return_true',
      ],
    ]);

    // http://localhost.robot2/wp-json/kiwify/v1/signatures
    register_rest_route('kiwify/v1', 'signatures', [
      [
        'methods'  => WP_REST_Server::CREATABLE,
        'callback' => [$this, 'handler_signatures'],
        'permission_callback' => '__return_true',
      ],
    ]);
  }

  function handler_create_account(WP_REST_Request $request)
  {
    $res = $request->get_json_params();

    return rest_ensure_response($res);
  }

  function handler_signatures(WP_REST_Request $request)
  {
    $res = $request->get_json_params();

    return rest_ensure_response($res);
  }
}

// new CTR_Kiwify();