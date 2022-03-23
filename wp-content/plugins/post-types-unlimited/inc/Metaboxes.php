<?php
/**
 * Metaboxes
 *
 * @author  WPExplorer
 * @package PTU/Classes
 * @version 1.0.3
 */

namespace PTU;

defined( 'ABSPATH' ) || exit;

class Metaboxes {
	public $version = '1.0.2'; // used for css/js scripts.

	/**
	 * Default metabox settings.
	 *
	 * @var   array
	 * @since 1.0
	 */
	protected $defaults = array(
		'id'       => '',
		'title'    => '',
		'screen'   => array( 'post' ),
		'context'  => 'normal',
		'priority' => 'high',
		'classes'  => array(),
		'fields'   => array(),
	);

	/**
	 * Metabox ID.
	 *
	 * @var   string
	 * @since 1.0
	 */
	protected $id = '';

	/**
	 * Array of custom metabox settings.
	 *
	 * @var   array
	 * @since 1.0
	 */
	protected $metabox = array();

	/**
	 * Register this class with the WordPress API.
	 *
	 * @since 1.0
	 *
	 * @access public
	 * @param array $metabox Array of metabox settings|fields.
	 * @return void
	 */
	public function __construct( $metabox ) {

		// Parse metabox args
		$this->metabox = wp_parse_args( $metabox, $this->defaults );

		// Fields are required
		if ( empty( $this->metabox[ 'fields' ] ) ) {
			return;
		}

		// Add metaboxes
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );

		// Save meta
		add_action( 'save_post', array( $this, 'save_meta_data' ) );

