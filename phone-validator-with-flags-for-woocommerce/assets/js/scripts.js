(function () {

    function buildCountryOrder( baseCountries ) {
        const preferred       = PVFWC_DATA.preferredCountries || [];
        const preferredInList = preferred.filter( c => baseCountries.includes( c ) );
        const rest            = baseCountries.filter( c => ! preferredInList.includes( c ) );

        if ( preferredInList.length ) {
            return [ ...preferredInList, 'divider', ...rest ];
        }
        return baseCountries;
    }

    function initPhoneField( input, countries ) {
        if ( ! input || input.dataset.pvfwcInit ) return;
        if ( typeof window.intlTelInput === 'undefined' ) return;

        input.dataset.pvfwcInit = 'true';

        const baseCountries = countries || PVFWC_DATA.allowedCountries || [];
        const countryOrder  = buildCountryOrder( baseCountries );

        const iti = window.intlTelInput( input, {
            initialCountry: PVFWC_DATA.defaultCountry || 'us',
            onlyCountries:  baseCountries,
            countryOrder:   countryOrder,
            nationalMode:   false,
            imagePath:      PVFWC_DATA.imgPath,
        } );

        input.closest( 'form' )?.addEventListener( 'submit', function ( e ) {
            const isEmpty = input.value.trim() === '';

            if ( PVFWC_DATA.respectWcValidation && isEmpty ) return;

            if ( ! isEmpty && ! iti.isValidNumber() ) {
                e.preventDefault();
                e.stopImmediatePropagation();
                showError( input, PVFWC_DATA.errorMessage );
                input.focus();
                return false;
            }

            if ( ! isEmpty ) {
                input.value = iti.getNumber();
            }
        }, true );
    }

    function showError( input, message ) {
        const existing = document.querySelector( '.pvfwc-error-msg' );
        if ( existing ) existing.remove();

        if ( PVFWC_DATA.errorStyle === 'alert' ) {
            alert( message );
            return;
        }

        const error = document.createElement( 'div' );
        error.className = 'pvfwc-error-msg woocommerce-error';
        error.setAttribute( 'role', 'alert' );
        error.innerHTML = '<ul><li>' + message + '</li></ul>';

        const wrapper = input.closest( '.form-row' ) || input.parentElement;
        wrapper?.insertAdjacentElement( 'afterend', error );

        error.scrollIntoView( { behavior: 'smooth', block: 'center' } );
    }

    function initAll() {
        if ( PVFWC_DATA.enableBilling ) {
            initPhoneField( document.querySelector( '#billing_phone' ) );
        }

        if ( PVFWC_DATA.enableShipping ) {
            initPhoneField( document.querySelector( '#shipping_phone' ), PVFWC_DATA.shippingCountries );
        }

        if ( PVFWC_DATA.enableMyAccount ) {
            initPhoneField( document.querySelector( '#billing_phone' ) );
        }
    }

    document.addEventListener( 'DOMContentLoaded', function () {
        initAll();

        document.body.addEventListener( 'updated_checkout', initAll );
        document.body.addEventListener( 'country_to_state_changed', initAll );
    } );

} )();