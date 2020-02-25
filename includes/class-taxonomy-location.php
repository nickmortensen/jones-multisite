<?php

/**
 * The 'expertise' taxonomy.
 *
 * @link       https://github.com/nickmortensen
 * @since      1.0.0
 *
 * @package    Jones_Multi
 * @subpackage Jones_Multi/admin
 */

/**
 * The 'expertise' taxonomy.
 *
 * Registers taxonomy name, labels, & parameters.
 *
 * @package    Jones_Multi
 * @subpackage Jones_Multi/admin
 * @author     Nick Mortensen <nmortensen@jonessign.com>
 */
class Location {
	/**
	 * The arguments of this taxonomy.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $type The arguments of this taxonomy..
	 */
	private $type = 'location';
	/**
	 * The slug of this taxonomy.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $slug The slug of this taxonomy..
	 */
	private $slug = 'location';
	/**
	 * The name of this taxonomy.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $name The slug of this taxonomy..
	 */
	private $name = 'location';
	/**
	 * The singular of this taxonomy.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $name The slug of this taxonomy..
	 */
	private $singular_name = 'Location';


	/**
	 * Create the taxonomy for 'expertise'.
	 *
	 * Create a custom taxonomy for all sites of 'expertise'.
	 *
	 * @since    1.0.0
	 */
	public static function register() {
		$labels = [
			'name'                       => _x( 'Locations', 'Taxonomy General Name', 'js2020' ),
			'singular_name'              => _x( 'Location', 'Taxonomy Singular Name', 'js2020' ),
			'menu_name'                  => __( 'Jones Locations', 'js2020' ),
			'all_items'                  => __( 'All Locations', 'js2020' ),
			'parent_item'                => __( 'Main', 'js2020' ),
			'parent_item_colon'          => __( 'Main Location', 'js2020' ),
			'new_item_name'              => __( 'New Location', 'js2020' ),
			'add_new_item'               => __( 'Add New Location', 'js2020' ),
			'edit_item'                  => __( 'Edit Location', 'js2020' ),
			'update_item'                => __( 'Update Location', 'js2020' ),
			'view_item'                  => __( 'View Location', 'js2020' ),
			'separate_items_with_commas' => __( 'Separate locations with commas', 'js2020' ),
			'add_or_remove_items'        => __( 'Add or remove Locations', 'js2020' ),
			'choose_from_most_used'      => __( 'Frequently Used Locations', 'js2020' ),
			'popular_items'              => __( 'Popular Locations', 'js2020' ),
			'search_items'               => __( 'Search Locations', 'js2020' ),
			'not_found'                  => __( 'Not Found', 'js2020' ),
			'no_terms'                   => __( 'No Locations', 'js2020' ),
			'items_list'                 => __( 'Locations list', 'js2020' ),
			'items_list_navigation'      => __( 'Locations list navigation', 'js2020' ),
		];
		$rewrite = [
			'slug'                       => 'location',
			'with_front'                 => true,
			'hierarchical'               => false,
		];

		$args = [
			'labels'             => $labels,
			'description'        => 'Covers Various Jones Sign Company Locations around North America',
			'hierarchical'       => false,
			'public'             => true,
			'show_ui'            => true,
			'show_in_quick_edit' => true,
			'show_in_menu'       => true,
			'show_admin_column'  => true,
			'show_in_nav_menus'  => true,
			'show_tagcloud'      => true,
			'query_var'          => 'location',
			'rewrite'            => $rewrite,
			'show_in_rest'       => true,
			'rest_base'          => 'location',
		];

		$objects_array = [
			'post',
			'page',
			'attachment',
			'nav_menu_item',
		];

		register_taxonomy( $this->type, $objects_array, $args );
	}

