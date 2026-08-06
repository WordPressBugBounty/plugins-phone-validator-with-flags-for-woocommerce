<?php
// If accessed directly, deny access.
defined('ABSPATH') || exit;


function pvfwc_review_notice() {
    $activation_time  = get_option( 'phone_validator_flags_activation_time', 0 );
    $last_notice_time = get_option( 'phone_validator_flags_last_notice_time', 0 );

    if ( ! $activation_time || ( time() - $activation_time ) < ( 7 * DAY_IN_SECONDS ) ) {
        return;
    }

    if ( $last_notice_time && ( time() - $last_notice_time ) < ( 30 * DAY_IN_SECONDS ) ) {
        return;
    }

    ?>
    <div class="notice notice-info is-dismissible">
        <p>
            <?php esc_html_e( '🥰 You have used:', 'phone-validator-with-flags-for-woocommerce' ); ?>
            <strong><?php esc_html_e( 'Phone Validator with Flags for WooCommerce', 'phone-validator-with-flags-for-woocommerce' ); ?></strong>
            <?php esc_html_e( 'for over 7 days! If you love the plugin, please leave a', 'phone-validator-with-flags-for-woocommerce' ); ?>
            <a href="<?php echo esc_url( 'https://wordpress.org/support/plugin/phone-validator-with-flags-for-woocommerce/reviews/#new-post' ); ?>" target="_blank">
                <?php esc_html_e( 'positive review', 'phone-validator-with-flags-for-woocommerce' ); ?>
            </a>
            <?php esc_html_e( 'to help us improve. Thank you! ❤️', 'phone-validator-with-flags-for-woocommerce' ); ?>
        </p>
    </div>
    <?php

    update_option( 'phone_validator_flags_last_notice_time', time() );
}
add_action( 'admin_notices', 'pvfwc_review_notice' );

function pvfwc_kofi_notice() {
    if ( get_option( 'phone_validator_flags_kofi_dismissed' ) ) return;

    $updated_time = get_option( 'phone_validator_flags_updated_time', 0 );

    if ( ! $updated_time || ( time() - $updated_time ) < ( 7 * DAY_IN_SECONDS ) ) {
        return;
    }

    ?>
    <div class="notice notice-success is-dismissible pvfwc-kofi-notice" id="pvfwc-kofi-notice">
        <p>
            ☕ <strong><?php esc_html_e( 'Phone Validator with Flags for WooCommerce', 'phone-validator-with-flags-for-woocommerce' ); ?></strong>
            <?php esc_html_e( '— If this plugin is useful to you, consider supporting its development!', 'phone-validator-with-flags-for-woocommerce' ); ?>
        </p>
        <p>
            <a href="https://ko-fi.com/mokhtarbsaid" target="_blank" class="pvfwc-kofi-btn">
                ☕ <?php esc_html_e( 'Buy me a coffee on Ko-fi', 'phone-validator-with-flags-for-woocommerce' ); ?>
            </a>
            &nbsp;
            <a href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'pvfwc_dismiss_kofi', '1' ), 'pvfwc_dismiss_kofi' ) ); ?>" class="pvfwc-kofi-dismiss">
                <?php esc_html_e( 'No thanks', 'phone-validator-with-flags-for-woocommerce' ); ?>
            </a>
        </p>
    </div>
    <?php
}
add_action( 'admin_notices', 'pvfwc_kofi_notice' );


function pvfwc_handle_kofi_dismiss() {
    if ( ! isset( $_GET['pvfwc_dismiss_kofi'] ) ) return;
    if ( ! wp_verify_nonce( $_GET['_wpnonce'] ?? '', 'pvfwc_dismiss_kofi' ) ) return;
    if ( ! current_user_can( 'manage_options' ) ) return;

    update_option( 'phone_validator_flags_kofi_dismissed', true );

    $redirect = remove_query_arg( [ 'pvfwc_dismiss_kofi', '_wpnonce' ] );
    wp_safe_redirect( $redirect );
    exit;
}
add_action( 'admin_init', 'pvfwc_handle_kofi_dismiss' );


function pvfwc_enqueue_admin_styles( $hook ) {
    wp_enqueue_style(
        'pvfwc-admin-css',
        PVFWC_URL . 'assets/css/admin.css',
        [],
        PVFWC_VERSION
    );
}
add_action( 'admin_enqueue_scripts', 'pvfwc_enqueue_admin_styles' );