<?php
/* -----------------------------------------------------------------------------------------
   $Id: orders_edit.inc.php,v 1.1 2005/04/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(database.php,v 1.19 2003/03/22); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_db_perform.inc.php,v 1.3 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  require('includes/application_top.php');
  require_once(DIR_FS_CATALOG.DIR_WS_CLASSES.'class.phpmailer.php');
  require_once(DIR_FS_INC . 'twe_php_mail.inc.php');
  
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  $smarty = new Smarty;

  include(DIR_WS_CLASSES . 'order.php');

  $oID = twe_db_prepare_input($_GET['oID']);
  $step = twe_db_prepare_input($_POST['step']);
  $add_product_categories_id = twe_db_prepare_input($_POST['add_product_categories_id']);
  $add_product_products_id = twe_db_prepare_input($_POST['add_product_products_id']);
  $add_product_quantity = twe_db_prepare_input($_POST['add_product_quantity']);

  // New "Status History" table has different format.
  $OldNewStatusValues = (twe_field_exists(TABLE_ORDERS_STATUS_HISTORY, "old_value") && twe_field_exists(TABLE_ORDERS_STATUS_HISTORY, "new_value"));
  $CommentsWithStatus = twe_field_exists(TABLE_ORDERS_STATUS_HISTORY, "comments");
  $SeparateBillingFields = twe_field_exists(TABLE_ORDERS, "billing_name");
  
  // Optional Tax Rate/Percent
  $AddShippingTax = "0.0"; // e.g. shipping tax of 17.5% is "17.5"

  $orders_statuses = array();
  $orders_status_array = array();
// 後台orders.php 狀態名稱排序 
  $orders_status_query = $db -> Execute("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$_SESSION['languages_id'] . "' ORDER BY orders_status_sort_id");
  while (!$orders_status_query -> EOF) {
    $orders_statuses[] = array('id' => $orders_status_query->fields['orders_status_id'],
                               'text' => $orders_status_query->fields['orders_status_name']);
    $orders_status_array[$orders_status_query->fields['orders_status_id']] = $orders_status_query->fields['orders_status_name'];
    $orders_status_query -> MoveNext();
  }

  $action = (isset($_GET['action']) ? $_GET['action'] : 'edit');
//UPDATE_INVENTORY_QUANTITY_START##############################################################################################################
$order_query = $db -> Execute("select products_id, products_quantity from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$oID . "'");
//UPDATE_INVENTORY_QUANTITY_START##############################################################################################################

  if (twe_not_null($action)) {
    switch ($action) {
    	
	// Update Order
	case 'update_order':
		
		$oID = twe_db_prepare_input($_GET['oID']);
		$order = new order($oID);
		$status = twe_db_prepare_input($_POST['status']);
		
		// Update Order Info
		// 取消帳單地址 , 新增宅配欄位  20110609     移動超商欄位置另一個form 20121214
		$UpdateOrders = "update " . TABLE_ORDERS . " set 
			customers_name = '" . twe_db_input(stripslashes($_POST['update_customer_name'])) . "',
			customers_company = '" . twe_db_input(stripslashes($_POST['update_customer_company'])) . "',
			customers_street_address = '" . twe_db_input(stripslashes($_POST['update_customer_street_address'])) . "',
			customers_suburb = '" . twe_db_input(stripslashes($_POST['update_customer_suburb'])) . "',
			customers_city = '" . twe_db_input(stripslashes($_POST['update_customer_city'])) . "',
			customers_state = '" . twe_db_input(stripslashes($_POST['update_customer_state'])) . "',
			customers_postcode = '" . twe_db_input($_POST['update_customer_postcode']) . "',
			customers_country = '" . twe_db_input(stripslashes($_POST['update_customer_country'])) . "',
			customers_telephone = '" . twe_db_input($_POST['update_customer_telephone']) . "',
			customers_fax = '" . twe_db_input($_POST['update_customer_fax']) . "',
			customers_email_address = '" . twe_db_input($_POST['update_customer_email_address']) . "',
		  delivery_name = '" . twe_db_input(stripslashes($_POST['update_delivery_name'])) . "',
			delivery_company = '" . twe_db_input(stripslashes($_POST['update_delivery_company'])) . "',
			delivery_street_address = '" . twe_db_input(stripslashes($_POST['update_delivery_street_address'])) . "',
			delivery_suburb = '" . twe_db_input(stripslashes($_POST['update_delivery_suburb'])) . "',
			delivery_city = '" . twe_db_input(stripslashes($_POST['update_delivery_city'])) . "',
			delivery_telephone = '" . twe_db_input($_POST['update_delivery_telephone']) . "',
			delivery_fax = '" . twe_db_input($_POST['update_delivery_fax']) . "',
			delivery_state = '" . twe_db_input(stripslashes($_POST['update_delivery_state'])) . "',
			delivery_postcode = '" . twe_db_input($_POST['update_delivery_postcode']) . "',
			delivery_country = '" . twe_db_input(stripslashes($_POST['update_delivery_country'])) . "',
			payment_method = '" . twe_db_input($_POST['update_info_payment_method']) . "',
			cc_type = '" . twe_db_input($_POST['update_info_cc_type']) . "',
			cc_owner = '" . twe_db_input($_POST['update_info_cc_owner']) . "',";
		
		if(substr($update_info_cc_number,0,8) != "(Last 4)")
		$UpdateOrders .= "cc_number = '". $_POST['update_info_cc_number']. "',";
		
		$UpdateOrders .= "cc_cvv = '". $_POST['update_info_cc_cvv']. "',";
		
		$UpdateOrders .= "cc_expires = '". $_POST['update_info_cc_expires']. "',
			orders_status = '" . twe_db_input($status) . "'";
		
		if(!$CommentsWithStatus)
		{
			#$UpdateOrders .= ", comments = '" . twe_db_input($comments) . "'";
		}
	
		$UpdateOrders .= " where orders_id = '" . twe_db_input($oID) . "';";

		$db->Execute($UpdateOrders);
		$order_updated = true;
      	$check_status = $db -> Execute("select customers_name, customers_email_address, orders_status, date_purchased from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
	
		// Update Status History & Email Customer if Necessary
		if ($order->info['orders_status'] != $status)
		{
			// Notify Customer
          		$customer_notified = '0';
			if (isset($_POST['notify']) && ($_POST['notify'] == 'on'))
			{
			  $notify_comments = '';
			  if (isset($_POST['notify_comments']) && ($_POST['notify_comments'] == 'on')) {
			    $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $comments) . "\n\n";
			  }
	  // assign language to template for caching
      $smarty->assign('language', $_SESSION['language']);
      $smarty->caching = false;

      // set dirs manual
	  $smarty->template_dir=DIR_FS_CATALOG.'templates';
	  $smarty->compile_dir=DIR_FS_CATALOG.'templates_c';
	  $smarty->config_dir=DIR_FS_CATALOG.'lang';
	  
	  $smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/');
      $smarty->assign('logo_path',HTTP_SERVER  . DIR_WS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/img/');

	  $smarty->assign('NAME',$check_status->fields['customers_name']);
	  $smarty->assign('ORDER_NR',$oID);
	  $smarty->assign('ORDER_LINK',twe_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $oID, 'SSL'));
	  $smarty->assign('ORDER_DATE',twe_date_long($check_status->fields['date_purchased']));
	  $smarty->assign('NOTIFY_COMMENTS',$comments);
	  $smarty->assign('ORDER_STATUS',$orders_status_array[$status]);
      $html_mail=$smarty->fetch(CURRENT_TEMPLATE . '/admin/mail/'.$order->info['language'].'/change_order_mail.html');
      $txt_mail=$smarty->fetch(CURRENT_TEMPLATE . '/admin/mail/'.$order->info['language'].'/change_order_mail.txt');

      twe_php_mail(EMAIL_BILLING_ADDRESS,EMAIL_BILLING_NAME , $check_status->fields['customers_email_address'], $check_status->fields['customers_name'], '', EMAIL_BILLING_REPLY_ADDRESS, EMAIL_BILLING_REPLY_ADDRESS_NAME, '', '', EMAIL_BILLING_SUBJECT, $html_mail , $txt_mail);
	  $customer_notified = '1';
			}			  
          		
			// "Status History" table has gone through a few 
			// different changes, so here are different versions of
			// the status update. 
			
			// NOTE: Theoretically, there shouldn't be a 
			//       orders_status field in the ORDERS table. It 
			//       should really just use the latest value from 
			//       this status history table.
			
			if($CommentsWithStatus)
			{
			$db -> Execute("insert into " . TABLE_ORDERS_STATUS_HISTORY . "
				(orders_id, orders_status_id, date_added, customer_notified, comments) 
				values ('" . twe_db_input($oID) . "', '" . twe_db_input($status) . "',  ".TIMEZONE_OFFSET." , " . twe_db_input($customer_notified) . ", '" . twe_db_input($comments)  . "')");
			}
			else
			{
				if($OldNewStatusValues)
				{
				$db -> Execute("insert into " . TABLE_ORDERS_STATUS_HISTORY . "
					(orders_id, new_value, old_value, date_added, customer_notified) 
					values ('" . twe_db_input($oID) . "', '" . twe_db_input($status) . "', '" . $order->info['orders_status'] . "', ".TIMEZONE_OFFSET.", " . twe_db_input($customer_notified) . ")");
				}
				else
				{
				$db -> Execute("insert into " . TABLE_ORDERS_STATUS_HISTORY . "
					(orders_id, orders_status_id, date_added, customer_notified) 
					values ('" . twe_db_input($oID) . "', '" . twe_db_input($status) . "',  ".TIMEZONE_OFFSET." , " . twe_db_input($customer_notified) . ")");
				}
			}
		}

		// Update Products
		$RunningSubTotal = 0;
		$RunningTax = 0;
        $update_products = $_POST['update_products'];
		$update_totals = $_POST['update_totals'];
        // CWS EDIT (start) -- Check for existence of subtotals...
        // Do pre-check for subtotal field existence
		$ot_subtotal_found = false;
    	foreach($update_totals as $total_details)
		{
		    extract($total_details,EXTR_PREFIX_ALL,"ot");
			if($ot_class == "ot_subtotal")
			{
			    $ot_subtotal_found = true;
    			break;
			}
		}
		// CWS EDIT (end) -- Check for existence of subtotals...		
        
		foreach($update_products as $orders_products_id => $products_details)
		{
			// Update orders_products Table
			//UPDATE_INVENTORY_QUANTITY_START##############################################################################################################
			if ($products_details["qty"] != $order_query->fields['products_quantity']){
				$differenza_quantita = ($products_details["qty"] - $order_query->fields['products_quantity']);
					$db -> Execute("update " . TABLE_PRODUCTS . " set products_quantity = products_quantity - " . $differenza_quantita . ", products_ordered = products_ordered + " . $differenza_quantita . " where products_id = '" . (int)$order_query->fields['products_id'] . "'");
			}
			//UPDATE_INVENTORY_QUANTITY_END##############################################################################################################
			if($products_details["qty"] > 0){
				$final_price = $products_details["price"] * $products_details["qty"];
                 $Query = "update " . TABLE_ORDERS_PRODUCTS . " set
					products_model = '" . $products_details["model"] . "',
					products_name = '" . str_replace("'", "&#39;", $products_details["name"]) . "',
					products_price ='".$products_details["price"]."',
					final_price = '" . $final_price . "',
					products_tax = '" . $products_details["tax"] . "',
					products_quantity = '" . $products_details["qty"] . "'
					where orders_products_id = '$orders_products_id';";

				$db -> Execute($Query);
                        	
				// Update Tax and Subtotals
				$RunningSubTotal += $products_details["qty"] * $products_details["price"];
				$RunningTax += (($products_details["tax"]/100) * ($products_details["qty"] * $products_details["price"]));
				// Update Any Attributes
				if(IsSet($products_details[attributes]))
				{
					foreach($products_details["attributes"] as $orders_products_attributes_id => $attributes_details)
					{
						$Query = "update " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " set
							products_options = '" . $attributes_details["option"] . "',
							products_options_values = '" . $attributes_details["value"] . "'
							where orders_products_attributes_id = '$orders_products_attributes_id';";
						$db -> Execute($Query);
					}
				}
			}
			else
			{
				// 0 Quantity = Delete
				$Query = "delete from " . TABLE_ORDERS_PRODUCTS . " where orders_products_id = '$orders_products_id';";
				$db -> Execute($Query);
					//UPDATE_INVENTORY_QUANTITY_START##############################################################################################################
					if ($products_details["qty"] != $order_query->fields['products_quantity']){
						$differenza_quantita = ($products_details["qty"] - $order_query->fields['products_quantity']);
						$db -> Execute("update " . TABLE_PRODUCTS . " set products_quantity = products_quantity - " . $differenza_quantita . ", products_ordered = products_ordered + " . $differenza_quantita . " where products_id = '" . (int)$order_query->fields['products_id'] . "'");
					}
					//UPDATE_INVENTORY_QUANTITY_END##############################################################################################################
				$Query = "delete from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_products_id = '$orders_products_id';";
				$db -> Execute($Query);
			}
                $order_query -> MoveNext();
		}

			$RunningShipping=0;	
            $update_totals = $_POST['update_totals'];
			foreach($update_totals as $total_index => $total_details)
			{
				extract($total_details,EXTR_PREFIX_ALL,"ot");
				if($ot_class == "ot_shipping")
				{
					$RunningTax += (($AddShippingTax / 100) * $ot_value);
					$RunningShipping=$ot_value;
				}
			}

		// Update Totals
		
			$RunningTotal = 0;
			$sort_order = 0;
			
			// Do pre-check for Tax field existence
				$ot_tax_found = 0;
				foreach($update_totals as $total_details)
				{
					extract($total_details,EXTR_PREFIX_ALL,"ot");
					if($ot_class == "ot_tax")
					{
						$ot_tax_found = 1;
						break;
					}
				}
				
			foreach($update_totals as $total_index => $total_details)
			{
				extract($total_details,EXTR_PREFIX_ALL,"ot");
				
				if( trim(strtolower($ot_title)) == "tax" || trim(strtolower($ot_title)) == "tax:" )
				{
					if($ot_class != "ot_tax" && $ot_tax_found == 0)
					{
						// Inserting Tax
						$ot_class = "ot_tax";
						$ot_value = "x"; // This gets updated in the next step
						$ot_tax_found = 1;
					}
				}
				
				if( trim($ot_title) && trim($ot_value) )
				{
					$sort_order++;
					
					// Update ot_subtotal, ot_tax, and ot_total classes
						if($ot_class == "ot_subtotal")
						$ot_value = $RunningSubTotal;
						
						if($ot_class == "ot_tax")
						{
						$ot_value = $RunningTax;
						// print "ot_value = $ot_value<br>\n";
						}
						
						if($ot_class == "ot_subtotal_no_tax")
                                                  $ot_value =$RunningSubTotal+(twe_format_price($RunningShipping, $price_special=0, $calculate_currencies=false));
     // CWS EDIT (start) -- Check for existence of subtotals...                        
						if($ot_class == "ot_total")
                        {
                            
						    $ot_value = $RunningTotal ;

                            if ( !$ot_subtotal_found )
                            {
                                // There was no subtotal on this order, lets add the running subtotal in.
                                $ot_value = $ot_value + $RunningSubTotal;
                            }
                        }
     // CWS EDIT (end) -- Check for existence of subtotals...    

					// Set $ot_text (display-formatted value)
						// $ot_text = "\$" . number_format($ot_value, 2, '.', ',');
						
						$order = new order($oID);
						$ot_text = $currencies->format($ot_value, true, $order->info['currency'], $order->info['currency_value']);
						
						if($ot_class == "ot_total")
						$ot_text = "<b>" . $ot_text . "</b>";
					
					if($ot_total_id > 0)
					{
						// In Database Already - Update
						$Query = "update " . TABLE_ORDERS_TOTAL . " set
							title = '$ot_title',
							text = '$ot_text',
							value = '$ot_value',
							sort_order = '$sort_order'
							where orders_total_id = '$ot_total_id'";
						$db -> Execute($Query);
					}
					else
					{
						
						// New Insert
						$Query = "insert into " . TABLE_ORDERS_TOTAL . " set
							orders_id = '$oID',
							title = '$ot_title',
							text = '$ot_text',
							value = '$ot_value',
							class = '$ot_class',
							sort_order = '$sort_order'";
						$db -> Execute($Query);
					}

					  if($ot_class != "ot_subtotal_no_tax")
					  $RunningTotal += $ot_value;
				}
				elseif($ot_total_id > 0)
				{
					// Delete Total Piece
					$Query = "delete from " . TABLE_ORDERS_TOTAL . " where orders_total_id = '$ot_total_id'";
					$db -> Execute($Query);
				}

			}
			
		if ($order_updated)
		{
			$messageStack->add_session(SUCCESS_ORDER_UPDATED, 'success');
		}

		twe_redirect(twe_href_link(FILENAME_ORDER_EDIT, twe_get_all_get_params(array('action')) . 'action=edit'));
		
	break;

	// Add a Product
	case 'add_product':
		if($step == 5)
		{
			// Get Order Info
			$oID = twe_db_prepare_input($_GET['oID']);
			$order = new order($oID);

			$AddedOptionsPrice = 0;

			// Get Product Attribute Info
			if(IsSet($add_product_options))
			{
				foreach($add_product_options as $option_id => $option_value_id)
				{
					$result = $db -> Execute("SELECT * FROM " . TABLE_PRODUCTS_ATTRIBUTES . " pa LEFT JOIN " . TABLE_PRODUCTS_OPTIONS . " po ON po.products_options_id=pa.options_id LEFT JOIN " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov ON pov.products_options_values_id=pa.options_values_id WHERE products_id='$add_product_products_id' and options_id=$option_id and options_values_id=$option_value_id and po.language_id = '" . (int)$_SESSION['languages_id'] . "' and pov.language_id = '" . (int)$_SESSION['languages_id'] . "'");
					###r.1. $row = tep_db_fetch_array($result);
                    $row = $result->fields;

					extract($row, EXTR_PREFIX_ALL, "opt");
					$AddedOptionsPrice += $opt_options_values_price;
					$option_value_details[$option_id][$option_value_id] = array ("options_values_price" => $opt_options_values_price);
					$option_names[$option_id] = $opt_products_options_name;
					$option_values_names[$option_value_id] = $opt_products_options_values_name;
				}
			}

                        
			// Get Product Info
			$InfoQuery = "select p.products_model,p.products_price,pd.products_name,p.products_tax_class_id from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on pd.products_id=p.products_id where p.products_id='$add_product_products_id' and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'";
			$result = $db -> Execute($InfoQuery);
			#$row = twe_db_fetch_array($result);
			extract($result->fields, EXTR_PREFIX_ALL, "p");
			
			$p_products_price=$result->fields["products_price"];
			// Following functions are defined at the bottom of this file
			$CountryID = twe_get_country_id($order->delivery["country"]);
			$ZoneID = twe_get_zone_id($CountryID, $order->delivery["state"]);
			
			$ProductsTax = twe_get_tax_rate($p_products_tax_class_id, $CountryID, $ZoneID);
			
			$Query = "insert into " . TABLE_ORDERS_PRODUCTS . " set
				orders_id = $oID,
				products_id = $add_product_products_id,
				products_model = '$p_products_model',
				products_name = '" . str_replace("'", "&#39;", $p_products_name) . "',
				products_price = '$p_products_price+$AddedOptionsPrice/$add_product_quantity',
				final_price = '" . ($p_products_price + $AddedOptionsPrice)*$add_product_quantity . "',
				products_tax = '$ProductsTax',
				products_quantity = $add_product_quantity;";
			$db -> Execute($Query);
			$new_product_id = $db->Insert_ID();
			//UPDATE_INVENTORY_QUANTITY_START##############################################################################################################
			$db -> Execute("update " . TABLE_PRODUCTS . " set products_quantity = products_quantity - " . $add_product_quantity . ", products_ordered = products_ordered + " . $add_product_quantity . " where products_id = '" . $add_product_products_id . "'");
			//UPDATE_INVENTORY_QUANTITY_END##############################################################################################################
			if(IsSet($add_product_options))
			{
				foreach($add_product_options as $option_id => $option_value_id)
				{
					$Query = "insert into " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " set
						orders_id = $oID,
						orders_products_id = $new_product_id,
						products_options = '" . $option_names[$option_id] . "',
						products_options_values = '" . $option_values_names[$option_value_id] . "',
						options_values_price = '" . $option_value_details[$option_id][$option_value_id]["options_values_price"] . "',
						price_prefix = '+';";
					$db -> Execute($Query);
				}
			}
			
			// Calculate Tax and Sub-Totals
			$order = new order($oID);
			$RunningSubTotal = 0;
			$RunningTax = 0;

			for ($i=0; $i<sizeof($order->products); $i++)
			{
			$RunningSubTotal += ($order->products[$i]['final_price']);			
			$RunningTax += (($order->products[$i]['tax'] / 100) * ($order->products[$i]['final_price']));			
			}
			
			
			// Tax
			$Query = "update " . TABLE_ORDERS_TOTAL . " set
				text = '\$" . number_format($RunningTax, 2, '.', ',') . $order->info['currency'] . "', 
				value = '" . $RunningTax . "'
				where class='ot_tax' and orders_id=$oID";
			$db -> Execute($Query);
			// Sub-Total
			$Query = "update " . TABLE_ORDERS_TOTAL . " set
				text = '\$" . number_format($RunningSubTotal, 2, '.', ',') . $order->info['currency'] . "',
				value = '" . $RunningSubTotal . "'
				where class='ot_subtotal' and orders_id=$oID";
			$db -> Execute($Query);
                        // subtotal_no_tax
			$Query = "select sum(value) as total_value from " . TABLE_ORDERS_TOTAL . " where (class = 'ot_subtotal' or class = 'ot_shipping') and orders_id=$oID";
			$result = $db -> Execute($Query);
			$subtotal_no_tax = $result->fields["total_value"];

			$Query = "update " . TABLE_ORDERS_TOTAL . " set
				text = '<b>\$" . number_format($subtotal_no_tax, 2, '.', ',') . $order->info['currency'] . "</b>',
				value = '" . $subtotal_no_tax . "'
				where class='ot_subtotal_no_tax' and orders_id=$oID";
			$db -> Execute($Query);
			// Total			  
			$Query = "select sum(value) as total_value from " . TABLE_ORDERS_TOTAL . " where class != 'ot_total' and orders_id=$oID";
			  if ($RunningSubTotal!=0) $Query .=" and class != 'ot_subtotal_no_tax'";
			$result = $db -> Execute($Query);
			#$row = twe_db_fetch_array($result);
			$Total = $result->fields["total_value"];

			$Query = "update " . TABLE_ORDERS_TOTAL . " set
				text = '<b>\$" . number_format($Total, 2, '.', ',') . $order->info['currency'] . "</b>',
				value = '" . $Total . "'
				where class='ot_total' and orders_id=$oID";
			$db -> Execute($Query);

			twe_redirect(twe_href_link(FILENAME_ORDER_EDIT, twe_get_all_get_params(array('action')) . 'action=edit'));
		}
	break;
  	case 'update_order_express':               		// Update Express Info
    		$oID = twe_db_prepare_input($_GET['oID']);
	    	$order = new order($oID);
		
		$UpdateOrders = "update " . TABLE_ORDERS . " set 
			delivery_use_exp = '" . twe_db_input(stripslashes($_POST['u_use_exp'])) . "',
			delivery_exp_type = '" . twe_db_input(stripslashes($_POST['u_exp_type'])) . "',
			delivery_exp_title = '" . twe_db_input(stripslashes($_POST['u_exp_title'])) . "',
			delivery_exp_number = '" . twe_db_input(stripslashes($_POST['u_exp_number'])) . "'";
    
    $UpdateOrders .= " where orders_id = '" . twe_db_input($oID) . "';";

		$db->Execute($UpdateOrders);
		$order_updated = true;
  		if ($order_updated)
		{
			$messageStack->add_session(SUCCESS_ORDER_UPDATED, 'success');
		}

		twe_redirect(twe_href_link(FILENAME_ORDER_EDIT, twe_get_all_get_params(array('action')) . 'action=edit'));
		
  break;
    }
  }

  if (($action == 'edit') && isset($_GET['oID'])) {
    $oID = twe_db_prepare_input($_GET['oID']);

    $orders_query = $db -> Execute("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
    $order_exists = true;
    if (!$orders_query->RecordCount()) {
      $order_exists = false;
      $messageStack->add(sprintf(ERROR_ORDER_DOES_NOT_EXIST, $oID), 'error');
    }
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $_SESSION['language_charset']; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<?php require('includes/includes.js.php'); ?>

<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">


<!-- header //-->
<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
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
  if (($action == 'edit') && ($order_exists == true)) {
    $order = new order($oID);
?>

      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?> #<?php echo $oID; ?></td>
            <td class="pageHeading" align="right"><?php echo twe_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td class="pageHeading" align="right"><?php echo '<a href="' . twe_href_link(FILENAME_ORDERS, twe_get_all_get_params(array('action'))) . '">' . twe_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
          </tr>
        </table></td>
      </tr>
      
<!-- Begin Addresses Block -->
      <tr><?php echo twe_draw_form('order_edit', "orders_edit.php", twe_get_all_get_params(array('action','paycc')) . 'action=update_order'); ?>
      <td>
      <table width="100%" border="0"><tr> <td><div align="center">
      <table width="100%" border="0" align="center">
  <!--DWLayoutTable-->
  <tr class="dataTableHeadingRow">
    <td class="dataTableHeadingContent" colspan="2" valign="top"><b> <?php echo ENTRY_CUSTOMER; ?> </b></td>
    <td class="dataTableHeadingContent" width="150" valign="top"><span class="main"><b><?php echo ENTRY_SHIPPING_ADDRESS; ?></b></span></td>
  </tr>
  <tr>
    <td class="main" width="160" valign="top"> <?php echo ENTRY_CUSTOMER_NAME; ?>:</td>
    <td width="150" valign="top"><span class="main">
	<?php echo twe_draw_input_field('update_customer_name', $order->customer['name'], 'maxlength="96"'); ?></span></td>
    <td valign="top"><span class="main">
    <?php echo twe_draw_input_field('update_delivery_name', $order->delivery['name'], 'maxlength="96"'); ?></span></td>
    </span></td>
  </tr>
  <tr>
    <td class="main" valign="top"> <?php echo ENTRY_CUSTOMER_COMPANY; ?>:</td>
    <td valign="top"><span class="main">
    <?php echo twe_draw_input_field('update_customer_company', $order->customer['company'], 'maxlength="96"'); ?></span></td>
    </span></td>
    <td valign="top"><span class="main">
	<?php echo twe_draw_input_field('update_delivery_company', $order->delivery['company'], 'maxlength="96"'); ?></span></td>
    </span></td>
  </tr>
  <tr>
    <td class="main" valign="top"><?php echo ENTRY_CUSTOMER_ADDRESS; ?>:</td>
    <td valign="top"><span class="main">
	<?php echo twe_draw_input_field('update_customer_street_address', $order->customer['street_address'], 'maxlength="96"'); ?></span></td>
    </span></td>
    <td valign="top"><span class="main">
	<?php echo twe_draw_input_field('update_delivery_street_address', $order->delivery['street_address'], 'maxlength="96"'); ?></span></td>
    </span></td>
  </tr>
  <tr>
    <td class="main" valign="top"><?php echo ENTRY_CUSTOMER_SUBURB; ?>:</td>
    <td valign="top"><span class="main">
	<?php echo twe_draw_input_field('update_customer_suburb', $order->customer['suburb'], 'maxlength="96"'); ?></span></td>
    </span></td>
    <td valign="top"><span class="main">
	<?php echo twe_draw_input_field('update_delivery_suburb', $order->delivery['suburb'], 'maxlength="96"'); ?></span></td>
    </span></td>
  </tr>
  <tr>
    <td class="main" valign="top"><?php echo ENTRY_CUSTOMER_CITY; ?>:</td>
    <td valign="top"><span class="main">
	<?php echo twe_draw_input_field('update_customer_city', $order->customer['city'], 'maxlength="96"'); ?></span></td>
    </span></td>
    <td valign="top"><span class="main">
	<?php echo twe_draw_input_field('update_delivery_city', $order->delivery['city'], 'maxlength="96"'); ?></span></td>
    </span></td>
  </tr>
  <tr>
    <td class="main" valign="top"><?php echo ENTRY_CUSTOMER_STATE; ?>:</td>
    <td valign="top"><span class="main">
	<?php echo twe_draw_input_field('update_customer_state', $order->customer['state'], 'maxlength="96"'); ?></span></td>
    </span></td>
    <td valign="top"><span class="main">
	<?php echo twe_draw_input_field('update_delivery_state', $order->delivery['state'], 'maxlength="96"'); ?></span></td>
    </span></td>
  </tr>
  <tr>
    <td class="main" valign="top"> <?php echo ENTRY_CUSTOMER_POSTCODE; ?>:</td>
    <td valign="top"><span class="main">
	<?php echo twe_draw_input_field('update_customer_postcode', $order->customer['postcode'], 'maxlength="96"'); ?></span></td>
    </span></td>
    <td valign="top"><span class="main">
	<?php echo twe_draw_input_field('update_delivery_postcode', $order->delivery['postcode'], 'maxlength="96"'); ?></span></td>
    </span></td>
  </tr>
  <tr>
    <td class="main" valign="top"> <?php echo ENTRY_CUSTOMER_COUNTRY; ?></td>
    <td valign="top"><span class="main">
	<?php echo twe_draw_input_field('update_customer_country', $order->customer['country'], 'maxlength="96"'); ?></span></td>
    </span></td>
    <td valign="top"><span class="main">
	<?php echo twe_draw_input_field('update_delivery_country', $order->delivery['country'], 'maxlength="96"'); ?></span></td>
    </span></td>
  </tr>
  <tr>
    <td class="main" valign="top"> <?php echo ENTRY_TELEPHONE_NUMBER; ?></td>
    <td valign="top"><span class="main">
	<?php echo twe_draw_input_field('update_customer_telephone', $order->customer['telephone'], 'maxlength="96"'); ?></span></td>
    </span></td>
    <td valign="top"><span class="main">
	<?php echo twe_draw_input_field('update_delivery_telephone', $order->delivery['telephone'], 'maxlength="96"'); ?></span></td>
    </span></td>
  </tr>
  
  <tr>
    <td class="main" valign="top"> <?php echo ENTRY_FAX_NUMBER; ?></td>
    <td valign="top"><span class="main">
	<?php echo twe_draw_input_field('update_customer_fax', $order->customer['fax'], 'maxlength="96"'); ?></span></td>
    </span></td>
    <td valign="top"><span class="main">
	<?php echo twe_draw_input_field('update_delivery_fax', $order->delivery['fax'], 'maxlength="96"'); ?></span></td>
    </span></td>
  </tr>
</table>
</div></td></tr></table>
<!-- End Addresses Block -->

      <tr>
	<td><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>

<!-- Begin Phone/Email Block -->
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
      		<tr>
      		  <td class="main"><b><?php echo ENTRY_EMAIL_ADDRESS; ?></b></td>
      		  <td class="main"><input name='update_customer_email_address' size='35' value='<?php echo $order->customer['email_address']; ?>'></td>
      		</tr>
      	</table></td>
      </tr>
      </td>
      </tr>
<!-- End Phone/Email Block -->
      
      <tr>
	<td><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>

<!-- Begin Payment Block -->
      <tr>
	<td><table border="0" cellspacing="0" cellpadding="2">
	  <tr>
	    <td class="main"><b><?php echo ENTRY_PAYMENT_METHOD; ?></b></td>
	    <td class="main"><input name='update_info_payment_method' size='35' value='<?php echo $order->info['payment_method']; ?>'>
	    <?php 
	    if($order->info['payment_method'] != "Credit Card")
	    echo ENTRY_UPDATE_TO_CC;
	    ?></td>
	  </tr>

	<?php if ($order->info['cc_type'] || $order->info['cc_owner'] || $order->info['payment_method'] == "Credit Card" || $order->info['cc_number']) { ?>
	  <!-- Begin Credit Card Info Block -->
	  <tr>
	    <td colspan="2"><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
	  </tr>
	  <tr>
	    <td class="main"><?php echo ENTRY_CREDIT_CARD_TYPE; ?></td>
	    <td class="main"><input name='update_info_cc_type' size='10' value='<?php echo $order->info['cc_type']; ?>'></td>
	  </tr>
	  <tr>
	    <td class="main"><?php echo ENTRY_CREDIT_CARD_OWNER; ?></td>
	    <td class="main"><input name='update_info_cc_owner' size='20' value='<?php echo $order->info['cc_owner']; ?>'></td>
	  </tr>
	  <tr>
	    <td class="main"><?php echo ENTRY_CREDIT_CARD_NUMBER; ?></td>
	    <td class="main"><input name='update_info_cc_number' size='20' value='<?php echo $order->info['cc_number']; ?>'></td>
	  </tr>
	  <tr>
	    <td class="main"><?php echo ENTRY_CREDIT_CARD_CVV; ?></td>
	    <td class="main"><input name='update_info_cc_cvv' size='3' value='<?php echo $order->info['cc_cvv']; ?>'></td>
	  </tr>
	  <tr>
	    <td class="main"><?php echo ENTRY_CREDIT_CARD_EXPIRES; ?></td>
	    <td class="main"><input name='update_info_cc_expires' size='4' value='<?php echo $order->info['cc_expires']; ?>'></td>
	  </tr>
	  <!-- End Credit Card Info Block -->
	<?php } ?>
	</table></td>
      </tr>
<!-- End Payment Block -->
	
      <tr>
	<td><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>

<!-- Begin Products Listing Block -->
      <tr>
	<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
	  <tr class="dataTableHeadingRow">
	    <td class="dataTableHeadingContent" colspan="2"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
	    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></td>
	    <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_TAX; ?></td>
	    <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_UNIT_PRICE; ?></td>
	    <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_PRICE; ?></td>
	  </tr>

	<!-- Begin Products Listings Block -->
	<?
      	// Override order.php Class's Field Limitations
		$index = 0;
		$order->products = array();
		$orders_products_query = $db -> Execute("select * from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$oID . "'");
		#while ($orders_products = twe_db_fetch_array($orders_products_query)) {
      while (!$orders_products_query -> EOF){
		$order->products[$index] = array('qty' => $orders_products_query->fields['products_quantity'],
                                        'name' => str_replace("'", "&#39;", $orders_products_query->fields['products_name']),
                                        'model' => $orders_products_query->fields['products_model'],
                                        'tax' => $orders_products_query->fields['products_tax'],
                                        'price' => $orders_products_query->fields['products_price'],
                                        'final_price' => $orders_products_query->fields['final_price'],
                                        'orders_products_id' => $orders_products_query->fields['orders_products_id']);

		$subindex = 0;
		$attributes_query_string = "select * from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . (int)$oID . "' and orders_products_id = '" . (int)$orders_products_query->fields['orders_products_id'] . "'";
		$attributes_query = $db -> Execute($attributes_query_string);

      while (!$attributes_query -> EOF){
		  $order->products[$index]['attributes'][$subindex] = array('option' => $attributes_query->fields['products_options'],
		                                                           'value' => $attributes_query->fields['products_options_values'],
		                                                           'prefix' => $attributes_query->fields['price_prefix'],
		                                                           'price' => $attributes_query->fields['options_values_price'],
		                                                           'orders_products_attributes_id' => $attributes_query->fields['orders_products_attributes_id']);
		$subindex++;
      $attributes_query -> MoveNext();
		}
		$index++;
      $orders_products_query -> MoveNext();

		}
		
	for ($i=0; $i<sizeof($order->products); $i++) {
		$orders_products_id = $order->products[$i]['orders_products_id'];
		
		$RowStyle = "dataTableContent";
		
		echo '	  <tr class="dataTableRow">' . "\n" .
		   '	    <td class="' . $RowStyle . '" valign="top" align="right">' . "<input name='update_products[$orders_products_id][qty]' size='2' value='" . $order->products[$i]['qty'] . "'>&nbsp;x</td>\n" . 
		   '	    <td class="' . $RowStyle . '" valign="top">' . "<input name='update_products[$orders_products_id][name]' size='64' value='" . $order->products[$i]['name'] . "'>";
		
		// Has Attributes?
		if (sizeof($order->products[$i]['attributes']) > 0) {
			for ($j=0; $j<sizeof($order->products[$i]['attributes']); $j++) {
				$orders_products_attributes_id = $order->products[$i]['attributes'][$j]['orders_products_attributes_id'];
				echo '<br><nobr><small>&nbsp;<i> - ' . 
					 "<input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][option]' size='32' value='" . $order->products[$i]['attributes'][$j]['option'] . "'>" . 
					 ': ' . 
					 "<input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][value]' size='25' value='" . $order->products[$i]['attributes'][$j]['value'];
				if (($order->products[$i]['attributes'][$j]['price'] != '0')&&($order->products[$i]['attributes'][$j]['value']==''))echo ' (' . $order->products[$i]['attributes'][$j]['prefix'] . $currencies->format($order->products[$i]['attributes'][$j]['price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . ')';
				echo "'>";
				echo '</i></small></nobr>';
			}
		}
		
		echo '	    </td>' . "\n" .
		     '	    <td class="' . $RowStyle . '" valign="top">' . "<input name='update_products[$orders_products_id][model]' size='12' value='" . $order->products[$i]['model'] . "'>" . '</td>' . "\n" .
		     '	    <td class="' . $RowStyle . '" align="center" valign="top">' . "<input name='update_products[$orders_products_id][tax]' size='3' value='" . twe_display_tax_value($order->products[$i]['tax']) . "'>" . '%</td>' . "\n" .
                     '	    <td class="' . $RowStyle . '" align="right" valign="top">' . "<input name='update_products[$orders_products_id][price]' size='5' value='" . number_format($order->products[$i]['final_price']/$order->products[$i]['qty'], 2, '.', '') . "'>" . '</td>' . "\n" . 
                     '	    <td class="' . $RowStyle . '" align="right" valign="top">' . $currencies->format($order->products[$i]['final_price'], true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n" . 
		     '	  </tr>' . "\n";
	}
	?>
	<!-- End Products Listings Block -->

	<!-- Begin Order Total Block -->
	  <tr>
	    <td align="right" colspan="6">
	    	<table border="0" cellspacing="0" cellpadding="2" width="100%">
	    	<tr>
	    	<td align='center' valign='top'><br><a href="<? print $PHP_SELF . "?oID=$oID&action=add_product&step=1"; ?>"><u><b><font size='3'><?php echo TEXT_DATE_ORDER_ADDNEW; ?> </font></b></u></a></td>
	    	<td align='right'>
	    	<table border="0" cellspacing="0" cellpadding="2">
<?php

      	// Override order.php Class's Field Limitations
		$totals_query = $db -> Execute("select * from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$oID . "' order by sort_order");
		$order->totals = array();
		#while ($totals = twe_db_fetch_array($totals_query)) {
      while (!$totals_query -> EOF){
         $order->totals[] = array('title' => $totals_query->fields['title'], 'text' => $totals_query->fields['text'], 'class' => $totals_query->fields['class'], 'value' => $totals_query->fields['value'], 'orders_total_id' => $totals_query->fields['orders_total_id']);
         $totals_query -> MoveNext();
         }
      
	$TotalsArray = array();
	for ($i=0; $i<sizeof($order->totals); $i++) {
		$TotalsArray[] = array("Name" => $order->totals[$i]['title'], "Price" => number_format($order->totals[$i]['value'], 2, '.', ''), "Class" => $order->totals[$i]['class'], "TotalID" => $order->totals[$i]['orders_total_id']);
		$TotalsArray[] = array("Name" => "          ", "Price" => "", "Class" => "ot_custom", "TotalID" => "0");
	}
	
	array_pop($TotalsArray);
	foreach($TotalsArray as $TotalIndex => $TotalDetails)
	{
		$TotalStyle = "smallText";
		if(($TotalDetails["Class"] == "ot_subtotal") || ($TotalDetails["Class"] == "ot_total"))
		{
			echo	'	      <tr>' . "\n" .
				'		<td class="main" align="right"><b>' . $TotalDetails["Name"] . '</b></td>' .
				'		<td class="main"><b>' . $TotalDetails["Price"] .
						"<input name='update_totals[$TotalIndex][title]' type='hidden' value='" . trim($TotalDetails["Name"]) . "' size='" . strlen($TotalDetails["Name"]) . "' >" . 
						"<input name='update_totals[$TotalIndex][value]' type='hidden' value='" . $TotalDetails["Price"] . "' size='6' >" . 
						"<input name='update_totals[$TotalIndex][class]' type='hidden' value='" . $TotalDetails["Class"] . "'>\n" . 
						"<input type='hidden' name='update_totals[$TotalIndex][total_id]' value='" . $TotalDetails["TotalID"] . "'>" . '</b></td>' . 
				'	      </tr>' . "\n";
		}
		elseif($TotalDetails["Class"] == "ot_tax")
		{
			echo	'	      <tr>' . "\n" .
				'		<td align="right" class="' . $TotalStyle . '">' . "<input name='update_totals[$TotalIndex][title]' size='" . strlen(trim($TotalDetails["Name"])) . "' value='" . trim($TotalDetails["Name"]) . "'>" . '</td>' . "\n" .
				'		<td class="main"><b>' . $TotalDetails["Price"] .
						"<input name='update_totals[$TotalIndex][value]' type='hidden' value='" . $TotalDetails["Price"] . "' size='6' >" . 
						"<input name='update_totals[$TotalIndex][class]' type='hidden' value='" . $TotalDetails["Class"] . "'>\n" . 
						"<input type='hidden' name='update_totals[$TotalIndex][total_id]' value='" . $TotalDetails["TotalID"] . "'>" . '</b></td>' . 
				'	      </tr>' . "\n";
		}
		else
		{
			echo	'	      <tr>' . "\n" .
				'		<td align="right" class="' . $TotalStyle . '">' . "<input name='update_totals[$TotalIndex][title]' size='" . strlen(trim($TotalDetails["Name"])) . "' value='" . trim($TotalDetails["Name"]) . "'>" . '</td>' . "\n" .
				'		<td align="right" class="' . $TotalStyle . '">' . "<input name='update_totals[$TotalIndex][value]' size='6' value='" . $TotalDetails["Price"] . "'>" . 
						"<input type='hidden' name='update_totals[$TotalIndex][class]' value='" . $TotalDetails["Class"] . "'>" . 
						"<input type='hidden' name='update_totals[$TotalIndex][total_id]' value='" . $TotalDetails["TotalID"] . "'>" . 
						'</td>' . "\n" .
				'	      </tr>' . "\n";
		}
	}
?>
	    	</table>
	    	</td>
	    	</tr>
	    	</table>
	    </td>
	  </tr>
	<!-- End Order Total Block -->

	</table></td>
      </tr>
      	
      <tr>
        <td><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>

      <tr>
        <td class="main"><table border="1" cellspacing="0" cellpadding="5">
          <tr>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_DATE_ADDED; ?></b></td>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_CUSTOMER_NOTIFIED; ?></b></td>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_STATUS; ?></b></td>
            <? if($CommentsWithStatus) { ?>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
            <? } ?>
          </tr>
<?php
    $orders_history_query = $db -> Execute("select * from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . twe_db_input($oID) . "' order by date_added");
    if ($orders_history_query->RecordCount()) {
      while (!$orders_history_query -> EOF){
        echo '          <tr>' . "\n" .
             '            <td class="smallText" align="center">' . twe_datetime_short($orders_history_query->fields['date_added']) . '</td>' . "\n" .
             '            <td class="smallText" align="center">';
        if ($orders_history_query->fields['customer_notified'] == '1') {
          echo twe_image(DIR_WS_ICONS . 'tick.gif', ICON_TICK) . "</td>\n";
        } else {
          echo twe_image(DIR_WS_ICONS . 'cross.gif', ICON_CROSS) . "</td>\n";
        }
        echo '            <td class="smallText">' . $orders_status_array[$orders_history_query->fields['orders_status_id']] . '</td>' . "\n";
        
        if($CommentsWithStatus) {
        echo '            <td class="smallText">' . nl2br(twe_db_output($orders_history_query->fields['comments'])) . '&nbsp;</td>' . "\n";
        }
        
        echo '          </tr>' . "\n";
        $orders_history_query -> MoveNext();
      }
    } else {
        echo '          <tr>' . "\n" .
             '            <td class="smallText" colspan="5">' . TEXT_NO_ORDER_HISTORY . '</td>' . "\n" .
             '          </tr>' . "\n";
    }
?>
        </table></td>
      </tr>

      <tr>
        <td class="main"><br><b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
      </tr>
      <tr>
        <td><?php echo twe_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
      </tr>
      <tr>
        <td class="main">
        <?
        if($CommentsWithStatus) {
        	echo twe_draw_textarea_field('comments', 'soft', '60', '5');
	}
	else
	{
		echo twe_draw_textarea_field('comments', 'soft', '60', '5', $order->info['comments']);
	}
	?>
        </td>
      </tr>
      <tr>
        <td><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>

      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo ENTRY_STATUS; ?></b> <?php echo twe_draw_pull_down_menu('status', $orders_statuses, $order->info['orders_status']); ?></td>
          </tr>
          <tr>
            <td class="main"><b><?php echo ENTRY_NOTIFY_CUSTOMER; ?></b> <?php echo twe_draw_checkbox_field('notify', '', false); ?></td>
          </tr>
          <? if($CommentsWithStatus) { ?>
          <tr>
                <td class="main"><b><?php echo ENTRY_NOTIFY_COMMENTS; ?></b> <?php echo twe_draw_checkbox_field('notify_comments', '', false); ?></td>
          </tr>
          <? } ?>
        </table></td>
      </tr>

      <tr>
	<td align='center' valign="top"><?php echo twe_image_submit('button_update.gif', IMAGE_UPDATE); ?></td>
      </tr>
      </form>

<!-- express_form  //-->
      <tr>
        <td><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><?php echo twe_draw_form('order_edit_express', "orders_edit.php", twe_get_all_get_params(array('action','paycc')) . 'action=update_order_express');
// 檢查是否使用超商  20121214
      $exp_query = $db->Execute("select orders_id, 
                                        delivery_use_exp as use_exp, 
                                        delivery_exp_type as exp_type,
                                        delivery_exp_title as exp_title,
                                        delivery_exp_number as exp_number
                                        from " . TABLE_ORDERS . " where orders_id = '" . $oID . "'");
    if ($exp_query->fields['use_exp']) {
     $use_exp = twe_output_string_protected($exp_query->fields['use_exp']);
   	 $exp_type = twe_output_string_protected($exp_query->fields['exp_type']);
   	 $exp_title = twe_output_string_protected($exp_query->fields['exp_title']);
   	 $exp_number = twe_output_string_protected($exp_query->fields['exp_number']);      

/*     if ($exp_type == '0') $exp_type = '未定義';
  	 if ($exp_type == '1') $exp_type = '統一超商';
     if ($exp_type == '2') $exp_type = '全家超商';     */

     } else {
     $use_exp = '0';
     $exp_type = 'N/A';
   	 $exp_title = 'N/A';
   	 $exp_number = 'N/A';           
     }