	/**
	 * Create the extra fields for 'location'.
	 *
	 * Use CMB2 to create additional fields for the location taxonomy.
	 *
	 * @since    1.0.0
	 */
	public function register_location_taxonomy_metabox() {
		// Establish prefeix.
		$prefix = '$location_';
		// Create an instance of the cmbs2box called $location.
		$location = new_cmb2_box(
			[
				'id'                           => $prefix . 'edit',
				'title'                        => esc_html__( 'Location Taxonomy Extra Info', 'jsCustom' ),
				'object_types'                 => array( 'term' ), // indicate to cmb we are using terms and not posts.
				'taxonomies'                   => array( 'location' ), // Fields can be added to more than one taxonomy term, but we will limit these just to the signtype taxonomy term.
				'cmb_styles'                   => true, // Disable cmb2 stylesheet.
				'show_in_rest'                 => WP_REST_Server::ALLMETHODS, // WP_REST_Server::READABLE|WP_REST_Server::EDITABLE, // Determines which HTTP methods the box is visible in.
				// Optional callback to limit box visibility.
				// See: https://github.com/CMB2/CMB2/wiki/REST-API#permissions.
				'get_box_permissions_check_cb' => 'projects_limit_rest_view_to_logged_in_users',
			]
		);
		$location->add_field(
			[
				'name'        => __( 'Website URL', 'cmb2' ),
				'description' => 'nimble website url',
				'id'          => 'locationURL',
				'type'        => 'text_url',
				'show_names'  => true,
				'classes_cb'  => 'add_these_classes',
				'protocols'   => [ 'http', 'https' ],
				'attributes'  => (
					[
						'placeholder'  => 'http://jonessign.com',
						'data-fucking' => 'ConditionalFucking',
					]
				),
			]
		);

		/* CAPABILITIES OF THE LOCATION */
		$location->add_field(
			[
				'name'              => 'Capability',
				'desc'              => 'check all that apply',
				'id'                => 'locationCapabilities',
				'type'              => 'multicheck',
				'inline'            => true,
				'select_all_button' => false,
				'options'           => [
					'Fabrication'        => 'Fab',
					'Installation'       => 'Install',
					'Project Management' => 'PM',
					'Sales'              => 'Sales',
				],
			]
		);

		$location->add_field(
			[
				'before'       => 'dashicons_callback',
				'name'         => 'Location Image',
				'show_names'   => false,
				'desc'         => '',
				'id'           => 'locationImage',
				'type'         => 'file',
				'options'      => [ 'url' => false ], // No box that allows for the url to be typed in as I want to use the image ids.
				'text'         => [ 'add_upload_file_text' => 'Upload or Find Location Image' ],
				// query_args are passed to wp.media's library query.
				'query_args'   => [
					'type' => [ 'image/jpg', 'image/jpeg' ],
				],
				'preview_size' => 'medium', // Image size to use when previewing in the admin.
			]
		);
		/* Location Phone */
		$location->add_field(
			[
				'name'    => 'Phone',
				'desc'    => '',
				'default' => '',
				'id'      => 'locationPhone',
				'type'    => 'text_medium',
			]
		);
		/* Location Fax */
		$location->add_field(
			[
				'name'    => 'Fax',
				'desc'    => '',
				'default' => '',
				'id'      => 'locationFax',
				'type'    => 'text_medium',
			]
		);
		/* Location Street Address */
		$location->add_field(
			[
				'name'    => 'Address',
				'desc'    => '',
				'default' => '',
				'id'      => 'locationAddress',
				'type'    => 'text_medium',
			]
		);

		/* Location City */
		$location->add_field(
			[
				'name'    => 'City',
				'desc'    => '',
				'default' => '',
				'id'      => 'locationCity',
				'type'    => 'text_medium',
			]

		);
			/* Location State */
	$location->add_field(
		array(
			'type'             => 'select',
			'default'          => 'custom',
			'name'             => 'State',
			'desc'             => '',
			'id'               => 'locationState',
			'show_option_none' => 'Select State',
			'options'          => array(
				'AL' => 'Alabama',
				'AK' => 'Alaska',
				'AZ' => 'Arizona',
				'AR' => 'Arkansas',
				'CA' => 'California',
				'CO' => 'Colorado',
				'CT' => 'Connecticut',
				'DE' => 'Delaware',
				'DC' => 'District Of Columbia',
				'FL' => 'Florida',
				'GA' => 'Georgia',
				'HI' => 'Hawaii',
				'ID' => 'Idaho',
				'IL' => 'Illinois',
				'IN' => 'Indiana',
				'IA' => 'Iowa',
				'KS' => 'Kansas',
				'KY' => 'Kentucky',
				'LA' => 'Louisiana',
				'ME' => 'Maine',
				'MD' => 'Maryland',
				'MA' => 'Massachusetts',
				'MI' => 'Michigan',
				'MN' => 'Minnesota',
				'MS' => 'Mississippi',
				'MO' => 'Missouri',
				'MT' => 'Montana',
				'NE' => 'Nebraska',
				'NV' => 'Nevada',
				'NH' => 'New Hampshire',
				'NJ' => 'New Jersey',
				'NM' => 'New Mexico',
				'NY' => 'New York',
				'NC' => 'North Carolina',
				'ND' => 'North Dakota',
				'OH' => 'Ohio',
				'OK' => 'Oklahoma',
				'OR' => 'Oregon',
				'PA' => 'Pennsylvania',
				'RI' => 'Rhode Island',
				'SC' => 'South Carolina',
				'SD' => 'South Dakota',
				'TN' => 'Tennessee',
				'TX' => 'Texas',
				'UT' => 'Utah',
				'VT' => 'Vermont',
				'VA' => 'Virginia',
				'WA' => 'Washington',
				'WV' => 'West Virginia',
				'WI' => 'Wisconsin',
				'WY' => 'Wyoming',
			),
		)
	);
	/* ENDLocation State */

	/* Location Zip */
	$location->add_field(
		array(
			'name'    => 'Zip',
			'desc'    => '',
			'default' => '',
			'id'      => 'locationZip',
			'type'    => 'text_medium',
		)
	);
	/* Location Latitude */
	$location->add_field(
		array(
			'name'    => 'latitude',
			'desc'    => '',
			'default' => '',
			'id'      => 'locationLatitude',
			'type'    => 'text_medium',
		)
	);
	/* Location Longitude */
	$location->add_field(
		array(
			'name'    => 'longitude',
			'desc'    => '',
			'default' => '',
			'id'      => 'locationLongitude',
			'type'    => 'text_medium',
		)
	);
		/* Location GoogleCID */
		$location->add_field(
			array(
				'name'    => 'Google CID',
				'desc'    => '',
				'default' => '',
				'id'      => 'locationGoogleCID',
				'type'    => 'text_medium',
			)
		);
		/* SHOW THIS FIELD IN FOOTER? */
		$location->add_field(
			array(
				'name'       => 'Footer',
				'desc'       => 'Show this location within the site footer',
				'id'         => 'locationWithinFooter',
				'type'       => 'checkbox',
				'show_names' => false,
			)
		);

	} // End def function register_projects_metabox().


