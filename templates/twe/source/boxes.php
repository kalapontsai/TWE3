<?php
/* -----------------------------------------------------------------------------------------
   $Id: boxes.php,v 1.2 2010/09/07 23:02:54 oldpa Exp $   

   TWE-Commerce - community made shopping   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce   
   Released under the GNU General Public License 
---------------------------------------------------------------------------------------*/
  define('DIR_WS_BOXES',DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/boxes/');
   
  $smarty = new smarty;
  $smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/');
 
  $lbox_content='';
  $rbox_content='';
  $sbox_content='';
  

  $sbox = $db->Execute("select layout_box_name, layout_box_text, layout_box_location, layout_box_status_single from layout_boxes where layout_box_status  = '1' and layout_template ='".CURRENT_TEMPLATE."' order by layout_box_sort_order_single, layout_box_sort_order ASC",'',SQL_CACHE,CACHE_LIFETIME);

    $rbox_content=array(); 
	$sbox_content=array();
    $lbox_content=array();
	
    $rows = 0;
    while (!$sbox->EOF){
if($sbox->fields['layout_box_status_single'] >'0'){	
	 $rows++;
   $width = (int)(100 / $rows) . '%';
   	if($sbox->fields['layout_box_name'] !='self'){
   include(DIR_WS_BOXES . $sbox->fields['layout_box_name']); 
   }
   if($sbox->fields['layout_box_text']){
   $stemp = $sbox->fields['layout_box_text'];
   }else{
   $stemp = '{$'.str_replace('.php', '', $sbox->fields['layout_box_name']).'}';
   }   
   $sbox_content[]=array('WIDTH' =>$width,
   						 'BOXES' =>$stemp);  
	} 
if($sbox->fields['layout_box_status_single'] == '0' && $sbox->fields['layout_box_location'] == '0'){	
   if($sbox->fields['layout_box_name'] !='self'){	
   include(DIR_WS_BOXES . $sbox->fields['layout_box_name']);   
   } 
   if($sbox->fields['layout_box_text']){
   $ltemp = $sbox->fields['layout_box_text'];
   }else{
   $ltemp = '{$'.str_replace('.php', '', $sbox->fields['layout_box_name']).'}';
   }
   $lbox_content[]=array('BOXES' => $ltemp); 
   }
    
if($sbox->fields['layout_box_status_single'] == '0' && $sbox->fields['layout_box_location'] == '1'){	
   	if($sbox->fields['layout_box_name'] !='self'){	
   include(DIR_WS_BOXES . $sbox->fields['layout_box_name']); 
   }  
   if($sbox->fields['layout_box_text']){
   $rtemp = $sbox->fields['layout_box_text'];
   }else{
   $rtemp = '{$'.str_replace('.php', '', $sbox->fields['layout_box_name']).'}';
   } 
   $rbox_content[]=array('BOXES' =>$rtemp);
    }
   $sbox->MoveNext(); 
}

$smarty->assign('rbox_box',$rbox_content);  
$smarty->assign('1box_box',$lbox_content);  
$smarty->assign('sbox_box',$sbox_content); 
 
?>