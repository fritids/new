<?php
//require_once dirname(__FILE__).'/classes/class.ecpCoupons.php';
//require_once dirname(__FILE__).'/classes/class.DownloadManager.php';

require_once IDG_CLASS_PATH.'/ShoppingCart/CouponActions/class.ecpCoupons.php';
require_once IDG_CLASS_PATH.'/DownloadManager/class.DownloadManager.php';

/// DOWNLOAD MANAGER FUNCTIONS
/////////////////////////////////////////

function ecp_dm_file_uploader(){?>
	<script type="text/javascript">
	jQuery(document).ready(function($){
	  $('p.submit').hide();
	});
	</script>
	
    <link href="<?php echo get_bloginfo('template_url'); ?>/lib/plugins/callbacks/uploadify/uploadify.css" type="text/css" rel="stylesheet" />
    <script type="text/javascript" src="<?php echo get_bloginfo('template_url'); ?>/lib/plugins/callbacks/uploadify/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="<?php echo get_bloginfo('template_url'); ?>/lib/plugins/callbacks/uploadify/swfobject.js"></script>
    <script type="text/javascript" src="<?php echo get_bloginfo('template_url'); ?>/lib/plugins/callbacks/uploadify/jquery.uploadify.v2.1.4.min.js"></script>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
      $('#file_upload').uploadify({
        'uploader'  : '<?php echo get_bloginfo('template_url'); ?>/lib/plugins/callbacks/uploadify/uploadify.swf',
        'script'    : '<?php echo get_bloginfo('template_url'); ?>/lib/plugins/callbacks/uploadify/uploadify.php',
        'cancelImg' : '<?php echo get_bloginfo('template_url'); ?>/lib/plugins/callbacks/uploadify/cancel.png',
        'folder'    : '<?php echo dirname(__FILE__);?>/../downloadmanagement/files',
        'buttonText': 'Browse File',
        'multi'		: true,
        'simUploadLimit' : 5,
        'auto'      : false,
        'onAllComplete' : function(event,data) {
              alert(data.filesUploaded + ' files uploaded successfully!');
        },
        'onComplete'  : function(event, ID, fileObj, response, data) {
            var obj = jQuery.parseJSON(response);
            var file_id = obj.file_id;
            var file_name = obj.file_name;

			$('.file_uploads_table').append('<tr><td>'+file_id+'</td><td>'+file_name+'</td><td><input type="hidden" value="'+file_id+'" class="remove_upload_id" /><input type="button" value="Remove" name="remove_upload" class="remove_upload_button" /></td></tr>');
             
           
        }
      });

      $('.remove_upload_button').click(function(){

    	  var dele_butt = $(this);
    	  
  		var file_upload_id = $(this).prev().val();


  		var msg = confirm("Really want to delete this file");
  		if(msg==true){
  		$.post("<?php echo get_bloginfo('template_url'); ?>/lib/plugins/callbacks/ajax/remove_file_upload.php", { file_id: file_upload_id }, function(data){
			if(data == 1){
				dele_butt.parents('tr:first').remove();
				var messagebox = $('.file_uploads_table').prev();
				messagebox.html('File Deleted').fadeOut(5000);
			}
			});
  		}
  		
      });
      

      });
    </script>

	<h2>Listing of all file uploads:</h2>
	<div></div>
	<table class="file_uploads_table">
	<tr>
	<td style="width:50px;">
	ID
	</td>
	<td>
	File Name
	</td>
	<td>
	</td>
	</tr>
<?php 
	$alluploads = new DownloadManager();
	$results = $alluploads->listAllUploads();
	
	foreach($results as $res){?>
		<tr><td><?php echo $res['file_upload_id']; ?></td><td><?php echo $res['file_upload_name']; ?></td><td><input type="hidden" value="<?php echo $res['file_upload_id']; ?>" class="remove_upload_id" /><input type="button" value="Remove" name="remove_upload" class="remove_upload_button" /></td></tr>
	<?php } ?>
	</table>

	<h2>Select file to upload:</h2>
    <input id="file_upload" name="file_upload" type="file"/><br/>
    <a href="javascript:jQuery('#file_upload').uploadifyUpload();">Upload Files</a>
		
