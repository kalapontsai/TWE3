<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_hide_session_id.inc.php,v 1.1 2003/09/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(html_output.php,v 1.52 2003/03/19); www.oscommerce.com
   (c) 2003	 nextcommerce (twe_hide_session_id.inc.php,v 1.5 2003/08/13); www.nextcommerce.org 

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
 // include needed functions
 require_once(DIR_FS_INC . 'twe_draw_hidden_field.inc.php');
// Hide form elements
  function twe_hide_session_id() {
    global $session_started;

    if ( ($session_started == true) && defined('SID') && twe_not_null(SID) ) {
      return twe_draw_hidden_field(twe_session_name(), twe_session_id());
    }
  }
 ?>
