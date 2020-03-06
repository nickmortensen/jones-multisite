<?php
/**
 * The 'project' custom post type.
 *
 * Registers post type name, labels, & parameters.
 *
 * @link       https://github.com/nickmortensen
 * @since      1.0.0
 * @package    Jones_Multisites
 * @subpackage Jones_Multisites/admin
 */

/**
 * The 'project' post type.
 *
 * Registers posttype name, labels, & parameters.
 *
 * @package    Jones_Multisites
 * @subpackage Jones_Multisites/admin
 * @author     Nick Mortensen <nmortensen@jonessign.com>
 */
class Project {
	/**
	 * The arguments of this posttype.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $type The arguments of this posttype..
	 */
	private $type = 'project';
	/**
	 * The slug of this posttype.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $slug The slug of this posttype..
	 */
	private $slug = 'project';
	/**
	 * The name of this posttype.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $name The slug of this posttype..
	 */
	private $name = 'project';
	/**
	 * The singular of this posttype.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $name The slug of this posttype..
	 */
	private $singular_name = 'project';
	/**
	 * The plural of this posttype.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $name The slug of this posttype..
	 */
	private $plural_name = 'projects';
	/**
	 * The size of the thumbnail.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $size The chosen size of the thumbnail (wp media imge-size for the list to choose among).
	 */
	private $size = 'thumbnail';

	/**
	 * The taxonomies to apply to this posttype.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $taxonomies_to_apply The taxonomies to apply to this post type.
	 */
	private $taxonomies_to_apply = [ 'expertise', 'signtype', 'services' ];

	/**
	 * The icon to use for this posttype.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $icon_for_posttype The icon (typically from among the dashicons collection) to apply to this post type.
	 */
	private $icon_for_posttype = 'dashicons-admin-multisite';

	/**
	 * List of states. To translate, pass array of states in the 'state_list' field param.
	 *
	 * @var array
	 */
	//phpcs:disable
	protected static $state_list = [ 'AL' => 'Alabama', 'AK' => 'Alaska', 'AZ' => 'Arizona', 'AR' => 'Arkansas', 'CA' => 'California', 'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware', 'DC' => 'District Of Columbia', 'FL' => 'Florida', 'GA' => 'Georgia', 'HI' => 'Hawaii', 'ID' => 'Idaho', 'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa', 'KS' => 'Kansas', 'KY' => 'Kentucky', 'LA' => 'Louisiana', 'ME' => 'Maine', 'MD' => 'Maryland', 'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota', 'MS' => 'Mississippi', 'MO' => 'Missouri', 'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada', 'NH' => 'New Hampshire', 'NJ' => 'New Jersey', 'NM' => 'New Mexico', 'NY' => 'New York', 'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio', 'OK' => 'Oklahoma', 'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' => 'South Carolina', 'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah', 'VT' => 'Vermont', 'VA' => 'Virginia', 'WA' => 'Washington', 'WV' => 'West Virginia', 'WI' => 'Wisconsin', 'WY' => 'Wyoming' ];
	//phpcs:enable

	/**
	 * Event constructor.
	 *
	 * When class is instantiated, this is what happens.
	 */
	public function __construct() {
		// Creates an 'address' field in CMB2 - really a collection of other fields thast is used as a field.
		add_filter( 'cmb2_render_address', [ $this, 'render_address_field_callback' ], 10, 5 );
		// Creates a Star Rating field type in CMB2 -- @link: https://github.com/CMB2/CMB2-Snippet-Library/blob/master/custom-field-types/star-rating-field-type/star-rating-field-type.php.
		add_filter( 'cmb2_render_rating', [ $this, 'cmb2_render_rating_field_callback' ], 10, 5 );
		// Register the post type.
		add_action( 'init', [ $this, 'register' ] );
		// Setup the extra fields for the post type.
		add_action( 'cmb2_init', [ $this, 'register_posttype_metabox' ] );
		// Add star ratings to attachment posts.
		add_action( 'cmb2_admin_init', [ $this, 'cmb2_star_rating_metabox' ] );
		// Create additional adminstrator table columns for the post type.
		add_filter( 'manage_' . $this->type . '_posts_columns', [ $this, 'setup_new_admin_columns' ] );
		// Populate data in the newly added admin columns for the post type.
		add_action( 'manage_' . $this->type . '_posts_custom_column', [ $this, 'populate_custom_columns' ], 20, 2 );
		// Make new columns for this post type sortable.
		add_action( 'manage_edit-' . $this->type . '_sortable_columns', [ $this, 'sortable_columns' ] );
	}

