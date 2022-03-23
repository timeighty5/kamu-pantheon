<?php
/**
 * KAMU functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package KAMU
 */

/**
 * Get all the include files for the theme.
 *
 * @author WebDevStudios
 */
function wds_kamu_get_theme_include_files() {
	return [
		'inc/setup.php', // Theme set up. Should be included first.
		'inc/compat.php', // Backwards Compatibility.
		'inc/customizer/customizer.php', // Customizer additions.
		'inc/extras.php', // Custom functions that act independently of the theme templates.
		'inc/hooks.php', // Load custom filters and hooks.
		'inc/security.php', // WordPress hardening.
		'inc/scaffolding.php', // Scaffolding.
		'inc/scripts.php', // Load styles and scripts.
		'inc/template-tags.php', // Custom template tags for this theme.
	];
}


foreach ( wds_kamu_get_theme_include_files() as $include ) {
	require trailingslashit( get_template_directory() ) . $include;
}


/* Enable the excerpt on Pages */
//add_post_type_support( 'page', 'excerpt' );
/**
 * Page excerpt support
 *
 * @return void
 */
function theme_add_post_type_support() {
    
    add_post_type_support( 'page', 'excerpt' );

}
add_action( 'init', 'theme_add_post_type_support' );


/* Adjust length of the excerpt */
function trim_custom_excerpt($excerpt) {
    if (has_excerpt()) {
        $excerpt = wp_trim_words(get_the_excerpt(), apply_filters('excerpt_length', 25));
    }

    return $excerpt;
}
add_filter('the_excerpt', 'trim_custom_excerpt', 999);



/* Load Theme Stylesheet */
function enqueue_theme_styles() {
	/* Bootstrap Grid Stylesheet */	
	wp_enqueue_style( 'bootstrap-grid', get_template_directory_uri().'/src/css/bootstrap-grid.min.css' );
	
	/* Slick Slider */	
	wp_enqueue_style( 'slick-slider', get_template_directory_uri().'/src/css/slick.css' );
	
	/* jQuery UI */	
	wp_enqueue_style( 'jquery-ui', get_template_directory_uri().'/src/css/jquery-ui.min.css' );
		
	/* Theme Stylesheet */	
	wp_enqueue_style( 'kamu-style', get_template_directory_uri().'/style.css' );
	
	/* KAMU Team Theme Stylesheet */	
	wp_enqueue_style( 'kamu-team-style', get_template_directory_uri().'/src/css/kamu-team-style.css' );
	
	/* Ryan Chadek Theme Stylesheet */	
	wp_enqueue_style( 'rchadek-style', get_template_directory_uri().'/src/css/rchadek-style.css' );
	
}
add_action( 'wp_enqueue_scripts', 'enqueue_theme_styles', PHP_INT_MAX);

/* Load Custom Scripts */
function enqueue_child_theme_scripts() {			
	/* Custom Scripts */	
	wp_register_script('kamu-custom', get_stylesheet_directory_uri().'/src/js/kamu-custom.js', array( 'jquery' ), '1.0' );
	wp_enqueue_script('kamu-custom');
	
	/* Slick Slider Scripts */	
	wp_register_script('slick-slider', get_stylesheet_directory_uri().'/src/js/slick.min.js', array( 'jquery' ), '1.0' );
	wp_enqueue_script('slick-slider');
	
	/* jQuery UI Scripts */	
	wp_register_script('jquery-ui', get_stylesheet_directory_uri().'/src/js/jquery-ui.min.js', array( 'jquery' ), '1.0' );
	wp_enqueue_script('jquery-ui');	
}
add_action( 'wp_enqueue_scripts', 'enqueue_child_theme_scripts');


function preconnect_google_fonts() { 
		
	echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
	echo "\n";
	echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
	
}
add_action('preload_fonts', 'preconnect_google_fonts');


function load_font_awesome() { 
	
	/* FontAwesome */
	wp_register_script('font-awesome', 'https://kit.fontawesome.com/ae0f735ef2.js', array(), '1.0' );
	wp_enqueue_script('font-awesome');
	
}
add_action('preload_fonts', 'load_font_awesome');


function font_awesome_atts( $tag, $handle, $src ) {
		
	if('font-awesome' === $handle) {
		$tag = '<script type="text/javascript" src="'. esc_url($src) .'" id="'. $handle .'" crossorigin="anonymous"></script>';
		$tag .= "\n";
	}
	
	return $tag;
}
//add_filter('script_loader_tag', 'font_awesome_atts', 5, 3);


function kamu_add_google_fonts() {
	wp_enqueue_style( 'kamu-google-fonts', 'https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;500&display=swap', false ); 
}
add_action( 'wp_enqueue_scripts', 'kamu_add_google_fonts');


