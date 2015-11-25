<?php
/*
Plugin Name: Posts By Taxonomy Widget
Plugin URI: http://pippinsplugins.com/posts-by-taxonomy-widget-plugin-free
Description: Provides a widget that allows you to display a list of taxonomy terms, and the posts in those terms
Version: 1.0.3
Author: Pippin Williamson
Author URI: http://pippinsplugins.com
*/


/**
 * Posts By Taxonomy Widget Widget Class
 */
class pbtw_wrapper extends WP_Widget {


    /** constructor */
    function pbtw_wrapper() {
        parent::WP_Widget(false, $name = 'Posts by Taxonomy');
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {
        extract( $args );
        $title 		= apply_filters( 'widget_title', $instance['title'] );
        $tax 		= isset( $instance['tax'] ) ? $instance['tax'] : 0;
		$cpost 		= isset( $instance['cpost'] ) ? $instance['cpost'] : '';
        $number 	= isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        ?>
              <?php echo $before_widget; ?>
                  <?php if ( $title ) echo $before_title . $title . $after_title; ?>
					<ul class="posts-by-taxonomy-list">
						<?php
						$terms = get_terms( $tax );
						if( $terms ) {
							foreach( $terms as $term ) {
								echo '<li class="posts-by-taxonomy-term ' . $term->slug . '">' . $term->name;
									echo '<ul class="posts-by-taxonomy-post-list">';
										$tax_query = array(
											array(
												'taxonomy' => $tax,
												'terms' => $term->slug,
												'field' => 'slug',
											)
										);
										$term_post_args = array( 'post_type'=>$cpost,'posts_per_page' => $number, 'tax_query' => $tax_query );
										$term_posts = get_posts($term_post_args);
										foreach ($term_posts as $term_post) {
											echo '<li><a href="' . get_permalink($term_post->ID) . '">' . get_the_title($term_post->ID) . '</a></li>';
										}
									echo '</ul>';
								echo '</li>';
							}
						}
						?>
					</ul>
              <?php echo $after_widget;
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
		$instance 				= $old_instance;
		$instance['title'] 		= isset( $new_instance['title'] ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['tax'] 		= isset( $new_instance['tax'] ) ? strip_tags( $new_instance['tax'] ) : 0;
		$instance['cpost'] = isset( $instance['cpost'] ) ? strip_tags( $new_instance['cpost'] ): '';
		$instance['number'] 	= isset( $new_instance['number'] ) ? strip_tags( $new_instance['number'] ) : 5;
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {

        $title 			= isset( $instance['title'] )  ? esc_attr( $instance['title'] ) : '';
        $taxonomy		= isset( $instance['tax'] )    ? esc_attr( $instance['tax'] )   : '';
		$cpost		= isset( $instance['cpost'] )    ? esc_attr( $instance['cpost'] )   : '';
        $number			= isset( $instance['number'] ) ? absint( $instance['number'] )  : 5;
        ?>
        <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title:'); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
		<p>
			<label for="<?php echo $this->get_field_id('tax'); ?>"><?php _e('Select Taxonomy'); ?></label>
			<select name="<?php echo $this->get_field_name('tax'); ?>" id="<?php echo $this->get_field_id('tax'); ?>" class="widefat extra-options-select">
				<?php
				$taxes = get_taxonomies( '','names' );
				foreach ( $taxes as $tax ) {
					echo '<option id="' . esc_attr( $tax ) . '"' . selected( $tax, $taxonomy, false ) . '>' . $tax . '</option>';
				}
				?>
			</select>
        </p>
		<p>
			<label for="<?php echo $this->get_field_id('cpost'); ?>"><?php _e('Select Post Type'); ?></label>
			<select name="<?php echo $this->get_field_name('cpost'); ?>" id="<?php echo $this->get_field_id('tax'); ?>" class="widefat extra-options-select">
				<?php
				$cposts =  get_post_types( '', 'names' );
				foreach ( $cposts as $tax ) {
					echo '<option id="' . esc_attr( $tax ) . '"' . selected( $tax, $cpost, false ) . '>' . $tax . '</option>';
				}
				?>
			</select>
        </p>
		<p>
          <input id="<?php echo $this->get_field_id('number'); ?>" class="small-text" name="<?php echo $this->get_field_name('number'); ?>" type="number" min="1" step="1" value="<?php echo esc_attr( $number ); ?>" />
          <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Maximum to show per term'); ?></label>
        </p>
        <?php
    }
	
} 
add_action('widgets_init', create_function('', 'return register_widget("pbtw_wrapper");'));