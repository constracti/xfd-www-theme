<?php

if ( !defined( 'ABSPATH' ) )
	exit;

function xfd_settings_page() {
	if ( !current_user_can( 'administrator' ) )
		return;
	xfd_header();
	echo '<table class="form-table">' . "\n";
	echo '<tbody>' . "\n";
	/* default author */
	echo '<tr>' . "\n";
	echo '' . "\n";
	echo sprintf( '<th scope="row"><label for="xfd_default_author">%s</label></th>', __( 'Default author', 'xfd' ) ) . "\n";
	echo '<td colspan="2">' . "\n";
	$users = get_users( [
		'role' => 'administrator',
		'orderby' => 'login',
		'order' => 'ASC',
	] );
	echo '<select name="default_author" id="xfd_default_author" class="xfd_option">' . "\n";
	echo sprintf( '<option value="">%s</option>', _x( 'none', 'm', 'xfd' ) ) . "\n";
	foreach ( $users as $user ) {
		$selected = selected( $user->ID, get_option( 'xfd_default_author' ), FALSE );
		echo sprintf( '<option value="%d"%s>%s (%s)</option>', $user->ID, $selected, $user->user_login, $user->display_name ) . "\n";
	}
	echo '</select>' . "\n";
	xfd_input_nonce( xfd_option_nonce( 'default_author' ) );
	xfd_spinner();
	xfd_description( 'description' ); // TODO description
	echo '</td>' . "\n";
	echo '</tr>' . "\n";
	/* author postmeta */
	echo '<tr>' . "\n";
	echo sprintf( '<th scope="row">%s</th>', __( 'Author postmeta', 'xfd' ) ) . "\n";
	echo '<td>' . "\n";
	echo sprintf( '<button type="button" name="author_postmeta_refresh" class="button xfd_button">%s</button>', __( 'refresh', 'xfd' ) ) . "\n";
	xfd_input_nonce( xfd_option_nonce( 'author_postmeta_refresh' ) );
	xfd_spinner();
	xfd_description( __( 'refresh all postmeta author entries', 'xfd' ) );
	echo '</td>' . "\n";
	echo '<td>' . "\n";
	echo sprintf( '<button type="button" name="author_postmeta_clear" class="button xfd_button" data-confirm="%s">%s</button>', __( 'Delete all author postmeta?', 'xfd' ), __( 'clear', 'xfd' ) ) . "\n";
	xfd_input_nonce( xfd_option_nonce( 'author_postmeta_clear' ) );
	xfd_spinner();
	xfd_description( __( 'clear all postmeta author entries', 'xfd' ) );
	echo '</td>' . "\n";
	echo '</tr>' . "\n";
	/* student tags */
	echo '<tr>' . "\n";
	echo sprintf( '<th scope="row">%s</th>', __( 'Students tags', 'xfd' ) ) . "\n";
	xfd_settings_students_tags_td( 'students_male_tag', __( 'male students', 'xfd' ) );
	xfd_settings_students_tags_td( 'students_female_tag', __( 'female students', 'xfd' ) );
	echo '</tr>' . "\n";
	/* parent categories */
	echo '<tr>' . "\n";
	echo sprintf( '<th scope="row">%s</th>', __( 'Parent categories', 'xfd' ) ) . "\n";
	xfd_settings_parent_categories_td( 'notices_parent_category', __( 'notices', 'xfd' ) );
	xfd_settings_parent_categories_td( 'reports_parent_category', __( 'reports', 'xfd' ) );
	echo '</tr>' . "\n";
	/* cities parent page */
	echo '<tr>' . "\n";
	echo sprintf( '<th scope="row"><label for="xfd_cities_parent_page">%s</label></th>', __( 'Cities parent page', 'xfd' ) ) . "\n";
	echo '<td colspan="2">' . "\n";
	wp_dropdown_pages( [
		'depth' => 1,
		'selected' => get_option( 'xfd_cities_parent_page' ),
		'name' => 'cities_parent_page',
		'id' => 'xfd_cities_parent_page',
		'class' => 'xfd_option',
		'echo' => TRUE,
		'show_option_none' => _x( 'none', 'f', 'xfd' ),
		'option_none_value' => '',
		'sort_order' => 'ASC',
		'sort_column' => 'post_title',
	] );
	xfd_input_nonce( xfd_option_nonce( 'cities_parent_page' ) );
	xfd_spinner();
	xfd_description( 'description' ); // TODO description
	echo '</td>' . "\n";
	echo '</tr>' . "\n";
	echo '</tbody>' . "\n";
	echo '</table>' . "\n";
	xfd_footer();
}

