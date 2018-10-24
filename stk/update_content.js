// JavaScript Document
var xmlHttp;
var json_object = new Object();
var rel = false;

function updateprod(pid,pmodel,pdesc,pqty)
{
xmlHttp=GetXmlHttpObject()
if (xmlHttp==null)
 {
 alert ("Browser does not support HTTP Request")
 return
 }
document.getElementById("pid").innerHTML= pid;
document.getElementById("pmodel").innerHTML= pmodel;
document.getElementById("pdesc").innerHTML= pdesc;
document.getElementById("pqty").innerHTML= pqty;
document.adj.elements["adj_qty"].focus();
document.getElementById("relate").innerHTML= "處理中....";

var urlp="update_content.php"
urlp=urlp+"?action=query&model="+pmodel;

xmlHttp.onreadystatechange=stateChanged
xmlHttp.open("GET",urlp,true)
xmlHttp.send(null)

//打開計算按鍵
document.adj.elements["adj_qty"].disabled = false;
document.adj.elements["btn"].disabled = false;
//alert (ppp)

}

function calc()
{
xmlHttp=GetXmlHttpObject()
if (xmlHttp==null)
 {
 alert ("Browser does not support HTTP Request")
 return
 }

var pid = document.getElementById('pid').innerHTML;
var pmodel = document.getElementById('pmodel').innerHTML;
var qty = document.getElementById('pqty').innerHTML;
var adjqty = document.adj.elements["adj_qty"].value;
var err_msg = "";

if (rel == false)
{
alert ("還不趕快設關聯~~");
return;
}

// 檢查是否為數值
if (adjqty === '0' )
{
alert ("請輸入數字");
return;
}

// 檢查是否為數值
if (isNumeric(adjqty,0,1)==false)
{
alert ("輸入數字不正確");
return;
}

//檢查下架時的數量是否為負數
var total = eval(qty) + eval(adjqty);
if (total < 0)
{
alert ("線上數量不足以下架");
return;
}
              
for (var i=0,j=json_object.length;i<j;i++)
  {
    if (json_object[i]['qty'] <= (json_object[i]['unit']*adjqty))
      {
        err_msg += json_object[i]['desc'] + " 數量不足\n\n";
      }
  }
if (err_msg != "")
 {
  alert (err_msg);
  return;
 }
document.getElementById("relate").innerHTML= "處理中....";
document.getElementById('pqty').innerHTML = total;
document.getElementById('id'+pid).innerHTML = total;
if (total == 0)
  {
   document.getElementById('status'+pid).innerHTML = "<font color = 'red'><b>0</b></font>";
  }
  else
  {
  document.getElementById('status'+pid).innerHTML = "1";
  }
var url="update_content.php"
url=url+"?pid="+pid+"&model="+pmodel+"&pqty="+qty+"&adjqty="+adjqty;

xmlHttp.onreadystatechange=stateChanged
xmlHttp.open("GET",url,true)
xmlHttp.send(null)

//alert (ppp)
}

function stateChanged() 
{ 
var jsonstr = "";

if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 { 
json_object = eval("(" + xmlHttp.responseText + ")");

if (json_object['0']['model'] == "na")  // php echo none data
  {
  jsonstr = "<p><font color='red'>查無關聯產品</font></p>";
  rel = false;
  }
else
 {
  for (var i=0,j=json_object.length;i<j;i++)
    {
      jsonstr += "<p>"+json_object[i]['model']+"<br>"+json_object[i]['desc']+"<br>"+"用量: "+json_object[i]['unit']+"<br>"+"數量: "+json_object[i]['qty']+"<br></p>";
      rel = true;
      }  // EOF for
  }  //EOF if
 document.getElementById("relate").innerHTML= jsonstr;

 } 
}

function GetXmlHttpObject()
{
var xmlHttp=null;
try
 {
 // Firefox, Opera 8.0+, Safari
 xmlHttp=new XMLHttpRequest();
 }
catch (e)
 {
 //Internet Explorer
 try
  {
  xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
  }
 catch (e)
  {
  xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
 }
return xmlHttp;
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
