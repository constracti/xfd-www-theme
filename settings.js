jQuery( function() {

jQuery( '.xfd_parent' ).change( function() {
	var element = jQuery( this );
	var spinner = element.siblings( '.spinner' );
	var data = {
		action: element.prop( 'name' ),
		value: element.val(),
		nonce: spinner.data( 'nonce' ),
	};
	spinner.addClass( 'is-active' );
	jQuery.post( ajaxurl, data, function( data ) {
		if ( typeof( data ) !== 'object' )
			alert( data );
		spinner.removeClass( 'is-active' );
	} );
} );

jQuery( '.xfd_students_tag' ).change( function() {
	var element = jQuery( this );
	var spinner = element.siblings( '.spinner' );
	var data = {
		action: element.prop( 'name' ),
		value: element.val(),
		nonce: spinner.data( 'nonce' ),
	};
	spinner.addClass( 'is-active' );
	jQuery.post( ajaxurl, data, function( data ) {
		if ( typeof( data ) !== 'object' )
			alert( data );
		spinner.removeClass( 'is-active' );
	} );
} );

} );
