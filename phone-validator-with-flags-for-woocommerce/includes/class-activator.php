<?php
// If accessed directly, deny access.
defined('ABSPATH') || exit;

class Phone_Validator_Flags_Activator {
    public static function activate() {

        if ( ! get_option( 'phone_validator_flags_activation_time' ) ) {
            update_option( 'phone_validator_flags_activation_time', time() );
        }
        update_option( 'phone_validator_flags_updated_time', time() );
        delete_option( 'phone_validator_flags_kofi_dismissed' );
    }
}