<?php
/**
 * Plugin Name: WooCommerce Payment Gateway - EFTPOS
 * Description: 
 * Version: 1.0.0
 * Author: Josh Marshall
 * License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * @package WordPress
 * @author joshmarshall
 * @since 1.0.0
 */
add_action('plugins_loaded', 'woocommerce_eftpos_commerce_init', 0);

function woocommerce_eftpos_commerce_init() {

	if (!class_exists('WC_Payment_Gateway')) {
		return;
	};

	DEFINE('PLUGIN_DIR', plugins_url(basename(plugin_dir_path(__FILE__)), basename(__FILE__)) . '/');

	/**
	 * EFTPOS Gateway Class
	 */
	class WC_EFTPOS_SAVINGS extends WC_Payment_Gateway {

		function __construct() {

			// Register plugin information
			$this->id = 'eftpos-savings';
			$this->title = 'EFTPOS Savings';
			$this->method_title = 'EFTPOS Savings';
			$this->has_fields = true;
			$this->supports = array(
				'products'
			);

			// Create plugin fields and settings
			$this->init_form_fields();
			$this->init_settings();

			// Get setting values
			foreach ($this->settings as $key => $val) {
				$this->$key = $val;
			}

		}

		/**
		 * Initialize Gateway Settings Form Fields.
		 */
		function init_form_fields() {

			$this->form_fields = array(
				'enabled' => array(
					'title' => __('Enable/Disable', 'woothemes'),
					'label' => __('Enable EFTPOS Savings', 'woothemes'),
					'type' => 'checkbox',
					'description' => '',
					'default' => 'no'
				),
				'title' => array(
					'title' => __('Title', 'woothemes'),
					'type' => 'text',
					'description' => __('This controls the title the user sees during checkout.', 'woothemes'),
					'default' => __('EFTPOS Savings', 'woothemes')
				),
				'description' => array(
					'title' => __('Description', 'woothemes'),
					'type' => 'textarea',
					'description' => __('This controls the description the user sees during checkout.', 'woothemes'),
					'default' => 'Pay with your savings card in store.'
				),
			);
		}

		/**
		 * UI - Admin Panel Options
		 */
		function admin_options() {
			?>
			<h3><?php _e('EFTPOS Savings', 'woothemes'); ?></h3>
			<p><?php _e('The plugin adds EFTPOS Savings Card on the checkout page, for in-store payments.', 'woothemes'); ?></p>
			<table class="form-table">
				<?php $this->generate_settings_html(); ?>
			</table>
			<?php
		}

		/**
		 * UI - Payment page fields for EFTPOS
		 */
		function payment_fields() {
			// Description of payment method from settings
			if ($this->description) {
				?><p><?php echo $this->description; ?></p><?php
			}
		}

		/**
		 * Process the payment and return the result.
		 */
		function process_payment($order_id) {

			$order = new WC_Order($order_id);

			$order->payment_complete();
			$order->add_order_note('Paid in store with Savings card');

			// Return thank you redirect
			return array(
				'result' => 'success',
				'redirect' => $this->get_return_url($order),
			);
		}

	}

	/**
	 * EFTPOS Gateway Class
	 */
	class WC_EFTPOS_CC extends WC_Payment_Gateway {

		function __construct() {

			// Register plugin information
			$this->id = 'eftpos-cc';
			$this->title = 'EFTPOS Credit Card';
			$this->method_title = 'EFTPOS Credit Card';
			$this->has_fields = true;
			$this->supports = array(
				'products'
			);

			// Create plugin fields and settings
			$this->init_form_fields();
			$this->init_settings();

			// Get setting values
			foreach ($this->settings as $key => $val) {
				$this->$key = $val;
			}

		}

		/**
		 * Initialize Gateway Settings Form Fields.
		 */
		function init_form_fields() {

			$this->form_fields = array(
				'enabled' => array(
					'title' => __('Enable/Disable', 'woothemes'),
					'label' => __('Enable EFTPOS Credit Card', 'woothemes'),
					'type' => 'checkbox',
					'description' => '',
					'default' => 'no'
				),
				'title' => array(
					'title' => __('Title', 'woothemes'),
					'type' => 'text',
					'description' => __('This controls the title the user sees during checkout.', 'woothemes'),
					'default' => __('EFTPOS Credit Card', 'woothemes')
				),
				'description' => array(
					'title' => __('Description', 'woothemes'),
					'type' => 'textarea',
					'description' => __('This controls the description the user sees during checkout.', 'woothemes'),
					'default' => 'Pay with your credit card in store.'
				),
			);
		}

		/**
		 * UI - Admin Panel Options
		 */
		function admin_options() {
			?>
			<h3><?php _e('EFTPOS Credit Card', 'woothemes'); ?></h3>
			<p><?php _e('The plugin adds EFTPOS Credit Card on the checkout page, for in-store payments.', 'woothemes'); ?></p>
			<table class="form-table">
				<?php $this->generate_settings_html(); ?>
			</table>
			<?php
		}

		/**
		 * UI - Payment page fields for EFTPOS 
		 */
		function payment_fields() {
			// Description of payment method from settings
			if ($this->description) {
				?><p><?php echo $this->description; ?></p><?php
			}
		}

		/**
		 * Process the payment and return the result.
		 */
		function process_payment($order_id) {

			$order = new WC_Order($order_id);

			$order->payment_complete();
			$order->add_order_note('Paid in store with Credit Card');

			// Return thank you redirect
			return array(
				'result' => 'success',
				'redirect' => $this->get_return_url($order),
			);
		}


	}

	/**
	 * Add the gateway to woocommerce
	 */
	function add_eftpos_commerce_gateway($methods) {
		$methods[] = 'WC_EFTPOS_SAVINGS';
		$methods[] = 'WC_EFTPOS_CC';
		return $methods;
	}

	add_filter('woocommerce_payment_gateways', 'add_eftpos_commerce_gateway');
}
