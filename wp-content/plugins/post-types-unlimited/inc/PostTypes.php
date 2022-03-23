<?php
/**
 * Post Types
 *
 * @author  WPExplorer
 * @package PTU/Classes
 * @version 1.0.5
 */

namespace PTU;

defined( 'ABSPATH' ) || exit;

class PostTypes {

	/**
	 * Admin Post Type
	 *
	 * @var string
	 */
	public $admin_pt = 'ptu';

	/**
	 * PosTypes Constructor.
	 *
	 * @since 1.0
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {

		// Add new submenu item under "Tools" for accessing the ptu post type
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

		// Register post type used for the admin interface
		add_action( 'init', array( $this, 'admin_type' ) );

		// Add custom metabox with post type settings
		add_filter( 'ptu_metaboxes', array( $this, 'meta' ) );

		// Register saved custom post types
		add_action( 'init', array( $this, 'register_custom_post_types' ) );

		// Custom admin columns
		add_filter( 'manage_edit-' . $this->admin_pt . '_columns', array( $this, 'edit_columns' ) );
		add_action( 'manage_' . $this->admin_pt . '_posts_custom_column', array( $this, 'column_display' ), 10, 2 );

	}

	/**
	 * Add new submenu item under "Tools" for accessing the ptu post type.
	 *
	 * @since  1.0
	 * @access public
	 * @return void
	 */
	public function admin_menu() {

		add_menu_page(
			__( 'Post Types', 'post-types-unlimited' ),
			__( 'Post Types', 'post-types-unlimited' ),
			'manage_options',
			'edit.php?post_type=' . $this->admin_pt,
			'',
			'dashicons-grid-view'
		);

	}

	/**
	 * Register post type used for the admin interface.
	 *
	 * @since  1.0
	 * @access public
	 * @return void
	 */
	public function admin_type() {

		register_post_type( $this->admin_pt, array(
			'labels' => array(
				'name'               => __( 'Post Types Unlimited', 'post-types-unlimited' ),
				'singular_name'      => __( 'Post Type', 'post-types-unlimited' ),
				'add_new'            => __( 'Add New' , 'post-types-unlimited' ),
				'add_new_item'       => __( 'Add New Post Type' , 'post-types-unlimited' ),
				'edit_item'          => __( 'Edit Post Type' , 'post-types-unlimited' ),
				'new_item'           => __( 'New Post Type' , 'post-types-unlimited' ),
				'view_item'          => __( 'View Post Type', 'post-types-unlimited' ),
				'search_items'       => __( 'Search Post Types', 'post-types-unlimited' ),
				'not_found'          => __( 'No Post Types found', 'post-types-unlimited' ),
				'not_found_in_trash' => __( 'No Post Types found in Trash', 'post-types-unlimited' ),
			),
			'public'          => false,
			'show_ui'         => true,
			'_builtin'        => false,
			'capability_type' => 'page',
			'hierarchical'    => false,
			'rewrite'         => false,
			'query_var'       => $this->admin_pt,
			'supports'        => array(
				'title'
			),
			'show_in_menu'    => false,
		) );

	}

