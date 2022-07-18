<?php
class Controller_Common_Admin
{

  /**
   * Undocumented function
   */
  public function __construct()
  {

    /**
     * Restaurar colunas no dashboard do Admin do WP
     */
    add_action('admin_head-index.php', array($this, 'restore_dashboard_columns'));


    /**
     * Adicionar CSS para melhorar visualização do codestyling
     */
    add_action('admin_head', array($this, 'style_codelstyling'));


    /**
     * Mudar label dos menus do painel
     */
    add_action('init', array($this, 'change_post_label'));


    /**
     * Personalizar logo da página de login
     */
    // add_action('login_head', array($this, 'page_login_logo'));


    /**
     * Reescrever link da página de login para a raiz do site
     */
    add_filter('login_headerurl', array($this, 'page_login_url_home'));


    /**
     * Reescrever o título do logo da página de login
     */
    add_filter('login_headertext', array($this, 'page_login_logo_title'));

    /**
     * Remover versão do WP do rodapé
     */
    add_action('update_footer', array($this, 'text_version'), 999);


    add_filter('wpseo_metabox_prio',  array($this, 'lower_wpseo_priority'));

    /*Remover dashboard padrão*/
    add_action('wp_dashboard_setup', array($this, 'remove_dashboard_widgets'));

    // Remove a barra do menu
    add_filter('show_admin_bar', '__return_false');

    /* Gutenberg options  */
    add_filter('use_block_editor_for_post_type', array($this, 'enable_gutenberg_post'), 10, 2);

    /* Remove admin separators */
    add_action('admin_menu', array($this, 'remove_separators'), 999);

    add_filter('admin_footer_text', array($this, 'footer_admin'));

    /* Add custom format in Wysiwyg */
    add_filter('mce_buttons_2', array($this, 'wpb_mce_buttons_2'));
    add_filter('tiny_mce_before_init', array($this, 'my_mce_before_init_insert_formats'));

    /* Add blody class on admin panel by role */
    add_filter('admin_body_class', [$this, 'theme_admin_body_class']);
  } // __construct


  /**
   * Alterar texto do rodapé da área de administração do WP
   */
  public function footer_admin()
  {

    $footer_text = '&copy; ' . date('Y') . ' - ' . get_bloginfo('name');
    $footer_text .=  " | Criado por <a href='https://louz.com.br' target='_blank'>Louzada's team </a>";

    echo $footer_text;
  } // footer_admin

  /**
   * Adds one or more classes to the body tag in the dashboard.
   *
   * @link https://wordpress.stackexchange.com/a/154951/17187
   * @param  String $classes Current body classes.
   * @return String          Altered body classes.
   */
  function theme_admin_body_class($classes)
  {
    $user = wp_get_current_user();
    $nClasses = "";
    foreach ($user->caps as $k => $v) {
      $nClasses .= " $k";
    }
    return "$classes $nClasses";
  }

  /**
   * Active gutenberg only for posts
   *
   * @param bool $current_status
   * @param string $post_type
   * @return bool
   */
  public function enable_gutenberg_post($current_status, $post_type)
  {
    if ($post_type === 'page') return false;

    return $current_status;
  }

  /**
   * Restaurar colunas no Admin do WP
   */
  public function restore_dashboard_columns()
  {

    add_screen_option(
      'layout_columns',
      array(
        // Quantidade máximas de colunas
        'max' => 2,

        // Valor definido como padrão
        'default' => 1
      )
    );
  } // restore_dashboard_columns

  /**
   * Style para codestyling
   */
  public function style_codelstyling()
  {

    $screen = get_current_screen();

    if ('tools_page_codestyling-localization/codestyling-localization' != $screen->id) {
      return false;
    }


    // Deixar tabela com 100% da largura tela
    $style = '<style>';
    $style .= 'table.widefat.clear { width: auto }';
    $style .= '</style>';


    echo $style;
  } // style_codelstyling



  /**
   * Mudar Labels dos menus do painel
   */
  public function change_post_label()
  {
    $get_post_type = get_post_type_object('post');
    $labels = $get_post_type->labels;
    $labels->name = 'Notícias';
    $labels->singular_name = 'Notícia';
    $labels->add_new = 'Adicionar notícia';
    $labels->add_new_item = 'Adicionar notícia';
    $labels->edit_item = 'Editar notícia';
    $labels->new_item = 'Notícias';
    $labels->view_item = 'Ver Notícias';
    $labels->search_items = 'procurar Notícias';
    $labels->not_found = 'Nenhuma Notícias encontada';
    $labels->not_found_in_trash = 'Nenhuma Notícias encontada';
    $labels->all_items = 'Todas notícias';
    $labels->menu_name = 'Notícias';
    $labels->name_admin_bar = 'Notícias';
  } // change_post_label



  /**
   * Personalizar logo da página de login
   */
  public function page_login_logo()
  {

    $logo_url = theme_url('public/images/exit-logo.png');
    // $bg_url = theme_url('public/images/admin-bg.jpg');
    $img_width = 170;
    $img_height = 131;

    $css = '<style>';
    // $css .= "body {";
    // $css .= "  background-image: url(" . $bg_url . ");  background-size: cover;";
    // $css .= "}";
    $css .= ".login #backtoblog a, .login #nav a {";
    $css .= "  color: #fff;";
    $css .= "}";
    $css .= 'body.login #login h1 a {';
    $css .= "  background: url( '{$logo_url}' ) no-repeat scroll center top transparent; background-size: 100%;";
    $css .= "  height: {$img_height}px;";
    $css .= "  width: {$img_width}px;";
    $css .= '}';
    $css .= '</style>';

    // echo $css;
  } // page_login_logo



  /**
   * Reescrever link da página de login para a raiz do site
   */
  public function page_login_url_home()
  {

    return home_url();
  } // page_login_url_home



  /**
   * Reescrever o título do logo da página de login
   */
  public function page_login_logo_title()
  {

    return esc_attr(get_bloginfo('name'));
  } // page_login_logo_title


  /**
   * Remover versão do WP do rodapé
   */
  public function text_version()
  {

    return '';
  } // text_version



  /**
   * Muda a prioridade do metaboxe do Yoast
   *
   */
  function lower_wpseo_priority($html)
  {
    return 'low';
  }

  public function remove_dashboard_widgets()
  {
    global $wp_meta_boxes;

    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
    unset($wp_meta_boxes['dashboard']['normal']['high']['wc_admin_dashboard_setup']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_drafts']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_drafts']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
    // unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
    // unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
  }

  // -----------------------------------------------------------------------------

  public function remove_separators($menu_ord)
  {
    remove_menu_page('separator1');
    remove_menu_page('separator2');
    remove_menu_page('separator-last');
  }

  // ----------------------------------------------------------------------------- 

  /**
   * Add new format in WysiWyg
   */
  public function wpb_mce_buttons_2($buttons)
  {
    array_unshift($buttons, 'styleselect');
    return $buttons;
  }

  /**
   * Callback function to filter the MCE settings
   */
  public function my_mce_before_init_insert_formats($init_array)
  {
    $style_formats = array(
      array(
        'title' => 'Big',
        'block' => 'span',
        'classes' => 'big',
        'wrapper' => true,
      )
    );
    $init_array['style_formats'] = json_encode($style_formats);

    return $init_array;
  }
} // Controller_Common_Admin

new Controller_Common_Admin;