/* Register Extra Menus */
function register_extra_menus() {
  register_nav_menu( 'secondary', __( 'Secondary Menu', 'theme-slug' ) );
}
add_action( 'after_setup_theme', 'register_extra_menus' );


/* Allow Shortcodes in Widgets */
add_filter( 'widget_text', 'do_shortcode' );


/**
 * WP Forms: Show values in Dropdown, checkboxes, and Multiple Choice.
 *
 * @link https://wpforms.com/developers/add-field-values-for-dropdown-checkboxes-and-multiple-choice-fields/
 *
 */
add_action( 'wpforms_fields_show_options_setting', '__return_true' );



/* Register Footer Widgets */
register_sidebar( array(
	'name'          => esc_html__( 'Footer Column 1', 'kamu' ),
	'id'            => 'footer1',
	'before_widget' => '<section id="%1$s" class="widget %2$s footer-column">',
	'after_widget'  => '</section>',
	'before_title'  => '<h2 class="widget-title">',
	'after_title'   => '</h2>',
    ) );

register_sidebar( array(
	'name'          => esc_html__( 'Footer Column 2', 'kamu' ),
	'id'            => 'footer2',
	'before_widget' => '<section id="%1$s" class="widget %2$s footer-column">',
	'after_widget'  => '</section>',
	'before_title'  => '<h2 class="widget-title">',
	'after_title'   => '</h2>',
    ) );

register_sidebar( array(
	'name'          => esc_html__( 'Footer Column 3', 'kamu' ),
	'id'            => 'footer3',
	'before_widget' => '<section id="%1$s" class="widget %2$s footer-column">',
	'after_widget'  => '</section>',
	'before_title'  => '<h2 class="widget-title">',
	'after_title'   => '</h2>',
    ) );