<?php	
}

/// COUPON FUNCTIONS
/////////////////////////////////////////

function ecp_coupons_callback_manage() {
?>

    <script type="text/javascript">

    jQuery(document).ready(function($){
    	$( "#edit_coupon_dialog" ).dialog({autoOpen: false,modal: true, width: 600});

		// BRISENJE NA KUPONI
    	$('.delete_coupon_button').click(function(){

    		var thisrow = $(this).parent();
    		var coupon_id = thisrow.find('input[name="delete_coupon_id"]').val();

    		$.post("<?php echo get_bloginfo('template_url'); ?>/lib/plugins/callbacks/ajax/delete_coupon.php", { coupon_id: coupon_id }, function(data){
				if(data == 1){
					thisrow.find('.delete_coupon_message').text("Coupon is removed");
					thisrow.remove();
					}
				else{
					thisrow.find('.delete_coupon_message').text("Coupon cannot be removed");
					}
				});
        	});
			//ENABLE DISABLE NA KUPONI
    	$('.toggle_coupon_enable').click(function(e){
        	e.preventDefault();

			var thisrow = $(this).parent();
			var coupon_id = thisrow.find('input[name="delete_coupon_id"]').val();
			var toggle = thisrow.find('input[name="toggle_coupon"]').val();
			
			if(toggle=='disabled'){
				toggle = 'disable';
			}
			else{
				toggle = 'enable';
				}
			
			$.post("<?php echo get_bloginfo('template_url'); ?>/lib/plugins/callbacks/ajax/disable_coupon.php", { coupon_id: coupon_id, toggle: toggle }, function(data){
				if(data.match('disabled')){
					thisrow.find('.toggle_coupon_enable').val("Enable");
					thisrow.find('input[name="toggle_coupon"]').val('enabled');
					}
				else{
					thisrow.find('.toggle_coupon_enable').val("Disable");
					thisrow.find('input[name="toggle_coupon"]').val('disabled');
					}
				});
			});

			//EDITIRANJE NA KUPONI
			$('.edit_coupon_button').click(function(e){
				e.preventDefault();
				var thisrow = $(this).parent();
				var coupon_id = thisrow.find('input[name="delete_coupon_id"]').val();

				$.post("<?php echo get_bloginfo('template_url'); ?>/lib/plugins/callbacks/ajax/list_products_by_coupon.php", { coupon_id: coupon_id }, function(data){
							if(data){
								$('#edit_coupon_dialog').html(data);

								 $('.add_coupon_dialog .edit_validity_time_from').datepicker(
						                 {   disabled:false,
						                     changeMonth: true,
						                     changeYear: true,
						                     closeText: 'X',
						                     dateFormat: 'yy-mm-dd'
						                 });

						                 $('.add_coupon_dialog .edit_validity_time_till').datepicker(
						                 {   disabled:false,
						                     changeMonth: true,
						                     changeYear: true,
						                     closeText: 'X',
						                     dateFormat: 'yy-mm-dd'    
						                 });
								 $('#ui-datepicker-div').css("z-index","1005");
			//UPDATE COUPON - gotovo
								 couponUpdateButton();
								 
			//ADD PRODUCT TO COUPON - gotovo
								couponUpdateAddProduct();
								 
			//DELETE PRODUCT FROM COUPON - gotovo
								 couponUpdateDeleteButton();
								}
							else{
								$('#edit_coupon_dialog').html("<p>Cannot make ajax call error!</p>");
							}
				});

				$('#edit_coupon_dialog').dialog('open');
				
			});
	

		
        });


	function couponUpdateAddProduct(){
		jQuery('.update_add_products_button').click(function(){

			 var thisrow = jQuery(this).parent();
			 var wrap_add_product = thisrow.find('.update_add_products_wrap');
			 wrap_add_product.slideDown();
			 wrap_add_product.find('.update_add_product_button').click(function(){
			 var products = new Array();
			 var thisrow = jQuery(this).parent();
			 var coupon_id = thisrow.parent().parent().parent().find('.edit_coupon_id').val();


			 		var checked_products = jQuery(this).parent().find('input:checked');
					checked_products.each(function(i){
						products[i] = jQuery(this).val();
						});

			jQuery.post("<?php echo get_bloginfo('template_url'); ?>/lib/plugins/callbacks/ajax/add_product.php", { coupon_id: coupon_id,products: products }, function(data){
				if(data == 1)
				{
					wrap_add_product.slideUp();
					checked_products.each(function(i){
						var coup_id = jQuery(this).val();
						var prod_name = jQuery(this).parent().text();
						
						couponListProducts().append('<li><input type="hidden" class="edit_coupon_product_id" value="'+coup_id+'">'+prod_name+'<input type="button" value="Delete" class="update_products_delete_button" name="update_products_delete_button"></li>');
							jQuery(this).parent('span:first').remove();
							couponUpdateDialogMessage().text("Product is added!");
							 couponUpdateDeleteButton();
						});
				} 
			});
				});
			
			 
			 });
	}

    
	function couponUpdateDeleteButton(){

		jQuery('.update_products_delete_button').click(function(){

			 var thisrow = jQuery(this).parents('li:first');
			 var coupon_id = thisrow.parent().parent().parent().find('.edit_coupon_id').val();
			 var product_id = thisrow.find('.edit_coupon_product_id').val();
			 
			 jQuery.post("<?php echo get_bloginfo('template_url'); ?>/lib/plugins/callbacks/ajax/delete_product.php", { coupon_id: coupon_id,product_id: product_id }, function(data){
					if(data != 0){
						couponUpdateDialogMessage().text("Product is removed");
						thisrow.remove();
						}
					else{
						couponUpdateDialogMessage().text("Product cannot be removed");
						}
					});
			 
			 });
		
	}

	function couponUpdateButton(){
	//UPDATE COUPON - gotovo
	 jQuery('#update_coupon_button').click(function(){

		 var thisrow = jQuery(this).parent();
		 var thiseditform = thisrow.parent().parent().parent();
		 var coupon_id = thiseditform.find('.edit_coupon_id').val();
		 var coupon_code = thiseditform.find('#edit_coupon_code').val();
        var coupon_price = thiseditform.find('.edit_coupon_price').val();
        var coupon_times = thiseditform.find('.edit_coupon_times').val();
        var validity_time_from = thiseditform.find('.edit_validity_time_from').val();
        var validity_time_till = thiseditform.find('.edit_validity_time_till').val();
		 
		 jQuery.post("<?php echo get_bloginfo('template_url'); ?>/lib/plugins/callbacks/ajax/update_coupon.php", { coupon_id: coupon_id, coupon_code: coupon_code, coupon_price: coupon_price,coupon_times: coupon_times,validity_time_from: validity_time_from,validity_time_till: validity_time_till }, function(data){
				if(data == 1){
					thiseditform.find('.update_coupon_message').text("Coupon is updated refresh page to see result");
					
					}
				else{
					thiseditform.find('.update_coupon_message').text("Coupon cannot be updated");
					}
				});
		 
		 });
	}

	function couponUpdateDialog(){
		var thisrow = jQuery('.add_coupon_dialog');
		return thisrow;
		}

	function couponListProducts(){
		return couponUpdateDialog().find('ul');
	}
	
	function couponUpdateDialogMessage(){
		var thisrow = couponUpdateDialog()
		var message = thisrow.find('.update_coupon_message');
		
		return message;
	}
	function availableCouponsWrapper(){
		return jQuery('#AvailableCoupons_1');
	}
	
	

    
    </script>
	<style type="text/css">
    	div.couponWrapper ul li{
    		float:left;
    		margin: 0 10px 30px 0;
			border-right: 1px solid #a2a2a2;
			padding-right: 10px;
		}
    </style>

<?php
    $a = ecpCoupons::getInstance();
    $result = $a->listAllCoupons();
    
?>

    <h2>List of All Coupons</h2>
    <div class="couponWrapper">
    <ul>
    <?php
    foreach ($result as $res):
        $button_disable;
        if ($res['coupon_enabled']) {
            $button_disable[0] = 'disabled';
            $button_disable[1] = 'Disable';
        } else {
            $button_disable[0] = '';
            $button_disable[1] = 'Enable';
        }
    ?>

        <li style="float:left;margin-right:10px">
            <p>ID : <?php echo $res['coupon_id']; ?></p>
            <p>Code : <?php echo $res['coupon_code']; ?></p>
            <p>Price : <?php echo $res['coupon_price']; ?>$</p>
            <p>Usage Left : <?php echo $res['coupon_usage']; ?></p>
            <p>Start Date : <?php echo $res['coupon_startdate']; ?></p>
            <p>Ending Date : <?php echo $res['coupon_enddate']; ?></p>
            <input type="button" value="Delete" name="delete_coupon_button" class="delete_coupon_button" />
            <input type="button" value="<?php echo $button_disable[1]; ?>" name="toggle_coupon_enable" <?php //echo $button_disable[0]; ?> class="toggle_coupon_enable" />
            <input type="button" value="Edit" name="edit_coupon_button" class="edit_coupon_button" />
            <input type="hidden" value="<?php echo $res['coupon_id']; ?>" name="delete_coupon_id" />
            <input type="hidden" value="<?php echo $button_disable[0]; ?>" name="toggle_coupon" />
            <span class="delete_coupon_message"></span>
        </li>


<?php endforeach; ?>
        </ul>
         </div>
         <div id="edit_coupon_dialog" title="Edit Coupon">
			
		</div>
<?php
    }

    function ecp_coupons_callback_products() {
?>


        <script type="text/javascript">

		jQuery(document).ready(function($){
			
			$('.delete_coupon_from_product').click(function(e){
					e.preventDefault();
					var thisrow = $(this).parent();
					var coupon_id = thisrow.find('input[name="coupon_id_hidden"]').val();
					var product_id = thisrow.find('input[name="product_id_hidden"]').val();
					
						console.log(thisrow);
					$.post("<?php echo get_bloginfo('template_url'); ?>/lib/plugins/callbacks/ajax/delete_coup_from_prod.php", { product_id: product_id, coupon_id: coupon_id }, function(data){
							if(data == 1){
								thisrow.find('.delete_coup_from_prod_message').text("Coupon is removed from product");
								thisrow.remove();
								}
							else{
								thisrow.find('.delete_coup_from_prod_message').text("Coupon cannot be removed from product");
								}
						
						
						});
				});



			});

        
        </script>

<?php
        $a = ecpCoupons::getInstance();
        $result = $a->listAllProductsWithCoupons();
        //print_r($result);
?>
        <style>
            .ui-tabs {
                overflow: hidden !important;
            }
            
            div.couponWrapper ul li{
				float:left;
				margin: 0 10px 30px 0;
				border-right: 1px solid #a2a2a2;
				padding-right: 10px;
			}
        </style>
        <h2>List of All Products with Coupons</h2>
		<div class="couponWrapper">
        <ul>
    <?php foreach ($result as $res): ?>
    <?php
            $enabled;
            if ($res['coupon_enabled']) {
                $enabled = 'Enabled';
            } else {
                $enabled = 'Disabled';
            }
    ?>
            <li style="float:left"><p>Product ID : <?php echo $res['ID']; ?></p>
                <p>Product Name : <?php echo $res['post_title']; ?></p>
                <p>Coupon Code : <?php echo $res['coupon_code']; ?></p>
                <p>Coupon is <?php echo $enabled; ?> </p>
                <input type="button" value="Remove Coupon From Product" name="delete_coupon_from_product" class="delete_coupon_from_product" />
                <input type="hidden" value="<?php echo $res['product_id']; ?>" name="product_id_hidden" />
                <input type="hidden" value="<?php echo $res['coupon_id']; ?>" name="coupon_id_hidden" />
                <span class="delete_coup_from_prod_message"></span>
            </li>

<?php endforeach; ?>
            </ul>
            </div>
<?php
        }

