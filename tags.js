jQuery( function() {

jQuery( '#xfd_tag_add' ).click( function() {
	jQuery( '#xfd_tag_template' ).children( 'p' ).clone( true ).appendTo( jQuery( '#xfd_tag_container' ) ).children( 'select' ).focus();
} );

jQuery( '.xfd_tag_delete' ).click( function() {
	jQuery( this ).parent( 'p' ).remove();
} );

jQuery( '.xfd_tag_up' ).click( function() {
	var p = jQuery( this ).parent( 'p' );
	var target = p.prev();
	if ( target.length === 0 )
		return;
	p.detach().insertBefore( target );
} );

jQuery( '.xfd_tag_down' ).click( function() {
	var p = jQuery( this ).parent( 'p' );
	var target = p.next();
	if ( target.length === 0 )
		return;
	p.detach().insertAfter( target );
} );

jQuery( '#xfd_tag_save' ).click( function() {
	var tags = [];
	jQuery( '#xfd_tag_container' ).children( 'p' ).each( function() {
		var value = jQuery( this ).children( 'select' ).val();
		if ( value === '' || tags.indexOf( value ) !== -1 )
			jQuery( this ).remove();
		else
			tags.push( value );
	} );
	jQuery( '#xfd_tag_value' ).val( tags.join( ';' ) ).change();
} );

} );