/* ACF - Options Page */
if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> 'Theme General Settings',
		'menu_title'	=> 'Theme Settings',
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
	
	acf_add_options_page(array(
		'page_title' 	=> 'Main Menu Ads',
		'menu_title'	=> 'Main Menu Ads Settings',
		'menu_slug' 	=> 'main-menu-ads-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
			
}

/* Load mobile menu JS file */
function wdm_mm_toggle_scripts() {
    wp_enqueue_script( 'wdm-mm-toggle', get_stylesheet_directory_uri() . '/src/js/template-tags/mobile-menu.js' );
}
add_action( 'wp_enqueue_scripts', 'wdm_mm_toggle_scripts' );


/**
 * Change the pre-loader icon
 *
 * @link https://wpforms.com/developers/how-to-change-the-pre-loader-icon-on-submit/
 *
 */ 
function custom_wpforms_display_submit_spinner_src( $src, $form_data ) {
  
    // Enter the URL to the loading image in between the single quotes
    return '';
 
}
add_filter( 'wpforms_display_submit_spinner_src', 'custom_wpforms_display_submit_spinner_src', 10, 2 );



/* Main Menu Ads */
function main_menu_ads() {
	
	$output = '';
	
	if( have_rows('main_menu_ads', 'option') ) {

		// Loop through rows.
		while( have_rows('main_menu_ads', 'option') ) : the_row();

			// Load sub field values.
			$header_script = get_sub_field('header_script');

			$output .= $header_script;

		// End loop.
		endwhile;
	}
	
	echo $output;
}
add_action( 'wp_head', 'main_menu_ads');


function output_ad_placements() {

	$output = '<div id="ad-placements">';
		
	if( have_rows('main_menu_ads', 'option') ) {
		
		// Loop through rows.
		while( have_rows('main_menu_ads', 'option') ) : the_row();
	
			$output .= '<div class="ad-placement">' . get_sub_field('ad_placement') . '</div>';	
		
		// End loop.
		endwhile;
	
	}
	
	$output .= '</div>';
	
	echo $output;
	
}
add_filter('wp_footer', 'output_ad_placements');


/**
 * WP Forms - Turn off the email suggestion.
 *
 * @link  https://wpforms.com/developers/how-to-disable-the-email-suggestion-on-the-email-form-field/
 */
add_filter( 'wpforms_mailcheck_enabled', '__return_false' );


/* Filter Custom Logo */
// Filter the output of logo to remove alt text and address Googles Error about itemprop logo
function wecodeart_com() {
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    $html = sprintf( '<a href="%1$s" class="custom-logo-link" title="KAMU PBS NPR" rel="home" itemprop="url">%2$s</a>',
            esc_url( home_url( '/' ) ),
            wp_get_attachment_image( $custom_logo_id, 'full', false, array(
                'class'    => 'custom-logo',
				'alt'      => 'KAMU PBS NPR',
            ) )
        );
    return $html;   
}
add_filter( 'get_custom_logo', 'wecodeart_com' );



// SHORTCODES
function social_links_func() {
	
	$social_links = '';
	
	if( have_rows('social_links', 'option') ) {
		
		$social_links = '<ul class="social-links">';
		
		// Loop through rows.
		while( have_rows('social_links', 'option') ) : the_row();
						
			$social_channel = get_sub_field('social_profile_channel');
			$social_profile_url = get_sub_field('social_profile_url');
		
			switch($social_channel) {
				case 'Instagram':
					$icon_class = 'fa-instagram';
					break;
				case 'Facebook':
					$icon_class = 'fa-facebook';
					break;
				case 'Twitter':
					$icon_class = 'fa-twitter';
					break;
				case 'YouTube':
					$icon_class = 'fa-youtube';
					break;
				default:
					break;
			}
		
			$social_links .= '<li><a href="'. $social_profile_url .'" target="_blank" rel="noopener" title="'. $social_channel .'">';
			$social_links .= '<span class="fab '. $icon_class .'"></span>';
			$social_links .= '</a></li>';

		
		// End loop.
		endwhile;
		
		$social_links .= '</ul>';
	}
		
	return $social_links;
}
add_shortcode('social_links', 'social_links_func');


function button_func($atts) {
	
	$button = '';
	
	$attributes = shortcode_atts( 
		array(
			'text' => 'Learn More',
			'href' => '#',
			'target' => '_self',
			'class' => 'btn-primary',
			'title' => '',
			'show_arrows' => 'yes'
			), $atts );
	
	if($attributes['show_arrows'] == 'yes') {
		$link_arrows = 'link-arrow';
	}
	else {
		$link_arrows = '';
	}
	
	$button .= '<a class="btn '. esc_attr( $attributes['class'] ) .' '. $link_arrows .'" href="'. esc_url( $attributes['href']) .'" target="'. esc_attr( $attributes['target'] ) .'" title="'.  esc_attr( $attributes['title'] ) .'">'. esc_attr( $attributes['text'] ) .'</a>';
	
	return $button;
	
}
add_shortcode('button', 'button_func');


function cta_func($atts) {
	
	$attributes = shortcode_atts( 
		array(
			'headline' => '',
			'text' => '',
			'form_id' => '',
			'button_color' => 'primary'
			), $atts );
	
	$cta = '<div class="cta-wrapper">';
	$cta .= '<div class="form-wrapper btn-'. esc_attr( $attributes['button_color'] ) .'">';
	
	$cta .= '<h3 class="cta-headline">'. esc_attr( $attributes['headline'] ) .'</h3>';
	$cta .= '<p class="cta-text">'. esc_attr( $attributes['text'] ) .'</p>';
	$cta .= do_shortcode('[wpforms id="'. esc_attr( $attributes['form_id'] ) .'" title="false"]');
	
	$cta .= '</div>';
	$cta .= '</div>';
	
	return $cta;
	
}
add_shortcode('cta', 'cta_func');


/* Community Calendar */
function display_community_calendar() {
	
	$community_calendar .= '<div class="community-calendar-wrapper">';
	
	$community_calendar .= get_field('community_calendar', 'option');
	
	$community_calendar .= '</div>';
				
	return $community_calendar;
}
add_shortcode('community_calendar', 'display_community_calendar');


/* Body Ad Placement */
function body_ad_placement_func() {
	
	$output = '';
	
	$body_ad_script = get_field('body_ad_script_placement');
			
	if($body_ad_script) {
		$output = '<div class="body-ad-placement">' . $body_ad_script . '</div>';
	}
	
	return $output;
	
}
add_shortcode('body_ad_placement', 'body_ad_placement_func');


/* Programs A-Z */
function programs_az_func($atts) {
	
	$attributes = shortcode_atts( 
		array(
			'type' => 'tv',
			), $atts );
	
	$output = get_programs($attributes['type']);
	
	return $output;
	
}
add_shortcode('programs_az', 'programs_az_func');


function get_programs($type) {
		
	$current_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	
	$base_url = explode('?', $current_url);
	
	$arr = get_url_parameters();
	
	// Check for filters or seach terms in the URL
	$filter_char = '';
	$search = '';
	
	if($arr['filter']) {
		$filter_char = $arr['filter'];
	}
	
	if($arr['search']) {
		$search_term = $arr['search'];
		
		// Format search term from URL parameter
		$search_term = str_replace('%20', ' ', $search_term);
	}
	
	switch($type) {
		case 'tv':
			$post_type = 'tv_program';
			$form_id = '598';
			break;
		case 'radio':
			$post_type = 'radio_program';
			$form_id = '605';
			break;
		case 'kids':
			$post_type = 'kids_program';
			$form_id = '1087';
			break;
		default:
			$post_type = 'tv_program';
			$form_id = '598';
			break;
	}
	
	$output = '';
	
	$output .= '<div class="az-wrapper">';
	$output .= '<div class="alphabet-container">';
	$output .= '<ul>';
	
	$output .= '<li><a href="'. $base_url[0] .'" class="letter-link show-all">Show All</a></li>';
	
	$output .= '<li><a href="'. $base_url[0] .'?filter=number" class="letter-link number">#</a></li>';

	foreach (range('A', 'Z') as $char) {
		
    	$output .= '<li><a href="'. $base_url[0] .'?filter='. $char .'" class="letter-link">' . $char . '</a></li>';
	}
	
	$output .= '</ul>';
	$output .= '</div>';
	
	$output .= '<div class="az-search-container">';
	$output .= do_shortcode('[wpforms id="'. $form_id .'"]');
	$output .= '</div>';
	
	$output .= '</div>';
		
	// Show results message
	if($filter_char || $search_term ) {
				
		if($filter_char === 'number') {
			$filter_term = 'a ' . $filter_char;
		}
		else {
			$filter_term = '"'. $filter_char .'"';
		}
		
		$output .= '<div class="az-search-results">';
		$output .= '<span>showing programs ';
		
		$output .= $filter_char ? 'that start with '. $filter_term : 'with the term "'. $search_term .'"'; 
		$output .= '</span>';
		$output .= '</div>';
		
	}
			
	$args = array(
		'post_type'      => $post_type,
		'posts_per_page' => -1,
		'orderby'        => 'title',
		'order'          => 'ASC',
		'starts_with'    => $filter_char,
		's'              => $search_term,
	);
	
	$query = new WP_Query($args);
	
	if($query->have_posts()) {
				
		$ctr = 0;
				
		while($query->have_posts()) : $query->the_post();
		
			if($ctr === 0) {
				$output .= '<div class="container programs-az">';	
			}
		
			if($type === 'radio') {
				$link_field = 'radio_program_link';
			}
			elseif($type === 'kids') {
				$link_field = 'kids_program_link';
			}
			else {
				$link_field = 'tv_program_link';
			}
		
			$program_link = get_field($link_field);	
		
			$program_thumbnail = '<a href="'. $program_link .'" target="_blank" rel="noopener" tabindex="-1">'. get_the_post_thumbnail() . '</a>';
			$program_title = '<h3><a href="'. $program_link .'" target="_blank" rel="noopener">'. get_the_title() .'</a></h3>';
			$program_learn_more = '<p><a href="'. $program_link .'" target="_blank" rel="noopener" tabindex="-1">'. get_the_title() .'</a></p>';
		
			$output .= '<div class="section-column col-md-4 col-12 sm-space-top column-block program">';
		
				$output .= '<div class="program-image">';
				$output .= $program_thumbnail;
				$output .= '</div>';

				$output .= '<div class="program-title">';
				$output .= $program_title;
				$output .= '</div>';

				$output .= '<div class="program-description">';
				$output .= '<p>' . wp_trim_words( get_the_content(), 20, '...' ) . '</p>';
				$output .= '</div>';

				$output .= '<div class="program-link">';
				$output .= $program_learn_more;
				$output .= '</div>';
		
			$output .= '</div>';
		
			$ctr++;
		
			if($ctr === 3) {
				$ctr = 0;
			} 
		
			if($ctr === 0) {
				$output .= '</div>';	
			}
			
		endwhile;
		
	}	
	
	return $output;
}


function get_url_parameters() {
	
	// Read URL parameters
	$url = $_SERVER['REQUEST_URI'];
		
	$url_parameters = parse_url($url, PHP_URL_QUERY);
			
	$query = explode('&', $url_parameters);
	
	foreach ($query as $i) {
		
		list($name, $value) = explode('=', $i, 2);
				
		# if name already exists
		if( isset($arr[$name]) ) {
		  # stick multiple values into an array
		  if( is_array($arr[$name]) ) {
			$arr[$name][] = $value;
		  }
		  else {
			$arr[$name] = array($arr[$name], $value);
		  }
		}
		# otherwise, simply stick it in a scalar
		else {
		  $arr[$name] = $value;
		}
	}
	
	return $arr;
}

/* Enable the ability to use 'starts_with' in WP Query args */
function get_posts_where( $where, $query ) {
	
    global $wpdb;

    $starts_with = esc_sql( $query->get( 'starts_with' ) );

    if ( $starts_with ) {
		
		if( $starts_with === 'number' ) {
			$where .= " AND $wpdb->posts.post_title REGEXP '^[0-9]'";
		}
		else {
			$where .= " AND $wpdb->posts.post_title LIKE '$starts_with%'";	
		}
        
    }

    return $where;
}
add_filter( 'posts_where', 'get_posts_where', 10, 2 );

/* Move Yoast to Bottom of Pages */

add_filter( 'wpseo_metabox_prio', function() { return 'low'; } );


