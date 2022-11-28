<?php

class Mikado_Latest_Posts_Menu extends WP_Widget {
	private $params;
	public function __construct() {
		parent::__construct(
			'mkd_latest_posts_menu_widget', // Base ID
			'Mikado Latest Posts Slider', // Name
			array( 'description' => __( 'Display posts from your blog, for use in dropdown menus', 'mkd' ), ) // Args
		);

		$this->setParams();
	}

	protected function setParams() {
		$this->params = array(
			array(
				'name' => 'number',
				'type' => 'textfield',
				'title' => 'Number of posts'
			),
			array(
				'name' => 'order_by',
				'type' => 'dropdown',
				'title' => 'Order By',
				'options' => array(
					'title' => 'Title',
					'date' => 'Date'
				)
			),
			array(
				'name' => 'order',
				'type' => 'dropdown',
				'title' => 'Order',
				'options' => array(
					'ASC' => 'ASC',
					'DESC' => 'DESC'
				)
			),
			array(
				'name' => 'show_category',
				'type' => 'dropdown',
				'title' => 'Show Category',
				'options' => array(
					'1' => 'Yes',
					'0' => 'No'
				)
			),
			array(
				'name' => 'category',
				'type' => 'textfield',
				'title' => 'Category Slug'
			),
			array(
				'name' => 'show_author',
				'type' => 'dropdown',
				'title' => 'Show Author',
				'options' => array(
					'1' => 'Yes',
					'0' => 'No'
				)
			),
		);
	}


	public function getParams() {
		return $this->params;
	}

	public function widget( $args, $instance ) {
		global $post;
		extract( $args );
		//var_dump($instance);exit;
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		if ( empty( $instance['number'] ) || ! $number = absint( $instance['number'] ) ) {
 			$number = 10;
		}
		
		$show_category  = isset( $instance['show_category'] ) ? esc_attr( $instance['show_category'] ) : '';
		$show_author  = isset( $instance['show_author'] ) ? esc_attr( $instance['show_author'] ) : '';
		
		if ( empty( $instance['category'] ) || $instance['category'] == '' ) {
 			$category = '';
 		}
 		else {
 			$category = $instance['category'];
 		}
		if ( empty( $instance['order'] ) || $instance['order'] == '' ) {
 			$order = 'DESC';
 		}
 		else {
 			$order = $instance['order'];
 		}
		if ( empty( $instance['order_by'] ) || $instance['order_by'] == '' ) {
 			$orderby = 'date';
 		}
 		else {
 			$orderby = $instance['order_by'];
 		}
		echo '<div class="widget widget_mkd_latest_posts_menu_widget">';  ?>
		
		
		<?php
				global $mkd_options;
				$blog_hide_comments = "";
				if (isset($mkd_options['blog_hide_comments'])) {
					$blog_hide_comments = $mkd_options['blog_hide_comments'];
				}
				$args = array(
					'order'=>$order, 
					'orderby'=>$orderby,
					'category_name'=> $category,
					'posts_per_page'=>$number // Number of related posts to display.
				);
 				$related_query = new WP_Query($args);
				if ($related_query->have_posts()) {
			?>
			
			<div class="flexslider widget_flexslider">
				<ul class="slides">
            <?php
            while ($related_query->have_posts()) : $related_query->the_post();
            ?>
				<li>
					<a href="<?php the_permalink() ?>"><?php echo get_the_post_thumbnail(get_the_id(),'menu-featured-post'); ?></a>
					<h3><a href="<?php the_permalink() ?>" ><?php the_title();?> </a></h3>
					<span class="menu_recent_post_text">
						<?php if($show_category == 1){
						 _e('Posted in','mkd'); ?> <?php the_category(', ');
						}?>
						<?php if($show_author == 1){
						 _e(' by','mkd'); ?> <a class="post_author" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author_meta('display_name'); ?></a>
						<?php }?>
					</span>
				</li>
            <?php
            endwhile;
            ?>
				</ul>
			</div>
        
 
<?php }
    wp_reset_query(); 

?>
	<?php	echo '</div>';
	}

	
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = (int) $new_instance['number'];
		$instance['show_category'] = (int) ($new_instance['show_category']);
		$instance['category'] = strip_tags($new_instance['category']);	
		$instance['show_author'] = (int)($new_instance['show_author']);
		$instance['order'] = strip_tags( $new_instance['order'] );
		$instance['order_by'] = strip_tags( $new_instance['order_by'] );
		return $instance;
	}

	public function form( $instance ) {
		foreach ($this->params as $param_array) {
			$param_name = $param_array['name'];
			${$param_name} = isset( $instance[$param_name] ) ? esc_attr( $instance[$param_name] ) : '';
		}

		foreach ($this->params as $param) {
			switch($param['type']) {
				case 'textfield':
					?>
					<p>
                        <label for="<?php echo esc_attr($this->get_field_id( $param['name'] )); ?>"><?php echo
                            esc_html($param['title']); ?></label>
						<input class="widefat" id="<?php echo esc_attr($this->get_field_id( $param['name'] )); ?>" name="<?php echo esc_attr($this->get_field_name( $param['name'] )); ?>" type="text" value="<?php echo esc_attr( ${$param['name']} ); ?>" />
					</p>
					<?php
					break;
				case 'dropdown':
					?>
					<p>
                        <label for="<?php echo esc_attr($this->get_field_id( $param['name'] )); ?>"><?php echo
                            esc_html($param['title']); ?></label>
						<?php if(isset($param['options']) && is_array($param['options']) && count($param['options'])) { ?>
							<select class="widefat" name="<?php echo esc_attr($this->get_field_name( $param['name'] )); ?>" id="<?php echo esc_attr($this->get_field_id( $param['name'] )); ?>">
								<?php foreach ($param['options'] as $param_option_key => $param_option_val) {
									$option_selected = '';
									if(${$param['name']} == $param_option_key) {
										$option_selected = 'selected';
									}
									?>
									<option <?php echo esc_attr($option_selected); ?> value="<?php echo esc_attr($param_option_key); ?>"><?php echo esc_attr($param_option_val); ?></option>
								<?php } ?>
							</select>
						<?php } ?>
					</p>

					<?php
					break;
			}
		}
	}

} 
add_action('widgets_init', function () {
    register_widget( "Mikado_Latest_Posts_Menu" );
});