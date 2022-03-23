<?php
/**
 * Template Name: Sidebar Right
 *
 * This template displays a page with a sidebar on the right side of the screen.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package KAMU
 */

get_header(); ?>

	<div class="container site-main">
		<main id="main" class="content-container bootstrap-wrapper">

			<?php
			while ( have_posts() ) :
				the_post();

				get_template_part( 'template-parts/content', 'page' );

				wds_kamu_display_comments();

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->

		<?php get_sidebar(); ?>
	</div>

<?php get_footer(); ?>