	/**
	 * Register this as a custom post type.
	 */
	public function register() {
		$plural   = $this->plural_name;
		$singular = $this->singular_name;
		$labels   = [
			'name'                  => ucfirst( $plural ),
			'singular_name'         => ucfirst( $singular ),
			'menu_name'             => ucfirst( $singular ),
			'name_admin_bar'        => ucfirst( $singular ),
			'archives'              => ucfirst( $singular ) . ' Archives',
			'attributes'            => 'Attributes',
			'parent_item_colon'     => 'Parent Item: ',
			'all_items'             => 'all ' . $plural,
			'add_new_item'          => 'Add New ' . ucfirst( $singular ),
			'add_new'               => 'Add New',
			'new_item'              => 'New ' . ucfirst( $singular ),
			'edit_item'             => 'Edit ' . ucfirst( $singular ),
			'update_item'           => 'Update ' . ucfirst( $singular ),
			'view_item'             => 'View ' . ucfirst( $singular ),
			'view_items'            => 'View ' . ucfirst( $plural ),
			'search_items'          => 'Search ' . ucfirst( $plural ),
			'not_found'             => 'Not found',
			'not_found_in_trash'    => 'Not found in Trash',
			'featured_image'        => 'Featured Image',
			'set_featured_image'    => 'Set featured image',
			'remove_featured_image' => 'Remove featured image',
			'use_featured_image'    => 'Use as featured image',
			'insert_into_item'      => 'Insert into item',
			'uploaded_to_this_item' => 'Uploaded to this ' . $singular,
			'items_list'            => ucfirst( $plural ) . ' list',
			'items_list_navigation' => ucfirst( $plural ) . ' list nav',
			'filter_items_list'     => 'Filter ' . ucfirst( $plural ) . ' List',
		];

		$rewrite = [
			'slug'       => $singular,
			'with_front' => true,
			'pages'      => true,
			'feeds'      => true,
		];

		$args = [
			'label'                 => ucfirst( $singular ),
			'description'           => 'Jones Sign Company ' . ucfirst( $plural ) . ' and Details',
			'labels'                => $labels,
			'supports'              => array( 'title', 'thumbnail', 'excerpt', 'post-formats' ),
			'taxonomies'            => $this->taxonomies_to_apply,
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 100,
			'menu_icon'             => $this->icon_for_posttype,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'rewrite'               => $rewrite,
			'capability_type'       => 'page',
			'show_in_rest'          => true,
			'rest_base'             => 'client',
			'rest_controller_class' => 'WP_REST_Client_Controller',
		];
		register_post_type( $singular, $args );
	} //end register.

	/**
	 * Create the extra fields for the attachment posts - specifically star rating.
	 *
	 * Use CMB2 to create additional fields for the client post type.
	 *
	 * @since    1.0.0
	 */
	public function cmb2_star_rating_metabox() {
		$metabox_args = [
			'context'      => 'side',
			'classes'      => [ 'imageStarsMetabox' ],
			'id'           => 'image_stars_metabox',
			'object_types' => [ 'attachment' ],
			'show_in_rest' => WP_REST_Server::ALLMETHODS,
			'show_names'   => false,
			'title'        => 'Star Rating',
			'cmb_styles'   => true, // Disable cmb2 stylesheet by setting to false.
		];

		$metabox = new_cmb2_box( $metabox_args );
		/**
		 * Star Rating Field CUSTOM.
		 *
		 * @see render_star_rating_field_callback().
		 */

		$args = [
			'name'         => 'Star Rating',
			'type'         => 'rating',
			'id'           => 'mediaStars',
			'desc'         => 'star rating for photo',
			'object_types' => [ 'attachment' ],
			'after_row'    => '<hr>',
		];
		$metabox->add_field( $args );
	}//end cmb2_star_rating_metabox()


