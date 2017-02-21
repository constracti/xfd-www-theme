<?php

if ( !defined( 'ABSPATH' ) )
	exit;

// TODO clear meta

function xfd_categories_page() {
	if ( ! current_user_can( 'administrator' ) )
		return;
	echo '<div class="wrap">' . "\n";
	echo sprintf( '<h1>%s :: %s</h1>', __( 'XFD', 'xfd' ), __( 'Categories', 'xfd' ) ) . "\n";
	echo sprintf( '<p class="dashicons-before dashicons-info">%s</p>', __( 'Options are immediately saved.', 'xfd' ) ) . "\n";
	$cities_parent_page = get_option( 'xfd_cities_parent_page' );
	$notices_parent_category = get_option( 'xfd_notices_parent_category' );
	$reports_parent_category = get_option( 'xfd_reports_parent_category' );
	if ( $cities_parent_page === FALSE )
		echo xfd_notice( 'error', __( 'Cities parent page not set.', 'xfd' ) );
	if ( $notices_parent_category === FALSE )
		echo xfd_notice( 'error', __( 'Notices parent category not set.', 'xfd' ) );
	if ( $reports_parent_category === FALSE )
		echo xfd_notice( 'error', __( 'Reports parent category not set.', 'xfd' ) );
	if ( $cities_parent_page !== FALSE && $notices_parent_category !== FALSE && $reports_parent_category !== FALSE ) {
		$cities_posts = get_posts( [
			'post_parent' => $cities_parent_page,
			'post_type' => 'page',
			'post_status' => 'public',
			'nopaging' => TRUE,
			'order' => 'ASC',
			'orderby' => 'title',
		] );
		$notices_cats = get_terms( [
			'taxonomy' => 'category',
			'hide_empty' => FALSE,
			'parent' => $notices_parent_category,
		] );
		$reports_cats = get_terms( [
			'taxonomy' => 'category',
			'hide_empty' => FALSE,
			'parent' => $reports_parent_category,
		] );
		echo '<table class="form-table">' . "\n";
		echo '<tbody>' . "\n";
		foreach ( $cities_posts as $post ) {
			echo '<tr>' . "\n";
			echo sprintf( '<th scope="row">%s</th>', $post->post_title ) . "\n";
			xfd_city_category_td( $post->ID, 'city_notices_category', $notices_cats, __( 'notices category', 'xfd' ) );
			xfd_city_category_td( $post->ID, 'city_reports_category', $reports_cats, __( 'reports category', 'xfd' ) );
			echo '</tr>' . "\n";
		}
		echo '</tbody>' . "\n";
		echo '</table>' . "\n";
	}
	echo '</div>' . "\n";
}

function xfd_city_category_td( int $id, string $key, array $cats, string $description ) {
	echo '<td>' . "\n";
	echo sprintf( '<select class="xfd_post_meta" name="%s">', $key ) . "\n";
	echo sprintf( '<option value="%d">%s</option>', 0, __( 'none', 'f', 'xfd' ) ) . "\n";
	$meta = intval( get_post_meta( $id, 'xfd_' . $key, TRUE ) );
	foreach ( $cats as $cat )
		echo sprintf( '<option value="%d"%s>%s</option>', $cat->term_id, selected( $meta, $cat->term_id, FALSE ), $cat->name ) . "\n";
	echo '</select>' . "\n";
	echo xfd_hidden( 'id', $id ) . "\n";
	echo xfd_input_nonce( xfd_post_nonce( $id, $key ) ) . "\n";
	echo xfd_spinner() . "\n";
	echo xfd_description( $description ) . "\n";
	echo '</td>' . "\n";
}

function xfd_post_nonce( int $id, string $key ): string {
	return sprintf( 'xfd_post_%d_%s', $id, $key );
}

add_action( 'admin_enqueue_scripts', function( $hook ) {
	if ( ! current_user_can( 'administrator' ) )
		return;
	if ( $hook !== 'xfd_page_xfd-categories' )
		return;
	wp_enqueue_script( 'xfd-categories', get_stylesheet_directory_uri() . '/categories.js', ['jquery'] );
} );

add_action( 'wp_ajax_xfd_post_meta', function() {
	if ( ! current_user_can( 'administrator' ) )
		exit( 'role' );
	$id = intval( $_POST['id'] );
	$key = $_POST['key'];
	$action = xfd_post_nonce( $id, $key );
	if ( wp_verify_nonce( $_POST['nonce'], $action ) === FALSE )
		exit( 'nonce' );
	$value = $_POST['value'];
	if ( $value !== '' )
		update_post_meta( $id, 'xfd_' . $key, $value );
	else
		delete_post_meta( $id, 'xfd_' . $key );
	xfd_success();
} );
