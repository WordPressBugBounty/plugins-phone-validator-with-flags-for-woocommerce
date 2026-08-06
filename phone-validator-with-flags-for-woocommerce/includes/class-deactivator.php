<?php
// If accessed directly, deny access.
defined('ABSPATH') || exit;

class Phone_Validator_Flags_Deactivator {
    public static function deactivate() {
        if (get_option('phone_validator_flags_activation_time')) {
            delete_option( 'phone_validator_flags_activation_time' );
        }
        
    }
}
