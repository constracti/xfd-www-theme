function xfd_ajax( element, action ) {
	var data = {
		action: 'xfd_' + action,
		key: element.prop( 'name' ),
		value: ( element.prop( 'type' ) !== 'checkbox' || element.prop( 'checked' ) ) ? element.val() : '',
	};
	element.siblings( 'input[type="hidden"]' ).each( function() {
		var hidden = jQuery( this );
		data[ hidden.prop( 'name' ) ] = hidden.val();
	} );
	var spinner = element.siblings( '.spinner' );
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

xfd_category_radios = jQuery( 'input[name="xfd_category_radio"]' );

xfd_category_radios.change( function() {
	var cb = jQuery( this );
	if ( !cb.prop( 'checked' ) )
		return;
	xfd_category_radios.each( function() {
		var id = jQuery( this ).val();
		var checked = jQuery( this ).prop( 'checked' )
		var cb = jQuery( '#in-category-' + id );
		cb.prop( 'checked', checked );
		var cb = jQuery( '#in-popular-category-' + id );
		cb.prop( 'checked', checked );
	} );
} ).change();

} );
