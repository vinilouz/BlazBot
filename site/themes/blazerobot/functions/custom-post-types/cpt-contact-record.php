<?php


class CPT_Contact_Record
{

  public function __construct()
  {
    add_action('init', array($this, 'post_type_contact_record'));
  }

  public function post_type_contact_record()
  {
    $labels = array(
      'name'                  => _x('Contatos', 'Contato General Name', 'blazerobot'),
      'singular_name'         => _x('Contato', 'Contato Singular Name', 'blazerobot'),
      'menu_name'             => __('Contatos', 'blazerobot'),
      'name_admin_bar'        => __('Contato', 'blazerobot'),
      'all_items'             => __('Todos os Contatos', 'blazerobot'),
      'add_new_item'          => __('Adicionar novo Contato', 'blazerobot'),
      'add_new'               => __('Adicionar novo Contato', 'blazerobot'),
      'new_item'              => __('Adicionar novo Contato', 'blazerobot'),
      'edit_item'             => __('Editar Contato', 'blazerobot'),
      'update_item'           => __('Atualizar Contato', 'blazerobot'),
      'view_item'             => __('Visualizar Contato', 'blazerobot'),
      'view_items'            => __('Visualizar Contato', 'blazerobot'),
      'search_items'          => __('Buscar Contato', 'blazerobot'),
      'not_found'             => __('Nenhum Contato encontrado', 'blazerobot'),
      'not_found_in_trash'    => __('Não encontrado no lixo', 'blazerobot'),
      'featured_image'        => __('Imagem Destaque', 'blazerobot'),
      'set_featured_image'    => __('Aplicar imagem destaque', 'blazerobot'),
      'remove_featured_image' => __('Remover imagem destaque', 'blazerobot'),
      'use_featured_image'    => __('Usar uma imagem destaque', 'blazerobot')
    );
    $args = array(
      'label'                 => __('Contato', 'blazerobot'),
      'labels'                => $labels,
      'supports'              => array('title'),
      'hierarchical'          => false,
      'public'                => false,
      'show_ui'               => true,
      'show_in_menu'          => true,
      'menu_position'         => 5,
      'show_in_admin_bar'     => true,
      'show_in_nav_menus'     => true,
      'can_export'            => true,
      'has_archive'           => true,
      'exclude_from_search'   => false,
      'publicly_queryable'    => true,
      'menu_icon'           => 'dashicons-groups',
      'capability_type'       => 'page',
    );
    register_post_type('contact_record', $args);
  }
}

// new CPT_Contact_Record();
