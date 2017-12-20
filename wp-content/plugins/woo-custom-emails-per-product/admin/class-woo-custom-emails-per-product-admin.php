<?php
/**
 * Responsible for rendering the admin view of the content.
 *
 * @package woo_custom_emails_domain\admin
 */

class Woo_Custom_Emails_Per_Product_Admin {

	private $version;

	public function __construct( $version ) {
		$this->version = $version;
	}

	public function add_woo_custom_emails_fields() {

		if ( is_admin() ) {

			$newCWF_orderstatus = new Custom_WooCommerce_Field( 'order_status', 'radio-orderstatus' );
			$newCWF_orderstatus->init();

			$newCWF_content = new Custom_WooCommerce_Field( 'custom_content', 'customcontent' );
			$newCWF_content->init();

			$newCWF_location = new Custom_WooCommerce_Field( 'location', 'radio-location' );
			$newCWF_location->init();

		}
	}

}
