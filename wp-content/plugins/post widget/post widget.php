<?php
/**
 * @package test
 * @version 1.0
 */
/*
Plugin Name: post widget

*/

// Creating the widget 
class wpb_widget extends WP_Widget {

function __construct() {
parent::__construct(
// Base ID of your widget
'wpb_widget', 

// Widget name will appear in UI
__('Display Post Widget', 'wpb_widget_domain'), 

// Widget description
array( 'description' => __( 'Display All Post ', 'wpb_widget_domain' ), ) 
);
}

// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
// before and after widget arguments are defined by themes
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];

// This is where you run the code and display the output
echo __( '<u>Your post</u>', 'wpb_widget_domain' );
echo __('<br/>','wpb_widget_domain');

$option_data = get_option('widget_wpb_widget');

$title = $option_data[2]['title']; // $title =
$custom_post = $option_data[2]['custom_post']; // $custom_post =
$cat = $option_data[2]['cat']; // $cat =
$sort_by = $option_data[2]['sort_by']; // $sort_by =

// rrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr

// WP_Query arguments
$args = array (
	'post_type'              => array( $custom_post ),
	'category_name'          => $cat,
	'order'                  => 'DESC',
	'orderby'                => $sort_by,
);

// The Query
$query = new WP_Query( $args );

// The Loop
if ( $query->have_posts() ) {
	while ( $query->have_posts() ) {
		$query->the_post();
		echo "<hr/>";
		the_title();
		echo "<br/>";
		the_content('Read more...');
		echo "<br/>";
		echo "<hr/>";
	}
} else {
	// no posts found
}

// Restore original Post Data
wp_reset_postdata();


// rrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
//echo $args['after_widget'];
}
		
// Widget Backend 
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'New title', 'wpb_widget_domain' );
}
// Widget admin form
?>
<!-- title -->
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<!-- Custom post type -->
<p>
<label for="<?php echo $this->get_field_id( 'custom_post' ); ?>"><?php _e( 'Custom Post:' ); ?></label> 
<?php

$post_types = get_post_types( '', 'names' ); 
?>
<select id="<?php echo $this->get_field_id('custom_post'); ?>" name="<?php echo $this->get_field_name('custom_post'); ?>" class="widefat" style="width:100%;">
<?php 
foreach ( $post_types as $post_type ){
echo "<option" .selected( $instance['custom_post'], 'Option 1')." value=".$post_type.">";
echo $post_type;
echo "</option>"; 
}
?>
</select>
</p>
<!-- Texonomy Type -->
<p>
<label for="">Texonomy Type</label> 
<?php 
$args = array(
	'type'                     => 'post',
	'child_of'                 => 0,
	'parent'                   => '',
	'orderby'                  => 'name',
	'order'                    => 'ASC',
	'hide_empty'               => 1,
	'hierarchical'             => 1,
	'exclude'                  => '',
	'include'                  => '',
	'number'                   => '',
	'taxonomy'                 => 'category',
	'pad_counts'               => false 
); 
$categories = get_categories( $args ); 
?>
<select id="<?php echo $this->get_field_id('posttype'); ?>" name="<?php echo $this->get_field_name('cat'); ?>" class="widefat" style="width:100%;">
<?php 
foreach ($categories as $key => $value){
echo "<option" .selected( $instance['posttype'], 'Option 1')." value=".$value->slug.">";
echo $value->name;
echo "</option>"; 
}
?>
</select>
</p>

<p>
<label for="">Sort By </label>
<select id="<?php echo $this->get_field_id('sort_by'); ?>" name="<?php echo $this->get_field_name('sort_by'); ?>" class="widefat" style="width:100%;">
<option "<?php //selected( $instance['sort_by'], ''); ?>" value="date">Date - default</option>
<option "<?php //selected( $instance['sort_by'], ''); ?>" value="id">ID</option>
<option "<?php //selected( $instance['sort_by'], ''); ?>" value="title">Title</option>
</select>
</p>

<p>
<label for="">No. of post </label>
<select id="<?php echo $this->get_field_id('no_of_post'); ?>" name="<?php echo $this->get_field_name('no_of_post'); ?>" class="widefat" style="width:100%;">
<option "<?php //selected( $instance['no_of_post'], ''); ?>" value="1">1 - default</option>
<option "<?php //selected( $instance['no_of_post'], ''); ?>" value="2">2</option>
<option "<?php //selected( $instance['no_of_post'], ''); ?>" value="3">3</option>
<option "<?php //selected( $instance['no_of_post'], ''); ?>" value="4">4</option>
<option "<?php //selected( $instance['no_of_post'], ''); ?>" value="5">5</option>
</select>
</p>


<?php 
}
	
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
$instance['custom_post'] = ( ! empty( $new_instance['custom_post'] ) ) ? strip_tags( $new_instance['custom_post'] ) : '';
$instance['cat'] = ( ! empty( $new_instance['cat'] ) ) ? strip_tags( $new_instance['cat'] ) : '';
$instance['sort_by'] = ( ! empty( $new_instance['sort_by'] ) ) ? strip_tags( $new_instance['sort_by'] ) : '';
$instance['no_of_post'] = ( ! empty( $new_instance['no_of_post'] ) ) ? strip_tags( $new_instance['no_of_post'] ) : '';
return $instance;
}
} // Class wpb_widget ends here

// Register and load the widget
function wpb_load_widget() {
	register_widget( 'wpb_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );


?>