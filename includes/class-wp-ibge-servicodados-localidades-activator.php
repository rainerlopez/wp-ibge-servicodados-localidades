<?php

/**
 * Fired during plugin activation
 *
 * @link       http://trampos.co/rainerlopez
 * @since      1.0.0
 *
 * @package    Wp_Ibge_Servicodados_Localidades
 * @subpackage Wp_Ibge_Servicodados_Localidades/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Ibge_Servicodados_Localidades
 * @subpackage Wp_Ibge_Servicodados_Localidades/includes
 * @author     Rainer Eduardo Lopez <rainerdev@gmail.com>
 */
class Wp_Ibge_Servicodados_Localidades_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		$estados = wp_remote_retrieve_body( wp_remote_get( 'https://servicodados.ibge.gov.br/api/v1/localidades/estados' ) );
		set_transient( 'wp-ibge-servicodados-localidades_estados', $estados, 0 );

		$estados = get_transient( 'wp-ibge-servicodados-localidades_estados' );

		$estados = json_decode($estados);
		foreach ($estados as $estado) {
			$cidades[$estado->id] = wp_remote_retrieve_body( wp_remote_get( 'https://servicodados.ibge.gov.br/api/v1/localidades/estados/' . $estado->id . '/municipios' ) );
			set_transient( 'wp-ibge-servicodados-localidades_cidades_' . $estado->id, $cidades[$estado->id], 0 );
		}
	}

}
