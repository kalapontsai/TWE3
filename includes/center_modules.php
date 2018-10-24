<?php
/* -----------------------------------------------------------------------------------------
   $Id: center_modules.php,v 1.3 2010/08/12 09:19:32 oldpa Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce  
   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
 
$module_smarty= new Smarty;
$module_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/');
$center_center='';
$center_right='';
$center_left='';
$center_center_down ='';
$tabbed = '';
$sbox = $db->Execute("select * from layout_boxes where layout_box_location > 1 and layout_box_status  = '1' and layout_template ='".CURRENT_TEMPLATE."' order by layout_box_sort_order ASC",'',SQL_CACHE,CACHE_LIFETIME);

  if(DEFAULT_TYPE == 'default'){   
	$center_left=array();
    $center_right=array();
    $center_center=array();
    $center_center_down=array();
    $rows = 0;
    while (!$sbox->EOF){
if($sbox->fields['layout_box_status_single'] == '0' && $sbox->fields['layout_box_location'] == '2'){	
	if($sbox->fields['layout_box_name']!='self'){  	  
   include(DIR_WS_CENTER_MODULES . $sbox->fields['layout_box_name']);    
   }
   if($sbox->fields['layout_box_text']){
   $cctemp = $sbox->fields['layout_box_text'];
   }else{
   $cctemp = '{$'.str_replace('.php', '', $sbox->fields['layout_box_name']).'}';
   }  
   $center_center[]=array('BOXES' => $cctemp);
   }
if($sbox->fields['layout_box_status_single'] == '0' && $sbox->fields['layout_box_location'] == '3'){
	 $rows++;	
   $width = (int)(100 / $rows) . '%';
 	if($sbox->fields['layout_box_name']!='self'){  	
   include(DIR_WS_CENTER_MODULES . $sbox->fields['layout_box_name']);  
   }
   if($sbox->fields['layout_box_text']){
   $cltemp = $sbox->fields['layout_box_text'];
   }else{
   $cltemp = '{$'.str_replace('.php', '', $sbox->fields['layout_box_name']).'}';
   }  
   $center_left[]=array('WIDTH' =>$width, 'BOXES' => $cltemp);  
}   
if($sbox->fields['layout_box_status_single'] == '0' && $sbox->fields['layout_box_location'] == '4'){
	 $rows++;	
 $width = (int)(100 / $rows) . '%';
  	if($sbox->fields['layout_box_name']!='self'){  	  
   include(DIR_WS_CENTER_MODULES . $sbox->fields['layout_box_name']); 
   } 
   if($sbox->fields['layout_box_text']){
   $crtemp = $sbox->fields['layout_box_text'];
   }else{
   $crtemp = '{$'.str_replace('.php', '', $sbox->fields['layout_box_name']).'}';
   }        
   $center_right[]=array('WIDTH' =>$width,'BOXES' =>$crtemp);
	}
if($sbox->fields['layout_box_status_single'] == '0' && $sbox->fields['layout_box_location'] == '5'){	
	if($sbox->fields['layout_box_name']!='self'){	
   include(DIR_WS_CENTER_MODULES . $sbox->fields['layout_box_name']);
   }   
   if($sbox->fields['layout_box_text']){
   $cdtemp = $sbox->fields['layout_box_text'];
   }else{
   $cdtemp = '{$'.str_replace('.php', '', $sbox->fields['layout_box_name']).'}';
   } 
   $center_center_down[]=array('BOXES' => $cdtemp); 
	}					  
   $sbox->MoveNext(); 
}
$default_smarty->assign('center_left',$center_left);  
$default_smarty->assign('center_center',$center_center);  
$default_smarty->assign('center_right',$center_right);  
$default_smarty->assign('center_center_down',$center_center_down); 
$default_smarty->assign('DEFAULT_TYPE','default'); 
}elseif(DEFAULT_TYPE == 'tabbed'){
        include(DIR_WS_CENTER_MODULES . 'center_news.php');
	    include(DIR_WS_CENTER_MODULES . 'shop_content.php');
	$tabbed = array();
     while (!$sbox->EOF){
	if($sbox->fields['layout_box_text']){	  
      $tabbedtemp = $sbox->fields['layout_box_text'];
   }else{
   $tabbedtemp = '{$'.str_replace('.php', '', $sbox->fields['layout_box_name']).'}';
   } 
    $boxtitle = '';
   	   if(twe_not_null($sbox->fields['layout_box_text_title'])){
    $boxtitle = $sbox->fields['layout_box_text_title'];
	   }else{
	$boxtitle = str_replace('.php', '', $sbox->fields['layout_box_name']);
	   }
   	   if(twe_not_null($sbox->fields['layout_box_text_title'])){
   $tabbed[]=array('BOXES_NAME_TITLE' => $boxtitle, 'BOXES_TITLE' => $boxtitle,'BOXES' => $tabbedtemp); 	 
	   }else{
   $tabbed[]=array('BOXES_NAME_TITLE' => $boxtitle, 'BOXES_TITLE' => '{#'.$boxtitle.'#}','BOXES' => $tabbedtemp); 	 
	   }
	   $sbox->MoveNext(); 	 
	 }
$default_smarty->assign('DEFAULT_TYPE','tabbed');  
$default_smarty->assign('TABBED',$tabbed);  
 }
?>