		// Load scripts for the metabox
		add_action( 'admin_enqueue_scripts', array( $this, 'load_scripts' ) );

	}

	/**
	 * The function responsible for creating the actual meta boxes.
	 *
	 * @since 1.0
	 *
	 * @access public
	 * @return void
	 */
	public function add_meta_box() {

		add_meta_box(
			$this->metabox[ 'id' ],
			$this->metabox[ 'title' ],
			array( $this, 'display_meta_box' ),
			$this->metabox[ 'screen' ],
			$this->metabox[ 'context' ],
			$this->metabox[ 'priority' ]
		);

		if ( ! empty( $this->metabox[ 'classes' ] ) && is_array( $this->metabox[ 'screen' ] ) ) {
			foreach( $this->metabox[ 'screen' ] as $screen ) {
				add_filter( 'postbox_classes_' . $screen . '_' . $this->metabox[ 'id' ], array( $this, 'postbox_classes' ) );
			}
		}

	}

	/**
	 * Add custom classes to the metabox.
	 *
	 * @since 1.0
	 * @var $classes array
	 * @access public
	 * @return $classes
	 */
	public function postbox_classes( $classes ) {
		if ( is_array( $this->metabox[ 'classes' ] ) ) {
			foreach( $this->metabox[ 'classes' ] as $class ) {
				array_push( $classes, $class );
			}
		}
		return $classes;
	}

	/**
	 * Enqueue scripts and styles needed for the metaboxes.
	 *
	 * @since 1.0
	 *
	 * @access public
	 * @param string $hook Current admin page hook prefix.
	 * @return void
	 */
	public function load_scripts( $hook ) {

		// Only needed on these admin screens
		if ( $hook != 'edit.php'
			&& $hook != 'post.php'
			&& $hook != 'post-new.php'
			&& $hook != 'page-new.php'
			&& $hook != 'page.php'
		) {
			return;
		}

		// Get global post
		global $post;

		// Return if post is not object or it's the wrong type
		if ( ! is_object( $post ) || ! in_array( $post->post_type, $this->metabox[ 'screen' ] ) ) {
			return;
		}

		// Enqueue metabox css
		wp_enqueue_style(
			'ptu-metaboxes',
			plugin_dir_url( dirname(__FILE__) ) . 'assets/css/ptu-metaboxes.css',
			array(),
			$this->version
		);

	}

	/**
	 * Renders the content of the meta box.
	 *
	 * @since 1.0
	 *
	 * @access public
	 * @param obj $post Current post being shown in the admin.
	 * @return void
	 */
	public function display_meta_box( $post ) {

		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'ptu_metabox_' . $this->metabox[ 'id' ], 'ptu_metabox_nonce_' . $this->metabox[ 'id' ] );

		// Get options
		$fields = $this->metabox[ 'fields' ]; ?>

		<div class="ptu-metabox">

			<table class="form-table">

				<?php
				// Loop through sections and store meta output
				foreach ( $fields as $key => $field ) {

					// Defaults
					$defaults = array(
						'name'    => '',
						'id'      => '',
						'type'    => '',
						'desc'    => '',
						'default' => '',
					);

					// Parse and extract
					$field = wp_parse_args( $field, $defaults );

					// Add prefix to id
					$field[ 'id' ] = '_ptu_' . $field[ 'id' ];

					// Get field values
					$custom_field_keys = get_post_custom_keys();
					if ( is_array( $custom_field_keys ) && in_array( $field[ 'id' ], $custom_field_keys ) ) {
						$value = get_post_meta( $post->ID, $field[ 'id' ], true );
					} else {
						$value = $field[ 'default' ];
					} ?>

					<tr id="<?php echo esc_attr( $field[ 'id' ] ); ?>_tr">

						<?php if ( $field[ 'name' ] ) { ?>

							<th>

								<?php if ( 'multi_select' !== $field[ 'type'] ) { ?>
									<label class="ptu-label" for="<?php echo esc_attr( $field[ 'id' ] ); ?>">
										<strong><?php echo esc_html( $field[ 'name' ] ); ?></strong>
									</label>
								<?php } else { ?>
									<span class="ptu-label">
										<strong><?php echo esc_html( $field[ 'name' ] ); ?></strong>
									</span>
								<?php } ?>

								<?php if ( ! empty( $field[ 'desc' ] ) ) { ?>
									<p class="ptu-mb-description"><?php echo wp_kses_post( $field[ 'desc' ] ); ?></p>
								<?php } ?>

							</th>

						<?php } ?>

						<?php
						// Output field type
						$method = 'field_' . $field[ 'type' ];

						if ( method_exists( $this, $method ) ) {

							$expand = empty( $field[ 'name' ] ) ? ' colspan="2"' : '';

							echo '<td' . $expand . '>' . $this->$method( $field, $value ) . '</td>';

						} ?>

					</tr>

				<?php } ?>

			</table>

		</div>

	<?php }

	/**
	 * Render a text field type.
	 *
	 * @since 1.0
	 */
	public function field_text( $field, $value ) {
		$required    = isset( $field[ 'required' ] ) ? ' required' : '';
		$maxlength   = isset( $field[ 'maxlength' ] ) ? ' maxlength="' . $field[ 'maxlength' ] . '"' : '';
		$placeholder = ! empty( $field[ 'placeholder' ] ) ? ' placeholder="' . esc_attr( $field[ 'placeholder' ] ) . '"' : '';
		return '<input id="' . esc_attr( $field[ 'id' ] ) . '" name="' . esc_attr( $field[ 'id' ] ) . '" type="text" value="' . esc_attr( $value ) . '" ' . $required . $maxlength . $placeholder . '>';
	}

	/**
	 * Render a number field type.
	 *
	 * @since 1.0
	 */
	public function field_number( $field, $value ) {
		$step        = isset( $field[ 'step' ] ) ? $field[ 'step' ] : 1;
		$min         = isset( $field[ 'min' ] ) ? $field[ 'min' ] : 1;
		$max         = isset( $field[ 'max' ] ) ? $field[ 'max' ] : 200;
		$placeholder = ! empty( $field[ 'placeholder' ] ) ? ' placeholder="' . esc_attr( $field[ 'placeholder' ] ) . '"' : '';
		return '<input id="' . esc_attr( $field[ 'id' ] ) . '" name="' . esc_attr( $field[ 'id' ] ) . '" type="number" value="' .  esc_attr( $value ) . '" step="' . absint( $step ) . '" min="' . floatval( $min ) .'" max="' . floatval( $max ) .'"' . $placeholder . '>';
	}

	/**
	 * Render a textare field type.
	 *
	 * @since 1.0
	 */
	public function field_textarea( $field, $value ) {
		$rows = isset ( $field[ 'rows' ] ) ? $field[ 'rows' ] : 4;
		return '<textarea id="' . esc_attr( $field[ 'id' ] ) . '" rows="' . absint( $rows ) . '" name="' . esc_attr( $field[ 'id' ] ) . '">' . wp_kses_post( $value ) . '</textarea>';
	}

	/**
	 * Render a checkbox field type.
	 *
	 * @since 1.0
	 */
	public function field_checkbox( $field, $value ) {
		$value   = $value ? true : false;
		$checked = checked( $value, true, false );
		return '<input id="' . esc_attr( $field[ 'id' ] ) . '" name="' . esc_attr( $field[ 'id' ] ) . '" type="checkbox" ' . $checked . '>';
	}

	/**
	 * Render a select field type.
	 *
	 * @since 1.0
	 */
	public function field_select( $field, $value ) {

		$choices = isset ( $field[ 'choices' ] ) ? $field[ 'choices' ] : array();

		if ( empty( $choices ) ) {
			return;
		}

		$output = '<select id="' . esc_attr( $field[ 'id' ] ) . '" name="' . esc_attr( $field[ 'id' ] ) . '">';

			foreach ( $choices as $choice_v => $name ) {

				$selected = selected( $value, $choice_v, false );

				$output .= '<option value="' .  esc_attr( $choice_v ) . '" ' . $selected . '>' . esc_attr( $name ) . '</option>';

			}

		$output .= '</select>';

		return $output;

	}

	/**
	 * Render a multi_select field type.
	 *
	 * @since 1.0
	 */
	public function field_multi_select( $field, $value ) {

		$value   = is_array( $value ) ? $value : array();
		$choices = isset ( $field[ 'choices' ] ) ? $field[ 'choices' ] : array();

		if ( empty( $choices ) ) {
			return;
		}

		$output = '<fieldset>';

		foreach ( $choices as $choice_v => $name ) {

			$field_id = $field[ 'id' ] . '_' . $choice_v;
			$selected = checked( in_array( $choice_v, $value ), true, false );

			$output .= '<input id="' . esc_attr( $field_id ) . '" type="checkbox" name="' . esc_attr( $field[ 'id' ] ) . '[]" value="' .  esc_attr( $choice_v ) . '" ' . $selected . '>';

			$output .= '<label for="' . esc_attr( $field_id ) . '">' . esc_attr( $name ) . '</label>';

			$output .= '<br />';

		}

		$output .= '</fieldset>';

		return $output;

	}

	/**
	 * Render a dashicon field type.
	 *
	 * @since 1.0
	 */
	public function field_dashicon( $field, $value ) {

		$dashicons = $this->get_dashicons();

		if ( empty( $dashicons ) ) {
			return;
		}

		wp_enqueue_script(
			'ptu-dashicon-select',
			plugin_dir_url( dirname(__FILE__) ) . 'assets/js/ptu-dashicon-select.js',
			array( 'jquery' ),
			$this->version,
			true
		);

		$output = '';

			$output .= '<div class="ptu-meta-icon-select-wrap">';

				$output .= '<input type="text" name="' . esc_attr( $field['id'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $value ) .'">';

				$output .= ' <button class="button-secondary" type="button">' . esc_html__( 'Select Icon', 'post-types-unlimited' ) . '</button>';

				if ( $value ) {
					$output .= '<br /><div class="ptu-meta-icon-select-preview"><span class="dashicons dashicons-' . esc_attr( $value ) . '" aria-hidden="true"></span></div>';
				}

				$output .= '<div class="ptu-meta-icon-select-modal" style="display:none;">';

					$output .= '<div class="ptu-meta-icon-select-modal-inner">';

						$output .= '<span class="screen-reader-text"><label for="ptu-meta-icon-select-search-' . esc_attr( $field[ 'id' ] ) . '">' . esc_html__( 'Search for an icon', 'post-types-unlimited' ) . '</label></span>';

						$output .= '<input class="ptu-meta-icon-select-search" id="ptu-meta-icon-select-search-' . esc_attr( $field[ 'id' ] ) . '" type="search" placeholder="' . esc_html__( 'Search for an icon', 'post-types-unlimited' ) . '&hellip;">';

						$output .= '<div class="ptu-meta-icon-select-modal-choices">';

							foreach ( $dashicons as $name => $code ) {

								$output .= '<a href="#" title="' . esc_html( $name ) . '" data-value="' . esc_attr( $name ) . '"><span class="dashicons dashicons-' . esc_html( $name ) . '"></span></a>';
							}

						$output .= '</div>';

						$output .= '<button class="button-primary ptu-meta-close">' . esc_html__( 'Close', 'post-types-unlimited' ) . '</button>';

					$output .= '</div>';

				$output .= '</div>';

			$output .= '</div>';

		$output .= '</td>';

		return $output;

	}

	/**
	 * Save metabox data.
	 *
	 * @since 1.0
	 */
	public function save_meta_data( $post_id ) {

		/*
		 * We need to verify this came from our screen and with proper authorization,
		 * because the save_post action can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST[ 'ptu_metabox_nonce_' . $this->metabox[ 'id' ] ] ) ) {
			return;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST[ 'ptu_metabox_nonce_' . $this->metabox[ 'id' ] ], 'ptu_metabox_' . $this->metabox[ 'id' ] ) ) {
			return;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check the user's permissions.
		if ( isset( $_POST[ 'post_type' ] ) && 'page' == $_POST[ 'post_type' ] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}

		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}

		}

		/* OK, it's safe for us to save the data now. Now we can loop through fields */

		// Get array of fields to save
		$fields = $this->metabox[ 'fields' ];

		// Return if fields are empty
		if ( empty( $fields ) ) {
			return;
		}

		// Loop through options and validate
		foreach ( $fields as $field ) {

			//print_r( $_POST ); exit;

			// Define loop main vars
			$value = '';
			$id    = '_ptu_' . $field[ 'id' ];

			// Make sure field exists and if so validate the data
			if ( isset( $_POST[$id] ) ) {

				// Get choices for select fields
				$choices = ( 'select' == $field[ 'type' ] && isset( $field[ 'choices' ] ) ) ? $field[ 'choices' ] : array();

				// Sanitize field before inserting into the database
				if ( ! empty( $field[ 'sanitize_callback' ] ) ) {
					$value = call_user_func( $field[ 'sanitize_callback' ], $field, $_POST[$id] );
				} else {
					$value = $this->sanitize_value_for_db( $_POST[$id], $field );
				}

				// Update meta if value exists
				if ( $value || '0' == $value ) {
					update_post_meta( $post_id, $id, $value );
				}

				// Delete if value is empty
				else {
					delete_post_meta( $post_id, $id );
				}

			} else {

				if ( 'checkbox' == $field[ 'type' ] && ! empty( $field[ 'default'] ) ) {
					update_post_meta( $post_id, $id, false );
				} else {
					delete_post_meta( $post_id, $id );
				}

			}

		}

		// Check if we need to flush rewrite rules since we are saving a custom post type or taxonomy
		$post_type = get_post_type( $post_id );

		if ( 'ptu' == $post_type || 'ptu_tax' == $post_type ) {
			update_option( 'ptu_flush_rewrite_rules', true );
		}

	}

	/**
	 * Sanitize input values before inserting into the database.
	 *
	 * @since 1.0
	 */
	public function sanitize_value_for_db( $input, $field ) {

		$type = $field[ 'type' ];

		switch ( $type ) {
			case 'text':
				return sanitize_text_field( $input );
				break;
			case 'number':
				if ( '' !== trim( $input ) ) {
					return intval( $input ); // prevent empty field from saving as 0
				}
				break;
			case 'textarea':
				return sanitize_textarea_field( $input );
				break;
			case 'dashicon':
				return array_key_exists( $input, $this->get_dashicons() ) ? sanitize_textarea_field( $input ) : null;
				break;
			case 'checkbox':
				return isset( $input ) ? true : false;
				break;
			case 'select':
				if ( in_array( $input, $field[ 'choices' ] ) || array_key_exists( $input, $field[ 'choices' ] ) ) {
					return esc_attr( $input );
				}
				break;
			case 'multi_select':
				if ( ! is_array( $input ) ) {
					return isset( $field[ 'default' ] ) ? $field[ 'default' ] : array();
				}
				$checks = true;
				foreach( $input as $v ) {
					if ( ! in_array( $v, $field[ 'choices' ] ) && ! array_key_exists( $v, $field[ 'choices' ] ) ) {
						$checks = false;
						break;
					}
				}
				return $checks ? $input : array();
				break;
			default:
				return wp_strip_all_tags( $input );
				break;
		}

	}

	/**
	 * Returns an array of dashicons
	 *
	 * @since 1.0
	 */
	public function get_dashicons() {
		$dashicons = array(
			'admin-appearance' => 'f100',
			'admin-collapse' => 'f148',
			'admin-comments' => 'f117',
			'admin-generic' => 'f111',
			'admin-home' => 'f102',
			'admin-media' => 'f104',
			'admin-network' => 'f112',
			'admin-page' => 'f133',
			'admin-plugins' => 'f106',
			'admin-settings' => 'f108',
			'admin-site' => 'f319',
			'admin-tools' => 'f107',
			'admin-users' => 'f110',
			'align-center' => 'f134',
			'align-full-width' => 'f114',
			'align-pull-left' => 'f10a',
			'align-pull-right' => 'f10b',
			'align-wide' => 'f11b',
			'align-left' => 'f135',
			'align-none' => 'f138',
			'align-right' => 'f136',
			'analytics' => 'f183',
			'arrow-down' => 'f140',
			'arrow-down-alt' => 'f346',
			'arrow-down-alt2' => 'f347',
			'arrow-left' => 'f141',
			'arrow-left-alt' => 'f340',
			'arrow-left-alt2' => 'f341',
			'arrow-right' => 'f139',
			'arrow-right-alt' => 'f344',
			'arrow-right-alt2' => 'f345',
			'arrow-up' => 'f142',
			'arrow-up-alt' => 'f342',
			'arrow-up-alt2' => 'f343',
			'art' => 'f309',
			'awards' => 'f313',
			'backup' => 'f321',
			'block-default' => 'f12b',
			'button' => 'f11a',
			'book' => 'f330',
			'book-alt' => 'f331',
			'businessman' => 'f338',
			'calendar' => 'f145',
			'camera' => 'f306',
			'cart' => 'f174',
			'category' => 'f318',
			'chart-area' => 'f239',
			'chart-bar' => 'f185',
			'chart-line' => 'f238',
			'chart-pie' => 'f184',
			'clock' => 'f469',
			'cloud' => 'f176',
			'cloud-saved' => 'f137',
			'cloud-upload' => 'f13b',
			'cover-image' => 'f13d',
			'columns' => 'f13c',
			'dashboard' => 'f226',
			'desktop' => 'f472',
			'dismiss' => 'f153',
			'download' => 'f316',
			'edit' => 'f464',
			'editor-aligncenter' => 'f207',
			'editor-alignleft' => 'f206',
			'editor-alignright' => 'f208',
			'editor-bold' => 'f200',
			'editor-customchar' => 'f220',
			'editor-distractionfree' => 'f211',
			'editor-help' => 'f223',
			'editor-indent' => 'f222',
			'editor-insertmore' => 'f209',
			'editor-italic' => 'f201',
			'editor-justify' => 'f214',
			'editor-kitchensink' => 'f212',
			'editor-ol' => 'f204',
			'editor-outdent' => 'f221',
			'editor-paste-text' => 'f217',
			'editor-paste-word' => 'f216',
			'editor-quote' => 'f205',
			'editor-removeformatting' => 'f218',
			'editor-rtl' => 'f320',
			'editor-spellcheck' => 'f210',
			'editor-strikethrough' => 'f224',
			'editor-textcolor' => 'f215',
			'editor-ul' => 'f203',
			'editor-underline' => 'f213',
			'editor-unlink' => 'f225',
			'editor-video' => 'f219',
			'exit' => 'f14a',
			'heading' => 'f10e',
			'html' => 'f14b',
			'info-outline' => 'f14c',
			'insert-after' => 'f14d',
			'insert-before' => 'f14e',
			'insert' => 'f10f',
			'remove' => 'f14f',
			'shortcode' => 'f150',
			'email' => 'f465',
			'email-alt' => 'f466',
			'embed-audio' => 'f13e',
			'embed-photo' => 'f144',
			'embed-post' => 'f146',
			'embed-video' => 'f149',
			'exerpt-view' => 'f164',
			'facebook' => 'f304',
			'facebook-alt' => 'f305',
			'feedback' => 'f175',
			'flag' => 'f227',
			'format-aside' => 'f123',
			'format-audio' => 'f127',
			'format-chat' => 'f125',
			'format-gallery' => 'f161',
			'format-image' => 'f128',
			'format-links' => 'f103',
			'format-quote' => 'f122',
			'format-standard' => 'f109',
			'format-status' => 'f130',
			'format-video' => 'f126',
			'forms' => 'f314',
			'googleplus' => 'f462',
			'groups' => 'f307',
			'hammer' => 'f308',
			'id' => 'f336',
			'id-alt' => 'f337',
			'image-crop' => 'f165',
			'image-flip-horizontal' => 'f169',
			'image-flip-vertical' => 'f168',
			'image-rotate-left' => 'f166',
			'image-rotate-right' => 'f167',
			'images-alt' => 'f232',
			'images-alt2' => 'f233',
			'info' => 'f348',
			'leftright' => 'f229',
			'lightbulb' => 'f339',
			'list-view' => 'f163',
			'location' => 'f230',
			'location-alt' => 'f231',
			'lock' => 'f160',
			'marker' => 'f159',
			'menu' => 'f333',
			'migrate' => 'f310',
			'minus' => 'f460',
			'networking' => 'f325',
			'no' => 'f158',
			'no-alt' => 'f335',
			'performance' => 'f311',
			'plus' => 'f132',
			'portfolio' => 'f322',
			'post-status' => 'f173',
			'pressthis' => 'f157',
			'products' => 'f312',
			'redo' => 'f172',
			'rss' => 'f303',
			'screenoptions' => 'f180',
			'search' => 'f179',
			'share' => 'f237',
			'share-alt' => 'f240',
			'share-alt2' => 'f242',
			'shield' => 'f332',
			'shield-alt' => 'f334',
			'slides' => 'f181',
			'smartphone' => 'f470',
			'smiley' => 'f328',
			'sort' => 'f156',
			'sos' => 'f468',
			'star-empty' => 'f154',
			'star-filled' => 'f155',
			'star-half' => 'f459',
			'tablet' => 'f471',
			'tag' => 'f323',
			'testimonial' => 'f473',
			'translation' => 'f326',
			'trash' => 'f182',
			'twitter' => 'f301',
			'undo' => 'f171',
			'update' => 'f463',
			'upload' => 'f317',
			'vault' => 'f178',
			'video-alt' => 'f234',
			'video-alt2' => 'f235',
			'video-alt3' => 'f236',
			'visibility' => 'f177',
			'welcome-add-page' => 'f133',
			'welcome-comments' => 'f117',
			'welcome-edit-page' => 'f119',
			'welcome-learn-more' => 'f118',
			'welcome-view-site' => 'f115',
			'welcome-widgets-menus' => 'f116',
			'wordpress' => 'f120',
			'wordpress-alt' => 'f324',
			'yes' => 'f147',
			'table-col-after' => 'f151',
			'table-col-before' => 'f152',
			'table-col-delete' => 'f15a',
			'table-row-after' => 'f15b',
			'table-row-before' => 'f15c',
			'table-row-delete' => 'f15d',
			'saved' => 'f15e',
			'database-add' => 'f170',
			'database-export' => 'f17a',
			'database-import' => 'f17b',
			'database-remove' => 'f17c',
			'database-view' => 'f17d',
			'database' => 'f17e',
			'airplane' => 'f15f',
			'car' => 'f16b',
			'calculator' => 'f16e',
			'games' => 'f18a',
			'printer' => 'f193',
			'beer' => 'f16c',
			'coffee' => 'f16f',
			'drumstick' => 'f17f',
			'food' => 'f187',
			'bank' => 'f16a',
			'hourglass' => 'f18c',
			'money-alt' => 'f18e',
			'open-folder' => 'f18f',
			'pdf' => 'f190',
			'pets' => 'f191',
			'privacy' => 'f194',
			'superhero' => 'f198',
			'superhero-alt' => 'f197',
			'edit-page' => 'f186',
			'fullscreen-alt' => 'f188',
			'fullscreen-exit-alt' => 'f189',
		);
		ksort( $dashicons );
		return apply_filters( 'ptu_dashicons_list', $dashicons );
	}

}

function ptu_metaboxes() {
	$metaboxes = apply_filters( 'ptu_metaboxes', array() );
	if ( ! $metaboxes ) {
		return;
	}
	foreach ( $metaboxes as $metabox ) {
		new \PTU\Metaboxes( $metabox );
	}
}
add_action( 'admin_init', '\PTU\ptu_metaboxes' );