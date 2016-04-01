/*
 * common.js
 * 
 *
 */


//action confirm
function confirm_action(url, msg, callback)
{
    if (confirm(msg)) {
        $.get(url, function(json){
            if (callback == 'reload') {
                window.location.reload();
            }
            else if (callback == undefined)
            {
                //code
            }
            else
            {
                callback(json);
            }
        });
    }
    
}

//全选(操作的checkbox对象id，被操作的checkbox父对象id)
function checkall(ckb, ck)
{
    if ($('#'+ckb).prop('checked') == true)
    {
        $('#'+ck+' input:checkbox').prop('checked', true);
    }
    else
    {
        $('#'+ck+' input:checkbox').prop('checked', false);
    }
}


//获取省份
function get_getpro(list) {
	$.post('/api/getpros/',{},function(data)
	{		
		var a = eval('(' + data + ')');
		var m='';
		m = "<select mSty=\"orangeHeart\" class=\"get_city\" onchange=\"get_city(this)\" name=\"provinceid\" id=\"provinceid\" >";
		m += "<option value='0'>请选择省份</option>";	
		var tmp = '';
		$.each(a,function(key,val){
			
			m += "<option value="+key+">"+val+"</option>";
		});
		m += "</select>";

		$(".manager_pro").html(m);
		if(list['provinceid'])
		{
			document.getElementById('provinceid').value = list['provinceid'];
			get_city();
		}
	});
	
}
//获取城市
function get_city(){
	$.post('/api/getcity/',{checkval:document.getElementById('provinceid').value},function(data)
	{
		var a = eval('(' + data + ')');
		var m='';
		m = "<select mSty=\"orangeHeart\" class=\"cityid\" id=\"cityid\" name=\"cityid\">";
		m += "<option value='0'>请选择城市</option>";
		$.each(a,function(key,val){
			m += "<option value="+key+">"+val+"</option>";
		});
		m += "</select>";
		$(".manager_city").html(m);
		if(list['cityid'])
		{
			document.getElementById('cityid').value = list['cityid'];
		}
	});
}

