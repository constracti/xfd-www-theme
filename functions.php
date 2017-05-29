<?php
/*  ----------------------------------------------------------------------------
    Newspaper 7 Child theme - Please do not use this child theme with older versions of Newspaper Theme

    What can be overwritten via the child theme:
     - everything from /parts folder
     - all the loops (loop.php loop-single-1.php) etc

     - the rest of the theme has to be modified via the theme api:
       http://forum.tagdiv.com/the-theme-api/

 */



define( 'XFD_DIR', get_stylesheet_directory() );
define( 'XFD_URL', get_stylesheet_directory_uri() );


/*  ----------------------------------------------------------------------------
    add the parent style + style.css from this folder
 */
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles', 1001);
function theme_enqueue_styles() {
    wp_enqueue_style('td-theme', get_template_directory_uri() . '/style.css', '', TD_THEME_VERSION . 'c' , 'all' );
    wp_enqueue_style('td-theme-child', get_stylesheet_directory_uri() . '/style.css', array('td-theme'), TD_THEME_VERSION . 'c', 'all' );

}


function custom_menu_page_removing() {					  // Κρύβουμε στοιχεία από τους επιμελητές και τους συντάκτες.
	if( current_user_can('epimelites') || current_user_can('author') ){
    	remove_menu_page( 'edit-comments.php' );          //τα σχόλια.
		remove_menu_page( 'vc-welcome' );         		  // το visual composer.
		remove_menu_page( 'tools.php' );                  // τα εργαλεία.
		remove_menu_page( 'link-manager.php' );			  // τους συνδέσμους.
		remove_menu_page( 'wpcf7' );			  // τους συνδέσμους.
		
		
		
	}
}
add_action( 'admin_menu', 'custom_menu_page_removing' );


// ΤΡΟΠΟΠΟΙΗΣΕΙΣ ΣΤΗΝ ΣΕΛΙΔΑ ΣΥΝΔΕΣΗΣ
function my_login_logo() { ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/images/site-login-logo.png);
            background-size: 250px;
			width:300px;
			height:200px;
			
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );

function my_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

function my_login_logo_url_title() {
    return 'Χριστιανική Φοιτητική Δράση';
}
add_filter( 'login_headertitle', 'my_login_logo_url_title' );


// ΠΡΟΣΘΕΤΕΙ ΕΠΙΛΕΓΜΕΝΗ ΕΙΚΟΝΑ ( ΕΦΟΣΟΝ ΔΕΝ ΥΠΑΡΧΕΙ ) ΤΗΝ ΠΡΩΤΗ ΠΟΥ ΘΑ ΒΡΕΙ ΣΤΟ ΑΡΘΡΟ. 
function auto_featured_image() {
    global $post;
 
    if (!has_post_thumbnail($post->ID)) {
        $attached_image = get_children( "post_parent=$post->ID&amp;post_type=attachment&amp;post_mime_type=image&amp;numberposts=1" );
         
      if ($attached_image) {
              foreach ($attached_image as $attachment_id => $attachment) {
                   set_post_thumbnail($post->ID, $attachment_id);
              }
         }
    }
}


/* constracti */
require_once( get_stylesheet_directory() . '/main.php' );


@ini_set( 'upload_max_size' , '128M' );
@ini_set( 'post_max_size', '128M');
@ini_set( 'max_execution_time', '600' );

add_filter( 'pre_get_posts', function( WP_Query $query ) {
	$q = $query->query;
	if ( $q['tag'] !== 'postcal' )
		return;
	unset( $q['tag'] );
	$q['post_status'] = ['publish', 'future'];
	$now = new DateTime();
	$q['meta_key'] = 'postcal';
	$q['meta_compare'] = '>=';
	$q['meta_value'] = $now->format( 'Y-m-d' );
	$q['orderby'] = 'meta_value';
	$q['order'] = 'ASC';
	$query->parse_query( $q );
} );

add_action( 'td_global_after', function() {
	td_api_block::add( 'td_block_7_postcal', [
		'map_in_visual_composer' => true,
		"name" => 'Block 7 postcal',
		"base" => 'td_block_7_postcal',
		"class" => 'td_block_7',
		"controls" => "full",
		"category" => 'Blocks',
		'tdc_category' => 'Blocks',
		'icon' => 'icon-pagebuilder-td_block_7',
		'file' => XFD_DIR . '/td_block_7_postcal.php',
		'params' => array_merge(
			td_config::get_map_block_general_array(),
			td_config::get_map_filter_array(),
			td_config::get_map_block_ajax_filter_array(),
			td_config::get_map_block_pagination_array()
		)
	] );
	td_api_module::add( 'td_module_6_postcal', [
		'file' => XFD_DIR . '/td_module_6_postcal.php',
		'text' => 'Module 6 postcal',
		'img' => td_global::$get_template_directory_uri . '/images/panel/modules/td_module_6.png',
		'used_on_blocks' => array('td_block_7_postcal'),
		'excerpt_title' => 12,
		'excerpt_content' => '',
		'enabled_on_more_articles_box' => true,
		'enabled_on_loops' => true,
		'uses_columns' => true,                      // if the module uses columns on the page template + loop
		'category_label' => true,
		'class' => 'td_module_6 td_module_wrap td-animation-stack',
		'group' => '' // '' - main theme, 'mob' - mobile theme, 'woo' - woo theme
	] );
	$fn = function() {
		$map_block_array = td_config::get_map_block_general_array();
		// remove some of the params that are not needed for the slide
		$map_block_array = td_util::vc_array_remove_params($map_block_array, array(
			'border_top',
			'ajax_pagination',
			'ajax_pagination_infinite_stop'
		));
		// add some more
		$temp_array_merge = array_merge(
			array(
				array(
					"param_name" => "autoplay",
					"type" => "textfield",
					"value" => '',
					"heading" => 'Autoplay slider (at x seconds)',
					"description" => "Leave empty do disable autoplay",
					"holder" => "div",
					"class" => ""
				)
			),
			td_config::get_map_filter_array(),
			$map_block_array
		);
		return $temp_array_merge;
	};
	td_api_block::add( 'td_block_slide_custom', [
		'map_in_visual_composer' => true,
		"name" => 'Slide custom',
		"base" => "td_block_slide_custom",
		"class" => "td_block_slide",
		"controls" => "full",
		"category" => 'Blocks',
		'icon' => 'icon-pagebuilder-slide',
		'file' => XFD_DIR . '/td_block_slide_custom.php',
		'params' => array_merge(
			$fn(),
			td_config::get_map_block_ajax_filter_array(),
			[
				[
					'param_name' => 'css',
					'value' => '',
					'type' => 'css_editor',
					'heading' => 'Css',
					'group' => 'Design options',
				]
			]
		)
	] );
} );

add_action( 'wp_enqueue_scripts', function() {
?>
<style>
.td_block_slide_custom_selectors {
	margin-top: 2px;
	text-align: center;
}
.td_block_slide_custom_selectors>* {
	margin: 0 1px;
	border-style: solid;
	border-width: 2px;
	border-color: #888;
}
.td_block_slide_custom_selectors>*:hover {
	border-color: #666;
}
.td_block_slide_custom_selectors>*.selected {
	border-color: #222;
}
.td_block_slide_custom_selectors>*.selected:hover {
	border-color: #000;
}
</style>
<?php
} );