	/**
	 * Create the extra fields for the post type.
	 *
	 * Use CMB2 to create additional fields for the client post type.
	 *
	 * @since    1.0.0
	 */
	public function register_posttype_metabox() {
		$after        = '<hr>';
		$prefix       = $this->singular_name . '_';
		$metabox_args = [
			'context'      => 'normal',
			'classes'      => $this->singular_name . '-posttype-metabox',
			'id'           => $this->singular_name . '_metabox',
			'object_types' => [ $this->singular_name ],
			'show_in_rest' => WP_REST_Server::ALLMETHODS,
			'show_names'   => true,
			'title'        => ucfirst( $this->singular_name ) . ' Overview',
			'cmb_styles'   => true, // Disable cmb2 stylesheet by setting to false.
		];

		$metabox = new_cmb2_box( $metabox_args );
		/**
		 * Get the label for the project address field;
		 */
		function get_label_cb() {
			return '<span class="indigo" style="font-size: 2.5rem;">Project Location Data </span><hr>';
		}

		/**
		 * Project Location Field. CUSTOM.
		 *
		 * @see render_address_field_callback()
		 */
//phpcs:disable
		$args = [
			'name'                => 'Project Location',
			'desc'                => 'Address of the Project',
			'id'                  => 'projectLocation', // Name of the custom field type we setup.
			'type'                => 'address',
			'object_types'        => [ 'project' ], // Only show on project post types.
			'options'             => [],
			'label_cb'            => 'get_label_cb',
			'repeatable'          => false,
			'text'                => ['add_row_text' => 'Add another location' ],
			'show_names'          => false, // false removes the left cell of the table -- this is worth understanding.
			'classes'             => [ 'repeatable', 'projectLocationAddress' ],
			'after_row'           => '<hr>',

		];
		$metabox->add_field( $args );
//phpcs:enable
		/**
		 * Project local folder within the jones sign company shared servers setup.
		 */
		$args = [
			'name' => 'Local Folder',
			'id'   => 'projectLocalFolder',
			'type' => 'text',
			'desc' => 'Where is the archive on the Jones Sign Internal Servers?',
		];
		$metabox->add_field( $args );

		/**
		 * ID projectJobNumber.
		 * Assigned Project Job Number.
		 */
		$args = [
			'name'         => 'Job#',
			'desc'         => 'Assigned Job Number',
			'default'      => '',
			'id'           => 'projectJobNumber',
			'type'         => 'text_small',
			'object_types' => [ 'project' ], // Only show on project post types.
			'before_row'   => '', // callback.
			'after_row'    => '<hr>',
		];
		$metabox->add_field( $args );

		/**
		 * ID projectJobStatus.
		 * Status of job: upcoming, complete, ongoing.
		 */
		$args = [
			'name'         => 'Status',
			'id'           => 'projectJobStatus',
			'type'         => 'radio_inline',
			'options'      => array(
				'complete' => 'Complete', // completion_year.
				'ongoing'  => 'Ongoing',  // completion_expected.
				'upcoming' => 'Upcoming', // year_started.
			),
			'default'      => '',
			'object_types' => [ 'project' ], // Only show on project post types.
		];
		$metabox->add_field( $args );

		/**
		 * ID projectCompletedYear.
		 * Year Job was completed - dependent on choosing the 'complete' option from the 'jobStatus' field.
		 */
		$args = [
			'before_row'   => '<div id="statusYearInputs">',
			'classes'      => 'statusDate',
			'name'         => 'Complete',
			'desc'         => '4 digit year representing when this project completed',
			'id'           => 'projectCompletedYear', // was 'jobYear' - alter that in your database.
			'type'         => 'text_small',
			'object_types' => [ 'project' ], // Only show on project post types.
			'attributes'   => [
				'data-conditionalid'    => 'projectJobStatus', // the ID value of the field that needs to be selected in order for this one to show up.
				'data-conditionalvalue' => 'complete',
			],
		];
		$metabox->add_field( $args );

		/**
		 * ID projectExpectedCompletionYear.
		 * Year Job is expected to wrap.
		 * Dependent on choosing the 'ongoing' option from the 'projectJobStatus' field.
		 */
		$args = [
			'classes'      => 'statusDate hidden',
			'name'         => 'Expected',
			'desc'         => '4 digit year for when this upcoming project is expected to complete',
			'id'           => 'projectExpectedCompletionYear', // was 'jobYear' - alter that in your database.
			'type'         => 'text_small',
			'object_types' => [ 'project' ], // Only show on project post types.
			'attributes'   => [
				'data-conditionalid'    => 'projectJobStatus', // the ID value of the field that needs to be selected in order for this one to show up.
				'data-conditionalvalue' => 'upcoming',
			],

		];
		$metabox->add_field( $args );

		/**
		 * ID projectYearStarted.
		 * Year Job was started - dependent on choosing the 'ongoing' option from the 'projectJobStatus' field.
		 */
		$args = [
			'after_row'    => '</div>',
			'classes'      => 'statusDate hidden',
			'name'         => 'Started',
			'desc'         => '4 digit year of when we started working on this ongoing project',
			'id'           => 'projectYearStarted',
			'type'         => 'text_small',
			'object_types' => [ 'project' ], // Only show on project post types.
			'attributes'   => [
				'data-conditionalid'    => 'projectJobStatus', // the ID value of the field that needs to be selected in order for this one to show up.
				'data-conditionalvalue' => 'ongoing',
			],
		];
		$metabox->add_field( $args );

		/**
		 * ID projectTease Teaser Field: Text Field.
		 * 140 characters or less summing up the project.
		 */
		$args = [
			'class'        => 'input-full-width',
			'name'         => 'Tease',
			'desc'         => 'Brief Synopsis of the project. 140 characters or less.',
			'default'      => '',
			'id'           => 'projectTease',
			'type'         => 'text',
			'object_types' => [ 'project' ], // Only show on project post types.
		];
		$metabox->add_field( $args );

		/**
		 * ID projectAltName: Returns string.
		 * The alternative name for this project.
		 */
		$args = [
			'class'        => 'input-full-width',
			'name'         => 'Alt',
			'desc'         => 'Is there an alternate name or client for this project?',
			'default'      => '',
			'id'           => 'projectAltName',
			'type'         => 'text',
			'object_types' => [ 'project' ], // Only show on project post types.
		];
		$metabox->add_field( $args );

		/**
		 * ID projectSVGLogo. Returns URL.
		 * A client logo. Should be square and ideally SVG.
		 */
		$args = [
			'name'         => 'SVG',
			'desc'         => 'Upload a client SVG logo.',
			'id'           => 'projectSVGLogo',
			'type'         => 'file',
			'object_types' => [ 'project' ], // Only show on project post types.
			'options'      => [ 'url' => false ],
			'text'         => [ 'add_upload_file_text' => 'Add SVG' ],
			'query_args'   => [ 'type' => 'image/svg+xml' ],
			'preview_size' => 'medium',
		];
		$metabox->add_field( $args );

		/**
		 * ID projectNarrative. Returns Text.
		 * 1 or 2 paragraphs about the project.
		 */
		$args = [
			'name'         => 'Narrative',
			'desc'         => 'Project Write Up / Narrative',
			'id'           => 'projectNarrative',
			'type'         => 'textarea_code',
			'object_types' => [ 'project' ],
		];
		$metabox->add_field( $args );

		/**
		 * Square Images for Slideshow?
		 *
		 * @todo It would be good to reduce the images to select from to ones that have height and width attributes that are equal. Figure that out.
		 */
		$args = [
			'name'         => 'Square',
			'desc'         => 'Add any images that have an equal height and width here.',
			'id'           => 'projectImagesSquare',
			'type'         => 'file_list',
			'preview_size' => [ 100, 100 ],
			'query_args'   => [ 'type' => 'image' ],
			'text'         => [
				'add_upload_files_text' => 'Add Images',
				'remove_image_text'     => 'Remove',
				'file_text'             => 'Files:',
				'file_download_text'    => 'DL',
				'remove_text'           => 'ReplaceREMOVE',
			],
		];
		$metabox->add_field( $args );

		/**
		 * 4x3 Images for Slideshow
		 */
		$args = [
			'name'         => '4x3',
			'desc'         => 'Typically 4x3',
			'id'           => 'projectImagesSlideshow',
			'type'         => 'file_list',
			'preview_size' => [ 100, 100 ],
			'query_args'   => [ 'type' => 'image' ],
			'text'         => [
				'add_upload_files_text' => 'Add slides',
				'remove_image_text'     => 'Remove',
				'file_text'             => 'Files:',
				'file_download_text'    => 'DL',
				'remove_text'           => 'ReplaceREMOVE',
			],
		];
		$metabox->add_field( $args );

	}//end register_posttype_metabox()

