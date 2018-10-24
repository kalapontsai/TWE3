<?php
/* -----------------------------------------------------------------------------------------
   $Id: content.php,v 1.2 2004/02/17 16:20:07 oldpa Exp $   

   TWE-Commerce - community made shopping   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(information.php,v 1.6 2003/02/10); www.oscommerce.com 
   (c) 2003	 nextcommerce (content.php,v 1.2 2003/08/21); www.nextcommerce.org   
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
---------------------------------------------------------------------------------------*/
               // create smarty elements
  // include boxes
  // include needed function
 global $db;  
$content_string='';
$infomation_string ='';
$rebuild = false;

$content_query="SELECT
 					content_id,
 					categories_id,
 					parent_id,
 					content_title,
 					content_group
 					FROM ".TABLE_CONTENT_MANAGER."
 					WHERE languages_id='".(int)$_SESSION['languages_id']."'
 					and file_flag=1 and content_status=1";
  $content_data = $db->Execute($content_query);
					
if($content_data->RecordCount() > 0){					
					
 while (!$content_data->EOF) {
 	
 $content_string .= '<li><a class="btn-block" role="button" href="' . twe_href_link(FILENAME_CONTENT,'coID='.$content_data->fields['content_group'],'SSL') . '">' . $content_data->fields['content_title'] . '</a></li>';
 $content_data->MoveNext();
 }
 } 
 
 $infomation_query="SELECT
 					content_id,
 					categories_id,
 					parent_id,
 					content_title,
 					content_group
 					FROM ".TABLE_CONTENT_MANAGER."
 					WHERE languages_id='".(int)$_SESSION['languages_id']."'
 					and file_flag=0 and content_status=1";
  $infomation_data = $db->Execute($infomation_query);
if($infomation_data->RecordCount() > 0){					
 while (!$infomation_data->EOF) { 	
 $content_string .= '<li><a class="btn-block" role="button" href="' . twe_href_link(FILENAME_CONTENT,'coID='.$infomation_data->fields['content_group'],'SSL') . '">' . $infomation_data->fields['content_title'] . '</a></li>';
 $infomation_data->MoveNext();
}
}
	
	echo $content_string;
	
	
?>