	/**
	 * Add custom metabox with post type settings.
	 *
	 * @since  1.0
	 * @access public
	 * @return $metaboxes
	 */
	public function meta( $metaboxes ) {

		$metaboxes[] = array(
			'id'       => $this->admin_pt . '_main',
			'title'    => __( 'Main Settings', 'post-types-unlimited' ),
			'screen'   => array( $this->admin_pt ),
			'context'  => 'normal',
			'priority' => 'high',
			'fields'   => array(
				array(
					'name'              => __( 'Name (required)', 'post-types-unlimited' ),
					'id'                => 'name',
					'type'              => 'text',
					'sanitize_callback' => array( $this, 'sanitize_post_type_name' ),
					'required'          => true,
					'maxlength'         => '20',
					'placeholder'       => __( '(e.g. product)', 'post-types-unlimited' ),
					'desc'              => __( 'max. 20 characters, cannot contain capital letters, underscores or spaces.', 'post-types-unlimited' ),
				),
				array(
					'name'        => __( 'Label', 'post-types-unlimited' ),
					'id'          => 'label',
					'type'        => 'text',
					'placeholder' => __( '(e.g. Products)', 'post-types-unlimited' ),
					'desc'        => __( 'A plural descriptive name for the post type marked for translation.', 'post-types-unlimited' ),
				),
				array(
					'name'        => __( 'Singular Name', 'post-types-unlimited' ),
					'id'          => 'singular_name',
					'type'        => 'text',
					'placeholder' => __( '(e.g. Product)', 'post-types-unlimited' ),
					'desc'        => __( 'Name for one object of this post type.', 'post-types-unlimited' ),
				),
				array(
					'name'        => __( 'Custom Menu Name', 'post-types-unlimited' ),
					'id'          => 'menu_name',
					'type'        => 'text',
					'placeholder' => __( '(e.g. Products)', 'post-types-unlimited' ),
					'desc'        => __( 'String used for the menu name. Only used for top level admin menus, if you define a submenu location WordPress will default to the All Items label.', 'post-types-unlimited' ),
				),
				array(
					'name'  => __( 'Description (optional)', 'post-types-unlimited' ),
					'id'    => 'description',
					'type'  => 'textarea',
					'desc'  => __( 'A short descriptive summary of what the post type is.', 'post-types-unlimited' ),
				),
				array(
					'name'    => __( 'Public', 'post-types-unlimited' ),
					'id'      => 'public',
					'type'    => 'checkbox',
					'default' => true,
					'desc'    => __( '(default: true) Controls how the type is visible to authors (show_in_nav_menus, show_ui) and readers.', 'post-types-unlimited' ),
				),
				array(
					'name'    => __( 'Exclude From Search', 'post-types-unlimited' ),
					'id'      => 'exclude_from_search',
					'type'    => 'checkbox',
					'default' => false,
					'desc'    => __( '(default: false) Whether to exclude posts with this post type from front end search results. Note: This will also exclude the post type from custom queries.', 'post-types-unlimited' ),
				),
				array(
					'name'    => __( 'Publicly Queryable', 'post-types-unlimited' ),
					'id'      => 'publicly_queryable',
					'type'    => 'checkbox',
					'default' => true,
					'desc'    => __( '(default: true) Whether queries can be performed on the front end as part of parse_request().', 'post-types-unlimited' ),
				),
				array(
					'name'    => __( 'Show UI', 'post-types-unlimited' ),
					'id'      => 'show_ui',
					'type'    => 'checkbox',
					'default' => true,
					'desc'    => __( '(default: true) Whether to generate a default UI for managing this post type in the admin.', 'post-types-unlimited' ),
				),
				array(
					'name'    => __( 'Show In Nav Menus', 'post-types-unlimited' ),
					'id'      => 'show_in_nav_menus',
					'type'    => 'checkbox',
					'default' => true,
					'desc'    => __( '(default: true) Whether post_type is available for selection in navigation menus.', 'post-types-unlimited' ),
				),
				array(
					'name'    => __( 'Show In Menu', 'post-types-unlimited' ),
					'id'      => 'show_in_menu',
					'type'    => 'checkbox',
					'default' => true,
					'desc'    => __( '(default: true) Show the post type in the admin menu. Show UI must be true.', 'post-types-unlimited' ),
				),
				array(
					'name'        => __( 'Sub-Menu Location (optional)', 'post-types-unlimited' ),
					'id'          => 'show_in_menu_string',
					'type'        => 'text',
					'placeholder' => __( '(e.g. tools.php)', 'post-types-unlimited' ),
					'desc'        => __( 'Top-level admin menu page file name for which the post type should be in the sub menu of such as tools.php, options-general.php, themes.php or edit.php', 'post-types-unlimited' ),
				),
				array(
					'name'    => __( 'Show In Admin Bar', 'post-types-unlimited' ),
					'id'      => 'show_in_admin_bar',
					'type'    => 'checkbox',
					'default' => false,
					'desc'    => __( '(default: false) Whether to make this post type available in the WordPress admin bar.', 'post-types-unlimited' ),
				),
				array(
					'name'    => __( 'Menu Position', 'post-types-unlimited' ),
					'id'      => 'menu_position',
					'type'    => 'number',
					'desc'    => __( '(default: 50) The position in the menu order the post type should appear. show_in_menu must be true.', 'post-types-unlimited' ),
					'default' => 50,
				),
				array(
					'name'    => __( 'Menu Icon', 'post-types-unlimited' ),
					'id'      => 'menu_icon',
					'type'    => 'dashicon',
					'default' => 'format-standard',
					'desc'    => __( 'The custom icon for the menu item. show_in_menu must be true.', 'post-types-unlimited' ),
				),
				array(
					'name'    => __( 'Capability Type', 'post-types-unlimited' ),
					'id'      => 'capability_type',
					'type'    => 'select',
					'desc'    => __( '(default: post) The post type to use to build the read, edit, and delete capabilities.', 'post-types-unlimited' ),
					'choices' => array(
						'post' => __( 'Post', 'post-types-unlimited' ),
						'page' => __( 'Page', 'post-types-unlimited' ),
					)
				),
				array(
					'name'    => __( 'Hierarchical', 'post-types-unlimited' ),
					'id'      => 'hierarchical',
					'type'    => 'checkbox',
					'default' => false,
					'desc'    => __( '(default: false) Whether the post type is hierarchical (e.g. page). Allows Parent to be specified.', 'post-types-unlimited' ),
				),
				array(
					'name'    => __( 'Supports', 'post-types-unlimited' ),
					'id'      => 'supports',
					'type'    => 'multi_select',
					'desc'    => __( 'The various metaboxes to be included when editing a singular post.', 'post-types-unlimited' ),
					'choices' => array(
						'title'           => __( 'Title (default)', 'post-types-unlimited' ),
						'editor'          => __( 'Editor (default)', 'post-types-unlimited' ),
						'author'          => __( 'Author', 'post-types-unlimited' ),
						'thumbnail'       => __( 'Thumbnail/Featured Image', 'post-types-unlimited' ),
						'excerpt'         => __( 'Excerpt', 'post-types-unlimited' ),
						'custom-fields'   => __( 'Custom Fields', 'post-types-unlimited' ),
						'comments'        => __( 'Comments', 'post-types-unlimited' ),
						'revisions'       => __( 'Revisions', 'post-types-unlimited' ),
						'page-attributes' => __( 'Page Attributes', 'post-types-unlimited' ),
						'post-formats'    => __( 'Post Formats', 'post-types-unlimited' ),
					),
					'default' => array( 'title', 'editor' ),
				),
				array(
					'name'    => __( 'Core Taxonomies', 'post-types-unlimited' ),
					'id'      => 'taxonomies',
					'type'    => 'multi_select',
					'desc'    => __( 'Core taxonomies to be used for this post type.', 'post-types-unlimited' ),
					'choices' => array(
						'category' =>  __( 'Category', 'post-types-unlimited' ),
						'post_tag' =>  __( 'Post Tag', 'post-types-unlimited' ),
					)
				),
				array(
					'name'    => __( 'Has Archive', 'post-types-unlimited' ),
					'id'      => 'has_archive',
					'type'    => 'checkbox',
					'default' => false,
					'desc'    => __( '(default: false) Enables post type archives.', 'post-types-unlimited' ),
				),
				array(
					'name'    => __( 'Rewrite', 'post-types-unlimited' ),
					'id'      => 'rewrite',
					'type'    => 'checkbox',
					'default' => true,
					'desc'    => __( '(default: true) Triggers the handling of rewrites for this post type. To prevent rewrites, disable.', 'post-types-unlimited' ),
				),
				array(
					'name'        => __( 'Custom Rewrite Slug', 'post-types-unlimited' ),
					'id'          => 'slug',
					'type'        => 'text',
					'placeholder' => __( '(default: post type name)', 'post-types-unlimited' ),
					'desc'        => __( 'Customize the permalink structure slug. Defaults to the post type name. Rewrite must be enabled in order for this to work.', 'post-types-unlimited' ),
				),
				array(
					'name'    => __( 'With Front', 'post-types-unlimited' ),
					'id'      => 'with_front',
					'type'    => 'checkbox',
					'default' => true,
					'desc'    => __( '(default: true) Should the permalink structure be prepended with the front base (example: if your permalink structure is /blog/, then your links will be blog/{post_type_slug}/ if enabled).', 'post-types-unlimited' ),
				),
				array(
					'name'    => __( 'Show in Rest API (enables Gutenberg)', 'post-types-unlimited' ),
					'id'      => 'show_in_rest',
					'type'    => 'checkbox',
					'default' => false,
					'desc'    => __( '(default: false) Whether to expose this post type in the REST API.', 'post-types-unlimited' ),
				),
			),
		);

		$metaboxes[] = array(
			'id'       => $this->admin_pt . '_advanced',
			'title'    => __( 'Advanced Settings', 'post-types-unlimited' ),
			'screen'   => array( $this->admin_pt ),
			'context'  => 'normal',
			'priority' => 'default',
			'classes'  => get_option( 'ptu_hide_advanced_settings', true ) ? array( 'closed' ) : array(),
			'fields'   => array(
				array(
					'name'        => __( 'Custom REST API Base', 'post-types-unlimited' ),
					'id'          => 'rest_base',
					'type'        => 'text',
					'placeholder' => __( '(default: post type name)', 'post-types-unlimited' ),
					'desc'        => __( 'The base slug that this post type will use when accessed using the REST API.', 'post-types-unlimited' ),
				),
				array(
					'name'        => __( 'Custom REST Controller Class', 'post-types-unlimited' ),
					'id'          => 'rest_controller_class',
					'type'        => 'text',
					'placeholder' => __( '(default: WP_REST_Posts_Controller)', 'post-types-unlimited' ),
					'desc'        => __( 'An optional custom controller to use instead of WP_REST_Posts_Controller. Must be a subclass of WP_REST_Controller.', 'post-types-unlimited' ),
				),
			)
		);

		$metaboxes[] = array(
			'id'       => $this->admin_pt . '_labels',
			'title'    => __( 'Custom Labels (optional)', 'post-types-unlimited' ),
			'screen'   => array( $this->admin_pt ),
			'context'  => 'normal',
			'priority' => 'default',
			'classes'  => get_option( 'ptu_hide_labels_settings', true ) ? array( 'closed' ) : array(),
			'fields'   => array(
				array(
					'name'        => __( 'Add New', 'post-types-unlimited' ),
					'id'          => 'labels_add_new',
					'type'        => 'text',
					'placeholder' => __( '(e.g. Add New)', 'post-types-unlimited' ),
					'desc'        => __( 'Displayed in the admin submenu.', 'post-types-unlimited' ),
				),
				array(
					'name'        => __( 'Add New Item', 'post-types-unlimited' ),
					'id'          => 'labels_add_new_item',
					'type'        => 'text',
					'placeholder' => __( '(e.g. Add New Product)', 'post-types-unlimited' ),
					'desc'        => __( 'Used at the top of the post editor screen when adding a new post.', 'post-types-unlimited' ),
				),
				array(
					'name'        => __( 'Edit Item', 'post-types-unlimited' ),
					'id'          => 'labels_edit_item',
					'type'        => 'text',
					'placeholder' => __( '(e.g. Edit Product)', 'post-types-unlimited' ),
					'desc'        => __( 'Used at the top of the post editor screen when editing an existing post type.', 'post-types-unlimited' ),
				),
				array(
					'name'        => __( 'New Item', 'post-types-unlimited' ),
					'id'          => 'labels_new_item',
					'type'        => 'text',
					'placeholder' => __( '(e.g. New Product)', 'post-types-unlimited' ),
					'desc'        => __( 'Post type label. Displayed in the admin menu for displaying post types.', 'post-types-unlimited' ),
				),
				array(
					'name'        => __( 'View Item', 'post-types-unlimited' ),
					'id'          => 'labels_view_item',
					'type'        => 'text',
					'placeholder' => __( '(e.g. View Product)', 'post-types-unlimited' ),
					'desc'        => __( 'Used in the admin bar when viewing the editor screen for a published post.', 'post-types-unlimited' ),
				),
				array(
					'name'        => __( 'View Items', 'post-types-unlimited' ),
					'id'          => 'labels_view_items',
					'type'        => 'text',
					'placeholder' => __( '(e.g. View Products)', 'post-types-unlimited' ),
					'desc'        => __( 'The plural version of View Item.', 'post-types-unlimited' ),
				),
				array(
					'name'        => __( 'Search Items', 'post-types-unlimited' ),
					'id'          => 'labels_search_items',
					'type'        => 'text',
					'placeholder' => __( '(e.g. Search Products)', 'post-types-unlimited' ),
					'desc'        => __( 'Used for the search button in the admin post type list screen.', 'post-types-unlimited' ),
				),
				array(
					'name'        => __( 'Not Found', 'post-types-unlimited' ),
					'id'          => 'labels_not_found',
					'type'        => 'text',
					'placeholder' => __( '(e.g. No Products found)', 'post-types-unlimited' ),
					'desc'        => __( 'Used when there are no items to display while doing a search in the post type list screen.', 'post-types-unlimited' ),
				),
				array(
					'name'        => __( 'Not Found in Trash', 'post-types-unlimited' ),
					'id'          => 'labels_not_found_in_trash',
					'type'        => 'text',
					'placeholder' => __( '(e.g. No Products found in Trash)', 'post-types-unlimited' ),
					'desc'        => __( 'String for use when there are no items to display while doing a search in the trash.', 'post-types-unlimited' ),
				),
				array(
					'name'        => __( 'Parent Item Colon', 'post-types-unlimited' ),
					'id'          => 'labels_parent_item_colon',
					'type'        => 'text',
					'placeholder' => __( '(e.g. Parent Product:)', 'post-types-unlimited' ),
					'desc'        => __( 'String for use with hierarchical post types that require a colon.', 'post-types-unlimited' ),
				),
				array(
					'name'        => __( 'All Items', 'post-types-unlimited' ),
					'id'          => 'labels_all_items',
					'type'        => 'text',
					'placeholder' => __( '(e.g. All Products)', 'post-types-unlimited' ),
					'desc'        => __( 'String for use in the submenu.', 'post-types-unlimited' ),
				),
				array(
					'name'        => __( 'Archives', 'post-types-unlimited' ),
					'id'          => 'labels_archives',
					'type'        => 'text',
					'placeholder' => __( '(e.g. Product Archives)', 'post-types-unlimited' ),
					'desc'        => __( 'String for use with archives in nav menus.', 'post-types-unlimited' ),
				),
				array(
					'name'        => __( 'Attributes', 'post-types-unlimited' ),
					'id'          => 'labels_attributes',
					'type'        => 'text',
					'placeholder' => __( '(e.g. Product Attributes)', 'post-types-unlimited' ),
					'desc'        => __( 'Label for the attributes meta box.', 'post-types-unlimited' ),
				),
				array(
					'name'        => __( 'Insert into Item', 'post-types-unlimited' ),
					'id'          => 'labels_insert_into_item',
					'type'        => 'text',
					'placeholder' => __( '(e.g. Insert into Product)', 'post-types-unlimited' ),
					'desc'        => __( 'String for the media frame button.', 'post-types-unlimited' ),
				),
				array(
					'name'        => __( 'Uploaded into this Item', 'post-types-unlimited' ),
					'id'          => 'labels_uploaded_to_this_item',
					'type'        => 'text',
					'placeholder' => __( '(e.g. Uploaded to this Product)', 'post-types-unlimited' ),
					'desc'        => __( 'String for the media frame filter.', 'post-types-unlimited' ),
				),
				array(
					'name'        => __( 'Featured Image', 'post-types-unlimited' ),
					'id'          => 'labels_featured_image',
					'type'        => 'text',
					'placeholder' => __( '(e.g. Featured Image)', 'post-types-unlimited' ),
					'desc'        => __( 'String used for the "Featured Image" phrase.', 'post-types-unlimited' ),
				),
				array(
					'name'        => __( 'Set Featured Image', 'post-types-unlimited' ),
					'id'          => 'labels_set_featured_image',
					'type'        => 'text',
					'placeholder' => __( '(e.g. Set featured image)', 'post-types-unlimited' ),
					'desc'        => __( 'String used for the "Set featured image" phrase.', 'post-types-unlimited' ),
				),
				array(
					'name'        => __( 'Remove Featured Image', 'post-types-unlimited' ),
					'id'          => 'labels_remove_featured_image',
					'type'        => 'text',
					'placeholder' => __( '(e.g. Remove featured image)', 'post-types-unlimited' ),
					'desc'        => __( 'String used for the "Remove featured image" phrase.', 'post-types-unlimited' ),
				),
				array(
					'name'        => __( 'Use Featured Image', 'post-types-unlimited' ),
					'id'          => 'labels_use_featured_image',
					'type'        => 'text',
					'placeholder' => __( '(e.g. Use as featured image)', 'post-types-unlimited' ),
					'desc'        => __( 'String used for the "Use as featured image" phrase.', 'post-types-unlimited' ),
				),
				array(
					'name'        => __( 'Filter Items List', 'post-types-unlimited' ),
					'id'          => 'labels_filter_items_list',
					'type'        => 'text',
					'placeholder' => __( '(e.g. Filter products list)', 'post-types-unlimited' ),
					'desc'        => __( 'String for the table views hidden heading. Used for screen readers.', 'post-types-unlimited' ),
				),
				array(
					'name'        => __( 'Items List Navigation', 'post-types-unlimited' ),
					'id'          => 'labels_items_list_navigation',
					'type'        => 'text',
					'placeholder' => __( '(e.g. Products list navigation)', 'post-types-unlimited' ),
					'desc'        => __( 'String for the table views pagination hidden heading. Used for screen readers.', 'post-types-unlimited' ),
				),
				array(
					'name'        => __( 'Items List', 'post-types-unlimited' ),
					'id'          => 'labels_items_list',
					'type'        => 'text',
					'placeholder' => __( '(e.g. Products list)', 'post-types-unlimited' ),
					'desc'        => __( 'String for the table hidden heading. Used for screen readers.', 'post-types-unlimited' ),
				),
				array(
					'name'        => __( 'Admin Bar Name', 'post-types-unlimited' ),
					'id'          => 'labels_name_admin_bar',
					'type'        => 'text',
					'placeholder' => __( '(e.g. Products)', 'post-types-unlimited' ),
					'desc'        => __( 'String for use in the Admin menu bar.', 'post-types-unlimited' ),
				),
			)
		);

		return $metaboxes;

	}

