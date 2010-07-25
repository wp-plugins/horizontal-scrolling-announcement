<?php

/*
Plugin Name: Horizontal scrolling announcement
Plugin URI: http://www.gopiplus.com/work/2010/07/18/horizontal-scrolling-announcement/
Description: This is horizontal scrolling announcement plugin with added features, such as configurable Scroll Amount, Scroll Delay, Direction, and option to pause scroller onmouse over.
Version: 3.0
Author: Gopi.R
Author URI: http://www.gopiplus.com/work/2010/07/18/horizontal-scrolling-announcement/
Donate link: http://www.gopiplus.com/work/2010/07/18/horizontal-scrolling-announcement/
*/

global $wpdb, $wp_version;
define("WP_HSA_TABLE", $wpdb->prefix . "hsa_plugin");

function horizontal_scrolling_announcement()
{
	global $wpdb;
	$data = $wpdb->get_results("select hsa_text from ".WP_HSA_TABLE." where hsa_status='YES' ORDER BY hsa_order");
	if ( ! empty($data) ) 
	{
		$cnt = 0;
		foreach ( $data as $data ) 
		{
			if($cnt==0) 
			{  
				$hsa = stripslashes($data->hsa_text);
			}
			else
			{
				$hsa = $hsa . "   -   " . stripslashes($data->hsa_text);
			}
			
			$cnt = $cnt + 1;
		}
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
	echo $what_marquee;
}

//if (function_exists (horizontal_scrolling_announcement)) horizontal_scrolling_announcement();

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
    $title = __('Horizontal scrolling announcement');
    $mainurl = get_option('siteurl')."/wp-admin/options-general.php?page=horizontal-scrolling-announcement/horizontal-scrolling-announcement.php";

    $DID=@$_GET["DID"];
    $AC=@$_GET["AC"];
    $submittext = "Insert Message";

	if($AC <> "DEL" and trim($_POST['hsa_text']) <>"")
    {
			if($_POST['hsa_id'] == "" )
			{
					$sql = "insert into ".WP_HSA_TABLE.""
					. " set `hsa_text` = '" . mysql_real_escape_string(trim($_POST['hsa_text']))
					. "', `hsa_order` = '" . $_POST['hsa_order']
					. "', `hsa_status` = '" . $_POST['hsa_status']
					. "'";	
			}
			else
			{
					$sql = "update ".WP_HSA_TABLE.""
					. " set `hsa_text` = '" . mysql_real_escape_string(trim($_POST['hsa_text']))
					. "', `hsa_order` = '" . $_POST['hsa_order']
					. "', `hsa_status` = '" . $_POST['hsa_status']
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
		if ( !empty($data) ) $hsa_order_x = htmlspecialchars(stripslashes($data->hsa_order));
        
        $submittext = "Update Message";
    }
    ?>
  <h2><?php echo wp_specialchars( $title ); ?></h2>
  <div align="left" style="padding-top:5px;padding-bottom:5px;">
    <a href="options-general.php?page=horizontal-scrolling-announcement/horizontal-scrolling-announcement.php">Manage Page</a>
    <a href="options-general.php?page=horizontal-scrolling-announcement/horizontal-scrolling-setting.php">Setting Page</a>
  </div>
  <script language="JavaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/horizontal-scrolling-announcement/horizontal-scrolling-announcement.js"></script>
  <form name="form_hsa" method="post" action="<?php echo $mainurl; ?>" onsubmit="return has_submit()"  >
    <table width="100%">
      <tr>
        <td colspan="3" align="left" valign="middle">Enter the message:</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle"><textarea name="hsa_text" cols="70" rows="8" id="hsa_text"><?php echo $hsa_text_x; ?></textarea></td>
        <td width="40%" rowspan="3" align="center" valign="top">
         
        </td>
      </tr>
      <tr>
        <td align="left" valign="middle">Display Status:</td>
        <td align="left" valign="middle">Display Order:</td>
      </tr>
      <tr>
        <td width="20%" align="left" valign="middle"><select name="hsa_status" id="hsa_status">
            <option value="">Select</option>
            <option value='YES' <?php if($hsa_status_x=='YES') { echo 'selected' ; } ?>>Yes</option>
            <option value='NO' <?php if($hsa_status_x=='NO') { echo 'selected' ; } ?>>No</option>
          </select>        </td>
        <td width="40%" align="left" valign="middle"><input name="hsa_order" type="text" id="hsa_order" size="10" value="<?php echo $hsa_order_x; ?>" maxlength="3" /></td>
      </tr>
      <tr>
        <td height="35" colspan="2" align="left" valign="bottom">
        <input name="publish" lang="publish" class="button-primary" value="<?php echo $submittext?>" type="submit" />
        <input name="publish" lang="publish" class="button-primary" onclick="_hsa_redirect()" value="Cancel" type="button" /></td>
        <td align="center" valign="middle">&nbsp;</td>
      </tr>
      <input name="hsa_id" id="hsa_id" type="hidden" value="<?php echo $hsa_id_x; ?>">
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
            <th width="4%" align="left" scope="col">ID</td>
            <th width="68%" align="left" scope="col">Message</td>
            <th width="8%" align="left" scope="col"> Order
              </td>
            <th width="7%" align="left" scope="col">Display</td>
            <th width="13%" align="left" scope="col">Action</td>          
          </tr>
        </thead>
        <?php 
        $i = 0;
        foreach ( $data as $data ) { 
		if($data->hsa_status=='YES') { $displayisthere="True"; }
        ?>
        <tbody>
          <tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
            <td align="left" valign="middle"><?php echo(stripslashes($data->hsa_id)); ?></td>
            <td align="left" valign="middle"><?php echo(stripslashes($data->hsa_text)); ?></td>
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
    <div align="left" style="padding-top:10px;padding-bottom:10px;">
    <a href="options-general.php?page=horizontal-scrolling-announcement/horizontal-scrolling-announcement.php">Manage Page</a>
    <a href="options-general.php?page=horizontal-scrolling-announcement/horizontal-scrolling-setting.php">Go to setting Page to update your setting</a>
    </div>
    
    <h2><?php echo wp_specialchars( 'Paste the below code to your desired template location!' ); ?></h2>
    <div style="padding-top:7px;padding-bottom:7px;">
    <code style="padding:7px;">
    &lt;?php if (function_exists (horizontal_scrolling_announcement)) horizontal_scrolling_announcement(); ?&gt;
    </code></div>
	<h2><?php echo wp_specialchars( 'About plugin!' ); ?></h2>
Plug-in created by <a target="_blank" href='http://www.gopiplus.com/work/'>Gopi</a>.<br>
<a target="_blank" href='http://www.gopiplus.com/work/2010/07/18/horizontal-scrolling-announcement/'>Click here</a> to post suggestion or comments or feedback.<br>
<a target="_blank" href='http://www.gopiplus.com/work/2010/07/18/horizontal-scrolling-announcement/'>Click here</a> to see live demo.<br>
<a target="_blank" href='http://www.gopiplus.com/work/2010/07/18/horizontal-scrolling-announcement/'>Click here</a> to see more info.<br>
<a target="_blank" href='http://www.gopiplus.com/work/plugin-list/'>Click here</a> to see my other plugins.<br>

  </div>
</div>
<?php
}

function HSA_add_to_menu() 
{
	add_options_page('Super horizontal scrolling announcement', 'Horizontal Scrolling', 7, __FILE__, 'HSA_admin_options' );
	add_options_page('Super horizontal scrolling announcement', '', 0, "horizontal-scrolling-announcement/horizontal-scrolling-setting.php",'' );
}

register_activation_hook(__FILE__, 'HSA_activation');
add_action('admin_menu', 'HSA_add_to_menu');
register_deactivation_hook( __FILE__, 'HSA_deactivate' );
?>
