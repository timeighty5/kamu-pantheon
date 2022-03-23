<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/* Register Custom Post Types */
     
            add_action( 'init', 'stp_create_post_type' );
            function stp_create_post_type() {
                    register_post_type( 'streamcast',
                            array(
                                    'labels' => array(
                                            'name' => __( 'StreamCast'),
                                            'singular_name' => __( 'StreamCast' ),
                                            'add_new' => __( 'Add New Radio Player' ),
                                            'add_new_item' => __( 'Add New Radio' ),
                                            'edit_item' => __( 'Edit Radio' ),
                                            'new_item' => __( 'New Radio' ),
                                            'view_item' => __( 'View Portfolio' ),
											'search_items'       => __( 'Search Portfolio'),
                                            'not_found' => __( 'Sorry, we couldn\'t find the Portfolio you are looking for.' )
                                    ),
                            'public' => false,
							'show_ui' => true, 									
                            'publicly_queryable' => true,
                            'exclude_from_search' => true,
                            'menu_position' => 14,
							'menu_icon' =>'dashicons-microphone',
                            'has_archive' => false,
                            'hierarchical' => false,
                            'capability_type' => 'page',
                            'rewrite' => array( 'slug' => 'behance' ),
                            'supports' => array( 'title' )
                            )
                    );
            }	
			

//Remove post update massage and link 
function stp_updated_messages( $messages ) {
    $messages['streamcast'][1] = __('Updated ');
    return $messages;
}
add_filter('post_updated_messages','stp_updated_messages');

/*-------------------------------------------------------------------------------*/
/*   Hide & Disabled View, Quick Edit and Preview Button
/*-------------------------------------------------------------------------------*/
function stp_remove_row_actions( $idtions ) {
	global $post;
    if( $post->post_type == 'streamcast' ) {
		unset( $idtions['view'] );
		unset( $idtions['inline hide-if-no-js'] );
	}
    return $idtions;
}

if ( is_admin() ) {
	add_filter( 'post_row_actions','stp_remove_row_actions', 10, 2 );}

// HIDE everything in PUBLISH metabox except Move to Trash & PUBLISH button
function stp_hide_publishing_actions(){
        $my_post_type = 'streamcast';
        global $post;
        if($post->post_type == $my_post_type){
            echo '
                <style type="text/css">
                    #misc-publishing-actions,
                    #minor-publishing-actions{
                        display:none;
                    }
                </style>
            ';
        }
}
add_action('admin_head-post.php', 'stp_hide_publishing_actions');
add_action('admin_head-post-new.php', 'stp_hide_publishing_actions');	

// Change publish button to save.
add_filter( 'gettext', 'stp_change_publish_button', 10, 2 );

function stp_change_publish_button( $translation, $text ) {
if ( 'streamcast' == get_post_type())
if ( $text == 'Publish' )
    return 'Save';

return $translation;
}

// column management
add_filter('manage_streamcast_posts_columns', 'stp_columns_head_only_streamcast', 10);
add_action('manage_streamcast_posts_custom_column', 'stp_columns_content_only_streamcast', 10, 2);
 
function stp_columns_head_only_streamcast($defaults) {
    $defaults['directors_name'] = 'ShortCode';
    return $defaults;
}
function stp_columns_content_only_streamcast($column_name, $post_ID) {
    if ($column_name == 'directors_name') {
        // show content of 'directors_name' column
		echo '<input onClick="this.select();" value="[radio_player id='. esc_attr($post_ID) . ']" >';
    }
}

// Add shortcode area

add_action( 'edit_form_after_title', 'stp_shortcode_area' );
function stp_shortcode_area() {
    global $post;
    if ( $post->post_type == 'streamcast' ) {
        ?>
    <style>
        #btss_meta .postbox-header{display:none}.bshortcode{margin-top:30px;border:5px solid #4527a4;overflow:hidden}.shortcode-heading{background:#4527a4;padding:15px;overflow:hidden;color:#fff}.shortcode-heading .icon{float:left;overflow:hidden;width:50%}.shortcode-heading .text{float:right;overflow:hidden;text-align:right}.shortcode-heading .text a{color:#fff;display:block;text-decoration:none}.bshortcode .shortcode-left{width:50%;float:left;overflow:hidden;padding:20px 0 30px;text-align:center;background:#fff;border-right:5px solid #4527a4;box-sizing:border-box}.bshortcode .shortcode-right{width:50%;float:left;overflow:hidden;padding:20px 0 30px;text-align:center;background:#fff}.bshortcode .shortcode{padding:8px 15px;background:#eae6f9;display:inline-block;user-select:all;font-size:16px}
    </style>

    <div class="bshortcode">
        <div class="shortcode-heading">
            <div class="icon"><span class="dashicons dashicons-format-audio"></span> <?php _e( 'Radio Player', 'radio-player' )?></div>
            <div class="text"> <a href="https://bplugins.com/support/" target="_blank"><?php _e( 'Supports', 'radio-player' )?></a></div>
        </div>
        <div class="shortcode-left">
            <h3><?php _e( 'Shortcode', 'radio-player' )?></h3>
            <p><?php _e( 'Copy and paste this shortcode into your posts or pages or widget content:', 'radio-player' )?></p>
            <div class="shortcode" selectable>[radio_player id='<?php echo esc_attr($post->ID); ?>']</div>
        </div>
        <div class="shortcode-right">
            <h3><?php _e( 'Template Include', 'radio-player' )?></h3>
            <p><?php _e( 'Copy and paste the PHP code into your template file:', 'radio-player' )?></p>
            <div class="shortcode">&lt;?php echo do_shortcode('[radio_player id="<?php echo esc_attr($post->ID); ?>"]');
            ?&gt;</div>
        </div>
    </div>
 <?php
}}