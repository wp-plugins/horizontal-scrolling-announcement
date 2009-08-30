<div class="wrap">
<h2><?php echo wp_specialchars( 'Horizontal scrolling announcement' ); ?></h2>

<?php
global $wpdb, $wp_version;

include_once("extra.php");
$hsa_title = get_option('hsa_title');
$hsa_scrollamount = get_option('hsa_scrollamount');
$hsa_scrolldelay = get_option('hsa_scrolldelay');
$hsa_direction = get_option('hsa_direction');
$hsa_style = get_option('hsa_style');

if ($_POST['hsa_submit']) 
{
	$hsa_title = stripslashes($_POST['hsa_title']);
	$hsa_scrollamount = stripslashes($_POST['hsa_scrollamount']);
	$hsa_scrolldelay = stripslashes($_POST['hsa_scrolldelay']);
	$hsa_direction = stripslashes($_POST['hsa_direction']);
	$hsa_style = stripslashes($_POST['hsa_style']);

	update_option('hsa_title', $hsa_title );
	update_option('hsa_scrollamount', $hsa_scrollamount );
	update_option('hsa_scrolldelay', $hsa_scrolldelay );
	update_option('hsa_direction', $hsa_direction );
	update_option('hsa_style', $hsa_style );

}

?>
<form name="form_hsa" method="post" action="">
<div align="left" style="padding-top:5px;padding-bottom:5px;">
<a href="options-general.php?page=horizontal-scrolling-announcement/horizontal-scrolling-announcement.php">Manage Page</a>
<a href="options-general.php?page=horizontal-scrolling-announcement/horizontal-scrolling-setting.php">Setting Page</a>
</div>
<table width="800" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="125" align="left" valign="middle">Title:</td>
    <td width="247" rowspan="10" align="center" valign="top">
    <?php if (function_exists (timepass)) timepass(); ?>
    </td>
  </tr>
  <tr align="left" valign="middle">
    <td><input name="hsa_title" type="text" value="<?php echo $hsa_title; ?>"  id="hsa_title" size="70" maxlength="100"></td>
  </tr>
  <tr align="left" valign="middle">
    <td>Scroll Amount:</td>
  </tr>
  <tr align="left" valign="middle">
    <td>
    <input name="hsa_scrollamount" type="text" value="<?php echo $hsa_scrollamount; ?>"  id="hsa_scrollamount" maxlength="5">    
     Makes the scroll faster.    </td>
  </tr>
  <tr align="left" valign="middle">
    <td>Scroll Delay:</td>
  </tr>
  <tr align="left" valign="middle">
    <td>
    <input name="hsa_scrolldelay" type="text" value="<?php echo $hsa_scrolldelay; ?>"  id="hsa_scrolldelay" maxlength="5">
    Sets the amount of delay in milliseconds.    </td>
  </tr>
  <tr align="left" valign="middle">
    <td>Direction:</td>
  </tr>
  <tr align="left" valign="middle">
    <td>
    <input name="hsa_direction" type="text" value="<?php echo $hsa_direction; ?>"  id="hsa_direction" maxlength="10">
    Iindicates which direction to scroll
    </td>
  </tr>
  <tr align="left" valign="middle">
    <td>HTML Style Attribute :</td>
  </tr>
  <tr>
    <td align="left" valign="middle"><input name="hsa_style" type="text" value="<?php echo $hsa_style; ?>"  id="hsa_style" size="70" maxlength="500"></td>
  </tr>
  <tr>
    <td height="40" align="left" valign="bottom"> <input name="hsa_submit" id="hsa_submit" lang="publish" class="button-primary" value="Update Setting" type="submit" /></td>
    <td align="center" valign="top">&nbsp;</td>
  </tr>
</table>
</form>
<h2><?php echo wp_specialchars( 'Paste the below code to your desired template location!' ); ?></h2>
<div style="padding-top:7px;padding-bottom:7px;">
<code style="padding:7px;">
&lt;?php if (function_exists (horizontal_scrolling_announcement)) horizontal_scrolling_announcement(); ?&gt;
</code></div>
<h2><?php echo wp_specialchars( 'Sample Output' ); ?></h2>
<?php if (function_exists (horizontal_scrolling_announcement)) horizontal_scrolling_announcement(); ?>
<div align="left" style="padding-top:10px;padding-bottom:5px;">
<a href="options-general.php?page=horizontal-scrolling-announcement/horizontal-scrolling-announcement.php">Manage Page</a>
<a href="options-general.php?page=horizontal-scrolling-announcement/horizontal-scrolling-setting.php">Setting Page</a>
</div>
<br>
Plug-in created by <a target="_blank" href='http://gopi.coolpage.biz/demo/about/'>Gopi</a>. || 
<a target="_blank" href='#'>click here</a> to post suggestion or comments or how to improve this plugin. || 
<a target="_blank" href='#'>click here</a> to see my plugin demo. || <a target="_blank" href='#'>click here</a> 
To download my other plugins. || 
Clicking the above ad will not affect the site, to remove the ad simply remove this "timepass()" function from page!!<br>
<br>
</div>
