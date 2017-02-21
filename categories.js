jQuery( function() {

jQuery( '.xfd_post_meta' ).change( function() {
	var element = jQuery( this );
	var data = {
		action: 'xfd_post_meta',
		key: element.prop( 'name' ),
		value: ( element.prop( 'type' ) !== 'checkbox' || element.prop( 'checked' ) ) ? element.val() : '',
	};
	element.siblings( 'input[type="hidden"]' ).each( function() {
		var hidden = jQuery( this );
		data[ hidden.prop( 'name' ) ] = hidden.val();
	} );
	var spinner = element.siblings( '.spinner' );
	spinner.addClass( 'is-active' );
	jQuery.post( ajaxurl, data, function( data ) {
		if ( typeof( data ) !== 'object' )
			alert( data );
		spinner.removeClass( 'is-active' );
	} );
} );

} );
