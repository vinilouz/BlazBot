<?

class CTR_Blaze
{
  /**
   * Construtor
   */
  public function __construct()
  {
    // add_action('rest_api_init', [$this, 'create_routes']);
    // add_action('admin_footer', 'my_user_del_button');
  }

  function my_user_del_button()
  {
    $screen = get_current_screen();
    if ($screen->id != "users") return;

?>
    <script type="text/javascript">
      jQuery(document).ready(function($) {
        $('<option>').val('del_user_meta').text('Delete User Meta').appendTo("select[name='action']");
      });
    </script>
<?

  }

  public static function make_bet($signal)
  {
  }

  // https://api-v2.blaze.com/roulette_games/recent
  // 0 - Branco
  // 1 - Vermelho
  // 2 - Preto
}

new CTR_Blaze();
