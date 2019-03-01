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
        $post_types = array( 'livros' );
 
        if ( in_array( $post_type, $post_types ) ) {
            add_meta_box(
                $this->plugin_name . '_meta_box',
                __( 'Local do Evento' ),
                array( $this, 'render_meta_box_content' ),
                $post_type,
                'side',
                'default'
            );
        }
	}
	
	/**
     * Render Meta Box content.
     *
     * @param WP_Post $post The post object.
     */
    public function render_meta_box_content( $post ) {
 
        // Add an nonce field so we can check for it later.
        wp_nonce_field( $this->plugin_name . '_inner_custom_box', $this->plugin_name . '_inner_custom_box_nonce' );
 
		// Use get_post_meta to retrieve an existing value from the database.
        $estados_value = get_post_meta( $post->ID, $this->plugin_name . '_estados', true );
        $cidades_value = get_post_meta( $post->ID, $this->plugin_name . '_cidades', true );
 
		// REQUEST
		$estados = get_transient( $this->plugin_name . '_estados' );
		
		// if( false === $estados ) {
		// 	// Transient expired, refresh the data
		// 	$estados = wp_remote_retrieve_body( wp_remote_get( 'https://servicodados.ibge.gov.br/api/v1/localidades/estados' ) );
		// 	set_transient( $this->plugin_name . '_estados', $estados, 60*60 );
		// }

		$estados = json_decode($estados);
		foreach ($estados as $estado) {
			$estados_arr[] = array( 'label' => $estado->nome, 'id' => $estado->id );
			
			// REQUEST
			$cidades[$estado->id] = get_transient( $this->plugin_name . '_cidades_' . $estado->id );
			
			// if( false === $cidades[$estado->id] ) {
			// 	// Transient expired, refresh the data
			// 	$cidades[$estado->id] = wp_remote_retrieve_body( wp_remote_get( 'https://servicodados.ibge.gov.br/api/v1/localidades/estados/' . $estado->id . '/municipios' ) );
			// 	set_transient( $this->plugin_name . '_cidades_' . $estado->id, $cidades[$estado->id], 60*60 );
			// }
		}

		foreach ($cidades as $key => $cidades_estado) {
			$cidades_estado = json_decode($cidades_estado);
            $cidades_estado_arr = array();
			foreach ($cidades_estado as $cidade) {
                // var_dump($cidade->nome);exit;
				$cidades_estado_arr[] = array( 'label' => $cidade->nome, 'id' => $cidade->id );
			}
			$cidades_arr[$key] = $cidades_estado_arr;
		}

        // Display the form, using the current value.
		?>
		<script>
		$( function() {
			var estados = <?php echo json_encode($estados_arr); ?>;
			var cidades = <?php echo json_encode($cidades_arr); ?>;

			$( "#<?php echo $this->plugin_name . '_estados'; ?>" ).autocomplete({
				source: estados,
				select: function(event, ui) {
					$( "#<?php echo $this->plugin_name . '_cidades'; ?>" ).val("");
					$( "#<?php echo $this->plugin_name . '_cidades'; ?>" ).autocomplete({
						source: cidades[ui.item.id]
					});
            	}
			});
		} );
		</script>
		<div class="ui-widget">
			<label for="<?php echo $this->plugin_name . '_estados'; ?>"><?php _e( 'Estado:' ); ?> </label>
			<input name="<?php echo $this->plugin_name . '_estados'; ?>" id="<?php echo $this->plugin_name . '_estados'; ?>" value="<?php echo ($estados_value) ? esc_attr($estados_value) : ''; ?>">
		</div>
		<div class="ui-widget">
			<label for="<?php echo $this->plugin_name . '_cidades'; ?>"><?php _e( 'Cidade:' ); ?> </label>
			<input name="<?php echo $this->plugin_name . '_cidades'; ?>" id="<?php echo $this->plugin_name . '_cidades'; ?>" value="<?php echo ($cidades_value) ? esc_attr($cidades_value) : ''; ?>">
		</div>
        <?php
	}
	
	/**
     * Save the meta when the post is saved.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function save( $post_id ) {
 
        /*
         * We need to verify this came from the our screen and with proper authorization,
         * because save_post can be triggered at other times.
         */
 
        // Check if our nonce is set.
        if ( ! isset( $_POST[$this->plugin_name . '_inner_custom_box_nonce'] ) ) {
            return $post_id;
        }
 
        $nonce = $_POST[$this->plugin_name . '_inner_custom_box_nonce'];
 
        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, $this->plugin_name . '_inner_custom_box' ) ) {
            return $post_id;
        }
 
        /*
         * If this is an autosave, our form has not been submitted,
         * so we don't want to do anything.
         */
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
 
        // Check the user's permissions.
        if ( 'page' == $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }
 
        /* OK, it's safe for us to save the data now. */
 
        // Sanitize the user input.
        $estados = sanitize_text_field( $_POST[$this->plugin_name . '_estados'] );
		$cidades = sanitize_text_field( $_POST[$this->plugin_name . '_cidades'] );
 
        // Update the meta field.
        update_post_meta( $post_id, $this->plugin_name . '_estados', $estados );
        update_post_meta( $post_id, $this->plugin_name . '_cidades', $cidades );
    }

}
