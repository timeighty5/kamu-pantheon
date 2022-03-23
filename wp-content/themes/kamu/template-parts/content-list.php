<?php
/**
 * Template part for displaying results in search pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package KAMU
 */

?>

<div class="row xl-space-top lg-space-top">
	<?php if ( has_post_thumbnail() ): ?>
		<div class="col-md-4">
			 <?php the_post_thumbnail( 'size-full', array( 'class' => 'img-fluid' ) ); ?>
		</div>
		<div class="col-md-8">
			<?php the_title( sprintf( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?></a>
			<a href="<?php echo( esc_url( get_permalink() ) ); ?>" rel="bookmark"><?php echo( esc_url( get_permalink() ) ); ?></a>
			<p><?php the_excerpt(); ?></p>
	
		</div>
	<?else: ?>
		<div class="col-12">
			<?php the_title( sprintf( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
			<a href="<?php echo( esc_url( get_permalink() ) ); ?>" rel="bookmark"><?php echo( esc_url( get_permalink() ) ); ?></a>
			<p><?php the_excerpt(); ?></p>
		</div>
	<?php endif ?>
</div>
