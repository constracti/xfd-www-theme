jQuery( function() {

jQuery( document ).on( 'change', '.xfd_parent', function() {
	var element = jQuery( this );
	var spinner = element.siblings( '.spinner' );
	var data = {
		action: element.prop( 'name' ),
		value: element.val(),
		nonce: spinner.data( 'nonce' ),
	};
	spinner.addClass( 'is-active' );
	jQuery.post( ajaxurl, data, function( data ) {
		if ( typeof( data ) === 'object' )
			jQuery( '#xfd_categories_per_city' ).html( data.html );
		else
			alert( data );
		spinner.removeClass( 'is-active' );
	} );
} );

jQuery( document ).on( 'change', '.xfd_city_category', function() {
	var element = jQuery( this );
	var spinner = element.siblings( '.spinner' );
	var data = {
		action: element.prop( 'name' ),
		page: spinner.data( 'page' ),
		value: element.val(),
		nonce: spinner.data( 'nonce' ),
	};
	spinner.addClass( 'is-active' );
	jQuery.post( ajaxurl, data, function( data ) {
		if ( typeof( data ) === 'object' )
			;
		else
			alert( data );
		spinner.removeClass( 'is-active' );
	} );
} );

jQuery( document ).on( 'change', '.xfd_students_tag', function() {
	var element = jQuery( this );
	var spinner = element.siblings( '.spinner' );
	var data = {
		action: element.prop( 'name' ),
		value: element.val(),
		nonce: spinner.data( 'nonce' ),
	};
	spinner.addClass( 'is-active' );
	jQuery.post( ajaxurl, data, function( data ) {
		if ( typeof( data ) === 'object' )
			;
		else
			alert( data );
		spinner.removeClass( 'is-active' );
	} );
} );

} );
