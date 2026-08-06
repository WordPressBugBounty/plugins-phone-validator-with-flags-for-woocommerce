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
        error.className = 'pvfwc-error-msg wc-block-components-notice-banner is-error';
        error.setAttribute( 'role', 'alert' );
        error.innerHTML = '<span>' + message + '</span>';

        input.closest( '.wc-block-components-text-input' )
            ?.insertAdjacentElement( 'afterend', error );
    }

    function observeFields() {
        const billingSelectors  = [];
        const shippingSelectors = [];
        const accountSelectors  = [];

        if ( PVFWC_DATA.enableBilling )   billingSelectors.push( '#billing-phone', '#billing_phone' );
        if ( PVFWC_DATA.enableShipping )  shippingSelectors.push( '#shipping-phone', '#shipping_phone' );
        if ( PVFWC_DATA.enableMyAccount ) accountSelectors.push( '#address-phone' );

        const allSelectors = [ ...billingSelectors, ...shippingSelectors, ...accountSelectors ];
        if ( allSelectors.length === 0 ) return;

        function tryInit() {
            billingSelectors.forEach( function ( selector ) {
                const input = document.querySelector( selector );
                if ( input ) initPhoneField( input );
            } );

            shippingSelectors.forEach( function ( selector ) {
                const input = document.querySelector( selector );
                if ( input ) initPhoneField( input, PVFWC_DATA.shippingCountries );
            } );

            accountSelectors.forEach( function ( selector ) {
                const input = document.querySelector( selector );
                if ( input ) initPhoneField( input );
            } );
        }

        const observer = new MutationObserver( tryInit );
        observer.observe( document.body, { childList: true, subtree: true } );

        tryInit();
    }

    if ( document.readyState === 'loading' ) {
        document.addEventListener( 'DOMContentLoaded', function () {
            observeFields();
        } );
    } else {
        observeFields();
    }

} )();