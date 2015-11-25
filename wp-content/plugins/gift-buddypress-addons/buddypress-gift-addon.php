<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Gift_Buddypress
 *
 * @wordpress-plugin
 * Plugin Name:       Gift Buddypress Addons
 * Plugin URI:        http://wordpress.org
 * Description:       Gift Buddypress Addons provide gift management functionality with buddypress plugin. 
 * Version:           1.0.0
 * Author:            Aiyaz
 * Author URI:        http://ayaz.co.nf
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       gift-buddypress
 * Domain Path:       /languages
 */
if (!class_exists('Gift_Buddypress_Template')) {
    class Gift_Buddypress_Template
    {
        /*************** Initialized Plugin *******************/
		
        public function __construct()
        {
            global $bp;
            $plugin = plugin_basename(__FILE__);
            add_action('bp_setup_nav', array(
                $this,
                'profile_tab_gifts'
            ));	
			add_action('wp_enqueue_scripts', array( $this, 'enque_styles'));
        }
		
		public static function activate()
		{
			// activation code here //
		} 
		
		public function enque_styles() 
		{
			wp_enqueue_style( 'base', plugins_url( '/css/base.css', __FILE__ ) , '', '', 'screen' );
			wp_enqueue_style( 'style', plugins_url( '/css/style.css', __FILE__ ) , '', '', 'screen' );
			wp_enqueue_script( 'Jquery', plugins_url( '/js/jquery-1.9.1.min.js', __FILE__ ), array(), '1.0.0', true );
			wp_enqueue_script( 'modernizr-js', plugins_url( '/js/modernizr.js', __FILE__ ), array(), '', true );
			wp_enqueue_script( 'tab-js', plugins_url( '/js/tabs.js', __FILE__ ), array(), '', true );
		}
		
		/*************** Add Navigation menu *******************/
		
        public function profile_tab_gifts()
        {
            global $bp;
            bp_core_new_nav_item(array(
                'parent_url' => bp_loggedin_user_domain() . '/gifts/',
                'parent_slug' => $bp->profile->slug,
                'default_subnav_slug' => 'send_gift',
                'show_for_displayed_user' => false,
                'name' => 'Gifts',
                'slug' => 'gifts',
                'screen_function' => array(
                    $this,
                    'send_gift_posts'
                ),
                'position' => 40
            ));
            bp_core_new_subnav_item(array(
                'name' => 'Send Gift',
                'slug' => 'send_gift',
                'show_for_displayed_user' => false,
                'parent_url' => bp_loggedin_user_domain() . '/gifts/',
                'parent_slug' => $bp->bp_nav['gifts']['slug'],
                'position' => 10,
                'screen_function' => array(
                    $this,
                    'send_gift_posts'
                )
            ));
            bp_core_new_subnav_item(array(
                'name' => 'Received Gift',
                'slug' => 'received_gift',
                'parent_url' => bp_loggedin_user_domain() . '/gifts/',
                'parent_slug' => $bp->bp_nav['gifts']['slug'],
                'position' => 10,
                'screen_function' => array(
                    $this,
                    'received_gifts_posts'
                )
            ));
        }
		
        public function send_gift_posts()
        {
            add_action('bp_template_content', array(
                $this,
                'send_gifts_content'
            ));
            bp_core_load_template(apply_filters('bp_core_template_plugin', 'members/single/plugins'));
        }
		
        public function received_gifts_posts()
        {
            add_action('bp_template_content', array(
                $this,
                'received_gifts_content'
            ));
            bp_core_load_template(apply_filters('bp_core_template_plugin', 'members/single/plugins'));
        }
		
		
		public function show_gift()
		{
			$args = array(
				'hide_empty'=> 1,
				'orderby' => 'name',
				'order' => 'ASC',
				'taxonomy' => 'gifts',
			);
			
			echo '<ul id="tabs">';
			$count = 0;
			$categories = get_categories($args);
			foreach($categories as $category) { 
				$count++;
				if($count==1) { 
					$activeClass = "active";
				} 
				else{
					$activeClass = "";
				}
				echo '<li class="'.$activeClass.'">'.$category->name .'</li>';
			}
			echo '</ul>';
			
			echo '<ul id="tab">';
			$count = 0;
			foreach($categories as $category) { 
				$the_query = new WP_Query(array (
				'post_type' => 'gift-post',
				'post_status' => 'publish',
				'tax_query' => array(
					array(
						'taxonomy' => 'gifts',
						'field' => 'slug',
						'terms' => $category->slug,
					)
				)
				));
			
				$count++;
				if($count==1) { 
					$activeClass = "active";
				} 
				else{
					$activeClass = "";
				}
				
				if ( $the_query->have_posts() ) {
					
					echo '<li class="'.$activeClass.'">';
					while ( $the_query->have_posts() ) {
						
						
						$the_query->the_post();
						echo '<div class="gift_col" id="'.get_the_ID().'">';
						echo get_the_post_thumbnail( get_the_ID(), 'thumbnail' );
						echo '<p>'.get_the_title().'</p>';
						echo get_the_excerpt();
						echo '</div>';
					}
					echo '</li>';
				} 
				else
				{
					echo '<p> Gift not available! </p>';
				}
			}
			echo '</ul>';
		}
		
        public function send_gifts_content()
        {
			global $bp;
			
			if( isset( $_POST["submitgift"])&& !empty($_POST['post_id']) )
			{
				//echo 'here';
				//print_r($_POST); die;
				$post_id = $_POST['post_id'];
				$sender = $_POST['sender'];
				$reciever = $_POST['reciever'];
				$sender_msg = trim( $_POST['sender_msg'] );
				//$allpoints = $_POST['points'];
				//$points = $allpoints[$post_id]; 
				//echo $points; die;
				update_post_meta( $post_id, 'sender_id', $sender );
				update_post_meta( $post_id, 'reciever_id', $reciever );
				update_post_meta( $post_id, 'sender_msg', $sender_msg );
				//$userid = get_current_user_id();
				//bp_core_add_notification('100', (int)$bp->loggedin_user->id, 'activity', 'activity_viewed');
				//bp_core_add_notification( '100', 1, 'logbooks', 'new_dive' );
				if (function_exists('mycred_add')) {
					mycred_add( 'birthday_present', $sender, $points, 'Sent Gift %plural%!', date( 'y' ) );
					header('Location: '.$_SERVER['REQUEST_URI']);
				}
				
			}
			
			$this->show_gift();
			
			echo '<form name="SendGiftForm" id="SendGiftForm" method="POST" action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" >';
			echo '<input type="hidden" name="sender" value="'.$bp->loggedin_user->id.'">';
			echo '<label>Message :</label> <input type="text" name="sender_msg" id="sender_msg" />';
			echo "<label>Send to : </label>";
			/******* all User List ************/
			if ( bp_has_members() ) : 
				echo '<select name="reciever" required>';
				while ( bp_members() ) : bp_the_member(); 
				echo bp_get_member_user_id();
				if( bp_get_member_user_id() !=  bp_loggedin_user_id() )
				{
					echo "<option value='".bp_get_member_user_id()."'> ";
					echo bp_member_name();
					echo '</option>'; 
				}
				endwhile; 
				echo '</select>';
			endif;
			echo '<input type="hidden" name="post_id" id="post_id" value="123">';
			echo '<input type="submit" name="submitgift" value="Send Gift" class="form_submit">'; 
			echo '</form>';
        }
		
        public function received_gifts_content()
        {
			$args1 = array (
				'post_type'              => 'gift-post',
				'post_status'            => 'publish',
				'order'                  => 'DESC',
				'orderby'                => 'date',
				'meta_query'             => array(
					array(
						'key'       => 'reciever_id',
						'value'     => bp_loggedin_user_id(),
						'compare'   => '=',
					),
				),
			);

			$the_query = new WP_Query( $args1 );
			$html_content = '<div class="gift-container"> <h4>Your Received Gift</h4>';

			if ( $the_query->have_posts() ) {
				$html_content .= '<ul class="received_gifts">';
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					$username = bp_core_get_username(get_post_meta(get_the_ID(), 'sender_id',true));
					$html_content .= '<li class="gift-content">';
					$html_content .= get_the_post_thumbnail( get_the_ID(), 'thumbnail' );
					$html_content .= '<span class="gift-title">'.get_the_title().'</span>';
					$html_content .= '<span class="gift-msg">'. get_post_meta(get_the_ID(), 'sender_msg',true).'</span>';
					$html_content .= '<span class="gift-by"><i>Sent by : </i>'.bp_core_get_userlink( get_post_meta(get_the_ID(), 'sender_id',true) ) .'</span></li>';
				}
				$html_content .= '</ul>';
			
			} else {
				$html_content .= '<p>No Gift Available</p>';
			}
			$html_content .= '</div>';
			echo $html_content;
			wp_reset_postdata();			
        }
    } 
} 

	
require_once(plugin_dir_path( __FILE__ ).'functions.php' );
$post_texonomy = new CustomPostTexonomy();

if (class_exists('Gift_Buddypress_Template'))
{
    /***************** activation & deactivation hooks ******************/
	
    register_activation_hook(__FILE__, array(
        'Gift_Buddypress_Template',
        'activate'
    ));
    register_deactivation_hook(__FILE__, array(
        'Gift_Buddypress_Template',
        'deactivate'
    ));
	
    $gift_buddypress_template = new Gift_Buddypress_Template();
}
?>