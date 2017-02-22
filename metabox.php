<?php

if ( !defined( 'ABSPATH' ) )
	exit;

add_action( 'add_meta_boxes_post', function() {
	// TODO permissions
	// TODO post saved values
	// TODO hide metaboxes
	// TODO script init and change
	// TODO radio buttons remove name property
	if ( !current_user_can( 'administrator' ) )
		return;
	if ( get_user_meta( get_current_user_id(), 'xfd_city', TRUE ) === '' )
		return;
	add_meta_box( 'xfddiv', __( 'XFD', 'xfd' ), function( $post ) {
		$user_id = get_current_user_id();
		$city = get_post( get_user_meta( $user_id, 'xfd_city', TRUE ) );
		echo sprintf( '<p><strong>%s</strong></p>', $city->post_title ) . "\n";
		$notices_parent = get_tag( get_option( 'xfd_notices_parent_category' ) );
		$notices = get_tag( get_post_meta( $city->ID, 'xfd_city_notices_category', TRUE ) );
		echo sprintf( '<p><label><input type="radio" name="xfd_category_radio" value="%d"%s /> %s</label></p>',
			$notices->term_id,
			checked( TRUE, TRUE, FALSE ),
			$notices_parent->name
		) . "\n";
		$reports_parent = get_tag( get_option( 'xfd_reports_parent_category' ) );
		$reports = get_tag( get_post_meta( $city->ID, 'xfd_city_reports_category', TRUE ) );
		echo sprintf( '<p><label><input type="radio" name="xfd_category_radio" value="%d"%s /> %s</label></p>',
			$reports->term_id,
			checked( FALSE, TRUE, FALSE ),
			$reports_parent->name
		) . "\n";
		echo '<hr />' . "\n";
		$students_male_tag = get_tag( get_option( 'xfd_students_male_tag' ) );
		echo sprintf( '<p><label><input type="checkbox" value="%d"%s /> %s</label></p>',
			$students_male_tag->term_id,
			checked( get_user_meta( $user_id, 'xfd_male', TRUE ), 'on', FALSE ),
			$students_male_tag->name
		) . "\n";
		$students_female_tag = get_tag( get_option( 'xfd_students_female_tag' ) );
		echo sprintf( '<p><label><input type="checkbox" value="%d"%s /> %s</label></p>',
			$students_female_tag->term_id,
			checked( get_user_meta( $user_id, 'xfd_female', TRUE ), 'on', FALSE ),
			$students_female_tag->name
		) . "\n";
		echo '<hr />' . "\n";
		echo '<p><small>under development</small></p>' . "\n";
	}, 'post', 'side' );
} );
