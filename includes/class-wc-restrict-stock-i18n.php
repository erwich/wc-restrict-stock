<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://wich.tech
 * @since      1.0.0
 *
 * @package    Wc_Restrict_Stock
 * @subpackage Wc_Restrict_Stock/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wc_Restrict_Stock
 * @subpackage Wc_Restrict_Stock/includes
 * @author     Eric Wich <eric@wich.tech>
 */
class Wc_Restrict_Stock_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wc-restrict-stock',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
