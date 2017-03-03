<?php

if ( !defined( 'ABSPATH' ) )
	exit;

function xfd_tags_page() {
	if ( !current_user_can( 'administrator' ) )
		return;
	$tags = get_terms( [
		'taxonomy' => 'post_tag',
		'orderby' => 'name',
		'order' => 'ASC',
		'hide_empty' => FALSE,
	] );
	$option = get_option( 'xfd_frequent_tags' );
	if ( $option === FALSE )
		$option = [];
	else
		$option = explode( ';', $option );
	xfd_header();
	echo '<table class="form-table">' . "\n";
	echo '<tbody>' . "\n";
	/* media tags */
	xfd_tags_media_tr( 'photo_tag', __( 'Photo tag', 'xfd' ), 'xfd-photo' );
	xfd_tags_media_tr( 'audio_tag', __( 'Audio tag', 'xfd' ), 'xfd-audio' );
	xfd_tags_media_tr( 'video_tag', __( 'Video tag', 'xfd' ), 'xfd-video' );
	/* frequent tags */
	echo '<tr>' . "\n";
	echo sprintf( '<th scope="row">%s</th>', __( 'Frequent tags', 'xfd' ) ) . "\n";
	echo '<td colspan="2">' . "\n";
	echo '<div id="xfd_tag_container">' . "\n";
	foreach ( $option as $id )
		xfd_tags_frequent_p( $tags, $id );
	echo '</div>' . "\n";
	echo '<p>' . "\n";
	echo sprintf( '<button type="button" class="button" id="xfd_tag_add">%s</button>', __( 'add', 'xfd' ) ) . "\n";
	echo '<span>' . "\n";
	echo sprintf( '<button type="button" class="button button-primary" id="xfd_tag_save">%s</button>', __( 'save', 'xfd' ) ) . "\n";
	echo '<input type="hidden" class="xfd_option" id="xfd_tag_value" name="frequent_tags" value="" />' . "\n";
	xfd_input_nonce( xfd_option_nonce( 'frequent_tags' ) );
	xfd_spinner();
	echo '</span>' . "\n";
	echo '</p>' . "\n";
	echo '<div id="xfd_tag_template" style="display: none;">' . "\n";
	xfd_tags_frequent_p( $tags, 0 );
	echo '</div>' . "\n";
	echo '</td>' . "\n";
	echo '</tr>' . "\n";
	echo '</tbody>' . "\n";
	echo '</table>' . "\n";
	xfd_footer();
}

function xfd_tags_media_tr( string $key, string $label, string $alt ) {
	echo '<tr>' . "\n";
	echo sprintf( '<th scope="row"><label for="xfd_video_tag">%s</label></th>', $label ) . "\n";
	echo '<td>' . "\n";
	wp_dropdown_categories( [
		'show_option_none' => _x( 'none', 'f', 'xfd' ),
		'option_none_value' => '',
		'orderby' => 'name',
		'order' => 'ASC',
		'hide_empty' => FALSE,
		'echo' => TRUE,
		'selected' => get_option( 'xfd_' . $key ),
		'name' => $key,
		'class' => 'xfd_option',
		'taxonomy' => 'post_tag',
	] );
	xfd_input_nonce( xfd_option_nonce( $key ) );
	xfd_spinner();
	echo '</td>' . "\n";
	echo sprintf( '<td><img src="%s/%s.png" alt="%s" /></td>', get_stylesheet_directory_uri(), $alt, $alt ) . "\n";
	echo '</tr>' . "\n";
}

function xfd_tags_frequent_p( array $tags, int $id ) {
	echo '<p>' . "\n";
	echo '<select>' . "\n";
	echo sprintf( '<option value="">%s</option>', _x( 'none', 'f', 'xfd' ) ) . "\n";
	foreach ( $tags as $tag ) {
		$selected = selected( $tag->term_id, $id, FALSE );
		echo sprintf( '<option value="%d"%s>%s</option>', $tag->term_id, $selected, $tag->name ) . "\n";
	}
	echo '</select>' . "\n";
	echo sprintf( '<button type="button" class="button xfd_tag_up">%s</button>', __( 'up', 'xfd' ) ) . "\n";
	echo sprintf( '<button type="button" class="button xfd_tag_down">%s</button>', __( 'down', 'xfd' ) ) . "\n";
	echo sprintf( '<button type="button" class="button xfd_tag_delete">%s</button>', __( 'delete', 'xfd' ) ) . "\n";
	echo '</p>' . "\n";
}

add_action( 'admin_enqueue_scripts', function( $hook ) {
	if ( !current_user_can( 'administrator' ) )
		return;
	if ( $hook !== 'xfd_page_xfd-tags' )
		return;
	wp_enqueue_script( 'xfd-tags', get_stylesheet_directory_uri() . '/tags.js', ['jquery'] );
} );
