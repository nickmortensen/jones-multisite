<?php

/**
 * The 'signtype' taxonomy.
 *
 * @link       https://github.com/nickmortensen
 * @since      1.0.0
 *
 * @package    Jones_Multisites
 * @subpackage Jones_Multisites/admin
 */

/**
 * The 'signtype' taxonomy.
 *
 * Registers taxonomy name, labels, & parameters.
 *
 * @package    Jones_Multisites
 * @subpackage Jones_Multisites/admin
 * @author     Nick Mortensen <nmortensen@jonessign.com>
 */
class SignType {
	/**
	 * The arguments of this taxonomy.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $type The arguments of this taxonomy..
	 */
	private $type = 'signtype';
	/**
	 * The slug of this taxonomy.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $slug The slug of this taxonomy..
	 */
	private $slug = 'signtype';
	/**
	 * The name of this taxonomy.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $name The slug of this taxonomy..
	 */
	private $name = 'SignTypes';
	/**
	 * The singular of this taxonomy.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $name The slug of this taxonomy..
	 */
	private $singular_name = 'SignType';

	/**
	 * Event constructor.
	 *
	 * When class is instantiated
	 */
	public function __construct() {
		// Reset taxonomy tables before taxonomy terms are initialized.
		add_action ( 'init', [ $this, 'all_sites_use_same_taxonomies' ] );
		// Reset again when we switch blogs.
		add_action ( 'switch_blog', [ $this, 'all_sites_use_same_taxonomies' ] );
		// Add extra data columns to the administrator table for this taxonomy.

		// Register the taxonomy.
		add_action( 'init', [ $this, 'register' ] );
		// Setup the extra fields.
		add_action( 'cmb2_init', [ $this, 'register_taxonomy_metabox' ] );

		// Add extra columns to the administrator end of this taxonomy.
		add_filter( 'manage_edit-' . $this->type . '_columns', [ $this, 'set_columns' ], 10, 1 );
		// Place data within the newly added columns for the admin side of this taxonomy.
		add_filter( 'manage_' . $this->type . '_custom_column', [ $this, 'edit_columns' ], 10, 3 );
		// Make new columns for this taxonomy sortable.
		add_action( 'manage_edit-' . $this->type . '_sortable_columns', [ $this, 'sortable_columns' ] );

	}

