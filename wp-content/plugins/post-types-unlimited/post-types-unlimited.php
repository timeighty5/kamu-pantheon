<?php
/**
 * Plugin Name: Post Types Unlimited
 * Plugin URI:  https://wordpress.org/plugins/post-types-unlimited/
 * Description: Create unlimited custom post types and custom taxonomies.
 * Version:     1.0.5
 * Author:      WPExplorer
 * Author URI:  https://www.wpexplorer.com/
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: post-types-unlimited
 * Domain Path: /languages
 *
 * @author  WPExplorer
 * @package PTU
 * @version 1.0.5
 */

defined( 'ABSPATH' ) || exit;

/**
 * Main Post_Types_Unlimited Class.
 *
 * @since 1.0
 */
if ( ! class_exists( 'Post_Types_Unlimited' ) ) {

	final class Post_Types_Unlimited {

		/**
		 * Post_Types_Unlimited constructor.
		 *
		 * @since  1.0
		 * @access public
		 * @return void
		 */
		public function __construct() {

			// Define main file path.
			define( 'PTU_MAIN_FILE_PATH', __FILE__ );

			// Define plugin directory path.
			define( 'PTU_PLUGIN_DIR_PATH', plugin_dir_path( PTU_MAIN_FILE_PATH ) );

			// Register Custom Post Types.
			require_once PTU_PLUGIN_DIR_PATH . 'inc/PostTypes.php';

			// Register Custom Taxonomies.
			require_once PTU_PLUGIN_DIR_PATH . 'inc/Taxonomies.php';

			// Create custom metaboxes.
			require_once PTU_PLUGIN_DIR_PATH . 'inc/Metaboxes.php';

			// Flush Rewrite Rules as needed.
			require_once PTU_PLUGIN_DIR_PATH . 'inc/FlushRewrites.php';

			// WPBakery Plugin Support.
			require_once PTU_PLUGIN_DIR_PATH . 'vendor/WPBakery.php';

		}

	}

	new Post_Types_Unlimited;

}