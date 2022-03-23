<?php

/*
 * Plugin Name: StreamCast
 * Plugin URI:  https://wordpress.org/plugins/streamcast
 * Description: Play iceCast, Shoutcast, Radioco, Radionomy Live stream in Wordpress.
 * Version: 2.1.2
 * Author: bPlugins
 * Author URI: http://bPlugins.com
 * License: GPLv3
 * Text Domain:  streamcast
 * Domain Path:  /languages
 */

if ( !function_exists( 'str_fs' ) ) {
    // Create a helper function for easy SDK access.
    function str_fs()
    {
        global  $str_fs ;
        
        if ( !isset( $str_fs ) ) {
            // Activate multisite network integration.
            if ( !defined( 'WP_FS__PRODUCT_6433_MULTISITE' ) ) {
                define( 'WP_FS__PRODUCT_6433_MULTISITE', true );
            }
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $str_fs = fs_dynamic_init( array(
                'id'             => '6433',
                'slug'           => 'streamcast',
                'type'           => 'plugin',
                'public_key'     => 'pk_a19d159db561c020210345da466f1',
                'is_premium'     => false,
                'premium_suffix' => 'Pro',
                'has_addons'     => false,
                'has_paid_plans' => true,
                'trial'          => array(
                'days'               => 7,
                'is_require_payment' => true,
            ),
                'menu'           => array(
                'slug'       => 'edit.php?post_type=streamcast',
                'first-path' => 'edit.php?post_type=streamcast&page=help',
                'support'    => false,
            ),
                'is_live'        => true,
            ) );
        }
        
        return $str_fs;
    }
    
    // Init Freemius.
    str_fs();
    // Signal that SDK was initiated.
    do_action( 'str_fs_loaded' );
}

/*Some Set-up*/
define( 'STP_PLUGIN_DIR', WP_PLUGIN_URL . '/' . plugin_basename( dirname( __FILE__ ) ) . '/' );
define( 'STP_PLUGIN_VERSION', '2.1.2' );
// load custom mimes
require_once 'mimes/enable-mime-type.php';
function stp_load_textdomain()
{
    load_plugin_textdomain( 'streamcast', false, dirname( __FILE__ ) . "/languages" );
}

add_action( "plugins_loaded", 'stp_load_textdomain' );
//Script and style
function stp_style_and_scripts()
{
    wp_enqueue_style(
        'stp-style',
        plugin_dir_url( __FILE__ ) . 'public/css/radio.css',
        array(),
        STP_PLUGIN_VERSION,
        'all'
    );
    wp_enqueue_style(
        'stp-player-style',
        plugin_dir_url( __FILE__ ) . 'public/css/styles.css',
        array(),
        STP_PLUGIN_VERSION,
        'all'
    );
    wp_enqueue_script(
        'ultimate-script',
        plugin_dir_url( __FILE__ ) . 'public/js/ultimate.js',
        array( 'jquery' ),
        STP_PLUGIN_VERSION,
        true
    );
    wp_enqueue_script(
        'stp-script',
        STP_PLUGIN_DIR . 'public/js/streamcast-final.js',
        array( 'jquery' ),
        STP_PLUGIN_VERSION,
        false
    );
}

add_action( 'wp_enqueue_scripts', 'stp_style_and_scripts' );
function stp_admin_style_and_scripts( $screen )
{
    if ( 'streamcast_page_help' == $screen ) {
        wp_enqueue_style(
            'stp-admin',
            plugin_dir_url( __FILE__ ) . 'admin/css/admin.css',
            array(),
            STP_PLUGIN_VERSION,
            'all'
        );
    }
}

add_action( 'admin_enqueue_scripts', 'stp_admin_style_and_scripts' );
// Help page and after activation redirect
add_action( 'admin_menu', 'stp_howto_page' );
function stp_howto_page()
{
    add_submenu_page(
        'edit.php?post_type=streamcast',
        'Getting Started',
        'Getting Started',
        'manage_options',
        'help',
        'stp_help_page_callback'
    );
}

function stp_help_page_callback()
{
    include_once 'admin/whats-new.php';
}

// Footer Review Request
add_filter( 'admin_footer_text', 'stp_admin_footer' );
function stp_admin_footer( $text )
{
    
    if ( 'streamcast' == get_post_type() ) {
        $url = 'https://wordpress.org/support/plugin/streamcast/reviews/?filter=5#new-post';
        $text = sprintf( __( 'If you like <strong>StreamCast Radio Player</strong> plugin please leave us a <a href="%s" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating. Your Review is very important to us as it helps us to grow more. ', 'streamcast' ), $url );
    }
    
    return $text;
}

//Common ShortCode
function stp_stream_sc_cb( $atts )
{
    extract( shortcode_atts( array(
        'url'        => null,
        'background' => null,
    ), $atts ) );
    ob_start();
    ?>

	<div style="width:200px;">
		<audio id="player" controls>
			<source src="<?php 
    echo  esc_url( $url ) ;
    ?>" type="audio/mp3" />
		</audio>
	</div>
	<style>
		.plyr__control{margin-right:0 !important;}

		.plyr--audio .plyr__controls {
		<?php 
    
    if ( !empty($background) ) {
        echo  'background:' . esc_html( $background ) . '!important;border-radius:3px !important;' ;
    } else {
        echo  'background: transparent!important; border-radius:3px !important;' ;
    }
    
    ?> }

	</style>

<script>
const player = new Plyr('#player', {
    controls: [ 'play',  'mute', 'volume']
});
</script>

<?php 
    $output = ob_get_clean();
    return $output;
    ?>

<?php 
}

add_shortcode( 'stream', 'stp_stream_sc_cb' );
//Live Demo In menu

if ( str_fs()->is_free_plan() ) {
    add_action( 'admin_menu', 'stp_add_custom_link_into_cpt_menu' );
    function stp_add_custom_link_into_cpt_menu()
    {
        global  $submenu ;
        $link = 'http://wpradioplayer.com/';
        $submenu['edit.php?post_type=streamcast'][] = array(
            'PRO Version Demo',
            'manage_options',
            $link,
            'meta' => 'target="_blank"'
        );
    }

}

function stp_my_custom_script()
{
    ?>
    <script type="text/javascript">
        jQuery(document).ready( function($) {
            $( "ul#adminmenu a[href$='https://bplugins.page.link/demo']" ).attr( 'target', '_blank' );
        });
    </script>
    <?php 
}

add_action( 'admin_head', 'stp_my_custom_script' );
/*-------------------------------------------------------------------------------*/
/*   FRAMEWORK + OTHER INC
/*-------------------------------------------------------------------------------*/
require_once 'inc/cpt.php';
require_once 'admin/codestar-framework/codestar-framework.php';

if ( str_fs()->is_free_plan() ) {
    // if(true){
    // free version code
    require_once 'admin/inc/metabox-free.php';
    require_once 'public/shortcode-free.php';
}
