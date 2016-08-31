<?php

/*
Plugin Name: WP notifications plugin
Description: Create notifications by shortcode
Version: 1.3
Author: Pari
*/

if(!defined('NOTIFICATION_PLUGIN_URL')) 
  define('NOTIFICATION_PLUGIN_URL', plugin_dir_url( __FILE__ ));

if(!defined('NOTIFICATION_PLUGIN_DIR')) 
  define('NOTIFICATION_PLUGIN_DIR', plugin_dir_path( __FILE__ ));

add_action( 'admin_menu', 'notification_plugin_menu');//Add a hook to the menu in the console

function notification_plugin_menu() //menu and submenu
{
  add_menu_page( 'My Plugin Options', 'WP notification plugin', 8, NOTIFICATION_PLUGIN_DIR.'settings.php', '', 'dashicons-groups', 6);
}

register_activation_hook( NOTIFICATION_PLUGIN_DIR.'WP_Notification_plugin.php', 'myNotification_activate' );

function myNotification_activate() 
{
   global $wpdb;

   $table_name = $wpdb->prefix . "notifications";
   if($wpdb->get_var("show tables like '$table_name'") != $table_name) 
   {
      $sql = "CREATE TABLE " . $table_name . " (
      id           int NOT NULL AUTO_INCREMENT,
      text         varchar(255) NOT NULL,
      slug         text NOT NULL,
      color        VARCHAR(10),
      position     VARCHAR(15),
      UNIQUE KEY id (id)
      );";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
   }
}

register_deactivation_hook( NOTIFICATION_PLUGIN_DIR.'WP_Notification_plugin.php', 'myNotification_deactivate' );

function myNotification_deactivate() 
{
   global $wpdb;
   $table_name = $wpdb->prefix . "notifications";
   $wpdb->query( "DROP TABLE `" . $table_name . "`");
}

function wptuts_scripts_basic()  
{  
   wp_register_script( 'custom-script5', NOTIFICATION_PLUGIN_URL.'assests/jquery-1.12.1.js', __FILE__ );  
   wp_enqueue_script( 'custom-script5' ); 

   wp_register_script( 'custom-script1', NOTIFICATION_PLUGIN_URL.'assests/bootstrap.min.js', __FILE__ );  
   wp_enqueue_script( 'custom-script1' ); 

   wp_register_script( 'custom-script', NOTIFICATION_PLUGIN_URL.'assests/bootstrap-notify.min.js', __FILE__ );  
   wp_enqueue_script( 'custom-script' ); 

   wp_register_script( 'custom-script6', NOTIFICATION_PLUGIN_URL.'assests/clipboard.min.js', __FILE__ );  
   wp_enqueue_script( 'custom-script6' ); 

   wp_register_script( 'custom-script2', NOTIFICATION_PLUGIN_URL.'assests/positions.js', __FILE__ );  
   wp_enqueue_script( 'custom-script2' ); 

   wp_register_script( 'custom-script4', NOTIFICATION_PLUGIN_URL.'jquery-palette-color-picker-master/ready.js',__FILE__ );  
   wp_enqueue_script( 'custom-script4' );  

   wp_register_script( 'custom-script3', NOTIFICATION_PLUGIN_URL.'jquery-palette-color-picker-master/src/palette-color-picker.js', __FILE__ );  
   wp_enqueue_script( 'custom-script3' );  
}  
add_action( 'wp_enqueue_scripts', 'wptuts_scripts_basic' );
add_action( 'admin_enqueue_scripts', 'wptuts_scripts_basic' );

function wptuts_style_basic()  
{  
    wp_register_style( 'custom-style', NOTIFICATION_PLUGIN_URL.'css/animate.css', __FILE__ );  
    wp_enqueue_style( 'custom-style' );  

    wp_register_style( 'custom-style3', NOTIFICATION_PLUGIN_URL.'css/bootstrap.css', __FILE__ );  
    wp_enqueue_style( 'custom-style3' );  

    wp_register_style( 'custom-style1', NOTIFICATION_PLUGIN_URL.'css/normalize.min.css', __FILE__ );  
    wp_enqueue_style( 'custom-style1' );  

    wp_register_style( 'custom-style2', NOTIFICATION_PLUGIN_URL.'jquery-palette-color-picker-master/src/palette-color-picker.css', __FILE__ );  
    wp_enqueue_style( 'custom-style2' ); 
}  
add_action( 'wp_enqueue_scripts', 'wptuts_style_basic' );
add_action( 'admin_enqueue_scripts', 'wptuts_style_basic' );

add_shortcode ('pd-notif', 'short_code_notifications');

function short_code_notifications($atts)
{
  extract( shortcode_atts( array(
    'slag' => 'smth',
  ), $atts ) );
  global $wpdb;
  $table_name = $wpdb->prefix . "notifications";
  $frontend_notif = $wpdb->get_results( "SELECT text FROM  `" . $table_name . "` WHERE slug = '$slag'" );
  $frontend_notif_color = $wpdb->get_results( "SELECT color FROM  `" . $table_name . "` WHERE slug = '$slag'" );
  $frontend_notif_position = $wpdb->get_results( "SELECT position FROM  `" . $table_name . "` WHERE slug = '$slag'" );
  $frontend_notif =             $frontend_notif[0]->text;
  $frontend_notif_color =       $frontend_notif_color[0]->color;
  $frontend_notif_position =    $frontend_notif_position[0]->position;

  return "<div id = \"scroll\"></div> 
<script>
    var done = false;
    $(document).ready( function (){
      window.onscroll = function() 
      {
        if (!done){

          var frontend_notif             = \"$frontend_notif\";
          var frontend_notif_color       = \"$frontend_notif_color\";
          frontend_notif_position    = \"$frontend_notif_position\";

          var elem_pos = $(\"#scroll\").offset();
          var elem_pos = elem_pos.top;
          var scroll_pos = $(window).scrollTop();

          if ((scroll_pos-200<elem_pos)&&(elem_pos<scroll_pos+700))
          {
            $.notify({
              icon: 'glyphicon glyphicon-warning-sign',
              message: frontend_notif,
            },{
              type: frontend_notif_color,
              offset: {
                x: 50,
                y: 50
              },
              placement: {
                from: full_positions[frontend_notif_position][\"position_from\"],
                align: full_positions[frontend_notif_position][\"position_align\"]
              }
            });

            done = true;
          }
        }
      }
    });
</script>
";
}