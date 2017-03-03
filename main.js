function xfd_ajax( element, action ) {
	var data = {
		action: 'xfd_' + action,
	};
	if ( element.prop( 'type' ) !== 'button' ) {
		data.key = element.prop( 'name' );
		if ( element.prop( 'type' ) === 'checkbox' && !element.prop( 'checked' ) )
			data.value = '';
		else
			data.value = element.val();
	}
	element.siblings( 'input[type="hidden"]' ).each( function() {
		var hidden = jQuery( this );
		data[ hidden.prop( 'name' ) ] = hidden.val();
	} );
	var spinner = element.siblings( '.spinner' );
	if ( spinner.hasClass( 'is-active' ) )
		return;
	spinner.addClass( 'is-active' );
	jQuery.post( ajaxurl, data ).done( function( data, textStatus, jqXHR ) {
		if ( typeof( data ) !== 'object' )
			alert( data );
	} ).fail( function( jqXHR, textStatus, errorThrown ) {
		alert( errorThrown );
	} ).always( function() {
		spinner.removeClass( 'is-active' );
	} );
}

jQuery( function() {

jQuery( '.xfd_option' ).change( function() {
	xfd_ajax( jQuery( this ), 'option' );
} );

jQuery( '.xfd_post_meta' ).change( function() {
	xfd_ajax( jQuery( this ), 'post_meta' );
} );

jQuery( '.xfd_user_meta' ).change( function() {
	xfd_ajax( jQuery( this ), 'user_meta' );
} );

jQuery( '.xfd_button' ).click( function() {
	var button = jQuery( this );
	if ( button.data( 'confirm' ) !== undefined )
		if ( !confirm( button.data( 'confirm' ) ) )
			return;
	xfd_ajax( button, button.prop( 'name' ) );
} );

} );
