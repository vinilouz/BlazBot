<?php

// define all helper functions to create list table
function jltwp_adminify_admin_columns_create_post_table($screen_id)
{
	require_once(ABSPATH . 'wp-admin/includes/class-wp-posts-list-table.php');

	return new WP_Posts_List_Table(['screen' => $screen_id]);
}

function jltwp_adminify_admin_columns_create_user_table($screen_id)
{
	require_once(ABSPATH . 'wp-admin/includes/class-wp-users-list-table.php');

	return new WP_Users_List_Table(['screen' => $screen_id]);
}

function jltwp_adminify_admin_columns_create_comment_table($screen_id)
{
	require_once(ABSPATH . 'wp-admin/includes/class-wp-comments-list-table.php');

	$table = new WP_Comments_List_Table(['screen' => $screen_id]);

	// Since 4.4 the `floated_admin_avatar` filter is added in the constructor of the `\WP_Comments_List_Table` class.
	remove_filter('comment_author', [$table, 'floated_admin_avatar']);

	return $table;
}

function jltwp_adminify_admin_columns_create_media_table($screen_id)
{
	require_once(ABSPATH . 'wp-admin/includes/class-wp-media-list-table.php');

	return new WP_Media_List_Table(['screen' => $screen_id]);
}

function jltwp_adminify_admin_columns_create_taxonomy_table($screen_id)
{
	require_once(ABSPATH . 'wp-admin/includes/class-wp-terms-list-table.php');

	return new WP_Terms_List_Table(['screen' => $screen_id]);
}
function jltwp_adminify_admin_columns_create_network_user_table($screen_id)
{
	require_once(ABSPATH . 'wp-admin/includes/class-wp-ms-users-list-table.php');

	return new WP_MS_Users_List_Table(['screen' => $screen_id]);
}



// get the columns list of any post or users, comments etc.
function jltwp_adminify_admin_columns_data($type)
{
	// $type can be post, page, product, user, shop_order, shop_coupon, 'edit-comments', 'users', 'upload'

	$builtin_non_posttype_data = ['users', 'edit-comments', 'upload'];
	$table = '';
	
	if ( in_array( $type, $builtin_non_posttype_data ) ) {
		switch ( $type ) {
			case 'users':
				$table = jltwp_adminify_admin_columns_create_user_table( $type );
				break;
			case 'edit-comments':
				$table = jltwp_adminify_admin_columns_create_comment_table( $type );
				break;
			case 'upload':
				$table = jltwp_adminify_admin_columns_create_media_table( $type );
				break;
		}
	} else {

		$type_post = post_type_exists( $type );
		$type_tax = taxonomy_exists( str_replace( 'edit-', '', $type ) );

		if ( $type_post ) $table = jltwp_adminify_admin_columns_create_post_table( $type ); // for all kind of post_type
		if ( $type_tax ) $table = jltwp_adminify_admin_columns_create_taxonomy_table( $type ); // for all kind of taxonomy

	}

	// handle woocommerce
	if ( function_exists('WC') ) {

		switch ( $type ) {
			case 'product':
				include_once dirname(WC_PLUGIN_FILE) . '/includes/admin/list-tables/class-wc-admin-list-table-products.php';
				new WC_Admin_List_Table_Products();
				break;
			case 'shop_order':
				include_once dirname(WC_PLUGIN_FILE) . '/includes/admin/list-tables/class-wc-admin-list-table-orders.php';
				new WC_Admin_List_Table_Orders();
				break;
			case 'shop_coupon':
				include_once dirname(WC_PLUGIN_FILE) . '/includes/admin/list-tables/class-wc-admin-list-table-coupons.php';
				new WC_Admin_List_Table_Coupons();
				break;
		}
	}

	$col_names = [];

	if ( $table instanceof WP_List_Table ) {
		$col_names =  $table->get_columns();
		if ( array_key_exists('cb', $col_names) ) {
			unset($col_names['cb']);
		}
	}

	return (array) $col_names;

}

// add_action('current_screen', 'jltwp_adminify_admin_columns_data', 99);


