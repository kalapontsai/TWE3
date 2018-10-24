<?php
/* --------------------------------------------------------------
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
$('#cChange').live("change",function() {
	var cID = <?php echo $_GET['cID']?>;
	var cVal = $(this).val();
	var cName = $(this).attr("name");
			$.ajax({
                url: 'categories.php?rType=ajax',
                data: 'action=saveC&cid='+cID+'&val='+cVal+'&key='+cName,				
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
	 $('#cdChange').live("change",function() {
		var cID = <?php echo $_GET['cID']?>;
		var cVal = encodeURIComponent($(this).val());
		var cName = $(this).attr("name");
			$.ajax({
                url: 'categories.php?rType=ajax',
                data: 'action=saveCD&cid='+cID+'&val='+cVal+'&key='+cName,	
				contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
				dataType:'html',			
                type: 'post',  
				beforeSend: function(){
      			$.blockUI({ message: '<img src="images/spinner.gif">' }); 
                setTimeout($.unblockUI, 1000);
  				 },                           
                success: function (data){ 
				$("#category_tree").html(data);
                }
             });
			 return false;
     });
	  <?php for ($i = 0, $n = sizeof($languages); $i < $n; $i++) { ?>
	 $('#categories_description_'+<?php echo $languages[$i]['id']?>).submit(function() {
		var cID = <?php echo $_GET['cID']?>;
		var Val = getEditorHTMLContents('categories_description[<?php echo $languages[$i]['id']?>]');
		var cVal = encodeURIComponent(Val);
		var cName = 'categories_description[<?php echo $languages[$i]['id']?>]';		
			$.ajax({
                url: 'categories.php?rType=ajax',
                data: 'action=saveCDText&cid='+cID+'&val='+cVal+'&key='+cName,				
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
	$("#categories_image").live("change", function() {
	$("#view_c_img").html('');
    $("#view_c_img").html('<img src="images/loading.gif" alt="Uploading...."/>');
	$("#upload_c_img").ajaxForm({target: "#view_c_img"
                }).submit();
	});
	$('#removeCImg').live("click",function (){
		  var $productRow = $(this).parent();
            var id = $(this).attr('linkData id');
            $.ajax({
                url: 'categories.php?rType=ajax',
                data: $(this).attr('linkData'),
                type: 'post',                
                success: function (data){
                $productRow.remove(); 
				$("#loadCform").html(data);   
                }
            });
          return false;
        });
		$('#cChecked').live("change",function() {
		var cID = <?php echo $_GET['cID']?>;
		var cVal = $("input[type='checkbox']:checked").getCheckboxVal();
		var cName = $(this).attr("name");		
			$.ajax({
                url: 'categories.php?rType=ajax',
                data: 'action=saveC&cid='+cID+'&val='+cVal+'&key='+cName,				
                type: 'post',
				beforeSend: function(){
      			$.blockUI({ message: '<img src="images/spinner.gif">' }); 
                setTimeout($.unblockUI, 1000);
  				 },                             
                success: function (data){ 
				$("#cgroup_tab").html(data);
                }
             });
			 return false;
     });
	 $('#createC').live("click",function (){
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
		$('#removeC').live("click",function (){		  
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
//--></script>