<?php
/**
 * Template Name: Podcast
 * Template Post Type: post, podcast 
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package KAMU
 */

get_header(); ?>

	<main id="main" class="container site-main">
		<div class="container">
			<div class="podcast-post-auxinfo">
				<?php
					$posttags = get_the_tags();
					if ($posttags) {
						foreach($posttags as $tag) {
							// echo $tag->name . ' '; 
							$tagLink = "<a href='/radio/radio-programs/" . $tag->slug . "'>" . $tag->name . "</a>";
							echo $tagLink;	
						}
					}
					while ( have_posts() ) :
						the_date();
			echo "</div>";
					the_post();
					get_template_part( 'template-parts/content', get_post_format() );
					wds_kamu_display_comments();
				endwhile; // End of the loop.
			?>
			
		</div>
	</main><!-- #main -->


<?php get_footer(); ?>
