
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

function et_custom_admin_bar_greeting_text( $wp_admin_bar ) {
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
add_action( 'admin_bar_menu', 'et_custom_admin_bar_greeting_text' );
