let base_url = $('base').attr('data-base');
let role = $('base[data-role]').attr('data-role');

let csrf_value_ = $("#csrf_value_").val();
$('.select2').select2();
$(document).ready(function(){
    $(document).on('click','#help_button',function(){
		$('#help_modal').fadeIn('500');
	});
    $(document).on('click','#cross_button_to_close',function(){
		$('#help_modal').fadeOut('500');
	});
	$(document).on('click','#select_all_items',function(){
		if($('#order_details_holder .single_order[data-selected=selected]').attr('data-order-type')=='Dine In'){
			$('#items_holder_of_order .single_item_in_order').css('background-color','#B5D6F6');
			$('#items_holder_of_order .single_item_in_order').attr('data-selected','selected');
		}else{
			swal({
				title: 'Alert',
				text: "You don't need to select or deselect any item for take away or delivery, because you need to deliver all items in a pack",
                confirmButtonColor: '#b6d6f6' 
			});
		}
		
	});
	$(document).on('click','#deselect_all_items',function(){
		if($('#order_details_holder .single_order[data-selected=selected]').attr('data-order-type')=='Dine In'){
			$('#items_holder_of_order .single_item_in_order').css('background-color','#ffffff');
			$('#items_holder_of_order .single_item_in_order').attr('data-selected','deselected');
		}else{
			swal({
				title: 'Alert',
				text: "You don't need to select or deselect any item for take away or delivery, because you need to deliver all items in a pack",
                confirmButtonColor: '#b6d6f6' 
			});
			
		}
	});
	$(document).on('click','#items_holder_of_order .single_item_in_order',function(){
		if($('#items_holder_of_order .single_item_in_order[data-order-type="Dine In"]').length>0){
			// $('.single_item_in_order').css('background-color','#ffffff');
			// $('.single_item_in_order').attr('data-selected','unselected');
			if($(this).attr('data-selected')=="selected"){
				$(this).css('background-color','#ffffff');
				$(this).attr('data-selected','unselected');
			}else{
				$(this).css('background-color','#B5D6F6');
				$(this).attr('data-selected','selected');
			}
			
		}
	});
	$(document).on('click','#start_cooking',function(){
		if($('#order_details_holder .single_order[data-selected=selected]').attr('data-order-type')=='Dine In'){
			if($('#items_holder_of_order .single_item_in_order[data-selected=selected]').length>0){
				// let previous_id = $('#items_holder_of_order .single_item_in_order[data-selected=selected]').attr('id').substr(12);
				let previous_id = '';
				let j = 1;
				let total_items = $('#items_holder_of_order .single_item_in_order[data-selected=selected]').length;
				$('#items_holder_of_order .single_item_in_order[data-selected=selected]').each(function(i, obj) {
					if(j==total_items){
						previous_id += $(this).attr('id').substr(12);	
					}else{
						previous_id += $(this).attr('id').substr(12)+',';
					}
					j++;
				});
				let previous_id_array = previous_id.split(",");
				previous_id_array.forEach(function(entry) {
				    $('#single_item_'+entry).attr('data-selected','selected');
				    $('#single_item_'+entry).css('background-color','#B5D6F6');
				});
				if(previous_id!=''){
					$.ajax({
						url:base_url+"Waiter/update_cooking_status_ajax",
						method:"POST",
						data:{
							previous_id : previous_id,
							cooking_status : 'Started Cooking',
							csrf_irestoraplus: csrf_value_
						},
						success:function(response) {
							swal({
								title: 'Alert',
								text: "Cooking Started!!",
				                confirmButtonColor: '#b6d6f6' 
							});	

						},
						error:function(){
							alert("error");
						}
					});
				}
			}else{
				swal({
					title: "Alert!",
					text: "Please select an item to start cooking!",
					confirmButtonColor: '#b6d6f6' 
				})	
			}
		}else{
			let previous_id = '';
			let j = 1;
			let total_items = $('#items_holder_of_order .single_item_in_order').length;
			$('#items_holder_of_order .single_item_in_order').each(function(i, obj) {
				if(j==total_items){
					previous_id += $(this).attr('id').substr(12);	
				}else{
					previous_id += $(this).attr('id').substr(12)+',';
				}
				j++;
			});
			let previous_id_array = previous_id.split(",");
			previous_id_array.forEach(function(entry) {
			    $('#single_item_'+entry).attr('data-selected','selected');
			    $('#single_item_'+entry).css('background-color','#B5D6F6');
			});
			if(previous_id!=''){
				$.ajax({
					url:base_url+"Waiter/update_cooking_status_delivery_take_away_ajax",
					method:"POST",
					data:{
						previous_id : previous_id,
						cooking_status : 'Started Cooking',
						csrf_irestoraplus: csrf_value_
					},
					success:function(response) {
						swal({
							title: 'Alert',
							text: "Cooking Started!!",
			                confirmButtonColor: '#b6d6f6' 
						});
								
					},
					error:function(){
						alert("error");
					}
				});
			}
		}
	});
	$(document).on('click','#cooking_done',function(){
		if($('#order_details_holder .single_order[data-selected=selected]').attr('data-order-type')=='Dine In'){
			if($('#items_holder_of_order .single_item_in_order[data-selected=selected]').length>0){
				// let previous_id = $('#items_holder_of_order .single_item_in_order[data-selected=selected]').attr('id').substr(12);
				let previous_id = '';
				let j = 1;
				let total_items = $('#items_holder_of_order .single_item_in_order[data-selected=selected]').length;
				$('#items_holder_of_order .single_item_in_order[data-selected=selected]').each(function(i, obj) {
					if(j==total_items){
						previous_id += $(this).attr('id').substr(12);	
					}else{
						previous_id += $(this).attr('id').substr(12)+',';
					}
					j++;
				});
				let previous_id_array = previous_id.split(",");
				previous_id_array.forEach(function(entry) {
				    $('#single_item_'+entry).attr('data-selected','selected');
				    $('#single_item_'+entry).css('background-color','#B5D6F6');
				});
				if(previous_id!=''){
					$.ajax({
						url:base_url+"Waiter/update_cooking_status_ajax",
						method:"POST",
						data:{
							previous_id : previous_id,
							cooking_status : 'Done',
							csrf_irestoraplus: csrf_value_
						},
						success:function(response) {
							swal({
								title: 'Alert',
								text: "Cooking Done!!",
				                confirmButtonColor: '#b6d6f6' 
							});	
								
						},
						error:function(){
							alert("error");
						}
					});
				}
			}else{
				swal({
					title: "Alert!",
					text: "Please select an item to cooking item done!",
					confirmButtonColor: '#b6d6f6' 
				})	
			}
		}else{
			let previous_id = '';
			let j = 1;
			let total_items = $('#items_holder_of_order .single_item_in_order').length;
			$('#items_holder_of_order .single_item_in_order').each(function(i, obj) {
				if(j==total_items){
					previous_id += $(this).attr('id').substr(12);	
				}else{
					previous_id += $(this).attr('id').substr(12)+',';
				}
				j++;
			});
			let previous_id_array = previous_id.split(",");
			previous_id_array.forEach(function(entry) {
			    $('#single_item_'+entry).attr('data-selected','selected');
			    $('#single_item_'+entry).css('background-color','#B5D6F6');
			});
			if(previous_id!=''){
				$.ajax({
					url:base_url+"Waiter/update_cooking_status_delivery_take_away_ajax",
					method:"POST",
					data:{
						previous_id : previous_id,
						cooking_status : 'Done',
						csrf_irestoraplus: csrf_value_
					},
					success:function(response) {
						swal({
							title: 'Alert',
							text: "Cooking Done!!",
			                confirmButtonColor: '#b6d6f6' 
						});	

					},
					error:function(){
						alert("error");
					}
				});
			}
		}
	});
	$(document).on('click','#order_details_holder .single_order',function(){
		let sale_id = $(this).attr('id').substr(13);
		$('#order_details_holder .single_order').attr('data-selected','unselected');
		$('#order_details_holder .single_order').css('background-color','#ffffff');
		$(this).attr('data-selected','selected');
		$(this).css('background-color','#b6d6f6');
		$('#selected_order_for_refreshing_help').html(sale_id);
		$.ajax({
			url:base_url+"Waiter/get_order_details_waiter_ajax",
			method:"POST",
			data:{
				sale_id : sale_id,
				csrf_irestoraplus: csrf_value_
			},
			success:function(response) {
				response = JSON.parse(response);

				let order_type = '';
				if(response.order_type=='1'){
					order_type = "Dine In";
				}else if(response.order_type=='2'){
					order_type = "Take Away";
				}else if(response.order_type=='3'){
					order_type = "Delivery";
				}
				let draw_table_for_order='';
							
				for (let key in response.items) {
					//construct div
					let this_item = response.items[key];
					
					let selected_modifiers = '';
					let selected_modifiers_id = '';
					let selected_modifiers_price = '';
					let modifiers_price = 0;
					let total_modifier = this_item.modifiers.length;
					let i = 1;
					for(let mod_key in this_item.modifiers)
					{
						let this_modifier = this_item.modifiers[mod_key];
						//get selected modifiers
				    	if(i == total_modifier){
			    			selected_modifiers += this_modifier.name;
			    			selected_modifiers_id += this_modifier.modifier_id;
			    			selected_modifiers_price += this_modifier.modifier_price;
				    		modifiers_price = (parseFloat(modifiers_price)+parseFloat(this_modifier.modifier_price)).toFixed(2);
				    	}else{
			    			selected_modifiers += this_modifier.name+',';
			    			selected_modifiers_id += this_modifier.modifier_id+',';
				    		selected_modifiers_price += this_modifier.modifier_price+',';
				    		modifiers_price = (parseFloat(modifiers_price)+parseFloat(this_modifier.modifier_price)).toFixed(2);
				    	}
					    i++;
					}
					let backgroundForSingleItem = '';
					if(this_item.cooking_status=='Done'){
						backgroundForSingleItem ='style="background-color:#598527;"';	
					}else if(this_item.cooking_status=='Started Cooking'){
						backgroundForSingleItem ='style="background-color:#0c5889;"';
					}
					
					draw_table_for_order += '<div '+backgroundForSingleItem+' data-order-type="'+order_type+'" data-selected="unselected" class="single_item_in_order fix floatleft" id="single_item_'+this_item.previous_id+'">';
						draw_table_for_order += '<h3 class="item_name">'+this_item.menu_name+'</h3>';
						draw_table_for_order += '<p class="item_qty">Qty: '+this_item.qty+'</p>';
						draw_table_for_order += '<p class="modifier_name">Modifiers: '+selected_modifiers+'</p>'
						draw_table_for_order += '<p class="note">Note: '+this_item.menu_note+'</p>';
					draw_table_for_order += '</div>';
				}
				//empty order detail segment
				$("#items_holder_of_order").empty();
				//add to top
				$("#items_holder_of_order").prepend(draw_table_for_order);
			
			},
			error:function(){
				alert("error");
			}
		});

	});

	//this is to set height when site load
	window.height_should_be = parseInt($(window).height())-parseInt($('.top').height());
	$('.bottom_left').css('height',height_should_be+'px');
	$('.bottom_right').css('height',height_should_be+'px');
	//end

	$(document).on('click','#notification_button',function(){
		//$('#notification_button').css('background-color','#F3F3F3');
		//$('#notification_button').css('color','buttontext');
		$('#notification_list_modal').fadeIn('500');
	});
	$(document).on('click','#notification_close',function(){
		$('#notification_list_modal').fadeOut('500');
		$('.single_notification_checkbox').prop('checked', false);
		$('#select_all_notification').prop('checked', false);
	});
	$(document).on('click','#notification_remove_all',function(){
		
		if($('.single_notification_checkbox:checked').length>0){
			let r = confirm("Are you sure to delete all notifications?");
			if (r ==false) {

				return false;
			}
			let notifications = '';
			let j = 1;
			let checkbox_length = $('.single_notification_checkbox:checked').length;
			$('.single_notification_checkbox:checked').each(function(i, obj) {
				if(j==checkbox_length){
					notifications += $(this).val();	
				}else{
					notifications += $(this).val()+',';
				}
				j++;
			});
			if(notifications!=""){
				let notifications_array = notifications.split(",");
				notifications_array.forEach(function(entry) {
				    $('#single_notification_row_'+entry).remove();
				});
				//Then read the values from the array where 0 is the first
				//Since we skipped the first element in the array, we start at 1
				$.ajax({
					url:base_url+"Waiter/remove_multiple_notification_ajax",
					method:"POST",
					data:{
						notifications : notifications,
						csrf_irestoraplus: csrf_value_
					},
					success:function(response) {
						// $('#single_notification_row_'+response).remove();
					},
					error:function(){
						alert("error");
					}
				});
			}			
		}else{
			swal({
				title: 'Alert',
				text: 'No notification is selected',
                confirmButtonColor: '#b6d6f6' 
			});
		}
	});
	$(document).on('click','.single_serve_b',function(){
		let notification_id = $(this).attr('id').substr(26);
		$.ajax({
			url:base_url+"Waiter/remove_notication_ajax",
			method:"POST",
			data:{
				notification_id : notification_id,
				csrf_irestoraplus: csrf_value_
			},
			success:function(response) {
				$('#single_notification_row_'+response).remove();
			},
			error:function(){
				alert("error");
			}
		});	
	});
    $(document).on('change','#select_all_notification',function(){
		if ($(this).is(':checked')) {
			$('.single_notification_checkbox').prop('checked', true);
		}else{
			$('.single_notification_checkbox').prop('checked', false);
		}
	});
});
// ==================================================
$(window).on('resize', function(){
	window.height_should_be = parseInt($(window).height())-parseInt($('.top').height());
	$('.bottom_left').css('height',height_should_be+'px');
	$('.bottom_right').css('height',height_should_be+'px');
});
// =============================================
$('.all_order_holder').slimscroll({
	height: '99.5%'
}).parent().css({
	background: '#f5f5f5',
	border: '0px solid #184055'
});
$('#items_holder_of_order').slimscroll({
	height: '430px'
}).parent().css({
	background: '#f5f5f5',
	border: '0px solid #184055'
});



