<?php

namespace WPAdminify\Inc\Admin\Options;

use  WPAdminify\Inc\Utils ;
use  WPAdminify\Inc\Admin\AdminSettingsModel ;
if ( !defined( 'ABSPATH' ) ) {
    die;
}
// Cannot access directly.
class General_Layout_Mode extends AdminSettingsModel
{
    public function __construct()
    {
        $this->general_layout_mode_settings();
    }
    
    public function get_defaults()
    {
        return [
            'admin_bar_mode'                => 'light',
            'admin_bar_logo_type'           => 'image_logo',
            'admin_bar_light_mode'          => array(
            'admin_bar_light_logo_text'      => 'WP Adminify',
            'admin_bar_light_logo_text_typo' => '',
            'admin_bar_light_logo'           => '',
            'light_logo_size'                => array(
            'width'  => '150',
            'height' => '45',
            'unit'   => 'px',
        ),
        ),
            'admin_bar_dark_mode'           => array(
            'admin_bar_dark_logo_text'      => 'WP Adminify',
            'admin_bar_dark_logo_text_typo' => '',
            'admin_bar_dark_logo'           => '',
            'dark_logo_size'                => array(
            'width'  => '150',
            'height' => '45',
            'unit'   => 'px',
        ),
        ),
            'enable_schedule_dark_mode'     => false,
            'schedule_dark_mode_type'       => 'system',
            'schedule_dark_mode_start_time' => '',
            'schedule_dark_mode_end_time'   => '',
        ];
    }
    
