<?php

namespace WPAdminify\Inc\Admin\Options;

use  WPAdminify\Inc\Utils ;
use  WPAdminify\Inc\Admin\AdminSettingsModel ;
if ( !defined( 'ABSPATH' ) ) {
    die;
}
// Cannot access directly.
class Module_Post_Color extends AdminSettingsModel
{
    public function __construct()
    {
        $this->general_post_settings();
    }
    
    public function get_defaults()
    {
        return [
            'post_status_bg_colors' => [
            'publish' => '#DBE2F5',
            'pending' => '#FCE4EE',
            'future'  => '#E0F1ED',
            'private' => '#FCF3D2',
            'draft'   => '#EBE0F5',
            'trash'   => '#EFF4E1',
        ],
            'post_thumb_column'     => '',
            'post_page_id_column'   => false,
            'taxonomy_id_column'    => false,
            'comment_id_column'     => false,
        ];
    }
    
    /**
     * Post Status colors
     */
    public function post_status_bg_colors( &$fields )
    {
        $fields[] = array(
            'type'    => 'subheading',
            'content' => Utils::adminfiy_help_urls(
            __( 'Post Status Background Settings', 'adminify' ),
            'https://wpadminify.com/kb/post-status-background-color/',
            'https://www.youtube.com/playlist?list=PLqpMw0NsHXV-EKj9Xm1DMGa6FGniHHly8',
            'https://www.facebook.com/groups/jeweltheme',
            'https://wpadminify.com/support/'
        ),
        );
        $fields[] = array(
            'type'    => 'notice',
            'title'   => __( 'Post Status Background', 'adminify' ),
            'style'   => 'warning',
            'content' => Utils::adminify_upgrade_pro(),
        );
    }
    
    /**
     * Post Status Columns
     */
    public function post_status_columns( &$fields )
    {
        $fields[] = array(
            'type'    => 'subheading',
            'content' => __( 'Custom Columns', 'adminify' ),
        );
        $fields[] = array(
            'type'    => 'notice',
            'title'   => __( 'Show Thumbnail Column', 'adminify' ),
            'style'   => 'warning',
            'content' => Utils::adminify_upgrade_pro(),
        );
        $fields[] = array(
            'type'    => 'notice',
            'title'   => __( 'Show Post/Page ID Column', 'adminify' ),
            'style'   => 'warning',
            'content' => Utils::adminify_upgrade_pro(),
        );
        $fields[] = array(
            'id'         => 'taxonomy_id_column',
            'type'       => 'switcher',
            'title'      => __( 'Show "Taxonomy ID" Column', 'adminify' ),
            'subtitle'   => __( 'Taxonomy ID show on all possible types of taxonomies', 'adminify' ),
            'text_on'    => __( 'Show', 'adminify' ),
            'text_off'   => __( 'Hide', 'adminify' ),
            'text_width' => 100,
            'default'    => $this->get_default_field( 'taxonomy_id_column' ),
        );
        $fields[] = array(
            'id'         => 'comment_id_column',
            'type'       => 'switcher',
            'title'      => __( 'Show "Comment ID" Column', 'adminify' ),
            'subtitle'   => __( 'Show Comment ID and Parent Comment ID Column', 'adminify' ),
            'text_on'    => 'Show',
            'text_off'   => 'Hide',
            'text_width' => 100,
            'default'    => $this->get_default_field( 'comment_id_column' ),
        );
    }
    
    public function general_post_settings()
    {
        if ( !class_exists( 'ADMINIFY' ) ) {
            return;
        }
        $fields = [];
        $this->post_status_bg_colors( $fields );
        $this->post_status_columns( $fields );
        \ADMINIFY::createSection( $this->prefix, array(
            'title'  => __( 'Post Status/Column', 'adminify' ),
            'parent' => 'module_settings',
            'icon'   => 'fas fa-paint-roller',
            'fields' => $fields,
        ) );
    }

}