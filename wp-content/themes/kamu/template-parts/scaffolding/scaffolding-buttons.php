<?php
/**
 * The template used for displaying Buttons in the scaffolding library.
 *
 * @package KAMU
 */

?>

<section class="section-scaffolding">

	<h2 class="scaffolding-heading"><?php esc_html_e( 'Buttons', 'kamu' ); ?></h2>
	<?php
		// Button.
		wds_kamu_display_scaffolding_section(
			[
				'title'       => 'Button',
				'description' => 'Display a button.',
				'usage'       => '<button class="button" href="#">Click Me</button>',
				'output'      => '<button class="button">Click Me</button>',
			]
		);
		?>
</section>
