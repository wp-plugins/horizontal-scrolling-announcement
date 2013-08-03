<?php
// Form submitted, check the data
if (isset($_POST['frm_hsa_display']) && $_POST['frm_hsa_display'] == 'yes')
{
	$did = isset($_GET['did']) ? $_GET['did'] : '0';
	
	$hsa_success = '';
	$hsa_success_msg = FALSE;
	
	// First check if ID exist with requested ID
	$sSql = $wpdb->prepare(
		"SELECT COUNT(*) AS `count` FROM ".WP_HSA_TABLE."
		WHERE `hsa_id` = %d",
		array($did)
	);
	$result = '0';
	$result = $wpdb->get_var($sSql);
	
	if ($result != '1')
	{
		?><div class="error fade"><p><strong>Oops, selected details doesn't exist (1).</strong></p></div><?php
	}
	else
	{
		// Form submitted, check the action
		if (isset($_GET['ac']) && $_GET['ac'] == 'del' && isset($_GET['did']) && $_GET['did'] != '')
		{
			//	Just security thingy that wordpress offers us
			check_admin_referer('hsa_form_show');
			
			//	Delete selected record from the table
			$sSql = $wpdb->prepare("DELETE FROM `".WP_HSA_TABLE."`
					WHERE `hsa_id` = %d
					LIMIT 1", $did);
			$wpdb->query($sSql);
			
			//	Set success message
			$hsa_success_msg = TRUE;
			$hsa_success = __('Selected record was successfully deleted.', WP_hsa_UNIQUE_NAME);
		}
	}
	
	if ($hsa_success_msg == TRUE)
	{
		?><div class="updated fade"><p><strong><?php echo $hsa_success; ?></strong></p></div><?php
	}
}
?>
<div class="wrap">
  <div id="icon-edit" class="icon32 icon32-posts-post"></div>
    <h2><?php echo WP_hsa_TITLE ?><a class="add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/options-general.php?page=horizontal-scrolling-announcement&amp;ac=add">Add New</a></h2>
    <div class="tool-box">
	<?php
		$sSql = "SELECT * FROM `".WP_HSA_TABLE."` order by hsa_id";
		$myData = array();
		$myData = $wpdb->get_results($sSql, ARRAY_A);
		?>
		<script language="JavaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/horizontal-scrolling-announcement/pages/setting.js"></script>
		<form name="frm_hsa_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
            <th class="check-column" scope="row"><input type="checkbox" /></th>
			<th scope="col">Text</th>
            <th scope="col">Order</th>
			<th scope="col">Display</th>
			<th scope="col">Group</th>
          </tr>
        </thead>
		<tfoot>
          <tr>
            <th class="check-column" scope="row"><input type="checkbox" /></th>
			<th scope="col">Text</th>
            <th scope="col">Order</th>
			<th scope="col">Display</th>
			<th scope="col">Group</th>
          </tr>
        </tfoot>
		<tbody>
			<?php 
			$i = 0;
			if(count($myData) > 0 )
			{
				foreach ($myData as $data)
				{
					?>
					<tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
						<td align="left"><input type="checkbox" value="<?php echo $data['hsa_id']; ?>" name="hsa_group_item[]"></td>
						<td><?php echo stripslashes($data['hsa_text']); ?>
						<div class="row-actions">
							<span class="edit"><a title="Edit" href="<?php echo get_option('siteurl'); ?>/wp-admin/options-general.php?page=horizontal-scrolling-announcement&amp;ac=edit&amp;did=<?php echo $data['hsa_id']; ?>">Edit</a> | </span>
							<span class="trash"><a onClick="javascript:hsa_delete('<?php echo $data['hsa_id']; ?>')" href="javascript:void(0);">Delete</a></span> 
						</div>
						</td>
						<td><?php echo stripslashes($data['hsa_order']); ?></td>
						<td><?php echo stripslashes($data['hsa_status']); ?></td>
						<td><?php echo stripslashes($data['hsa_group']); ?></td>
					</tr>
					<?php 
					$i = $i+1; 
				} 
			}
			else
			{
				?><tr><td colspan="5" align="center">No records available.</td></tr><?php 
			}
			?>
		</tbody>
        </table>
		<?php wp_nonce_field('hsa_form_show'); ?>
		<input type="hidden" name="frm_hsa_display" value="yes"/>
      </form>	
	  <div class="tablenav">
	  <h2>
	  <a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/options-general.php?page=horizontal-scrolling-announcement&amp;ac=add">Add new</a>
	  <a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/options-general.php?page=horizontal-scrolling-announcement&amp;ac=set">Setting management</a>
	  <a class="button add-new-h2" target="_blank" href="<?php echo WP_hsa_FAV; ?>">Help</a>
	  </h2>
	  </div>
		<div style="height:5px"></div>
		<h3>Plugin configuration option</h3>
		<ol>
			<li>Add the plugin in the posts or pages using short code.</li>
			<li>Add directly in to the theme using PHP code.</li>
			<li>Drag and drop the widget to your sidebar.</li>
		</ol>
		<p class="description"><?php echo WP_hsa_LINK; ?></p>
	</div>
</div>