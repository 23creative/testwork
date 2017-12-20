<?php
class Woo_Custom_Emails_Output {

	// Define vars
	protected $version;

	// Class constructor
	public function __construct() {

		$this->version = '1.0.4';

		add_action( 'woocommerce_order_status_processing', array( $this, 'status_action_processing' ), 1 );
		add_action( 'woocommerce_order_status_processing', array( $this, 'woo_custom_emails_insert_content' ), 2 );

		add_action( 'woocommerce_order_status_on-hold', array( $this, 'status_action_onhold' ), 1 );
		add_action( 'woocommerce_order_status_on-hold', array( $this, 'woo_custom_emails_insert_content' ), 2 );

		add_action( 'woocommerce_order_status_completed', array( $this, 'status_action_completed' ), 1 );
		add_action( 'woocommerce_order_status_completed', array( $this, 'woo_custom_emails_insert_content' ), 2 );

	}

	public function status_action_processing(){
		global $this_order_status_action;
		$this_order_status_action = "woocommerce_order_status_processing";
	}

	public function status_action_onhold(){
		global $this_order_status_action;
		$this_order_status_action = "woocommerce_order_status_on-hold";
	}

	public function status_action_completed(){
		global $this_order_status_action;
		$this_order_status_action = "woocommerce_order_status_completed";
	}

	// Insert the custom content into the email template at the chosen location
	public function woo_custom_emails_insert_content() {

		global $this_order_status_action;

		// Add action for each email template location to inject our custom message
		add_action( 'woocommerce_email_before_order_table', 'woo_custom_emails_output_message', 1 );
		add_action( 'woocommerce_email_after_order_table', 'woo_custom_emails_output_message', 2 );
		add_action( 'woocommerce_email_order_meta', 'woo_custom_emails_output_message', 3 );
		add_action( 'woocommerce_email_customer_details', 'woo_custom_emails_output_message', 4 );

		// Output the custom message
		function woo_custom_emails_output_message( $order ){

			global $this_order_status_action;

			// Get items in this order
			$items = $order->get_items();

			// Loop through all items in this order
			foreach ( $items as $item ) {

				// Get this product ID
				$this_product_id = $item['product_id'];

				// Get this meta
				$orderstatus_meta = get_post_meta( $this_product_id, 'order_status', true );
				$customcontent_meta = get_post_meta( $this_product_id, 'custom_content', true );
				$templatelocation_meta = get_post_meta( $this_product_id, 'location', true );

				$this_email_template_location = (string) current_action();

				// If there is custom content
				if ( $customcontent_meta ){

					// If order status meta is set to this order status action
					if ( $orderstatus_meta == $this_order_status_action ) {

						// If template location meta is set to this current action, show it
						if ( $templatelocation_meta == $this_email_template_location ) {

							// Define output var
							$output = '';

							// Build output string
							$output .= $customcontent_meta;

							// Output everything
							echo $output;
						}

					}

				}

			}

		}

	}

}
