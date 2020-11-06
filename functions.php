
//Custom Login Page

function my_login_stylesheet() {
    wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri() . '/style-login.css' );
    wp_enqueue_script( 'custom-login', get_stylesheet_directory_uri() . '/style-login.js' );
}
add_action( 'login_enqueue_scripts', 'my_login_stylesheet' );

add_action( 'login_enqueue_scripts', 'my_login_logo' );

function my_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

function my_login_logo_url_title() {
    return 'Your Site Name and Info';
}
add_filter( 'login_headertitle', 'my_login_logo_url_title' );

//Remove Dashboard Widgtes for NON Admin Users

function remove_dashboard_meta() {
      if ( ! current_user_can( 'manage_options' ) ) {
            remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
            remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
            remove_meta_box( 'dashboard_primary', 'dashboard', 'normal' );
            remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
            remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
            remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
            remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
            remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
            remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');
      }
}
add_action( 'admin_init', 'remove_dashboard_meta' );


//Remove Options Screen if not Admin

function wpb_remove_screen_options() { 
if(!current_user_can('manage_options')) {
return false;
}
return true; 
}
add_filter('screen_options_show_screen', 'wpb_remove_screen_options');

//Change Greeting

function dv_custom_admin_bar_greeting_text( $wp_admin_bar ) {
  $user_data         = wp_get_current_user();
  $user_display_name = isset( $user_data->display_name ) ? $user_data->display_name : false;
  $user_id           = isset( $user_data->ID ) ? (int) $user_data->ID : 0;
  if ( ! $user_id || ! $user_display_name ) {
    return;
  }  $user_avatar = get_avatar( $user_id, 26 );  // translators: %s: Current user's display name
  $my_account_text = sprintf(
    __( 'Hello, %s' ),
    '<span class="display-name">' . esc_html( $user_data->display_name ) . '</span>'
  );  $wp_admin_bar->add_node(
    array(
      'id'    => 'my-account',
      'title' => $my_account_text . $user_avatar,
    )
  );
}
add_action( 'admin_bar_menu', 'dv_custom_admin_bar_greeting_text' );


//Change Footer Text

function dv_custom_change_admin_footer_text () {
  return __( 'Theme designed by <a href="https://dviralsolutions.pt/">DViral Solutions</a>.', 'dv_custom-text-domain' );
}
add_filter( 'admin_footer_text', 'dv_custom_change_admin_footer_text' );?>

//Custom Dashboard Box

add_action('wp_dashboard_setup', 'dv_my_custom_dashboard_widgets');
  
function dv_my_custom_dashboard_widgets() {
global $wp_meta_boxes;
 
wp_add_dashboard_widget('dv_custom_help_widget', 'Theme Support', 'dv_custom_dashboard_help');
}
 
function dv_custom_dashboard_help() {
echo '<p>Welcome to Custom Blog Theme! Need help? Contact the developer <a href="mailto:yourusername@gmail.com">here</a>. For WordPress Tutorials visit: <a href="https://www.wpbeginner.com" target="_blank">DViral Solutions</a></p>';
}

//Remove or change icon/logo in admin

function dv_custom_admin_styles() { ?>
    <style type="text/css">
		
		
		#wpadminbar>#wp-toolbar>#wp-admin-bar-root-default>#wp-admin-bar-wp-logo {
			display: none;
		}

		#wpadminbar>#wp-toolbar>#wp-admin-bar-root-default>#wp-admin-bar-wp-logo .ab-item .ab-icon:before {
			content: url("https://via.placeholder.com/16");
			position: relative;
		}
	
	
    </style>
<?php }
add_action( 'admin_enqueue_scripts', 'dv_custom_admin_styles' );

//Remove Template (Elementor Menu Admin) if Not Administrator

function dv_remove_comments_menu_item() {
    $user = wp_get_current_user();
    if ( ! $user->has_cap( 'manage_options' ) ) {
        remove_menu_page( 'edit.php?post_type=elementor_library' );
    }
}
add_action( 'admin_menu', 'dv_remove_comments_menu_item' );

//Sources for other Elementor Menu admin https://plugintests.com/plugins/wporg/elementor/tips

//Remove any menu item if not administrator - example tools

function dv_remove_tools_menu_item() {
    $user = wp_get_current_user();
    if ( ! $user->has_cap( 'manage_options' ) ) {
        remove_menu_page( 'tools.php' ); //to know the slug just hover it and check the link address
    }
    if( ! $user->has_cap( 'update_core' ) ){ //shop manager doesn't have this cap, so use this 
	remove_submenu_page('woocommerce','wc-addons');
	remove_submenu_page('woocommerce','wc-status');
    }
}
add_action( 'admin_menu', 'dv_remove_tools_menu_item' );

//Remove cap from role

function remove_shopmanager_list_users() {
 
    // get_role returns an instance of WP_Role.
    $role = get_role( 'shop_manager' );
    $role->remove_cap( 'list_users' );
}
add_action( 'init', 'remove_shopmanager_list_users' );

// Remove meta box from Product Page

function dv_remove_product_custom_fields() {
    remove_meta_box( 'postexcerpt' , 'product' , 'normal' ); 
    remove_meta_box( 'astra_settings_meta_box' , 'product' , 'side' );
}
		
   add_action( 'add_meta_boxes_product' , 'dv_remove_product_custom_fields' );

//Remove any tab in edit product page

function dv_remove_tab($tabs){
	//unset($tabs['general']); // it is to remove general tab
	//unset($tabs['inventory']); // it is to remove inventory tab
	unset($tabs['advanced']); // it is to remove advanced tab
	//unset($tabs['linked_product']); // it is to remove linked_product tab
	//unset($tabs['attribute']); // it is to remove attribute tab
	//unset($tabs['variations']); // it is to remove variations tab
return($tabs);
	}

add_filter('woocommerce_product_data_tabs', 'dv_remove_tab'	);


// Add a new top level menu link to the admin bar

function dv_custom_link_admin_bar() {

	$createProductURL = '/wp-admin/post-new.php?post_type=product';
	$wp_admin_bar->add_menu( array(
	'parent' => false,
	'id' => 'new-product',
	'title' => '<span style="top:4px;" class="ab-icon dashicons dashicons-plus"></span> <span class="ab-label">'. __('Add new product', 'woocommerce') .'</span>',
	'href' => $createProductURL
			));

}
			

add_action( 'wp_before_admin_bar_render', 'dv_custom_link_admin_bar' );

//check admin menu array with style to see good in browser

function dv_check_admin_menu_array() {
    global $menu;
    global $submenu;
 	echo '<pre style="margin-left:200px;">';
    print_r($menu);
	print_r($submenu);
	echo '</pre>';
    
    
}
add_action( 'admin_menu', 'dv_check_admin_menu_array' );
