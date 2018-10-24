<?php
/* -----------------------------------------------------------------------------------------
   $Id$ twe_js_lang.php

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(general.php,v 1.3 2002/08/16); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_in_array.inc.php,v 1.3 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   function twe_js_lang($message) {
   	
   	$replace_array = array('%A1','%A2','%A3','%A4','%A5','%A6','%A6','%A7','%A8','%A8','%A9','%AA','%AB','%AC','%AD','%AE','%AF','%AF','%B0','%B1','%B2','%B3','%B4','%B5','%B6','%B7','%B8','%B9','%BA','%BB','%BC','%BD','%BD','%BE','%BF','%C0','%C1','%C2','%C3','%C4','%C5','%C6','%C7','%C8','%C9','%CA','%CB','%CC','%CD','%CE','%CF','%D0','%D1','%D2','%D3','%D4','%D5','%D6','%D7','%D8','%D9','%DA','%DB','%DC','%DD','%DE','%DF','%E0','%E1','%E2','%E3','%E4','%E5','%E6','%E7','%E8','%E9','%EA','%EB','%EC','%ED','%EE','%EF','%F0','%F1','%F2','%F3','%F4','%F5','%F6','%F7','%F8','%F9','%FA','%FB','%FC','%FD','%FE','%FF');
   	$search_array = array('&iexcl;','&cent;','&pound;','&curren;','&yen;','&brkbar;','&brvbar;','&sect;','&die;','&uml;','&copy;','&ordf;','&laquo;','&not;','&shy;','&reg;','&hibar;','&macr;','&deg;','&plusmn;','&sup2;','&sup3;','&acute;','&micro;','&para;','&middot;','&cidil;','&sup1;','&ordm;','&raquo;','&frac14;','&half;','&frac12;','&frac34;','&iquest;','&Agrave;','&Aacute;','&Acirc;','&Atilde;','&Auml;','&Aring;','&AElig;','&Ccedil;','&Egrave;','&Eacute;','&Ecirc;','&Euml;','&Igrave;','&Iacute;','&Icirc;','&Iuml;','&ETH;','&Ntilde;','&Ograve;','&Oacute;','&Ocirc;','&Otilde;','&Ouml;','&times;','&Oslash;','&Ugrave;','&Uacute;','&Ucirc;','&Uuml;','&Yacute;','&THORN;','&szlig;','&agrave;','&aacute;','&acirc;','&atilde;','&auml;','&aring;','&aelig;','&ccedil;','&egrave;','&eacute;','&ecirc;','&euml;','&igrave;','&iacute;','&icirc;','&iuml;','&eth;','&ntilde;','&ograve;','&oacute;','&ocirc;','&otilde;','&ouml;','&divide;','&oslash;','&ugrave;','&uacute;','&ucirc;','&uuml;','&yacute;','&thorn;','&yuml;');  	
   	$message=str_replace($search_array,$replace_array,$message);
   	   	
   	return $message;
   	
   }
   
   
?>