setInterval(function(){ 
	new_notification_interval(); 
}, 15000);

setInterval(function(){ 
	$('#order_details_holder .single_order').each(function(i, obj) {
		let order_id = $(this).attr('id').substr(13);
		let minutes = $('#ordered_minute_'+order_id).html();
		let seconds = $('#ordered_second_'+order_id).html();
		upTime($(this),minutes,seconds);
	});
}, 1000);

function upTime(object,minute,second) {
  order_id = object.attr('id').substr(13);
  if($('#ordered_minute_'+order_id).html()=='00' && $('#ordered_second_'+order_id).html()=='00'){
  	return false;
  }
  second++;
  if(second==60){
  	minute++;
  	second=0;
  }
  
  minute = minute.toString();
  second = second.toString();
  minute = (minute.length==1)?'0'+minute:minute;
  second = (second.length==1)?'0'+second:second;
  $('#ordered_minute_'+order_id).html(minute);
  $('#ordered_second_'+order_id).html(second);

  // upTime2.to=setTimeout(function(){ upTime2(object,second,minute,hour); },1000);
}
function new_notification_interval(){
	 
	$.ajax({
		url:base_url+"Waiter/get_new_notifications_ajax",
		method:"POST",
		data:{
			csrf_irestoraplus: csrf_value_
		},
		success:function(response) {
			response = JSON.parse(response);
			let notification_counter_update = response.length;
			let notification_counter_previous = $('#notification_counter').html();
			$('#notification_counter').html(notification_counter_update);
			if(notification_counter_update>notification_counter_previous){
				
				setTimeout(function(){ 
					$('#notification_button').css('background-color','#dc3545')
					$('#notification_button').css('color','#fff'); 
				}, 500);
				setTimeout(function(){ 
					$('#notification_button').css('background-color','#ccc');
					$('#notification_button').css('color','buttontext'); 
				}, 1000);
				setTimeout(function(){ 
					$('#notification_button').css('background-color','#dc3545')
					$('#notification_button').css('color','#fff'); 
				}, 1500);
				setTimeout(function(){ 
					$('#notification_button').css('background-color','#ccc');
					$('#notification_button').css('color','buttontext'); 
				}, 2000);
				setTimeout(function(){ 
					$('#notification_button').css('background-color','#dc3545')
					$('#notification_button').css('color','#fff'); 
				}, 2500);
				setTimeout(function(){ 
					$('#notification_button').css('background-color','#ccc');
					$('#notification_button').css('color','buttontext'); 
				}, 3000);
				setTimeout(function(){ 
					$('#notification_button').css('background-color','#dc3545')
					$('#notification_button').css('color','#fff'); 
				}, 3500);
			}

			// $order_list_left = '';
			// let i = 1;
			let notifications_list = '';
			for (let key in response) {
				let this_notification = response[key];
				notifications_list += '<div class="single_row_notification fix" id="single_notification_row_'+this_notification.id+'">';
					notifications_list += '<div class="fix single_notification_check_box">';
						notifications_list += '<input class="single_notification_checkbox" type="checkbox" id="single_notification_'+this_notification.id+'" value="'+this_notification.id+'">';
					notifications_list += '</div>';
					notifications_list += '<div class="fix single_notification">'+this_notification.notification+'</div>';
					notifications_list += '<div class="fix single_serve_button">';
						notifications_list += '<button class="single_serve_b" id="notification_serve_button_'+this_notification.id+'">Collect</button>';
					notifications_list += '</div>';
				notifications_list += '</div>';
			}
			$('#notification_list_holder').html(notifications_list);
		},
		error:function(){
			console.log("Notification refresh error");
		}
	});
}