	<div class="wrap about__container">
<?php if(str_fs()->is_free_plan()){ ?>

			<div style="text-align: center; border-width: 3px 0; border-style: solid; border-color: currentColor;padding:10px;">
				<p style="margin: 0;padding: 0;font-size: 6em;line-height: 1;font-weight: 500;
">
					<?php _e( 'StreamCast' ); ?>
					<span><?php _e( 'PRO' ); ?></span>
				</p>
			</div>		
		<div style="min-height: 290px;background-color:#fff; background:url(<?php echo STP_PLUGIN_DIR; ?>admin/bg.png) no-repeat ;" class="about__header">
			
			
		</div>	


		<div class="">
			<nav class="about__header-navigation nav-tab-wrapper wp-clearfix" aria-label="<?php esc_attr_e( 'Secondary menu' ); ?>">
				<a href="edit.php?post_type=streamcast&page=help" class="nav-tab nav-tab-active" aria-current="page"><?php _e( 'What&#8217;s New' ); ?></a>

				<a target="_blank"  href="http://wpradioplayer.com/" class="nav-tab"><?php _e( 'Live Demo' ); ?></a>			
				<a target="_blank" href="edit.php?post_type=streamcast&page=streamcast-contact" class="nav-tab"><?php _e( 'I Have A Questions' ); ?></a>
				<a href="edit.php?post_type=streamcast&page=streamcast-pricing" class="nav-tab"><?php _e( 'Upgrade Now' ); ?></a>
			</nav>
		</div>

		<div style="display:none;" class="about__section has-2-columns has-subtle-background-color">
			<h2 class="is-section-header">
				<?php __( 'Welcome to StreamCast PRO ' ); ?>
			</h2>
			<div class="column">
				<p>
					<?php _e( 'After launch StreamCast Radio Player, many users find it useful and  ' ); ?>
				</p>
				<p>
					<?php _e( 'More ways to make posts and pages come alive with your best images.' ); ?>
					<?php _e( 'More ways to bring your visitors in, and keep them engaged, with the richness of embedded media from the web&#8217;s top services.' ); ?>
				</p>
			</div>
			<div class="column">
				<p>
					<?php _e( 'More ways to make your vision real, and put blocks in the perfect place&mdash;even if a particular kind of block is new to you. More efficient processes.' ); ?>
				</p>
				<p>
					<?php _e( 'And more speed everywhere, so as you build sections or galleries, or just type in a line of prose, you can feel how much faster your work flows.' ); ?>
				</p>
			</div>
		</div>



		<div class="about__section has-2-columns">
			<div class="column is-vertically-aligned-center">
				<h2><?php _e( 'PRO Version Features...' ); ?></h2>
				<ul>
					<li><?php _e( '85+ Player Skins' ); ?></li>
					<li><?php _e( 'Added StreamCast Radio Player Blocks' ); ?></li>
					<li><?php _e( 'Player Visualizer' ); ?></li>
					<li><?php _e( 'Color Scheme' ); ?></li>
					<li><?php _e( 'Sidebar Widget' ); ?></li>
					<li><?php _e( 'Additional Theme' ); ?></li>
					<li><?php _e( 'Set Station Name to show in the player.' ); ?></li>
					<li><?php _e( 'Set Welcome Message to show in the player' ); ?></li>
					<li><?php _e( 'Set Player ArtWork' ); ?></li>
					<li><?php _e( 'AutoPlay Options' ); ?></li>
					<li><?php _e( 'Set nitial volume' ); ?></li>
					<li><?php _e( 'Change player background color' ); ?></li>
					<li><?php _e( 'Set player position' ); ?></li>
					<li><?php _e( 'Support custom CSS' ); ?></li>
					<li><?php _e( 'And more...' ); ?></li>
				</ul>
			</div>
			<div class="column is-edge-to-edge">
				<div class="about__image aligncenter">
					<img src="data:image/svg+xml;charset=utf8,%3Csvg width='500' height='500' viewbox='0 0 500 500' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill='%23F3F4F5' d='M0 0h500v500H0z'/%3E%3Cg clip-path='url(%23clip0)'%3E%3Cpath d='M169.6 171.55l-.3 72.3 330.7-1v-72.6l-330.4 1.3z' fill='%230740B3'/%3E%3Cpath d='M291.2 97.85l-1.3-14.8-63.4-.7v76c0 3.6 176.7 4.1 273.5 4.1v-64.5H291.2v-.1z' fill='%230285D7'/%3E%3Cpath d='M500 27.75l-215.5-5.9 5.4 61.2 210.1 2.5v-57.8z' fill='%231730E5'/%3E%3Cpath d='M500 97.85v-12.3l-210.1-2.5 1.3 14.8H500z' fill='%230285D7'/%3E%3Cpath d='M500 97.85v-12.3l-210.1-2.5 1.3 14.8H500z' fill='%231730E5' style='mix-blend-mode:multiply'/%3E%3Cpath d='M255.2 379.75l-1-49.2-229.2.3-2 69.7 477-1.3v-24.3l-244.8 4.8z' fill='%230285D7'/%3E%3Cpath d='M500 424.35v-15l-430.8 1.2-4 51.5 134.6-.5v-34.4c.1-2.8 214.4-2.9 300.2-2.8z' fill='%230878FF'/%3E%3Cpath d='M500 290.05l-246.4 4.3.6 36.2 245.8-.3v-40.2z' fill='%23072CF0'/%3E%3Cpath d='M500 374.95v-44.7l-245.8.3 1 49.2 244.8-4.8z' fill='%230285D7'/%3E%3Cpath d='M500 374.95v-44.7l-245.8.3 1 49.2 244.8-4.8z' fill='%23072CF0' style='mix-blend-mode:multiply'/%3E%3Cpath d='M199.9 461.55v17.6l300.1-2.4v-16.3l-300.1 1.1z' fill='%230285D7'/%3E%3Cpath d='M500 424.35c-85.8-.1-300.1 0-300.1 2.8v34.4l300.1-1.1v-36.1z' fill='%230878FF'/%3E%3Cpath d='M500 424.35c-85.8-.1-300.1 0-300.1 2.8v34.4l300.1-1.1v-36.1z' fill='%230285D7' style='mix-blend-mode:multiply'/%3E%3C/g%3E%3Cdefs%3E%3CclipPath id='clip0'%3E%3Cpath transform='rotate(-90 23 479.15)' fill='%23fff' d='M23 479.15h457.3v477H23z'/%3E%3C/clipPath%3E%3C/defs%3E%3C/svg%3E" alt="">
				</div>
			</div>
		</div>

		<div style="display:none;" class="about__section has-2-columns">
			<div class="column is-edge-to-edge">
				<div class="about__image aligncenter">
					<img src="data:image/svg+xml;charset=utf8,%3Csvg width='500' height='500' viewbox='0 0 500 500' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill='%23F3F4F5' d='M0 0h500v500H0z'/%3E%3Cpath d='M31.3 284.4c-2-.1 12.2-250.6 12.2-250.6s94.8 4.4 99.7 5.2c.3 21.8 4.1 250.1 4.1 250.1l-116-4.7z' fill='%231730E5'/%3E%3Cpath d='M346.8 467.4l-11.7-305.9 138.2 2.4-3 304.1-123.5-.6z' fill='%230840B3'/%3E%3Cpath d='M287.7 34.9c2.3 0 5.9 398.5 5.9 398.5s-109.6-2.2-115 .6c-5.4 2.8 10.6-400.5 10.6-400.5l98.5 1.4z' fill='%23018BDE'/%3E%3Cpath d='M372.3 138c32.585 0 59-26.415 59-59s-26.415-59-59-59-59 26.415-59 59 26.415 59 59 59z' fill='%23062EF7'/%3E%3Cpath d='M35.8 315c-12.8 0-24.9 2.9-35.8 8.1v148.7c10.8 5.2 22.9 8.1 35.8 8.1 45.6 0 82.5-36.9 82.5-82.5S81.3 315 35.8 315z' fill='%231C87C0'/%3E%3C/svg%3E" alt="" />
				</div>
			</div>
			<div class="column is-vertically-aligned-center">
				<h2><?php _e( 'Your fundamental right: privacy' ); ?></h2>
				<p><?php _e( '5.4 helps with a variety of privacy issues around the world. So when users and stakeholders ask about regulatory compliance, or how your team handles user data, the answers should be a lot easier to get right.' ); ?></p>
				<p><?php _e( 'Take a look:' ); ?></p>
				<ul>
					<li><?php _e( 'Now personal data exports include users session information and users location data from the community events widget. Plus, a table of contents!' ); ?></li>
					<li><?php _e( 'See progress as you process export and erasure requests through the privacy tools.' ); ?></li>
					<li><?php _e( 'Plus, little enhancements throughout give the privacy tools a little cleaner look. Your eyes will thank you!' ); ?></li>
				</ul>
			</div>
		</div>
		
		
	<?php }?>	
		
		
		

		<hr />

		<div class="about__section ">
			<div class="column has-subtle-background-color">
				<h2 class=""><?php _e( 'Getting started with <strong>StreamCast </strong> Radio Player' ); ?></h2>
				<p>Watch this quick tutorial below to learn how to use StreamCast radio player in your website.</p>
			</div>
		</div>

		<hr class="is-small" />

		<div class="about__section">
			
				<style>.embed-container { position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; max-width: 100%; } .embed-container iframe, .embed-container object, .embed-container embed { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }</style><div class='embed-container'><iframe src='https://www.youtube.com/embed/ad_LKLe_YQg' frameborder='0' allowfullscreen></iframe></div>
			
		</div>

		<hr class="is-small" />

		



		<div class="return-to-dashboard">
			<?php if ( current_user_can( 'update_core' ) && isset( $_GET['updated'] ) ) : ?>
				<a href="<?php echo esc_url( self_admin_url( 'update-core.php' ) ); ?>">
					<?php is_multisite() ? _e( 'Return to Updates' ) : _e( 'Return to Dashboard &rarr; Updates' ); ?>
				</a> |
			<?php endif; ?>
			<a href="<?php echo esc_url(get_admin_url().'edit.php?post_type=streamcast'); ?>"><?php is_blog_admin() ? _e( 'Go to StreamCast &rarr; Home' ) : _e( 'Go to Dashboard' ); ?></a>
		</div>
	</div>