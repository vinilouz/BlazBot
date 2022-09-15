<?php


class CPT_Crash
{

  public function __construct()
  {
    add_action('init', [$this, 'post_type_crash']);
  }

  public function post_type_crash()
  {
    $labels = array(
      'name'                  => _x('Crash', 'Crash General Name', 'obr'),
      'singular_name'         => _x('Crash', 'Crash Singular Name', 'obr'),
      'menu_name'             => __('Crash', 'obr'),
      'name_admin_bar'        => __('Crash', 'obr'),
      'all_items'             => __('Todos os Crash', 'obr'),
      'add_new_item'          => __('Adicionar nova pergunta', 'obr'),
      'add_new'               => __('Adicionar nova', 'obr'),
      'new_item'              => __('Adicionar nova', 'obr'),
      'edit_item'             => __('Editar segmento', 'obr'),
      'update_item'           => __('Atualizar segmento', 'obr'),
      'view_item'             => __('Visualizar segmento', 'obr'),
      'view_items'            => __('Visualizar segmento', 'obr'),
      'search_items'          => __('Buscar segmento', 'obr'),
      'not_found'             => __('Nenhum segmento encontrado', 'obr'),
      'not_found_in_trash'    => __('Não encontrado no lixo', 'obr'),
      'featured_image'        => __('Imagem Destaque', 'obr'),
      'set_featured_image'    => __('Aplicar imagem destaque', 'obr'),
      'remove_featured_image' => __('Remover imagem destaque', 'obr'),
      'use_featured_image'    => __('Usar uma imagem destaque', 'obr')
    );
    $args = array(
      'label'                 => __('Crash', 'obr'),
      'labels'                => $labels,
      'supports'              => array('title', 'editor', 'revisions', 'page-attributes'),
      'hierarchical'          => false,
      'public'                => true,
      'show_ui'               => true,
      'show_in_menu'          => true,
      'menu_position'         => 3,
      'show_in_admin_bar'     => true,
      'show_in_nav_menus'     => true,
      'can_export'            => true,
      'has_archive'           => false,
      'exclude_from_search'   => false,
      'publicly_queryable'    => true,
      'menu_icon'             => 'dashicons-feedback',
      'capability_type'       => 'page',
    );
    register_post_type('crash_signal', $args);
  }
}

new CPT_Crash();
