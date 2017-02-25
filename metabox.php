<?php

if ( !defined( 'ABSPATH' ) )
	exit;

add_action( 'add_meta_boxes_post', function() {
	// TODO permissions
	// TODO post saved values
	// TODO hide metaboxes
	// TODO script init and change
	// TODO radio buttons remove name property
	if ( !current_user_can( 'administrator' ) )
		return;
	$user_id = get_current_user_id();
	if ( get_user_meta( $user_id, 'xfd_city', TRUE ) === '' )
		return;
	add_meta_box( 'xfddiv', __( 'XFD', 'xfd' ), function( $post ) {
		$user_id = get_current_user_id();
		$city = get_post( get_user_meta( $user_id, 'xfd_city', TRUE ) );
		echo sprintf( '<p><strong>%s</strong></p>', $city->post_title ) . "\n";
		xfd_metabox_category_p( $city->ID, 'notices' );
		xfd_metabox_category_p( $city->ID, 'reports' );
		echo '<hr />' . "\n";
		xfd_metabox_students_p( $user_id, 'male' );
		xfd_metabox_students_p( $user_id, 'female' );
		echo '<hr />' . "\n";
		echo '<p><small>under development</small></p>' . "\n";
	}, 'post', 'side' );
} );


function xfd_metabox_category_p( int $post_id, string $key ) {
	$option_key = sprintf( 'xfd_%s_parent_category', $key );
	$tag_id = get_option( $option_key );
	$tag = get_tag( $tag_id );
	$post_meta_key = sprintf( 'xfd_city_%s_category', $key );
	$cat_id = get_post_meta( $post_id, $post_meta_key, TRUE );
	$cat = get_tag( $cat_id );
	echo '<p>' . "\n";
	echo '<label>' . "\n";
	$checked = checked( $key, 'notices', FALSE );
	echo sprintf( '<input type="radio" name="xfd_category_radio" value="%d"%s />', $cat->term_id, $checked ) . "\n";
	echo sprintf( '<span>%s</span>', $tag->name ) . "\n";
	echo '</label>' . "\n";
	echo '</p>' . "\n";
}

function xfd_metabox_students_p( int $user_id, string $key ) {
	$option_key = sprintf( 'xfd_students_%s_tag', $key );
	$tag_id = get_option( $option_key );
	$tag = get_tag( $tag_id );
	$user_meta_key = sprintf( 'xfd_%s', $key );
	$user_meta = get_user_meta( $user_id, $user_meta_key, TRUE );
	$checked = checked( $user_meta, 'on', FALSE );
	echo '<p>' . "\n";
	echo '<label>' . "\n";
	echo sprintf( '<input type="checkbox" name="xfd_students_checkbox" value="%d"%s />', $tag->term_id, $checked ) . "\n";
	echo sprintf( '<span>%s</span>', $tag->name ) . "\n";
	echo '</label>' . "\n";
	echo '</p>' . "\n";
}

add_action( 'admin_enqueue_scripts', function( $hook ) {
	// TODO permissions
	if ( !current_user_can( 'administrator' ) )
		return;
	$user_id = get_current_user_id();
	if ( get_user_meta( $user_id, 'xfd_city', TRUE ) === '' )
		return;
	if ( !in_array( $hook, ['post.php', 'post-new.php'] ) )
		return;
	wp_enqueue_script( 'xfd-metabox', get_stylesheet_directory_uri() . '/metabox.js', ['jquery'] );
} );
