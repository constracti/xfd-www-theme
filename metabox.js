jQuery( function() {

/* category */
var category_radios = jQuery( '.xfd_category_radio' );
category_radios.each( function() {
	var id = jQuery( this ).val();
	var checked = jQuery( '#in-category-' + id ).prop( 'checked' );
	jQuery( this ).prop( 'checked', checked );
} ).change( function() {
	var selected = jQuery( this ).val();
	category_radios.each( function() {
		var id = jQuery( this ).val();
		var checked = (id === selected );
		jQuery( this ).prop( 'checked', checked )
		jQuery( '#in-category-' + id ).prop( 'checked', checked );
		jQuery( '#in-popular-category-' + id ).prop( 'checked', checked );
	} );
} );
jQuery( '#category-pop, #category-all' ).find( 'input[type="checkbox"]' ).change( function() {
	var value = jQuery( this ).val();
	var checked = jQuery( this ).prop( 'checked' );
	category_radios.filter( function() {
		return jQuery( this ).val() === value;
	} ).prop( 'checked',  checked );
} );

/* tags */
var tag_checkboxes = jQuery( '.xfd_tag_checkbox' );
var tag_div = jQuery( '#post_tag' );
tag_checkboxes.change( function() {
	var name = jQuery( this ).val();
	var checked = jQuery( this ).prop( 'checked' );
	if ( checked ) {
		tagBox.userAction = 'add';
		tagBox.flushTags( tag_div, '<span>' + name + '</span>', true );
	} else {
		var button = tag_div.find( '.tagchecklist' ).find( '.ntdelbutton' ).filter( function() {
			return jQuery( this ).find( '.screen-reader-text' ).html().split( ': ' )[1] === name;
		} ).get( 0 );
		tagBox.userAction = 'remove';
		tagBox.parseTags( button );
	}
} );
tagBox.quickClicksOld = tagBox.quickClicks;
tagBox.quickClicks = function( el ) {
	this.quickClicksOld( el );
	tag_checkboxes.each( function() {
		var name = jQuery( this ).val();
		var checked = tag_div.find( '.the-tags' ).val().indexOf( name ) !== -1;
		jQuery( this ).prop( 'checked', checked );
	} );
};

/* initialize */
var xfd_screen_action = jQuery ( '#xfd_screen_action' ).val();
if ( xfd_screen_action !== '' ) {
	category_radios.first().prop( 'checked', true ).change();
	tag_checkboxes.filter( '.xfd_tag_checkbox_default' ).prop( 'checked', true ).change();
}

} );
