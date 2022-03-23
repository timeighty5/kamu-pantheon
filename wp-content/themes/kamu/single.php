<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package KAMU
 */

get_header(); ?>

	<main id="main" class="container site-main station-news">
		<div class="station-news-hero"><?php the_post_thumbnail('large'); ?></div>
		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', get_post_format() );

			wds_kamu_display_comments();

		endwhile; // End of the loop.
		?>

		<?php
   $args = array(
		 'post_type' => 'post',
      'post_status' => 'publish',
			'category_name' => 'station-news',
			'posts_per_page' => 3,
			'orderby' => 'date',
    	'order' => 'DESC'
      );
   $stationNews = new WP_Query($args);
?>

<?php if($stationNews->have_posts()) : ?>

<div class="recent-news">
		<h2>KAMU Station News</h2>
<div class="recent-news-group">
   <?php while($stationNews->have_posts()) : $stationNews->the_post() ?>
      <div class='recent-news-item'>
				 <?php $thumbnail_id = get_post_thumbnail_id( $post->ID );
		      $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true); ?>
		     <a href="<?php the_permalink(); ?>"><img src="<?php the_post_thumbnail_url(); ?>" alt="<?php echo $alt; ?>" /></a>
					<a href="<?php the_permalink(); ?>"> <?php the_title(); ?></a>
      </div>

   <?php endwhile ?>
		</div>
	<p class="text-center">
		 <a class="btn btn-primary link-arrow recent-news-readmore" href="/category/station-news/">Read More Stories</a>
			</p>

</div>






<?php endif ?>

	</main><!-- #main -->

<?php get_footer(); ?>