	/**
	 * Sanitize post type name to make sure it's valid.
	 * max. 20 characters, cannot contain capital letters, underscores or spaces.
	 *
	 * @since  1.0
	 * @access public
	 * @return $value
	 */
	public function sanitize_post_type_name( $field, $value ) {
		return sanitize_key( $value );
	}

	/**
	 * Register saved custom post types.
	 *
	 * @since  1.0
	 * @access public
	 * @return void
	 */
	public function register_custom_post_types() {

		// Get custom post type ID's
		$custom_types = get_posts( array(
			'numberposts' 	   => -1,
			'post_type' 	   => $this->admin_pt,
			'post_status'      => 'publish',
			'suppress_filters' => false,
			'fields'           => 'ids',
		) );

		// If we have custom post types, lets try and register them
		if ( $custom_types ) {

			// Loop through all custom post types and register them
			foreach( $custom_types as $type_id ) {

				// Get custom post type meta
				$meta = get_post_meta( $type_id, '', false );

				// Check custom post type name
				$name = array_key_exists( '_ptu_name', $meta ) ? $meta[ '_ptu_name' ][0] : '';

				// Custom post type name is required
				if ( ! $name ) {
					continue;
				}

				// Get labels from meta
				$label                 = array_key_exists( '_ptu_label', $meta ) ? $meta[ '_ptu_label' ][0] : $name;
				$singular_name         = array_key_exists( '_ptu_singular_name', $meta ) ? $meta[ '_ptu_singular_name' ][0] : $label;
				$menu_name             = array_key_exists( '_ptu_menu_name', $meta ) ? $meta[ '_ptu_menu_name' ][0] : $label;
				$add_new               = array_key_exists( '_ptu_labels_add_new', $meta ) ? $meta[ '_ptu_labels_add_new' ][0] : _x( 'Add New ', $singular_name, 'post-types-unlimited' );
				$add_new_item          = array_key_exists( '_ptu_labels_add_new_item', $meta ) ? $meta[ '_ptu_labels_add_new_item' ][0] : sprintf( _x( 'Add New %s', 'post type label', 'post-types-unlimited' ), $singular_name );
				$edit_item             = array_key_exists( '_ptu_labels_edit_item', $meta ) ? $meta[ '_ptu_labels_edit_item' ][0] : sprintf( _x( 'Edit %s', 'post type label', 'post-types-unlimited' ), $singular_name );
				$new_item              = array_key_exists( '_ptu_labels_new_item', $meta ) ? $meta[ '_ptu_labels_new_item' ][0] : sprintf( _x( 'New %s', 'post type label', 'post-types-unlimited' ), $singular_name );
				$view_item             = array_key_exists( '_ptu_labels_view_item', $meta ) ? $meta[ '_ptu_labels_view_item' ][0] : sprintf( _x( 'View %s', 'post type label', 'post-types-unlimited' ), $singular_name );

				$view_items            = array_key_exists( '_ptu_labels_view_items', $meta ) ? $meta[ '_ptu_labels_view_items' ][0] : sprintf( _x( 'View %s', 'post type label', 'post-types-unlimited' ),  $label );
				$all_items             = array_key_exists( '_ptu_labels_all_items', $meta ) ? $meta[ '_ptu_labels_all_items' ][0] : sprintf( _x( 'All %s', 'post type label', 'post-types-unlimited' ), $label );
				$search_items          = array_key_exists( '_ptu_labels_search_items', $meta ) ? $meta[ '_ptu_labels_search_items' ][0] : sprintf( _x( 'Search %s', 'post type label', 'post-types-unlimited' ), $label );
				$parent_item_colon     = array_key_exists( '_ptu_labels_parent_item_colon', $meta ) ? $meta[ '_ptu_labels_parent_item_colon' ][0] : sprintf( _x( 'Parent %s:', 'post type label', 'post-types-unlimited' ), $label );
				$not_found             = array_key_exists( '_ptu_labels_not_found', $meta ) ? $meta[ '_ptu_labels_not_found' ][0] : sprintf( _x( 'No %s found.', 'post type label', 'post-types-unlimited' ), $label );
				$not_found_in_trash    = array_key_exists( '_ptu_labels_not_found_in_trash', $meta ) ? $meta[ '_ptu_labels_not_found_in_trash' ][0] : sprintf( _x( 'No %s found in Trash.', 'post type label', 'post-types-unlimited' ), $label );
				$archives              = array_key_exists( '_ptu_labels_archives', $meta ) ? $meta[ '_ptu_labels_archives' ][0] : sprintf( _x( '%s Archives', 'post type label', 'post-types-unlimited' ), $label );
				$attributes            = array_key_exists( '_ptu_labels_attributes', $meta ) ? $meta[ '_ptu_labels_attributes' ][0] : sprintf( _x( '%s Attributes', 'post type label', 'post-types-unlimited' ), $label );
				$insert_into_item      = array_key_exists( '_ptu_labels_insert_into_item', $meta ) ? $meta[ '_ptu_labels_insert_into_item' ][0] : sprintf( _x( 'Insert into %s', 'post type label', 'post-types-unlimited' ), $singular_name );
				$uploaded_to_this_item = array_key_exists( '_ptu_labels_uploaded_to_this_item', $meta ) ? $meta[ '_ptu_labels_uploaded_to_this_item' ][0] : sprintf( _x( 'Uploaded to this %s', 'post type label', 'post-types-unlimited' ), $singular_name );
				$filter_items_list     = array_key_exists( '_ptu_labels_filter_items_list', $meta ) ? $meta[ '_ptu_labels_filter_items_list' ][0] : sprintf( _x( 'Filter %s list', 'post type label', 'post-types-unlimited' ), strtolower( $label ) );
				$items_list_navigation = array_key_exists( '_ptu_labels_items_list_navigation', $meta ) ? $meta[ '_ptu_labels_items_list_navigation' ][0] : sprintf( _x( 'Filter %s list navigation', 'post type label', 'post-types-unlimited' ), strtolower( $label ) );
				$items_list            = array_key_exists( '_ptu_labels_items_list', $meta ) ? $meta[ '_ptu_labels_items_list' ][0] : sprintf( _x( '%s list', 'post type label', 'post-types-unlimited' ), strtolower( $label ) );


				// Define Post Type Labels
				$labels = array(
					'name'                  => _x( $label, 'post type general name', 'post-types-unlimited' ),
					'singular_name'         => _x( $singular_name, 'post type singular name', 'post-types-unlimited' ),
					'add_new'               => $add_new,
					'add_new_item'          => $add_new_item,
					'new_item'              => $new_item,
					'edit_item'             => $edit_item,
					'view_item'             => $view_item,
					'view_items'            => $view_items,
					'all_items'             => $all_items,
					'search_items'          => $search_items,
					'parent_item_colon'     => $parent_item_colon,
					'not_found'             => $not_found,
					'not_found_in_trash'    => $not_found_in_trash,
					'archives'              => $archives,
					'attributes'            => $attributes,
					'insert_into_item'      => $insert_into_item,
					'uploaded_to_this_item' => $uploaded_to_this_item,
					'filter_items_list'     => $filter_items_list,
					'items_list_navigation' => $items_list_navigation,
					'items_list'            => $items_list,
				);

				// Custom labels
				if ( array_key_exists( '_ptu_labels_featured_image', $meta ) ) {
					$labels[ 'featured_image' ] = $meta[ '_ptu_labels_featured_image' ][0];
				}
				if ( array_key_exists( '_ptu_labels_set_featured_image', $meta ) ) {
					$labels[ 'set_featured_image' ] = $meta[ '_ptu_labels_set_featured_image' ][0];
				}
				if ( array_key_exists( '_ptu_labels_remove_featured_image', $meta ) ) {
					$labels[ 'remove_featured_image' ] = $meta[ '_ptu_labels_remove_featured_image' ][0];
				}
				if ( array_key_exists( '_ptu_labels_use_featured_image', $meta ) ) {
					$labels[ 'use_featured_image' ] = $meta[ '_ptu_labels_use_featured_image' ][0];
				}
				if ( array_key_exists( '_ptu_menu_name', $meta ) ) {
					$labels[ 'menu_name' ] = $meta[ '_ptu_menu_name' ][0];
				}
				if ( array_key_exists( '_ptu_labels_name_admin_bar', $meta ) ) {
					$labels[ 'name_admin_bar' ] = $meta[ '_ptu_labels_name_admin_bar' ][0];
				}

				// Get args from meta
				$description         = array_key_exists( '_ptu_description', $meta ) ? __( $meta[ '_ptu_description' ][0], 'post-types-unlimited' ) : '';
				$public              = array_key_exists( '_ptu_public', $meta ) ? $meta[ '_ptu_public' ][0] : true;
				$publicly_queryable  = array_key_exists( '_ptu_publicly_queryable', $meta ) ? $meta[ '_ptu_publicly_queryable' ][0] : true;
				$exclude_from_search = array_key_exists( '_ptu_exclude_from_search', $meta ) ? $meta[ '_ptu_exclude_from_search' ][0] : false;
				$show_ui             = array_key_exists( '_ptu_show_ui', $meta ) ? $meta[ '_ptu_show_ui' ][0] : true;
				$show_in_nav_menus   = array_key_exists( '_ptu_show_in_nav_menus', $meta ) ? $meta[ '_ptu_show_in_nav_menus' ][0] : true;
				$show_in_menu        = array_key_exists( '_ptu_show_in_menu', $meta ) ? $meta[ '_ptu_show_in_menu' ][0] : true;
				$show_in_menu_string = array_key_exists( '_ptu_show_in_menu_string', $meta ) ? $meta[ '_ptu_show_in_menu_string' ][0] : '';
				$show_in_admin_bar   = array_key_exists( '_ptu_show_in_admin_bar', $meta ) ? $meta[ '_ptu_show_in_admin_bar' ][0] : false;
				$capability_type     = array_key_exists( '_ptu_capability_type', $meta ) ? $meta[ '_ptu_capability_type' ][0] : 'post';
				$has_archive         = array_key_exists( '_ptu_has_archive', $meta ) ? $meta[ '_ptu_has_archive' ][0] : false;
				$hierarchical        = array_key_exists( '_ptu_hierarchical', $meta ) ? $meta[ '_ptu_hierarchical' ][0] : false;
				$menu_position       = array_key_exists( '_ptu_menu_position', $meta ) ? $meta[ '_ptu_menu_position' ][0] : 50;
				$menu_icon           = array_key_exists( '_ptu_menu_icon', $meta ) ? $meta[ '_ptu_menu_icon' ][0] : null;
				$supports            = array_key_exists( '_ptu_supports', $meta ) ? get_post_meta( $type_id, '_ptu_supports', true ) : array( 'title', 'editor' );
				$taxonomies          = array_key_exists( '_ptu_taxonomies', $meta ) ? get_post_meta( $type_id, '_ptu_taxonomies', true ) : array();

				$query_var           = array_key_exists( '_ptu_query_var', $meta ) ? $meta[ '_ptu_query_var' ][0] : true;
				$show_in_rest        = array_key_exists( '_ptu_show_in_rest', $meta ) ? $meta[ '_ptu_show_in_rest' ][0] : false;
				$rewrite             = array_key_exists( '_ptu_rewrite', $meta ) ? $meta[ '_ptu_rewrite' ][0] : true;
				$slug                = array_key_exists( '_ptu_slug', $meta ) ? $meta[ '_ptu_slug' ][0] : '';
				$with_front          = array_key_exists( '_ptu_with_front', $meta ) ? $meta[ '_ptu_with_front' ][0] : true;

				// Define Post Type Arguments
				$args = array(
					'labels'              => $labels,
					'description'         => $description,
					'public'              => wp_validate_boolean( $public ),
					'publicly_queryable'  => wp_validate_boolean( $publicly_queryable ),
					'exclude_from_search' => wp_validate_boolean( $exclude_from_search ),
					'show_ui'             => wp_validate_boolean( $show_ui ),
					'show_in_nav_menus'   => wp_validate_boolean( $show_in_nav_menus ),
					'show_in_menu'        => $show_in_menu_string ? $show_in_menu_string : wp_validate_boolean( $show_in_menu ),
					'show_in_admin_bar'   => wp_validate_boolean( $show_in_admin_bar ),
					'query_var'           => wp_validate_boolean( $query_var ),
					'show_in_rest'        => wp_validate_boolean( $show_in_rest ),
					'capability_type'     => $capability_type,
					'has_archive'         => wp_validate_boolean( $has_archive ),
					'hierarchical'        => wp_validate_boolean( $hierarchical ),
					'menu_position'       => absint( $menu_position ),
					'menu_icon'           => $menu_icon ? 'dashicons-' . $menu_icon : null,
					'supports'            => $supports,
					'taxonomies'          => is_array( $taxonomies ) ? $taxonomies : array(),
				);

				if ( $rewrite ) {
					$args[ 'rewrite' ] = array(
						'slug'       => $slug,
						'with_front' => wp_validate_boolean( $with_front ),
					);
				} else {
					$args[ 'rewrite' ] = false;
				}

				// Register the custom post type
				register_post_type( $name, $args );

			}

		}

	}

