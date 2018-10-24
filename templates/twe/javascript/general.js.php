<?php
/* -----------------------------------------------------------------------------------------
   $Id: general.js.php,v 1.1 2003/09/24 11:31:00 oldpa Exp $

   TWE-Commerce - community made shopping   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
---------------------------------------------------------------------------------------*/


   // this javascriptfile get includes at every template page in shop, you can add your template specific
   // js scripts here
?>

<link rel="stylesheet" type="text/css" href="<?php echo 'templates/'.CURRENT_TEMPLATE.'/javascript/bootstrap/css/bootstrap.min.css'; ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo 'templates/'.CURRENT_TEMPLATE.'/javascript/bootstrap/css/bootstrap-theme.min.css'; ?>" media="screen" />
<script type="text/javascript" language="javascript" src="<?php echo 'templates/'.CURRENT_TEMPLATE.'/javascript/bootstrap/js/bootstrap.min.js'; ?>"></script>
<script type="text/javascript" language="javascript" src="<?php echo 'templates/'.CURRENT_TEMPLATE.'/javascript/bootstrap/js/bootstrap-hover-dropdown.js'; ?>"></script>

<script type="text/javascript">
$(function ()
{
    var $window = $(window);
	if ($window.width() < 750){
		$(".logo").removeClass("large").addClass("small");
		$("#stickytop-nav").removeClass("top-nav").addClass("mobile-top-nav");
		$(".header-navbar").addClass("navbar-fixed-top");
		$("#index_content").css({'margin-top': '50px'});
		//$(".banner a img").attr("data-src","holder.js/100%x50");
		}
    $window.on('resize', function ()
    {
        if ($window.width() > 750)
        {
		 $(".logo").removeClass("small").addClass("large");	
		 $("#stickytop-nav").addClass("top-nav").removeClass("mobile-top-nav");
		 $(".header-navbar").removeClass("navbar-fixed-top");
		 $("#index_content").css({'margin-top': '0px'});
        }else{
		$(".logo").removeClass("large").addClass("small");
		$("#stickytop-nav").removeClass("top-nav").addClass("mobile-top-nav");
		$(".header-navbar").addClass("navbar-fixed-top");
		$("#index_content").css({'margin-top': '50px'});	
		}
    });
});
    $(function() {
	$(".Alink a img").each(function(){ 
			var text = $(this).attr("alt");
			$(this).parent().append(text);
			$(this).parent().attr("data-role","button");
			$(this).parent().attr("class","btn button-a");
			$(this).remove();
		});		
	$(".btnreplace a img").each(function(){ 
			var text = $(this).attr("alt");
			$(this).parent().append(text);
			$(this).parent().attr("data-role","button");
			$(this).parent().attr("class","btn button-a btn-block");
			$(this).remove();
		});	
	$(".btn_replace input[type=image]").each(function(){ 
		var text = $(this).attr("alt");
		$(this).parent().append('<button data-role="button" class="btn button-a btn-block" type="submit">'+text+'</button>');
		$(this).remove();
	});
		
	$(".btn_info_replace input[type=image]").each(function(){ 
		var text = $(this).attr("alt");			
		$(this).parent().append('<button class="btn button-s btn-block" data-role="button" name="addinfoProducts" id="addCartButton" type="submit">'+text+'</button>');
		$(this).remove();
	});
});	
</script>		
<?php   
if (strstr($PHP_SELF, FILENAME_DEFAULT) && (DEFAULT_TYPE == 'tabbed')) {
?>
<script type="text/javascript">
$(document).ready(function (){
	$(function() {
		$("#tabbed_index").tabs({cookie:true});
		var index = $('#tabbed_index .ui-tabs-selected a').attr('href');
	   if(index == "#center_news"){
		 $("#center_news").load("center_modules.php",{action:"getCenterNews"});	
		}
		if(index == "#new_products"){
          $("#new_products").load("center_modules.php",{action:"getNewProducts"});
		}
		if(index == "#products_featured"){
            $("#products_featured").load("center_modules.php",{action:"getProductsFeatured"});
		}
		if(index == "#products_best"){
		 $("#products_best").load("center_modules.php",{action:"getProductsBest"});	
		}
		if(index == "#specials_center"){
           $("#specials_center").load("center_modules.php",{action:"getProductsSpecials"});
		}
		if(index == "#shop_content"){
           $("#shop_content").load("center_modules.php",{action:"getShopContent"});
		}
		if(index == "#upcoming_products"){
		$("#upcoming_products").load("center_modules.php",{action:"getUpcomingProducts"});	
		}
	    $('.new_products').click(function (){
            $("#new_products").load("center_modules.php",{action:"getNewProducts"});
        });
	    $('.products_featured').click(function (){
            $("#products_featured").load("center_modules.php",{action:"getProductsFeatured"});
        });
		$('.products_best').click(function (){
           $("#products_best").load("center_modules.php",{action:"getProductsBest"});
        });
		$('.specials_center').click(function (){
           $("#specials_center").load("center_modules.php",{action:"getProductsSpecials"});
        });
		
		$('.center_news').click(function (){
           $("#center_news").load("center_modules.php",{action:"getCenterNews"});
        });
		
		$('.shop_content').click(function (){
           $("#shop_content").load("center_modules.php",{action:"getShopContent"});
        });
		$('.upcoming_products').click(function (){
           $("#upcoming_products").load("center_modules.php",{action:"getUpcomingProducts"});
        });
	 });
	});
</script>
<?php
}
if (strstr($PHP_SELF, FILENAME_PRODUCT_INFO)){
?>
<link rel="stylesheet" type="text/css" href="ext/jquery/css/colorbox.css" media="screen" />
<script type="text/javascript" language="javascript" src="ext/jquery/jquery.colorbox-min.js"></script>
<script type="text/javascript">
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.0
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && document.getElementById) x=document.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}

$(document).ready(function (){
	$(function() {
		$("#tabbed_info").tabs({cookie:true});
	 });
	 $(".thickbox").colorbox({rel:'thickbox'});			
});
</script>
<?php
}
?>