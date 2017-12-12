<?php

if ( !defined( 'ABSPATH' ) )
	exit;

function xfd_categories_page() {
	if ( !current_user_can( 'administrator' ) )
		return;
	xfd_header();
	$cities_parent_page = get_option( 'xfd_cities_parent_page' );
	$notices_parent_category = get_option( 'xfd_notices_parent_category' );
	$reports_parent_category = get_option( 'xfd_reports_parent_category' );
	if ( $cities_parent_page === FALSE )
		xfd_notice( 'error', __( 'Cities parent page not set.', 'xfd' ) );
	else {
		$cities_posts = get_posts( [
			'post_parent' => $cities_parent_page,
			'post_type' => 'page',
			'post_status' => 'publish',
			'nopaging' => TRUE,
			'order' => 'ASC',
			'orderby' => 'title',
		] );
		if ( empty( $cities_posts ) )
			xfd_notice( 'warning', __( 'No cities posts found.', 'xfd' ) );
	}
	if ( $notices_parent_category === FALSE )
		xfd_notice( 'error', __( 'Notices parent category not set.', 'xfd' ) );
	else {
		$notices_cats = get_terms( [
			'taxonomy' => 'category',
			'hide_empty' => FALSE,
			'parent' => $notices_parent_category,
		] );
		if ( empty( $notices_cats ) )
			xfd_notice( 'warning', __( 'No notices categories found.', 'xfd' ) );
	}
	if ( $reports_parent_category === FALSE )
		xfd_notice( 'error', __( 'Reports parent category not set.', 'xfd' ) );
	else {
		$reports_cats = get_terms( [
			'taxonomy' => 'category',
			'hide_empty' => FALSE,
			'parent' => $reports_parent_category,
		] );
		if ( empty( $reports_cats ) )
			xfd_notice( 'warning', __( 'No reports categories found.', 'xfd' ) );
	}
	if ( $cities_parent_page !== FALSE && $notices_parent_category !== FALSE && $reports_parent_category !== FALSE ) {
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
	xfd_footer();
}

function xfd_city_category_td( int $id, string $key, array $cats, string $description ) {
	echo '<td>' . "\n";
	echo sprintf( '<select class="xfd_post_meta" name="%s">', $key ) . "\n";
	echo sprintf( '<option value="">%s</option>', _x( 'none', 'f', 'xfd' ) ) . "\n";
	$meta = intval( get_post_meta( $id, 'xfd_' . $key, TRUE ) );
	foreach ( $cats as $cat )
		echo sprintf( '<option value="%d"%s>%s</option>', $cat->term_id, selected( $meta, $cat->term_id, FALSE ), $cat->name ) . "\n";
	echo '</select>' . "\n";
	xfd_hidden( 'id', $id ) . "\n";
	xfd_input_nonce( xfd_post_nonce( $id, $key ) ) . "\n";
	xfd_spinner() . "\n";
	xfd_description( $description ) . "\n";
	echo '</td>' . "\n";
}
