<?php
/**
 * Template part for displaying results in search pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package KAMU
 */

?>

<?
	$tv_link = get_field('tv_program_link');
	$radio_link = get_field('radio_program_link');
	$kids_link = get_field('kids_program_link');

	if($tv_link) {
		$post_link = $tv_link;
		$target = '_blank';
	}
	elseif($radio_link) {
		$post_link = $radio_link;
		$target = '_blank';
	}
	elseif($kids_link) {
		$post_link = $kids_link;
		$target = '_blank';
	}
	else {
		$post_link = get_permalink();
		$target = '_self';
	}
?>

<div class="row xl-space-top lg-space-top">
	<?php if ( has_post_thumbnail() ): ?>
		<div class="col-md-4">
			 <?php the_post_thumbnail( 'size-full', array( 'class' => 'img-fluid' ) ); ?>
		</div>
		<div class="col-md-8">
			<?php the_title( sprintf( '<h2 class="entry-title"><a href="' . esc_url( $post_link ) . '" target="'. $target .'" rel="bookmark noopener">', esc_url( $post_link ) ), '</a></h2>' ); ?></a>
			<a href="<?php echo( esc_url( $post_link ) ); ?>" target="<?php echo $target; ?>" rel="bookmark noopener"><?php echo( esc_url( $post_link ) ); ?></a>
			<p><?php the_excerpt(); ?></p>
	
		</div>
	<?else: ?>
		<div class="col-12">
			<?php the_title( sprintf( '<h2 class="entry-title"><a href="' . esc_url( $post_link ) . '" target="'. $target .'" rel="bookmark noopener">', esc_url( $post_link ) ), '</a></h2>' ); ?>
			<a href="<?php echo( esc_url( $post_link ) ); ?>" target="<?php echo $target; ?>" rel="bookmark noopener"><?php echo( esc_url( $post_link ) ); ?></a>
			<?php the_excerpt(); ?>
		</div>
	<?php endif ?>
</div>