	/**
	 * Event constructor.
	 *
	 * When class is instantiated
	 */
	public function __construct() {
		// Register the taxonomy.
		add_action( 'init', [ $this, 'register' ] );
		// Setup the extra fields.
		add_action( 'cmb2_init', [ $this, 'register_location_taxonomy_metabox' ] );
		// Admin set post columns
		// add_filter( 'manage_edit-'.$this->type.'_columns',        array($this, 'set_columns'), 10, 1) ;

		// Admin edit post columns
		// add_action( 'manage_'.$this->type.'_posts_custom_column', array($this, 'edit_columns'), 10, 2 );

	}

	/**
	 * Callback for the locationState field.
	 *
	 * @param bool $value Whether the specific state should be the default.
	 *
	 * @return string $state_option HTML for all the options in the select dropdown.
	 */
	public $states_array = array(
		'AL' => 'Alabama',
		'AK' => 'Alaska',
		'AZ' => 'Arizona',
		'AR' => 'Arkansas',
		'CA' => 'California',
		'CO' => 'Colorado',
		'CT' => 'Connecticut',
		'DE' => 'Delaware',
		'DC' => 'District Of Columbia',
		'FL' => 'Florida',
		'GA' => 'Georgia',
		'HI' => 'Hawaii',
		'ID' => 'Idaho',
		'IL' => 'Illinois',
		'IN' => 'Indiana',
		'IA' => 'Iowa',
		'KS' => 'Kansas',
		'KY' => 'Kentucky',
		'LA' => 'Louisiana',
		'ME' => 'Maine',
		'MD' => 'Maryland',
		'MA' => 'Massachusetts',
		'MI' => 'Michigan',
		'MN' => 'Minnesota',
		'MS' => 'Mississippi',
		'MO' => 'Missouri',
		'MT' => 'Montana',
		'NE' => 'Nebraska',
		'NV' => 'Nevada',
		'NH' => 'New Hampshire',
		'NJ' => 'New Jersey',
		'NM' => 'New Mexico',
		'NY' => 'New York',
		'NC' => 'North Carolina',
		'ND' => 'North Dakota',
		'OH' => 'Ohio',
		'OK' => 'Oklahoma',
		'OR' => 'Oregon',
		'PA' => 'Pennsylvania',
		'RI' => 'Rhode Island',
		'SC' => 'South Carolina',
		'SD' => 'South Dakota',
		'TN' => 'Tennessee',
		'TX' => 'Texas',
		'UT' => 'Utah',
		'VT' => 'Vermont',
		'VA' => 'Virginia',
		'WA' => 'Washington',
		'WV' => 'West Virginia',
		'WI' => 'Wisconsin',
		'WY' => 'Wyoming',
	);

}
/**
 * Instantiate class, creating taxonomy.
 */
new Location();
