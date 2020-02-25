<?php

/**
 * The 'service' taxonomy.
 *
 * @link       https://github.com/nickmortensen
 * @since      1.0.0
 *
 * @package    Jones_Multi
 * @subpackage Jones_Multi/admin
 */

/**
 * The 'service' taxonomy.
 *
 * Registers taxonomy name, labels, & parameters.
 *
 * @package    Jones_Multi
 * @subpackage Jones_Multi/admin
 * @author     Nick Mortensen <nmortensen@jonessign.com>
 */
class Services {
	/**
	 * The arguments of this taxonomy.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $type The arguments of this taxonomy..
	 */
	private $type = 'services';
	/**
	 * The slug of this taxonomy.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $slug The slug of this taxonomy..
	 */
	private $slug = 'service';
	/**
	 * The name of this taxonomy.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $name The slug of this taxonomy..
	 */
	private $name = 'service';
	/**
	 * The singular of this taxonomy.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $name The slug of this taxonomy..
	 */
	private $singular_name  = 'Service';


	/**
	 * Create the taxonomy for 'service'.
	 *
	 * Create a custom taxonomy for all sites of 'service'.
	 *
	 * @since    1.0.0
	 */
	public static function register() {
		$labels = [
			'add_new_item'               => __( 'Add Service', 'JonesMulti' ),
			'add_or_remove_items'        => __( 'Add or Remove Service', 'JonesMulti' ),
			'all_items'                  => __( 'All Service', 'JonesMulti' ),
			'back_to_items'              => __( 'Back to Services', 'JonesMulti' ),
			'choose_from_most_used'      => __( 'Often Used Services', 'JonesMulti' ),
			'edit_item'                  => __( 'Edit Service', 'JonesMulti' ),
			'items_list'                 => __( 'Service List', 'JonesMulti' ),
			'items_list_navigation'      => __( 'Service List Nav', 'JonesMulti' ),
			'menu_name'                  => __( 'Service Tags', 'JonesMulti' ),
			'name'                       => _x( 'Service', 'Taxonomy General Name', 'JonesMulti' ),
			'new_item_name'              => __( 'New Service Tag', 'JonesMulti' ),
			'no_terms'                   => __( 'No Service Tags', 'JonesMulti' ),
			'not_found'                  => __( 'Service Not Found', 'JonesMulti' ),
			'popular_items'              => __( 'Popular Services', 'JonesMulti' ),
			'search_items'               => __( 'Search Services', 'JonesMulti' ),
			'separate_items_with_commas' => __( 'Separate Services w/commas', 'JonesMulti' ),
			'singular_name'              => _x( 'Service', 'Taxonomy Singular Name', 'JonesMulti' ),
			'update_item'                => __( 'Update Service', 'JonesMulti' ),
			'view_item'                  => __( 'View Service ', 'JonesMulti' ),
		];

		$args = [
			'hierarchical'          => false,
			'description'           => 'Apply an Service Tag to Photos or Project pages.',
			'labels'                => $labels,
			'public'                => true, // Sets the defaults for 'publicly_queryable', 'show_ui', & 'show_in_nav_menus' as well.
			'query_var'             => 'service',
			'show_in_menu'          => true,
			'show_in_rest'          => true,
			'rewrite'               => array( 'slug' => 'service' ),
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

		register_taxonomy( $this->type, $objects_array, $args );
	}
	/**
	 * Create the extra fields for 'service'.
	 *
	 * Use CMB2 to create additional fields for the expertise taxonomy.
	 *
	 * @since    1.0.0
	 */
	public static function register_expertise_taxonomy_metabox() {
		$prefix = 'expertise_';
			// Create an instance of the cmbs2box called $expertise.
		$newfields = new_cmb2_box(
			array(
				'id'           => $prefix . 'edit',
				'title'        => esc_html__( 'Service Additional Info', 'JonesMulti' ),
				'object_types' => array( 'term' ), // indicate to cmb we are using terms and not posts.
				'taxonomies'   => array( 'service' ), // Fields can be added to more than one taxonomy term, but we will limit these just to the expertise taxonomy term.
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
			'desc'         => 'Several Images that are representative of this area of expertise',
			'id'           => 'expertiseMainImages',
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
	 * Event constructor.
	 *
	 * When class is instantiated
	 */
	public function __construct() {
		// Register the taxonomy.
		add_action( 'init', [ $this, 'register' ] );
		// Setup the extra fields.
		add_action( 'cmb2_init', [ $this, 'register_expertise_taxonomy_metabox' ] );
		// Admin set post columns
		// add_filter( 'manage_edit-'.$this->type.'_columns',        array($this, 'set_columns'), 10, 1) ;

		// Admin edit post columns
		// add_action( 'manage_'.$this->type.'_posts_custom_column', array($this, 'edit_columns'), 10, 2 );

	}

}
/**
 * Instantiate class, creating taxonomy.
 */
new Services();
