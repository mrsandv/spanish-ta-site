<?php

//Disable the default WooCommerce stylesheet.
if ( version_compare( WOOCOMMERCE_VERSION, "2.1" ) >= 0 ) {
    add_filter( 'woocommerce_enqueue_styles', '__return_false' );
} else {
    define( 'WOOCOMMERCE_USE_CSS', false );
}

if(!function_exists('mkd_woo_related_products_args')) {
    /**
     * Function that sets number of displayed related products. Hooks to woocommerce_output_related_products_args filter
     * @param $args array array of args for the query
     * @return mixed array of changed args
     */
    function mkd_woo_related_products_args( $args ) {
        global $mkd_options;
        if(isset($mkd_options['woo_products_list_number']) && $mkd_options['woo_products_list_number'] != ''){
            switch($mkd_options['woo_products_list_number']){
                case('columns-3') :
                    $args['posts_per_page'] = 3;
                    break;
                case('columns-4') :
                    $args['posts_per_page'] = 4;
                    break;
                default :
                    $args['posts_per_page'] = 3;
            }
        }
        else {
            $args['posts_per_page'] = 3;
        }
        return $args;
    }

    add_filter( 'woocommerce_output_related_products_args', 'mkd_woo_related_products_args' );
}

// Define number of products per page.
if(!function_exists('mkd_woo_product_per_page')) {
    /**
     * Function that sets number of products per page. Default is 12
     * @return int number of products to be shown per page
     */
    function mkd_woo_product_per_page() {
        global $mkd_options;

        $products_per_page = 12;
        if(isset($mkd_options['woo_products_per_page']) && $mkd_options['woo_products_per_page']) {
            $products_per_page = $mkd_options['woo_products_per_page'];
        }

        return $products_per_page;
    }

    add_filter('loop_shop_per_page', 'mkd_woo_product_per_page', 20);
}

// Hook in
add_filter('woocommerce_checkout_fields', 'mkd_custom_override_checkout_fields');

/**
 * Remove add to cart function from woocommerce_after_shop_loop_item_title hook
 * and hook it in mkd_woocommerce_after_product_image
 */
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
add_action('mkd_woocommerce_after_product_image', 'woocommerce_template_loop_add_to_cart', 10);

/**
 * Remove related products from woocommerce_after_single_product_summary hook
 * and hook it in mkd_woocommerce_related_products.With this action(mkd_woocommerce_related_products)
 *  related products now can be hooked separately from woocommerce tabs(accordions)
 */
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
add_action('mkd_woocommerce_related_products', 'woocommerce_output_related_products', 5);


/**
 * Overrides placeholder values for checkout fields
 * @param array all checkout fields
 * @return array checkout fields with overriden values
 */
function mkd_custom_override_checkout_fields($fields) {
    //billing fields
    $args_billing = array(
        'first_name' => __('First name','mkd'),
        'last_name'  => __('Last name','mkd'),
        'company'    => __('Company name','mkd'),
        'address_1'  => __('Address','mkd'),
        'email'      => __('Email','mkd'),
        'phone'      => __('Phone','mkd'),
        'postcode'   => __('Postcode / ZIP','mkd'),
        'city'       => __('City','mkd'),
        'state'      => __('State','mkd')
    );
    
    //shipping fields
    $args_shipping = array(
        'first_name' => __('First name','mkd'),
        'last_name'  => __('Last name','mkd'),
        'company'    => __('Company name','mkd'),
        'address_1'  => __('Address','mkd'),
        'postcode'   => __('Postcode / ZIP','mkd')
    );
    
    //override billing placeholder values
    foreach ($args_billing as $key => $value) {
        $fields["billing"]["billing_{$key}"]["placeholder"] = $value;
    }
    
    //override shipping placeholder values
    foreach ($args_shipping as $key => $value) {
        $fields["shipping"]["shipping_{$key}"]["placeholder"] = $value;
    }

    return $fields;
}

// Adds theme support for woocommerce 
add_theme_support('woocommerce');

