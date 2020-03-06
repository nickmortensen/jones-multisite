<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/user/nickmortensen
 * @since      1.0.0
 *
 * @package    Jones_Multisite
 * @subpackage Jones_Multisite/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Jones_Multisite
 * @subpackage Jones_Multisite/includes
 * @author     Nick Mortensen <nmortensen@jonessign.com>
 */
class Jones_Multisite_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		// Load CMB2 for custom fields on taxonomies and post types.
		require_once __DIR__ . '/cmb2/init.php';

		// Always run this on activation/deactivation.
		flush_rewrite_rules();
	}

}
