<?php

if ( !defined( 'ABSPATH' ) )
	exit;

function xfd_users_page() {
	if ( !current_user_can( 'administrator' ) )
		return;
	xfd_header();
	$cities_parent_page = get_option( 'xfd_cities_parent_page' );
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
		echo '<table class="form-table">' . "\n";
		echo '<tbody>' . "\n";
		$users = get_users( [
			'role__in' => ['administrator', 'editor', 'author', 'contributor'],
		] );
		foreach ( $users as $user ) {
			echo '<tr>' . "\n";
			echo sprintf( '<th scope="row">%s</th>', $user->user_login ) . "\n";
			echo '<td>' . "\n";
			echo '<select class="xfd_user_meta" name="city">' . "\n";
			echo sprintf( '<option value="">%s</option>', _x( 'none', 'f', 'xfd' ) ) . "\n";
			$meta = intval( get_user_meta( $user->ID, 'xfd_city', TRUE ) );
			foreach ( $cities_posts as $city )
				echo sprintf( '<option value="%d"%s>%s</option>', $city->ID, selected( $meta, $city->ID, FALSE ), $city->post_title ) . "\n";
			echo '</select>' . "\n";
			xfd_hidden( 'id', $user->ID ) . "\n";
			xfd_input_nonce( xfd_user_nonce( $user->ID, 'city' ) ) . "\n";
			xfd_spinner() . "\n";
			xfd_description( __( 'city', 'xfd' ) ) . "\n";
			echo '</td>' . "\n";
			echo '<td>' . "\n";
			xfd_users_students_p( $user->ID, 'male', __( 'male students', 'xfd' ) );
			xfd_users_students_p( $user->ID, 'female', __( 'female students', 'xfd' ) );
			echo '</td>' . "\n";
			echo '</tr>' . "\n";
		}
		echo '</tbody>' . "\n";
		echo '</table>' . "\n";
	}
	xfd_footer();
}

function xfd_users_students_p( int $id, string $key, string $label ) {
	echo '<p>' . "\n";
	echo '<label>' . "\n";
	$meta = get_user_meta( $id, 'xfd_' . $key, TRUE );
	$checked = checked( $meta, 'on', FALSE );
	echo sprintf( '<input class="xfd_user_meta" name="%s" type="checkbox" value="on"%s />', $key, $checked ) . "\n";
	echo sprintf( '<span>%s</span>', $label ) . "\n";
	xfd_hidden( 'id', $id ) . "\n";
	xfd_input_nonce( xfd_user_nonce( $id, $key ) ) . "\n";
	xfd_spinner() . "\n";
	echo '</label>' . "\n";
	echo '</p>' . "\n";
}
