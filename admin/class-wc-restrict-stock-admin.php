<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wich.tech
 * @since      1.0.0
 *
 * @package    Wc_Restrict_Stock
 * @subpackage Wc_Restrict_Stock/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wc_Restrict_Stock
 * @subpackage Wc_Restrict_Stock/admin
 * @author     Eric Wich <eric@wich.tech>
 */
class Wc_Restrict_Stock_Admin {

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
		 * defined in Wc_Restrict_Stock_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wc_Restrict_Stock_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wc-restrict-stock-admin.css', array(), $this->version, 'all' );

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
		 * defined in Wc_Restrict_Stock_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wc_Restrict_Stock_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wc-restrict-stock-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the custom field for tracking how much of a product to restrict
	 *
	 * @since    1.0.0
	 */
	public function wcrs_create_inventory_restriction_field() {
		woocommerce_wp_text_input(
			array(
				'id' => 'wcrs_restrict_count',
				'label' => __( 'Quantity to restrict', 'woocommerce' ),
				'class' => 'wcrs-custom-field',
				'desc_tip' => true,
				'description' => __( 'If you wish to only allow a certain number of this item to be purchased in one order, set that amount here.', 'woocommerce' ),
				'value' => get_post_meta( get_the_ID(), 'wcrs_restrict_count', true ),
				'type' => 'number',
				'custom_attributes' => array(
					'step' 	=> 'any',
					'min'	=> '0'
				) 
			)
		);
	}

	/**
	 * Register the custom field for tracking notes about quantity restrictions
	 *
	 * @since    1.0.0
	 */
	public function wcrs_create_inventory_restriction_notes_field() {
		woocommerce_wp_text_input(
			array(
				'id' => 'wcrs_restrict_count_notes',
				'label' => __( 'Restriction Notes', 'woocommerce' ),
				'class' => 'wcrs-custom-field',
				'desc_tip' => true,
				'description' => __( 'Optional note that will display on products that have purchase count restrictions.', 'woocommerce' ),
				'value' => get_post_meta( get_the_ID(), 'wcrs_restrict_count_notes', true ),
				'type' => 'text'
			)
		);
	}

	/**
	 * Register the custom field for tracking how much of a product to hide
	 *
	 * @since    1.0.0
	 */
	public function wcrs_create_inventory_hidden_field() {
		woocommerce_wp_text_input(
			array(
				'id' => 'wcrs_hide_count',
				'label' => __( 'Quantity to hide', 'woocommerce' ),
				'class' => 'wcrs-custom-field',
				'desc_tip' => true,
				'description' => __( 'If you wish to hold sales on a certain number of this product, enter that number here.', 'woocommerce' ),
				'value' => get_post_meta( get_the_ID(), 'wcrs_hide_count', true ),
				'type' => 'number',
				'custom_attributes' => array(
					'step' 	=> 'any',
					'min'	=> '0'
				) 
			)
		);
	}
	
	public function wcrs_save_inventory_restriction_field( $id, $post ) {
		/* Sanitize for numeric, and ensure it's a positive int */
		$restrict_count = is_numeric( $_POST['wcrs_restrict_count'] ) ?
		max( 0, intval( $_POST['wcrs_restrict_count'] ) )
		: 0;
		update_post_meta( $id, 'wcrs_restrict_count', $restrict_count );
	}
	
	public function wcrs_save_inventory_hidden_field( $id, $post ) {
		/* Sanitize for numeric, and ensure it's a positive int */
		$hide_count = is_numeric( $_POST['wcrs_hide_count'] ) ?
		max( 0, intval( $_POST['wcrs_hide_count'] ) )
		: 0;
		update_post_meta( $id, 'wcrs_hide_count', $hide_count );
	}
	
	public function wcrs_save_inventory_restriction_notes_field( $id, $post ) {
		update_post_meta( $id, 'wcrs_restrict_count_notes', sanitize_text_field( $_POST['wcrs_restrict_count_notes'] ) );
	}
}
