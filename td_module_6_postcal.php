<?php

class td_module_6_postcal extends td_module {

    function __construct($post) {
        //run the parrent constructor
        parent::__construct($post);
    }

    function render() {
        ob_start();
        ?>

        <div class="<?php echo $this->get_module_classes();?>">

        <?php echo $this->get_image('td_100x70');?>

        <div class="item-details">
            <?php echo $this->get_title();?>
            <div class="td-module-meta-info">
                <?php if (td_util::get_option('tds_category_module_6') == 'yes') { echo $this->get_category(); }?>
                <?php echo $this->get_author();?>
                <?php echo $this->get_date();?>
                <?php //echo $this->get_comments();?>
            </div>
        </div>

        </div>

        <?php return ob_get_clean();
    }

    function get_date($show_stars_on_review = true) {
        $visibility_class = '';
        if (td_util::get_option('tds_m_show_date') == 'hide') {
            $visibility_class = ' td-visibility-hidden';
        }

        $buffy = '';
        if ($this->is_review and $show_stars_on_review === true) {
            //if review show stars
            $buffy .= '<div class="entry-review-stars">';
            $buffy .=  td_review::render_stars($this->td_review);
            $buffy .= '</div>';

        } else {
            if (td_util::get_option('tds_m_show_date') != 'hide') {
                $meta = get_post_meta( $this->post->ID, 'postcal', FALSE );
                sort( $meta );
                $meta = $meta[0];
                $dt = strtotime( $meta );
                $dt = sprintf( '%s, %s', date_i18n( 'l', $dt ), date_i18n( 'j F', $dt ) );
                $buffy .= '<span class="td-post-date">';
                    $buffy .= '<time class="entry-date updated td-module-date' . $visibility_class . '" >' . $dt . '</time>';
                $buffy .= '</span>';
            }
        }

        return $buffy;
    }

	function get_image( $thumbType, $css_image = FALSE ) {
		$image = parent::get_image( $thumbType, $css_image );
		if ( get_post_status( $this->post ) === 'publish' || current_user_can( 'read_post', $this->post->ID ) )
			return $image;
		$div = new HTML_Collection( $image );
		$a = $div->children();
		$img = $a->children();
		$div->text( $img->serialize() );
		$image = $div->serialize();
		return $image;
	}

	function get_title( $cut_at = '' ) {
		$title = parent::get_title( $cut_at );
		if ( get_post_status( $this->post ) === 'publish' || current_user_can( 'read_post', $this->post->ID ) )
			return $title;
		$h3 = new HTML_Collection( $title );
		$a = $h3->children();
		$text = $a->children();
		$h3->text( $text->serialize() );
		$title = $h3->serialize();
		return $title;
	}
}
