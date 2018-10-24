<?php
/* -----------------------------------------------------------------------------------------
   $Id: getCartBox.php,v 1.34 2011/11/26 10:31:17 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(product_info.php,v 1.94 2003/05/04); www.oscommerce.com 
   (c) 2003      nextcommerce (product_info.php,v 1.46 2003/08/25); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   -----------------------------------------------------------------------------------------*/

  include('includes/application_top.php');
  require_once(DIR_FS_INC . 'twe_check_stock.inc.php');
  require_once(DIR_FS_INC . 'twe_recalculate_price.inc.php');
  require_once(DIR_FS_INC . 'twe_get_products_name.inc.php');
  header('Content-type: text/html; charset='.CHARSET);
  function addProductToCart($productID){
         $_SESSION['cart']->add_cart((int)$productID, $_SESSION['cart']->get_quantity((int)$productID)+1);
       $json = '';
          if (isset($_GET['rType']) && $_GET['rType'] == 'ajax'){
              $json .= '{
                  "success": "true",
				  "total":	"'.$_SESSION['cart']->count_contents().'",
                  "products": "' . $productID . '",
				  "name": "'.twe_get_products_name($productID).TEXT_IN_CART.'"
              }';
          }else{
              twe_redirect(twe_href_link('getCartBox.php'));
          }
        return $json;   
      } 
function removeBoxProductFromCart($productID){
         $_SESSION['cart']->remove($productID);
          
          $json = '';
          if (isset($_GET['rType']) && $_GET['rType'] == 'ajax'){
              $json .= '{
                  "success": "true",
                  "products": "' . $_SESSION['cart']->count_contents() . '"
              }';
          }else{
              twe_redirect(twe_href_link('getCartBox.php'));
          }
        return $json;
      }	 
function addCartProducts($qtys, $ids){
        
          foreach($qtys as $pID => $qty){
              $_SESSION['cart']->add_cart($pID, $_SESSION['cart']->get_quantity(twe_get_uprid($pID, $ids))+$qty, $ids);
          }
          
          $json = '';
          if (isset($_GET['rType']) && $_GET['rType'] == 'ajax'){
              $json .= '{
                  "success": "true",
				  "total": "'.$_SESSION['cart']->count_contents().'",
				  "name": "'.twe_get_products_name($pID).TEXT_IN_CART.'"
              }';
          }else{
              twe_redirect(twe_href_link('getCartBox.php'));
          }
        return $json;
      }	   
 $action = (isset($_POST['action']) ? $_POST['action'] : '');
  if (twe_not_null($action)){
	  switch($action){
		  case 'addinfoProducts':
		  echo addCartProducts($_POST['products_qty'], $_POST['id']);
		  break;
		  case 'addProductToCart':
				echo addProductToCart($_POST['BUYNOW_id']);	  
		  break;
		  case 'removeBoxProduct':
              echo removeBoxProductFromCart($_POST['pID']);
          break;
          case 'getProductsFinal':
	         ob_start();
			include(DIR_WS_INCLUDES . 'buynow/getCartBoxlist.php');
			$products_in_cart = ob_get_contents();
			ob_end_clean();
			echo $products_in_cart;
          break;
		  /*case 'getBoxCartTotals':
		  if ($_SESSION['cart']->count_contents() > 0) {
    		$total_content='<hr><table width="100%"  border="0" cellpadding="2" cellspacing="0"><tr>';
   			$total_price =twe_format_price($_SESSION['cart']->show_total(), $price_special = 0, $calculate_currencies = false);
   			 if ($_SESSION['customers_status']['customers_status_ot_discount_flag'] == '1' && $_SESSION['customers_status']['customers_status_ot_discount'] != '0.00' ) {
     	$total_content .='<td align="right" class="boxText"><font color="#FF0000"><strong>'.TEXT_DISCOUNT.'</strong>'.twe_format_price(twe_recalculate_price(($total_price*(-1)), $_SESSION['customers_status']['customers_status_ot_discount']), $price_special = 1, $calculate_currencies = false).'</font><br><strong>'.TEXT_TOTAL.'</strong>'.twe_format_price(($total_price), $price_special = 1, $calculate_currencies = false).'</td>';	  
    		} else {
     	$total_content .='<td align="right" class="boxText"><strong>'.TEXT_TOTAL.'</strong>'.twe_format_price(($total_price), $price_special = 1, $calculate_currencies = false).'</td>';	  
    		}
		$total_content .='</tr></table>';
          echo '<div class="BoxcartTotals">' . $total_content . '</div>';
		  }
          break;*/
	  }
	  twe_session_close();
      exit;
  }
      
?>