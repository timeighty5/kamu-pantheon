<?php
/**
 * FlushRewrites
 *
 * @author  WPExplorer
 * @package PTU/Classes
 * @version 1.0.2
 */

namespace PTU;

defined( 'ABSPATH' ) || exit;

class FlushRewrites {

	/**
	 * The FlushRewrites class constructor.
	 *
	 * @since 1.0
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {

		// Flush on plugin activation and de-activation
		register_deactivation_hook( PTU_MAIN_FILE_PATH, 'flush_rewrite_rules' );
		register_activation_hook( PTU_MAIN_FILE_PATH, 'flush_rewrite_rules' );

		// Flush rewrite rules whenever a PTU post type is saved and settings have changed
		add_action( 'admin_init', array( $this, 'flush_rewrite_rules' ) );

	}

	/**
	 * Flush rewrite rules as needed
	 *
	 * @since 1.0
	 *
	 * @access public
	 * @return void
	 */
	public function flush_rewrite_rules() {
		if ( get_option( 'ptu_flush_rewrite_rules' ) ) {
			flush_rewrite_rules();
			delete_option( 'ptu_flush_rewrite_rules' );
		}
	}

}
new FlushRewrites;