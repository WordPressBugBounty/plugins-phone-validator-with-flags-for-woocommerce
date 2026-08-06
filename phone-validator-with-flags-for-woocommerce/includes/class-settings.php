<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class PVFWC_Settings_Page extends WC_Settings_Page {

    public function __construct() {
        $this->id    = 'phone_validator';
        $this->label = __( 'Phone Validator', 'phone-validator-with-flags-for-woocommerce' );
        parent::__construct();
    }

    public function get_settings( $current_section = '' ) {

        $wc_default_country = wc_get_base_location();
        $default_country    = strtolower( $wc_default_country['country'] ?? 'us' );

        $countries = [ 'auto' => __( 'Auto (WooCommerce Store Country)', 'phone-validator-with-flags-for-woocommerce' ) ];
        foreach ( WC()->countries->get_countries() as $code => $name ) {
            $countries[ strtolower( $code ) ] = $name;
        }

        $all_countries_for_preferred = [];
        foreach ( WC()->countries->get_allowed_countries() as $code => $name ) {
            $all_countries_for_preferred[ strtolower( $code ) ] = $name;
        }

        $settings = [

            [
                'title' => __( 'General Settings', 'phone-validator-with-flags-for-woocommerce' ),
                'type'  => 'title',
                'id'    => 'pvfwc_general_section',
            ],

            [
                'title'   => __( 'Default Country', 'phone-validator-with-flags-for-woocommerce' ),
                'desc'    => __( 'The country selected by default when the phone field loads.', 'phone-validator-with-flags-for-woocommerce' ),
                'id'      => 'pvfwc_default_country',
                'type'    => 'select',
                'options' => $countries,
                'default' => 'auto',
                'css'     => 'min-width: 300px;',
            ],

            [
                'title'   => __( 'Country Source', 'phone-validator-with-flags-for-woocommerce' ),
                'desc'    => __( 'Which countries appear in the dropdown.', 'phone-validator-with-flags-for-woocommerce' ),
                'id'      => 'pvfwc_country_source',
                'type'    => 'select',
                'options' => [
                    'wc'  => __( 'WooCommerce Selling Locations', 'phone-validator-with-flags-for-woocommerce' ),
                    'all' => __( 'All Countries', 'phone-validator-with-flags-for-woocommerce' ),
                ],
                'default' => 'wc',
                'css'     => 'min-width: 300px;',
            ],

            [
                'title'             => __( 'Preferred Countries', 'phone-validator-with-flags-for-woocommerce' ),
                'desc'              => __( 'These countries will appear at the top of the dropdown list.', 'phone-validator-with-flags-for-woocommerce' ),
                'id'                => 'pvfwc_preferred_countries',
                'type'              => 'multiselect',
                'options'           => $all_countries_for_preferred,
                'default'           => [],
                'css'               => 'min-width: 350px;',
                'custom_attributes' => [
                    'data-placeholder' => __( 'Select countries...', 'phone-validator-with-flags-for-woocommerce' ),
                ],
                'class'             => 'wc-enhanced-select',
            ],

            [
                'type' => 'sectionend',
                'id'   => 'pvfwc_general_section',
            ],

            [
                'title' => __( 'Phone Fields', 'phone-validator-with-flags-for-woocommerce' ),
                'type'  => 'title',
                'id'    => 'pvfwc_fields_section',
            ],

            [
                'title'   => __( 'Billing Phone', 'phone-validator-with-flags-for-woocommerce' ),
                'desc'    => __( 'Enable on billing phone field at checkout.', 'phone-validator-with-flags-for-woocommerce' ),
                'id'      => 'pvfwc_enable_billing',
                'type'    => 'checkbox',
                'default' => 'yes',
            ],

            [
                'title'   => __( 'Shipping Phone', 'phone-validator-with-flags-for-woocommerce' ),
                'desc'    => __( 'Enable on shipping phone field at checkout (if visible).', 'phone-validator-with-flags-for-woocommerce' ),
                'id'      => 'pvfwc_enable_shipping',
                'type'    => 'checkbox',
                'default' => 'no',
            ],

            [
                'title'   => __( 'My Account Page', 'phone-validator-with-flags-for-woocommerce' ),
                'desc'    => __( 'Enable on the phone field in My Account → Address pages.', 'phone-validator-with-flags-for-woocommerce' ),
                'id'      => 'pvfwc_enable_my_account',
                'type'    => 'checkbox',
                'default' => 'yes',
            ],

            [
                'type' => 'sectionend',
                'id'   => 'pvfwc_fields_section',
            ],

            [
                'title' => __( 'Validation', 'phone-validator-with-flags-for-woocommerce' ),
                'type'  => 'title',
                'id'    => 'pvfwc_validation_section',
            ],

            [
                'title'   => __( 'Respect WooCommerce Validation', 'phone-validator-with-flags-for-woocommerce' ),
                'desc'    => __( 'Only validate if WooCommerce marks the phone field as required. If optional and left empty, no validation is triggered.', 'phone-validator-with-flags-for-woocommerce' ),
                'id'      => 'pvfwc_respect_wc_validation',
                'type'    => 'checkbox',
                'default' => 'yes',
            ],

            [
                'title'   => __( 'Error Message Style', 'phone-validator-with-flags-for-woocommerce' ),
                'desc'    => __( 'How to display the validation error to the customer.', 'phone-validator-with-flags-for-woocommerce' ),
                'id'      => 'pvfwc_error_style',
                'type'    => 'select',
                'options' => [
                    'inline' => __( 'Inline (like WooCommerce notices)', 'phone-validator-with-flags-for-woocommerce' ),
                    'alert'  => __( 'Browser Alert (legacy)', 'phone-validator-with-flags-for-woocommerce' ),
                ],
                'default' => 'inline',
                'css'     => 'min-width: 300px;',
            ],

            [
                'title'       => __( 'Custom Error Message', 'phone-validator-with-flags-for-woocommerce' ),
                'desc'        => __( 'Leave empty to use the default message.', 'phone-validator-with-flags-for-woocommerce' ),
                'id'          => 'pvfwc_error_message',
                'type'        => 'text',
                'default'     => '',
                'placeholder' => __( 'e.g. Please enter a valid phone number.', 'phone-validator-with-flags-for-woocommerce' ),
                'css'         => 'min-width: 400px;',
            ],

            [
                'type' => 'sectionend',
                'id'   => 'pvfwc_validation_section',
            ],

        ];

        return apply_filters( 'pvfwc_settings', $settings );
    }

    public function output() {
        $settings = $this->get_settings();
        WC_Admin_Settings::output_fields( $settings );
        $this->output_kofi_box();
    }

    private function output_kofi_box() {
        ?>
        <div class="pvfwc-kofi-box">
            <div class="pvfwc-kofi-inner">
                <span class="pvfwc-kofi-icon">☕</span>
                <div class="pvfwc-kofi-text">
                    <strong><?php esc_html_e( 'Enjoying the plugin?', 'phone-validator-with-flags-for-woocommerce' ); ?></strong>
                    <p><?php esc_html_e( 'If this plugin saves you time, consider supporting its development with a coffee. It keeps the updates coming!', 'phone-validator-with-flags-for-woocommerce' ); ?></p>
                </div>
                <a href="https://ko-fi.com/mokhtarbsaid" target="_blank" class="pvfwc-kofi-btn">
                    ☕ <?php esc_html_e( 'Buy me a coffee', 'phone-validator-with-flags-for-woocommerce' ); ?>
                </a>
            </div>
        </div>
        <?php
    }

    public function save() {
        $settings = $this->get_settings();
        WC_Admin_Settings::save_fields( $settings );
    }
}