function ecp_coupons_callback_add() {
?>
                    <script type="text/javascript">
                        jQuery(document).ready(function($)
                        {
                        	
                            $('p.submit').hide();

                            $('.add_coupon .validity_time_from').datepicker(
                            {   disabled:false,
                                changeMonth: true,
                                changeYear: true,
                                closeText: 'X',
                                dateFormat: 'yy-mm-dd'
                            });

                            $('.add_coupon .validity_time_till').datepicker(
                            {   disabled:false,
                                changeMonth: true,
                                changeYear: true,
                                closeText: 'X',
                                dateFormat: 'yy-mm-dd'    
                            });

                                $('#add_coupon_button').click(function(e){
                                e.preventDefault();
                                var timestamp = Number(new Date());
                                var couponcode = $('#coupon_code').val();
                                var products = new Array();
                                var coupon_price = $('.coupon_price').val();
                                var coupon_times = $('.coupon_times').val();
                                var validity_time_from = $('.validity_time_from').val();
                                var validity_time_till = $('.validity_time_till').val();

                                $('.add_coupon ul li input:checked').each(function(i){
                                        products[i] = $(this).val();
                                        });

                                
								$.post("<?php echo get_bloginfo('template_url'); ?>/lib/plugins/callbacks/ajax/add_coupon.php", { timestamp: timestamp, couponcode: couponcode, products: products,coupon_price: coupon_price,coupon_times: coupon_times,validity_time_from: validity_time_from,validity_time_till: validity_time_till },
                                    function(data){
                                    
                                    data = jQuery.parseJSON(data);
                                    console.log(data);
                                    if(data.success == 1){
                                        
											$('.add_coupon input').not('#add_coupon_button').val("").attr('checked', false);
											$('.add_coupon_message').text("Coupon addded sucessfully");
											availableCouponsWrapper().find('ul').append(
													'<li style="float: left; margin-right: 10px;"><p>ID : '+data.coupon_id+'</p>'+
										            '<p>Code : '+couponcode+'</p>'+
										            '<p>Price : '+coupon_price+'$</p>'+
										            '<p>Usage Left : '+coupon_times+'</p>'+
										            '<p>Start Date : '+validity_time_from+'</p>'+
										            '<p>Ending Date : '+validity_time_till+'</p>'+
										            '<input type="button" class="delete_coupon_button" name="delete_coupon_button" value="Delete">'+
										            '<input type="button" class="toggle_coupon_enable" name="toggle_coupon_enable" value="Disable">'+
										            '<input type="button" class="edit_coupon_button" name="edit_coupon_button" value="Edit">'+
										            '<input type="hidden" name="delete_coupon_id" value="'+data.coupon_id+'">'+
										            '<input type="hidden" name="toggle_coupon" value="disabled">'+
										            '<span class="delete_coupon_message"></span>'+
										        '</li>');
                                        }
                                    else{
											$('.add_coupon_message').text("Something is wrong, maybe there is already same CODE or missing values");
                                        }

                                });


                                });
							
                            
                        });
                    </script>
			<?php $a = ecpCoupons::getInstance(); $products = $a->listAllProducts(); ?>
                    <div class="add_coupon">
                        <h2>Add new Coupon</h2>
                        <label for="coupon_code">New Coupon Code:
                            <input type="text" name="coupon_code" id="coupon_code" maxlength="10" size="10" value="">
                        </label>
                        <h3>Select Product to apply this coupon:</h3>
                        <ul>
                        <?php foreach($products as $product):?>
                        <li><input type="checkbox" name="all_products_checkbox" class="all_products_checkbox" value="<?php echo $product['ID']; ?>" /><?php echo $product['post_title']; ?></li>
                        <?php endforeach; ?>
                        </ul>
                        <label for="coupon_price"><div>Value: -<input type="text" name="coupon_price" class="coupon_price" size="3" value="" maxlength="3"/>$</div></label>
                        <label for="coupon_times"><div>How many times it can be used in total: <input type="text" name="coupon_times" class="coupon_times" size="3" value="" maxlength="3" />(only numbers - max 3 characters)</div></label>
                        <label for="validity_time_from">Validity From:<input type="text" name="validity_time_from" class="validity_time_from" readonly value="Click here" maxlength="10" size="10" /><div class="validity_time_from2"></div></label>
                        <label for="validity_time_till">Validity Till:<input type="text" name="validity_time_till" class="validity_time_till" readonly value="Click here" maxlength="10" size="10" /></label>
                        <br>
                        <input type="button" value="Add Coupon" id="add_coupon_button" name="add_coupon_button" /><span class="add_coupon_message"></span>
                    </div>
                   
<?php
                }
?>
