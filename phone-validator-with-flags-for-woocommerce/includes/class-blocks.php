<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Phone_Validator_Flags_Blocks {

    public static function init() {
        if ( ! class_exists( 'WooCommerce' ) ) return;

        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );
    }

    public static function enqueue_scripts() {

        if ( ! self::is_block_checkout_active() ) return;

        $enable_billing    = get_option( 'pvfwc_enable_billing', 'yes' ) === 'yes';
        $enable_shipping   = get_option( 'pvfwc_enable_shipping', 'no' ) === 'yes';
        $enable_my_account = get_option( 'pvfwc_enable_my_account', 'yes' ) === 'yes';

        $should_load = ( is_checkout() && ( $enable_billing || $enable_shipping ) )
                    || ( is_account_page() && $enable_my_account );

        if ( ! $should_load ) return;

        wp_enqueue_style(
            'pvfwc-intl-tel-css',
            PVFWC_URL . 'assets/css/intlTelInput.min.css',
            [],
            '29.0.1'
        );

        wp_enqueue_style(
            'pvfwc-style-css',
            PVFWC_URL . 'assets/css/style.css',
            [],
            PVFWC_VERSION
        );

        wp_enqueue_script(
            'pvfwc-intl-tel-js',
            PVFWC_URL . 'assets/js/intlTelInputWithUtils.min.js',
            [],
            '29.0.1',
            true
        );

        wp_enqueue_script(
            'pvfwc-blocks-script',
            PVFWC_URL . 'assets/js/scripts-blocks.js',
            [ 'pvfwc-intl-tel-js' ],
            PVFWC_VERSION,
            true
        );

        $wc_base         = wc_get_base_location();
        $default_country = get_option( 'pvfwc_default_country', 'auto' );
        $country_source  = get_option( 'pvfwc_country_source', 'wc' );
        $preferred_raw   = get_option( 'pvfwc_preferred_countries', [] );
        $error_style     = get_option( 'pvfwc_error_style', 'inline' );
        $error_message   = get_option( 'pvfwc_error_message', '' );
        $respect_wc      = get_option( 'pvfwc_respect_wc_validation', 'yes' ) === 'yes';

        if ( $default_country === 'auto' ) {
            $default_country = strtolower( $wc_base['country'] ?? 'us' );
        }

        $shipping_countries = array_keys( WC()->countries->get_shipping_countries() );
        $shipping_countries = array_map( 'strtolower', $shipping_countries );

        if ( $country_source === 'wc' ) {
            $allowed_countries = array_keys( WC()->countries->get_allowed_countries() );
        } else {
            $allowed_countries = array_keys( WC()->countries->get_countries() );
        }

        $allowed_countries = array_map( 'strtolower', $allowed_countries );

        if ( is_string( $preferred_raw ) ) {
            $preferred_countries = array_filter( explode( ',', $preferred_raw ) );
        } else {
            $preferred_countries = (array) $preferred_raw;
        }
        $preferred_countries = array_map( 'strtolower', $preferred_countries );

        if ( empty( $error_message ) ) {
            $error_message = __( 'Please enter a valid phone number including the country code.', 'phone-validator-with-flags-for-woocommerce' );
        }

        wp_localize_script( 'pvfwc-blocks-script', 'PVFWC_DATA', [
            'defaultCountry'      => $default_country,
            'allowedCountries'    => $allowed_countries,
            'preferredCountries'  => $preferred_countries,
            'shippingCountries'   => $shipping_countries,
            'errorStyle'          => $error_style,
            'errorMessage'        => $error_message,
            'respectWcValidation' => $respect_wc,
            'enableBilling'       => $enable_billing,
            'enableShipping'      => $enable_shipping,
            'enableMyAccount'     => $enable_my_account,
            'isCheckout'          => is_checkout(),
            'isMyAccount'         => is_account_page(),
            'imgPath'             => PVFWC_URL . 'assets/img/',
        ] );
    }

    private static function is_block_checkout_active() {
        if ( ! function_exists( 'wc_get_page_id' ) ) return false;

        $checkout_page_id = wc_get_page_id( 'checkout' );
        if ( ! $checkout_page_id ) return false;

        $post = get_post( $checkout_page_id );
        if ( ! $post ) return false;

        return str_contains( $post->post_content, 'wp:woocommerce/checkout' );
    }
}