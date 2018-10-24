<?php
/* -----------------------------------------------------------------------------------------
   new function for EXPRESS address format at 20110210 by kadela
   ---------------------------------------------------------------------------------------*/

function twe_address_exp_format($address, $html, $boln, $eoln, $no_name= false) {
//   $company = twe_output_string_protected($address['company']);
    if (isset($address['firstname']) && twe_not_null($address['firstname'])) {
      $firstname = twe_output_string_protected($address['firstname']);
//      $lastname = twe_output_string_protected($address['lastname']);
    } elseif (isset($address['name']) && twe_not_null($address['name'])) {
      $firstname = twe_output_string_protected($address['name']);
      $lastname = '';
    } else {
      $firstname = '';
      $lastname = '';
    }
//	$telephone = twe_output_string_protected($address['telephone']);
	$fax = twe_output_string_protected($address['fax']);
	$exp_type = twe_output_string_protected($address['exp_type']);
	$exp_title = '店名:' . twe_output_string_protected($address['exp_title']);
	$exp_number = '店號:' . twe_output_string_protected($address['exp_number']);

  if ($exp_type == '0') $exp_type = '未定義';
	if ($exp_type == '1') $exp_type = TEXT_EXP_TYPE1;
  if ($exp_type == '2') $exp_type = TEXT_EXP_TYPE2;


    if ($html) {
// HTML Mode
      $HR = '<hr>';
      $hr = '<hr>';
      if ( ($boln == '') && ($eoln == "\n") ) { // Values not specified, use rational defaults
        $CR = '<br>';
        $cr = '<br>';
        $eoln = $cr;
      } else { // Use values supplied
        $CR = $eoln . $boln;
        $cr = $CR;
      }
    } else {
// Text Mode
      $CR = $eoln;
      $cr = $CR;
      $HR = '----------------------------------------';
      $hr = '----------------------------------------';
    }

    $statecomma = ',';
    
// no_name use for checkout_shipping_address.php
    if ($no_name == false){
      $address = $firstname . $cr . $fax . $cr . $exp_type . $cr . $exp_title . $cr . $exp_number . $cr;
    } else
    { $address = $exp_type . $cr . $exp_title . $cr . $exp_number . $cr . $cr;
    }
    if ( (ACCOUNT_COMPANY == 'true') && (twe_not_null($company)) ) {
      $address = $company . $cr . $address;
    }

    return $address;
  }
 ?>
