<?php
// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { 
	die('You are not allowed to call this page directly.'); 
}
?>
<div class="wrap">
  <div class="form-wrap">
    <div id="icon-edit" class="icon32 icon32-posts-post"><br>
    </div>
    <h2><?php echo WP_hsa_TITLE; ?></h2>	
    <?php
	$hsa_title = get_option('hsa_title');
	$hsa_scrollamount = get_option('hsa_scrollamount');
	$hsa_scrolldelay = get_option('hsa_scrolldelay');
	$hsa_direction = get_option('hsa_direction');
	$hsa_style = get_option('hsa_style');
	
	if (@$_POST['hsa_submit']) 
	{
		//	Just security thingy that wordpress offers us
		check_admin_referer('hsa_form_setting');
			
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
		
		?>
		<div class="updated fade">
			<p><strong>Details successfully updated.</strong></p>
		</div>
		<?php
	}
	?>
	<script language="JavaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/horizontal-scrolling-announcement/pages/setting.js"></script>
    <form name="hsa_form" method="post" action="">
         <h3>Default settings</h3>
		<label for="tag-width">Title</label>
		<input name="hsa_title" type="text" value="<?php echo $hsa_title; ?>"  id="hsa_title" size="70" maxlength="150">
		<p>Please enter your widget title.</p>
		
		<label for="tag-width">Scroll amount</label>
		<input name="hsa_scrollamount" type="text" value="<?php echo $hsa_scrollamount; ?>"  id="hsa_scrollamount" maxlength="3">  
		<p>Please enter scroll amount, This will make the scroll faster. (Example: 2)</p>
		
		<label for="tag-width">Scroll delay</label>
		<input name="hsa_scrolldelay" type="text" value="<?php echo $hsa_scrolldelay; ?>"  id="hsa_scrolldelay" maxlength="3">
		<p>Set the amount of delay in milliseconds. (Example: 5)</p>
		
		<label for="tag-width">Direction</label>
		<select name="hsa_direction" id="hsa_direction">
			<option value='left' <?php if($hsa_direction == 'left') { echo "selected='selected'" ; } ?>>Right to Left</option>
			<option value='right' <?php if($hsa_direction == 'right') { echo "selected='selected'" ; } ?>>Left to Right</option>
		</select>
		<p>Please select your scroll direction.</p>
		
		<label for="tag-width">CSS attribute</label>
		<input name="hsa_style" type="text" value="<?php echo $hsa_style; ?>"  id="hsa_style" size="70" maxlength="500">
		<p>Please enter your CSS attributes for style. (Example: color:#FF0000;font:Arial;)</p>
		
		<p class="submit">
		<input name="hsa_submit" id="hsa_submit" class="button" value="Submit" type="submit" />
		<input name="publish" lang="publish" class="button" onclick="hsa_redirect()" value="Cancel" type="button" />
		<input name="Help" lang="publish" class="button" onclick="hsa_help()" value="Help" type="button" />
		</p>
		<?php wp_nonce_field('hsa_form_setting'); ?>
    </form>
  </div>
  <p class="description"><?php echo WP_hsa_LINK; ?></p>
</div>
