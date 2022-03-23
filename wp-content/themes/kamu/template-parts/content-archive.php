<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package KAMU
 */

?>

	<article <?php post_class( 'post-container' ); ?>>
		<aside>
	<?php $thumbnail_id = get_post_thumbnail_id( $post->ID );
		      $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true); ?>
		     <a href="<?php the_permalink(); ?>"><img src="<?php the_post_thumbnail_url(); ?>" alt="<?php echo $alt; ?>" /></a>
		</aside>
		<section>
		<header class="entry-header">
						
			<?php
			$page_title = get_field('page_title');
			$h1_open_tag = '<h2 class="">';
			$h1_close_tag = '</h2>';
			
			$page_header = get_field('page_header');
			
			if($page_header) {
				
				$header_bg_img = get_field('page_header_background'); 
			
				?>
			
				<div class="top-level-page-header">
				
					<div class="page-header-bg" style="background-image: url(' <? echo $header_bg_img['url']; ?> ')">

						<div class="container">

							<? 
							echo $h1_open_tag;

							if($page_title) {
								echo $page_title;
							}
							else {
								the_title();
							} 
							echo $h1_close_tag;
							?>

						</div>

					</div>
						
				</div>
			
				<?
			}
			else {
				
				?>
				
				<div class="inner-page-header">
							
					<?

					$image_above_page_title = get_field('image_above_page_title');

					if($image_above_page_title) { ?>
						<div class="container"><img src="<? echo $image_above_page_title['url']; ?>" class="image-above-page-title" alt="" /></div>
					<?
					}

					echo '<div class="container">' . $h1_open_tag;
				
					if(is_page('airdates')) {
						
						$show_title = $_GET['show'];
						
						$show_title = str_replace('-', ' ', $show_title);
						
						echo $show_title;
					}
					elseif($page_title) {
						echo $page_title;
					}
					else {
						the_title();
					} 
					echo $h1_close_tag . '</div>';

					?>
					
				</div>	
			
				<?
			}	
			
			?>
			
		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php  
			echo '<p>';the_excerpt();echo '</p>';
			
			if(has_excerpt()) {
			
			echo '<a href="';the_permalink();echo'" title="Read More about ';the_title();echo'" class="more-link">Read More</a>';
				}
			else {
			}
			?>
	

		</div><!-- .entry-content -->
	</section>
	</article><!-- #post-## -->
