<?php

/*
Plugin Name: Horizontal scrolling announcement
Plugin URI: http://www.gopiplus.com/work/2010/07/18/horizontal-scrolling-announcement/
Description: This horizontal scrolling announcement wordpress plug-in let's scroll the content from one end to another end like reel.    
Version: 6.2
Author: Gopi.R
Author URI: http://www.gopiplus.com/work/2010/07/18/horizontal-scrolling-announcement/
Donate link: http://www.gopiplus.com/work/2010/07/18/horizontal-scrolling-announcement/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

global $wpdb, $wp_version;
define("WP_HSA_TABLE", $wpdb->prefix . "hsa_plugin");

function announcement()
{
	horizontal_scrolling_announcement();
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
	echo $what_marquee;
}

add_shortcode( 'horizontal-scrolling', 'HSA_shortcode' );

function HSA_shortcode( $atts ) 
{
	// [horizontal-scrolling]
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
	}
	$what_marquee = "";	
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
	
	add_option('hsa_title', "Announcement");
	add_option('hsa_scrollamount', "2");
	add_option('hsa_scrolldelay', "5");
	add_option('hsa_direction', "left");
	add_option('hsa_style', "color:#FF0000;font:Arial;");
}

function HSA_admin_options() 
{
	global $wpdb;
	?>

<div class="wrap">
  <?php
    $mainurl = get_option('siteurl')."/wp-admin/options-general.php?page=horizontal-scrolling-announcement/horizontal-scrolling-announcement.php";

    $DID=@$_GET["DID"];
    $AC=@$_GET["AC"];
    $submittext = "Insert Message";

	if(@$AC <> "DEL" and trim(@$_POST['hsa_text']) <>"")
    {
			if($_POST['hsa_id'] == "" )
			{
					$sql = "insert into ".WP_HSA_TABLE.""
					. " set `hsa_text` = '" . mysql_real_escape_string(trim($_POST['hsa_text']))
					. "', `hsa_order` = '" . $_POST['hsa_order']
					. "', `hsa_status` = '" . $_POST['hsa_status']
					. "', `hsa_link` = '" . $_POST['hsa_link']
					. "'";	
			}
			else
			{
					$sql = "update ".WP_HSA_TABLE.""
					. " set `hsa_text` = '" . mysql_real_escape_string(trim($_POST['hsa_text']))
					. "', `hsa_order` = '" . $_POST['hsa_order']
					. "', `hsa_status` = '" . $_POST['hsa_status']
					. "', `hsa_link` = '" . $_POST['hsa_link']
					. "' where `hsa_id` = '" . $_POST['hsa_id'] 
					. "'";	
			}
			$wpdb->get_results($sql);
    }
    
    if($AC=="DEL" && $DID > 0)
    {
        $wpdb->get_results("delete from ".WP_HSA_TABLE." where hsa_id=".$DID);
    }
    
    if($DID<>"" and $AC <> "DEL")
    {
        //select query
        $data = $wpdb->get_results("select * from ".WP_HSA_TABLE." where hsa_id=$DID limit 1");
    
        //bad feedback
        if ( empty($data) ) 
        {
           echo "<div id='message' class='error'><p>No data available! use below form to create!</p></div>";
            return;
        }
        
        $data = $data[0];
        
        //encode strings
        if ( !empty($data) ) $hsa_id_x = htmlspecialchars(stripslashes($data->hsa_id)); 
        if ( !empty($data) ) $hsa_text_x = htmlspecialchars(stripslashes($data->hsa_text));
        if ( !empty($data) ) $hsa_status_x = htmlspecialchars(stripslashes($data->hsa_status));
		if ( !empty($data) ) $hsa_link_x = htmlspecialchars(stripslashes($data->hsa_link));
		if ( !empty($data) ) $hsa_order_x = htmlspecialchars(stripslashes($data->hsa_order));
        
        $submittext = "Update Message";
    }
    ?>
  <?php include_once("button.php"); ?>
  <h2>Horizontal scrolling announcement</h2>
  <script language="JavaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/horizontal-scrolling-announcement/horizontal-scrolling-announcement.js"></script>
  <form name="form_hsa" method="post" action="<?php echo @$mainurl; ?>" onsubmit="return has_submit()"  >
    <table width="100%">
      <tr>
        <td colspan="2" align="left" valign="middle">Enter the message:</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle"><textarea name="hsa_text" cols="70" rows="8" id="hsa_text"><?php echo @$hsa_text_x; ?></textarea></td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle">Enter the hyperlink:</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle"><input name="hsa_link" type="text" id="hsa_link" size="100" value="<?php echo @$hsa_link_x; ?>" maxlength="1024" /></td>
      </tr>
      <tr>
        <td align="left" valign="middle">Display Status:</td>
        <td align="left" valign="middle">Display Order:</td>
      </tr>
      <tr>
        <td width="20%" align="left" valign="middle"><select name="hsa_status" id="hsa_status">
            <option value="">Select</option>
            <option value='YES' <?php if(@$hsa_status_x=='YES') { echo 'selected' ; } ?>>Yes</option>
            <option value='NO' <?php if(@$hsa_status_x=='NO') { echo 'selected' ; } ?>>No</option>
          </select>        </td>
        <td width="40%" align="left" valign="middle"><input name="hsa_order" type="text" id="hsa_order" size="10" value="<?php echo @$hsa_order_x; ?>" maxlength="3" /></td>
      </tr>
      <tr>
        <td height="35" colspan="2" align="left" valign="middle">
          <input name="publish" lang="publish" class="button-primary" value="<?php echo @$submittext?>" type="submit" />
        <input name="publish" lang="publish" class="button-primary" onclick="_hsa_redirect()" value="Cancel" type="button" /></td>
      </tr>
      <input name="hsa_id" id="hsa_id" type="hidden" value="<?php echo @$hsa_id_x; ?>">
    </table>
  </form>
  <div class="tool-box">
    <?php
	$data = $wpdb->get_results("select * from ".WP_HSA_TABLE." order by hsa_order");
	if ( empty($data) ) 
	{ 
		echo "<div id='message' class='error'>No data available! use below form to create!</div>";
		return;
	}
	?>
    <form name="frm_hsa" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
            <th width="3%" align="left" scope="col">ID</td>
            <th align="left" scope="col">Message</td>
            <th width="7%" align="left" scope="col"> Order
              </td>
            <th width="7%" align="left" scope="col">Display</td>
            <th width="8%" align="left" scope="col">Action</td>          
          </tr>
        </thead>
        <?php 
        $i = 0;
        foreach ( $data as $data ) { 
		if($data->hsa_status=='YES') { @$displayisthere="True"; }
        ?>
        <tbody>
          <tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
            <td align="left" valign="middle"><?php echo(stripslashes($data->hsa_id)); ?></td>
            <td align="left" valign="middle"><a href='<?php echo $data->hsa_link; ?>'><?php echo(stripslashes($data->hsa_text)); ?></a></td>
            <td align="left" valign="middle"><?php echo(stripslashes($data->hsa_order)); ?></td>
            <td align="left" valign="middle"><?php echo(stripslashes($data->hsa_status)); ?></td>
            <td align="left" valign="middle"><a href="options-general.php?page=horizontal-scrolling-announcement/horizontal-scrolling-announcement.php&DID=<?php echo($data->hsa_id); ?>">Edit</a> &nbsp; <a onClick="javascript:_hsadelete('<?php echo($data->hsa_id); ?>')" href="javascript:void(0);">Delete</a> </td>
          </tr>
        </tbody>
        <?php $i = $i+1; } ?>
        <?php if($displayisthere<>"True") { ?>
        <tr>
          <td colspan="5" align="center" style="color:#FF0000" valign="middle">No Announcement available with display status 'Yes'!' </td>
        </tr>
        <?php } ?>
      </table>
    </form>
    <?php 
	include_once("help.php");
	help();  
	?>
  </div>
</div>
<?php
}

function HSA_add_to_menu() 
{
	add_options_page('Horizontal scrolling announcement', 'Horizontal Scrolling', 'manage_options', __FILE__, 'HSA_admin_options' );
	add_options_page('Horizontal scrolling announcement', '', 'manage_options', "horizontal-scrolling-announcement/horizontal-scrolling-setting.php",'' );
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