?>
  <table>
  <tr>
    <td class="main" valign="top"> <?php echo ENTRY_DELIVERY_EXPRESS; ?></td>
    <td>&nbsp;</td>
    <td valign="top"><span class="main"><input name='u_use_exp' value='<?php echo $use_exp; ?>'>
</span></td>
  </tr>
  <tr>
    <td class="main" valign="top"> <?php echo ENTRY_DELIVERY_EXPRESS_TYPE.$_POST['u_exp_type']; ?></td>
    <td>&nbsp;</td>
    <td valign="top"><span class="main"><input name='u_exp_type' value='<?php echo $exp_type; ?>'>
</span></td>
  </tr>
  <tr>
    <td class="main" valign="top"> <?php echo ENTRY_DELIVERY_EXPRESS_TITLE; ?></td>
    <td>&nbsp;</td>
    <td valign="top"><span class="main"><input name='u_exp_title' value='<?php echo $exp_title; ?>'>
</span></td>
  </tr>
  <tr>
    <td class="main" valign="top"> <?php echo ENTRY_DELIVERY_EXPRESS_NUMBER; ?></td>
    <td>&nbsp;</td>
    <td valign="top"><span class="main"><input name='u_exp_number' value='<?php echo $exp_number; ?>'>
</span></td>
  </tr>
        <tr>
	<td align='center' valign="top"><?php echo twe_image_submit('button_update.gif', IMAGE_UPDATE); ?></td>
      </tr>