	/**
	 * Register new admin columns for the ptu type.
	 *
	 * @since 1.0.3
	 */
	public function edit_columns( $columns ) {
		unset( $columns[ 'date' ] ); // no need for date.
		$columns[ 'admin_icon' ]         = __( 'Admin Icon', 'post-types-unlimited' );
		$columns[ 'slug' ]               = __( 'Slug', 'post-types-unlimited' );
		$columns[ 'public' ]             = __( 'Public', 'post-types-unlimited' );
		$columns[ 'publicly_queryable' ] = __( 'Publicly Queryable', 'post-types-unlimited' );
		$columns[ 'has_archive' ]        = __( 'Has Archive', 'post-types-unlimited' );
		return $columns;
	}


	/**
	 * Display new admin columns for the ptu type.
	 *
	 * @since 1.0.3
	 */
	public function column_display( $column, $post_id ) {

		switch ( $column ) :

			case 'admin_icon':

				$admin_icon = get_post_meta( $post_id, '_ptu_menu_icon', true );

				if ( ! empty( $admin_icon ) ) {
					echo '<span class="screen-reader-text">' . esc_html( $admin_icon ) . '</span>';
					echo '<span class="dashicons dashicons-' . sanitize_html_class( $admin_icon ) . '" aria-hidden="true" style="font-size:38px;height:auto;width:auto;"><span>';
				} else {
					echo '&#8212;';
				}

				break;

			case 'slug':

				$slug = get_post_meta( $post_id, '_ptu_slug', true );

				if ( empty( $slug ) ) {
					$slug = get_post_meta( $post_id, '_ptu_name', true );
				}

				if ( ! empty( $slug ) ) {
					echo esc_html( $slug );
				} else {
					echo '&#8212;';
				}

				break;

			case 'public':

				$public = get_post_meta( $post_id, '_ptu_public', true );

				if ( ! empty( $public ) ) {
					echo '<span class="screen-reader-text">' . esc_html__( 'yes', 'post-types-unlimited' ) . '</span>';
					echo '<span class="dashicons dashicons-yes" aria-hidden="true"><span>';
				} else {
					echo '<span class="screen-reader-text">' . esc_html__( 'no', 'post-types-unlimited' ) . '</span>';
					echo '<span class="dashicons dashicons-no-alt" aria-hidden="true"><span>';
				}

				break;

			case 'publicly_queryable':

				$publicly_queryable = get_post_meta( $post_id, '_ptu_publicly_queryable', true );

				if ( ! empty( $publicly_queryable ) ) {
					echo '<span class="screen-reader-text">' . esc_html__( 'yes', 'post-types-unlimited' ) . '</span>';
					echo '<span class="dashicons dashicons-yes" aria-hidden="true"><span>';
				} else {
					echo '<span class="screen-reader-text">' . esc_html__( 'no', 'post-types-unlimited' ) . '</span>';
					echo '<span class="dashicons dashicons-no-alt" aria-hidden="true"><span>';
				}

				break;

			case 'has_archive':

				$has_archive = get_post_meta( $post_id, '_ptu_has_archive', true );

				if ( ! empty( $has_archive ) ) {
					echo '<span class="screen-reader-text">' . esc_html__( 'yes', 'post-types-unlimited' ) . '</span>';
					echo '<span class="dashicons dashicons-yes" aria-hidden="true"><span>';
				} else {
					echo '<span class="screen-reader-text">' . esc_html__( 'no', 'post-types-unlimited' ) . '</span>';
					echo '<span class="dashicons dashicons-no-alt" aria-hidden="true"><span>';
				}

				break;

		endswitch;

	}

}
new PostTypes;