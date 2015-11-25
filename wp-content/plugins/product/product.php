<?php 
/**
 * @package Product
 */
/*
Plugin Name: Product
Plugin URI: 
Description: Product Description
Author: Mahesh Patel
Author URI: 
License: GPLv2 or later
Text Domain: Product
*/

global $jal_db_version;
$jal_db_version = '1.0';

function jal_install() {
    global $wpdb;
    global $jal_db_version;

    $table_name = $wpdb->prefix . 'product';
    $table_cat = $wpdb->prefix . 'category';
    $charset_collate = $wpdb->get_charset_collate();    

    $sql = "CREATE TABLE $table_name (
        id int(9) NOT NULL AUTO_INCREMENT,
        cat_name int(9) NOT NULL,
        name varchar(255) NOT NULL,
        description varchar(255) NOT NULL,
        UNIQUE KEY id (id)
    ) $charset_collate;  
    CREATE TABLE $table_cat (
        id int(9) NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        UNIQUE KEY id (id)
    ) $charset_collate;";    
    

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
    
    add_option( 'jal_db_version', $jal_db_version );
        
}
register_activation_hook( __FILE__, 'jal_install' );

function jal_Uninstall() {
    
        global $wpdb;
        $table_name = $wpdb->prefix . 'product';
        $table_cat = $wpdb->prefix . 'category';        
        $wpdb->query("DROP TABLE IF EXISTS $table_name");
        $wpdb->query("DROP TABLE IF EXISTS $table_cat");    
}
register_deactivation_hook( __FILE__, 'jal_Uninstall' );


add_action( 'admin_menu', 'custome_menu' );


function custome_menu(){
  add_menu_page('Theme page title', 'Products', 'manage_options', 'theme-options', 'product_tab');        
  add_submenu_page( 'theme-options', 'Title', 'Category', 'manage_options', 'theme-op-settings', 'custome_category');
  add_submenu_page( 'theme-options', 'title', 'Add Products', 'manage_options', 'theme-op-faq', 'custome_product');
}

function wps_prod_func(){
               
}
function custome_category(){
                echo '<h2>Product Category</h2>';  ?>
                <form method="post"  action="#">
                Category Name<input type="text" value="" name="cat_name" id="cat_name" required><br>
                <input type="submit"  name="submit"  id="submit">            
                </form>
            <?php 
                if($_POST['submit']){
            
                global $wpdb;
                $table_name = $wpdb->prefix . "category";
                $wpdb->insert( $table_name, array( 'name' => $_POST['cat_name'] ) );
                            
                }  
                
                global $wpdb;
                $table_cat = $wpdb->prefix . 'category';
                $category = $wpdb->get_results( "SELECT * FROM $table_cat");
                echo "<h2>Category List</h2><table  border='1'><tr><th>ID</th><th>Category Name</th></tr>";
                foreach($category as $category)
                {        
                    echo "<tr><td>" . $category->id ."</td><td>".$category->name ."</td></tr>";
                }
                
                echo "</table>";

}

function custome_product(){
                global $wpdb;
                echo '<h2>Add Products</h2>';  
                echo '<form method="post" name="pro"  action="#">';
                echo 'Product Name:<input type="text" name="product_name" id="product_name" value="">';
                
                $table_name = $wpdb->prefix .'category';
                $categories = $wpdb->get_results( "SELECT * FROM $table_name");
                echo 'Category <select name="pro_cat">';    
                
                foreach($categories as $categories){
                echo '<option value="'.$categories->name .'">'.$categories->name .'</option>';
                 }
                echo '</select>
                Description:<input type="text" name="description" id="description"><br>
                <input type="submit" name="submit" id="submit">
                </form>';
                if($_POST['submit']){                            
                global $wpdb;
                $table_name_pro = $wpdb->prefix ."product";				
                $wpdb->insert( $table_name_pro, array( 'name' => $_POST['product_name'] ,'cat_name' => $_POST['pro_cat'],'description' => $_POST['description']) );
                
                } 
                
                echo '<h2>Product Listting</h2><table border="1"><tr><th>ID</th><th>Product Name</th><th>Category</th><th>Description</th></tr>';
        
                $table_pro = $wpdb->prefix .'product';
                $table_pro_d = $wpdb->get_results( "SELECT * FROM $table_pro");
                
                foreach($table_pro_d as $table_pro_d)
                { 
                echo '<tr><td>'. $table_pro_d->id .'</td><td>'.$table_pro_d->name .'</td><td>'.$table_pro_d->cat_name.'</td><td>'.$table_pro_d->description.'</td></tr>';
                }
                echo '</table>';
				
			/*	?>
				
				
				<table class="widefat fixed" cellspacing="0">
    <thead>
    <tr>

            <th id="cb" class="manage-column column-cb check-column" scope="col"></th> // this column contains checkboxes
            <th id="columnname" class="manage-column column-columnname" scope="col"></th>
            <th id="columnname" class="manage-column column-columnname num" scope="col"></th> // "num" added because the column contains numbers

    </tr>
    </thead>

    <tfoot>
    <tr>

            <th class="manage-column column-cb check-column" scope="col"></th>
            <th class="manage-column column-columnname" scope="col"></th>
            <th class="manage-column column-columnname num" scope="col"></th>

    </tr>
    </tfoot>

    <tbody>
        <tr class="alternate">
            <th class="check-column" scope="row"></th>
            <td class="column-columnname"></td>
            <td class="column-columnname"></td>
        </tr>
        <tr>
            <th class="check-column" scope="row"></th>
            <td class="column-columnname"></td>
            <td class="column-columnname"></td>
        </tr>
        <tr class="alternate" valign="top"> // this row contains actions
            <th class="check-column" scope="row"></th>
            <td class="column-columnname">
                <div class="row-actions">
                    <span><a href="#">Action</a> |</span>
                    <span><a href="#">Action</a></span>
                </div>
            </td>
            <td class="column-columnname"></td>
        </tr>
        <tr valign="top"> // this row contains actions
            <th class="check-column" scope="row"></th>
            <td class="column-columnname">
                <div class="row-actions">
                    <span><a href="#">Action</a> |</span>
                    <span><a href="#">Action</a></span>
                </div>
            </td>
            <td class="column-columnname"></td>
        </tr>
    </tbody>
</table>
				
				
				<?php */
				



}    


