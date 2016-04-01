//dcm 2012-7-19
//图片单独显示列表JS
var html_index=0; //对象类型，0 为全车 1 为车身图片 2 外观 3 内饰  4 骨架
var Arraylist_select_imglist=new Array();//选中类型 列表
var outher_smaill_title_list=new Array();//类型id 列表
var outher_smaill_select_obj=new Array();//选择列表中单独大项 
var outher_smaill_select_obj_index=0;//选中单独项对用的下标
var outher_smaill_select_index_a=0;//外观内饰骨架使用当前选中区域下标
//启动显示区域图片显示
function outherimg_show_detect_Area(e,index_a)
{	
	//显示图片显示区域 关闭页面
    $("#detect_show_view_kuang").css("display", "none");
    $("#body_showimglist_id").css("display", "");
    $("body").append("<div id='bigdiv'></div>");
    $("#bigdiv").css("height", $("body").height());
    $("#body_showimglist_id").css("left", ($("body").width() - 1079) / 2);
    
	//当前区域赋值
	html_index=e;
	outher_smaill_select_obj_index=index_a;
	switch(e)
	{
		case 1://车身18张图片列表
			typelistdic=CarInfor_Imglist;
			outherimg_settitlelist_start(typelistdic,index_a);//设置列表内容
			outherimg_setcarinfo_start(index_a);//初始化车身图片			
		break;
		case 2://外观图片列表
			typelistdic=outher_dic_AreaMap; 
			outherimg_settitlelist_start(typelistdic,index_a);//设置列表内容
			outher_smaill_select_index_a=index_a;//存储选中区域
			outherimg_outher_start(index_a,0,outher_dic_AreaMap);			
		break;
		case 3://内饰图片列表		
		typelistdic=inner_dic_AreaMap;
		outherimg_settitlelist_start(typelistdic,index_a)//设置列表内容
		outher_smaill_select_index_a=index_a;//存储选中区域
		outherimg_inner_start(index_a,0,inner_dic_AreaMap)
		break;
		case 4://骨架图片列表左
		typelistdic=skele_dic_AreaMap_Left;
	    outherimg_settitlelist_start(typelistdic,index_a)//设置列表内容
		outher_smaill_select_index_a=index_a;//存储选中区
		outherimg_skele_start(index_a, 0, skele_dic_AreaMap_Left)
		changeskeletonLeftPoint();
			break;
		case 5://骨架图片列表右
		typelistdic=skele_dic_AreaMap_Right;
		 outherimg_settitlelist_start(typelistdic,index_a)//设置列表内容
		 outher_smaill_select_index_a = index_a; //存储选中区
		 outherimg_skele_start(index_a, 0, skele_dic_AreaMap_Right)
		 changeskeletonRightPoint();
			break;
	}
}
//骨架显示初始化
function outherimg_skele_start(index_a,selectindex,typelistdic)
{
	//显示内饰雷达图
    $("#div_outher_guang_show_img").html(skeleton_check_point);
	//对当前数组赋值
	Arraylist_select_imglist=typelistdic[index_a]["show_img"].toString().split('$');
	//显示分页数字
	if(selectindex<0)selectindex= Number(Arraylist_select_imglist.length-1);
	if(selectindex> Number(Arraylist_select_imglist.length-1))selectindex= 0;
	$("#div_outher_guang_show_type").html("<span class='page_select'>"+Number(selectindex+1)+"</span>/"+Arraylist_select_imglist.length);
	outher_smaill_select_obj_index=selectindex;
	//是否显示分页按钮
	$("#outher_img_detectleftright").html("");
	if(Arraylist_select_imglist.length>1)$("#outher_img_detectleftright").html(outher_smaill_leftbtn_rightbtn);	
	//显示数据
	outher_smaill_select_obj=Arraylist_select_imglist[selectindex].split(';');
	//显示当前标题
	$("#outher_img_detecttype").html(typelistdic[index_a]["name"].toString()+"有损伤：<span class='detect'>"+outherimg_getinner_detecttype(outher_smaill_select_obj[2],skele_dic_detectType)+"</span>")
	//判断是否本地	
	if(detect_taskid!=0){
		$("#outher_img_detectimg").html("<img src='"+addbigimg(outher_smaill_select_obj[1])+"'>");	  
	}else
	{
		$("#outher_img_detectimg").html("<img src='"+outher_smaill_select_obj[1]+"'>");	 
	}
   //设置选中雷达显示	
  $("#smaill_map" + outher_smaill_select_obj[0]).html("<img src='local/images/light_red.gif'>");
}
//内饰显示初始化
function outherimg_inner_start(index_a,selectindex,typelistdic)
{
	//显示内饰雷达图
	$("#div_outher_guang_show_img").html(outher_inner_smaill_brground);
	//对当前数组赋值
	Arraylist_select_imglist=typelistdic[index_a]["show_img"].toString().split('$');
	//显示分页数字
	if(selectindex<0)selectindex= Number(Arraylist_select_imglist.length-1);
	if(selectindex> Number(Arraylist_select_imglist.length-1))selectindex= 0;
	$("#div_outher_guang_show_type").html("<span class='page_select'>"+Number(selectindex+1)+"</span>/"+Arraylist_select_imglist.length);
	outher_smaill_select_obj_index=selectindex;
	//是否显示分页按钮
	$("#outher_img_detectleftright").html("");
	if(Arraylist_select_imglist.length>1)$("#outher_img_detectleftright").html(outher_smaill_leftbtn_rightbtn);	
	//显示数据
	outher_smaill_select_obj=Arraylist_select_imglist[selectindex].split(';');
	//显示当前标题
	$("#outher_img_detecttype").html(typelistdic[index_a]["name"].toString()+"有损伤：<span class='detect'>"+outherimg_getinner_detecttype(outher_smaill_select_obj[2],inner_dic_detectType)+"</span>")
	//判断是否本地	
	if(detect_taskid!=0){
		$("#outher_img_detectimg").html("<img src='"+addbigimg(outher_smaill_select_obj[1])+"'>");	  
	}else
	{
		$("#outher_img_detectimg").html("<img src='"+outher_smaill_select_obj[1]+"'>");	 
	}
	//设置选中雷达显示	
	$("#smaill_li"+outher_smaill_select_obj[0]).html("<img src='local/images/light_red.gif'>");
}
//外观显示初始化
function outherimg_outher_start(index_a,selectindex,typelistdic)
{	
	//显示外观雷达图
	$("#div_outher_guang_show_img").html(outher_smaill_brground);
	//对当前数组赋值
	Arraylist_select_imglist=typelistdic[index_a]["show_img"].toString().split('$');
	//显示分页数字
	if(selectindex<0)selectindex= Number(Arraylist_select_imglist.length-1);
	if(selectindex> Number(Arraylist_select_imglist.length-1))selectindex= 0;
	$("#div_outher_guang_show_type").html("<span class='page_select'>"+Number(selectindex+1)+"</span>/"+Arraylist_select_imglist.length);
	outher_smaill_select_obj_index=selectindex;
	//是否显示分页按钮
	$("#outher_img_detectleftright").html("");
	if(Arraylist_select_imglist.length>1)$("#outher_img_detectleftright").html(outher_smaill_leftbtn_rightbtn);	
	//显示数据
	outher_smaill_select_obj=Arraylist_select_imglist[selectindex].split(';');
	//显示当前标题
	$("#outher_img_detecttype").html(typelistdic[index_a]["name"].toString()+"有损伤：<span class='detect'>"+outherimg_getinner_detecttype(outher_smaill_select_obj[2],outher_dic_detectType)+"</span>")
	//判断是否本地	
	if(detect_taskid!=0){	
		$("#outher_img_detectimg").html("<img src='"+addbigimg(outher_smaill_select_obj[1])+"'>");	  
	}else
	{	
		$("#outher_img_detectimg").html("<img src='"+outher_smaill_select_obj[1]+"'>");	 
	}
	//设置选中雷达显示
	$("#small_li"+outher_smaill_select_obj[0]).html("<img src='local/images/light_red.gif'>");
}
//左右按钮点击事件
function outherimg_leftbtn_rightbtn_onclick(e)
{
	//左点击
	if(e==0){		
		outher_smaill_select_obj_index--;
	}else
	{//右点击
		outher_smaill_select_obj_index++;
	}
	//初始化数据
	switch(html_index)
	{
		case 1://车身18张图片列表
		if(outher_smaill_select_obj_index>17)outher_smaill_select_obj_index=0;
		if(outher_smaill_select_obj_index<0)outher_smaill_select_obj_index=17;
		outherimg_titlelist_click(outher_smaill_select_obj_index);		
		break;
		case 2://外观图片列表		
		outherimg_outher_start(outher_smaill_select_index_a,outher_smaill_select_obj_index,outher_dic_AreaMap);
		break;
		case 3://内饰图片列表
		 outherimg_inner_start(outher_smaill_select_index_a,outher_smaill_select_obj_index,inner_dic_AreaMap);
		break;
		case 4://骨架左侧45度
		    outherimg_skele_start(outher_smaill_select_index_a, outher_smaill_select_obj_index, skele_dic_AreaMap_Left);
		    changeskeletonLeftPoint();
		break;
		case 5://骨架左侧45度
		    outherimg_skele_start(outher_smaill_select_index_a, outher_smaill_select_obj_index, skele_dic_AreaMap_Right);
		    changeskeletonRightPoint();
		break;
	}

}
//分类列表点击事件
function outherimg_titlelist_click(e)
{
	//对列表状态赋值
	for(var i=0;i<outher_smaill_title_list.length;i++)
	{
		if(outher_smaill_title_list[i].toString()=="outher_title_"+e)
			$("#"+outher_smaill_title_list[i]).attr("class","select");
		else
			$("#"+outher_smaill_title_list[i]).attr("class","outselect");
	}
	
	//初始化数据
	switch(html_index)
	{
		case 1://车身18张图片列表
		outher_smaill_select_obj_index=e;
		outherimg_setcarinfo_start(e);			
		break;
		case 2://外观图片列表
		outher_smaill_select_index_a=e;//存储当前区域
		outherimg_outher_start(e,0,outher_dic_AreaMap);
		break;
		case 3://内饰图片列表		
		outher_smaill_select_index_a=e;//存储当前区域
		outherimg_inner_start(e,0,inner_dic_AreaMap);
		break;
		case 4://骨架左侧图片列表		
		outher_smaill_select_index_a=e;//存储当前区域
		outherimg_skele_start(e, 0, skele_dic_AreaMap_Left);
		changeskeletonLeftPoint();
		break;
		case 5://骨架右侧图片列表		
		outher_smaill_select_index_a=e;//存储当前区域
		outherimg_skele_start(e, 0, skele_dic_AreaMap_Right);
		changeskeletonRightPoint();
		break;
	}
}
//关闭图片列表
function outherimg_colse__detect_Area()
{
	//html_index=0;
	Arraylist_select_imglist=[];//选中类型 列表
    outher_smaill_title_list=[];//类型id 列表
    outher_smaill_select_obj=[];//选择列表中单独大项 
    outher_smaill_select_obj_index=0;//选中单独项对用的下标
    outher_smaill_select_index_a = 0;
    //取消黑底
    $("#bigdiv").remove();
	//取消显示和图片和按钮
	$("#div_outher_guang_show_type").html("");
	$("#outher_img_detectleftright").html("");
	$("#div_outher_guang_show_img").html("");	
    $("#outher_img_detectimg").html("图片加载中。。。");
     //显示大区
    $("#body_div").css("display", "");
    $("#body_showimglist_id").css("display", "none");
	//$("#body_showimglist").css("display","none");
	if(html_index==1)//返回18张图片
	window.location.href="#top"
	if(html_index==2)//返回外观
	window.location.href="#a3"
    if(html_index==3)//返回外观
	window.location.href="#a3"
	if(html_index==4)//返回骨架
	window.location.href="#a7"		
}
//初始化车身图片显示
function outherimg_setcarinfo_start(index_a)
{
     //显示左右按钮
	$("#outher_img_detectleftright").html(outher_smaill_leftbtn_rightbtn);
	//设置拍摄位置显示
	$("#div_outher_guang_show_img").html(outher_smaill_carinfo_weizhi[index_a]);
	//显示分页数字
	$("#div_outher_guang_show_type").html("<span class='page_select'>"+Number(index_a+1)+"</span>/18");
	
	if(detect_taskid!=0){
	//真实数据
	$("#outher_img_detectimg").html("<img src='"+addbigimg(typelistdic[index_a]["show_txt"].toString())+"'>");	
	//测试用
		//$("#outher_img_detectimg").html("<img src='local/images/testimg.png'>");		
	}else
	{
		$("#outher_img_detectimg").html("<img src='"+typelistdic[index_a]["show_txt"].toString()+"'>");
	}
	//标题
	$("#outher_img_detecttype").html(typelistdic[index_a]["name"].toString());
}
//初始化列表
function outherimg_settitlelist_start(typelistdic,index_a)
{ 
   	//显示列表
	var htmltable = "";
    var count = 0;
	 for(var i = 0; i < typelistdic.length; i++)
	{
		
      if (typelistdic[i]["show_txt"].toString() != "") {
		  outher_smaill_title_list.push("outher_title_"+typelistdic[i]["index"].toString());
		  if(i!=index_a)
		 htmltable += "<li id='outher_title_"+typelistdic[i]["index"].toString()+"' class='outselect' onclick='outherimg_titlelist_click("+typelistdic[i]["index"].toString()+")'><span >" + typelistdic[i]["name"].toString() + "</span></li>";
		 else
		 htmltable += "<li id='outher_title_"+typelistdic[i]["index"].toString()+"' class='select' onclick='outherimg_titlelist_click("+typelistdic[i]["index"].toString()+")'><span >" + typelistdic[i]["name"].toString() + "</span</li>";		 
		 count++;
      }
    }  
    if (count != 0){
        $("#img_list").html(htmltable);
    }	
}

