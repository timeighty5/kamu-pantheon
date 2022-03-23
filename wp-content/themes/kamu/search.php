<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package KAMU
 */

get_header(); ?>
	<main id="main" class="container site-main bootstrap-wrapper">

		<?php
		if ( have_posts() ) : ?>
			<header class="entry-header">
				<div class="inner-page-header">
					<div class="container">
						<h1 class="entry-title">Search Results</h1>
						<em>showing results for: <b><?php echo get_search_query(); ?></b></em>
					</div>
				</div>	
			</header>
			<div class="entry-content">
				<div class="section-wrapper">
					<div class="container">
					<?php get_search_form(); ?>

					<?php
					/* Start the Loop */
					while ( have_posts() ) :
						the_post();

						/**
						 * Include the Post-Format-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part( 'template-parts/content-search', get_post_format() );

					endwhile;

					wds_kamu_display_numeric_pagination();
					?>	
					</div>
				</div>
			</div>
		<?php else : ?>
			<header class="entry-header">
				<div class="inner-page-header">
					<div class="container">
						<h1 class="entry-title">Nothing Found</h1>
						<em>for: <b><?php echo get_search_query(); ?></b></em>
					</div>
				</div>	
			</header>
			<div class="entry-content">
				<div class="section-wrapper">
					<div class="container">

					<?php
						get_search_form();
						get_template_part( 'template-parts/content', 'none' );
					?>
					</div>
				</div>
			</div>
		<?php	endif;	?>
	</main><!-- #main -->
	

<?php get_footer(); ?>
