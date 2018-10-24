<?php
/* --------------------------------------------------------------
   $Id: categories.php,v 1.45 2004/04/25 13:58:08 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(categories.php,v 1.140 2003/03/24); www.oscommerce.com
   (c) 2003  nextcommerce (categories.php,v 1.37 2003/08/18); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   --------------------------------------------------------------
   Third Party contribution:
   Enable_Disable_Categories 1.3               Autor: Mikel Williams | mikel@ladykatcostumes.com
   New Attribute Manager v4b                   Autor: Mike G | mp3man@internetwork.net | http://downloads.ephing.com
   Category Descriptions (Version: 1.5 MS2)    Original Author:   Brian Lowe <blowe@wpcusrgrp.org> | Editor: Lord Illicious <shaolin-venoms@illicious.net>
   Customers Status v3.x  (c) 2002-2003 Copyright Elari elari@free.fr | www.unlockgsm.com/dload-osc/ | CVS : http://cvs.sourceforge.net/cgi-bin/viewcvs.cgi/elari/?sortby=date#dirlist

   Released under the GNU General Public License
   --------------------------------------------------------------*/
// ?cpath=0_10&pID=478&action=new_product

  require('includes/application_top.php');
  include ('includes/classes/image_manipulator.php');
  require_once(DIR_FS_INC .'twe_get_tax_rate.inc.php');
  require_once(DIR_FS_INC . 'twe_get_more_images.inc.php'); 
  require_once(DIR_FS_INC . 'twe_wysiwyg.inc.php'); 
  require_once(DIR_FS_INC . 'twe_get_parent_categories.inc.php');
  require(DIR_WS_CLASSES . 'currencies.php');
    
  $currencies = new currencies();
  $today = date('Y-m-d H:i:s');
	if (isset($_GET['rType']) && $_GET['rType'] == 'ajax'){
	$action = (isset($_POST['action']) ? $_POST['action'] : '');
  	if (twe_not_null($action)){
	  switch($action){
		  case 'takecPath':
		  $categories = array();
		  twe_get_parent_categories($categories, $_POST['cPath']);
		  $categories = array_reverse($categories);
		  $Path = implode('_', $categories);
		  if (twe_not_null($Path)) $Path .= '_';
		  $Path .= $_POST['cPath'];
		  echo '<a href="'.twe_href_link(FILENAME_CATEGORIES, 'cPath=' . $Path . '&action=new_category').'">'.twe_image_button('button_new_category.gif', IMAGE_NEW_CATEGORY).'</a>';
		 break;
		 case 'removeCategory':
		  $cID = twe_db_prepare_input($_POST['cid']);
		  $Path = twe_db_prepare_input($_POST['cPath']);
		  twe_remove_category($cID);
		 break;
		 case 'getcPath':
		  $categories = array();
		  twe_get_parent_categories($categories, $_POST['cPath']);
		  $categories = array_reverse($categories);
		  $Path = implode('_', $categories);
		  if (twe_not_null($Path)) $Path .= '_';
		  $Path .= $_POST['cPath'];
		  echo '<a href="'.twe_href_link(FILENAME_CATEGORIES, 'cPath=' . $Path . '&action=new_product').'">'.twe_image_button('button_new_product.gif', IMAGE_NEW_PRODUCT).'</a>';
		 break;
		 case 'removeProduct':
		  $pID = twe_db_prepare_input($_POST['pid']);
		  $Path = twe_db_prepare_input($_POST['cPath']);
		  twe_remove_product($pID);
		 break; 
		 case 'saveC':
			$cVal = twe_db_prepare_input($_POST['val']);
			$cName = twe_db_prepare_input($_POST['key']);
			$cID = twe_db_prepare_input($_POST['cid']);
			if($cName == 'groups[]'){
			$cName = 'group_ids';
			$group_ids='';
			$cVal = explode(',',$cVal);	
			if(isset($cVal)) foreach($cVal as $b){
			$group_ids .= 'c_'.$b."_group ,";
			}
			$customers_statuses_array=twe_get_customers_statuses();
		     if (strstr($group_ids,'c_all_group')) {
			  $group_ids='c_all_group,';
			  for ($i=0;$n=sizeof($customers_statuses_array),$i<$n;$i++) {
			  $group_ids .='c_'.$customers_statuses_array[$i]['id'].'_group,';
			  }			
		     }
			 $cVal=$group_ids;	
			}
			$db->Execute("UPDATE " . TABLE_CATEGORIES . " SET ".$cName." = '" . $cVal . "' WHERE categories_id = '" . (int)$cID . "'");
			if (GROUP_CHECK=='true') {
			  $category_query = "select group_ids from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$cID . "'";
          $category = $db->Execute($category_query);
		  $customers_statuses_array = twe_get_customers_statuses();
		  $customers_statuses_array=array_merge(array(array('id'=>'all','text'=>TXT_ALL)),$customers_statuses_array);
		  
			$group ='<table  class="ui-widget-content ui-corner-all" width="100%" border="0">
			<tr>
			  <td colspan="4">'.twe_draw_separator('pixel_trans.gif', '1', '10').'</td>
			 </tr>
		  <tr>
		  <td style="border-top: 1px solid; border-color: #ff0000;" valign="top" class="main" ></td>
		  <td style="border-top: 1px solid; border-color: #ff0000;" valign="top" class="main" ></td>
		  <td style="border-top: 1px solid; border-color: #ff0000;" valign="top" class="main" >'.ENTRY_CUSTOMERS_STATUS.'</td>
		  <td style="border-top: 1px solid; border-left: 1px solid; border-color: #ff0000;"  bgcolor="#FFCC33" class="main">';		  
		  for ($i=0;$n=sizeof($customers_statuses_array),$i<$n;$i++) {
		  if (strstr($category->fields['group_ids'],'c_'.$customers_statuses_array[$i]['id'].'_group')) {
		  $checked='checked ';
		  } else {
		  $checked='';
		  }
		  $group .='<input id="cChecked" type="checkbox" name="groups[]" value="'.$customers_statuses_array[$i]['id'].'"'.$checked.'> '.$customers_statuses_array[$i]['text'].'<br>';
		  }		  
		  $group .='</td>
		  </tr></table>';
		  echo $group;		  
		  }
		break;
		case 'saveCD':
		$cVal = twe_db_prepare_input($_POST['val']);
		$cID = twe_db_prepare_input($_POST['cid']);
		$tmp_cName = twe_db_prepare_input($_POST['key']);
		$cName_pat = explode('[',$tmp_cName);		 
		$cName = $cName_pat[0];		
		$lang_id = str_replace(']','',$cName_pat[1]);
		$categories_query = $db->Execute("select * from ".TABLE_CATEGORIES_DESCRIPTION." where language_id = '".$lang_id."' and categories_id = '".$cID."'");
		if ($categories_query->RecordCount() == 0){
		$db->Execute("insert into " . TABLE_CATEGORIES_DESCRIPTION . " (categories_id,language_id) values ('" . $cID . "','".$lang_id."')");
		}
		$db->Execute("UPDATE " . TABLE_CATEGORIES_DESCRIPTION . " SET ".$cName." = '" . $cVal . "' WHERE categories_id = '" . (int)$cID . "' and language_id = '".$lang_id."'");
		$categories = $db->Execute("select categories_name from ".TABLE_CATEGORIES_DESCRIPTION." where language_id = '".$lang_id."' and categories_id = '".$cID."'");
        while (!$categories->EOF) {
		if(twe_not_null($categories->fields['categories_name'])){
		echo twe_draw_pull_down_menu('cPath', twe_get_category_tree(), $current_category_id, 'linkData="action=takecPath" id="createC"');	
		}
		$categories->MoveNext();	
		}
		break;
		case 'saveCDText':
		$cVal = twe_db_prepare_input($_POST['val']);
		$cID = twe_db_prepare_input($_POST['cid']);
		$tmp_cName = twe_db_prepare_input($_POST['key']);
		$cName_pat = explode('[',$tmp_cName);		 
		$cName = $cName_pat[0];		
		$lang_id = str_replace(']','',$cName_pat[1]);
		$product_query = $db->Execute("select * from ".TABLE_CATEGORIES_DESCRIPTION." where language_id = '".$lang_id."' and categories_id = '".$cID."'");
		if ($product_query->RecordCount() == 0){
		$db->Execute("insert into " . TABLE_CATEGORIES_DESCRIPTION . " (categories_id,language_id) values ('" . $cID . "','".$lang_id."')");
		}
		$db->Execute("UPDATE " . TABLE_CATEGORIES_DESCRIPTION . " SET ".$cName." = '" . $cVal . "' WHERE categories_id = '" . (int)$cID . "' and language_id = '".$lang_id."'");
		break;
		case 'upload_Cimg':
		header("Cache-Control: no-cache, must-revalidate");
		$cID = twe_db_prepare_input($_POST['cid']);
		if ($categories_image =twe_try_upload('categories_image', DIR_FS_CATALOG_IMAGES.'categories/','777','')) {
			$categories_image_name = twe_db_input($categories_image->filename);			
		    $db->Execute("update " . TABLE_CATEGORIES . " set categories_image = '" . $categories_image_name . "' where categories_id = '" . (int)$cID . "'");
		    echo '<img src="'.DIR_WS_CATALOG.'images/categories/'.$categories_image_name.'?' . time().'" ><a href="Javascript:void();" linkData="action=removeCimg&img='.$categories_image_name.'&cid='.$cID.'" id="removeCImg"><img border="0" src="'.DIR_WS_IMAGES.'icons/cross.gif"><span class="main">'.TEXT_DELETE.'</span></a>&nbsp;'.$categories_image_name;
			}else{
			$categories_image_name = twe_db_prepare_input($_POST['categories_previous_image']);
		   $db->Execute("update " . TABLE_CATEGORIES . " set categories_image = '" . $categories_image_name . "' where categories_id = '" . (int)$cID . "'");
	  	   echo '<img src="'.DIR_WS_CATALOG.'images/categories/'.$categories_image_name.'?' . time().'" ><a href="Javascript:void();" linkData="action=removeCimg&img='.$categories_image_name.'&cid='.$cID.'" id="removeCImg"><img border="0" src="'.DIR_WS_IMAGES.'icons/cross.gif"><span class="main">'.TEXT_DELETE.'</span></a>&nbsp;'.$categories_image_name;
			}
		break;
		case 'removeCimg':
		$cID = twe_db_prepare_input($_POST['cid']);
		$img = twe_db_prepare_input($_POST['img']);
		$db->Execute("update " . TABLE_CATEGORIES . " set categories_image = '' where categories_id = '" . (int)$cID . "'");
		@ unlink(DIR_FS_CATALOG_IMAGES.'categories/'.$img); 
        echo TEXT_EDIT_CATEGORIES_IMAGE.'<br><form id="upload_c_img" method="post" action="categories.php?rType=ajax" enctype="multipart/form-data" >'.twe_draw_file_field('categories_image', 'id="categories_image"') . twe_draw_hidden_field('categories_previous_image', $img).twe_draw_hidden_field('action', 'upload_Cimg').twe_draw_hidden_field('cid', $cID).'</form><div id="view_c_img"></div>';
		break;
		case 'saveP':
			$pVal = twe_db_prepare_input($_POST['val']);
			$pName = twe_db_prepare_input($_POST['key']);
			$pID = twe_db_prepare_input($_POST['pid']);
			if($pName == 'groups[]'){
			$pName = 'group_ids';
			$group_ids='';
			$pVal = explode(',',$pVal);	
			if(isset($pVal)) foreach($pVal as $b){
			$group_ids .= 'c_'.$b."_group ,";
			}
			$customers_statuses_array=twe_get_customers_statuses();
		     if (strstr($group_ids,'c_all_group')) {
			  $group_ids='c_all_group,';
			  for ($i=0;$n=sizeof($customers_statuses_array),$i<$n;$i++) {
			  $group_ids .='c_'.$customers_statuses_array[$i]['id'].'_group,';
			  }			
		     }
			 $pVal=$group_ids;	
			}
			$db->Execute("UPDATE " . TABLE_PRODUCTS . " SET ".$pName." = '" . $pVal . "' WHERE products_id = '" . (int)$pID . "'");
			if (GROUP_CHECK=='true') {
			$product_query = $db->Execute("select group_ids from " . TABLE_PRODUCTS . " where products_id = '" . (int)$pID . "'");
			$customers_statuses_array = twe_get_customers_statuses();
			$customers_statuses_array=array_merge(array(array('id'=>'all','text'=>TXT_ALL)),$customers_statuses_array);
			
			$group ='<table  class="ui-widget-content ui-corner-all" width="100%" border="0">
			<tr>
			<td style="border-top: 1px solid; border-color: #ff0000;" valign="top" class="main" >'.ENTRY_CUSTOMERS_STATUS.'</td>
			<td style="border-top: 1px solid; border-left: 1px solid; border-color: #ff0000;"  bgcolor="#FFCC33" class="main">';	
			
			for ($i=0;$n=sizeof($customers_statuses_array),$i<$n;$i++) {
			if (strstr($product_query->fields['group_ids'],'c_'.$customers_statuses_array[$i]['id'].'_group')) {
			
			$checked='checked ';
			} else {
			$checked='';
			}
			$group .='<input id="pChecked" type="checkbox" name="groups[]" value="'.$customers_statuses_array[$i]['id'].'" '.$checked.'> '.$customers_statuses_array[$i]['text'].'<br>';
			}
			$group .='</td>
			</tr>
			</table>';	
			echo $group;
			}
		break;
		case 'savePD':
		$pVal = twe_db_prepare_input($_POST['val']);
		$pID = twe_db_prepare_input($_POST['pid']);
		$tmp_pName = twe_db_prepare_input($_POST['key']);
		$pName_pat = explode('[',$tmp_pName);		 
		$pName = $pName_pat[0];		
		$lang_id = str_replace(']','',$pName_pat[1]);
		$product_query = $db->Execute("select * from ".TABLE_PRODUCTS_DESCRIPTION." where language_id = '".$lang_id."' and products_id = '".$pID."'");
		if ($product_query->RecordCount() == 0){
		$db->Execute("insert into " . TABLE_PRODUCTS_DESCRIPTION . " (products_id,language_id) values ('" . $pID . "','".$lang_id."')");
		}
		$db->Execute("UPDATE " . TABLE_PRODUCTS_DESCRIPTION . " SET ".$pName." = '" . $pVal . "' WHERE products_id = '" . (int)$pID . "' and language_id = '".$lang_id."'");
		break;
		case 'savePDText':
		$pVal = twe_db_prepare_input($_POST['val']);
		$pID = twe_db_prepare_input($_POST['pid']);
		$temp_pName = twe_db_prepare_input($_POST['key']);			 
		$lang_temp_id = explode('_',strrchr($temp_pName,'_'));
		$lang_id = $lang_temp_id[1]; 
		$pName = substr($temp_pName,0,-2);
		$product_query = $db->Execute("select * from ".TABLE_PRODUCTS_DESCRIPTION." where language_id = '".$lang_id."' and products_id = '".$pID."'");
		if ($product_query->RecordCount() == 0){
		$db->Execute("insert into " . TABLE_PRODUCTS_DESCRIPTION . " (products_id,language_id) values ('" . $pID . "','".$lang_id."')");
		}
		$db->Execute("UPDATE " . TABLE_PRODUCTS_DESCRIPTION . " SET ".$pName." = '" . $pVal . "' WHERE products_id = '" . (int)$pID . "' and language_id = '".$lang_id."'");
		break;
		case 'savePGp':
		$pVal = twe_db_prepare_input($_POST['val']);
		$pName = twe_db_prepare_input($_POST['key']);
		$pID = twe_db_prepare_input($_POST['pid']);
		$pName = explode('_',$pName);
		$check_query=$db->Execute("SELECT quantity FROM personal_offers_by_customers_status_" . $pName[2] . " WHERE products_id='". $pID."' and quantity='1'");
		if ($check_query->RecordCount()<1) {
			$db->Execute("INSERT INTO personal_offers_by_customers_status_" . $pName[2] . " (price_id, products_id, quantity, personal_offer) VALUES ('', '" . $pID . "', '1', '" . $pVal . "')");
		}else{
			$db->Execute("UPDATE personal_offers_by_customers_status_" . $pName[2] . " SET personal_offer = '" . $pVal . "' WHERE products_id = '" . $pID . "' AND quantity = '1'");
		}
		break;
		case 'uploadimg':
		header("Cache-Control: no-cache, must-revalidate");
		$pID = twe_db_prepare_input($_POST['pid']);
		if ($products_image = twe_try_upload('products_image', DIR_FS_CATALOG_ORIGINAL_IMAGES, '777', '')) {
          $pname_arr = explode('.',$products_image->filename);
          $nsuffix = array_pop($pname_arr);
          $products_image_name = $pID . '_0.' . $nsuffix;
		  $dup_check_query = "SELECT count(*) as total FROM ".TABLE_PRODUCTS." WHERE products_id = '".(int)$pID."'";
          $dup_check = $db->Execute($dup_check_query);
          if ($dup_check->fields['total'] < 2) { @twe_del_image_file($products_image_name); }
          rename(DIR_FS_CATALOG_ORIGINAL_IMAGES.$products_image->filename, DIR_FS_CATALOG_ORIGINAL_IMAGES.$products_image_name);
		  $db->Execute("UPDATE " . TABLE_PRODUCTS . " SET products_image = '" . $products_image_name . "' WHERE products_id = '" . (int)$pID . "'");

		  require(DIR_WS_INCLUDES . 'product_thumbnail_images.php');
		  require(DIR_WS_INCLUDES . 'product_info_images.php');
		  require(DIR_WS_INCLUDES . 'product_popup_images.php');
		  echo twe_image(DIR_WS_CATALOG_THUMBNAIL_IMAGES.$products_image_name.'?' . time(), 'Standard Image').'<br>'.$products_image_name;
	  }else{
		  echo twe_image(DIR_WS_CATALOG_THUMBNAIL_IMAGES.$_POST['products_previous_image_0'], 'Standard Image').'<br>'.$_POST['products_previous_image_0'];
	  }
		break;
		case 'uploadmoreimg':
		header('Content-type: text/html; charset=utf-8');
		$pID = twe_db_prepare_input($_POST['pid']);
		$lan_id = twe_db_prepare_input($_POST['lan_id']);
		if($_POST['lan_id'] =='')$lan_id =0;
		if ($pIMG = &twe_try_upload('mo_pics_'.$lan_id, DIR_FS_CATALOG_ORIGINAL_IMAGES, '777', '')) {
          $pname_arr = explode('.',$pIMG->filename);
          $nsuffix = array_pop($pname_arr);
          $products_image_name = $pID . '_' .($lan_id+1) . '.' . $nsuffix;
		  $dup_check_query = "SELECT count(*) as total FROM ".TABLE_PRODUCTS_MORE_IMAGES." WHERE products_id = '".$pID."' and image_nr = '".($lan_id+1)."'";
          $dup_check = $db->Execute($dup_check_query);
          if ($dup_check->fields['total'] < 2) @twe_del_image_file($products_image_name); 
          //@twe_del_image_file($products_image_name);
		  $db->Execute("delete from " . TABLE_PRODUCTS_MORE_IMAGES . " where products_id = '".$pID."' and image_nr = '".($lan_id+1)."'");

          rename(DIR_FS_CATALOG_ORIGINAL_IMAGES.'/'.$pIMG->filename, DIR_FS_CATALOG_ORIGINAL_IMAGES.'/'.$products_image_name);          	         
	      $mo_img = array('products_id' => twe_db_prepare_input($pID),
					  'image_nr' => twe_db_prepare_input($lan_id+1),
					  'image_name' => twe_db_prepare_input($products_image_name));
         twe_db_perform(TABLE_PRODUCTS_MORE_IMAGES, $mo_img);
		 require(DIR_WS_INCLUDES . 'product_thumbnail_images.php');
		 require(DIR_WS_INCLUDES . 'product_info_images.php');
		 require(DIR_WS_INCLUDES . 'product_popup_images.php');
		 
	     echo twe_image(DIR_WS_CATALOG_THUMBNAIL_IMAGES.$products_image_name.'?' . time(), 'Image '.$lan_id).'<a href="Javascript:void();" linkData="action=removeimg&pid='.$pID.'&lan_id='.$lan_id.'&img='.$products_image_name.'" id="removeImg'.$lan_id.'"><img border="0" src="'.DIR_WS_IMAGES.'icons/cross.gif"><span class="main">'. TEXT_DELETE.'</span></a><span class="main">'.$products_image_name.'</span>';
             }else{
		  $dup= $db->Execute("SELECT image_name FROM ".TABLE_PRODUCTS_MORE_IMAGES." WHERE products_id = '".$pID."' and image_nr = '".($lan_id+1)."'");
	     echo twe_image(DIR_WS_CATALOG_THUMBNAIL_IMAGES.$dup->fields['image_name'].'?' . time(), 'Image '.$lan_id).'<a href="Javascript:void();" linkData="action=removeimg&pid='.$pID.'&lan_id='.$lan_id.'&img='.$dup->fields['image_name'].'" id="removeImg'.$lan_id.'"><img border="0" src="'.DIR_WS_IMAGES.'icons/cross.gif"><span class="main">'. TEXT_DELETE.'</span></a><span class="main">'.$dup->fields['image_name'].'</span>';
		  }
		break;  
		case 'removeimg':
		$pID = twe_db_prepare_input($_POST['pid']);
		$lan_id = twe_db_prepare_input($_POST['lan_id']);
		$img = twe_db_prepare_input($_POST['img']);
		@twe_del_image_file($img); 
		$db->Execute("delete from " . TABLE_PRODUCTS_MORE_IMAGES . " where image_name = '".$img."'");
        echo '<form id="more_upload'.$lan_id.'" method="post" action="categories.php?rType=ajax" enctype="multipart/form-data" >'.twe_draw_file_field('mo_pics_'.$lan_id, 'id="mo_pics_'.$lan_id.'"') . twe_draw_separator('pixel_trans.gif', '10', '15') .twe_draw_hidden_field('action', 'uploadmoreimg').twe_draw_hidden_field('pid', $pID).twe_draw_hidden_field('lan_id', $lan_id).'</form><div id="mo_view'.$lan_id.'"></div>';
		break;
	  }
	  exit;
 	 }
	}//ajax end
  if ($_GET['function']) {
    switch ($_GET['function']) {
      case 'delete':
        $db->Execute("DELETE FROM personal_offers_by_customers_status_" . (int)$_GET['statusID'] . " WHERE products_id = '" . (int)$_GET['pID'] . "' AND quantity = '" . (int)$_GET['quantity'] . "'");
    break;
    }
    twe_redirect(twe_href_link(FILENAME_CATEGORIES, 'cPath=' . $_GET['cPath'] . '&action=new_product&pID=' . (int)$_GET['pID']));
  } 
  if ($_GET['del_function']) {
	switch ($_GET['del_function']) {
		case 'delete' :
			$db->Execute("DELETE FROM personal_offers_by_customers_status_".(int) $_GET['statusID']."
						                     WHERE products_id = '".(int) $_GET['pID']."'
						                     AND quantity    = '".(int) $_GET['quantity']."'");
			break;
	}
	twe_redirect(twe_href_link(FILENAME_CATEGORIES, 'selected_box=catolog&cPath='.$_GET['cPath'].'&action=new_staffel&pID='.(int) $_GET['pID']));
}    
  if ($_GET['action']) {
    switch ($_GET['action']) {
		case 'new_product':
		  if ($_GET['action'] == 'new_product' && $_GET['pID'] == '') {
				$products_id = '';
			   if(!$products_id || $products_id == '') {
				   $new_pid_query_values = $db->Execute("SHOW TABLE STATUS LIKE '" . TABLE_PRODUCTS . "'");
				   $products_id = $new_pid_query_values->fields['Auto_increment'];
				}
				  $db->Execute("insert into " . TABLE_PRODUCTS . " (products_id,products_date_added) values ('" . $products_id . "','".date('Y-m-d')."')");
			  $products_id = $db->Insert_ID();	
			  $languages = twe_get_languages();
			  for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
				  $language_id = $languages[$i]['id'];
				  
				  $db->Execute("insert into " . TABLE_PRODUCTS_DESCRIPTION . " (products_id,language_id) values ('" . $products_id . "','".$language_id."')");
			  }
			  $i = 0;
				$group_values = $db->Execute("SELECT customers_status_id  FROM " . TABLE_CUSTOMERS_STATUS . " WHERE language_id = '" . (int)$_SESSION['languages_id'] . "' AND customers_status_id != '0'");
				while (!$group_values->EOF) {
				  $i++;
				  $group_data[$i] = array('STATUS_ID' => $group_values->fields['customers_status_id']);
			   $group_values->MoveNext(); 
				}
				for ($col = 0, $n = sizeof($group_data); $col < $n+1; $col++) {
				  if ($group_data[$col]['STATUS_ID'] != '') {
				   $db->Execute("INSERT INTO personal_offers_by_customers_status_" . $group_data[$col]['STATUS_ID'] . " (price_id, products_id, quantity, personal_offer) VALUES ('', '" . $products_id . "', '1', '')");
				  }
				}
				   $db->Execute("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . $products_id . "', '" . $current_category_id . "')");
				twe_redirect(twe_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products_id.'&action=new_product'));
		  }
		break;
      case 'setflag':
        if ( ($_GET['flag'] == '0') || ($_GET['flag'] == '1') ) {
          if ($_GET['pID']) {
            twe_set_product_status($_GET['pID'], $_GET['flag']);
          }
          if ($_GET['cID']) {
            twe_set_categories_rekursiv($_GET['cID'], $_GET['flag']);
          }
		  
		  if ($_GET['fID']) {
            twe_set_product_featured($_GET['fID'], $_GET['flag']);
          }
        }

        twe_redirect(twe_href_link(FILENAME_CATEGORIES, 'cPath=' . $_GET['cPath']));
        break;

      case 'new_category':
	  if ($_GET['action'] == 'new_category' && $_GET['cID'] == '') {
	  if (ALLOW_CATEGORY_DESCRIPTIONS == 'true')$_GET['action']=$_GET['action'] . '_ACD';
		$db->Execute("insert into " . TABLE_CATEGORIES . " (parent_id,date_added) values ('" . $current_category_id . "','".TIMEZONE_OFFSET."')");
		$categories_id = $db->Insert_ID();
		 $languages = twe_get_languages();
		for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
		$language_id = $languages[$i]['id'];
		$db->Execute("insert into " . TABLE_CATEGORIES_DESCRIPTION . " (categories_id,language_id) values ('" . $categories_id . "','".$languages[$i]['id']."')");
		}
		twe_redirect(twe_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories_id.'&action=edit_category'));
	  }
	  break;
      case 'edit_category':
        if (ALLOW_CATEGORY_DESCRIPTIONS == 'true')
        $_GET['action']=$_GET['action'] . '_ACD';
      break;
        
      case 'insert_category':
      case 'update_category':
        if (($_POST['edit_x']) || ($_POST['edit_y'])) {
          $_GET['action'] = 'edit_category_ACD';
        } else {
        $categories_id = twe_db_prepare_input($_POST['categories_id']);
        if ($categories_id == '') {
        $categories_id = twe_db_prepare_input($_GET['cID']);
        }
        $sort_order = twe_db_prepare_input($_POST['sort_order']);
        $categories_status = twe_db_prepare_input($_POST['categories_status']);

        // set allowed c.groups
        $group_ids='';
        if(isset($_POST['groups'])) foreach($_POST['groups'] as $b){
        $group_ids .= 'c_'.$b."_group ,";
        }
        $customers_statuses_array=twe_get_customers_statuses();
        if (strstr($group_ids,'c_all_group')) {
        $group_ids='c_all_group,';
         for ($i=0;$n=sizeof($customers_statuses_array),$i<$n;$i++) {
            $group_ids .='c_'.$customers_statuses_array[$i]['id'].'_group,';
         }
        }


        $sql_data_array = array( 'sort_order' => $sort_order,
                                 'group_ids'=>$group_ids,
                                 'categories_status' => $categories_status,
                                 'products_sorting' => twe_db_prepare_input($_POST['products_sorting']),
                                 'products_sorting2' => twe_db_prepare_input($_POST['products_sorting2']),
                                 'categories_template'=>twe_db_prepare_input($_POST['categorie_template']),
                                 'listing_template'=>twe_db_prepare_input($_POST['listing_template']));

        if ($_GET['action'] == 'insert_category') {
          $insert_sql_data = array('parent_id' => $current_category_id,
                                   'date_added' => $today);
          $sql_data_array = twe_array_merge($sql_data_array, $insert_sql_data);
          twe_db_perform(TABLE_CATEGORIES, $sql_data_array);
          $categories_id = $db->Insert_ID();
        } elseif ($_GET['action'] == 'update_category') {
          $update_sql_data = array('last_modified' => $today);
          $sql_data_array = twe_array_merge($sql_data_array, $update_sql_data);
          twe_db_perform(TABLE_CATEGORIES, $sql_data_array, 'update', 'categories_id = \'' . $categories_id . '\'');
        }
        twe_set_groups($categories_id,$group_ids);
        $languages = twe_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $categories_name_array = $_POST['categories_name'];
          $language_id = $languages[$i]['id'];
          $sql_data_array = array('categories_name' => twe_db_prepare_input($categories_name_array[$language_id]));
          if (ALLOW_CATEGORY_DESCRIPTIONS == 'true') {
              $sql_data_array = array('categories_name' => twe_db_prepare_input($_POST['categories_name'][$language_id]),
                                      'categories_heading_title' => twe_db_prepare_input($_POST['categories_heading_title'][$language_id]),
                                      'categories_description' => twe_db_prepare_input($_POST['categories_description'][$language_id]),
                                      'categories_meta_title' => twe_db_prepare_input($_POST['categories_meta_title'][$language_id]),
                                      'categories_meta_description' => twe_db_prepare_input($_POST['categories_meta_description'][$language_id]),
                                      'categories_meta_keywords' => twe_db_prepare_input($_POST['categories_meta_keywords'][$language_id]));
            }
         
          if ($_GET['action'] == 'insert_category') {
            $insert_sql_data = array('categories_id' => $categories_id,
                                     'language_id' => $languages[$i]['id']);
            $sql_data_array = twe_array_merge($sql_data_array, $insert_sql_data);
            twe_db_perform(TABLE_CATEGORIES_DESCRIPTION, $sql_data_array);
          } elseif ($_GET['action'] == 'update_category') {
			   $category_query = $db->Execute("select * from ".TABLE_CATEGORIES_DESCRIPTION." where language_id = '".$languages[$i]['id']."' and categories_id = '".$categories_id."'");
				if ($category_query->RecordCount() == 0) twe_db_perform(TABLE_CATEGORIES_DESCRIPTION, array ('categories_id' => $categories_id, 'language_id' => $languages[$i]['id']));

            twe_db_perform(TABLE_CATEGORIES_DESCRIPTION, $sql_data_array, 'update', 'categories_id = \'' . $categories_id . '\' and language_id = \'' . $languages[$i]['id'] . '\'');
          }
        }
           
           
          if ($categories_image =twe_try_upload('categories_image', DIR_FS_CATALOG_IMAGES.'categories/','777','')) {
			$categories_image_name = twe_db_input($categories_image->filename);
			}else{
			$categories_image_name = twe_db_prepare_input($_POST['categories_previous_image']);
			}
            $db->Execute("update " . TABLE_CATEGORIES . " set categories_image = '" . $categories_image_name . "' where categories_id = '" . (int)$categories_id . "'");
            //}
       if ($_POST['del_cat_pic'] =='yes') {
			@ unlink(DIR_FS_CATALOG_IMAGES.'categories/'.$_POST['categories_previous_image']);
			$db->Execute("UPDATE ".TABLE_CATEGORIES."
						    		                 SET categories_image = ''
						    		               WHERE categories_id    = '".(int) $categories_id."'");
		}
          twe_redirect(twe_href_link(FILENAME_CATEGORIES, 'cPath=' . $_GET['cPath'] . '&cID=' . $categories_id));
        }
        break;

        
      case 'delete_category_confirm':
        if ($_POST['categories_id']) {
          $categories_id = twe_db_prepare_input($_POST['categories_id']);

          $categories = twe_get_category_tree($categories_id, '', '0', '', true);
          $products = array();
          $products_delete = array();

          for ($i = 0, $n = sizeof($categories); $i < $n; $i++) {
            $product_ids = $db->Execute("select products_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . $categories[$i]['id'] . "'");
            while (!$product_ids->EOF) {
              $products[$product_ids->fields['products_id']]['categories'][] = $categories[$i]['id'];
            $product_ids->MoveNext();
			}
          }

          reset($products);
          while (list($key, $value) = each($products)) {
            $category_ids = '';
            for ($i = 0, $n = sizeof($value['categories']); $i < $n; $i++) {
              $category_ids .= '\'' . $value['categories'][$i] . '\', ';
            }
            $category_ids = substr($category_ids, 0, -2);

            $check_query = "select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . $key . "' and categories_id not in (" . $category_ids . ")";
            $check = $db->Execute($check_query);
            if ($check->fields['total'] < '1') {
              $products_delete[$key] = $key;
            }
          }

          // Removing categories can be a lengthy process
          @twe_set_time_limit(0);
          for ($i = 0, $n = sizeof($categories); $i < $n; $i++) {
            twe_remove_category($categories[$i]['id']);
          }

          reset($products_delete);
          while (list($key) = each($products_delete)) {
            twe_remove_product($key);
          }
        }

        twe_redirect(twe_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath));
        break;
      case 'delete_product_confirm':
        if ( ($_POST['products_id']) && (is_array($_POST['product_categories'])) ) {
          $product_id = twe_db_prepare_input($_POST['products_id']);
          $product_categories = $_POST['product_categories'];

          for ($i = 0, $n = sizeof($product_categories); $i < $n; $i++) {
            $db->Execute("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . twe_db_input($product_id) . "' and categories_id = '" . twe_db_input($product_categories[$i]) . "'");
          }

          $product_categories_query = "select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . twe_db_input($product_id) . "'";
          $product_categories = $db->Execute($product_categories_query);

          if ($product_categories->fields['total'] == '0') {
            twe_remove_product($product_id);
          }
        }

        twe_redirect(twe_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath));
        break;
      case 'move_category_confirm':
        if ( ($_POST['categories_id']) && ($_POST['categories_id'] != $_POST['move_to_category_id']) ) {
          $categories_id = twe_db_prepare_input($_POST['categories_id']);
          $new_parent_id = twe_db_prepare_input($_POST['move_to_category_id']);
          $db->Execute("update " . TABLE_CATEGORIES . " set parent_id = '" . twe_db_input($new_parent_id) . "', last_modified = ".TIMEZONE_OFFSET." where categories_id = '" . twe_db_input($categories_id) . "'");
        }

        twe_redirect(twe_href_link(FILENAME_CATEGORIES, 'cPath=' . $new_parent_id . '&cID=' . $categories_id));
        break;
      case 'move_product_confirm':
        $products_id = twe_db_prepare_input($_POST['products_id']);
        $new_parent_id = twe_db_prepare_input($_POST['move_to_category_id']);

        $duplicate_check_query = "select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . twe_db_input($products_id) . "' and categories_id = '" . twe_db_input($new_parent_id) . "'";
        $duplicate_check = $db->Execute($duplicate_check_query);
        if ($duplicate_check->fields['total'] < 1) $db->Execute("update " . TABLE_PRODUCTS_TO_CATEGORIES . " set categories_id = '" . twe_db_input($new_parent_id) . "' where products_id = '" . twe_db_input($products_id) . "' and categories_id = '" . $current_category_id . "'");

        twe_redirect(twe_href_link(FILENAME_CATEGORIES, 'cPath=' . $new_parent_id . '&pID=' . $products_id));
        break;
       case 'update_price':
	   echo price_product($_POST, '', 'insert');	
       break;
	  case 'insert_product':
      case 'update_product':

     // print_r($_POST);
	 // break;
        if (PRICE_IS_BRUTTO=='true' && $_POST['products_price']){
                $_POST['products_price'] = ($_POST['products_price']/(twe_get_tax_rate($_POST['products_tax_class_id'])+100)*100);
         }


        if ( ($_POST['edit_x']) || ($_POST['edit_y']) ) {
          $_GET['action'] = 'new_product';
        } else {
          $products_id = twe_db_prepare_input($_GET['pID']);
          $products_date_available = twe_db_prepare_input($_POST['products_date_available']);

          $products_date_available = (date('Y-m-d') < $products_date_available) ? $products_date_available : 'null';

                  // set allowed c.groups
        $group_ids='';
        if(isset($_POST['groups'])) foreach($_POST['groups'] as $b){
        $group_ids .= 'c_'.$b."_group ,";
        }
        $customers_statuses_array=twe_get_customers_statuses();
        if (strstr($group_ids,'c_all_group')) {
        $group_ids='c_all_group,';
         for ($i=0;$n=sizeof($customers_statuses_array),$i<$n;$i++) {
            $group_ids .='c_'.$customers_statuses_array[$i]['id'].'_group,';
         }
        }

          $sql_data_array = array('products_quantity' => twe_db_prepare_input($_POST['products_quantity']),
                                  'products_model' => twe_db_prepare_input($_POST['products_model']),
                                  'products_price' => twe_db_prepare_input($_POST['products_price']),
                                  'products_sort' => twe_db_prepare_input($_POST['products_sort']),
                                  'group_ids'=>$group_ids,
                                  'products_shippingtime' => twe_db_prepare_input($_POST['shipping_status']),
                                  'products_discount_allowed' => twe_db_prepare_input($_POST['products_discount_allowed']),
                                  'products_date_available' => $products_date_available,
                                  'products_weight' => twe_db_prepare_input($_POST['products_weight']),
                                  'products_status' => twe_db_prepare_input($_POST['products_status']),
                                  'products_tax_class_id' => twe_db_prepare_input($_POST['products_tax_class_id']),
                                  'product_template' => twe_db_prepare_input($_POST['product_template']),
                                  'options_template' => twe_db_prepare_input($_POST['options_template']),
                                  'manufacturers_id' => twe_db_prepare_input($_POST['manufacturers_id']),
                                  'products_fsk18' => twe_db_prepare_input($_POST['products_fsk18']),
								  'products_featured' => twe_db_prepare_input($_POST['products_featured']));


          if(!$products_id || $products_id == '') {
          	 $new_pid_query_values = $db->Execute("SHOW TABLE STATUS LIKE '" . TABLE_PRODUCTS . "'");
          	 $products_id = $new_pid_query_values->fields['Auto_increment'];
          }


 if ($products_image = twe_try_upload('products_image', DIR_FS_CATALOG_ORIGINAL_IMAGES, '777', '')) {
          $pname_arr = explode('.',$products_image->filename);
          $nsuffix = array_pop($pname_arr);
          $products_image_name = $products_id . '_0.' . $nsuffix;
          $dup_check_query = "SELECT count(*) as total FROM ".TABLE_PRODUCTS." WHERE products_image = '".$_POST['products_previous_image_0']."'";
          $dup_check = $db->Execute($dup_check_query);
          if ($dup_check->fields['total'] < 2) { @twe_del_image_file($_POST['products_previous_image_0']); }
          rename(DIR_FS_CATALOG_ORIGINAL_IMAGES.$products_image->filename, DIR_FS_CATALOG_ORIGINAL_IMAGES.$products_image_name);
          $sql_data_array['products_image'] = twe_db_prepare_input($products_image_name);

		  require(DIR_WS_INCLUDES . 'product_thumbnail_images.php');
		  require(DIR_WS_INCLUDES . 'product_info_images.php');
		  require(DIR_WS_INCLUDES . 'product_popup_images.php');

          } else {
          $products_image_name = $_POST['products_previous_image_0'];
          }
          
          if ($_POST['del_mo_pic'] != '') {
          	foreach ($_POST['del_mo_pic'] as $val){
          	$dup_check_query = "SELECT count(*) as total FROM ".TABLE_PRODUCTS_MORE_IMAGES." WHERE image_name = '".$val."'";
          	$dup_check = $db->Execute($dup_check_query);
          	if ($dup_check->fields['total'] < 2) @twe_del_image_file($val);
          	$db->Execute("delete from " . TABLE_PRODUCTS_MORE_IMAGES . " where products_id = '" . twe_db_input($products_id) . "' AND image_name = '".$val."'");
          	}
          }                     
       
          for ($img=0;$img<MORE_PICS;$img++) {          	          	          	
          if ($pIMG = &twe_try_upload('mo_pics_'.$img, DIR_FS_CATALOG_ORIGINAL_IMAGES, '777', '')) {
          $pname_arr = explode('.',$pIMG->filename);
          $nsuffix = array_pop($pname_arr);
          $products_image_name = $products_id . '_' . ($img+1) . '.' . $nsuffix;
          $dup_check_query = "SELECT count(*) as total FROM ".TABLE_PRODUCTS_MORE_IMAGES." WHERE image_name = '".$_POST['products_previous_image_'.($img+1)]."'";
          $dup_check = $db->Execute($dup_check_query);
          if ($dup_check->fields['total'] < 2) @twe_del_image_file($_POST['products_previous_image_'.($img+1)]); 
          @twe_del_image_file($products_image_name);
          rename(DIR_FS_CATALOG_ORIGINAL_IMAGES.'/'.$pIMG->filename, DIR_FS_CATALOG_ORIGINAL_IMAGES.'/'.$products_image_name);          	         

	        $mo_img = array('products_id' => twe_db_prepare_input($products_id),
					  'image_nr' => twe_db_prepare_input($img+1),
					  'image_name' => twe_db_prepare_input($products_image_name));
		  if ($_GET['action'] == 'insert_product'){					  
          	twe_db_perform(TABLE_PRODUCTS_MORE_IMAGES, $mo_img);
		  } elseif ($_GET['action'] == 'update_product' && $_POST['products_previous_image_'.($img+1)]) {
		  	if ($_POST['del_mo_pic']) foreach ($_POST['del_mo_pic'] as $val){ if ($val == $_POST['products_previous_image_'.($img+1)]) twe_db_perform(TABLE_PRODUCTS_MORE_IMAGES, $mo_img);break; }	
		  	twe_db_perform(TABLE_PRODUCTS_MORE_IMAGES, $mo_img, 'update', 'image_name = \'' . twe_db_input($_POST['products_previous_image_'.($img+1)]). '\'');		  	
		  } elseif (!$_POST['products_previous_image_'.($img+1)]){
		  	twe_db_perform(TABLE_PRODUCTS_MORE_IMAGES, $mo_img);
		  }
          //image processing
   require(DIR_WS_INCLUDES . 'product_thumbnail_images.php');
   require(DIR_WS_INCLUDES . 'product_info_images.php');
   require(DIR_WS_INCLUDES . 'product_popup_images.php');

          }
          }
          
          if (isset($_POST['products_image']) && twe_not_null($_POST['products_image']) && ($_POST['products_image'] != 'none')) {
            $sql_data_array['products_image'] = twe_db_prepare_input($_POST['products_image']);
          }

          if ($_GET['action'] == 'insert_product') {
            $insert_sql_data = array('products_date_added' => $today);
            $sql_data_array = twe_array_merge($sql_data_array, $insert_sql_data);
            twe_db_perform(TABLE_PRODUCTS, $sql_data_array);
            $products_id = $db->Insert_ID();
            $db->Execute("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . $products_id . "', '" . $current_category_id . "')");
          } elseif ($_GET['action'] == 'update_product') {
            $update_sql_data = array('products_last_modified' => $today);
            $sql_data_array = twe_array_merge($sql_data_array, $update_sql_data);
            twe_db_perform(TABLE_PRODUCTS, $sql_data_array, 'update', 'products_id = \'' . twe_db_input($products_id) . '\'');          
          }

          $languages = twe_get_languages();
          // Here we go, lets write Group prices into db
          // start  
          $i = 0;
          $group_values = $db->Execute("SELECT customers_status_id  FROM " . TABLE_CUSTOMERS_STATUS . " WHERE language_id = '" . (int)$_SESSION['languages_id'] . "' AND customers_status_id != '0'");
          while (!$group_values->EOF) {
            // load data into array
            $i++;
            $group_data[$i] = array('STATUS_ID' => $group_values->fields['customers_status_id']);
         $group_values->MoveNext(); 
		  }
          for ($col = 0, $n = sizeof($group_data); $col < $n+1; $col++) {
            if ($group_data[$col]['STATUS_ID'] != '') {
              $personal_price = twe_db_prepare_input($_POST['products_price_' . $group_data[$col]['STATUS_ID']]);
              if ($personal_price == '' or $personal_price=='0.0000') {
              $personal_price = '0.00';
              } else {
            if (PRICE_IS_BRUTTO=='true'){
                $personal_price= ($personal_price/(twe_get_tax_rate($_POST['products_tax_class_id']) +100)*100);
          	}
          $personal_price=twe_round($personal_price,PRICE_PRECISION);
			  }

			  $check_query=$db->Execute("SELECT quantity FROM personal_offers_by_customers_status_" . $group_data[$col]['STATUS_ID'] . " WHERE products_id='". $products_id."' and quantity='1'");
			  if ($check_query->RecordCount()<1) {
				  $db->Execute("INSERT INTO personal_offers_by_customers_status_" . $group_data[$col]['STATUS_ID'] . " (price_id, products_id, quantity, personal_offer) VALUES ('', '" . $products_id . "', '1', '" . $personal_price . "')");
			  }else{
				  $db->Execute("UPDATE personal_offers_by_customers_status_" . $group_data[$col]['STATUS_ID'] . " SET personal_offer = '" . $personal_price . "' WHERE products_id = '" . $products_id . "' AND quantity = '1'");
			  }
            }
          }
          // end
          // ok, lets check write new staffelpreis into db (if there is one)
          $i = 0;
          $group_values = $db->Execute("SELECT customers_status_id FROM " . TABLE_CUSTOMERS_STATUS . " WHERE language_id = '" . (int)$_SESSION['languages_id'] . "' AND customers_status_id != '0'");
          while (!$group_values->EOF) {
            // load data into array
            $i++;
            $group_data[$i]=array('STATUS_ID' => $group_values->fields['customers_status_id']);
           $group_values->MoveNext();  
		  }
          for ($col = 0, $n = sizeof($group_data); $col < $n+1; $col++) {
            if ($group_data[$col]['STATUS_ID'] != '') {
              $quantity = twe_db_prepare_input($_POST['products_quantity_staffel_' . $group_data[$col]['STATUS_ID']]);
              $staffelpreis = twe_db_prepare_input($_POST['products_price_staffel_' . $group_data[$col]['STATUS_ID']]);
            if (PRICE_IS_BRUTTO=='true'){
                $staffelpreis= ($staffelpreis/(twe_get_tax_rate($_POST['products_tax_class_id']) +100)*100);
          }
          $staffelpreis=twe_round($staffelpreis,PRICE_PRECISION);

              if ($staffelpreis!='' && $quantity!='') {

              	// ok, lets check entered data to get rid of user faults
                if ($quantity<=1) $quantity=2;
                    $check_query=$db->Execute("SELECT
                                               quantity FROM
                                               personal_offers_by_customers_status_" . $group_data[$col]['STATUS_ID'] . "
                                               WHERE products_id='". $products_id."'
                                               and quantity='".$quantity."'");
                    // dont insert if same qty!
                if ($check_query->RecordCount()<1) {
                	$db->Execute("INSERT INTO personal_offers_by_customers_status_" . $group_data[$col]['STATUS_ID'] . " (price_id, products_id, quantity, personal_offer) VALUES ('', '" . $products_id . "', '" . $quantity . "', '" . $staffelpreis . "')");
                }
              }
            }
          }
          for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
            $language_id = $languages[$i]['id'];
            $sql_data_array = array('products_name' => twe_db_prepare_input($_POST['products_name'][$language_id]),
                                    'products_description' => twe_db_prepare_input($_POST['products_description_'.$language_id]),
                                    'products_short_description' => twe_db_prepare_input($_POST['products_short_description_'.$language_id]),
                                    'products_url' => twe_db_prepare_input($_POST['products_url'][$language_id]),
                                    'products_meta_title' => twe_db_prepare_input($_POST['products_meta_title'][$language_id]),
                                    'products_meta_description' => twe_db_prepare_input($_POST['products_meta_description'][$language_id]),
                                    'products_meta_keywords' => twe_db_prepare_input($_POST['products_meta_keywords'][$language_id]));

            if ($_GET['action'] == 'insert_product') {
              $insert_sql_data = array('products_id' => $products_id,
                                       'language_id' => $language_id);
              $sql_data_array = twe_array_merge($sql_data_array, $insert_sql_data);

              twe_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array);
            } elseif ($_GET['action'] == 'update_product') {
				$product_query = $db->Execute("select * from ".TABLE_PRODUCTS_DESCRIPTION." where language_id = '".$language_id."' and products_id = '".$products_id."'");
				if ($product_query->RecordCount() == 0) twe_db_perform(TABLE_PRODUCTS_DESCRIPTION, array ('products_id' => $products_id, 'language_id' => $language_id));

              twe_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array, 'update', 'products_id = \'' . twe_db_input($products_id) . '\' and language_id = \'' . $language_id . '\'');
            }
          }

          twe_redirect(twe_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products_id));
        }
        break;
      case 'copy_to_confirm':

        if(isset($_POST['cat_ids']) && $_POST['copy_as'] == 'link') {
        $products_id = twe_db_prepare_input($_POST['products_id']);

        foreach($_POST['cat_ids'] as $key){
              $check_query = "select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . $products_id . "' and categories_id = '" . $key . "'";
              $check = $db->Execute($check_query);
              if ($check->fields['total'] < '1') {
                $db->Execute("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . $products_id . "', '" . $key . "')");
              } else  {
              $messageStack->add_session(ERROR_CANNOT_LINK_TO_SAME_CATEGORY, 'error');
            }

        }
        break;
        }


        if ( (twe_not_null($_POST['products_id'])) && (twe_not_null($_POST['categories_id'])) ) {
          $products_id = twe_db_prepare_input($_POST['products_id']);

          $categories_id = twe_db_prepare_input($_POST['categories_id']);
          if(isset($_POST['cat_ids'])) {
          $cat_ids=$_POST['cat_ids'];
          } else {
          $cat_ids=array('0'=>$categories_id);
          }

          foreach($cat_ids as $key) {
          $categories_id=$key;

          if ($_POST['copy_as'] == 'link') {
            if ($_POST['categories_id'] != $current_category_id) {
              $check_query = "select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . twe_db_input($products_id) . "' and categories_id = '" . twe_db_input($categories_id) . "'";
              $check = $db->Execute($check_query);
              if ($check->fields['total'] < '1') {
                $db->Execute("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . twe_db_input($products_id) . "', '" . twe_db_input($categories_id) . "')");
              }
            } else {
              $messageStack->add_session(ERROR_CANNOT_LINK_TO_SAME_CATEGORY, 'error');
            }
          } elseif ($_POST['copy_as'] == 'duplicate') {
            $product_query = "select
                                           products_quantity,
                                           products_model,
                                           products_shippingtime,
                                           group_ids,
                                           products_sort,
                                           products_image,
                                           products_price,
                                           products_discount_allowed,
                                           products_date_available,
                                           products_weight,
                                           products_tax_class_id,
                                           manufacturers_id,
                                           product_template,
                                           options_template,
                                           products_fsk18,
										   products_featured
                                           from " . TABLE_PRODUCTS . "
                                           where products_id = '" . twe_db_input($products_id) . "'";

            $product = $db->Execute($product_query);
            $db->Execute("insert into " . TABLE_PRODUCTS . "
                          (products_quantity,
                          products_model,
                          products_shippingtime,
                          group_ids,
                          products_sort,
                          products_image,
                          products_price,
                          products_discount_allowed,
                          products_date_added,
                          products_date_available,
                          products_weight,
                          products_status,
                          products_tax_class_id,
                          manufacturers_id,
                          product_template,
                          options_template,
                          products_fsk18,
						  products_featured)
                          values
                          ('" . $product->fields['products_quantity'] . "',
                          '" . $product->fields['products_model'] . "',
                          '" . $product->fields['products_shippingtime'] . "',
                          '" . $product->fields['group_ids'] . "',
                          '" . $product->fields['products_sort'] . "',
                          '" . $product->fields['products_image'] . "',
                          '" . $product->fields['products_price'] . "',
                          '" . $product->fields['products_discount_allowed'] . "',
                          ".TIMEZONE_OFFSET.",
                          '" . $product->fields['products_date_available'] . "',
                          '" . $product->fields['products_weight'] . "',
                          '0',
                          '" . $product->fields['products_tax_class_id'] . "',
                          '" . $product->fields['manufacturers_id'] . "',
                          '" . $product->fields['product_template'] . "',
                          '" . $product->fields['options_template'] . "',
                          '" . $product->fields['products_fsk18'] . "',
						  '" . $product->fields['products_featured'] . "'
                          )");

            $dup_products_id = $db->Insert_ID();

            $description = $db->Execute("select
                                               language_id,
                                               products_name,
                                               products_description,
                                               products_short_description,
                                               products_meta_title,
                                               products_meta_description,
                                               products_meta_keywords,
                                               products_url
                                               from " . TABLE_PRODUCTS_DESCRIPTION . "
                                               where products_id = '" . twe_db_input($products_id) . "'");
            $old_products_id=twe_db_input($products_id);
            while (!$description->EOF) {
              $db->Execute("insert into " . TABLE_PRODUCTS_DESCRIPTION . "
                            (products_id,
                            language_id,
                            products_name,
                            products_description,
                            products_short_description,
                            products_meta_title,
                            products_meta_description,
                            products_meta_keywords,
                            products_url,
                            products_viewed)
                            values (
                            '" . $dup_products_id . "',
                            '" . $description->fields['language_id'] . "',
                            '" . addslashes($description->fields['products_name']) . "',
                            '" . addslashes($description->fields['products_description']) . "',
                            '" . addslashes($description->fields['products_short_description']) . "',
                            '" . addslashes($description->fields['products_meta_title']) . "',
                            '" . addslashes($description->fields['products_meta_description']) . "',
                            '" . addslashes($description->fields['products_meta_keywords']) . "',
                            '" . $description->fields['products_url'] . "',
                            '0')");
            $description->MoveNext();
			}

            $db->Execute("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . $dup_products_id . "', '" . twe_db_input($categories_id) . "')");
            $mo_images = twe_get_more_images($products_id);
            if (isset($mo_images)){
            foreach ($mo_images as $mo_img){
           $db->Execute("insert into " . TABLE_PRODUCTS_MORE_IMAGES . " (products_id, image_nr, image_name) values ('". $dup_products_id . "', '" . $mo_img['image_nr'] . "', '" . $mo_img['image_name'] . "')");
           }
          }
          $products_id = $dup_products_id;


          $i = 0;
          $group_values = $db->Execute("SELECT customers_status_id FROM " . TABLE_CUSTOMERS_STATUS . " WHERE language_id = '" . (int)$_SESSION['languages_id'] . "' AND customers_status_id != '0'");
          while (!$group_values->EOF) {
            // load data into array
            $i++;
            $group_data[$i]=array('STATUS_ID' => $group_values->fields['customers_status_id']);
          $group_values->MoveNext();
		  }
          for ($col = 0, $n = sizeof($group_data); $col < $n+1; $col++) {
            if ($group_data[$col]['STATUS_ID'] != '') {

            $copy_data=$db->Execute("SELECT
                                      quantity,
                                      personal_offer
                                      FROM personal_offers_by_customers_status_" . $group_data[$col]['STATUS_ID'] . "
                                      WHERE products_id='".$old_products_id."'");
            while (!$copy_data->EOF) {

                $db->Execute("INSERT INTO
                personal_offers_by_customers_status_" . $group_data[$col]['STATUS_ID'] . "
                (price_id, products_id, quantity, personal_offer)
                 VALUES ('', '" . $products_id . "', '" . $copy_data->fields['quantity']. "', '" . $copy_data->fields['personal_offer'] . "')");
             $copy_data->MoveNext();
            }
          }
         }
        }
       }
      }
        twe_redirect(twe_href_link(FILENAME_CATEGORIES, 'cPath=' . $categories_id . '&pID=' . $products_id));
        break;
   }
 }

  // check if the catalog image directory exists
  if (is_dir(DIR_FS_CATALOG_IMAGES)) {
    if (!is_writeable(DIR_FS_CATALOG_IMAGES.'product_images/')) $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE, 'error');
  } else {
    $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST, 'error');
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $_SESSION['language_charset']; ?>"> 
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
<?php
if (USE_SPAW == 'true') {
	$query="SELECT code FROM ". TABLE_LANGUAGES ." WHERE languages_id='".$_SESSION['languages_id']."'";
    $data=$db->Execute($query);
	if($data->fields['code'] == 'tw'){
   $data->fields['code'] = 'zh';	
    }
	$languages = twe_get_languages();
?>
<script type="text/javascript" src="includes/modules/fckeditor/fckeditor.js"></script>
<script type="text/javascript">
	window.onload = function()
		{<?php
	if ($_GET['action'] == 'new_category_ACD' || $_GET['action'] == 'edit_category_ACD') {
		for ($i = 0; $i < sizeof($languages); $i ++) {
			echo twe_wysiwyg('categories_description', $data->fields['code'], $languages[$i]['id']);
		}
	}
	if ($_GET['action'] == 'new_product') {
		for ($i = 0; $i < sizeof($languages); $i ++) {
			echo twe_wysiwyg('products_description', $data->fields['code'], $languages[$i]['id']);
			echo twe_wysiwyg('products_short_description', $data->fields['code'], $languages[$i]['id']);
		}
	}
?>}
</script><?php
}
?>
<?php require('includes/includes.js.php'); ?>
<script type="text/javascript" language="javascript" src="../ext/jquery/jquery.form.js"></script>
<script type="text/javascript" language="javascript" src="../ext/jquery/jquery.blockUI.js"></script>
<?php if(isset($_GET['cID'])){?>
<?php require('includes/categories.js.php'); ?>
<?php } ?> 
<?php if(isset($_GET['pID'])){?>
<?php require('includes/products.js.php'); ?>
<?php }?>
</head>
<!--<body style="margin: 0; background-color: #FFFFFF" onLoad='hide_browse();'>-->

<body style="margin: 0; background-color: #FFFFFF">
<div id="spiffycalendar" class="text"></div>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td class="columnLeft2" width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  //----- new_category / edit_category (when ALLOW_CATEGORY_DESCRIPTIONS is 'true') -----
  if ($_GET['action'] == 'new_category_ACD' || $_GET['action'] == 'edit_category_ACD') {
  include(DIR_WS_MODULES.'categories/new_categorie.php');
?>
<script type="text/javascript">
$("#Tabbed_categorie").tabs();
</script>
<?php
  } elseif ($_GET['action'] == 'new_category_preview') {
  // removed
  } elseif ($_GET['action'] == 'new_product') {
  include(DIR_WS_MODULES.'categories/new_product.php');
?>
<script type="text/javascript">
$("#Tabbed_product").tabs();
<?php if (MORE_PICS > 0){ ?>
$("#Tabbed_mopic").tabs();
<?php } ?>
</script>
<?php 
}elseif ($_GET['action'] == 'new_staffel') {
  include(DIR_WS_MODULES.'categories/group_prices.php');
  } elseif ($_GET['action'] == 'new_product_preview') {
  // preview removed
  } else {
  if (!$cPath) $cPath = '0';
  include(DIR_WS_MODULES.'categories/categories_view.php');
  }
  
?>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>