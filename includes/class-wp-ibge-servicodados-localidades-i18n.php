<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://trampos.co/rainerlopez
 * @since      1.0.0
 *
 * @package    Wp_Ibge_Servicodados_Localidades
 * @subpackage Wp_Ibge_Servicodados_Localidades/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_Ibge_Servicodados_Localidades
 * @subpackage Wp_Ibge_Servicodados_Localidades/includes
 * @author     Rainer Eduardo Lopez <rainerdev@gmail.com>
 */
class Wp_Ibge_Servicodados_Localidades_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-ibge-servicodados-localidades',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
