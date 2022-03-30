<?php
/**
 * Plugin Name:       Neo Woocommerce Your Custom Fee
 * Description:       User based user entered custom fee, cost of product will be changed.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Tested up to: 	  5.9.2
 * Requires PHP:      7.2
 * Author:            Nilesh Kumar Chouhan 
 * Author URI:        https://www.linkedin.com/in/nilesh-kumar-chouhan-wp-dev/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       woocommerce
 * Domain Path:       /languages
 */

define('WDT_ADMIN_PLACEHOLDER', '');
define('WDT_FRONT_INPUT_LABEL', 'Enter Custom Fee');
define('WDT_FRONT_PLACEHOLDER', '20');
define('WDT_FRONT_AFTER_LABEL', 'Custom Fee');
define('WDT_CART_COLUMN_LABEL', 'Custom Fee');

function neo_wup_init_after_woocommerce() {
	if (class_exists('WooCommerce')) {
    global $wdt_pdo_version;
    $wdt_pdo_version = '1.0.0';
      
			include_once dirname(__FILE__) . '/app.php';
      include_once dirname(__FILE__) . '/product-edit.php';
			include_once dirname(__FILE__) . '/single.php';
      include_once dirname(__FILE__) . '/cart-update.php';

	}
}

add_action('plugins_loaded', 'neo_wup_init_after_woocommerce');
