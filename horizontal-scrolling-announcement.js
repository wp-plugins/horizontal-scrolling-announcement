/**
 *     Horizontal scrolling announcement
 *     Copyright (C) 2011  www.gopiplus.com
 * 
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 * 
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 * 
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
 
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