function adminify_post_types() {

	$sections = [];

	$update_column_settings = get_option('_wpadminify_admin_columns_settings');

	foreach ( $update_column_settings  as $p => $post_type ) {

		if ( $post_type == 'attachment' ) continue;

		$defaults = [];

		foreach ( $post_type as $column => $column_label ) {

			$column_meta = adminify_get_column_meta( $post_type, $column, $column_label );

			$defaults[] = array(
				'type' 		=> $column,
				'label'     => $column_label,
				'width'     => $column_meta['width']
			);

		}

		$sections[] = array(
			'title'  => WPAdminify\Inc\Utils::id_to_string($p),
			'fields' => array(
				array(
					'id'     => 'admin-columns-group-' . $p,
					'type'   => 'group',
					'title'  => '',
					'fields' => array(

						'field_type' => array(
							'id'          => 'field_type',
							'type'        => 'select',
							'title'       => 'Column Type',
							'chosen'      => true,
							'placeholder' => 'Select Column Type',
							'options'     => array(
								'title'      => 'Title',
								'author'     => 'Author',
								'categories' => 'Categories',
								'tags'       => 'Tags',
								'comments'   => 'Comments',
								'date'       => 'Date',
								'id'         => 'ID',
							),
						),

						'label' => array(
							'id'    => 'label',
							'type'  => 'text',
							'title' => 'Label',
						),

						'width' => array(
							'id'      => 'width',
							'type'    => 'slider',
							'title'   => 'Width',
							'unit'    => '%'
						),

					),

					'default' => $defaults

				)
			)
		);

	}
	
	return $sections;

}



/*
 * Get registered post_types
 */
function adminify__get_post_types() {

	$post_types = [];

	foreach ( WPAdminify\Inc\Utils::get_post_types() as $post_type ) {

		if ( $post_type->name == 'attachment' ) continue;

		$post_types[ $post_type->name ] = $post_type->labels->singular_name;

	}
	
	return $post_types;

}

/*
 * Get registered post_types
 */
function adminify__get_taxonomies() {

	$taxonomies = [];

	foreach ( WPAdminify\Inc\Utils::get_taxonomies() as $taxonomy ) {
		$taxonomies[ $taxonomy->name ] = $taxonomy->labels->singular_name;
	}
	
	return $taxonomies;

}

/*
 * Get saved version of both visible & non visible columns of a specific post_type
 */
function adminify__get_post_type_all_columns( $post_type ) {
	return (array) get_option( '_adminify_admin_columns_' . $post_type, [] );
}

/*
 * Get both visible & non visible columns of a specific post_type
 */
function _adminify__get_post_type_all_columns( $post_type ) {
	$columns = (array) jltwp_adminify_admin_columns_data( $post_type );
	update_option( '_adminify_admin_columns_' . $post_type, $columns );
	return $columns;
}

/*
 * Get saved version of both visible & non visible columns of a specific taxonomy
 */
function adminify__get_taxonomy_all_columns( $taxonomy ) {
	return (array) get_option( '_adminify_admin_taxonomy_columns_' . $taxonomy, [] );
}

/*
 * Get both visible & non visible columns of a specific taxonomy
 */
function _adminify__get_taxonomy_all_columns( $taxonomy ) {
	$columns = (array) jltwp_adminify_admin_columns_data( 'edit-' . $taxonomy );
	update_option( '_adminify_admin_taxonomy_columns_' . $taxonomy, $columns );
	return $columns;
}

/*
 * Get visible columns of all post_types
 */
function adminify__get_post_types_columns() {

	$post_types = adminify__get_post_types();

	$post_types_columns = [];

	foreach ( $post_types as $post_type => $post_type_title ) {

		$column_data = [
			'name' => $post_type,
			'title' => $post_type_title,
			'columns' => _adminify__get_post_type_all_columns( $post_type ),
			'display_columns' 	=> adminify_prepare_post_type_column_meta( $post_type ),
			'fields'    => adminify_get_post_type_fields( $post_type ),
		];

		if ( ! in_array($post_type, ['post', 'page']) ) {
			$column_data['is_pro'] = true;
		}

		$post_types_columns[] = $column_data;

	}
	
	return $post_types_columns;

}

function _adminify__get_taxonomy_post_type( $taxonomy ) {
	
	$tax = get_taxonomy( $taxonomy );
	
	if ( $tax && !empty($tax->object_type) ) {
		return $tax->object_type[0];
	}

	return '';

}

/*
 * Get visible columns of all taxonomies
 */
