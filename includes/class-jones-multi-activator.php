<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/nickmortensen
 * @since      1.0.0
 *
 * @package    Jones_Multi
 * @subpackage Jones_Multi/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Jones_Multi
 * @subpackage Jones_Multi/includes
 * @author     Nick Mortensen <nmortensen@jonessign.com>
 */
class Jones_Multi_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		require_once __DIR__ . '/cmb2/init.php';
		// Custom Taxonomy definitions.
		// require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/custom-taxonomies/sign-type-taxonomy.php';
	}

}
