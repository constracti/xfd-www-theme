<?php

if ( !defined( 'ABSPATH' ) )
	exit;

// TODO clean and clear meta and options

function xfd_settings_page() {
	if ( !current_user_can( 'administrator' ) )
		return;
	xfd_header();
?>
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<label for="xfd_default_author"><?= __( 'Default author', 'xfd' ) ?></label>
				</th>
				<td colspan="2">
<?php
	$users = get_users( [
		'role' => 'administrator',
		'orderby' => 'login',
		'order' => 'ASC',
	] );
	echo '<select name="default_author" id="xfd_default_author" class="xfd_option">' . "\n";
	echo sprintf( '<option value="">%s</option>', __( 'none', 'm', 'xfd' ) ) . "\n";
	foreach ( $users as $user ) {
		$selected = selected( $user->ID, get_option( 'xfd_default_author' ), FALSE );
		echo sprintf( '<option value="%d"%s>%s (%s)</option>', $user->ID, $selected, $user->user_login, $user->display_name ) . "\n";
	}
	echo '</select>' . "\n";
	xfd_input_nonce( xfd_option_nonce( 'default_author' ) );
	xfd_spinner();
	xfd_description( 'description' ); // TODO description
?>
				</td>
			</tr>
			<tr>
				<th scope="row"><?= __( 'Students tags', 'xfd' ) ?></th>
<?php
	xfd_settings_students_tags_td( 'students_male_tag', __( 'male students', 'xfd' ) );
	xfd_settings_students_tags_td( 'students_female_tag', __( 'female students', 'xfd' ) );
?>
			</tr>
			<tr>
				<th scope="row"><?= __( 'Parent categories', 'xfd' ) ?></th>
<?php
	xfd_settings_parent_categories_td( 'notices_parent_category', __( 'notices', 'xfd' ) );
	xfd_settings_parent_categories_td( 'reports_parent_category', __( 'reports', 'xfd' ) );
?>
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
		'name' => 'cities_parent_page',
		'id' => 'xfd_cities_parent_page',
		'class' => 'xfd_option',
		'echo' => TRUE,
		'show_option_none' => __( 'none', 'f', 'xfd' ),
		'option_none_value' => '',
		'sort_order' => 'ASC',
		'sort_column' => 'post_title',
	] );
	xfd_input_nonce( xfd_option_nonce( 'cities_parent_page' ) );
	xfd_spinner();
	xfd_description( 'description' ); // TODO description
?>
				</td>
			</tr>
		</tbody>
	</table>
<?php
	xfd_footer();
}

function xfd_settings_students_tags_td( string $key, string $description ) {
	echo '<td>' . "\n";
	wp_dropdown_categories( [
		'show_option_none' => __( 'none', 'f', 'xfd' ),
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
		'show_option_none' => __( 'none', 'f', 'xfd' ),
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
