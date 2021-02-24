<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wich.tech
 * @since      1.0.0
 *
 * @package    Wc_Restrict_Stock
 * @subpackage Wc_Restrict_Stock/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wc_Restrict_Stock
 * @subpackage Wc_Restrict_Stock/public
 * @author     Eric Wich <eric@wich.tech>
 */
class Wc_Restrict_Stock_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wc-restrict-stock-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wc-restrict-stock-public.js', array( 'jquery' ), $this->version, false );

	}

	function get_restriction_quantity_notes( $id ) {
		$notes = wc_get_product( $id ) -> get_meta( 'wcrs_restrict_count_notes' );
		$restrict_count = wc_get_product( $id ) -> get_meta( 'wcrs_restrict_count' );

		if( !is_numeric( $restrict_count ) ) {
			$restrict_count = "a certain amount";
		}

		return $notes && strlen( $notes ) > 0 ? $notes : "This item is limited to $restrict_count per customer. Thank you.";
	}

	function wcrs_process_cart_restrictions( $passed_validation, $product_id, $qty ) {
		global $woocommerce;

		$product = wc_get_product( $product_id );
		$restrict_count = $product->get_meta( 'wcrs_restrict_count' );
		$restrict_count_notes = $this->get_restriction_quantity_notes( $product_id );

		if( !is_numeric( $restrict_count ) || $restrict_count == 0 ) {
			return $passed_validation;
		}

		$cart_items = $woocommerce->cart->get_cart();
		$count = $qty;

		foreach( $cart_items as $_item => $_values ) {
			if( $_values['data']->get_id() == $product_id ) {
				$count += $_values['quantity'];
			}
		}

		if( $count > $restrict_count ) {
			wc_add_notice( __( $restrict_count_notes, 'woocommerce' ), 'error' );
			return false;
		}

		return $passed_validation;
	}

	public function wcrs_cart_update_quantity( $cart_item_key, $quantity, $old_quantity, $cart ) {
		$cart_data = $cart->get_cart();
		$cart_item = $cart_data[$cart_item_key];

		/* Get information about any product quantity restrictions */
		$restrict_qty = $cart_item['data']->get_meta( 'wcrs_restrict_count' );
		$restrict_qty_notes = $this->get_restriction_quantity_notes( $cart_item['data']->get_id() );

		/* Get information about any hidden/reserved for this product */
		$hide_qty = $cart_item['data']->get_meta( 'wcrs_hide_count' );

		$product_stock = $cart_item['data']->get_stock_quantity();

		if( is_numeric( $restrict_qty ) && $restrict_qty > 0 ) {
			if( $quantity > $restrict_qty ) {
				wc_add_notice( __( $restrict_qty_notes, 'woocommerce' ), 'error' );
				$cart->cart_contents[ $cart_item_key ]['quantity'] = max( $old_quantity, $restrict_qty );
			}
		}

		if( is_numeric( $hide_qty ) && $hide_qty > 0 ) {
			if( $product_stock - $hide_qty - $quantity <= 0 ) {
				/* Set quantity to maximum it can be (product stuck, minus what we're hiding) */
				$cart->cart_contents[ $cart_item_key ]['quantity'] = $product_stock - $hide_qty;
			}
		}
	}

	public function wcrs_single_product_update_quantity() {
		global $product;
		$hide_qty = $product->get_meta( 'wcrs_hide_count' );
		if( is_numeric( $hide_qty ) && $hide_qty > 0 ) {
			$product->set_stock_quantity(
				max( $product->get_stock_quantity() - $hide_qty, 0 )
			);
			if( $product->get_stock_quantity() == 0 ) {
				$product->set_stock_status( 'outofstock' );
			}
		}
	}
}
