<?php

/*
Plugin Name: Badge Management For Woocommerce
Plugin URI: https://www.phoeniixx.com/product/badge-management-for-woocommerce/
Description: This plugin allows you to add badges to products on your ecommerce site. Badges on a product could help you highlight special offers or new features of the products. 
Version: 1.2.3
Author: phoeniixx
Author URI:www.phoeniixx.com
License: GPL2
*/

if ( ! defined( 'ABSPATH' ) ) {
	
	exit;
}

	
	global $wpdb; 
	
	$pre=$wpdb->prefix; 
	
	define("PHBGPRE", $pre);
	
    define("PHBGPLUG_PATH",plugin_dir_url( __FILE__ ));

	if(in_array('woocommerce/woocommerce.php',apply_filters('active_plugins',get_option('active_plugins'))))
	{
	
		include("template/phbg_admin_template.php");
		
		include("phbg_admin_function.php");
		
		include("phbg_front_function.php");
		
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		add_action( 'admin_init', 'phbg_admin' );
		
		add_action('init','phbg_post_type');
		
		add_action( 'admin_menu', 'phbg_remove_meta_boxes' );
		
		add_action('admin_menu' , 'phbg_setting_menu'); 
		
		add_action( 'save_post', 'phbg_posts' );
		
		wp_enqueue_style( 'wp-color-picker' ); 
		
		wp_enqueue_script( 'custom-script-handle', PHBGPLUG_PATH.'assets/js/phbg_script.js?ver=4.3.1', array( 'wp-color-picker' ) ); 
		
		wp_enqueue_style( 'my-custom-style', PHBGPLUG_PATH.'assets/css/phbg_admin.css'); 

		$enable_badge=get_option('_phoe_badge_enable');	
		
		if(($enable_badge!='2'))
		{

			add_action("add_meta_boxes", "phbg_admin_setting");
			
			if(!is_admin())
			{
				
				add_action( 'woocommerce_single_product_image_html', 'phbg_product_post', 10, 2 );
				
				add_filter( 'post_thumbnail_html','phbg_single_post' , 999 , 2 );
				
			}
			
		}
	}
	else
	{

		 add_action( 'admin_notices', 'phbg_notice' );
	}

	function phbg_single_post( $thumb, $post_id ) {
		
		return phbg_product_post( $thumb, $post_id );
		
	}
	
	function phbg_setting_menu() {
		
		add_submenu_page('edit.php?post_type=phoe_badge', 'badge_setting', 'Settings', 'manage_options', 'phoe_badge_set', 'phoe_badge_set');
		
	}

	 function phbg_activate() {

		if( !get_option('_phoe_badge_enable'))
		{

			update_option('_phoe_badge_enable','1');
		}
		
	}
	register_activation_hook( __FILE__, 'phbg_activate' );
 	
?>