	/**
	 * Unset existing columns for this post type. Add new ones from your cmb2 metabox fields.
	 *
	 * @param array $columns  The off-the-rack post columns from WordPress.
	 * @return array $columns The ones that I want to stick around.
	 */
	public function setup_new_admin_columns( $columns ) {
		unset( $columns['author'] );
		unset( $columns['categories'] );
		unset( $columns['tags'] );
		unset( $columns['comments'] );
		unset( $columns['date'] );
		unset( $columns['title'] );
		unset( $columns['cb'] );
		$new['cb']        = '<input type = "checkbox" />';
		$new['id']        = 'ID';
		$new['job_id']    = 'Job';
		$new['status']    = 'Status';
		$new['thumbnail'] = 'Image';
		$new['year']      = 'Year';
		return array_merge( $new, $columns );
	}
	/**
	 * Populate the data within the new columns added to this posttype.
	 *
	 * @param string $column The name of the column - from the setup_new_admin_columns function.
	 * @param int    $post_id Identification of the post via the jco_posts column.
	 */
	public function populate_custom_columns( $column, $post_id ) {
		$single_post  = get_post( $post_id );
		$post_title   = $single_post->post_title;
		$image_size   = $this->size;
		$featured     = get_the_post_thumbnail( $post_id, $this->size );
		$featured_id  = get_post_thumbnail_id( $post_id ) ?? '69';
		$featured_src = wp_get_attachment_image_src( $featured_id, $this->size );
		$job_number   = get_post_meta( $post_id, 'projectJobNumber', true );
		$status       = get_post_meta( $post_id, 'projectJobStatus', true ) ?? 'complete: 2020';
		switch ( $status ) {
			case 'ongoing':
				$year = get_post_meta( $post_id, 'projectYearStarted', true );
				break;
			case 'upcoming':
				$year = get_post_meta( $post_id, 'projectExpectedCompletionYear', true );
				break;
			default:
				$year = get_post_meta( $post_id, 'projectCompletedYear', true );
				break;
		}
		switch ( $column ) {
			case 'id':
				$output = $post_id . ': ' . $post_title;
				break;
			case 'job_id':
				$output = $job_number ?? '69';
				break;
			case 'status':
				$output = $status . ': <strong>' . $year . '</strong>';
				break;
			case 'thumbnail':
				$output = $featured_id;
				break;
			default:
				$output = '';
				break;
		}
		echo $output;
	}

