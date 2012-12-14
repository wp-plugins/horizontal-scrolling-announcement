// JavaScript Document
// Plugin Name: Horizontal scrolling announcement
function has_submit()
{
	if(document.form_hsa.hsa_text.value=="")
	{
		alert("Please enter the text.")
		document.form_hsa.hsa_text.focus();
		return false;
	}
	else if(document.form_hsa.hsa_status.value=="")
	{
		alert("Please select the display status.")
		document.form_hsa.hsa_status.focus();
		return false;
	}
	else if(document.form_hsa.hsa_order.value=="")
	{
		alert("Please enter the display order, only number.")
		document.form_hsa.hsa_order.focus();
		return false;
	}
	else if(isNaN(document.form_hsa.hsa_order.value))
	{
		alert("Please enter the display order, only number.")
		document.form_hsa.hsa_order.focus();
		return false;
	}
}

function _hsadelete(id)
{
	if(confirm("Do you want to delete this record?"))
	{
		document.frm_hsa.action="options-general.php?page=horizontal-scrolling-announcement/horizontal-scrolling-announcement.php&AC=DEL&DID="+id;
		document.frm_hsa.submit();
	}
}	

function _hsa_redirect()
{
	window.location = "options-general.php?page=horizontal-scrolling-announcement/horizontal-scrolling-announcement.php";
}