<?php

class Controller_Scripts
{

  public function __construct()
  {

    // Adicionar CSS e JS corretamente no site
    add_action('wp_enqueue_scripts', array($this, 'add_css_js'));

    // Remover CSS e JS não utlizados
    add_filter('woocommerce_enqueue_styles', '__return_false');
    add_action('init', array($this, 'remove_css_js'), 99);

    // Custom Gutenberg Script
    add_action('init', array($this, 'custom_gutenberg_script'));

    //ACF - OPTIONS PAGE
    add_action('init', array($this, 'acf_start'));

    //
    add_filter('clean_url', array($this, 'handleScripts'), 11, 1);
  }

  // -----------------------------------------------------------------------------

  public function add_css_js()
  {
    /**
     * CSS
     */
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('wc-blocks-style'); // Remove WooCommerce block CSS
    // wp_enqueue_style('swiper-css', 'https://unpkg.com/swiper@8/swiper-bundle.min.css');
    // wp_enqueue_style('toasty-css', 'https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css');
    // wp_enqueue_style('aos-css', 'https://unpkg.com/aos@2.3.1/dist/aos.css');
    // wp_enqueue_style('locomotive-css', 'https://cdn.jsdelivr.net/npm/locomotive-scroll@4.1.4/dist/locomotive-scroll.min.css');
    // wp_enqueue_style('theme-style', get_theme_file_uri('/public/css/style.css'), [], date('his'));

    /**
     * JS
     */
    wp_deregister_script('jquery');
    // wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-3.2.1.min.js', array(), null);
    // wp_enqueue_script('swiper-js', 'https://unpkg.com/swiper@8/swiper-bundle.min.js', [], '1.0.0');
    // wp_enqueue_script('toasty-js', 'https://cdn.jsdelivr.net/npm/toastify-js', [], '1.0.0');
    // wp_enqueue_script('aos-js', 'https://unpkg.com/aos@2.3.1/dist/aos.js', [], '1.0.0');
    // wp_enqueue_script('gsap', "https://cdnjs.cloudflare.com/ajax/libs/gsap/3.10.4/gsap.min.js", [], '1.0.0');
    // wp_enqueue_script('ScrollTrigger', "https://cdnjs.cloudflare.com/ajax/libs/gsap/3.10.4/ScrollTrigger.min.js", [], '1.0.0');
    // wp_enqueue_script('locomotive', "https://cdn.jsdelivr.net/npm/locomotive-scroll@4.1.4/dist/locomotive-scroll.min.js", [], '1.0.0');
    // wp_enqueue_script('isotope', 'https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js', [], '1.0.0');
    // wp_enqueue_script('theme-script', get_theme_file_uri('/public/js/script.js'), array('jquery'), date('his'));
  }

  public function custom_gutenberg_script()
  {
    wp_enqueue_script('gutenberg-script', get_theme_file_uri('/admin/public/js/guten.js'), array('wp-blocks', 'wp-dom-ready', 'wp-edit-post'), '0.011', true);
  }

  // -----------------------------------------------------------------------------

  public function handleScripts($url)
  {

    if ($url != theme_url('public/js/vendor/require.js'))
      return $url;

    return sprintf(
      "%s' data-js='%s' data-main='%s' data-base-url='%s' data-template-url='%s",
      $url,
      'script-default',
      theme_url('public/js/boot'),
      home_url(),
      theme_url()
    );
  }

  // -----------------------------------------------------------------------------

  public function use_google_maps()
  {
    add_action('wp_enqueue_scripts', array($this, 'google_maps_script'));
  }

  // -----------------------------------------------------------------------------

  public function remove_css_js()
  {
    $isInLoginPage = in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));

    if (!is_admin() && !$isInLoginPage) {
      wp_deregister_script('jquery');
      wp_dequeue_script('jquery');
      wp_deregister_script('wp-embed');
      wp_dequeue_script('wp-embed');
    }
  }

  public function acf_start()
  {
    // acf_add_options_page(array(
    //   'page_title' => 'Dados Gerais',
    //   'menu_title' => 'Dados Gerais',
    //   'menu_slug'  => 'dados-gerais',
    //   'update_button'   => __('Atualizar', 'blazerobot'),
    //   'updated_message' => __("Informações Atualizadas", 'blazerobot'),
    //   'capability' => 'edit_posts'
    // ));

    acf_add_options_page(array(
      'page_title' => 'Sinais',
      'menu_title' => 'Sinais',
      'menu_slug'  => 'signals-list',
      'update_button'   => __('Atualizar', 'blazerobot'),
      'updated_message' => __("Informações Atualizadas", 'blazerobot'),
      'capability' => 'edit_posts'
    ));
  }
}

new Controller_Scripts;