	/**
	 * Make custom columns added to the taxonomy sortable.
	 *
	 * @link https://code.tutsplus.com/articles/quick-tip-make-your-custom-column-sortable--wp-25095
	 * @param array $columns An array of the existing columns.
	 */
	public function sortable_columns( $columns ) {
		$columns['id']     = 'ID';
		$columns['job_id'] = 'Job';
		return $columns;
	}

	/**
	 * Return state options as HTML for the select field entitled 'State' in the address CMB2 field type.
	 *
	 * @link https://developer.wordpress.org/reference/functions/selected/
	 * @param string $value From the containing function.
	 * @return string $options The html for the options within a select field of 'State'.
	 */
	public function get_state_options( $value ) {
		$states  = self::$state_list;
		$states  = [ '' => esc_html( 'Select a State' ) ] + $states;
		$options = '';
		foreach ( $states as $abbrev => $state ) {
			$selected = selected( $value, $abbrev, false );
			$options .= "<option value=\"$abbrev\" $selected>$state</option>";
		}
		return $options;
	}

	/**
	 * Render Address Field
	 *
	 * @param array  $field              The passed in `CMB2_Field` .
	 * @param mixed  $value              The value of this field escaped. It defaults to `sanitize_text_field`.
	 * @param int    $object_id          The ID of the current object.
	 * @param string $object_type        The type of object you are working with. Most commonly, `post` (this applies to all post-types),but could also be `comment`, `user` or `options-page`.
	 * @param object $field_type         The `CMB2_Types` object.
	 */
	public function render_address_field_callback( $field, $value, $object_id, $object_type, $field_type ) {
		$new_values = [
			'address-1' => '',
			'city'      => '',
			'state'     => '',
			'zip'       => '',
			'latitude'  => '',
			'longitude' => '',
		];
		$value      = wp_parse_args( $value, $new_values );
	?>

	<section class="projectAddressFields">
		<!-- address-1 -->
		<div class="field-div" data-fieldid="address1">
			<span class="innerlabel">
				<label for="<?= $field_type->_id( '_address_1', false ); ?>">Address</label>
			</span>
			<?= $field_type->input(
				[
					'name'  => $field_type->_name( '[address-1]' ),
					'id'    => $field_type->_id( '_address_1' ),
					'value' => $value['address-1'],
					'desc'  => '',
				]
			);
			?>
		</div><!-- /address-1 -->

		<!-- city state zip -->
		<div class="citystatezip">
			<!-- city-->
			<div class="field-div" data-fieldid="city">
				<span class="innerlabel">
					<label for="<?= $field_type->_id( '_city' ); ?>'">City</label>
				</span>
				<?= $field_type->input(
					[
						'name'  => $field_type->_name( '[city]' ),
						'id'    => $field_type->_id( '_city' ),
						'value' => $value['city'],
						'desc'  => '',
						'type'  => 'text_small',
					]
				);
				?>
			</div><!-- /city -->

			<!-- state -->
			<div class="field-div" data-fieldid="state">
				<span class="innerlabel">
					<label for="<?= $field_type->_id( '_state' ); ?>'">State</label>
				</span>
				<?= $field_type->select(
					[
						'name'    => $field_type->_name( '[state]' ),
						'id'      => $field_type->_id( '_state' ),
						'options' => $this->get_state_options( $value['state'] ),
						'desc'    => '',
					]
				);
				?>
			</div><!-- /state -->

			<!-- /zip -->
			<div class="field-div" data-fieldid="zip">
				<span class="innerlabel">
					<label for="<?= $field_type->_id( '_zip' ); ?>'">Zip</label>
				</span>
				<?= $field_type->input(
					[
						'name'  => $field_type->_name( '[zip]' ),
						'id'    => $field_type->_id( '_zip' ),
						'value' => $value['zip'],
						'type'  => 'text_small',
						'desc'  => '',
					]
				);
				?>
			</div><!-- /zip -->
		</div><!-- /city state zip -->

		<!-- coordinates -->
		<div class="coordinates">
			<div data-fieldid="latitude" class="field-div">
				<span class="innerlabel">
					<label for="<?=$field_type->_id( '_latitude' ); ?>'">Latitude</label>
				</span>
				<?= $field_type->input(
					[
						'name'  => $field_type->_name( '[latitude]' ),
						'id'    => $field_type->_id( '_latitude' ),
						'value' => $value['latitude'],
						'desc'  => '',
					]
				);
				?>
			</div><!-- /latitude -->
			<div data-fieldid="longitude" class="field-div">
				<span class="innerlabel">
					<label for="<?= $field_type->_id( '_longitude' ); ?>'">Longitude</label>
				</span>
				<?= $field_type->input(
					[
						'name'  => $field_type->_name( '[longitude]' ),
						'id'    => $field_type->_id( '_longitude' ),
						'value' => $value['longitude'],
						'desc'  => '',
					]
				);
				?>
			</div><!-- /longitude -->
		</div><!-- /coordinates -->

	</section><!-- end section.projectaddressfields -->
	<?php
	}//end render_address_field_callback()

