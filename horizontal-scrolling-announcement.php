<?php

/*
Plugin Name: Horizontal scrolling announcement
Plugin URI: http://www.gopiplus.com/work/2010/07/18/horizontal-scrolling-announcement/
Description: This horizontal scrolling announcement wordpress plug-in let's scroll the content from one end to another end like reel.    
Version: 7.1
Author: Gopi.R
Author URI: http://www.gopiplus.com/work/2010/07/18/horizontal-scrolling-announcement/
Donate link: http://www.gopiplus.com/work/2010/07/18/horizontal-scrolling-announcement/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

global $wpdb, $wp_version;
define("WP_HSA_TABLE", $wpdb->prefix . "hsa_plugin");

define("WP_hsa_UNIQUE_NAME", "horizontal-scrolling-announcement");
define("WP_hsa_TITLE", "Horizontal scrolling announcement");
define('WP_hsa_FAV', 'http://www.gopiplus.com/work/2010/07/18/horizontal-scrolling-announcement/');
define('WP_hsa_LINK', 'Check official website for more information <a target="_blank" href="'.WP_hsa_FAV.'">click here</a>');

function announcement()
{
	horizontal_scrolling_announcement();
}

function newannouncement( $group = "GROUP1" ) 
{
	$arr = array();
	$arr["group"]=$group;
	echo HSA_shortcode($arr);
}

function horizontal_scrolling_announcement()
{
	global $wpdb;
	$data = $wpdb->get_results("select hsa_text,hsa_link from ".WP_HSA_TABLE." where hsa_status='YES' ORDER BY hsa_order");
	if ( ! empty($data) ) 
	{
		$cnt = 0;
		$hsa = "";
		foreach ( $data as $data ) 
		{
			@$link = $data->hsa_link;	
			if($cnt==0) 
			{  
				if($link != "") { $hsa = $hsa . "<a href='".$link."'>"; } 
				$hsa = $hsa . stripslashes($data->hsa_text);
				if($link != "") { $hsa = $hsa . "</a>"; }
			}
			else
			{
				$hsa = $hsa . "   -   ";
				if($link != "") { $hsa = $hsa . "<a href='".$link."'>"; } 
				$hsa = $hsa . stripslashes($data->hsa_text);
				if($link != "") { $hsa = $hsa . "</a>"; }
			}			
			$cnt = $cnt + 1;
		}
		$hsa_title = get_option('hsa_title');
		$hsa_scrollamount = get_option('hsa_scrollamount');
		$hsa_scrolldelay = get_option('hsa_scrolldelay');
		$hsa_direction = get_option('hsa_direction');
		$hsa_style = get_option('hsa_style');
		
		$what_marquee = "";	
		$what_marquee = $what_marquee . "<div style='padding:3px;'>";
		$what_marquee = $what_marquee . "<marquee style='$hsa_style' scrollamount='$hsa_scrollamount' scrolldelay='$hsa_scrolldelay' direction='$hsa_direction' onmouseover='this.stop()' onmouseout='this.start()'>";
		$what_marquee = $what_marquee . $hsa;
		$what_marquee = $what_marquee . "</marquee>";
		$what_marquee = $what_marquee . "</div>";
	}	
	else
	{
		$what_marquee = $what_marquee . "No announcement available.";
	}
	
	echo $what_marquee;
}

add_shortcode( 'horizontal-scrolling', 'HSA_shortcode' );

function HSA_shortcode( $atts ) 
{
	// [horizontal-scrolling group="GROUP1"]
	global $wpdb;
	
	if ( is_array( $atts ) )
	{
		$group = $atts['group'];
	}
	
	$sSql = "select hsa_text,hsa_link from ".WP_HSA_TABLE." where hsa_status='YES'";
	if($group <> "")
	{
		$sSql = $sSql . " and hsa_group='$group'";
	}
	$sSql = $sSql . " ORDER BY hsa_order";

	$data = $wpdb->get_results($sSql);
	$what_marquee = "";	
	if ( ! empty($data) ) 
	{
		$cnt = 0;
		$hsa = "";
		$link = "";
		foreach ( $data as $data ) 
		{
			$link = $data->hsa_link;
			if($cnt==0) 
			{  
				if($link != "") { $hsa = $hsa . "<a href='".$link."'>"; } 
				$hsa = $hsa . stripslashes($data->hsa_text);
				if($link != "") { $hsa = $hsa . "</a>"; }
			}
			else
			{
				$hsa = $hsa . "   -   ";
				if($link != "") { $hsa = $hsa . "<a href='".$link."'>"; } 
				$hsa = $hsa . stripslashes($data->hsa_text);
				if($link != "") { $hsa = $hsa . "</a>"; }
			}			
			$cnt = $cnt + 1;
		}
		$hsa_title = get_option('hsa_title');
		$hsa_scrollamount = get_option('hsa_scrollamount');
		$hsa_scrolldelay = get_option('hsa_scrolldelay');
		$hsa_direction = get_option('hsa_direction');
		$hsa_style = get_option('hsa_style');		
		$what_marquee = $what_marquee . "<div style='padding:3px;'>";
		$what_marquee = $what_marquee . "<marquee style='$hsa_style' scrollamount='$hsa_scrollamount' scrolldelay='$hsa_scrolldelay' direction='$hsa_direction' onmouseover='this.stop()' onmouseout='this.start()'>";
		$what_marquee = $what_marquee . $hsa;
		$what_marquee = $what_marquee . "</marquee>";
		$what_marquee = $what_marquee . "</div>";
	}
	else
	{
		$what_marquee = $what_marquee . "No announcement available.";
		if($group <> "")
		{
			$what_marquee =  $what_marquee . " Please check this group " . $group;
		}
	}

	return $what_marquee;
}

function HSA_deactivate() 
{
	delete_option('hsa_title');
	delete_option('hsa_scrollamount');
	delete_option('hsa_scrolldelay');
	delete_option('hsa_direction');
	delete_option('hsa_style');
}

function HSA_activation() 
{
	global $wpdb;
	
	if($wpdb->get_var("show tables like '". WP_HSA_TABLE . "'") != WP_HSA_TABLE) 
	{
		$wpdb->query("
			CREATE TABLE IF NOT EXISTS `". WP_HSA_TABLE . "` (
			  `hsa_id` int(11) NOT NULL auto_increment,
			  `hsa_text` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
			  `hsa_order` int(11) NOT NULL default '0',
			  `hsa_status` char(3) NOT NULL default 'No',
			  `hsa_date` datetime NOT NULL default '0000-00-00 00:00:00',
			  PRIMARY KEY  (`hsa_id`) )
			");
		$sSql = "INSERT INTO `". WP_HSA_TABLE . "` (`hsa_text`, `hsa_order`, `hsa_status`, `hsa_date`)"; 
		$sSql = $sSql . "VALUES ('This is sample text for horizontal scrolling announcement.', '1', 'YES', '0000-00-00 00:00:00');";
		$wpdb->query($sSql);
	}
	
	$sSql = "ALTER TABLE `". WP_HSA_TABLE . "` ADD `hsa_link` VARCHAR( 1024 ) NOT NULL ";
	$wpdb->query($sSql);
	$sSql = "ALTER TABLE `". WP_HSA_TABLE . "` ADD `hsa_group` VARCHAR( 100 ) NOT NULL ";
	$wpdb->query($sSql);

	
	add_option('hsa_title', "Announcement");
	add_option('hsa_scrollamount', "2");
	add_option('hsa_scrolldelay', "5");
	add_option('hsa_direction', "left");
	add_option('hsa_style', "color:#FF0000;font:Arial;");
}

function HSA_admin_options() 
{
	global $wpdb;
	$current_page = isset($_GET['ac']) ? $_GET['ac'] : '';
	switch($current_page)
	{
		case 'edit':
			include('pages/content-management-edit.php');
			break;
		case 'add':
			include('pages/content-management-add.php');
			break;
		case 'set':
			include('pages/content-setting.php');
			break;
		default:
			include('pages/content-management-show.php');
			break;
	}
}

function HSA_add_to_menu() 
{
	add_options_page('Horizontal scrolling announcement', 'Horizontal scrolling', 'manage_options', 'horizontal-scrolling-announcement', 'HSA_admin_options' );
}

function HSA_widget_init() 
{
	if(function_exists('wp_register_sidebar_widget')) 	
	{
		wp_register_sidebar_widget('Horizontal Scrolling', 'Horizontal Scrolling', 'HSA_widget');
	}
	
	if(function_exists('wp_register_widget_control')) 	
	{
		wp_register_widget_control('Horizontal Scrolling', array('Horizontal Scrolling', 'widgets'), 'HSA_control');
	} 
}

function HSA_control() 
{
	echo "Horizontal scrolling announcement";
}

function HSA_widget($args) 
{
	extract($args);
	$hsa_title = get_option('hsa_title');
	if($hsa_title <> "")
	{
		echo $before_widget . $before_title;
		echo get_option('hsa_title');
		echo $after_title;
	}
	else
	{
		echo "<div style='padding-top:10px;padding-bottom:10px;'>";
	}
	horizontal_scrolling_announcement();
	if($hsa_title <> "")
	{
		echo $after_widget;
	}
	else
	{
		echo "</div>";
	}
}

add_action("plugins_loaded", "HSA_widget_init");
register_activation_hook(__FILE__, 'HSA_activation');
add_action('admin_menu', 'HSA_add_to_menu');
register_deactivation_hook( __FILE__, 'HSA_deactivate' );
add_action('init', 'HSA_widget_init');
?>