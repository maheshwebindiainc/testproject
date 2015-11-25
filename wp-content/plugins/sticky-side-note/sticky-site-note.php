<?php
/*
Plugin Name: Sticky Side Note
Plugin URI: http://resumedirectory.in
Description: Display Sticky note on website, You can add custom messages along with note. 
Version: 1.0
Author: Aiyaz Khorajia	
Author URI: http://resumedirectory.in
License: GPLv2 or later
*/


if (!class_exists('StickyNote')) {
    class StickyNote
    {
		private $options;
		
		public function __construct()
        {
            $plugin = plugin_basename(__FILE__);
			add_action('wp_enqueue_scripts', array( $this, 'enque_styles'));			
			$this->show_sticky_image();
			add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
			add_action( 'admin_init', array( $this, 'page_init' ) );
        }
		
		public function enque_styles() 
		{
			wp_enqueue_style( 'main', plugins_url( '/css/main.css', __FILE__ ) , '', '', 'screen' );
			wp_enqueue_script( 'main-js', plugins_url( '/js/main.js', __FILE__ ), array(), '', true );
		}
		public function show_sticky_image(){
			if( !is_admin()){
				echo '<img src="'.plugins_url( '/images/note.png', __FILE__ ).'" class="sticky_note">';
			}
		}
		
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'Stickynote Settings', 
            'manage_options', 
            'my-setting-admin', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'my_option_name' );
        ?>
        <div class="wrap">
            <h2>Stickynote Settings</h2>           
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'my_option_group' );   
                do_settings_sections( 'my-setting-admin' );
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'my_option_group', // Option group
            'my_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'My Custom Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'my-setting-admin' // Page
        );  

        add_settings_field(
            'id_number', // ID
            'ID Number', // Title 
            array( $this, 'id_number_callback' ), // Callback
            'my-setting-admin', // Page
            'setting_section_id' // Section           
        );      

        add_settings_field(
            'title', 
            'Title', 
            array( $this, 'title_callback' ), 
            'my-setting-admin', 
            'setting_section_id'
        );      
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['id_number'] ) )
            $new_input['id_number'] = absint( $input['id_number'] );

        if( isset( $input['title'] ) )
            $new_input['title'] = sanitize_text_field( $input['title'] );

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your settings below:';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function id_number_callback()
    {
        printf(
            '<input type="text" id="id_number" name="my_option_name[id_number]" value="%s" />',
            isset( $this->options['id_number'] ) ? esc_attr( $this->options['id_number']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function title_callback()
    {
        printf(
            '<input type="text" id="title" name="my_option_name[title]" value="%s" />',
            isset( $this->options['title'] ) ? esc_attr( $this->options['title']) : ''
        );
    }
		
	}
}

    $stickynote = new StickyNote();
	
/*	add_action('admin_menu', 'stickynote_setting_menu');

function stickynote_setting_menu() {
	add_submenu_page( 'options-general.php', 'Stickynote Settings', 'Stickynote Settings', 'manage_options', 'stickynote-submenu-page', 'stickynote_submenu_page_callback' );
}

function stickynote_submenu_page_callback() {
	
	echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';
		echo '<h2>Stickynote Settings</h2>';
	echo '</div>';

?>
<div class="wrap">
<h2>Your Plugin Name</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'my-cool-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'my-cool-plugin-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">New Option Name</th>
        <td><input type="text" name="new_option_name" value="<?php echo esc_attr( get_option('new_option_name') ); ?>" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Some Other Option</th>
        <td><input type="text" name="some_other_option" value="<?php echo esc_attr( get_option('some_other_option') ); ?>" /></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Options, Etc.</th>
        <td><input type="text" name="option_etc" value="<?php echo esc_attr( get_option('option_etc') ); ?>" /></td>
        </tr>
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php */
	

