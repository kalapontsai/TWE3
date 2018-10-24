<?php
/* --------------------------------------------------------------
   $Id: new_categorie.php,v 1.5 2004/03/16 15:01:16 oldpa   Exp $

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
    if ( ($_GET['cID']) && (!$_POST) ) {
      $category_query = "select c.categories_id,c.group_ids, cd.language_id, cd.categories_name, cd.categories_heading_title, cd.categories_description, cd.categories_meta_title, cd.categories_meta_description, cd.categories_meta_keywords, c.categories_image, c.sort_order, c.date_added, c.last_modified, c.products_sorting, c.products_sorting2, c.categories_template, c.listing_template from " . TABLE_NEWS_CATEGORIES . " c, " . TABLE_NEWS_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and c.categories_id = '" . $_GET['cID'] . "'";
      $category = $db->Execute($category_query);

      $cInfo = new objectInfo($category->fields);
    } elseif ($_POST) {
      $cInfo = new objectInfo($_POST);
      $categories_name = $_POST['categories_name'];
      $categories_heading_title = $_POST['categories_heading_title'];
      $categories_description = $_POST['categories_description'];
      $categories_meta_title = $_POST['categories_meta_title'];
      $categories_meta_description = $_POST['categories_meta_description'];
      $categories_meta_keywords = $_POST['categories_meta_keywords'];
      $categories_url = $_POST['categories_url'];
    } else {
      $cInfo = new objectInfo(array());
    }

    $languages = twe_get_languages();

    $text_new_or_edit = ($_GET['action']=='new_category_ACD') ? TEXT_INFO_HEADING_NEW_CATEGORY : TEXT_INFO_HEADING_EDIT_CATEGORY;
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo sprintf($text_new_or_edit, twe_output_generated_category_path($current_category_id)); ?></td>
            <td class="pageHeading" align="right"><?php echo twe_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <?php
          $form_action = ($_GET['cID']) ? 'update_category' : 'insert_category';
    echo twe_draw_form('new_category', FILENAME_NEWS_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $_GET['cID'] . '&action='.$form_action, 'post', 'enctype="multipart/form-data"'); ?>



        <td><table border="0" cellspacing="0" cellpadding="2"><tr><td  colspan="2">
 <table bgcolor="f3f3f3" style="border: 1px solid; border-color: #cccccc;" "width="100%"  border="0">
<tr>
            <td class="main" width="200"><?php echo TEXT_EDIT_CATEGORIES_IMAGE; ?></td>
            <td class="main"><?php echo twe_draw_file_field('categories_image') . '<br>' . twe_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . $cInfo->categories_image . twe_draw_hidden_field('categories_previous_image', $cInfo->categories_image); ?></td>
          </tr>
          <tr><td colspan="2"><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td></tr>
                <tr>
          <?php
        $files=array();
 if ($dir= opendir(DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/module/news_product_listing/')){
 while  (($file = readdir($dir)) !==false) {
        if (is_file( DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/module/news_product_listing/'.$file) and ($file !="index.html")){
        $files[]=array(
                        'id' => $file,
                        'text' => $file);
        }//if
        } // while
        closedir($dir);
 }
 $default_array=array();
 // set default value in dropdown!
if ($content['content_file']=='') {
$default_array[]=array('id' => 'default','text' => TEXT_SELECT);
$default_value=$cInfo->listing_template;
$files=array_merge($default_array,$files);
} else {
$default_array[]=array('id' => 'default','text' => TEXT_NO_FILE);
$default_value=$cInfo->listing_template;
$files=array_merge($default_array,$files);
}
echo '<td class="main">'.TEXT_CHOOSE_INFO_TEMPLATE_LISTING.':</td>';
echo '<td><span class="main">'.twe_draw_pull_down_menu('listing_template',$files,$default_value);
?>
        </span></td>
      </tr>
                      <tr>
          <?php
        $files=array();
 if ($dir= opendir(DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/module/news_categorie_listing/')){
 while  (($file = readdir($dir)) !==false) {
        if (is_file( DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/module/news_categorie_listing/'.$file) and ($file !="index.html")){
        $files[]=array(
                        'id' => $file,
                        'text' => $file);
        }//if
        } // while
        closedir($dir);
 }
 $default_array=array();
 // set default value in dropdown!
if ($content['content_file']=='') {
$default_array[]=array('id' => 'default','text' => TEXT_SELECT);
$default_value=$cInfo->categories_template ;
$files=array_merge($default_array,$files);
} else {
$default_array[]=array('id' => 'default','text' => TEXT_NO_FILE);
$default_value=$cInfo->categories_template ;
$files=array_merge($default_array,$files);
}
echo '<td class="main">'.TEXT_CHOOSE_INFO_TEMPLATE_CATEGORIE.':</td>';
echo '<td><span class="main">'.twe_draw_pull_down_menu('categorie_template',$files,$default_value);
?>
        </span></td>
      </tr>
      <tr>
<?php
$order_array='';
$order_array=array(
                   array('id' => 'pd.products_name','text'=>TXT_NAME),                   
                   array('id' => 'p.products_sort','text'=>TXT_SORT)
                   );
?>
            <td class="main"><?php echo TEXT_EDIT_PRODUCT_SORT_ORDER; ?>:</td>
            <td class="main"><?php echo twe_draw_pull_down_menu('products_sorting',$order_array,$cInfo->products_sorting); ?></td>
          </tr>
          <tr>
<?php
$order_array='';
$order_array=array(array('id' => 'ASC','text'=>'ASC (1 first)'),
                   array('id' => 'DESC','text'=>'DESC (1 last)'));
?>
          <td class="main"><?php echo TEXT_EDIT_PRODUCT_SORT_ORDER; ?>:</td>
            <td class="main"><?php echo twe_draw_pull_down_menu('products_sorting2',$order_array,$cInfo->products_sorting2); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_EDIT_SORT_ORDER; ?></td>
            <td class="main"><?php echo twe_draw_input_field('sort_order', $cInfo->sort_order, 'size="2"'); ?></td>
          </tr>
<?php

if (GROUP_CHECK=='true') {
$customers_statuses_array = twe_get_customers_statuses();
$customers_statuses_array=array_merge(array(array('id'=>'all','text'=>TXT_ALL)),$customers_statuses_array);
?>
<tr>
<td style="border-top: 1px solid; border-color: #ff0000;" valign="top" class="main" ><?php echo ENTRY_CUSTOMERS_STATUS; ?></td>
<td style="border-top: 1px solid; border-left: 1px solid; border-color: #ff0000;"  bgcolor="#FFCC33" class="main">
<?php

for ($i=0;$n=sizeof($customers_statuses_array),$i<$n;$i++) {
if (strstr($category->fields['group_ids'],'c_'.$customers_statuses_array[$i]['id'].'_group')) {

$checked='checked ';
} else {
$checked='';
}
echo '<input type="checkbox" name="groups[]" value="'.$customers_statuses_array[$i]['id'].'"'.$checked.'> '.$customers_statuses_array[$i]['text'].'<br>';
}
?>
</td>
</tr>
<?php
}
?>
</table></td></tr></table>
<div id="Tabbed_news_categorie">
  <ul>
<?php    for ($i=0; $i<sizeof($languages); $i++) { ?>
           <li><a href="#<?php echo $languages[$i]['name']?>" class="<?php echo $languages[$i]['name']?>"><?php echo twe_image(DIR_WS_LANGUAGES.$languages[$i]['directory'].'/admin/images/'.$languages[$i]['image'])?></a></li>
<?php } ?>
</ul>
<?php    for ($i=0; $i<sizeof($languages); $i++) { ?>
<div id="<?php echo $languages[$i]['name']?>">
          <table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="main"><?php echo TEXT_EDIT_CATEGORIES_NAME; ?></td>
            <td class="main"><?php echo twe_image(DIR_WS_LANGUAGES.$languages[$i]['directory'].'/admin/images/'.$languages[$i]['image']) . '&nbsp;' . twe_draw_input_field('categories_name[' . $languages[$i]['id'] . ']', (($categories_name[$languages[$i]['id']]) ? stripslashes($categories_name[$languages[$i]['id']]) : twe_get_news_categories_name($cInfo->categories_id, $languages[$i]['id']))); ?></td>
          </tr>
			<tr>
            <td class="main"><?php echo TEXT_EDIT_CATEGORIES_HEADING_TITLE; ?></td>
            <td class="main"><?php echo twe_image(DIR_WS_LANGUAGES.$languages[$i]['directory'].'/admin/images/'.$languages[$i]['image']) . '&nbsp;' . twe_draw_input_field('categories_heading_title[' . $languages[$i]['id'] . ']', (($categories_name[$languages[$i]['id']]) ? stripslashes($categories_name[$languages[$i]['id']]) : twe_get_news_categories_heading_title($cInfo->categories_id, $languages[$i]['id']))); ?></td>
          </tr>
          <tr>
            <td class="main" valign="top"><?php  echo TEXT_EDIT_CATEGORIES_DESCRIPTION; ?></td>
            <td><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" valign="top"><?php echo twe_image(DIR_WS_LANGUAGES.$languages[$i]['directory'].'/admin/images/'.$languages[$i]['image']); ?>&nbsp;</td>
                <td class="main"><?php echo twe_draw_textarea_field('categories_description[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (($categories_description[$languages[$i]['id']]) ? stripslashes($categories_description[$languages[$i]['id']]) : twe_get_news_categories_description($cInfo->categories_id, $languages[$i]['id']))); ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td class="main" valign="top"><?php  echo TEXT_META_TITLE; ?></td>
            <td><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" valign="top"><?php echo twe_image(DIR_WS_LANGUAGES.$languages[$i]['directory'].'/admin/images/'.$languages[$i]['image']); ?>&nbsp;</td>
                <td class="main"><?php echo twe_draw_textarea_field('categories_meta_title[' . $languages[$i]['id'] . ']', 'soft', '70', '2', (($categories_meta_title[$languages[$i]['id']]) ? stripslashes($categories_meta_title[$languages[$i]['id']]) : twe_get_news_categories_meta_title($cInfo->categories_id, $languages[$i]['id']))); ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
           <tr>
            <td class="main" valign="top"><?php  echo TEXT_META_DESCRIPTION; ?></td>
            <td><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" valign="top"><?php echo twe_image(DIR_WS_LANGUAGES.$languages[$i]['directory'].'/admin/images/'.$languages[$i]['image']); ?>&nbsp;</td>
                <td class="main"><?php echo twe_draw_textarea_field('categories_meta_description[' . $languages[$i]['id'] . ']', 'soft', '70', '3', (($categories_meta_description[$languages[$i]['id']]) ? stripslashes($categories_meta_description[$languages[$i]['id']]) : twe_get_news_categories_meta_description($cInfo->categories_id, $languages[$i]['id']))); ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
           <tr>
            <td class="main" valign="top"><?php  echo TEXT_META_KEYWORDS; ?></td>
            <td><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" valign="top"><?php echo twe_image(DIR_WS_LANGUAGES.$languages[$i]['directory'].'/admin/images/'.$languages[$i]['image']); ?>&nbsp;</td>
                <td class="main"><?php echo twe_draw_textarea_field('categories_meta_keywords[' . $languages[$i]['id'] . ']', 'soft', '70', '3', (($categories_meta_keywords[$languages[$i]['id']]) ? stripslashes($categories_meta_keywords[$languages[$i]['id']]) : twe_get_news_categories_meta_keywords($cInfo->categories_id, $languages[$i]['id']))); ?></td>
              </tr>
            </table></td>
          </tr></table></div>
<?php } ?>
</div>
<table border="0" cellspacing="0" cellpadding="0">
        <tr><td colspan="2"><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td></tr>

           <tr><td colspan="2"><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td></tr>
        </table></td>
      </tr>
      <tr>
        <td class="main" align="right"><?php echo twe_draw_hidden_field('categories_date_added', (($cInfo->date_added) ? $cInfo->date_added : date('Y-m-d'))) . twe_draw_hidden_field('parent_id', $cInfo->parent_id) . twe_image_submit('button_save.gif', IMAGE_SAVE,'style="cursor:hand" onClick="return confirm(\''.SAVE_ENTRY.'\')"') . '&nbsp;&nbsp;<a href="' . twe_href_link(FILENAME_NEWS_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $_GET['cID']) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
      </form></tr>