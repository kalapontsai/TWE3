// JavaScript Document
/*  ---------------------------------------
  stk_v2.php : TWE305 product stock management for ELHOMEO.COM
  v1.0 20150314
  v1.1 20150408 add check box to decide whether update product status
  
  --------------------------------- */

var xmlHttp;
var json_object = new Object();
var pos;
var orig_q;
   function saveq(qty) {
     orig_q = qty;
   }

   function upt(pid,pqty,pos,status) {
     if (isNumeric(pqty)== false) {
     document.getElementById(pos).innerHTML= "非數字";
     return;
     }
     if (pqty == orig_q){return;}
     
     if ((document.getElementById("status_switch").checked == true) && (status == 0)) {status = 1;}
     
     var str = "stk_v2a.php?action=update&id=" + pid + "&qty=" + pqty + "&pos=" + pos + "&status=" + status;

     if (window.XMLHttpRequest) {
     // code for IE7+, Firefox, Chrome, Opera, Safari
       xmlhttp=new XMLHttpRequest();
       } else { // code for IE6, IE5
       xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
       }

     document.getElementById(pos).innerHTML= "修改中";
     xmlhttp.onreadystatechange=function() {
       if (xmlhttp.readyState==4 && xmlhttp.status==200) {
         if (xmlhttp.responseText){
           document.getElementById(pos).innerHTML=xmlhttp.responseText; }
       }
     }
   xmlhttp.open("GET",str,true);
   xmlhttp.send();
   }



function isNumeric(sText,decimals,negatives) {
	var isNumber=true;
	var numDecimals = 0;
	var validChars = "0123456789";
	if (decimals)  validChars += ".";
	if (negatives) validChars += "-";
	var thisChar;
	for (i = 0; i < sText.length && isNumber == true; i++) {  
		thisChar = sText.charAt(i); 
		if (negatives && thisChar == "-" && i > 0) isNumber = false;
		if (decimals && thisChar == "."){
			numDecimals = numDecimals + 1;
			if (i==0 || i == sText.length-1) isNumber = false;
			if (numDecimals > 1) isNumber = false;
		}
		if (validChars.indexOf(thisChar) == -1) isNumber = false;
	}
	return isNumber;
}