/*

// Register Custom Taxonomy
function taxonomy_product() {

    $labels = array(
        'name'                       => _x( 'Categories', 'Taxonomy General Name', 'text_domain' ),
        'singular_name'              => _x( 'Category', 'Taxonomy Singular Name', 'text_domain' ),
        'menu_name'                  => __( 'Categories', 'text_domain' ),
        'all_items'                  => __( 'All Items', 'text_domain' ),
        'parent_item'                => __( 'Parent Item', 'text_domain' ),
        'parent_item_colon'          => __( 'Parent Item:', 'text_domain' ),
        'new_item_name'              => __( 'New Item Name', 'text_domain' ),
        'add_new_item'               => __( 'Add New Item', 'text_domain' ),
        'edit_item'                  => __( 'Edit Item', 'text_domain' ),
        'update_item'                => __( 'Update Item', 'text_domain' ),
        'view_item'                  => __( 'View Item', 'text_domain' ),
        'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
        'add_or_remove_items'        => __( 'Add or remove items', 'text_domain' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),        
        'search_items'               => __( 'Search Items', 'text_domain' ),
        'not_found'                  => __( 'Not Found', 'text_domain' ),
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
    );
    register_taxonomy( 'taxonomy_product', array( 'post_product' ), $args );

}
add_action( 'init', 'taxonomy_product', 0 );

// Register Custom Post Type
function custom_product() {

    $labels = array(
        'name'                => _x( 'Products', 'Post Type General Name', 'text_domain' ),
        'singular_name'       => _x( 'Product', 'Post Type Singular Name', 'text_domain' ),
        'menu_name'           => __( 'Products', 'text_domain' ),
        'name_admin_bar'      => __( 'Products', 'text_domain' ),
        'parent_item_colon'   => __( 'Parent Item:', 'text_domain' ),
        'all_items'           => __( 'All Items', 'text_domain' ),
        'add_new_item'        => __( 'Add New Item', 'text_domain' ),
        'add_new'             => __( 'Add New', 'text_domain' ),
        'new_item'            => __( 'New Item', 'text_domain' ),
        'edit_item'           => __( 'Edit Item', 'text_domain' ),
        'update_item'         => __( 'Update Item', 'text_domain' ),
        'view_item'           => __( 'View Item', 'text_domain' ),
        'search_items'        => __( 'Search Item', 'text_domain' ),
        'not_found'           => __( 'Not found', 'text_domain' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'text_domain' ),
    );
    $args = array(
        'label'               => __( 'Product', 'text_domain' ),
        'description'         => __( 'Products Description', 'text_domain' ),
        'labels'              => $labels,
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'trackbacks', 'revisions', 'custom-fields', 'page-attributes', 'post-formats', ),
        'taxonomies'          => array('taxonomy_product' ),
        'hierarchical'        => true,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => 5,
        'show_in_admin_bar'   => true,
        'show_in_nav_menus'   => true,
        'can_export'          => true,
        'has_archive'         => true,        
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'page',
    );
    register_post_type( 'post_product', $args );

}
add_action( 'init', 'custom_product', 0 );

*/
/*
global $jal_db_version;
$jal_db_version = '1.0';
function jal_install() {
    global $wpdb;
    $version = get_option( 'my_plugin_version', '1.0' );
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'product';

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        cat_id mediumint(9) NOT NULL AUTO_INCREMENT,
        name varchar(5) NOT NULL,
        description varchar(5) NOT NULL,
        UNIQUE KEY id (id)
    ) $charset_collate;";
    
    $table_cat = $wpdb->prefix . 'category';

    $sql_cat= "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name varchar(5) NOT NULL,
        UNIQUE KEY id (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
    
    add_option( 'jal_db_version', $jal_db_version );
}
register_activation_hook( __FILE__, 'jal_install' );
register_activation_hook( __FILE__, 'jal_install_data' );*/
?>