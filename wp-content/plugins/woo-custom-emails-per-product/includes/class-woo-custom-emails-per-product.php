<?php

class Woo_Custom_Emails_Per_Product {

	// Define vars
	protected $loader;
	protected $plugin_slug;
	protected $version;

	// Class constructor
	public function __construct() {

		$this->plugin_slug = 'woo_custom_emails_domain';
		$this->version = '1.0.4';

		$this->woo_custom_emails_load_dependencies();
		$this->woo_custom_emails_define_admin_hooks();

	}

	// Load required files
	private function woo_custom_emails_load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-woo-custom-emails-per-product-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-custom-woocommerce-field.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woo-custom-emails-per-product-loader.php';
		$this->loader = new Woo_Custom_Emails_Per_Product_Loader();

	}

	// Setup Admin Hooks
	private function woo_custom_emails_define_admin_hooks() {
		$admin = new Woo_Custom_Emails_Per_Product_Admin( $this->get_version() );
		$this->loader->add_action( 'plugins_loaded', $admin, 'add_woo_custom_emails_fields' );
	}

	// Function to get plugin version
	public function get_version() {
		return $this->version;
	}

	// Run everything
	public function run() {
		$this->loader->run();
	}

}
