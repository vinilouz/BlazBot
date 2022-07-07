<?
class Controller_Common
{

  /**
   * Construtor
   */
  public function __construct()
  {

    /**
     * Remover metatags não utilizadas
     */
    $this->remove_metatags();
    $this->remove_emoji();

    /**
     * Classes para body
     */
    add_filter('body_class', array($this, 'body_classes'));

    /**
     * Depois de ativar o tema
     */
    add_action('after_setup_theme', array($this, 'setup_features'));

    //Generate Numeric Pagination Base on Query
    add_filter('generateNumericPaginationFromQuery', array($this, 'generateNumericPaginationFromQuery'), 10, 2);

    add_filter('jpeg_quality', array($this, 'setJPEGQuality'));


    add_filter('wpseo_breadcrumb_separator', array($this, 'filter_wpseo_breadcrumb_separator'), 10, 1);
  } // __construct

  /**
   * Yoast separator
   */
  public function filter_wpseo_breadcrumb_separator($this_options_breadcrumbs_sep)
  {
    return '<i><svg width="8" height="8" viewBox="0 0 8 8" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.45333 3.7784C7.42557 3.7068 7.38394 3.64138 7.33083 3.5859L4.41417 0.669234C4.35978 0.614845 4.29521 0.571701 4.22415 0.542266C4.15308 0.512831 4.07692 0.497681 4 0.497681C3.84466 0.497681 3.69568 0.55939 3.58583 0.669234C3.53144 0.723623 3.4883 0.788193 3.45886 0.859255C3.42943 0.930318 3.41428 1.00648 3.41428 1.0834C3.41428 1.23874 3.47599 1.38772 3.58583 1.49757L5.51083 3.41673H1.08333C0.928624 3.41673 0.780251 3.47819 0.670854 3.58759C0.561458 3.69698 0.5 3.84536 0.5 4.00007C0.5 4.15478 0.561458 4.30315 0.670854 4.41255C0.780251 4.52194 0.928624 4.5834 1.08333 4.5834H5.51083L3.58583 6.50257C3.53116 6.5568 3.48776 6.62131 3.45815 6.6924C3.42853 6.76348 3.41328 6.83973 3.41328 6.91673C3.41328 6.99374 3.42853 7.06999 3.45815 7.14107C3.48776 7.21215 3.53116 7.27667 3.58583 7.3309C3.64006 7.38557 3.70458 7.42897 3.77566 7.45859C3.84675 7.4882 3.92299 7.50345 4 7.50345C4.07701 7.50345 4.15325 7.4882 4.22434 7.45859C4.29542 7.42897 4.35994 7.38557 4.41417 7.3309L7.33083 4.41423C7.38394 4.35876 7.42557 4.29334 7.45333 4.22173C7.51168 4.07972 7.51168 3.92042 7.45333 3.7784Z" fill="#696969"/></svg></i>';
  }

  /**
   * Remover metatags não utilizadas
   */
  public function remove_metatags()
  {
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
  } // remove_metatags

  public function body_classes($classes)
  {
    if (is_home() || is_front_page()) {
      $classes[] = 'page-home';
    }

    return $classes;
  }

  public function setup_features()
  {

    /**
     * Suporte de linguagem para Odin
     */
    load_theme_textdomain('blazerobot', get_template_directory() . '/languages');

    /**
     * Registrar Menus
     */
    register_nav_menus(array(
      'primary' => 'Menu Principal'
    ));

    /**
     * Adicionar suporte à Imagem Destacada
     */
    add_theme_support('post-thumbnails');

    /**
     * Adicionar Feeds automaticamente
     */
    add_theme_support('automatic-feed-links');

    /**
     * Support de CSS pesonalizado para o editor
     */
    add_editor_style(get_template_directory_uri() . '/admin/public/css/editor-style.css');

    /**
     * Add correct Title tag
     */
    add_theme_support('title-tag');
  } // setup_features

  // -----------------------------------------------------------------------------

  public function generateNumericPaginationFromQuery($page_count = 6, $query = "")
  {

    global $wp_query;

    $args = array(
      'range'           => 3,
      'custom_query'    => $query,
      'previous_string' => '<span aria-hidden="true">&larr;</span>',
      'next_string'     => '<span aria-hidden="true">&rarr;</span>',
      'before_output'   => '<nav><ul class="pagination">',
      'after_output'    => '</ul></nav>'
    );

    $args['range'] = (int) $args['range'] - 1;
    if (!$args['custom_query'])
      $args['custom_query'] = @$GLOBALS['wp_query'];
    $count = (int) $args['custom_query']->max_num_pages;
    $page  = intval($wp_query->query['paged']);
    $ceil  = ceil($args['range'] / 2);

    if ($count <= 1)
      return FALSE;

    if (!$page)
      $page = 1;

    if ($count > $args['range']) {
      if ($page <= $args['range']) {
        $min = 1;
        $max = $args['range'] + 1;
      } elseif ($page >= ($count - $ceil)) {
        $min = $count - $args['range'];
        $max = $count;
      } elseif ($page >= $args['range'] && $page < ($count - $ceil)) {
        $min = $page - $ceil;
        $max = $page + $ceil;
      }
    } else {
      $min = 1;
      $max = $count;
    }

    $echo = '';
    $previous = intval($page) - 1;
    $previous = esc_attr(get_pagenum_link($previous));

    $firstpage = esc_attr(get_pagenum_link(1));
    // if ( $firstpage && (1 != $page) )
    //     $echo .= '<li class="previous"><a href="' . $firstpage . '">' . __( 'Primeira Página', 'text-domain' ) . '</a></li>';
    if ($previous && (1 != $page))
      $echo .= '<li><a href="' . $previous . '" title="' . __('previous', 'text-domain') . '">' . $args['previous_string'] . '</a></li>';

    if (!empty($min) && !empty($max)) {
      for ($i = $min; $i <= $max; $i++) {
        if ($page == $i) {
          $echo .= '<li class="active"><span class="active">' . $i . '</span></li>';
        } else {
          $echo .= sprintf('<li><a href="%s">%d</a></li>', esc_attr(get_pagenum_link($i)), $i);
        }
      }
    }

    $next = intval($page) + 1;
    $next = esc_attr(get_pagenum_link($next));
    if ($next && ($count != $page))
      $echo .= '<li><a href="' . $next . '" title="' . __('next', 'text-domain') . '">' . $args['next_string'] . '</a></li>';

    $lastpage = esc_attr(get_pagenum_link($count));
    // if ( $lastpage ) {
    // $echo .= '<li class="next"><a href="' . $lastpage . '">' . __( 'Última Página', 'text-domain' ) . '</a></li>';

    if (isset($echo))
      echo $args['before_output'] . $echo . $args['after_output'];
  }

  // -----------------------------------------------------------------------------

  public function setJPEGQuality()
  {
    return 100;
  }

  // -----------------------------------------------------------------------------

  /** Remover scripts emoji */
  public function remove_emoji()
  {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');

    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
  }
  // -----------------------------------------------------------------------------

}

new Controller_Common;
