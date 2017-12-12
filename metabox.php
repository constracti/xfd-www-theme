<?php

if ( !defined( 'ABSPATH' ) )
	exit;

add_action( 'add_meta_boxes_post', function() {
	if ( !current_user_can( 'edit_posts' ) )
		return;
	$user_id = get_current_user_id();
	if ( get_user_meta( $user_id, 'xfd_city', TRUE ) === '' )
		return;
	add_meta_box( 'xfddiv', __( 'XFD', 'xfd' ), function( $post ) {
		$user_id = get_current_user_id();
		$city = get_post( get_user_meta( $user_id, 'xfd_city', TRUE ) );
		echo sprintf( '<input type="hidden" id="xfd_screen_action" value="%s" />', get_current_screen()->action ) . "\n";
		echo sprintf( '<div class="xfd_metabox_city_name"><strong>%s</strong></div>', $city->post_title ) . "\n";
		xfd_metabox_category_div( $city->ID, 'notices' );
		xfd_metabox_category_div( $city->ID, 'reports' );
		echo '<hr />' . "\n";
		xfd_metabox_students_div( $user_id, 'male' );
		xfd_metabox_students_div( $user_id, 'female' );
		echo '<hr />' . "\n";
		foreach ( ['photo', 'audio', 'video'] as $option ) {
			$tag = get_option( sprintf( 'xfd_%s_tag', $option ) );
			if ( $tag !== FALSE )
				xfd_metabox_media_tag_div( $tag, $option );
		}
		$tags = get_option( 'xfd_frequent_tags' );
		if ( $tags === FALSE )
			$tags = [];
		else
			$tags = explode( ';', $tags );
		if ( !empty( $tags ) ) {
			echo '<hr />' . "\n";
			foreach ( $tags as $tag )
				xfd_metabox_frequent_tag_div( $tag );
		}
	}, 'post', 'side' );
} );


function xfd_metabox_category_div( int $post_id, string $key ) {
	$option_key = sprintf( 'xfd_%s_parent_category', $key );
	$term_id = get_option( $option_key );
	$term = get_term( $term_id );
	$post_meta_key = sprintf( 'xfd_city_%s_category', $key );
	$cat_id = get_post_meta( $post_id, $post_meta_key, TRUE );
	$cat = get_category( $cat_id );
	echo '<div class="xfd_metabox_category_div">' . "\n";
	echo '<label>' . "\n";
	echo sprintf( '<input type="radio" class="xfd_category_radio" value="%d" />', $cat->term_id ) . "\n";
	echo sprintf( '<span>%s</span>', $term->name ) . "\n";
	echo '</label>' . "\n";
	echo '</div>' . "\n";
}

function xfd_metabox_students_div( int $user_id, string $key ) {
	$option_key = sprintf( 'xfd_students_%s_tag', $key );
	$tag_id = get_option( $option_key );
	$tag = get_tag( $tag_id );
	$user_meta_key = sprintf( 'xfd_%s', $key );
	$user_meta = get_user_meta( $user_id, $user_meta_key, TRUE );
	$class = ['xfd_tag_checkbox'];
	if ( $user_meta === 'on' )
		$class[] = 'xfd_tag_checkbox_default';
	echo '<div class="xfd_metabox_students_tag_div">' . "\n";
	echo '<label>' . "\n";
	echo sprintf( '<input type="checkbox" class="%s" value="%s" />', implode( ' ', $class ), $tag->name ) . "\n";
	echo sprintf( '<span>%s</span>', $tag->name ) . "\n";
	echo '</label>' . "\n";
	echo '</div>' . "\n";
}

function xfd_metabox_media_tag_div( int $id, string $alt ) {
	$tag = get_tag( $id );
	echo '<div class="xfd_metabox_media_tag_div">' . "\n";
	echo '<label>' . "\n";
	echo sprintf( '<input type="checkbox" class="xfd_tag_checkbox" value="%s" />', $tag->name ) . "\n";
	echo sprintf( '<img src="%s/xfd-%s-16.png" alt="xfd-%s" />', get_stylesheet_directory_uri(), $alt, $alt ) . "\n";
	echo '</label>' . "\n";
	echo '</div>' . "\n";
}

function xfd_metabox_frequent_tag_div( int $id ) {
	$tag = get_tag( $id );
	echo '<div class="xfd_metabox_frequent_tag_div">' . "\n";
	echo '<label>' . "\n";
	echo sprintf( '<input type="checkbox" class="xfd_tag_checkbox" value="%s" />', $tag->name ) . "\n";
	echo sprintf( '<span>%s</span>', $tag->name ) . "\n";
	echo '</label>' . "\n";
	echo '</div>' . "\n";
}

add_action( 'admin_enqueue_scripts', function( $hook ) {
	if ( !current_user_can( 'edit_posts' ) )
		return;
	$user_id = get_current_user_id();
	if ( get_user_meta( $user_id, 'xfd_city', TRUE ) === '' )
		return;
	if ( !in_array( $hook, ['post.php', 'post-new.php'] ) )
		return;
	wp_enqueue_style( 'xfd-metabox', get_stylesheet_directory_uri() . '/metabox.css' );
	wp_enqueue_script( 'xfd-metabox', get_stylesheet_directory_uri() . '/metabox.js', ['jquery'] );
} );
