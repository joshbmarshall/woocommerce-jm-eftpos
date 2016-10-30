# woocommerce-jm-eftpos

Adds EFTPOS Savings and EFTPOS Credit Card to a Woocommerce system for in-store payments.

To hide from regular users add a filter such as:

`
function jm_filter_gateways($args) {
	if (!current_user_can('manage_options')) {
		if (isset($args['eftpos-savings'])) {
			unset($args['eftpos-savings']);
		}
		if (isset($args['eftpos-cc'])) {
			unset($args['eftpos-cc']);
		}
	}
	return $args;
}
add_filter( "woocommerce_available_payment_gateways", "jm_filter_gateways", 9999 );
`
