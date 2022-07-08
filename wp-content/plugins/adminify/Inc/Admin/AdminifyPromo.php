<?php

namespace WPAdminify\Inc\Admin;

use  WPAdminify\Inc\Admin\AdminSettings ;
/**
 * Author Name: Liton Arefin
 * Author URL: https://jeweltheme.com
 * Date: 25/07/2021
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// No, Direct access Sir !!!
class AdminifyPromo
{
    public  $timenow ;
    private static  $instance = null ;
    public static function get_instance()
    {
        if ( !self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function __construct()
    {
        if ( !is_admin() ) {
            return;
        }
        $this->options = (array) AdminSettings::get_instance()->get();
        $this->timenow = strtotime( "now" );
        // Admin Notices
        add_action( 'admin_init', [ $this, 'jltwp_adminify_admin_notice_init' ] );
        // Notices
        add_action( 'admin_notices', [ $this, 'jltwp_adminify_latest_update_details' ], 10 );
        add_action( 'network_admin_notices', [ $this, 'jltwp_adminify_latest_update_details' ], 10 );
        add_action( 'admin_notices', [ $this, 'jltwp_adminify_review_notice_generator' ], 10 );
        add_action( 'admin_notices', [ $this, 'jltwp_adminify_upgrade_pro_notice_generator' ], 10 );
        // Black Friday & Cyber Monday Offer
        add_action( 'admin_notices', [ $this, 'jltwp_adminify_black_friday_cyber_monday_deals' ] );
        // Styles
        add_action( 'admin_print_styles', [ $this, 'jltwp_adminify_admin_notice_styles' ] );
    }
    
    public function jltwp_adminify_admin_notice_init()
    {
        add_action( 'wp_ajax_adminify_dismiss_admin_notice', [ $this, 'jltwp_adminify_dismiss_admin_notice' ] );
    }
    
    public function jltwp_adminify_latest_update_details()
    {
        if ( !self::is_admin_notice_active( 'wp-adminify-update-notice-forever' ) ) {
            return;
        }
        $jltwp_adminify_changelog_message = sprintf(
            __( '%3$s %4$s %5$s %6$s %7$s %8$s <br> <strong>Check Changelogs for </strong> <a href="%1$s" target="__blank">%2$s</a>', 'adminify' ),
            esc_url_raw( 'https://wpadminify.com/updates-209/' ),
            __( 'More Details', 'adminify' ),
            /** Changelog Items
             * Starts from: %3$s
             */
            '<h3 class="adminify-update-head">' . WP_ADMINIFY . ' <span><small><em>v' . WP_ADMINIFY_VER . '</em></small>' . __( ' has some Major updates..', 'adminify' ) . '</span></h3><br>',
            // %3$s
            __( '<span class="dashicons dashicons-yes"></span> <span class="adminify-changes-list"> New Module "Redirect URLs" added</span><br>', 'adminify' ),
            __( '<span class="dashicons dashicons-yes"></span> <span class="adminify-changes-list"> Horizontal Menu not working issue fixed </span><br>', 'adminify' ),
            __( '<span class="dashicons dashicons-yes"></span> <span class="adminify-changes-list"> White Label options bugs fixed </span><br>', 'adminify' ),
            __( '<span class="dashicons dashicons-yes"></span> <span class="adminify-changes-list"> Typography options updated </span><br>', 'adminify' ),
            __( '<span class="dashicons dashicons-yes"></span> <span class="adminify-changes-list"> Menu Editor, Folders, Admin Columns Updated </span><br>', 'adminify' )
        );
        printf( '<div data-dismissible="wp-adminify-update-notice-forever" id="wp-adminify-notice-forever" class="wp-adminify-notice updated notice notice-success is-dismissible"><p>%1$s</p></div>', $jltwp_adminify_changelog_message );
    }
    
    public function jltwp_adminify_admin_notice_ask_for_review( $notice_key )
    {
        if ( !self::is_admin_notice_active( $notice_key ) ) {
            return;
        }
        $this->jltwp_adminify_notice_header( $notice_key );
        echo  sprintf(
            __( '<p>Enjoying <strong>%1$s ?</strong></p> <p>Seems like you are enjoying <strong>%1$s</strong>. Would you please show us a little love by rating us on <a href="%2$s" target="_blank" style="background:yellow; padding:2px 5px;">%3$s?</a></p>
            <ul class="wp-adminify-review-ul">
                <li><a href="%2$s" target="_blank" class="button adminify-sure-do-btn is-warning mt-4 upgrade-btn pt-1 pb-1 pr-4 pl-4" style="background-color: transparent; color: #fff;"><span class="dashicons dashicons-external" style="line-height:inherit"></span>Sure! I\'d love to!</a></li>
                <li><a href="#" target="_blank" class="adminify-notice-dismiss button upgrade-btn mt-4 pt-1 pb-1 pr-4 pl-4"><span class="dashicons dashicons-smiley" style="line-height:inherit"></span>I\'ve already left a review</a></li>
                <li><a href="#" target="_blank" class="adminify-notice-dismiss button is-danger upgrade-btn mt-4 pt-1 pb-1 pr-4 pl-4" style="background-color: #f14668 !important; color:#fff !important; border:1px solid #f14668;"><span class="dashicons dashicons-dismiss" style="line-height:inherit"></span>Never show again</a></li>
            </ul>', 'adminify' ),
            WP_ADMINIFY,
            esc_url_raw( 'https://wordpress.org/support/plugin/adminify/reviews/?filter=5' ),
            __( "WordPress.org", 'adminify' )
        ) ;
        $this->jltwp_adminify_notice_footer();
    }
    
    public function jltwp_adminify_admin_upgrade_pro_notice( $notice_key )
    {
        if ( !self::is_admin_notice_active( $notice_key ) ) {
            return;
        }
        $this->jltwp_adminify_notice_header( $notice_key );
        echo  sprintf(
            __( ' <p> %1$s <strong>%2$s</strong> %3$s </p> <p><a class="button upgrade-btn mt-4" href="https://wpadminify.com/pricing" target="_blank">Upgrade Now</a></p>', 'adminify' ),
            __( "Unlock all possiblities - Schedule Dark Mode, hide all admin Notices, Pagespeed Insights, unlock Folders etc.. <br>", 'adminify' ),
            __( '20% Discount on all pricing, enjoy the freedom.<br>', 'adminify' ),
            __( "Coupon Code: <strong style='background:yellow; padding:1px 5px; color: #0347FF;'>ENJOY25</strong>", 'adminify' )
        ) ;
        $this->jltwp_adminify_notice_footer();
    }
    
    // Black Friday & Cyber Monday Offer
    public function jltwp_adminify_admin_black_friday_cyber_monday_notice( $notice_key )
    {
        if ( !self::is_admin_notice_active( $notice_key ) ) {
            return;
        }
        $this->jltwp_adminify_notice_header( $notice_key );
        echo  sprintf(
            __( ' <p> %1$s <strong>%2$s</strong> %3$s </p> <p><a class="button upgrade-btn mt-4" href="https://wpadminify.com/pricing" target="_blank">Upgrade Now</a></p>', 'adminify' ),
            __( "Unlock all possiblities - Schedule Dark Mode, hide all Admin Notices, Pagespeed Insights, unlock Folders etc.. <br>", 'adminify' ),
            __( '50% Huge Discount for <span style="background:#111; padding:2px 10px; color: #fff;">Black Friday and Cyber Monday Deals</span><br>', 'adminify' ),
            __( "Coupon Code: <strong style='background:yellow; padding:2px 10px; color: #0347FF;'>BFCM50</strong>", 'adminify' )
        ) ;
        $this->jltwp_adminify_notice_footer();
    }
    
    public function jltwp_adminify_notice_header( $notice_key )
    {
        ?>
        <div data-dismissible="<?php 
        echo  esc_attr( $notice_key ) ;
        ?>" id="<?php 
        echo  esc_attr( $notice_key ) ;
        ?>" class="wp-adminify-notice adminify-review-notice-banner updated notice notice-success is-dismissible">
            <div id="wp-adminify-bfcm-upgrade-notice" class="wp-adminify-review-notice">
                <div class="wp-adminify-notice-banner">
                    <div class="wp-adminify-notice-contents columns is-tablet is-align-items-center">
                        <ul class="adminify-notice-left-nav column is-2-tablet">
                            <li>
                                <a class="is-flex is-align-items-center" target="_blank" href="https://wpadminify.com/kb">
                                    <i class="is-rounded is-pulled-left mr-2 dashicons dashicons-book"></i>
                                    <?php 
        echo  __( 'Docs', 'adminify' ) ;
        ?>
                                </a>
                            </li>
                            <li>
                                <a class="is-flex is-align-items-center" target="_blank" href="https://demo.wpadminify.com/">
                                    <i class="is-rounded is-pulled-left mr-2 dashicons dashicons-fullscreen-alt"></i>
                                    <?php 
        echo  __( 'Live Demo', 'adminify' ) ;
        ?>
                                </a>
                            </li>
                            <li>
                                <a class="is-flex is-align-items-center" target="_blank" href="https://wpadminify.com/faqs/">
                                    <i class="is-rounded is-pulled-left mr-2 dashicons dashicons-editor-help"></i>
                                    <?php 
        echo  __( 'F.A.Q.', 'adminify' ) ;
        ?>
                                </a>
                            </li>
                            <li>
                                <a class="is-flex is-align-items-center" target="_blank" href="https://wpadminify.com/contact/">
                                    <i class="is-rounded is-pulled-left mr-2 dashicons dashicons-phone"></i>
                                    <?php 
        echo  __( 'Contact Us', 'adminify' ) ;
        ?>
                                </a>
                            </li>
                        </ul>
                        <div class="adminify-notice-middle column is-8-tablet has-text-centered">

                        <?php 
    }
    
    public function jltwp_adminify_notice_footer()
    {
        ?>
                        </div>

                        <div class="adminify-notice-right column is-2-tablet has-text-centered">
                            <ul class="adminify-notice-right-nav">
                                <li>
                                    <a class="adminify-logo" href="https://wpadminify.com/" target="_blank">
                                        <img src="<?php 
        echo  WP_ADMINIFY_ASSETS_IMAGE ;
        ?>/logos/logo-text-dark.svg" alt="WP Adminify">
                                    </a>
                                </li>
                                <li class="adminify-notice-social">
                                    <a class="adminify-notice-social-icon" target="_blank" href="https://www.facebook.com/groups/jeweltheme">
                                        <i class="is-rounded dashicons dashicons-facebook-alt"></i>
                                    </a>
                                    <a class="adminify-notice-social-icon" target="_blank" href="https://www.youtube.com/playlist?list=PLqpMw0NsHXV-EKj9Xm1DMGa6FGniHHly8">
                                        <i class="is-rounded dashicons dashicons-youtube"></i>
                                    </a>
                                    <a class="adminify-notice-social-icon" target="_blank" href="https://twitter.com/jwthemeltd">
                                        <i class="is-rounded dashicons dashicons-twitter"></i>
                                    </a>
                                </li>
                                <li class="adminify-rate-us mt-3">
                                    <div class="adminify-rate-contents">
                                        <label class="adminify-rating-label">Rate us:</label>
                                        <a class="adminify-rating is-inline-block" href="https://wordpress.org/support/plugin/adminify/reviews/?filter=5" target="_blank">
                                            <span class="star">
                                                <i class="dashicons dashicons-star-half"></i>
                                            </span>
                                            <span class="star">
                                                <i class="dashicons dashicons-star-filled"></i>
                                            </span>
                                            <span class="star">
                                                <i class="dashicons dashicons-star-filled"></i>
                                            </span>
                                            <span class="star">
                                                <i class="dashicons dashicons-star-filled"></i>
                                            </span>
                                            <span class="star">
                                                <i class="dashicons dashicons-star-filled"></i>
                                            </span>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php 
    }
    
    public function jltwp_adminify_dismiss_admin_notice()
    {
        $option_name = sanitize_text_field( $_POST['option_name'] );
        $dismissible_length = sanitize_text_field( $_POST['dismissible_length'] );
        
        if ( 'forever' != $dismissible_length ) {
            // If $dismissible_length is not an integer default to 1
            $dismissible_length = ( 0 == absint( $dismissible_length ) ? 1 : $dismissible_length );
            $dismissible_length = strtotime( absint( $dismissible_length ) . ' days' );
        }
        
        check_ajax_referer( 'adminify-notice-nonce', 'notice_nonce' );
        self::set_admin_notice_cache( $option_name, $dismissible_length );
        wp_die();
    }
    
    public static function set_admin_notice_cache( $id, $timeout )
    {
        $cache_key = 'wp-adminify-notice-' . md5( $id );
        update_site_option( $cache_key, $timeout );
        return true;
    }
    
    public static function is_admin_notice_active( $arg )
    {
        $array = explode( '-', $arg );
        $length = array_pop( $array );
        // do not delete it
        $option_name = implode( '-', $array );
        $db_record = self::get_admin_notice_cache( $option_name );
        
        if ( 'forever' === $db_record ) {
            return false;
        } elseif ( absint( $db_record ) >= time() ) {
            return false;
        } else {
            return true;
        }
    
    }
    
    public static function get_admin_notice_cache( $id = false )
    {
        if ( !$id ) {
            return false;
        }
        $cache_key = 'wp-adminify-notice-' . md5( $id );
        $timeout = get_site_option( $cache_key );
        $timeout = ( 'forever' === $timeout ? time() + 45 : $timeout );
        if ( empty($timeout) || time() > $timeout ) {
            return false;
        }
        return $timeout;
    }
    
    public function jltwp_adminify_admin_notice_styles()
    {
        $jltwp_adminify_promo_css = '';
        $jltwp_adminify_promo_css .= '.wp-adminify-review-notice .notice-dismiss{padding:0 0 0 26px}.wp-adminify-notice .adminify-update-head{margin:0}.wp-adminify-notice .adminify-update-head span{font-size:.9em}.wp-adminify-notice .adminify-changes-list{padding-left:.5em}.wp-adminify-review-notice .notice-dismiss:before{display:none}.wp-adminify-review-notice.wp-adminify-review-notice{background-color:#fff;border-radius:3px;border-left:4px solid transparent;display:flex;align-items:center;padding:10px 10px 10px 0}.wp-adminify-review-notice .wp-adminify-review-thumbnail{width:160px;float:left;margin-right:20px;padding-top:20px;text-align:center;border-right:4px solid transparent}.wp-adminify-review-notice .wp-adminify-review-thumbnail img{vertical-align:middle}.wp-adminify-review-notice .wp-adminify-review-text{flex:0 0 1;overflow:hidden}.wp-adminify-review-notice .wp-adminify-review-text h3{font-size:24px;margin:0 0 5px;font-weight:400;line-height:1.3}.wp-adminify-review-notice .wp-adminify-review-text p{margin:0 0 5px}.wp-adminify-review-notice .wp-adminify-review-ul{margin:5px 0 0;padding:0}.wp-adminify-review-notice .wp-adminify-review-ul li{display:inline-block;margin:5px 15px 0 0}.wp-adminify-review-notice .wp-adminify-review-ul li a{display:inline-block;color:#4b00e7;text-decoration:none;padding-top:10px;position:relative}.wp-adminify-review-notice .wp-adminify-review-ul li a:not(.notice-dismiss) span.dashicons{font-size:17px;float:left;height:auto;width:auto;margin-right:3px}.wp-adminify #wpbody-content .wp-adminify-notice.adminify-review-notice-banner{background-color:#0347ff;border-left:0;padding-right:.5rem}.wp-adminify #wpbody-content .adminify-review-notice-banner .wp-adminify-notice-banner{-webkit-box-flex:0;-webkit-flex:0 0 100%;-ms-flex:0 0 100%;flex:0 0 100%}.wp-adminify-notice-banner .columns{margin-top:-2em!important;margin-bottom:-2em!important}.wp-adminify #wpbody-content .adminify-review-notice-banner .wp-adminify-review-notice{background-color:transparent;font-size:15px}.wp-adminify #wpbody-content .adminify-review-notice-banner #wp-adminify-bfcm-upgrade-notice p{color:#fff;font-size:15px}.wp-adminify #wpbody-content .adminify-review-notice-banner .adminify-notice-left-nav{margin:0}.wp-adminify #wpbody-content .adminify-review-notice-banner .adminify-notice-left-nav li{margin-bottom:5px}.wp-adminify #wpbody-content .adminify-review-notice-banner #wp-adminify-bfcm-upgrade-notice .adminify-notice-left-nav a{color:#fff}.wp-adminify #wpbody-content .adminify-review-notice-banner .adminify-notice-left-nav a i{background-color:#fff;color:#0347ff;font-size:20px;height:26px;width:26px;line-height:26px}.wp-adminify #wpbody-content .adminify-review-notice-banner .adminify-notice-middle .upgrade-btn{background-color:#fff;border:1px solid #fff;color:#0347ff;font-size:16px;font-weight:800;border-radius:8px}.wp-adminify #wpbody-content .adminify-review-notice-banner .adminify-notice-middle .upgrade-btn:hover{border:1px solid #fff!important;background:#0347ff!important;color:#fff!important}.wp-adminify #wpbody-content .adminify-review-notice-banner .adminify-notice-middle .upgrade-btn:focus{background-color:#fff}.adminify-review-notice-banner .adminify-logo{display:flex;margin:0 auto 1rem;max-width:135px}.wp-adminify #wpbody-content .adminify-review-notice-banner .adminify-notice-social-icon i{background-color:#fff;height:40px;width:40px;line-height:40px;margin:3px}.adminify-review-notice-banner .adminify-logo{max-width:135px}.wp-adminify #wpbody-content .adminify-review-notice-banner #wp-adminify-bfcm-upgrade-notice .adminify-rate-contents,.wp-adminify #wpbody-content .adminify-review-notice-banner #wp-adminify-bfcm-upgrade-notice .adminify-rate-contents a{color:#fff}.wp-adminify .adminify-review-notice-banner .adminify-rating{direction:rtl}.wp-adminify .adminify-review-notice-banner .adminify-rating label{font-size:0;line-height:0}.wp-adminify .adminify-review-notice-banner .adminify-rate-contents i{font-size:14px;height:auto;width:auto;line-height:0;vertical-align:middle}.adminify-rating input{display:none!important}.adminify-rating:hover span i:before{content:"\\f154"}.adminify-rating span:hover i:before,.adminify-rating span:hover~span i:before{content:"\\f155"}.wp-adminify #wpbody-content .adminify-review-notice-banner .notice-dismiss{border-color:#fff}.wp-adminify #wpbody-content .adminify-review-notice-banner .notice-dismiss:before{color:#fff}.wp-adminify #wpbody-content .adminify-review-notice-banner .adminify-notice-middle .adminify-sure-do-btn:hover{background-color:#00d1b2!important;border-color:transparent!important}';
        $jltwp_adminify_promo_css = preg_replace( '#/\\*.*?\\*/#s', '', $jltwp_adminify_promo_css );
        $jltwp_adminify_promo_css = preg_replace( '/\\s*([{}|:;,])\\s+/', '$1', $jltwp_adminify_promo_css );
        $jltwp_adminify_promo_css = preg_replace( '/\\s\\s+(.*)/', '$1', $jltwp_adminify_promo_css );
        
        if ( !empty($this->options['admin_ui']) ) {
            wp_add_inline_style( 'wp-adminify-admin', wp_strip_all_tags( $jltwp_adminify_promo_css ) );
        } else {
            wp_add_inline_style( 'wp-adminify-default-ui', wp_strip_all_tags( $jltwp_adminify_promo_css ) );
        }
    
    }
    
    public function get_diff_days( $datetime )
    {
        $date_first = date_create( date( "d-m-Y", $datetime ) );
        $date_second = date_create( date( "d-m-Y" ) );
        $different = date_diff( $date_first, $date_second );
        return $different->format( '%R%a' );
    }
    
    public function jltwp_adminify_review_notice_generator()
    {
        $jltwp_adminify_activation_time = get_option( 'jltwp_adminify_activation_time' );
        $diff_days = $this->get_diff_days( $jltwp_adminify_activation_time );
        if ( $diff_days >= 15 ) {
            $this->jltwp_adminify_admin_notice_ask_for_review( 'wp-adminify-review-15' );
        }
    }
    
    public function jltwp_adminify_upgrade_pro_notice_generator()
    {
        $this->jltwp_adminify_admin_upgrade_pro_notice( 'wp-adminify-review-20' );
    }
    
    public function jltwp_adminify_black_friday_cyber_monday_deals()
    {
        $today = date( "Y-m-d" );
        $start_date = '2021-11-20';
        $expire_date = '2021-12-30';
        $today_time = strtotime( $today );
        $start_time = strtotime( $start_date );
        $expire_time = strtotime( $expire_date );
        if ( $today_time >= $start_time && $today_time <= $expire_time ) {
            $this->jltwp_adminify_admin_black_friday_cyber_monday_notice( 'wp-adminify-bfcm-2021' );
        }
    }

}