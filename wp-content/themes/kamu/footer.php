<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package KAMU
 */

?>

	<footer class="site-footer">
		
		<div class="cta-wrapper">
			<div class="form-wrapper">
				<h3 class="cta-headline">Subscribe To Our Newsletter</h3>
				<? echo do_shortcode('[wpforms id="191" title="false"]'); ?>
			</div>
		</div>
		
		<div id="footer-columns"> 
			
			<div id="footer-logo" class="footer-column">
			
				<?php dynamic_sidebar( 'footer1' ); ?>
				
			</div>
			
			<div id="footer-links" class="footer-column">
			
				<?php dynamic_sidebar( 'footer2' ); ?>
				
			</div>
			
			<div id="footer-contact-info" class="footer-column">
			
				<?php dynamic_sidebar( 'footer3' ); ?>
			
			</div>
			
		</div>

		<nav id="site-footer-navigation" class="footer-navigation navigation-menu" aria-label="<?php esc_attr_e( 'Footer Navigation', 'kamu' ); ?>">
			<?php
			wp_nav_menu(
				[
					'fallback_cb'    => false,
					'theme_location' => 'footer',
					'menu_id'        => 'footer-menu',
					'menu_class'     => 'menu container',
					'container'      => false,
					'depth'          => 1,
				]
			);
			?>
		</nav><!-- #site-navigation-->


		<div class="container site-info">
			<?php wds_kamu_display_copyright_text(); ?>
			<?php //wds_kamu_display_social_network_links(); ?>
		</div><!-- .site-info -->

	</footer><!-- .site-footer container-->

	<?php wds_kamu_display_mobile_menu(); ?>
	<?php wp_footer(); ?>

</body>

</html>
