<?php

if ( !defined( 'ABSPATH' ) )
	exit;

// TODO set_option generic script and ajax call; main .php and .js
 
function xfd_success( array $array = [] ) {
	header( 'content-type: application/json' );
	exit( json_encode( $array ) );
}

function xfd_notice( string $class, string $message ): string {
	return sprintf( '<div class="notice notice-%s inline"><p>%s</p></div>', $class, $message );
}

function xfd_hidden( string $name, string $value ): string {
	return sprintf( '<input type="hidden" name="%s" value="%s" />', $name, $value );
}

function xfd_input_nonce( string $action ): string {
	$nonce = wp_create_nonce( $action );
	return xfd_hidden( 'nonce', $nonce );
}

function xfd_spinner(): string {
	return '<span class="spinner" style="float: none;"></span>';
}

function xfd_description( string $description ): string {
	return sprintf( '<p class="description">%s</p>', $description ) . "\n";
}

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
	// add submenu page 'Users'
	$parent_slug = 'xfd-settings';
	$page_title = sprintf( '%s :: %s', __( 'XFD', 'xfd' ), __( 'Categories', 'xfd' ) );
	$menu_title = __( 'Categories', 'xfd' );
	$menu_slug = 'xfd-categories';
	$function = 'xfd_categories_page';
	add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
	// add submenu page 'Users'
	$parent_slug = 'xfd-settings';
	$page_title = sprintf( '%s :: %s', __( 'XFD', 'xfd' ), __( 'Users', 'xfd' ) );
	$menu_title = __( 'Users', 'xfd' );
	$menu_slug = 'xfd-users';
	$function = 'xfd_users_page';
	add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
} );

require_once( get_stylesheet_directory() . '/categories.php' );
require_once( get_stylesheet_directory() . '/settings.php' );
require_once( get_stylesheet_directory() . '/users.php' );
