<?php
/**
 * Plugin Name: Phone Validator with Flags for WooCommerce
 * Description: Adds a country flag and phone validation to the WooCommerce checkout phone field. Supports Classic and Block Checkout.
 * Version: 2.0.0
 * Author: Mokhtar Bensaid
 * Author URI: https://mokhtarbensaid.com
 * Text Domain: phone-validator-with-flags-for-woocommerce
 * Domain Path: /languages
 * Requires PHP: 7.4
 * Requires at least: 6.8
 * Tested up to: 6.9.4
 * WC tested up to: 10.6.2
 * WC requires at least: 5.0
 * Requires Plugins: woocommerce
 * WC HPOS compatibility: yes
 * License: GPL-2.0+
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'PVFWC_VERSION', '2.0.0' );
define( 'PVFWC_PATH', plugin_dir_path( __FILE__ ) );
define( 'PVFWC_URL', plugin_dir_url( __FILE__ ) );

// HPOS Compatibility
add_action( 'before_woocommerce_init', function () {
    if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
            'custom_order_tables',
            __FILE__,
            true
        );
    }
} );

// Load classes that don't depend on WooCommerce
require_once PVFWC_PATH . 'includes/class-activator.php';
require_once PVFWC_PATH . 'includes/class-deactivator.php';
require_once PVFWC_PATH . 'includes/functions.php';

// Activation & Deactivation hooks
register_activation_hook( __FILE__, [ 'Phone_Validator_Flags_Activator', 'activate' ] );
register_deactivation_hook( __FILE__, [ 'Phone_Validator_Flags_Deactivator', 'deactivate' ] );

// Initialize frontend classes
add_action( 'plugins_loaded', function () {
    if ( ! class_exists( 'WooCommerce' ) ) return;

    require_once PVFWC_PATH . 'includes/class-phone-validator-flags.php';
    require_once PVFWC_PATH . 'includes/class-blocks.php';

    Phone_Validator_Flags::init();
    Phone_Validator_Flags_Blocks::init();
}, 20 );

// Initialize settings page
add_filter( 'woocommerce_get_settings_pages', function ( $settings ) {
    require_once PVFWC_PATH . 'includes/class-settings.php';
    $settings[] = new PVFWC_Settings_Page();
    return $settings;
} );

