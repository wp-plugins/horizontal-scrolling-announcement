<?php
// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { 
	die('You are not allowed to call this page directly.'); 
}
?>
<div class="wrap">
<?php

$hsa_errors = array();
$hsa_success = '';
$hsa_error_found = FALSE;

// Preset the form fields
$form = array(
	'hsa_id' => '',
	'hsa_text' => '',
	'hsa_order' => '',
	'hsa_status' => '',
	'hsa_link' => '',
	'hsa_group' => '',
	'hsa_dateend' => ''
);

// Form submitted, check the data
if (isset($_POST['hsa_form_submit']) && $_POST['hsa_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('hsa_form_add');
	
	$form['hsa_text'] = isset($_POST['hsa_text']) ? $_POST['hsa_text'] : '';
	if ($form['hsa_text'] == '')
	{
		$hsa_errors[] = __('Please enter the text.', WP_hsa_UNIQUE_NAME);
		$hsa_error_found = TRUE;
	}

	$form['hsa_order'] = isset($_POST['hsa_order']) ? $_POST['hsa_order'] : '';
	if ($form['hsa_order'] == '')
	{
		$hsa_errors[] = __('Please enter the display order, only number.', WP_hsa_UNIQUE_NAME);
		$hsa_error_found = TRUE;
	}

	$form['hsa_status'] = isset($_POST['hsa_status']) ? $_POST['hsa_status'] : '';
	if ($form['hsa_status'] == '')
	{
		$hsa_errors[] = __('Please select the display status.', WP_hsa_UNIQUE_NAME);
		$hsa_error_found = TRUE;
	}
	
	$form['hsa_link'] = isset($_POST['hsa_link']) ? $_POST['hsa_link'] : '';
	$form['hsa_group'] = isset($_POST['hsa_group']) ? $_POST['hsa_group'] : '';
	$form['hsa_dateend'] = isset($_POST['hsa_dateend']) ? $_POST['hsa_dateend'] : '0000-00-00';

	//	No errors found, we can add this Group to the table
	if ($hsa_error_found == FALSE)
	{
		$sSql = $wpdb->prepare(
			"INSERT INTO `".WP_HSA_TABLE."`
			(`hsa_text`, `hsa_order`, `hsa_status`, `hsa_link`, `hsa_group`, `hsa_dateend`)
			VALUES(%s, %s, %s, %s, %s, %s)",
			array($form['hsa_text'], $form['hsa_order'], $form['hsa_status'], $form['hsa_link'], $form['hsa_group'], $form['hsa_dateend'])
		);
		
		$wpdb->query($sSql);
		$hsa_success = __('New details was successfully added.', WP_hsa_UNIQUE_NAME);
		
		// Reset the form fields
		$form = array(
			'hsa_id' => '',
			'hsa_text' => '',
			'hsa_order' => '',
			'hsa_status' => '',
			'hsa_link' => '',
			'hsa_group' => '',
			'hsa_dateend' => ''
		);
	}
}

if ($hsa_error_found == TRUE && isset($hsa_errors[0]) == TRUE)
{
	?>
	<div class="error fade">
		<p><strong><?php echo $hsa_errors[0]; ?></strong></p>
	</div>
	<?php
}
if ($hsa_error_found == FALSE && strlen($hsa_success) > 0)
{
	?>
	<div class="updated fade">
		<p><strong><?php echo $hsa_success; ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/options-general.php?page=horizontal-scrolling-announcement">Click here</a> to view the details</strong></p>
	</div>
	<?php
}
?>
<script language="JavaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/horizontal-scrolling-announcement/pages/setting.js"></script>
<div class="form-wrap">
	<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
	<h2><?php echo WP_hsa_TITLE; ?></h2>
	<form name="form_hsa" method="post" action="#" onsubmit="return hsa_submit()"  >
      <h3>Add details</h3>
      
		<label for="tag-title">Enter the message/announcement</label>
		<textarea name="hsa_text" cols="110" rows="6" id="hsa_text"></textarea>
		<p>Please enter your announcement text.</p>
			
		<label for="tag-title">Enter target link</label>
		<input name="hsa_link" type="text" id="hsa_link" size="113" value="" maxlength="1024" />
		<p>When someone clicks on the picture, where do you want to send them.</p>
		
		<label for="tag-title">Display status</label>
		<select name="hsa_status" id="hsa_status">
			<option value='YES'>Yes</option>
			<option value='NO'>No</option>
		</select>
		<p>Do you want to show this announcement in your scroll?</p>
		
		<label for="tag-title">Display order</label>
		<input name="hsa_order" type="text" id="hsa_order" value="" maxlength="3" />
		<p>What order should this announcement be played in. should it come 1st, 2nd, 3rd, etc..</p>
		
		<label for="tag-title">Announcement group</label>
		<select name="hsa_group" id="hsa_group">
		<option value='Select'>Select</option>
		<?php
		$sSql = "SELECT distinct(hsa_group) as hsa_group FROM `".WP_HSA_TABLE."` order by hsa_group";
		$myDistinctData = array();
		$arrDistinctDatas = array();
		$myDistinctData = $wpdb->get_results($sSql, ARRAY_A);
		$i = 0;
		foreach ($myDistinctData as $DistinctData)
		{
			$arrDistinctData[$i]["hsa_group"] = strtoupper($DistinctData['hsa_group']);
			$i = $i+1;
		}
		for($j=$i; $j<$i+5; $j++)
		{
			$arrDistinctData[$j]["hsa_group"] = "GROUP" . $j;
		}
		$arrDistinctDatas = array_unique($arrDistinctData, SORT_REGULAR);
		foreach ($arrDistinctDatas as $arrDistinct)
		{
			?><option value='<?php echo strtoupper($arrDistinct["hsa_group"]); ?>'><?php echo strtoupper($arrDistinct["hsa_group"]); ?></option><?php
		}
		?>
		</select>
		<p>Please select your announcement group.</p>
		
		<label for="tag-title">Expiration date</label>
		<input name="hsa_dateend" type="text" id="hsa_dateend" value="9999-12-31" maxlength="10" />
		<p>Please enter the expiration date in this format YYYY-MM-DD <br /> 9999-12-31 : Is equal to no expire.</p>
					
      <input name="hsa_id" id="hsa_id" type="hidden" value="">
      <input type="hidden" name="hsa_form_submit" value="yes"/>
      <p class="submit">
        <input name="publish" lang="publish" class="button add-new-h2" value="Submit" type="submit" />&nbsp;
        <input name="publish" lang="publish" class="button add-new-h2" onclick="hsa_redirect()" value="Cancel" type="button" />&nbsp;
        <input name="Help" lang="publish" class="button add-new-h2" onclick="hsa_help()" value="Help" type="button" />
      </p>
	  <?php wp_nonce_field('hsa_form_add'); ?>
    </form>
</div>
<p class="description"><?php echo WP_hsa_LINK; ?></p>
</div>