<?php

if ( !defined( 'ABSPATH' ) )
	exit;

function xfd_settings_page() {
	if ( ! current_user_can( 'administrator' ) )
		return;
	// TODO clean and clear meta and options
?>
<div class="wrap">
	<h1><?= sprintf( '%s :: %s', __( 'XFD', 'xfd' ), __( 'Settings', 'xfd' ) ) ?></h1>
	<p class="dashicons-before dashicons-info"><?= __( 'Options are immediately saved.', 'xfd' ) ?></p>
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row"><?= __( 'Students tags', 'xfd' ) ?></th>
				<td>
<?php
	wp_dropdown_categories( [
		'show_option_none' => __( 'none', 'f', 'xfd' ),
		'option_none_value' => 0,
		'orderby' => 'name',
		'order' => 'ASC',
		'hide_empty' => FALSE,
		'echo' => TRUE,
		'selected' => get_option( 'xfd_students_male_tag' ),
		'name' => 'xfd_students_male_tag',
		'id' => 'xfd_students_male_tag',
		'class' => 'xfd_students_tag',
		'taxonomy' => 'post_tag',
	] );
	$nonce = wp_create_nonce( 'xfd_students_male_tag' );
?>
					<span class="spinner" data-nonce="<?= $nonce ?>" style="float: none;"></span>
					<p class="description"><?= __( 'male students', 'xfd' ) ?></p>
				</td>
				<td>
<?php
	wp_dropdown_categories( [
		'show_option_none' => __( 'none', 'f', 'xfd' ),

		'option_none_value' => 0,
		'orderby' => 'name',
		'order' => 'ASC',
		'hide_empty' => FALSE,
		'echo' => TRUE,
		'selected' => get_option( 'xfd_students_female_tag' ),
		'name' => 'xfd_students_female_tag',
		'id' => 'xfd_students_female_tag',
		'class' => 'xfd_students_tag',
		'taxonomy' => 'post_tag',
	] );
	$nonce = wp_create_nonce( 'xfd_students_female_tag' );
?>
					<span class="spinner" data-nonce="<?= $nonce ?>" style="float: none;"></span>
					<p class="description"><?= __( 'female students', 'xfd' ) ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?= __( 'Parent categories', 'xfd' ) ?></th>
				<td>
<?php
	wp_dropdown_categories( [
		'show_option_none' => __( 'none', 'f', 'xfd' ),
		'option_none_value' => 0,
		'orderby' => 'name',
		'order' => 'ASC',
		'hide_empty' => FALSE,
		'echo' => TRUE,
		'selected' => get_option( 'xfd_notices_parent_category' ),
		'hierarchical' => TRUE,
		'name' => 'xfd_notices_parent_category',
		'id' => 'xfd_notices_parent_category',
		'class' => 'xfd_parent',
		'depth' => 1,
	] );
	$nonce = wp_create_nonce( 'xfd_notices_parent_category' );
?>
					<span class="spinner" data-nonce="<?= $nonce ?>" style="float: none;"></span>
					<p class="description"><?= __( 'notices', 'xfd' ) ?></p>
				</td>
				<td>
<?php
	wp_dropdown_categories( [
		'show_option_none' => __( 'none', 'f', 'xfd' ),
		'option_none_value' => 0,
		'orderby' => 'name',
		'order' => 'ASC',
		'hide_empty' => FALSE,
		'echo' => TRUE,
		'selected' => get_option( 'xfd_reports_parent_category' ),
		'hierarchical' => TRUE,
		'name' => 'xfd_reports_parent_category',
		'id' => 'xfd_reports_parent_category',
		'class' => 'xfd_parent',
		'depth' => 1,
	] );
	$nonce = wp_create_nonce( 'xfd_reports_parent_category' );
?>
					<span class="spinner" data-nonce="<?= $nonce ?>" style="float: none;"></span>
					<p class="description"><?= __( 'reports', 'xfd' ) ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="xfd_cities_parent_page"><?= __( 'Cities parent page', 'xfd' ) ?></label>
				</th>
				<td colspan="2">
<?php
	wp_dropdown_pages( [
		'depth' => 1,
		'selected' => get_option( 'xfd_cities_parent_page' ),
		'name' => 'xfd_cities_parent_page',
		'id' => 'xfd_cities_parent_page',
		'class' => 'xfd_parent',
		'echo' => TRUE,
		'show_option_none' => __( 'none', 'f', 'xfd' ),
		'option_none_value' => 0,
		'sort_order' => 'ASC',
		'sort_column' => 'post_title',
	] );
	$nonce = wp_create_nonce( 'xfd_cities_parent_page' );
?>
					<span class="spinner" data-nonce="<?= $nonce ?>" style="float: none;"></span>
					<p class="description">description</p>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<?php
}

add_action( 'admin_enqueue_scripts', function( $hook ) {
	if ( ! current_user_can( 'administrator' ) )
		return;
	if ( $hook !== 'toplevel_page_xfd-settings' )
		return;
	wp_enqueue_script( 'xfd-settings', get_stylesheet_directory_uri() . '/settings.js', ['jquery'] );
} );

add_action( 'wp_ajax_xfd_notices_parent_category', 'xfd_parent_ajax' );
add_action( 'wp_ajax_xfd_reports_parent_category', 'xfd_parent_ajax' );
add_action( 'wp_ajax_xfd_cities_parent_page', 'xfd_parent_ajax' );
function xfd_parent_ajax() {
	if ( ! current_user_can( 'administrator' ) )
		exit;
	$action = $_POST['action'];
	if ( wp_verify_nonce( $_POST['nonce'], $action ) === FALSE )
		exit;
	$value = intval( $_POST['value'] );
	if ( $value )
		update_option( $action, $value, FALSE );
	else
		delete_option( $action );
	xfd_success();
}

add_action( 'wp_ajax_xfd_students_male_tag', 'xfd_students_tag_ajax' );
add_action( 'wp_ajax_xfd_students_female_tag', 'xfd_students_tag_ajax' );
function xfd_students_tag_ajax() {
	if ( ! current_user_can( 'administrator' ) )
		exit;
	$action = $_POST['action'];
	if ( wp_verify_nonce( $_POST['nonce'], $action ) === FALSE )
		exit;
	$value = intval( $_POST['value'] );
	if ( $value )
		update_option( $action, $value, FALSE );
	else
		delete_option( $action );
	xfd_success();
}
