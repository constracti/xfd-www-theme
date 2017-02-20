<?php
add_action( 'admin_menu', function() {
	// applies to all pages
	$capability = 'administrator';
	if ( ! current_user_can( $capability ) )
		return;
	// add menu page 'XFD'
	$page_title = __( 'XFD', 'xfd' );
	$menu_title = __( 'XFD', 'xfd' );
	$menu_slug = 'xfd-settings';
	$function = 'xfd_settings_page';
	$icon_url = get_stylesheet_directory_uri() . '/xfd-square.png';
	$position = NULL;
	add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
	// add submenu page 'Settings'
	$parent_slug = 'xfd-settings';
	$page_title = sprintf( '%s :: %s', __( 'XFD', 'xfd' ), __( 'Settings', 'xfd' ) );
	$menu_title = __( 'Settings', 'xfd' );
	$menu_slug = 'xfd-settings';
	$function = 'xfd_settings_page';
	add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
	// add submenu page 'Tags'
	$parent_slug = 'xfd-settings';
	$page_title = sprintf( '%s :: %s', __( 'XFD', 'xfd' ), __( 'Tags', 'xfd' ) );
	$menu_title = __( 'Tags', 'xfd' );
	$menu_slug = 'xfd-tags';
	$function = 'xfd_tags_page';
	add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
} );

function xfd_settings_page() {
	if ( ! current_user_can( 'administrator' ) )
		return;
	// TODO clean and clear meta and options
?>
<div class="wrap">
	<h1><?= sprintf( '%s :: %s', __( 'XFD', 'xfd' ), __( 'Settings', 'xfd' ) ) ?></h1>
	<p class="dashicons-before dashicons-info"><?= __( 'Options are immediately saved.', 'xfd' ) ?></p>
	<h2 class="title"><?= __( 'Parent selection', 'xfd' ) ?></h2>
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<label for="xfd_notices_parent_category"><?= __( 'Notices parent category', 'xfd' ) ?></label>
				</th>
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
					<p class="description">description</p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="xfd_reports_parent_category"><?= __( 'Reports parent category', 'xfd' ) ?></label>
				</th>
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
					<p class="description">description</p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="xfd_cities_parent_page"><?= __( 'Cities parent page', 'xfd' ) ?></label>
				</th>
				<td>
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
	<h2 class="title"><?= __( 'Categories per city', 'xfd' ) ?></h2>
	<div id="xfd_categories_per_city">
<?php
	echo xfd_categories_per_city_html();
?>
	</div>
	<h2 class="title"><?= __( 'Student tags', 'xfd' ) ?></h2>
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<label for="xfd_students_male_tag"><?= __( 'Male students tag', 'xfd' ) ?></label>
				</th>
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
					<p class="description">description</p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="xfd_students_female_tag"><?= __( 'Female students tag', 'xfd' ) ?></label>
				</th>
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
					<p class="description">description</p>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<?php
}