    /**
     * Logo Options Settings
     *
     * @return void
     */
    public function layout_mode_setting_fields( &$fields )
    {
        $fields[] = array(
            'type'    => 'subheading',
            'content' => Utils::adminfiy_help_urls(
            __( 'Logo Options Settings', 'adminify' ),
            'https://wpadminify.com/kb/configure-wordpress-dashboard-dark-mode/',
            'https://www.youtube.com/playlist?list=PLqpMw0NsHXV-EKj9Xm1DMGa6FGniHHly8',
            'https://www.facebook.com/groups/jeweltheme',
            'https://wpadminify.com/support/'
        ),
        );
        $fields[] = array(
            'id'      => 'admin_bar_mode',
            'type'    => 'button_set',
            'title'   => __( 'Color Mode', 'adminify' ),
            'options' => array(
            'light' => __( 'Light Mode', 'adminify' ),
            'dark'  => __( 'Dark Mode', 'adminify' ),
        ),
            'default' => $this->get_default_field( 'admin_bar_mode' ),
        );
        $fields[] = array(
            'id'         => 'admin_bar_logo_type',
            'type'       => 'button_set',
            'title'      => __( 'Logo Type', 'adminify' ),
            'options'    => array(
            'image_logo' => __( 'Image', 'adminify' ),
            'text_logo'  => __( 'Text', 'adminify' ),
        ),
            'default'    => $this->get_default_field( 'admin_bar_logo_type' ),
            'dependency' => array(
            'admin_ui',
            '==',
            'true',
            'true'
        ),
        );
        $fields[] = array(
            'id'         => 'admin_bar_light_mode',
            'type'       => 'fieldset',
            'fields'     => array(
            array(
            'id'         => 'admin_bar_light_logo_text',
            'type'       => 'text',
            'title'      => __( 'Logo Text', 'adminify' ),
            'dependency' => array(
            'admin_bar_logo_type',
            '==',
            'text_logo',
            'true'
        ),
            'default'    => $this->get_default_field( 'admin_bar_light_mode' )['admin_bar_light_logo_text'],
        ),
            array(
            'id'              => 'admin_bar_light_logo_text_typo',
            'type'            => 'typography',
            'title'           => __( 'Logo Text Typography', 'adminify' ),
            'font_family'     => true,
            'font_weight'     => true,
            'font_style'      => true,
            'font_size'       => true,
            'line_height'     => true,
            'letter_spacing'  => true,
            'text_align'      => false,
            'text-transform'  => true,
            'color'           => true,
            'subset'          => false,
            'word_spacing'    => true,
            'text_decoration' => true,
            'dependency'      => array(
            'admin_bar_logo_type',
            '==',
            'text_logo',
            'true'
        ),
            'default'         => $this->get_default_field( 'admin_bar_light_mode' )['admin_bar_light_logo_text_typo'],
        ),
            array(
            'id'           => 'admin_bar_light_logo',
            'type'         => 'media',
            'title'        => __( 'Light Logo', 'adminify' ),
            'library'      => 'image',
            'preview_size' => 'thumbnail',
            'button_title' => __( 'Add Light Logo', 'adminify' ),
            'remove_title' => __( 'Remove Light Logo', 'adminify' ),
            'default'      => $this->get_default_field( 'admin_bar_light_mode' )['admin_bar_light_logo'],
            'dependency'   => array(
            'admin_bar_logo_type',
            '==',
            'image_logo',
            'true'
        ),
        ),
            array(
            'id'         => 'light_logo_size',
            'type'       => 'dimensions',
            'title'      => __( 'Logo Size', 'adminify' ),
            'default'    => $this->get_default_field( 'admin_bar_light_mode' )['light_logo_size'],
            'dependency' => array(
            'admin_bar_logo_type',
            '==',
            'image_logo',
            'true'
        ),
        )
        ),
            'dependency' => array(
            'admin_ui|admin_bar_mode',
            '==|==',
            'true|light',
            'true'
        ),
        );
        $fields[] = array(
            'id'         => 'admin_bar_dark_mode',
            'type'       => 'fieldset',
            'fields'     => array(
            array(
            'id'         => 'admin_bar_dark_logo_text',
            'type'       => 'text',
            'title'      => __( 'Logo Text', 'adminify' ),
            'default'    => $this->get_default_field( 'admin_bar_dark_mode' )['admin_bar_dark_logo_text'],
            'dependency' => array(
            'admin_bar_logo_type',
            '==',
            'text_logo',
            'true'
        ),
        ),
            array(
            'id'                 => 'admin_bar_dark_logo_text_typo',
            'type'               => 'typography',
            'title'              => __( 'Logo Text Typography', 'adminify' ),
            'font_family'        => true,
            'font_weight'        => true,
            'font_style'         => true,
            'font_size'          => true,
            'line_height'        => true,
            'letter_spacing'     => true,
            'text_align'         => false,
            'text-transform'     => true,
            'color'              => true,
            'subset'             => false,
            'backup_font_family' => false,
            'font_variant'       => false,
            'word_spacing'       => true,
            'text_decoration'    => true,
            'dependency'         => array(
            'admin_bar_logo_type',
            '==',
            'text_logo',
            'true'
        ),
            'default'            => $this->get_default_field( 'admin_bar_dark_mode' )['admin_bar_dark_logo_text_typo'],
        ),
            array(
            'id'           => 'admin_bar_dark_logo',
            'type'         => 'media',
            'title'        => __( 'Dark Logo', 'adminify' ),
            'library'      => 'image',
            'preview_size' => 'thumbnail',
            'button_title' => __( 'Add Dark Logo', 'adminify' ),
            'remove_title' => __( 'Remove Dark Logo', 'adminify' ),
            'default'      => $this->get_default_field( 'admin_bar_dark_mode' )['admin_bar_dark_logo'],
            'dependency'   => array(
            'admin_bar_logo_type',
            '==',
            'image_logo',
            'true'
        ),
        ),
            array(
            'id'         => 'dark_logo_size',
            'type'       => 'dimensions',
            'title'      => __( 'Logo Size', 'adminify' ),
            'default'    => $this->get_default_field( 'admin_bar_dark_mode' )['dark_logo_size'],
            'dependency' => array(
            'admin_bar_logo_type',
            '==',
            'image_logo',
            'true'
        ),
        )
        ),
            'dependency' => array(
            'admin_ui|admin_bar_mode',
            '==|==',
            'true|dark',
            'true'
        ),
        );
    }
    
    public function schedule_dark_mode_fields( &$fields )
    {
        $fields[] = array(
            'type'    => 'subheading',
            'content' => __( 'Schedule Dark Mode', 'adminify' ),
        );
        $fields[] = array(
            'type'    => 'notice',
            'title'   => __( 'Enable Schedule Dark Mode', 'adminify' ),
            'style'   => 'warning',
            'content' => Utils::adminify_upgrade_pro(),
        );
    }
    
    public function general_layout_mode_settings()
    {
        if ( !class_exists( 'ADMINIFY' ) ) {
            return;
        }
        $fields = [];
        $this->layout_mode_setting_fields( $fields );
        $this->schedule_dark_mode_fields( $fields );
        \ADMINIFY::createSection( $this->prefix, array(
            'title'  => __( 'Logo Options', 'adminify' ),
            'icon'   => 'fas fa-adjust',
            'fields' => $fields,
        ) );
    }

}