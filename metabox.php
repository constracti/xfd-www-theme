<?php

if ( !defined( 'ABSPATH' ) )
	exit;

add_action( 'add_meta_boxes_post', function() {
	// TODO permissions
	// TODO hide metaboxes
	if ( !current_user_can( 'administrator' ) )
		return;
	$user_id = get_current_user_id();
	if ( get_user_meta( $user_id, 'xfd_city', TRUE ) === '' )
		return;
	add_meta_box( 'xfddiv', __( 'XFD', 'xfd' ), function( $post ) {
		$user_id = get_current_user_id();
		$city = get_post( get_user_meta( $user_id, 'xfd_city', TRUE ) );
		echo sprintf( '<input type="hidden" id="xfd_screen_action" value="%s" />', get_current_screen()->action ) . "\n";
		echo sprintf( '<p><strong>%s</strong></p>', $city->post_title ) . "\n";
		xfd_metabox_category_p( $city->ID, 'notices' );
		xfd_metabox_category_p( $city->ID, 'reports' );
		echo '<hr />' . "\n";
		xfd_metabox_students_p( $user_id, 'male' );
		xfd_metabox_students_p( $user_id, 'female' );
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
	echo sprintf( '<input type="radio" class="xfd_category_radio" value="%d" />', $cat->term_id ) . "\n";
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
	$class = ['xfd_tag_checkbox'];
	if ( $user_meta === 'on' )
		$class[] = 'xfd_tag_checkbox_default';
	echo '<p>' . "\n";
	echo '<label>' . "\n";
	echo sprintf( '<input type="checkbox" class="%s" value="%s" />', implode( ' ', $class ), $tag->name ) . "\n";
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
