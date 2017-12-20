<?php
/**
 * Adds a custom field to the 'General' tab of a Simple Product in WooCommerce.
 *
 * @package CWF\Admin
 */
class Custom_WooCommerce_Field {

	/**
	 * Maintains a value to the text field ID for serialization.
	 *
	 * @access private
	 * @var    string
	 */
	private $textfield_id;

	/**
	 * Selector for which type of field to build.
	 *
	 * @access private
	 * @var    string
	 */
	private $field_selector_type;

	/**
	 * Initializes the class and the instance variables.
	 */
	public function __construct( $id, $fieldselect ) {
		$this->textfield_id = $id;
		$this->field_selector_type = $fieldselect;
	}

	/**
	 * Initializes the hooks for adding the text field and saving the values.
	 */
	public function init() {

		if ( 'radio-orderstatus' == $this->field_selector_type ) {
			add_action( 'woocommerce_product_options_general_product_data', array( $this, 'add_orderstatusemail_field' ) );
		}

		if ( 'customcontent' == $this->field_selector_type ) {
			add_action( 'woocommerce_product_options_general_product_data', array( $this, 'add_customcontent_field' ) );
		}

		if ( 'radio-location' == $this->field_selector_type ) {
			add_action( 'woocommerce_product_options_general_product_data', array( $this, 'add_templatelocation_field' ) );
		}

		add_action( 'woocommerce_process_product_meta', array( $this, 'save_woo_custom_emails_fields' ) );


		// add necessary scripts
		add_action( 'plugins_loaded', function() {
			global $pagenow;
			if( $GLOBALS['pagenow'] == 'post.php' ){
				add_action( 'admin_print_scripts', array( $this, 'my_admin_scripts' ) );
			}
		});

	}

	public function my_admin_scripts() {
		wp_enqueue_script('jquery');
	}

	/**
	 * Initializes and renders the contents of the Order Status Email field.
	 */
	public function add_orderstatusemail_field() {
		echo '<div class="options_group orderstatusemail show_if_simple show_if_variable show_if_external ">';

		$label = sanitize_text_field( 'Order Status Email' );
		$description = sanitize_text_field( 'Which Customer Order Status Email will display the Custom Email Content.' );

		woocommerce_wp_select(
			array(
				'id' 			=> $this->textfield_id,
				'label' 		=> $label,
				'options' 		=> array(
					'woocommerce_order_status_processing' => __( 'Processing', 'woo_custom_emails_domain' ),
					'woocommerce_order_status_on-hold' => __( 'On-Hold', 'woo_custom_emails_domain' ),
					'woocommerce_order_status_completed' => __( 'Completed', 'woo_custom_emails_domain' ),
				),
				'desc_tip'		=> true,
				'description'	=> $description,
			)
		);

		echo '</div>';
	}

	/**
	 * Initializes and renders the contents of the CustomContent field.
	 */
	public function add_customcontent_field() {
		echo '<div class="options_group customcontent show_if_simple show_if_variable show_if_external ">';

		$label = sanitize_text_field( 'Custom Email Content' );
		$placeholder = sanitize_text_field( 'Enter your custom Text or HTML' );
		$description = sanitize_text_field( 'Enter your custom text/HTML to be displayed in the email template.' );

		woocommerce_wp_textarea_input(
			array(
				'id' 			=> $this->textfield_id,
				'label'			=> $label,
				'placeholder'	=> $placeholder,
				'desc_tip'		=> true,
				'description'	=> $description,
				'cols'			=> 60
			)
		);

		echo '</div>';
		echo '<style>.woocommerce_options_panel textarea#custom_content { min-height: 200px; width: 90%; font-size: 12px; font-family: \'Courier\', serif; line-height: normal; }</style>';
	}

	/**
	 * Initializes and renders the contents of the ContentLocation field.
	 */
	public function add_templatelocation_field() {
		echo '<div class="options_group templatelocation show_if_simple show_if_variable show_if_external ">';

		$label = sanitize_text_field( 'Content Location' );
		$description = sanitize_text_field( 'Select where your custom content will display in the email template.' );

		woocommerce_wp_radio(
			array(
				'id' 		=> $this->textfield_id,
				'label' 	=> $label,
				'options' 	=> array(
					'woocommerce_email_before_order_table' 	=> __( 'Before Order Table', 'woo_custom_emails_domain' ),
					'woocommerce_email_after_order_table' => __( 'After Order Table', 'woo_custom_emails_domain' ),
					'woocommerce_email_order_meta' => __( 'After Order Meta', 'woo_custom_emails_domain' ),
					'woocommerce_email_customer_details' => __( 'After Customer Details', 'woo_custom_emails_domain' )
				),
				'desc_tip'		=> true,
				'description'	=> $description,
			)
		);

		echo '</div>';
	}

	/**
	 * Saves a sanitized version of the values provided by the user.
	 *
	 * @param int $post_id The ID of the current post to which the IDs are associated.
	 */
	public function save_woo_custom_emails_fields( $post_id ) {

		$allowed_html = array(
			'article' => array(
				'title' => array(),
				'class' => array(),
				'style' => array()
				),
			'section' => array(
				'title' => array(),
				'class' => array(),
				'style' => array()
				),
			'div' => array(
				'title' => array(),
				'class' => array(),
				'style' => array()
				),
			'h1' => array(
				'title' => array(),
				'class' => array(),
				'style' => array()
				),
			'h2' => array(
				'title' => array(),
				'class' => array(),
				'style' => array()
				),
			'h3' => array(
				'title' => array(),
				'class' => array(),
				'style' => array()
				),
			'h4' => array(
				'title' => array(),
				'class' => array(),
				'style' => array()
				),
			'h5' => array(
				'title' => array(),
				'class' => array(),
				'style' => array()
				),
			'h6' => array(
				'title' => array(),
				'class' => array(),
				'style' => array()
				),
			'ul' => array(
				'title' => array(),
				'class' => array(),
				'style' => array()
				),
			'ol' => array(
				'title' => array(),
				'class' => array(),
				'style' => array()
				),
			'li' => array(
				'title' => array(),
				'class' => array(),
				'style' => array()
				),
			'p' => array(
				'title' => array(),
				'class' => array(),
				'style' => array()
				),
			'a' => array(
				'title' => array(),
				'class' => array(),
				'style' => array(),
				'href' => array(),
				'target' => array()
				),
			'b' => array(
				'title' => array(),
				'class' => array(),
				'style' => array()
				),
			'strong' => array(
				'title' => array(),
				'class' => array(),
				'style' => array()
				),
			'i' => array(
				'title' => array(),
				'class' => array(),
				'style' => array()
				),
			'em' => array(
				'title' => array(),
				'class' => array(),
				'style' => array()
				),
			'span' => array(
				'title' => array(),
				'class' => array(),
				'style' => array()
				),
			'hr' => array(
				'title' => array(),
				'class' => array(),
				'style' => array()
				),
			'img' => array(
				'title' => array(),
				'class' => array(),
				'style' => array(),
				'align' => array(),
				'src' => array()
				),
			'br' => array(),
			);

		if ( ! ( isset( $_POST['woocommerce_meta_nonce'], $_POST[ $this->textfield_id ] ) || wp_verify_nonce( sanitize_key( $_POST['woocommerce_meta_nonce'] ), 'woocommerce_save_data' ) ) ) { // Input var okay.
			return false;
		}

		if ( ! empty( $_POST[$this->textfield_id] ) ) {
			update_post_meta(
				$post_id,
				$this->textfield_id,
				wp_kses( $_POST[$this->textfield_id], $allowed_html )
			);
		}


	}
}
