<?php
/**
 * The 'expertise' taxonomy.
 *
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
class Expertise {
	/**
	 * The arguments of this taxonomy.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $type The arguments of this taxonomy..
	 */
	private $type = 'expertise';
	/**
	 * The slug of this taxonomy.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $slug The slug of this taxonomy..
	 */
	private $slug = 'expertise';
	/**
	 * The name of this taxonomy.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $name The slug of this taxonomy..
	 */
	private $name = 'expertise';
	/**
	 * The singular of this taxonomy.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $name The slug of this taxonomy..
	 */
	private $singular_name = 'expertise';

	/**
	 * Event constructor.
	 *
	 * When class is instantiated
	 */
	public function __construct() {
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
	 * Create the taxonomy for 'expertise'.
	 *
	 * Create a custom taxonomy for all sites of 'expertise'.
	 *
	 * @since    1.0.0
	 */
	public static function register() {
		$labels = [
			'add_new_item'               => __( 'Add Area of Expertise', 'JonesMulti' ),
			'add_or_remove_items'        => __( 'Add or Remove Expertise', 'JonesMulti' ),
			'all_items'                  => __( 'All Expertise', 'JonesMulti' ),
			'back_to_items'              => __( 'Back to Expertises', 'JonesMulti' ),
			'choose_from_most_used'      => __( 'Often Used Expertises', 'JonesMulti' ),
			'edit_item'                  => __( 'Edit Expertise', 'JonesMulti' ),
			'items_list'                 => __( 'Expertise List', 'JonesMulti' ),
			'items_list_navigation'      => __( 'Expertise List Nav', 'JonesMulti' ),
			'menu_name'                  => __( 'Expertises', 'JonesMulti' ),
			'name'                       => _x( 'Expertise', 'Taxonomy General Name', 'JonesMulti' ),
			'new_item_name'              => __( 'New Expertise Tag', 'JonesMulti' ),
			'no_terms'                   => __( 'No Expertise Tags', 'JonesMulti' ),
			'not_found'                  => __( 'Expertise Not Found', 'JonesMulti' ),
			'popular_items'              => __( 'Popular Expertises', 'JonesMulti' ),
			'search_items'               => __( 'Search Expertises', 'JonesMulti' ),
			'separate_items_with_commas' => __( 'Separate Expertises w/commas', 'JonesMulti' ),
			'singular_name'              => _x( 'Expertise', 'Taxonomy Singular Name', 'JonesMulti' ),
			'update_item'                => __( 'Update Expertise', 'JonesMulti' ),
			'view_item'                  => __( 'View Expertise ', 'JonesMulti' ),
		];

		$args = [
			'hierarchical'          => false,
			'description'           => 'Apply an Expertise Tag to Photos or Project pages.',
			'labels'                => $labels,
			'public'                => true, // Sets the defaults for 'publicly_queryable', 'show_ui', & 'show_in_nav_menus' as well.
			'query_var'             => 'expertise',
			'show_in_menu'          => true,
			'show_in_rest'          => true,
			'rewrite'               => array( 'slug' => 'expertise' ),
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
		];

		register_taxonomy( 'expertise', $objects_array, $args );
	}
	/**
	 * Create the extra fields for 'expertise'.
	 *
	 * Use CMB2 to create additional fields for the expertise taxonomy.
	 *
	 * @since    1.0.0
	 */
	public static function register_taxonomy_metabox() {
		$prefix = 'expertise_';
			// Create an instance of the cmbs2box called $expertise.
		$newfields = new_cmb2_box(
			array(
				'id'           => $prefix . 'edit',
				'title'        => esc_html__( 'Expertise Additional Info', 'JonesMulti' ),
				'object_types' => array( 'term' ), // indicate to cmb we are using terms and not posts.
				'taxonomies'   => array( 'expertise' ), // Fields can be added to more than one taxonomy term, but we will limit these just to the expertise taxonomy term.
				'cmb_styles'   => true, // Disable cmb2 stylesheet.
				'show_in_rest' => WP_REST_Server::ALLMETHODS, // WP_REST_Server::READABLE|WP_REST_Server::EDITABLE, // Determines which HTTP methods the box is visible in.
				// Optional callback to limit box visibility.
				// See: https://github.com/CMB2/CMB2/wiki/REST-API#permissions.
			)
		);

		$args = [
			'name'       => 'Instance',
			'desc'       => 'Scenario Wherein this type of sign is best.',
			'default'    => '',
			'id'         => 'expertiseCases',
			'type'       => 'text',
			'repeatable' => true,
			'attributes' => [ 'rows' => 2 ],
			'text'       => [ 'add_row_text' => 'Add Another Use Case' ],
		];
		$newfields->add_field( $args );

		// Best images should be a file_list field in CMB2. That way there are several images to choose among.
		$args = [
			'name'         => 'Best Images',
			'desc'         => 'Several Images that are representative of this area of expertise',
			'id'           => 'expertiseMainImages',
			'type'         => 'file_list',
			'preview_size' => [ 400, 300 ],
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


}

/**
 * Instantiate class, creating taxonomy.
 */
new Expertise();
