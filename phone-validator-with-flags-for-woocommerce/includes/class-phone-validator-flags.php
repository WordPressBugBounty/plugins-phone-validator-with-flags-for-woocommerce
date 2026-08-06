<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Phone_Validator_Flags {

    public static function init() {
        if ( ! class_exists( 'WooCommerce' ) ) return;

        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );
        add_action( 'woocommerce_after_checkout_billing_form', [ __CLASS__, 'inject_hidden_input' ] );
        add_filter( 'woocommerce_shipping_fields', [ __CLASS__, 'add_shipping_phone_field' ] );
    }

    public static function enqueue_scripts() {

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
            'pvfwc-script',
            PVFWC_URL . 'assets/js/scripts.js',
            [ 'pvfwc-intl-tel-js', 'jquery' ],
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

        wp_localize_script( 'pvfwc-script', 'PVFWC_DATA', [
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

    public static function inject_hidden_input() {
        // placeholder ensure loading scripts in classic checkout
    }

    public static function add_shipping_phone_field( $fields ) {
        if ( get_option( 'pvfwc_enable_shipping', 'no' ) !== 'yes' ) return $fields;

        $fields['shipping_phone'] = [
            'label'        => __( 'Shipping phone', 'phone-validator-with-flags-for-woocommerce' ),
            'required'     => false,
            'class'        => [ 'form-row-wide' ],
            'validate'     => [ 'phone' ],
            'autocomplete' => 'tel',
            'priority'     => 25,
        ];

        return $fields;
    }
}