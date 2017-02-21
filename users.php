<?php

if ( !defined( 'ABSPATH' ) )
	exit;

// TODO clear meta

function xfd_users_page() {
	if ( ! current_user_can( 'administrator' ) )
		return;
	echo '<div class="wrap">' . "\n";
	echo sprintf( '<h1>%s :: %s</h1>', __( 'XFD', 'xfd' ), __( 'Users', 'xfd' ) ) . "\n";
	echo sprintf( '<p class="dashicons-before dashicons-info">%s</p>', __( 'Options are immediately saved.', 'xfd' ) ) . "\n";
	$cities_parent_page = get_option( 'xfd_cities_parent_page' );
	if ( $cities_parent_page === FALSE ) {
		echo xfd_notice( 'error', __( 'Cities parent page not set.', 'xfd' ) );
	} else {
		$cities = get_posts( [
			'post_parent' => $cities_parent_page,
			'post_type' => 'page',
			'post_status' => 'public',
			'nopaging' => TRUE,
			'order' => 'ASC',
			'orderby' => 'title',
		] );
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
			echo sprintf( '<option value="%d">%s</option>', 0, __( 'none', 'f', 'xfd' ) ) . "\n";
			$meta = intval( get_user_meta( $user->ID, 'xfd_city', TRUE ) );
			foreach ( $cities as $city )
				echo sprintf( '<option value="%d"%s>%s</option>', $city->ID, selected( $meta, $city->ID, FALSE ), $city->post_title ) . "\n";
			echo '</select>' . "\n";
			echo xfd_hidden( 'id', $user->ID ) . "\n";
			echo xfd_input_nonce( xfd_user_nonce( $user->ID, 'city' ) ) . "\n";
			echo xfd_spinner() . "\n";
			echo xfd_description( __( 'city', 'xfd' ) ) . "\n";
			echo '</td>' . "\n";
			echo '<td>' . "\n";
			xfd_user_check( $user->ID, 'male', __( 'male students', 'xfd' ) );
			xfd_user_check( $user->ID, 'female', __( 'female students', 'xfd' ) );
			echo '</td>' . "\n";
			echo '</tr>' . "\n";
		}
		echo '</tbody>' . "\n";
		echo '</table>' . "\n";
		echo $html;
	}
	echo '</div>' . "\n";
}

function xfd_user_check( int $id, string $key, string $label ) {
	echo '<p>' . "\n";
	echo '<label>' . "\n";
	$meta = get_user_meta( $id, 'xfd_' . $key, TRUE );
	$checked = checked( $meta, 'on', FALSE );
	echo sprintf( '<input class="xfd_user_meta" name="%s" type="checkbox" value="on"%s />', $key, $checked ) . "\n";
	echo sprintf( '<span>%s</span>', $label ) . "\n";
	echo xfd_hidden( 'id', $id ) . "\n";
	echo xfd_input_nonce( xfd_user_nonce( $id, $key ) ) . "\n";
	echo xfd_spinner() . "\n";
	echo '</label>' . "\n";
	echo '</p>' . "\n";
}

function xfd_user_nonce( int $id, string $key ): string {
	return sprintf( 'xfd_user_%d_%s', $id, $key );
}

add_action( 'admin_enqueue_scripts', function( $hook ) {
	if ( ! current_user_can( 'administrator' ) )
		return;
	if ( $hook !== 'xfd_page_xfd-users' )
		return;
	wp_enqueue_script( 'xfd-users', get_stylesheet_directory_uri() . '/users.js', ['jquery'] );
} );

add_action( 'wp_ajax_xfd_user_meta', function() {
	if ( ! current_user_can( 'administrator' ) )
		exit( 'role' );
	$id = intval( $_POST['id'] );
	$key = $_POST['key'];
	$action = xfd_user_nonce( $id, $key );
	if ( wp_verify_nonce( $_POST['nonce'], $action ) === FALSE )
		exit( 'nonce' );
	$value = $_POST['value'];
	if ( $value !== '' )
		update_user_meta( $id, 'xfd_' . $key, $value );
	else
		delete_user_meta( $id, 'xfd_' . $key );
	xfd_success();
} );