if (!function_exists('mkd_woocommerce_content')){

    /**
     * Output WooCommerce content.
     *
     * This function is only used in the optional 'woocommerce.php' template
     * which people can add to their themes to add basic woocommerce support
     * without hooks or modifying core templates.
     *
     * @access public
     * @return void
     */
    function mkd_woocommerce_content() {

        if ( is_singular( 'product' ) ) {

            while ( have_posts() ) : the_post();

                wc_get_template_part( 'content', 'single-product' );

            endwhile;

        } else {

            ?>

            <?php do_action( 'woocommerce_archive_description' ); ?>

            <?php if ( have_posts() ) : ?>

                <?php do_action('woocommerce_before_shop_loop'); ?>

                <?php woocommerce_product_loop_start(); ?>

                    <?php woocommerce_product_subcategories(); ?>

                    <?php while ( have_posts() ) : the_post(); ?>

                        <?php wc_get_template_part( 'content', 'product' ); ?>

                    <?php endwhile; // end of the loop. ?>

                <?php woocommerce_product_loop_end(); ?>

                <?php do_action('woocommerce_after_shop_loop'); ?>

            <?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

                <?php wc_get_template( 'loop/no-products-found.php' ); ?>

            <?php endif;

        }
    }
}
if ( ! function_exists( 'mkd_woocommerce_output_product_data_tabs' ) ) {

	/**
	 * Output the product tabs.
	 *
	 * @access public
	 * @subpackage	Product/Tabs
	 * @return void
	 */
	function mkd_woocommerce_output_product_data_tabs() {
		wc_get_template( 'single-product/tabs/tabs.php' );
                echo '</div>';
	}
}


if(!function_exists('mkd_woocommerce_change_actions_priorities')) {
    /**
     * Function that changes woocommerce actions priorities.
     * Used in product listing to put product rating bellow product price
     */
    function mkd_woocommerce_change_actions_priorities() {
        $actions = array(
            array(
                'tag' => 'woocommerce_after_shop_loop_item_title',
                'action' => 'woocommerce_template_loop_price',
                'priority' => 10,
                'priority_to_set' => 10
            ),
            array(
                'tag' => 'woocommerce_after_shop_loop_item_title',
                'action' => 'woocommerce_template_loop_rating',
                'priority' => 5,
                'priority_to_set' => 11
            )
        );
        
        foreach($actions as $action) {
            //actions which priorities needs to be changed
            remove_action($action['tag'], $action['action'], $action['priority']);
            
            //new priorities
            add_action($action['tag'], $action['action'], $action['priority_to_set']);
        }
    }
    
    add_action('woocommerce_change_priorities', 'mkd_woocommerce_change_actions_priorities');
    do_action('woocommerce_change_priorities');
}

add_filter( 'get_product_search_form' , 'mkd_woo_product_searchform');

/**
 * woo_custom_product_searchform
 *
 * @access      public
 * @since       1.0
 * @return      void
 */
function mkd_woo_product_searchform($form) {

    $form = '<form role="search" method="get" id="searchform" action="' . esc_url( home_url( '/'  ) ) . '">
		<div>
			<label class="screen-reader-text" for="s">' . __( 'Search for:', 'woocommerce' ) . '</label>
			<input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="' . __( 'Search Products', 'woocommerce' ) . '" />
			<input type="submit" id="searchsubmit" value="&#xf002" />
			<input type="hidden" name="post_type" value="product" />
		</div>
	</form>';

    return $form;

}

if(!function_exists('mkd_woocommerce_share')) {
    function mkd_woocommerce_share() {
        global $mkd_options;

        $product_show_social_share = "no";
        if (isset($mkd_options['enable_social_share'])&& ($mkd_options['enable_social_share']) =="yes"){
            if (isset($mkd_options['post_types_names_product'])&& $mkd_options['post_types_names_product'] =="product"){
                if (isset($mkd_options['woo_product_single_show_social_share'])) {
                    $product_show_social_share = $mkd_options['woo_product_single_show_social_share'];

                    $product_social_share_type = "dropdown";
                    if(isset($mkd_options['woo_product_single_select_share_option'])){
                        $product_social_share_type = $mkd_options['woo_product_single_select_share_option'];
                    }
                }
            }
        }
        if($product_show_social_share == 'yes'){
            if($product_social_share_type == 'dropdown'){
                if(do_shortcode('[no_social_share]') != ""){
                    echo '<span class="socail_share_title">Share: </span>';
                    echo do_shortcode('[no_social_share]'); // XSS OK
                }
            }
            elseif($product_social_share_type == 'list'){
                if(do_shortcode('[no_social_share_list]') != ""){
                    echo '<div class="social_share_list_holder">';
                    echo do_shortcode('[no_social_share_list]'); // XSS OK
                    echo '</div>'; // close social_share_list_holder
                }
            }
        }

    }

    add_action('woocommerce_product_meta_end', 'mkd_woocommerce_share');
}

/* Removes double links around photos on lists and related items */
remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);