<?php
/* --------------------------------------------------------------
   $Id: product_thumbnail_images.php,v 1.2 2004/03/16 19:24:49 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   --------------------------------------------------------------*/
   
  $a = new image_manipulation(DIR_FS_CATALOG_ORIGINAL_IMAGES . $products_image_name,PRODUCT_IMAGE_THUMBNAIL_WIDTH,PRODUCT_IMAGE_THUMBNAIL_HEIGHT,DIR_FS_CATALOG_THUMBNAIL_IMAGES . $products_image_name,IMAGE_QUALITY,'');

$array=clear_string(PRODUCT_IMAGE_THUMBNAIL_BEVEL);
if (PRODUCT_IMAGE_THUMBNAIL_BEVEL != ''){
$a->bevel($array[0],$array[1],$array[2]);}

$array=clear_string(PRODUCT_IMAGE_THUMBNAIL_GREYSCALE);
if (PRODUCT_IMAGE_THUMBNAIL_GREYSCALE != ''){
$a->greyscale($array[0],$array[1],$array[2]);}

$array=clear_string(PRODUCT_IMAGE_THUMBNAIL_ELLIPSE);
if (PRODUCT_IMAGE_THUMBNAIL_ELLIPSE !== ''){
$a->ellipse($array[0]);}

$array=clear_string(PRODUCT_IMAGE_THUMBNAIL_ROUND_EDGES);
if (PRODUCT_IMAGE_THUMBNAIL_ROUND_EDGES != ''){
$a->round_edges($array[0],$array[1],$array[2]);}

$string=str_replace("'",'',PRODUCT_IMAGE_THUMBNAIL_MERGE);
$string=str_replace(')','',$string);
$string=str_replace('(',DIR_FS_CATALOG_IMAGES,$string);
$array=explode(',',$string);
//$array=clear_string();
if (PRODUCT_IMAGE_THUMBNAIL_MERGE != ''){
$a->merge($array[0],$array[1],$array[2],$array[3],$array[4]);}

$array=clear_string(PRODUCT_IMAGE_THUMBNAIL_FRAME);
if (PRODUCT_IMAGE_THUMBNAIL_FRAME != ''){
$a->frame($array[0],$array[1],$array[2],$array[3]);}

$array=clear_string(PRODUCT_IMAGE_THUMBNAIL_DROP_SHADOW);
if (PRODUCT_IMAGE_THUMBNAIL_DROP_SHADOW != ''){
$a->drop_shadow($array[0],$array[1],$array[2]);}

$array=clear_string(PRODUCT_IMAGE_THUMBNAIL_MOTION_BLUR);
if (PRODUCT_IMAGE_THUMBNAIL_MOTION_BLUR != ''){
$a->motion_blur($array[0],$array[1]);}

	  $a->create();
?>