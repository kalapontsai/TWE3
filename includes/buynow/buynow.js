var buynow = {
    charset: 'UTF-8',
	pageLinks: {},
	errors:true,
	buynowClicked:false,
	showAjaxLoader: function (){
        $('#ajaxLoader').show();
    },
    hideAjaxLoader: function (){
        $('#ajaxLoader').hide();
    },
    showAjaxMessage: function (message){
        $('#cartButtonContainer').hide();
        $('#ajaxMessages').show().html(message);
    },
    hideAjaxMessage: function (){
        $('#cartButtonContainer').show();
        $('#ajaxMessages').hide();
    },
    showMessage: function (){
        $('#empty_cart').hide();
    },
  
	queueAjaxRequest: function (options){
		var buynowClass = this;
		var o = {
			contentType:'application/x-www-form-urlencoded; charset=UTF-8',
			url: options.url,
			cache: options.cache || false,
			dataType: options.dataType || 'html',
			type: options.type || 'GET',
			contentType: options.contentType || 'application/x-www-form-urlencoded; charset=' + this.ajaxCharset,
			data: options.data || false,
			beforeSend: options.beforeSend || function (){
				buynowClass.showAjaxMessage('<div align="center"><img src="images/ajax-loader.gif"><div>');
				buynowClass.showAjaxLoader();
			},
			complete: function (){
					buynowClass.hideAjaxMessage();
					if (document.ajaxq.q['doAjax'].length <= 0){
						buynowClass.hideAjaxLoader();
					}
			},
			success: options.success,
			error: function (XMLHttpRequest, textStatus, errorThrown){
				if (XMLHttpRequest.responseText == 'session_expired') document.location = this.pageLinks.buynow;
				alert(options.errorMsg || 'There was an ajax error, please contact ' + buynowClass.storeName + ' for support.');
			}
		};
		$.ajaxq('doAjax', o);
	},
	
	//--------------------------------------------------------BOXCART
	//更新區塊樣式
    updateBoxProductListing: function (){
        var buynowClass = this;
        this.queueAjaxRequest({
            url: this.pageLinks.buynow,
            data: 'action=getProductsFinal',
            type: 'post',
            beforeSendMsg: 'Refreshing Final Product Listing',
            success: function (data){
                $('#finalProducts').html(data);
				$('#tinycart').html(data);
            },
            errorMsg: 'There was an error refreshing the final products listing, please inform IT Web Experts about this error.'
        });
    },
   updateCartTotals: function (){
        var buynowClass = this;
        this.queueAjaxRequest({
            url: this.pageLinks.buynow,
            cache: false,
            data: 'action=getBoxCartTotals',
            type: 'post',
            beforeSendMsg: 'Updating Cart Totals',
            success: function (data){
                $('.BoxcartTotals').html(data);
            },
            errorMsg: 'There was an error refreshing the shopping cart, please inform IT Web Experts about this error.'
        });
    },
	
	//--------------------------------------------------------SHOPPINGCART
	 updateShoppingCartView: function (){
        var buynowClass = this;
        this.queueAjaxRequest({
            url: this.pageLinks.cart,
            data: 'action=updateCartView',
            type: 'post',
            beforeSendMsg: 'Refreshing Shopping Cart',
            success: function (data){
            $('#shoppingCart').html(data);             
            },
            errorMsg: 'There was an error updateShoppingCartView, please inform IT Web Experts about this error.'
        });
    },
    	updateShoppingCartTotals: function (){
        var buynowClass = this;
        this.queueAjaxRequest({
            url: this.pageLinks.cart,
            cache: false,
            data: 'action=getCartTotals',
            type: 'post',
            beforeSendMsg: 'Updating Cart Totals',
            success: function (data){
                $('.cartTotals').html(data);
            },
            errorMsg: 'There was an error updateShoppingCartTotals, please inform IT Web Experts about this error.'
        });
    },
    initBuynow: function (){
        var buynowClass = this;
		
		$('#removeBoxFromCart').live("click",function (){
            var $productRow = $(this).parent().parent();
            buynowClass.queueAjaxRequest({
                url: buynowClass.pageLinks.buynow,
                data: $(this).attr('linkData'),
                type: 'post',
                beforeSendMsg: 'Removing Product From Cart',
                dataType: 'json',
                success: function (data){
					$('.success, .warning').remove();
                    if (data.products == 0){
						buynowClass.updateBoxProductListing();
						$('#cart_total').html('('+data.products+')');
                    }else{
                        $productRow.remove();
                        buynowClass.updateBoxProductListing();
						$('#cart_total').html('('+data.products+')');
                    }
                },
                errorMsg: 'There was an error Removing Product From Cart, please inform IT Web Experts about this error.'
            });
          return false;
        });   	
	        $('#addToCart').live("click",function (){
                buynowClass.queueAjaxRequest({
                url: buynowClass.pageLinks.buynow,
                data: $(this).attr('linkData'),
                type: 'post',
                beforeSendMsg: 'Add Product Cart',
                dataType: 'json',
                success: function (data){ 
				$('.success, .warning').remove(); 
		        var productIDVal 	= data.products;
				var productName 	= data.name;				
				var productTotal 	= data.total;
				$('#notification').animate({opacity:1},800).animate({opacity:0},800);
				$('#notification').html('<div class="success" style="display: none;">' + productName + '</div>');
				$('.success').fadeIn('slow');
                buynowClass.updateBoxProductListing();
				//buynowClass.updateCartTotals();
				$('#cart_total').html('('+productTotal+')');
				 },
                errorMsg: 'There was an error Add Product to box Cart, please inform IT Web Experts about this error.'
            });
          return false;
        });    
 		$('#addCartButton').live("click",function (){   
	          buynowClass.queueAjaxRequest({
                url: buynowClass.pageLinks.buynow,
                data: 'action=addinfoProducts&' + $('input', $('#productsInfo')).serialize(),
                type: 'post',
                beforeSendMsg: 'Add Product Quantities',
                dataType: 'json',
                success: function (data){	
				$('.success, .warning').remove();
				var productName 	= data.name;
				var productTotal	= data.total;	
				//$('html, body').animate({ scrollTop: 0 }, 'slow');
				
				$('#notification').animate({opacity:1},800).animate({opacity:0},800);
				$('#notification').html('<div class="success" style="display: none;">' + productName + '</div>');
				$('.success').fadeIn('slow');					
                    buynowClass.updateBoxProductListing();
					$('#cart_total').html('('+productTotal+')');
                },
                errorMsg: 'There was an error add shopping cart, please inform IT Web Experts about this error.'
            });
	return false;
        });      
	//--------------------------------------------------------BOXCART	
	//--------------------------------------------------------SHOPPINGCART
	
 	$('#removeFromCart').live("click",function (){
            var $productRow = $(this).parent().parent();
            buynowClass.queueAjaxRequest({
                url: buynowClass.pageLinks.cart,
                data: $(this).attr('linkData'),
                type: 'post',
                beforeSendMsg: 'Removing Product From Cart',
                dataType: 'json',
                success: function (data){
                    if (data.products == 0){
                        document.location = buynowClass.pageLinks.shoppingCart;
                    }else{
                        $productRow.remove();
                        buynowClass.updateShoppingCartTotals();
                    }
                },
                errorMsg: 'There was an error updating shopping cart, please inform IT Web Experts about this error.'
            });
          return false;
        });  
  
        $('#updateCartButton').click(function (){
            buynowClass.showAjaxLoader();
            buynowClass.queueAjaxRequest({
                url: buynowClass.pageLinks.cart,
                data: 'action=updateQuantities&' + $('input', $('#shoppingCart')).serialize(),
                type: 'post',
                beforeSendMsg: 'Updating Product Quantities',
                dataType: 'json',
                success: function (){
                    buynowClass.updateShoppingCartView();
                    buynowClass.updateShoppingCartTotals();
                },
                errorMsg: 'There was an error updating shopping cart, please inform IT Web Experts about this error.'
            });
          return false;
        });
	//--------------------------------------------------------SHOPPINGCART	
		
	$('#cart > .heading a').live('click', function() {
		$('#cart').addClass('active');	
		$('#cart').live('mouseleave', function() {
			$(this).removeClass('active');
		});
	}); 	 	         
        this.initializing = false;
    }
}