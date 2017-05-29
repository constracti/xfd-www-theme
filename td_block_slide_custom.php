<?php

class td_block_slide_custom extends td_block_slide {


    /**
     * @param $additional_classes_array array - of classes to add to the block
     * @return string
     */
    protected function get_block_classes($additional_classes_array = array()) {
        $class = parent::get_block_classes( $additional_classes_array );
        if ( $class !== '' )
            $class .= ' ';
        $class .= ' td_block_slide';
        return $class;
    }


    /**
     * @param $posts
     * @param string $td_column_number - get the column number
     * @param string $autoplay - not use via ajax
     * @param bool $is_ajax - if true the script will return the js inline, if not, it will use the td_js_buffer class
     * @return string
     */
    function inner($posts, $td_column_number = '', $autoplay = '', $is_ajax = false) {
        $buffy = '';

        if (empty($td_column_number)) {
            $td_column_number = td_global::vc_get_column_number(); // get the column width of the block from the page builder API
        }

        $td_post_count = 0; // the number of posts rendered

        $td_unique_id_slide = td_global::td_generate_unique_id();

        //@generic class for sliders : td-theme-slider
        $buffy .= '<div id="' . $td_unique_id_slide . '" class="td-theme-slider iosSlider-col-' . $td_column_number . ' td_mod_wrap">';
            $buffy .= '<div class="td-slider ">';
                if (!empty($posts)) {
                    foreach ($posts as $post) {
                        //$buffy .= td_modules::mod_slide_render($post, $td_column_number, $td_post_count);
                        $td_module_slide = new td_module_slide($post);
                        $buffy .= $td_module_slide->render($td_column_number, $td_post_count, $td_unique_id_slide);
                        $td_post_count++;

	                    // Show only the first frame in tagDiv composer
	                    if (td_util::tdc_is_live_editor_iframe() or td_util::tdc_is_live_editor_ajax()) {
		                    break;
	                    }
                    }
                }
            $buffy .= '</div>'; //close slider

            $buffy .= '<i class = "td-icon-left prevButton"></i>';
            $buffy .= '<i class = "td-icon-right nextButton"></i>';

        $buffy .= '</div>'; //close ios
        $buffy .= '<div class="td_block_slide_custom_selectors">';
        if ( !empty( $posts ) )
        	foreach ( $posts as $post )
			$buffy .= get_the_post_thumbnail( $post, 'td_100x70', ['title' => $post->post_title] );
        $buffy .= '</div>';

	    // Suppress any iosSlider in tagDiv composer
	    if (td_util::tdc_is_live_editor_iframe() or td_util::tdc_is_live_editor_ajax()) {
		    return $buffy;
	    }

        if (!empty($autoplay)) {
            $autoplay_string =  '
            autoSlide: true,
            autoSlideTimer: ' . $autoplay * 1000 . ',
            ';
        } else {
            $autoplay_string = '';
        }

        //add resize events
        //$add_js_resize = '';
        //if($td_column_number > 1) {
            $add_js_resize = ',
                //onSliderLoaded : td_resize_normal_slide,
                //onSliderResize : td_resize_normal_slide_and_update';
        //}


        $slide_js = '
jQuery(document).ready(function() {
    var selectors = jQuery("#' . $td_unique_id_slide . '").next().children();
    selectors.css( "max-width", "' . sprintf( '%0.2f', 90 / count( $posts ) ) . '%" );
    function slide_change( args ) {
        selectors.removeClass( "selected" ).eq( args.currentSlideNumber - 1 ).addClass( "selected" );
    }
    jQuery("#' . $td_unique_id_slide . '").iosSlider({
        snapToChildren: true,
        desktopClickDrag: true,
        keyboardControls: false,
        responsiveSlideContainer: true,
        responsiveSlides: true,
        ' . $autoplay_string. '

        infiniteSlider: true,
        navSlideSelector: selectors,
        onSliderLoaded: slide_change,
        onSlideChange: slide_change,
        navPrevSelector: jQuery("#' . $td_unique_id_slide . ' .prevButton"),
        navNextSelector: jQuery("#' . $td_unique_id_slide . ' .nextButton")
        ' . $add_js_resize . '
    });
});
    ';

        if ($is_ajax) {
            $buffy .= '<script>' . $slide_js . '</script>';
        } else {
            td_js_buffer::add_to_footer($slide_js);
        }

        return $buffy;
    }
}
