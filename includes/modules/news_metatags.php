<?php
/* -----------------------------------------------------------------------------------------
   $Id: metatags.php,v 1.8 2004/04/26 10:31:17 oldpa   Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2003	 nextcommerce (metatags.php,v 1.7 2003/08/14); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
$descrip = "艾沙順勢糖球屋提供順勢療法愛用者一個可以交流使用心得、提出問題討論、以及方便快速的購買管道。
順勢療法是由內而外，包括身心的整體療法，每個人的個性，病徵的些微不同都會影響效果。";
$key = "艾沙順勢糖球,順勢療法,同類療法,BOIRON,布瓦宏,";
?>
<meta name="robots" content="<?php echo META_ROBOTS; ?>">
<meta name="language" content="<?php echo $language; ?>">
<meta name="company" content="艾沙順勢糖球屋">
<meta name="copyright" content="&copy; 2011 ELHOMEO.COM 艾沙順勢糖球屋">
<meta name="page-topic" content="艾沙順勢糖球屋-購物車">
<meta name="reply-to" content="service@elhomeo.com">
<meta name="distribution" content="global">
<meta name="revisit-after" content="30 days">
<?php
if (strstr($PHP_SELF, FILENAME_NEWS_PRODUCT_INFO)) {
$product_meta_query = "select pd.products_name,p.products_model,pd.products_meta_title,pd.products_meta_description , pd.products_meta_keywords,pd.products_meta_title from " . TABLE_NEWS_PRODUCTS . " p, " . TABLE_NEWS_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$_GET['newsid'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'";
$product_meta = $db->Execute($product_meta_query);
?>
<META NAME="description" CONTENT="<?php echo $product_meta->fields['products_meta_description']; ?>">
<META NAME="keywords" CONTENT="<?php echo $product_meta->fields['products_meta_keywords']; ?>">
<title><?php echo TITLE.' - '.$product_meta->fields['products_meta_title'].' '.$product_meta->fields['products_name'].' '.$product_meta->fields['products_model']; ?></title>
<?php
} else {
if ($_GET['news_cPath']) {
if (strpos($_GET['news_cPath'],'_')=='1') {
$arr=explode('_',$_GET['news_cPath']);
$news_cPath=$arr[1];
}
$categories_meta_query="SELECT categories_meta_keywords,
                                            categories_meta_description,
                                            categories_meta_title,
                                            categories_name
                                            FROM ".TABLE_NEWS_CATEGORIES_DESCRIPTION."
                                            WHERE categories_id='".$news_cPath."' and
                                            language_id='".$_SESSION['languages_id']."'";
$categories_meta = $db->Execute($categories_meta_query);
if ($categories_meta->fields['categories_meta_keywords']=='') {
$categories_meta->fields['categories_meta_keywords']=META_KEYWORDS;
}
if ($categories_meta->fields['categories_meta_description']=='') {
$categories_meta->fields['categories_meta_description']=META_DESCRIPTION;
}
if ($categories_meta->fields['categories_meta_title']=='') {
$categories_meta->fields['categories_meta_title']=$categories_meta->fields['categories_name'];
}
?>
<META NAME="description" CONTENT="<?php echo $categories_meta->fields['categories_meta_description']; ?>">
<META NAME="keywords" CONTENT="<?php echo $categories_meta->fields['categories_meta_keywords']; ?>">
<title><?php echo TITLE.' - '.$categories_meta->fields['categories_meta_title']; ?></title>
<?php
} else {
?>
<META NAME="description" CONTENT="<?php echo META_DESCRIPTION; ?>">
<META NAME="keywords" CONTENT="<?php echo META_KEYWORDS; ?>">
<title><?php echo TITLE; ?></title>
<?php
}
}
?>