<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://trampos.co/rainerlopez
 * @since      1.0.0
 *
 * @package    Wp_Ibge_Servicodados_Localidades
 * @subpackage Wp_Ibge_Servicodados_Localidades/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Ibge_Servicodados_Localidades
 * @subpackage Wp_Ibge_Servicodados_Localidades/admin
 * @author     Rainer Eduardo Lopez <rainerdev@gmail.com>
 */
class Wp_Ibge_Servicodados_Localidades_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Ibge_Servicodados_Localidades_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Ibge_Servicodados_Localidades_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-ibge-servicodados-localidades-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Ibge_Servicodados_Localidades_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Ibge_Servicodados_Localidades_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-ibge-servicodados-localidades-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Adds the meta box container. for the admin area.
	 *
	 * @since    1.0.0
	 */
    public function add_meta_box( $post_type ) {
        // Limit meta box to certain post types.
        $post_types = array( 'post', 'page' );
 
        if ( in_array( $post_type, $post_types ) ) {
            add_meta_box(
                'some_meta_box_name',
                __( 'Some Meta Box Headline', 'textdomain' ),
                array( $this, 'render_meta_box_content' ),
                $post_type,
                'advanced',
                'high'
            );
        }
    }

}