</table>
  </td></tr>
      </form>
<!-- express_form_eof //-->

<?php
  }
  
if($action == "add_product")
{
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo ADDING_TITLE; ?> #<?php echo $oID; ?></td>
            <td class="pageHeading" align="right"><?php echo twe_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td class="pageHeading" align="right"><?php echo '<a href="' . twe_href_link(FILENAME_ORDERS, twe_get_all_get_params(array('action'))) . '">' . twe_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
          </tr>
        </table></td>
      </tr>

<?
	// ############################################################################
	//   Get List of All Products
	// ############################################################################

		$result = $db -> Execute("SELECT products_name, p.products_id, categories_name, ptc.categories_id FROM " . TABLE_PRODUCTS . " p LEFT JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd ON pd.products_id=p.products_id LEFT JOIN " . TABLE_PRODUCTS_TO_CATEGORIES . " ptc ON ptc.products_id=p.products_id LEFT JOIN " . TABLE_CATEGORIES_DESCRIPTION . " cd ON cd.categories_id=ptc.categories_id where pd.language_id = '" . (int)$_SESSION['languages_id'] . "' ORDER BY categories_name");
		#while($row = twe_db_fetch_array($result))      {
      while (!$result -> EOF){
 		   extract($result->fields,EXTR_PREFIX_ALL,"db");
			$ProductList[$db_categories_id][$db_products_id] = $db_products_name;
			$CategoryList[$db_categories_id] = $db_categories_name;
			$LastCategory = $db_categories_name;
         $result -> MoveNext();
		}
		
		// ksort($ProductList);
		
		$LastOptionTag = "";
		$ProductSelectOptions = "<option value='0'>Don't Add New Product" . $LastOptionTag . "\n";
		$ProductSelectOptions .= "<option value='0'>&nbsp;" . $LastOptionTag . "\n";
		
		foreach($ProductList as $Category => $Products)
		{
			$ProductSelectOptions .= "<option value='0'>$Category" . $LastOptionTag . "\n";
			$ProductSelectOptions .= "<option value='0'>---------------------------" . $LastOptionTag . "\n";
			asort($Products);
			foreach($Products as $Product_ID => $Product_Name)
			{
				$ProductSelectOptions .= "<option value='$Product_ID'> &nbsp; $Product_Name" . $LastOptionTag . "\n";
			}
			
			if($Category != $LastCategory)
			{
				$ProductSelectOptions .= "<option value='0'>&nbsp;" . $LastOptionTag . "\n";
				$ProductSelectOptions .= "<option value='0'>&nbsp;" . $LastOptionTag . "\n";
			}
		}
	
	
	// ############################################################################
	//   Add Products Steps
	// ############################################################################
	
		print "<tr><td><table border='0'>\n";
		
		// Set Defaults
			if(!IsSet($add_product_categories_id))
			$add_product_categories_id = 0;

			if(!IsSet($add_product_products_id))
			$add_product_products_id = 0;
		
		// Step 1: Choose Category
			print "<tr class=\"dataTableRow\"><form action='$PHP_SELF?oID=$oID&action=$action' method='POST'>\n";
			print "<td class='dataTableContent' align='right'><b>STEP 1:</b></td><td class='dataTableContent' valign='top'>";
			echo ' ' . twe_draw_pull_down_menu('add_product_categories_id', twe_get_category_tree(), $current_category_id, 'onChange="this.form.submit();"');
 			  
 			  //if ($_POST['step']<2) 
			print "<input type='hidden' name='step' value='2'>";
			print "</td>\n";
			print "</form></tr>\n";
			print "<tr><td colspan='3'>&nbsp;</td></tr>\n";

		// Step 2: Choose Product
		if(($step > 1) && ($add_product_categories_id > 0))
		{
			print "<tr class=\"dataTableRow\"><form action='$PHP_SELF?oID=$oID&action=$action' method='POST'>\n";
			print "<td class='dataTableContent' align='right'><b>STEP 2:</b></td><td class='dataTableContent' valign='top'><select name=\"add_product_products_id\" onChange=\"this.form.submit();\">";
			$ProductOptions = "<option value='0'>" .  ADDPRODUCT_TEXT_SELECT_PRODUCT . "\n";
			asort($ProductList[$add_product_categories_id]);
			foreach($ProductList[$add_product_categories_id] as $ProductID => $ProductName)
			{
			$ProductOptions .= "<option value='$ProductID'> $ProductName\n";
			}
			$ProductOptions = str_replace("value='$add_product_products_id'","value='$add_product_products_id' selected", $ProductOptions);
			print $ProductOptions;
			print "</select></td>\n";
			print "<input type='hidden' name='add_product_categories_id' value='$add_product_categories_id'>";
			print "<input type='hidden' name='step' value='3'>";
			print "</form></tr>\n";
			print "<tr><td colspan='3'>&nbsp;</td></tr>\n";
		}

		// Step 3: Choose Options
		if(($step > 2) && ($add_product_products_id > 0))
		{
			// Get Options for Products
			$result = $db -> Execute("SELECT * FROM " . TABLE_PRODUCTS_ATTRIBUTES . " pa LEFT JOIN " . TABLE_PRODUCTS_OPTIONS . " po ON po.products_options_id=pa.options_id LEFT JOIN " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov ON pov.products_options_values_id=pa.options_values_id WHERE products_id='$add_product_products_id' and po.language_id = '" . (int)$_SESSION['languages_id'] . "'");
			
			// Skip to Step 4 if no Options
			if($result->RecordCount() == 0)
			{
				print "<tr class=\"dataTableRow\">\n";
				print "<td class='dataTableContent' align='right'><b>STEP 3:</b></td><td class='dataTableContent' valign='top' colspan='2'><i>No Options - Skipped...</i></td>";
				print "</tr>\n";
				$step = 4;
			}
			else
			{
            while (!$result -> EOF){
 					extract($result->fields,EXTR_PREFIX_ALL,"db");
					$Options[$db_products_options_id] = $db_products_options_name;
					$ProductOptionValues[$db_products_options_id][$db_products_options_values_id] = $db_products_options_values_name;
               $result -> MoveNext();
				}
			
				print "<tr class=\"dataTableRow\"><form action='$PHP_SELF?oID=$oID&action=$action' method='POST'>\n";
				print "<td class='dataTableContent' align='right'><b>STEP 3:</b></td><td class='dataTableContent' valign='top'>";
				foreach($ProductOptionValues as $OptionID => $OptionValues)
				{
					$OptionOption = "<b>" . $Options[$OptionID] . "</b> - <select name='add_product_options[$OptionID]'>";
					foreach($OptionValues as $OptionValueID => $OptionValueName)
					{
					$OptionOption .= "<option value='$OptionValueID'> $OptionValueName\n";
					}
					$OptionOption .= "</select><br>\n";
					
					if(IsSet($add_product_options))
					$OptionOption = str_replace("value='" . $add_product_options[$OptionID] . "'","value='" . $add_product_options[$OptionID] . "' selected",$OptionOption);
					
					print $OptionOption;
				}		
				print "</td>";
				print "<td class='dataTableContent' align='center'><input type='submit' value='" . ADDPRODUCT_TEXT_OPTIONS_CONFIRM . "'>";
				print "<input type='hidden' name='add_product_categories_id' value='$add_product_categories_id'>";
				print "<input type='hidden' name='add_product_products_id' value='$add_product_products_id'>";
				print "<input type='hidden' name='step' value='4'>";
				print "</td>\n";
				print "</form></tr>\n";
			}

			print "<tr><td colspan='3'>&nbsp;</td></tr>\n";
		}

		if($step > 3)
		{
			print "<tr class=\"dataTableRow\"><form action='$PHP_SELF?oID=$oID&action=$action' method='POST'>\n";
			print "<td class='dataTableContent' align='right'><b>STEP 4:</b></td>";
			print "<td class='dataTableContent' valign='top'><input name='add_product_quantity' size='2' value='1'>" . ADDPRODUCT_TEXT_CONFIRM_QUANTITY . "</td>";
			print "<td class='dataTableContent' align='center'><input type='submit' value='" . ADDPRODUCT_TEXT_CONFIRM_ADDNOW . "'>";

			if(IsSet($add_product_options))
			{
				foreach($add_product_options as $option_id => $option_value_id)
				{
					print "<input type='hidden' name='add_product_options[$option_id]' value='$option_value_id'>";
				}
			}
			print "<input type='hidden' name='add_product_categories_id' value='$add_product_categories_id'>";
			print "<input type='hidden' name='add_product_products_id' value='$add_product_products_id'>";
			print "<input type='hidden' name='step' value='5'>";
			print "</td>\n";
			print "</form></tr>\n";
		}
		
		print "</table></td></tr>\n";
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
<?
  ////////////////////////////////////////////////////////////////////////////////////////////////
  //
  // Function    : twe_get_country_id
  //
  // Arguments   : country_name		country name string
  //
  // Return      : country_id
  //
  // Description : Function to retrieve the country_id based on the country's name
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////
  function twe_get_country_id($country_name) {

   global $db;
    $country_id_query = $db -> Execute("select * from " . TABLE_COUNTRIES . " where countries_name = '" . $country_name . "'");

    if (!$country_id_query->RecordCount()) {
      return 0;
    }else {
    return $country_id_row_query->fields['countries_id'];
    }
  }

  ////////////////////////////////////////////////////////////////////////////////////////////////
  //
  // Function    : twe_get_country_iso_code_2
  //
  // Arguments   : country_id		country id number
  //
  // Return      : country_iso_code_2
  //
  // Description : Function to retrieve the country_iso_code_2 based on the country's id
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////
  function twe_get_country_iso_code_2($country_id) {
      global $db;
    $country_iso_query = $db->Execute("select * from " . TABLE_COUNTRIES . " where countries_id = '" . $country_id . "'");
    if ($country_iso_query->RecordCount()>0) {
    return $country_iso_row->fields['countries_iso_code_2'];    
    }else {      
    return 0;
    }
  }

  ////////////////////////////////////////////////////////////////////////////////////////////////
  //
  // Function    : twe_get_zone_id
  //
  // Arguments   : country_id		country id string
  //               zone_name		state/province name
  //
  // Return      : zone_id
  //
  // Description : Function to retrieve the zone_id based on the zone's name
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////
  function twe_get_zone_id($country_id, $zone_name) {
   global $db;
    $zone_id_query = $db -> Execute("select * from " . TABLE_ZONES . " where zone_country_id = '" . $country_id . "' and zone_name = '" . $zone_name . "'");

    if ($zone_id_query->RecordCount()>0) {
      return $zone_id_query->fields['zone_id'];    
    }else {
      return 0;
    }
  }
  
  ////////////////////////////////////////////////////////////////////////////////////////////////
  //
  // Function    : twe_field_exists
  //
  // Arguments   : table	table name
  //               field	field name
  //
  // Return      : true/false
  //
  // Description : Function to check the existence of a database field
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////
  function twe_field_exists($table,$field) {
   global $db;

    $describe_query = $db -> Execute("describe $table");
    while (!$describe_query -> EOF) {
      if ($d_row["Field"] == "$field") {
      return true;
    }
      $describe_query -> MoveNext();
    }
    return false;
  }

  ////////////////////////////////////////////////////////////////////////////////////////////////
  //
  // Function    : twe_html_quotes
  //
  // Arguments   : string	any string
  //
  // Return      : string with single quotes converted to html equivalent
  //
  // Description : Function to change quotes to HTML equivalents for form inputs.
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////
  function twe_html_quotes($string) {
    return str_replace("'", "&#39;", $string);
  }

  ////////////////////////////////////////////////////////////////////////////////////////////////
  //
  // Function    : twe_html_unquote
  //
  // Arguments   : string	any string
  //
  // Return      : string with html equivalent converted back to single quotes
  //
  // Description : Function to change HTML equivalents back to quotes
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////
  function twe_html_unquote($string) {
    return str_replace("&#39;", "'", $string);
  }

require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
