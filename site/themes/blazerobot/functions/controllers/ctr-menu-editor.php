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
    global $menu;
    // Perfil label
    $menu[70][0] = 'Configurações';

    // == // == // == // == // == //

    $user = get_user_by('id', get_current_user_id());
    $role = array_shift($user->roles);

    remove_menu_page('edit-comments.php');                  // Comentários
    remove_menu_page('edit.php');                           // Blog posts

    unset($GLOBALS['submenu']['pages'][0]);

    if ($role == 'editor' || $role == 'subscriber') {
      // Main menus
      // remove_menu_page('options-general.php');                // Configurações
      // remove_menu_page('edit.php?post_type=acf-field-group'); // ACF
      remove_menu_page('index.php');                             // Ferramentas
      remove_menu_page('tools.php');                             // Ferramentas
      remove_menu_page('edit.php?post_type=page');               // Páginas
      remove_menu_page('upload.php');                            // Mídia
      remove_menu_page('edit.php?post_type=acf-field-group');    // ACF
      remove_menu_page('uip-content');                           // Conteúdos
      remove_menu_page('edit.php?post_type=uip-admin-page');     // Dashboard

      // Sub menus
      // remove_submenu_page('themes.php', 'customize.php?return=%2Fwp-admin%2F'); // Personalizar
      remove_submenu_page('options-general.php', 'options-general.php');      // Temas
      remove_submenu_page('options-general.php', 'options-writing.php');      // Temas
      remove_submenu_page('options-general.php', 'options-reading.php');      // Temas
      remove_submenu_page('options-general.php', 'options-discussion.php');   // Temas
      remove_submenu_page('options-general.php', 'options-media.php');        // Temas
      remove_submenu_page('options-general.php', 'options-permalink.php');    // Temas
      remove_submenu_page('options-general.php', 'options-privacy.php');      // Temas
      remove_submenu_page('options-general.php', 'svg-support');              // Temas
      remove_submenu_page('options-general.php', 'uip-menu-creator');         // Temas
    }
  }

  public function custom_menu_order($menu_ord)
  {

    if (!$menu_ord)
      return true;

    return array(
      'index.php',                          // Dashboard
      'uip-overview',                       // Dashboard
      'signals-list',                       // Sinais
      'crash-signals-list',                 // Sinais
      'edit.php?post_type=page',            // Pages
      'users.php',                          // Usuários
      'edit.php?post_type=page',            // Pages
      'plugins.php',                        // Plugins
      'upload.php',                         // Midias
      'themes.php',                         // Aparência
      'options-general.php',                // Options
      'edit.php?post_type=acf-field-group', // ACF
    );
  }
}

new CTR_Menu_Editor();
