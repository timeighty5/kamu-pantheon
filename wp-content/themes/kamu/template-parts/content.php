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

		<header class="entry-header">
						
			<?php
			$page_title = get_field('page_title');
			$h1_open_tag = '<h1 class="entry-title">';
			$h1_close_tag = '</h1>';
			
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
			
			// Check rows exists.
			if( have_rows('page_section') ):

				// Loop through rows.
				while( have_rows('page_section') ) : the_row();

					// Load sub field values.
					$section_id = get_sub_field('section_id');
					$section_classes = get_sub_field('section_classes');
			
					$bg_image = get_sub_field('section_background_image');
					$bg_size = 'full';
					$bg_src = wp_get_attachment_image_src( $bg_image['id'], $size );
			
					$bg_color = get_sub_field('section_background_color');
			
					$section_text_color = get_sub_field('section_text_color');
			
					$section_custom_text_color = '';
			
					switch($section_text_color) {
						case('Dark'):
							$section_text_color_class = 'section-text-dark';
							break;
						case('Light'):
							$section_text_color_class = 'section-text-light';
							break;
						case('Custom'):
							$section_text_color_class = 'section-text-custom';
							$section_custom_text_color = get_sub_field('section_custom_text_color');
							break;
					}
					
					$section_columns = get_sub_field('section_columns');
				
					switch($section_columns) {
						case 1:
							$section_layout = '1';
							break;
						case 2:
							$section_layout = get_sub_field('section_layout_2_columns');
							break;
						case 3:
							$section_layout = get_sub_field('section_layout_3_columns');
							break;
						case 4:
							$section_layout = '1/4 + 1/4 + 1/4 + 1/4';
							break;
					}
									
					switch($section_layout) {
						case '1':
							$column_1_classes = 'col-12';
							break;
						case '2/3 + 1/3':
							$column_1_classes = 'col-md-8 col-sm-6 col-12';
							$column_2_classes = 'col-md-4 col-sm-6 col-12';
							break;
						case '1/2 + 1/2':
							$column_1_classes = 'col-md-6 col-12';
							$column_2_classes = 'col-md-6 col-12';
							break;
						case '1/3 + 2/3':
							$column_1_classes = 'col-md-4 col-sm-6 col-12';
							$column_2_classes = 'col-md-8 col-sm-6 col-12';
							break;
						case '1/3 + 1/3 + 1/3':
							$column_1_classes = 'col-md-4 col-12';
							$column_2_classes = 'col-md-4 col-12';
							$column_3_classes = 'col-md-4 col-12';
							break;
						case '2/3 + 1/6 + 1/6':
							$column_1_classes = 'col-md-8 col-12';
							$column_2_classes = 'col-md-2 col-sm-6 col-12';
							$column_3_classes = 'col-md-2 col-sm-6 col-12';
							break;
						case '1/6 + 2/3 + 1/6':
							$column_1_classes = 'col-md-2 col-sm-6 col-12';
							$column_2_classes = 'col-md-8 col-12';
							$column_3_classes = 'col-md-2 col-sm-6 col-12';
							break;
						case '1/6 + 1/6 + 2/3':
							$column_1_classes = 'col-md-2 col-sm-6 col-12';
							$column_2_classes = 'col-md-2 col-sm-6 col-12';
							$column_3_classes = 'col-md-8 col-12';
							break;
						case '1/4 + 1/4 + 1/4':
							$column_1_classes = 'col-md-3 col-12';
							$column_2_classes = 'col-md-3 col-12';
							$column_3_classes = 'col-md-3 col-12';
							$column_4_classes = 'col-md-3 col-12';
							break;
						default:
							$column_1_classes = 'col-12';
							break;
					}
			
					if( get_sub_field('section_content_column_1_classes') ) {
								
						$column_1_custom_classes = get_sub_field('section_content_column_1_classes');

						if (strpos($column_1_custom_classes, 'col-') !== false ) {
							$column_1_classes = $column_1_custom_classes;	
						}
						else {
							$column_1_classes .= ' '. $column_1_custom_classes;
						}

					}
			
					if( get_sub_field('section_content_column_2_classes') ) {

						$column_2_custom_classes = get_sub_field('section_content_column_2_classes');

						if (strpos($column_2_custom_classes, 'col-') !== false ) {
							$column_2_classes = $column_2_custom_classes;	
						}
						else {
							$column_2_classes .= ' '. $column_2_custom_classes;
						}

					}
			
					if( get_sub_field('section_content_column_3_classes') ) {

						$column_3_custom_classes = get_sub_field('section_content_column_3_classes');

						if (strpos($column_3_custom_classes, 'col-') !== false ) {
							$column_3_classes = $column_3_custom_classes;	
						}
						else {
							$column_3_classes .= ' '. $column_3_custom_classes;
						}

					}
			
					if( get_sub_field('section_content_column_4_classes') ) {

						$column_4_custom_classes = get_sub_field('section_content_column_4_classes');

						if (strpos($column_4_custom_classes, 'col-') !== false ) {
							$column_4_classes = $column_4_custom_classes;	
						}
						else {
							$column_4_classes .= ' '. $column_4_custom_classes;
						}

					}
								
					?>

					<div id="<? echo $section_id; ?>" class="section-wrapper <? echo $section_text_color_class; ?> <? if($section_classes) { echo $section_classes; } ?>"						 
						 
						 <? if($bg_image || $bg_color || $section_custom_text_color) { 
						 
						 	$style = '';
	
							if($bg_image) {
								$style .= 'background-image: url('. $bg_src[0] .'); ';
							}
							if($bg_color) {
								$style .= 'background-color: '. $bg_color .'; ';
							}
							if($section_custom_text_color) {
								$style .= 'color: '. $section_custom_text_color .'; ';
							}
	
							?> style="<? echo $style; ?>" 
						 <? } ?>
					>
			
						<div class="container">
							
							<div class="section-column <? echo $column_1_classes; ?>">
								
								<? echo get_sub_field('section_content_column_1'); ?>
								
							</div>
							
							<?php 
							
								if($section_columns == 2) { ?>
									
									<div class="section-column <? echo $column_2_classes; ?>">
										
										<? echo get_sub_field('section_content_column_2'); ?>
										
									</div>
							
								<?
								}
							
							?>
							
							<?php 
							
								if($section_columns == 3) { ?>
							
										<div class="section-column <? echo $column_2_classes; ?>">

											<? echo get_sub_field('section_content_column_2'); ?>

										</div>

										<div class="section-column <? echo $column_3_classes; ?>">

											<? echo get_sub_field('section_content_column_3'); ?>

										</div>
							
								<?
								}
							
							?>
							
							<?php 
							
								if($section_columns == 4) { ?>
							
									<div class="section-column <? echo $column_2_classes; ?>">
										
										<? echo get_sub_field('section_content_column_2'); ?>
										
									</div>
									
									<div class="section-column <? echo $column_3_classes; ?>">
										
										<? echo get_sub_field('section_content_column_3'); ?>
										
									</div>
									
									<div class="section-column <? echo $column_4_classes; ?>">
										
										<? echo get_sub_field('section_content_column_4'); ?>
										
									</div>
							
								<?
								}
							
							?>
							
						</div> <!-- end .container -->
						
					</div> <!-- end .section-wrapper -->
					<?
					
					// Do something...

				// End loop.
				endwhile;

			// No value.
			else :
				// Do something...
			endif;
						
			?>
			
			<?php
				the_content(
					sprintf(
						wp_kses(
							/* translators: %s: Name of current post. */
							esc_html__( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'kamu' ),
							[
								'span' => [
									'class' => [],
								],
							]
						),
						the_title( '<span class="screen-reader-text">"', '"</span>', false )
					)
				);

				wp_link_pages(
					[
						'before' => '<div class="page-links">' . esc_attr__( 'Pages:', 'kamu' ),
						'after'  => '</div>',
					]
				);
				?>
		</div><!-- .entry-content -->

	</article><!-- #post-## -->