function adminify__get_taxonomies_columns() {

	$taxonomies = adminify__get_taxonomies();

	$taxonomies_columns = [];
	
	foreach ( $taxonomies as $taxonomy => $taxonomy_title ) {
		
		$column_data = [
			'name' => $taxonomy,
			'title' => $taxonomy_title,
			'object_type' => _adminify__get_taxonomy_post_type( $taxonomy ),
			'columns' => _adminify__get_taxonomy_all_columns( $taxonomy ),
			'display_columns' 	=> adminify_prepare_taxonomy_column_meta( $taxonomy ),
			'fields'    => adminify_get_taxonomy_fields( $taxonomy )
		];
		
		if ( ! in_array($taxonomy, ['category', 'post_tag']) ) {
			$column_data['is_pro'] = true;
		}

		$taxonomies_columns[] = $column_data;

	}
	
	return $taxonomies_columns;

}

/*
 * Get specific column meta data
 */
function adminify_get_column_meta( $post_type, $column, $column_label ) {

	$column_default_meta = [
		'label' => $column_label,
		'width' => [
			'value' => 'auto',
			'unit' => '%'
		],
		'fields' => ['type', 'label', 'width']
	];

	/*
	 * You can extend the fields based on post type and column
	 * Make sure you have added the new field type in this function: adminify_get_post_type_fields
	*/

	// if ( $post_type == 'page' && $column == 'taxonomy-folder' ) {
	// 	$column_default_meta['fields'][] = 'new';
	// }

	$data = (array) get_option( '_adminify_admin_columns_meta_data', [] );

	$column_meta = [];

	if ( !empty($data) && !empty($data[$post_type]) && !empty($data[$post_type][$column]) ) {
		$column_meta = $data[$post_type][$column];
	}

	return array_merge_recursive( $column_default_meta, $column_meta );

}

/*
 * Get post_type columns meta
 */
function adminify_prepare_post_type_column_meta( $post_type ) {

	$columns_meta = get_option( '_adminify_admin_columns_meta_' . $post_type, null );

	if ( ! is_null($columns_meta) ) return (array) $columns_meta;

	$columns = adminify__get_post_type_all_columns( $post_type );

	$_columns = [];

	foreach ( $columns as $column => $column_label ) {
		$_columns[] = [ 'name' => $column ] + (array) adminify_get_column_meta( $post_type, $column, $column_label );
	}

	return $_columns;

}

/*
 * Get taxonomy columns meta
 */
function adminify_prepare_taxonomy_column_meta( $taxonomy ) {

	$columns_meta = get_option( '_adminify_admin_taxonomy_columns_meta_' . $taxonomy, null );

	if ( ! is_null($columns_meta) ) return (array) $columns_meta;

	$columns = adminify__get_taxonomy_all_columns( $taxonomy );

	$_columns = [];

	foreach ( $columns as $column => $column_label ) {
		$_columns[] = [ 'name' => $column ] + (array) adminify_get_column_meta( $taxonomy, $column, $column_label );
	}

	return $_columns;

}

/*
 * Get post_type fields
 */
function adminify_get_post_type_fields( $post_type ) {

	$columns = array_map( 'sanitize_text_field', _adminify__get_post_type_all_columns($post_type) );

	return [
		[
			'id'          => 'type',
			'type'        => 'select',
			'title'       => 'Column Type',
			'chosen'      => true,
			'placeholder' => 'Select Column Type',
			'options'     => $columns,
		],
		[
			'id'    => 'label',
			'type'  => 'text',
			'title' => 'Label',
		],
		[
			'id'      => 'width',
			'type'    => 'slider',
			'title'   => 'Width',
			'unit'    => '%'
		],

		/*
		 * Register additional fields
		 */
		// [
		// 	'id'      => 'new',
		// 	'type'    => 'text',
		// 	'title'   => 'New'
		// ]

	];

}

/*
 * Get taxonomy fields
 */
function adminify_get_taxonomy_fields( $taxonomy ) {

	$columns = array_map( 'sanitize_text_field', _adminify__get_taxonomy_all_columns( $taxonomy ) );

	return [
		[
			'id'          => 'type',
			'type'        => 'select',
			'title'       => 'Column Type',
			'chosen'      => true,
			'placeholder' => 'Select Column Type',
			'options'     => $columns,
		],
		[
			'id'    => 'label',
			'type'  => 'text',
			'title' => 'Label',
		],
		[
			'id'      => 'width',
			'type'    => 'slider',
			'title'   => 'Width',
			'unit'    => '%'
		],

		/*
		 * Register additional fields
		 */
		// [
		// 	'id'      => 'new',
		// 	'type'    => 'text',
		// 	'title'   => 'New'
		// ]

	];

}