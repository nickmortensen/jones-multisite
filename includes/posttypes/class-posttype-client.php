<?php
/**
 * The 'client' custom post type.
 *
 * Registers post type name, labels, & parameters.
 * @link       https://github.com/nickmortensen
 * @since      1.0.0
 *
 * @package    Jones_Multisites
 * @subpackage Jones_Multisites/admin
 */

/**
 * The 'expertise' taxonomy.
 *
 * Registers taxonomy name, labels, & parameters.
 *
 * @package    Jones_Multisites
 * @subpackage Jones_Multisites/admin
 * @author     Nick Mortensen <nmortensen@jonessign.com>
 */
class Client {
	/**
	 * The arguments of this posttype.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $type The arguments of this posttype..
	 */
	private $type = 'client';
	/**
	 * The slug of this posttype.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $slug The slug of this posttype..
	 */
	private $slug = 'client';
	/**
	 * The name of this posttype.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $name The slug of this posttype..
	 */
	private $name = 'client';
	/**
	 * The singular of this posttype.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $name The slug of this posttype..
	 */
	private $singular_name = 'client';
	/**
	 * The plural of this posttype.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $name The slug of this posttype..
	 */
	private $plural_name = 'clientele';

	/**
	 * The taxonomies to apply to this posttype.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $taxonomies_to_apply The taxonomies to apply to this post type.
	 */
	private $taxonomies_to_apply = [ 'expertise' ];

	/**
	 * The icon to use for this posttype.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $icon_for_posttype The icon (typically from among the dashicons collection) to apply to this post type.
	 */
	private $icon_for_posttype = 'dashicons-admin-multisite';
	/**
	 * Create the post type for 'client'.
	 *
	 * Create a custom post type for all sites of 'client'.
	 *
	 * @since    1.0.0
	 */
	public static function register() {
		$labels = [
			'name'                  => ucfirst( $this->$plural_name ),
			'singular_name'         => ucfirst( $this->$singular_name ),
			'menu_name'             => ucfirst( $this->$singular_name ),
			'name_admin_bar'        => ucfirst( $this->$singular_name ),
			'archives'              => ucfirst( $this->$singular_name ) . ' Archives',
			'attributes'            => 'Attributes',
			'parent_item_colon'     => 'Parent Item: ',
			'all_items'             => 'all ' . $this->$plural_name,
			'add_new_item'          => 'Add New ' . ucfirst( $this->$singular_name ),
			'add_new'               => 'Add New',
			'new_item'              => 'New ' . ucfirst( $this->$singular_name ),
			'edit_item'             => 'Edit ' . ucfirst( $this->$singular_name ),
			'update_item'           => 'Update ' . ucfirst( $this->$singular_name ),
			'view_item'             => 'View ' . ucfirst( $this->$singular_name ),
			'view_items'            => 'View ' . ucfirst( $this->$plural_name ),
			'search_items'          => 'Search ' . ucfirst( $this->$plural_name ),
			'not_found'             => 'Not found',
			'not_found_in_trash'    => 'Not found in Trash',
			'featured_image'        => 'Featured Image',
			'set_featured_image'    => 'Set featured image',
			'remove_featured_image' => 'Remove featured image',
			'use_featured_image'    => 'Use as featured image',
			'insert_into_item'      => 'Insert into item',
			'uploaded_to_this_item' => 'Uploaded to this ' . $this->$singular_name,
			'items_list'            => ucfirst( $this->$plural_name ) . ' list',
			'items_list_navigation' => ucfirst( $this->$plural_name ) . ' list nav',
			'filter_items_list'     => 'Filter ' . ucfirst( $this->$plural_name ) . ' List',
		];

		$rewrite = [
			'slug'       => $this->$singular_name,
			'with_front' => true,
			'pages'      => true,
			'feeds'      => true,
		];

		$args = [
			'label'                 => ucfirst( $this->$singular_name ),
			'description'           => 'Jones Sign Company ' . ucfirst( $this->$plural_name ) . ' and Details',
			'labels'                => $labels,
			'supports'              => array( 'title', 'thumbnail', 'excerpt', 'post-formats' ),
			'taxonomies'            => $this->$taxonomies_to_apply,
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 100,
			'menu_icon'             => $this->$icon_for_posttype,
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
		register_post_type( $this->$singular_name, $args );
	} //end register.

	/**
	 * Create the extra fields for the post type.
	 *
	 * Use CMB2 to create additional fields for the client post type.
	 *
	 * @since    1.0.0
	 */
	public static function register_posttype_metabox() {
		$prefix       = $this->$singular_name . '_';
		$metabox_args = [
			'context'      => 'normal',
			'classes'      => $this->$singular_name . '-posttype-metabox',
			'id'           => $this->$singular_name . '_metabox',
			'object_types' => [ $this->$singular_name ],
			'show_in_rest' => WP_REST_Server::ALLMETHODS,
			'show_names'   => true,
			'title'        => $this->$singular_name . ' Overview',
		];

		$metabox = new_cmb2_box( $metabox_args );
		$after   = '<hr>';

		$website = [
			'protocols'    => [ 'http', 'https' ],
			'name'         => 'Client Website',
			'id'           => 'clientWebsite',
			'type'         => 'text_url',
			'show_names'   => 'true',
			'inline'       => true,
			'object_types' => array( 'client' ),
			'after_row'    => $after,
		];
		$metabox->add_field( $website );

		// Stock Ticker - if it applies.
		$ticker = [ 'id' => 'clientTicker', 'name' => ucfirst( 'stock ticker' ), 'type' => 'text_small', 'after_row' => $after ];
		$metabox->add_field( $ticker );

		// Client Logo.
		$type   = 'image/svg+xml';
		$url    = false;
		$button = 'Upload logo';
		$logo   = [ 'options' => [ 'url' => $url ],'id' => 'clientLogo', 'name' => ucfirst( 'logo' ), 'type' => 'file', 'query_args' => [ 'type' => $type ], 'text' => [ 'add_upload_file_text' => $button ], 'after_row' => $after, ];
		$metabox->add_field( $logo );
	}

	/**
	 * Event constructor.
	 *
	 * When class is instantiated
	 */
	public function __construct() {
		// Register the post type.
		add_action( 'init', [ $this, 'register' ] );
		// Setup the extra fields.
		add_action( 'cmb2_init', [ $this, 'register_posttype_metabox' ] );
	}

} //end Client class.

/**
 * Instantiate class, creating post type.
 */
new Client();
