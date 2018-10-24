<?php
/* --------------------------------------------------------------
   20180321- fix products_description textarea field value update function - ELHOMEO
   --------------------------------------------------------------
   $Id: includes.js.php,v 1.4 2012/08/29 17:05:18 oldpa Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw

   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(configuration.php,v 1.40 2002/12/29); www.oscommerce.com 
   (c) 2003	 nextcommerce (configuration.php,v 1.16 2003/08/19); www.nextcommerce.org
   (c) 2003-2004 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   --------------------------------------------------------------*/
?>
<script language="javascript"><!--
$(document).ready(function(){	
$('#seldateAvailable').submit(function() {
		var pID = <?php echo $_GET['pID']?>;
		var pVal = $('.cal-TextBox').val();		
		var pName = 'products_date_available';		
			$.ajax({
                url: 'categories.php?rType=ajax',
                data: 'action=saveP&pid='+pID+'&val='+pVal+'&key='+pName,				
                type: 'post',   
				beforeSend: function(){
      			$.blockUI({ message: '<img src="images/spinner.gif">' }); 
                setTimeout($.unblockUI, 500);
  				 },                          
                success: function (data){ 
                }
             });
			 return false;
     }); 
$('#pChange').live("change",function() {
	var pID = <?php echo $_GET['pID']?>;
	var pVal = encodeURIComponent($(this).val());
	var pName = $(this).attr("name");
			$.ajax({
                url: 'categories.php?rType=ajax',
                data: 'action=saveP&pid='+pID+'&val='+pVal+'&key='+pName,				
                type: 'post',
				beforeSend: function(){
      			$.blockUI({ message: '<img src="images/spinner.gif">' }); 
                setTimeout($.unblockUI, 1000);
  				 },                             
                success: function (data){ 
                }
             });
			 return false;
     });
$('#pGpChange').live("change",function() {
	var pID = <?php echo $_GET['pID']?>;
	var pVal = $(this).val();
	var pName = $(this).attr("name");
			$.ajax({
                url: 'categories.php?rType=ajax',
                data: 'action=savePGp&pid='+pID+'&val='+pVal+'&key='+pName,				
                type: 'post',
				beforeSend: function(){
      			$.blockUI({ message: '<img src="images/spinner.gif">' }); 
                setTimeout($.unblockUI, 1000);
  				 },                             
                success: function (data){ 
                }
             });
			 return false;
     });
	 	 
	 $('#pdChange').live("change",function() {
		var pID = <?php echo $_GET['pID']?>;
		var pVal = encodeURIComponent($(this).val());
		var pName = $(this).attr("name");
			$.ajax({
                url: 'categories.php?rType=ajax',
                data: 'action=savePD&pid='+pID+'&val='+pVal+'&key='+pName,				
                type: 'post',  
				beforeSend: function(){
      			$.blockUI({ message: '<img src="images/spinner.gif">' }); 
                setTimeout($.unblockUI, 1000);
  				 },                           
                success: function (data){ 
                }
             });
			 return false;
     });
	 $('.pdMetaChange').live("change",function() {
		var pID = <?php echo $_GET['pID']?>;
		var pVal = encodeURIComponent($(this).val());
		var pName = $(this).attr("name");
    	$.ajax({
                url: 'categories.php?rType=ajax',
                data: 'action=savePD&pid='+pID+'&val='+pVal+'&key='+pName,				
                type: 'post',
				beforeSend: function(){
      			$.blockUI({ message: '<img src="images/spinner.gif">' }); 
                setTimeout($.unblockUI, 1000);
  				 },                             
                success: function (data){ 
                }
             });
			 return false;
     });
 <?php for ($i = 0, $n = sizeof($languages); $i < $n; $i++) { ?>
	 $('#products_description_'+<?php echo $languages[$i]['id']?>).submit(function() {
		var pID = <?php echo $_GET['pID']?>;
		//var Val = getEditorHTMLContents('products_description_'+<?php echo $languages[$i]['id']?>);
		//var pVal = encodeURIComponent(Val);
// change the textarea edit method to fix 'undifined' issue - 20180321
    var pVal = document.getElementById('txt_products_description_'+<?php echo $languages[$i]['id']?>).value;
		var pName = 'products_description_'+<?php echo $languages[$i]['id']?>;
			$.ajax({
                url: 'categories.php?rType=ajax',
                data: 'action=savePDText&pid='+pID+'&val='+pVal+'&key='+pName,				
                type: 'post', 
				beforeSend: function(){
      			// debug for SQL context
            //document.getElementById('debug_SQL').innerHTML= 'action=savePDText&pid='+pID+'&val='+pVal+'&key='+pName;
            $.blockUI({ message: '<img src="images/spinner.gif">' }); 
                setTimeout($.unblockUI, 1000);
  				 },                            
                success: function (data){ 
                }
             });
			 return false;
     });
	 $('#products_short_description_'+<?php echo $languages[$i]['id']?>).submit(function() {
		var pID = <?php echo $_GET['pID']?>;
		//var Val = getEditorHTMLContents('products_short_description_'+<?php echo $languages[$i]['id']?>);
		//var pVal = encodeURIComponent(Val);
// change the textarea edit method to fix 'undifined' issue - 20180321
    var pVal = document.getElementById('txt_products_short_description_'+<?php echo $languages[$i]['id']?>).value;

		var pName = 'products_short_description_'+<?php echo $languages[$i]['id']?>;		
			$.ajax({
                url: 'categories.php?rType=ajax',
                data: 'action=savePDText&pid='+pID+'&val='+pVal+'&key='+pName,				
                type: 'post',   
				beforeSend: function(){
      			$.blockUI({ message: '<img src="images/spinner.gif">' }); 
                setTimeout($.unblockUI, 1000);
  				 },                          
                success: function (data){ 
                }
             });
			 return false;
     });
	<?php } ?> 
	 $('#pChecked').live("change",function() {
		var pID = <?php echo $_GET['pID']?>;
		var pVal = $("input[type='checkbox']:checked").getCheckboxVal();
		var pName = $(this).attr("name");		
			$.ajax({
                url: 'categories.php?rType=ajax',
                data: 'action=saveP&pid='+pID+'&val='+pVal+'&key='+pName,				
                type: 'post',
				beforeSend: function(){
      			$.blockUI({ message: '<img src="images/spinner.gif">' }); 
                setTimeout($.unblockUI, 1000);
  				 },                             
                success: function (data){ 
				$("#group_tab").html(data);
                }
             });
			 return false;
     });
    $("#products_image").live("change", function() {
	$(".display").hide();	
	$("#view").html('');
    $("#view").html('<img src="images/loading.gif" alt="Uploading...."/>');
	$("#upload").ajaxForm({target: "#view"
                }).submit();
	});
	
<?php if (MORE_PICS > 0){
		 for ($i=0;$i<MORE_PICS;$i++) { ?>
	$('#mo_pics_<?php echo $i?>').live("change", function() {
	$('#mo_view<?php echo $i?>').html('');
    $('#mo_view<?php echo $i?>').html('<img src="images/loading.gif" alt="Uploading...."/>');
	$("#more_upload<?php echo $i?>").ajaxForm({target: '#mo_view<?php echo $i?>'}).submit();
	});
      $('#removeImg<?php echo $i?>').live("click",function (){
		  var $productRow = $(this).parent();
            $.ajax({
                url: 'categories.php?rType=ajax',
                data: $(this).attr('linkData'),
                type: 'post',                
                success: function (data){
                $productRow.remove(); 
				$("#view<?php echo $i?>").html(data);   
                }
            });
          return false;
        });
<?php 
		} 
	}
?>		
		$('#createP').live("change",function (){
			var value = $(this).children("option:selected").attr('value');
	 	  $.ajax({
                url: 'categories.php?rType=ajax',
                data: $(this).attr("linkData")+'&cPath='+value,			
                type: 'post',                             
                success: function (data){ 	
				$("#createB").html(data);			
                }
             });
			 return false;
    });	
	$('#removeP').live("click",function (){		  
            //var cPath = $(this).attr('linkData cPath');
            $.ajax({
                url: 'categories.php?rType=ajax',
                data: $(this).attr('linkData'),
                type: 'post',                
                success: function (data){                
			document.location = '<?php echo twe_href_link(FILENAME_CATEGORIES); ?>';	   
                }
            });
          return false;
        });
	});

function getEditorHTMLContents(EditorName){
	if(typeof(FCKeditorAPI) !=='undefined'){
	var oEditor = FCKeditorAPI.GetInstance(EditorName);
	return(oEditor.GetXHTML(true));
	}
}
jQuery.fn.getCheckboxVal = function(){
    var vals = [];
    var i = 0;
    this.each(function(){
        vals[i++] = jQuery(this).val();
    });
    return vals;
}
  //-->
</script>