function xfd_categories_per_city_html(): string {
	$notices_parent_category = get_option( 'xfd_notices_parent_category' );
	$reports_parent_category = get_option( 'xfd_reports_parent_category' );
	$cities_parent_page = get_option( 'xfd_cities_parent_page' );
	if ( $notices_parent_category === FALSE || $reports_parent_category === FALSE || $cities_parent_page === FALSE ) {
		$message = __( 'All parents must have a specified value.', 'xfd' );
		return sprintf( '<div class="notice notice-error inline"><p>%s</p></div>', $message ) . "\n";
	}
	$notices_categories = get_terms( [
		'taxonomy' => 'category',
		'hide_empty' => FALSE,
		'fields' => 'ids',
		'parent' => $notices_parent_category,
	] );
	$reports_categories = get_terms( [
		'taxonomy' => 'category',
		'hide_empty' => FALSE,
		'fields' => 'ids',
		'parent' => $reports_parent_category,
	] );
	$cities_pages = get_pages( [
		'hierarchical' => FALSE,
		'parent' => $cities_parent_page,
	] );
	$html = '';
	if ( ! count( $notices_categories ) ) {
		$message = __( 'No categories were found with the selected notices parent.', 'xfd' );
		$html .= sprintf( '<div class="notice notice-warning inline"><p>%s</p></div>', $message ) . "\n";
	}
	if ( ! count( $reports_categories ) ) {
		$message = __( 'No categories were found with the selected reports parent.', 'xfd' );
		$html .= sprintf( '<div class="notice notice-warning inline"><p>%s</p></div>', $message ) . "\n";
	}
	if ( ! count( $cities_pages ) ) {
		$message = __( 'No pages were found with the selected cities parent.', 'xfd' );
		$html .= sprintf( '<div class="notice notice-warning inline"><p>%s</p></div>', $message ) . "\n";
	}
	$html .= '<table class="form-table" id="xfd_table">' . "\n";
	$html .= '<tbody>' . "\n";
	foreach ( $cities_pages as $page ) {
		$html .= '<tr>' . "\n";
		$html .= sprintf( '<th scope="row">%s</th>', $page->post_title ) . "\n";
		$html .= '<td>' . "\n";
		$html .= '<div>' . "\n";
		$html .= wp_dropdown_categories( [
			'show_option_none' => __( 'none', 'f', 'xfd' ),
			'option_none_value' => 0,
			'orderby' => 'name',
			'order' => 'ASC',
			'hide_empty' => FALSE,
			'include' => $notices_categories,
			'echo' => FALSE,
			'selected' => get_post_meta( $page->ID, 'xfd_city_notice_category', TRUE ),
			'name' => 'xfd_city_notice_category',
			'class' => 'xfd_city_category',
		] ) . "\n";
		$nonce = wp_create_nonce( sprintf( 'xfd_city_notice_category_%d', $page->ID ) );
		$html .= sprintf( '<span class="spinner" data-page="%d" data-nonce="%s" style="float: none;"></span>', $page->ID, $nonce ) . "\n";
		$html .= '<p class="description">notices category</p>' . "\n";
		$html .= '</div>' . "\n";
		$html .= '<br />' . "\n";
		$html .= '<div>' . "\n";
		$html .= wp_dropdown_categories( [
			'show_option_none' => __( 'none', 'f', 'xfd' ),
			'option_none_value' => 0,
			'orderby' => 'name',
			'order' => 'ASC',
			'hide_empty' => FALSE,
			'include' => $reports_categories,
			'echo' => FALSE,
			'selected' => get_post_meta( $page->ID, 'xfd_city_report_category', TRUE ),
			'name' => 'xfd_city_report_category',
			'class' => 'xfd_city_category',
		] ) . "\n";
		$nonce = wp_create_nonce( sprintf( 'xfd_city_report_category_%d', $page->ID ) );
		$html .= sprintf( '<span class="spinner" data-page="%d" data-nonce="%s" style="float: none;"></span>', $page->ID, $nonce ) . "\n";
		$html .= '<p class="description">reports category</p>' . "\n";
		$html .= '</div>' . "\n";
		$html .= '</td>' . "\n";
		$html .= '</tr>' . "\n";
	}
	$html .= '</tbody>' . "\n";
	$html .= '</table>' . "\n";
	return $html;
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
	xfd_success( ['html' => xfd_categories_per_city_html()] );
}

add_action( 'wp_ajax_xfd_city_notice_category', 'xfd_city_category_ajax' );
add_action( 'wp_ajax_xfd_city_report_category', 'xfd_city_category_ajax' );
function xfd_city_category_ajax() {
	if ( ! current_user_can( 'administrator' ) )
		exit;
	$action = $_POST['action'];
	$page = intval( $_POST['page'] );
	if ( wp_verify_nonce( $_POST['nonce'], sprintf( '%s_%d', $action, $page ) ) === FALSE )
		exit;
	$value = intval( $_POST['value'] );
	if ( $value )
		update_post_meta( $page, $action, $value );
	else
		delete_post_meta( $page, $action );
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

function xfd_tags_page() {
	if ( ! current_user_can( 'administrator' ) )
		return;
?>
<div class="wrap">
	<h1><?= sprintf( '%s :: %s', __( 'XFD', 'xfd' ), __( 'Tags', 'xfd' ) ) ?></h1>
<?php
	$tags = get_tags( [
		'hide_empty' => FALSE,
	] );
	foreach ( $tags as $tag ) {
		echo "\t" . '<p>' . "\n";
		echo "\t\t" . '<input type="checkbox" />' . "\n";
		echo "\t\t" . sprintf( '%d %s %s', $tag->term_id, $tag->name, $tag->slug ) . "\n";
		echo "\t" . '</p>' . "\n";
	}
?>
</div>
<?php
}