//得到内饰显示类型
function outherimg_getinner_detecttype(e,list)
{
	var str_type="";
	var typelist=e.split(',');
	for(var j=0;j<typelist.length;j++){
		for(var i=0;i<list.length;i++)
		{
			if(typelist[j]==list[i]["key"])
			{
				if(str_type!="")str_type+=",";
				str_type+=list[i]["name"];
				break;
			}
		}
	}
	return str_type;
}

//切换骨架图检测点显示
function changeskeletonLeftPoint(num) {
    for (var i = 0; i < skeletonRightPoint.length; i++) {
        $("#smaill_" + skeletonRightPoint[i]).hide();
    }
    for (var i = 0; i < skeletonLeftPoint.length; i++) {
        $("#smaill_" + skeletonLeftPoint[i]).show();
    }

    $(".carskeleton_smaill").css("background", "url('local/images/skeleton_left.png')  no-repeat center");
}

function changeskeletonRightPoint(num) {
    for (var i = 0; i < skeletonRightPoint.length; i++) {
        $("#smaill_" + skeletonRightPoint[i]).show();
    }
    for (var i = 0; i < skeletonLeftPoint.length; i++) {
        $("#smaill_" + skeletonLeftPoint[i]).hide();
    }

    $(".carskeleton_smaill").css("background", "url('local/images/skeleton_right.png')  no-repeat center");
}

