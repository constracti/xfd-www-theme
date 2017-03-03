<?php

if ( !defined( 'ABSPATH' ) )
	exit;

// TODO clear options and meta values
// TODO author colors!

/* initialize tabs */
$xfd_tabs = [
	'xfd-settings' => __( 'Settings', 'xfd' ),
	'xfd-categories' => __( 'Categories', 'xfd' ),
	'xfd-users' => __( 'Users', 'xfd' ),
	'xfd-tags' => __( 'Tags', 'xfd' ),
];
 
function xfd_success( array $array = [] ) {
	header( 'content-type: application/json' );
	exit( json_encode( $array ) );
}

function xfd_header() {
	global $xfd_tabs;
	$active = $_GET['page'];
	$url = admin_url( 'admin.php' );
	echo '<div class="wrap">' . "\n";
	echo sprintf( '<h1>%s :: %s</h1>', __( 'XFD', 'xfd' ), $xfd_tabs[ $active ] ) . "\n";
	echo '<h2 class="nav-tab-wrapper">' . "\n";
	foreach ( $xfd_tabs as $page => $title ) {
		$class = ['nav-tab'];
		if ( $page === $active )
			$class[] = 'nav-tab-active';
		echo sprintf( '<a class="%s" href="%s?page=%s">%s</a>', implode( ' ', $class ), $url, $page, $title ) . "\n";
	}
	echo '</h2>' . "\n";
}

function xfd_footer() {
	echo '<hr />' . "\n";
	echo sprintf( '<p class="dashicons-before dashicons-info">%s</p>', __( 'Options are immediately saved.', 'xfd' ) );
	echo '</div>' . "\n";
}

function xfd_notice( string $class, string $message ) {
	echo sprintf( '<div class="notice notice-%s inline"><p>%s</p></div>', $class, $message ) . "\n";
}

function xfd_hidden( string $name, string $value ) {
	echo sprintf( '<input type="hidden" name="%s" value="%s" />', $name, $value ) . "\n";
}

function xfd_input_nonce( string $action ) {
	$nonce = wp_create_nonce( $action );
	xfd_hidden( 'nonce', $nonce );
}

function xfd_spinner() {
	echo '<span class="spinner" style="float: none;"></span>' . "\n";
}

function xfd_description( string $description ) {
	echo sprintf( '<p class="description">%s</p>', $description ) . "\n";
}

add_action( 'admin_menu', function() {
	global $xfd_tabs;
	// applies to all pages
	$capability = 'administrator';
	if ( !current_user_can( $capability ) )
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
	$page_title = sprintf( '%s :: %s', __( 'XFD', 'xfd' ), $xfd_tabs['xfd-settings'] );
	$menu_title = $xfd_tabs['xfd-settings'];
	$menu_slug = 'xfd-settings';
	$function = 'xfd_settings_page';
	add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
	// add submenu page 'Users'
	$parent_slug = 'xfd-settings';
	$page_title = sprintf( '%s :: %s', __( 'XFD', 'xfd' ), $xfd_tabs['xfd-categories'] );
	$menu_title = $xfd_tabs['xfd-categories'];
	$menu_slug = 'xfd-categories';
	$function = 'xfd_categories_page';
	add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
	// add submenu page 'Users'
	$parent_slug = 'xfd-settings';
	$page_title = sprintf( '%s :: %s', __( 'XFD', 'xfd' ), $xfd_tabs['xfd-users'] );
	$menu_title = $xfd_tabs['xfd-users'];
	$menu_slug = 'xfd-users';
	$function = 'xfd_users_page';
	add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
	// add submenu page 'Tags'
	$parent_slug = 'xfd-settings';
	$page_title = sprintf( '%s :: %s', __( 'XFD', 'xfd' ), $xfd_tabs['xfd-tags'] );
	$menu_title = $xfd_tabs['xfd-tags'];
	$menu_slug = 'xfd-tags';
	$function = 'xfd_tags_page';
	add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
} );

add_action( 'admin_enqueue_scripts', function( $hook ) {
	if ( !current_user_can( 'administrator' ) )
		return;
	if ( !in_array( $hook, ['toplevel_page_xfd-settings', 'xfd_page_xfd-categories', 'xfd_page_xfd-users', 'xfd_page_xfd-tags'] ) )
		return;
	wp_enqueue_script( 'xfd-main', get_stylesheet_directory_uri() . '/main.js', ['jquery'] );
} );

function xfd_option_nonce( string $key ): string {
	return sprintf( 'xfd_%s', $key );
}

add_action( 'wp_ajax_xfd_option', function() {
	if ( !current_user_can( 'administrator' ) )
		exit( 'role' );
	$key = $_POST['key'];
	$action = xfd_option_nonce( $key );
	if ( wp_verify_nonce( $_POST['nonce'], $action ) === FALSE )
		exit( 'nonce' );
	$value = $_POST['value'];
	if ( $value !== '' )
		update_option( 'xfd_' . $key, $value, FALSE );
	else
		delete_option( 'xfd_' . $key );
	xfd_success();
} );

function xfd_post_nonce( int $id, string $key ): string {
	return sprintf( 'xfd_post_%d_%s', $id, $key );
}

add_action( 'wp_ajax_xfd_post_meta', function() {
	if ( !current_user_can( 'administrator' ) )
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

function xfd_user_nonce( int $id, string $key ): string {
	return sprintf( 'xfd_user_%d_%s', $id, $key );
}

add_action( 'wp_ajax_xfd_user_meta', function() {
	if ( !current_user_can( 'administrator' ) )
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

require_once( get_stylesheet_directory() . '/categories.php' );
require_once( get_stylesheet_directory() . '/settings.php' );
require_once( get_stylesheet_directory() . '/tags.php' );
require_once( get_stylesheet_directory() . '/users.php' );

require_once( get_stylesheet_directory() . '/metabox.php' );
