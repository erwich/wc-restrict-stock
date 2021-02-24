<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wich.tech
 * @since      1.0.0
 *
 * @package    Wc_Restrict_Stock
 * @subpackage Wc_Restrict_Stock/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wc_Restrict_Stock
 * @subpackage Wc_Restrict_Stock/includes
 * @author     Eric Wich <eric@wich.tech>
 */
class Wc_Restrict_Stock {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wc_Restrict_Stock_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WC_HIDE_STOCK_VERSION' ) ) {
			$this->version = WC_HIDE_STOCK_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wc-restrict-stock';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wc_Restrict_Stock_Loader. Orchestrates the hooks of the plugin.
	 * - Wc_Restrict_Stock_i18n. Defines internationalization functionality.
	 * - Wc_Restrict_Stock_Admin. Defines all hooks for the admin area.
	 * - Wc_Restrict_Stock_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wc-restrict-stock-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wc-restrict-stock-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wc-restrict-stock-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wc-restrict-stock-public.php';

		$this->loader = new Wc_Restrict_Stock_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wc_Restrict_Stock_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wc_Restrict_Stock_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wc_Restrict_Stock_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'woocommerce_product_options_inventory_product_data', $plugin_admin, 'wcrs_create_inventory_restriction_field' );
		$this->loader->add_action( 'woocommerce_product_options_inventory_product_data', $plugin_admin, 'wcrs_create_inventory_restriction_notes_field' );
		$this->loader->add_action( 'woocommerce_product_options_inventory_product_data', $plugin_admin, 'wcrs_create_inventory_hidden_field' );

		$this->loader->add_action( 'woocommerce_process_product_meta', $plugin_admin, 'wcrs_save_inventory_restriction_field', 10, 2 );
		$this->loader->add_action( 'woocommerce_process_product_meta', $plugin_admin, 'wcrs_save_inventory_restriction_notes_field', 10, 2 );
		$this->loader->add_action( 'woocommerce_process_product_meta', $plugin_admin, 'wcrs_save_inventory_hidden_field', 10, 2 );


	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wc_Restrict_Stock_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action( 'woocommerce_add_to_cart_validation', $plugin_public, 'wcrs_process_cart_restrictions', 10, 3 );
		$this->loader->add_action( 'woocommerce_after_cart_item_quantity_update', $plugin_public, 'wcrs_cart_update_quantity', 10, 4 );

		$this->loader->add_action( 'woocommerce_before_single_product', $plugin_public, 'wcrs_single_product_update_quantity' );
		$this->loader->add_action( 'woocommerce_before_shop_loop_item', $plugin_public, 'wcrs_single_product_update_quantity' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wc_Restrict_Stock_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
