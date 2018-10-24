<?php
/* -----------------------------------------------------------------------------------------
   $Id: buynow.js.php,v 1.1 2012/09/06 22:13:53 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(general.js,v 1.3 2003/02/10); www.oscommerce.com 
   (c) 2003	 nextcommerce (general.js,v 1.3 2003/08/13); www.nextcommerce.org 
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
  function fixSeoLink($url){
      return str_replace('&amp;', '&', $url);
  }
?>
<script type="text/javascript" src="includes/buynow/buynow.js"></script>
<script type="text/javascript"><!--
  var jqbuynow = buynow;
  jqbuynow.initializing = true;
  jqbuynow.ajaxCharset = '<?php echo 'UTF-8';?>';
      jqbuynow.pageLinks = {
      buynow: '<?php echo fixSeoLink(twe_href_link('getCartBox.php', 'rType=ajax', $request_type));?>',
	  cart:'<?php echo fixSeoLink(twe_href_link(FILENAME_SHOPPING_CART, 'rType=ajax', $request_type));?>',
	  shoppingCart: '<?php echo fixSeoLink(twe_href_link(FILENAME_SHOPPING_CART));?>',
	  Default:'<?php echo fixSeoLink(twe_href_link(FILENAME_DEFAULT));?>'
  }
  
$(document).ready(function (){ 
    jqbuynow.initBuynow();
});
//-->
</script>