	/**
	 * Create the taxonomy for 'signtype'.
	 *
	 * Create a custom taxonomy for all sites of 'signtype'.
	 *
	 * @since    1.0.0
	 */
	public static function register() {
		$labels = [
			'add_new_item'               => __( 'Add Sign Type', 'jsCustom' ),
			'add_or_remove_items'        => __( 'Add or Remove Sign Type', 'jsCustom' ),
			'all_items'                  => __( 'All Sign Types', 'jsCustom' ),
			'back_to_items'              => __( 'Back to Sign Types', 'jsCustom' ),
			'choose_from_most_used'      => __( 'Often Used Sign Tags', 'jsCustom' ),
			'edit_item'                  => __( 'Edit Sign Type', 'jsCustom' ),
			'items_list'                 => __( 'Sign Type List', 'jsCustom' ),
			'items_list_navigation'      => __( 'Sign Types List Nav', 'jsCustom' ),
			'menu_name'                  => __( 'Sign Types', 'jsCustom' ),
			'name'                       => _x( 'Sign Type', 'Taxonomy General Name', 'jsCustom' ),
			'new_item_name'              => __( 'New Sign Type Tag', 'jsCustom' ),
			'no_terms'                   => __( 'No Sign Type Tags', 'jsCustom' ),
			'not_found'                  => __( 'Sign Type Not Found', 'jsCustom' ),
			'popular_items'              => __( 'Popular Sign Types', 'jsCustom' ),
			'search_items'               => __( 'Search Sign Types', 'jsCustom' ),
			'separate_items_with_commas' => __( 'Separate Sign Types w/commas', 'jsCustom' ),
			'singular_name'              => _x( 'Sign Type', 'Taxonomy Singular Name', 'jsCustom' ),
			'update_item'                => __( 'Update Sign Type', 'jsCustom' ),
			'view_item'                  => __( 'View Sign Type ', 'jsCustom' ),
		];

		$args = [
			'hierarchical'          => false,
			'description'           => 'Apply a Sign Type Tag to Photos or Project pages.',
			'labels'                => $labels,
			'public'                => true, // Sets the defaults for 'publicly_queryable', 'show_ui', & 'show_in_nav_menus' as well.
			'query_var'             => 'signtype',
			'show_in_menu'          => true,
			'show_in_rest'          => true,
			'rewrite'               => array( 'slug' => 'sign' ),
			'show_admin_column'     => true,
			'show_tagcloud'         => true,
			'capabilities'          => array( 'manage_terms', 'edit_terms', 'delete_terms', 'assign_posts' ),
			'update_count_callback' => '_update_post_term_count',
		];

		$objects_array = [
			'post',
			'page',
			'attachment',
			'nav_menu_item',
			'project',
		];

		register_taxonomy( 'signtype', $objects_array, $args );
	}
	/**
	 * Create the extra fields for 'signtype'.
	 *
	 * Use CMB2 to create additional fields for the signtype taxonomy.
	 *
	 * @since    1.0.0
	 */
	public static function register_taxonomy_metabox() {
		$prefix = 'signtype_';
			// Create an instance of the cmbs2box called $signtype.
		$newfields = new_cmb2_box(
			array(
				'id'                           => $prefix . 'edit',
				'title'                        => esc_html__( 'Signtype Extra Info', 'jsCustom' ),
				'object_types'                 => array( 'term' ), // indicate to cmb we are using terms and not posts.
				'taxonomies'                   => array( 'signtype' ), // Fields can be added to more than one taxonomy term, but we will limit these just to the signtype taxonomy term.
				'cmb_styles'                   => true, // Disable cmb2 stylesheet.
				'show_in_rest'                 => WP_REST_Server::ALLMETHODS, // WP_REST_Server::READABLE|WP_REST_Server::EDITABLE, // Determines which HTTP methods the box is visible in.
				// Optional callback to limit box visibility.
				// See: https://github.com/CMB2/CMB2/wiki/REST-API#permissions.
				'get_box_permissions_check_cb' => 'projects_limit_rest_view_to_logged_in_users',
			)
		);

		$args = [
			'name'       => 'Instance',
			'desc'       => 'Scenario Wherein this type of sign is best.',
			'default'    => '',
			'id'         => 'signtypeUseCases',
			'type'       => 'text',
			'repeatable' => true,
			'attributes' => array(
				'rows' => 2,
			),
			'text'       => array(
				'add_row_text' => 'Add Another Use Case',
			),
		];
		$newfields->add_field( $args );

		// Best images should be a file_list field in CMB2. That way there are several images to choose among.
		$args = [
			'name'         => 'Best Images',
			'desc'         => 'Several Images that are representative of this sign type ',
			'id'           => 'signtypeMainImages',
			'type'         => 'file_list',
			'preview_size' => array( 400, 300 ),
			'query_args'   => array( 'type' => 'image' ), // Only images attachment.
			'text'         => [
				'add_upload_files_text' => 'Add Image',
				'remove_image_text'     => 'Remove',
				'file_text'             => 'File: ',
				'file_download_text'    => 'Download Photo',
				'remove_text'           => 'Remove',
			],
		];
		$newfields->add_field( $args );
	}

	/**
	 * Set up some new columns in the admin screen for this taxonomy.
	 *
	 * @param array $columns The existing columns before I monkeyed with them.
	 * @link https://shibashake.com/wordpress-theme/modify-custom-taxonomy-columns
	 */
	public function set_columns( $columns ) {
		// Remove the checkbox that comes with $columns.
		unset( $columns['cb'] );
		// Add the checkbox back in so it can be before the ID column.
		$new['cb'] = '<input type="checkbox" />';
		$new['id'] = 'ID';
		return array_merge( $new, $columns );
	}

	/**
	 * Put content into the newly setup columns columns in the admin screen for the taxonomy.
	 *
	 * @param array  $column The existing columns before I monkeyed with them.
	 * @param string $tax     Taxonomy name - in this case 'location'.
	 * @param int    $term_id ID number assigned to the term in the jco_terms table within the db.
	 */
	public function edit_columns( $column, $tax, $term_id ) {
		$tax = $this->type;
		switch ( $column ) {
			case 'id':
				$output = $term_id;
				break;
		default:
			// $output = '<i class = "text-indigo-600 material-icons">stars</i>';
			$output = $term_id;
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
		$columns['id'] = 'ID';
		return $columns;
	}

	/**
	 * Ensure Taxonomy terms that are used are the same throughout all the child sites.
	 *
	 * Use CMB2 to create additional fields for the signtype taxonomy.
	 *
	 * @since    1.0.0
	 */
	public static function all_sites_use_same_taxonomies() {
		global $wpdb;
		// Change terms table to use main sites terms.
		$wpdb->terms = $wpdb->base_prefix . 'terms';
		// Change taxonomy table to use main site's taxonomy table.
		$wpdb->term_taxonomy = $wpdb->base_prefix . 'term_taxonomy';
		/**
		 * NOTE: //if you want to use a different sub sites table for sharing, you can replcace $wpdb->vbase_prefix with $wpdb->get_blog_prefix( $blog_id )
		 */
	}



}
/**
 * Instantiate class, creating taxonomy.
 */
new SignType();
