<?php

class CTR_Menu_Editor
{

  public function __construct()
  {
    add_filter('custom_menu_order', array($this, 'custom_menu_order'), 10, 1);
    add_filter('menu_order', array($this, 'custom_menu_order'), 10, 1);
    add_action('admin_menu', array($this, 'editor_menu'), 99);
    // add_action('admin_init', array($this, 'debug'), 99);
  }

  public function debug()
  {
    /* Debug for first level Menu */
    echo '<pre style="top:10px;left:170px;position:relative;">' . print_r($GLOBALS['menu'], true) . '</pre>';
    /* Debug submenu */
    global $submenu;
    echo '<pre style="top:10px;left:170px;position:relative;">' . print_r($submenu, true) . '</pre>';
  }

  public function editor_menu()
  {
    $user = get_user_by('id', get_current_user_id());
    $role = array_shift($user->roles);

    // Custom menu
    // add_menu_page('Home', 'Home', 'edit_pages', 'post.php?post=2&action=edit', '', 'dashicons-admin-home');

    remove_menu_page('edit-comments.php');                  // Comentários

    if ($role != 'editor')
      return;

    global $menu;

    // Main menus
    remove_menu_page('options-general.php');                // Configurações
    remove_menu_page('edit.php?post_type=acf-field-group'); // ACF
    remove_menu_page('plugins.php');                        // Plugins
    remove_menu_page('edit.php');                           // Blog posts

    // Sub menus
    remove_submenu_page('themes.php', 'customize.php?return=%2Fwp-admin%2F'); // Personalizar
    remove_submenu_page('themes.php', 'themes.php');                          // Temas

    unset($GLOBALS['submenu']['pages'][0]);
  }

  public function custom_menu_order($menu_ord)
  {

    if (!$menu_ord)
      return true;

    return array(
      'index.php',                         // Dashboard
      'post.php?post=2&action=edit',       // Home
      'dados-gerais',                      // Geral options
      'clientes',                          // Clientes
      'depoimentos',                       // Depoimentos
      'edit.php?post_type=vagas',          // Vagas
      'edit.php?post_type=trabalho',       // Trabalhos
      'edit.php?post_type=contact_record', // Contato
      'edit.php?post_type=page',           // Pages
      'themes.php',                        // Aparência
      'plugins.php',                       // Plugins
      'upload.php',                        // Midias
      'users.php',                         // Usuários

      'plugins.php',
      'edit.php',
      'edit-comments.php',
      'options-general.php',
      'edit.php?post_type=acf-field-group',
      'separator-last',
    );
  }
}

new CTR_Menu_Editor();
