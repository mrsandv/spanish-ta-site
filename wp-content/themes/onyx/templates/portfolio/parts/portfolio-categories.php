<?php
global $mkd_options;

$portfolio_info_tag             = 'h6';
$portfolio_info_style           = '';

//set info tag
if (isset($mkd_options['portfolio_info_tag'])) {
    $portfolio_info_tag = $mkd_options['portfolio_info_tag'];
}

//set style for info
if ((isset($mkd_options['portfolio_info_margin_bottom']) && $mkd_options['portfolio_info_margin_bottom'] != '')
    || (isset($mkd_options['portfolio_info_color']) && !empty($mkd_options['portfolio_info_color']))) {

    if (isset($mkd_options['portfolio_info_margin_bottom']) && $mkd_options['portfolio_info_margin_bottom'] != '') {
        $portfolio_info_style .= 'margin-bottom:' . esc_attr($mkd_options['portfolio_info_margin_bottom']) . 'px;';
    }

    if (isset($mkd_options['portfolio_info_color']) && !empty($mkd_options['portfolio_info_color'])) {
        $portfolio_info_style .= 'color:'.esc_attr($mkd_options['portfolio_info_color']).';';
    }

}


$portfolio_hide_categories = "no";
if(isset($mkd_options['portfolio_hide_categories'])) {
	$portfolio_hide_categories = $mkd_options['portfolio_hide_categories'];
}

$portfolio_categories = wp_get_post_terms(get_the_ID(),'portfolio_category');

if(is_array($portfolio_categories) && count($portfolio_categories) && $portfolio_hide_categories != "yes"){

	$portfolio_categories_array = array();
	foreach($portfolio_categories as $portfolio_category) {
		$portfolio_categories_array[] = $portfolio_category->name;
	}

	?>
	<div class="info portfolio_single_categories">
		<<?php echo esc_attr($portfolio_info_tag); ?> class="info_section_title" <?php mkd_inline_style($portfolio_info_style); ?>><?php _e('Category ','mkd'); ?></<?php echo esc_attr($portfolio_info_tag); ?>>
		<p>
			<span class="category">
			<?php echo esc_html(implode(', ', $portfolio_categories_array)); ?>
			</span> <!-- close span.category -->
		</p>
	</div> <!-- close div.info.portfolio-categories -->
<?php } ?>