	/**
	 * Render 'star rating' custom field type
	 *
	 * @since 0.1.0
	 *
	 * @param array  $field              The passed in `CMB2_Field` .
	 * @param mixed  $value              The value of this field escaped. It defaults to `sanitize_text_field`.
	 * @param int    $object_id          The ID of the current object.
	 * @param string $object_type        The type of object you are working with. Most commonly, `post` (this applies to all post-types),but could also be `comment`, `user` or `options-page`.
	 * @param object $field_type         The `CMB2_Types` object.
	 */
	public function cmb2_render_rating_field_callback( $field, $value, $object_id, $object_type, $field_type ) {
		?>
		<section id="cmb2-star-rating-metabox">
			<fieldset>
				<span class="star-cb-group">
					<?php
						$y = 5;
						while ( $y > 0 ) {
							?>
								<input type="radio" id="rating-<?php echo $y; ?>" name="<?php echo $field_type->_id( false ); ?>" value="<?php echo $y; ?>" <?php checked( $value, $y ); ?>/>
								<label for="rating-<?php echo $y; ?>"><?php echo $y; ?></label>
							<?php
							$y--;
						}
					?>
				</span>
			</fieldset>
		</section>
	<?php
	echo $field_type->_desc( true );

	}

} //end Project class.

/**
 * Instantiate class, creating post type.
 */
new Project();
