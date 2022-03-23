<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package KAMU
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>

	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	
	<?php do_action('preload_fonts'); ?>
	
	<?php 
		
		$header_ad_script = get_field('header_ad_script_placement');
	
		if($header_ad_script) {
			echo $header_ad_script;
		}
	
	?>
	
	<?php wp_head(); ?>

</head>

<body <?php body_class( 'site-wrapper' ); ?>>

	<?php wp_body_open(); ?>

	<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'kamu' ); ?></a>

	<header class="site-header">

		<div class="container">

			<div class="site-branding">

				<?php the_custom_logo(); ?>

				<?php if ( is_front_page() && is_home() ) : ?>
					<h1 class="site-title screen-reader-text"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php else : ?>
					<p class="site-title screen-reader-text"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
				<?php endif; ?>

				<?php

				$description = get_bloginfo( 'description', 'display' );
				if ( $description || is_customize_preview() ) :
					?>
					<p class="site-description screen-reader-text"><?php echo esc_html( $description ); ?></p>
				<?php endif; ?>

			</div><!-- .site-branding -->

			<?php if ( has_nav_menu( 'primary' ) || has_nav_menu( 'mobile' ) ) : ?>
				<button type="button" class="off-canvas-open" aria-expanded="false" aria-label="<?php esc_attr_e( 'Open Menu', 'kamu' ); ?>"></button>
			<?php endif; ?>

		</div><!-- .container -->

		<nav id="site-navigation" class="main-navigation navigation-menu" aria-label="<?php esc_attr_e( 'Main Navigation', 'kamu' ); ?>">
			<?php
			wp_nav_menu(
				[
					'fallback_cb'    => false,
					'theme_location' => 'primary',
					'menu_id'        => 'primary-menu',
					'menu_class'     => 'menu dropdown container',
					'container'      => false,
				]
			);
			?>
		</nav><!-- #site-navigation-->

	</header><!-- .site-header-->
	
	<div id="secondary-menu-wrapper">
		
		<div class="container">
			
			<div class="secondary-menu">
				
				<nav id="secondary-site-navigation" class="secondary-navigation navigation-menu" aria-label="<?php esc_attr_e( 'Secondary Navigation', 'kamu' ); ?>">
					<?php
					wp_nav_menu(
						[
							'fallback_cb'    => false,
							'theme_location' => 'secondary',
							'menu_id'        => 'secondary-menu',
							'menu_class'     => 'menu dropdown container',
							'container'      => false,
						]
					);
					?>
				</nav><!-- #secondary site-navigation-->
				
				<?php get_search_form(); ?>
				
			</div>
			
		</div>
	
	</div>