function xfd_settings_students_tags_td( string $key, string $description ) {
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
	xfd_description( $description );
	echo '</td>' . "\n";
}

function xfd_settings_parent_categories_td( string $key, string $description ) {
	echo '<td>' . "\n";
	wp_dropdown_categories( [
		'show_option_none' => _x( 'none', 'f', 'xfd' ),
		'option_none_value' => '',
		'orderby' => 'name',
		'order' => 'ASC',
		'hide_empty' => FALSE,
		'echo' => TRUE,
		'selected' => get_option( 'xfd_' . $key ),
		'hierarchical' => TRUE,
		'name' => $key,
		'class' => 'xfd_option',
		'depth' => 1,
	] );
	xfd_input_nonce( xfd_option_nonce( $key ) );
	xfd_spinner();
	xfd_description( $description );
	echo '</td>' . "\n";
}

/* author postmeta */

function xfd_author( $post ): string {
	$display_name = get_userdata( get_option( 'xfd_default_author' ) )->display_name;
	$author_id = $post->post_author;
	$city_id = get_user_meta( $author_id, 'xfd_city', TRUE );
	if ( $city_id === '' )
		return sprintf( '<span class="xfd_author">%s</span>', $display_name );
	$city = get_post( $city_id )->post_title;
	$male_id = get_option( 'xfd_students_male_tag' );
	$male = get_tag( $male_id )->name;
	$female_id = get_option( 'xfd_students_female_tag' );
	$female = get_tag( $female_id )->name;
	if ( has_tag( $male_id, $post ) ) {
		if ( has_tag( $female_id, $post ) ) {
			return sprintf( '<span class="xfd_author_ab">%s & %s - %s</span>', $male, $female, $city );
		} else {
			return sprintf( '<span class="xfd_author_a">%s - %s</span>', $male, $city );
		}
	} else {
		if ( has_tag( $female_id, $post ) ) {
			return sprintf( '<span class="xfd_author_b">%s - %s</span>', $female, $city );
		} else {
			return sprintf( '<span class="xfd_author_o">%s</span>', $city );
		}
	}
}

add_action( 'wp_ajax_xfd_author_postmeta_refresh', function() {
	if ( !current_user_can( 'administrator' ) )
		exit( 'role' );
	$action = xfd_option_nonce( 'author_postmeta_refresh' );
	if ( wp_verify_nonce( $_POST['nonce'], $action ) === FALSE )
		exit( 'nonce' );
	$posts = get_posts( [
		'post_type' => 'post',
		'post_status' => ['publish', 'pending', 'future', 'private', 'trash'],
		'nopaging' => TRUE,
	] );
	foreach ( $posts as $post )
		update_post_meta( $post->ID, 'xfd_author', xfd_author( $post ) );
	xfd_success();
} );

add_action( 'wp_ajax_xfd_author_postmeta_clear', function() {
	if ( !current_user_can( 'administrator' ) )
		exit( 'role' );
	$action = xfd_option_nonce( 'author_postmeta_clear' );
	if ( wp_verify_nonce( $_POST['nonce'], $action ) === FALSE )
		exit( 'nonce' );
	$posts = get_posts( [
		'post_type' => 'post',
		'post_status' => 'any',
		'nopaging' => TRUE,
		'meta_key' => 'xfd_author',
		'fields' => 'ids',
	] );
	if ( empty( $posts ) )
		exit( 'empty' );
	foreach ( $posts as $post )
		delete_post_meta( $post, 'xfd_author' );
	xfd_success();
} );

add_action( 'save_post_post', function( $post_id, $post, $update ) {
	update_post_meta( $post->ID, 'xfd_author', xfd_author( $post ) );
}, 10, 3 );
