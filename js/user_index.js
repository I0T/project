$(function() {

	$("#loginbtn").click(function(){
		if ($('#username').val() == "" || $('#password').val() == ""){
			$("#errormsg").html("{$y_lang.msg_userpwd_not_empty}");
			return ;
		}
		$.post('/user/checkuser',{ 'username':$('#username').val(),'password':$('#password').val() },function(data){
			if(data=="1")
			{
				window.location.reload();
			}
			else
			{
				$("#errormsg").html('{$y_lang.msg_login_fail}');
			}
		});
	});

	changeTabelColor(".car-list","table","tr","odd");
	$('.label-radio').click(function(){
		setupLabel();
	});
	setupLabel();
	$(".payPrice").click(function(){
		show_popup('#pricePopup','.closeJs');
	});
	yxp_serial_model_trim('#yxp_serial_select', '#yxp_model_select', '#yxp_trim_select'); //车辆品牌、车系、车型三级联动

	$(".datepicker").datepicker({ changeYear: true });
	$(".fancybox").fancybox();

});



function publish(id){
	$.post('/user/index?detail=pricing',{ carid:id },function(data)
	{
		//alert(a.start_prices);
		var a = eval('(' + data + ')');
		$("#car_name").html(a.en_name);
		$("#mileage").html(a.mileage);
		$("#exhaust").html(a.exhaust);
		$("#start_price").html(a.start_price);
		$("#hidden_price").html(a.start_prices);
		$("#year").html(a.year);
		$("#car_no").html(a.pid);
		$("#pid").val(a.pid);
		$("#car_configure").html(a.config_info);
	});
}

//删除 收藏
function del(id)
{
	var confim_del = "{$y_lang.car_confirm_del_data}";
	var del_success = "{$y_lang.car_confirm_del_success}";
	var del_fail = "{$y_lang.car_confirm_del_fail}";
	if(confirm(confim_del))
	{
		$.post("/user/favorite_del/",{ pid:id },function(data){
			if(data>0)
			{
				alert(del_success);
				location.reload();
			} else {
				alert(del_fail);
			}
		});
	}
}


function sub()
{
	var carno_first = $("#carno_first").val();
	var CheckData = /<|>|'|;|&|#|"|'/;
	if (CheckData.test(carno_first)) {
		alert("车辆编号包含非法字符，请不要使用特殊字符！");
		return false;
	}else{
		$('.searchForm').submit();
	}
}

//收藏车辆
function favarite(id){
	var car_collection_of_success = "{$y_lang.car_collection_of_success}";
 	var car_collection_fail_warning = "{$y_lang.car_collection_fail_warning}";
 	var car_collection_successed = "{$y_lang.car_collection_successed}";
	$.post("/user/favorite/",{ pid:id }, function(data){
		 if(data>0)
		 {
	    	alert(car_collection_of_success);
		 }
		 else if(data== -2)
		 {
		 	alert(car_collection_fail_warning);
		 }
		 else if(data== -1)
		 {
			alert(car_collection_successed);
		 }
	});
}

//出价 提交价格
function insert_price(){

	var car_enter_price = "{$y_lang.car_enter_price}";
	var car_enter_price_success = "{$y_lang.car_enter_price_success}";
	var car_margin_warning = "{$y_lang.car_margin_warning}";
	var car_public_once = "{$y_lang.car_public_once}";
	var car_invalid_argument = "{$y_lang.car_invalid_argument}";
	var car_tender_price = "{$y_lang.car_tender_price}";
	if($("#offerPrice").val()=='')
	{
		$("#reminder").html(car_enter_price);
		return ;
	}
	var status = $('input[name=fukuan]:checked').val();
	$.post("/user/bid/",{ pricing:$("#offerPrice").val(),publishid:$("#pid").val(),status:status }, function(data){
		 if(data>0)
		 {
			$("#reminder").html(car_enter_price_success);
			location.reload();
		 }
		 else if(data== -1)
		 {
			$("#reminder").html(car_margin_warning);
		 }
		 else if(data== -2)
		 {
			$("#reminder").html(car_public_once);
		 }
		 else if(data== -3)
		 {
			$("#reminder").html(car_invalid_argument);
		 }
		 else if(data== -4)
		 {
		 	$("#reminder").html(car_tender_price);
		 }
	});
}

function shows(nums)
{
	if(nums==0){
		$('.cost').hide();
	}else if(nums==1){
		$('.cost').show();
	}
}
