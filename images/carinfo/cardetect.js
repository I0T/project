// 2012-7-27
//dcm
//损伤显示
var Yx_gj_ShowArry = ["无修复","无修复", "无修复","无修复","无修复","无修复","无修复","无修复","无修复"];
var Yx_qm_ShowArry = ["","无修复", "无修复", "无修复","无修复","无修复", "无修复", "无修复","无修复","无修复","无修复","无修复"];
var yx_qm_showImgArray = [{ "img": "03", "detect": 0, "name": "yx_qm_4_0" },
 { "img": "09", "detect": 0, "name": "yx_qm_4_1" },
  { "img": "14", "detect": 0, "name": "yx_qm_4_2" },
   { "img": "17", "detect": 0, "name": "yx_qm_4_12" },
    { "img": "18", "detect": 0, "name": "yx_qm_4_13" },
     { "img": "26", "detect": 0, "name": "yx_qm_4_14" },
     { "img": "27", "detect": 0, "name": "yx_qm_4_15" },
     { "img": "30", "detect": 0, "name": "yx_qm_4_16" },
      { "img": "31", "detect": 0, "name": "yx_qm_4_17" },
       { "img": "36", "detect": 0, "name": "yx_qm_4_18" },
       { "img": "37", "detect": 0, "name": "yx_qm_4_19" },
        { "img": "41", "detect": 0, "name": "yx_qm_4_32" },
         { "img": "46", "detect": 0, "name": "yx_qm_4_33" },
         { "img": "48", "detect": 0, "name": "yx_qm_4_34" },
         { "img": "07", "detect": 0, "name": "yx_qm_4_9" },
         { "img": "13", "detect": 0, "name": "yx_qm_4_10" },
          { "img": "16", "detect": 0, "name": "yx_qm_4_11" },
           { "img": "20", "detect": 0, "name": "yx_qm_4_24" },
           { "img": "21", "detect": 0, "name": "yx_qm_4_25" },
           { "img": "28", "detect": 0, "name": "yx_qm_4_26" },
            { "img": "29", "detect": 0, "name": "yx_qm_4_27" },
             { "img": "34", "detect": 0, "name": "yx_qm_4_28" },
              { "img": "35", "detect": 0, "name": "yx_qm_4_29" },
              { "img": "38", "detect": 0, "name": "yx_qm_4_30" },
              { "img": "39", "detect": 0, "name": "yx_qm_4_31" },
              { "img": "42", "detect": 0, "name": "yx_qm_4_40" },
              { "img": "47", "detect": 0, "name": "yx_qm_4_41" },
              { "img": "49", "detect": 0, "name": "yx_qm_4_42" },
              { "img": "04", "detect": 0, "name": "yx_qm_4_3" },
              { "img": "05", "detect": 0, "name": "yx_qm_4_4" },
               { "img": "06", "detect": 0, "name": "yx_qm_4_5" },
               { "img": "10", "detect": 0, "name": "yx_qm_4_6" },
                { "img": "11", "detect": 0, "name": "yx_qm_4_7" },
                 { "img": "12", "detect": 0, "name": "yx_qm_4_8" },
                  { "img": "23", "detect": 0, "name": "yx_qm_4_20" },
                   { "img": "24", "detect": 0, "name": "yx_qm_4_21" },
                    { "img": "32", "detect": 0, "name": "yx_qm_4_22" },
                     { "img": "33", "detect": 0, "name": "yx_qm_4_23" },
                      { "img": "43", "detect": 0, "name": "yx_qm_4_35" },
                       { "img": "44", "detect": 0, "name": "yx_qm_4_36" },
                        { "img": "45", "detect": 0, "name": "yx_qm_4_37" },
                         { "img": "50", "detect": 0, "name": "yx_qm_4_38" },
                          { "img": "51", "detect": 0, "name": "yx_qm_4_39"}];
var showdetectviewflag = false;
function cardetect_showdetectview(e) {

    //显示单独显示点
    showdetectviewflag = true;   
     for (var i = 0; i < carsourcedetectlist.length; i++) {
         if (carsourcedetectlist[i].detectitemmapid == e.data.foo) {
             var title = "";
             var content = "";
             var imglist = "";
             if (e.data.type == 1) {
                 content = outherimg_getinner_detecttype(carsourcedetectlist[i].detectdefectid, outher_dic_detectType)
                 title = cardetect_getareaname(carsourcedetectlist[i].detectitemid, outher_dic_AreaMap);
              
            }
             if (e.data.type == 2) {
                 content = outherimg_getinner_detecttype(carsourcedetectlist[i].detectdefectid, inner_dic_detectType)
                 title = cardetect_getareaname(carsourcedetectlist[i].detectitemid, inner_dic_AreaMap)
            }
             if (e.data.type == 3) {
                 content = outherimg_getinner_detecttype(carsourcedetectlist[i].detectdefectid, skele_dic_detectType)
                 title = cardetect_getareaname(e.data.foo, skele_dic_AreaMap)
             }
             if (carsourcedetectlist[i].defectpic != null && carsourcedetectlist[i].defectpic.toString() != "") {
                 var typelist = carsourcedetectlist[i].defectpic.split(';');
                 for (var c = 0; c < typelist.length; c++) {
                     imglist += "<img src='" + addimg(typelist[c]) + "' width=100  height=75 style='border:2px #5d5d5d solid;'/>"
                 }
             }
             title = "<div class='title'>" + title + "</div>";
             content = "<div class='content'>" + content + "</div>";
             var obj ;
             if (e.data.type != 3) obj = $("#li" + e.data.foo);
             if (e.data.type == 3) obj = $("#div_skele_new_" + e.data.foo); 
             var offset = obj.offset();
             $("#detect_show_view_kuang").css("display", "");
             $("#detect_show_view_kuang").css("top", offset.top -12);
             $("#detect_show_view_kuang").css("left", offset.left + 8);
             $("#detect_show_view").html(title + content + imglist);            
             break;
         }
    }
 }
 function cardetect_getareaname(e, list) {
     for (var i = 0; i < list.length; i++) {
         if (e == list[i]["areamap"]) 
         { return list[i]["name"]; }
     }

 }
 function cardetect_closedetectview() {
     if (showdetectviewflag == true)
         $("#detect_show_view_kuang").css("display", "none");
 }
 function cardetect_closedetectviewfalg() {
     showdetectviewflag = true;
     $("#detect_show_view_kuang").css("display", "");  
 }
function Setyinxinshow(e)
{
    for (var i = 0; i < e.length; i++) {
        switch (e[i].detectareatype) {
            case 1: //外观显示
                show_outher_obj(e[i]);
                break;
            case 2: //内饰显示
                show_inner_obj(e[i]);
                break;
            case 3: //骨架显示
                show_skeleton_obj(e[i]);
                break;
            case 4: //外观显示
                setyinxinqimianvalue(e[i]);
                break;
            case 5: //外观显示
                setyinxingujiavalue(e[i]);
                break;
        }
    }
    //显示外观table内容
    showouthertable(outher_dic_detectType, "div_show_detectouther_table", outher_dic_AreaMap,2);
    //显示内饰table内容
    showouthertable(inner_dic_detectType, "div_show_detectinner_table", inner_dic_AreaMap,3);
	//显示骨架左侧45度
	showouthertable(skele_dic_detectType, "div_show_detectskeleleft_table", skele_dic_AreaMap_Left,4);
	//显示骨架右后45度
	showouthertable(skele_dic_detectType, "div_show_detectskeleright_table", skele_dic_AreaMap_Right,5);
	//显示漆面table内容
	showqimiantable();
	//显示骨架table内容
	showgujiatable();
	//漆面显示
	
	for( var j=0;j<yx_qm_showImgArray.length;j++)	
		showqimiantable_array(yx_qm_showImgArray[j]);
	
	
}

//隐性漆面损伤的图片赋值
function setyinxinqimianvalue(e)
{
	if(e.detectdefectid=="all")
	{
		Yx_qm_ShowArry[0]="q";
		for(var i=0;i<yx_qm_showImgArray.length;i++)
		{
			if(yx_qm_showImgArray[i].detect!=2)
			yx_qm_showImgArray[i].detect=1;
		}
		for(var j=0;j<Yx_qm_ShowArry.length;j++)
		{
			if(Yx_qm_ShowArry[j]!="喷漆且有腻子修复")
			Yx_qm_ShowArry[j]= '重新喷漆';
		}		
	    return;
	}
	switch (e.detectitemmapid) 
	{
		case "4_0":
		js_yx_qm_tu(e,"yx_qm_4_0",1,"左前翼子板","03",0);
		break;
		case "4_1":
		js_yx_qm_tu(e,"yx_qm_4_1",1,"左前翼子板","09",1);
		break;
		case "4_2":
		js_yx_qm_tu(e,"yx_qm_4_2",1,"左前翼子板","14",2);
		break;	
		
		
		case "4_12":
		js_yx_qm_tu(e,"yx_qm_4_12",2,"左前门","17",3);
		break;
		case "4_13":
		js_yx_qm_tu(e,"yx_qm_4_13",2,"左前门","18",4);
		break;
		case "4_14":
		js_yx_qm_tu(e,"yx_qm_4_14",2,"左前门","26",5);
		break;	
		case "4_15":
		js_yx_qm_tu(e,"yx_qm_4_15",2,"左前门","27",6);
		break;
		case "4_16":
		js_yx_qm_tu(e,"yx_qm_4_16",3,"左后门","30",7);
		break;
		case "4_17":
		js_yx_qm_tu(e,"yx_qm_4_17",3,"左后门","31",8);
		break;
		case "4_18":
		js_yx_qm_tu(e,"yx_qm_4_18",3,"左后门","36",9);
		break;	
		case "4_19":
		js_yx_qm_tu(e,"yx_qm_4_19",3,"左后门","37",10);
		break;
		case "4_32":
		js_yx_qm_tu(e,"yx_qm_4_32",4,"左后翼子板","41",11);
		break;
		case "4_33":
		js_yx_qm_tu(e,"yx_qm_4_33",4,"左后翼子板","46",12);
		break;
		case "4_34":
		js_yx_qm_tu(e,"yx_qm_4_34",4,"左后翼子板","48",13);
		break;
			
		case "4_9":
		js_yx_qm_tu(e,"yx_qm_4_9",5,"右前翼子板","07",14);
		break;
		case "4_10":
		js_yx_qm_tu(e,"yx_qm_4_10",5,"右前翼子板","13",15);
		break;
		case "4_11":
		js_yx_qm_tu(e,"yx_qm_4_11",5,"右前翼子板","16",16);
		break;	
		
		case "4_24":
		js_yx_qm_tu(e,"yx_qm_4_24",6,"右前门","20",17);
		break;
		case "4_25":
		js_yx_qm_tu(e,"yx_qm_4_25",6,"右前门","21",18);
		break;
		case "4_26":
		js_yx_qm_tu(e,"yx_qm_4_26",6,"右前门","28",19);
		break;	
		case "4_27":
		js_yx_qm_tu(e,"yx_qm_4_27",6,"右前门","29",20);
		break;
		case "4_28":
		js_yx_qm_tu(e,"yx_qm_4_28",7,"右后门","34",21);
		break;
		case "4_29":
		js_yx_qm_tu(e,"yx_qm_4_29",7,"右后门","35",22);
		break;
		case "4_30":
		js_yx_qm_tu(e,"yx_qm_4_30",7,"右后门","38",23);
		break;	
		case "4_31":
		js_yx_qm_tu(e,"yx_qm_4_31",7,"右后门","39",24);

		break;	
		case "4_40":
		js_yx_qm_tu(e,"yx_qm_4_40",8,"右后翼子板","42",25);
		break;
		case "4_41":
		js_yx_qm_tu(e,"yx_qm_4_41",8,"右后翼子板","47",26);
		break;
		case "4_42":
		js_yx_qm_tu(e,"yx_qm_4_42",8,"右后翼子板","49",27);
		break;
		
		case "4_3":
		js_yx_qm_tu(e,"yx_qm_4_3",9,"前机盖","04",28);
		break;
		case "4_4":
		js_yx_qm_tu(e,"yx_qm_4_4",9,"前机盖","05",29);
		break;
		case "4_5":
		js_yx_qm_tu(e,"yx_qm_4_5",9,"前机盖","06",30);
		break;
		case "4_6":
		js_yx_qm_tu(e,"yx_qm_4_6",9,"前机盖","10",31);
		break;
		case "4_7":
		js_yx_qm_tu(e,"yx_qm_4_7",9,"前机盖","11",32);
		break;
		case "4_8":
		js_yx_qm_tu(e,"yx_qm_4_8",9,"前机盖","12",33);
		break;
		
		case "4_20":
		js_yx_qm_tu(e,"yx_qm_4_20",10,"车顶","23",34);
		break;
		case "4_21":
		js_yx_qm_tu(e,"yx_qm_4_21",10,"车顶","24",35);
		break;
		case "4_22":
		js_yx_qm_tu(e,"yx_qm_4_22",10,"车顶","32",36);
		break;
		case "4_23":
		js_yx_qm_tu(e,"yx_qm_4_23",10,"车顶","33",37);
		break;
			
		case "4_35":
		js_yx_qm_tu(e,"yx_qm_4_35",11,"后背箱","43",38);
		break;
		case "4_36":
		js_yx_qm_tu(e,"yx_qm_4_36",11,"后背箱","44",39);
		break;
		case "4_37":
		js_yx_qm_tu(e,"yx_qm_4_37",11,"后背箱","45",40);
		break;
		case "4_38":
		js_yx_qm_tu(e,"yx_qm_4_38",11,"后背箱","50",41);
		break;
		case "4_39":
		js_yx_qm_tu(e,"yx_qm_4_39",11,"后背箱","51",42);
		break;

	}
}
function showqimiantable_array(e)
{
	switch (Number(e.detect)) 
	{
		case 1:		    
			$("#"+e.name).attr("src","local/images/carin/in_"+e.img+".png");
			break;
		case 2:
		    $("#" + e.name).attr("src", "local/images/carout/out_" + e.img + ".png");
			break;
		default:
		    $("#" + e.name).attr("src", "local/images/toumingdian.png");	
		break;
	 }	
};


function js_yx_qm_tu(e,div_id,arrayindex,name,img,arrindx)
{
	switch (Number(e.detectdefectid)) 
	{
	    case 1:
	        if (yx_qm_showImgArray[arrindx].detect != 2) yx_qm_showImgArray[arrindx].detect = 1;
	        yx_qm_showImgArray[arrindx].detect = 1;
	        if (Yx_qm_ShowArry[arrayindex] != "喷漆且有腻子修复")
	            Yx_qm_ShowArry[arrayindex] = '重新喷漆';
	        yx_qm_showImgArray[arrindx].img = img;
	        break;
		case 2:
		    yx_qm_showImgArray[arrindx].detect=2;
		    Yx_qm_ShowArry[arrayindex] = '喷漆且有腻子修复';
		    yx_qm_showImgArray[arrindx].img = img;
			
			break;
		default:
		    Yx_qm_ShowArry[arrayindex] = '无修复';
		    yx_qm_showImgArray[arrindx].img = img;
			
		break;
	 }	
}
//隐性骨架图片赋值定位
function setyinxingujiavalue(e)
{
    //区域代码
         
	     switch (e.detectitemmapid) 
		 {
		     case "5_gjia11": //左A柱  0
		        
		         switch (Number(e.detectdefectid)) {
		             case 1:
		                 if (Yx_gj_ShowArry[0] != "喷漆且有腻子修复")
		                     Yx_gj_ShowArry[0] = '重新喷漆';
		                 $("#map5_gjia11").attr("src","local/images/skelein/skin_28.png");
		                 break;
		             case 2:
		                 Yx_gj_ShowArry[0] = '喷漆且有腻子修复';
		                 $("#map5_gjia11").attr("src", "local/images/skeleout/skout_28.png");
		                 break;
		             default:
		                 Yx_gj_ShowArry[0] = '无修复';
		                 $("#map5_gjia11").attr("src","local/images/toumingdian.png");
		                 break;
		         }

		         break;
			case "5_gjia12"://左B柱 1 区域7
			  		switch (Number(e.detectdefectid)) 
					{
						case 1:						
							if(Yx_gj_ShowArry[1]!="喷漆且有腻子修复")
							Yx_gj_ShowArry[1]= '重新喷漆';
							$("#map5_gjia12").attr("src", "local/images/skelein/skin_20.png");
							break;
						case 2:						
							Yx_gj_ShowArry[1]= '喷漆且有腻子修复';
							$("#map5_gjia12").attr("src", "local/images/skeleout/skout_20.png");
							break;
						default:
							Yx_gj_ShowArry[1]= '无修复';
							$("#map5_gjia12").attr("src", "local/images/toumingdian.png");	
						break;
					 }	
				break;
			case "5_gjia14"://左C柱 2  区域19
			
			 		switch (Number(e.detectdefectid)) 
					{
						
						case 1:						
							if(Yx_gj_ShowArry[2]!="喷漆且有腻子修复")
							Yx_gj_ShowArry[2]= '重新喷漆';
							$("#map5_gjia14").attr("src", "local/images/skelein/skin_12.png");
							break;
						case 2:						
							Yx_gj_ShowArry[2]= '喷漆且有腻子修复';
							$("#map5_gjia14").attr("src", "local/images/skeleout/skout_12.png");
							break;
						default:
							Yx_gj_ShowArry[2]= '无修复';
							$("#map5_gjia14").attr("src", "local/images/toumingdian.png");	
						break;
					 }	
				break;
			case "5_gjia03"://右A柱 3  区域15
			  	switch (Number(e.detectdefectid)) 
					{
						case 1:						
							if(Yx_gj_ShowArry[3]!="喷漆且有腻子修复")
							Yx_gj_ShowArry[3]= '重新喷漆';
							$("#map5_gjia03").attr("src", "local/images/skelein/skin_15.png");
							break;
						case 2:						
							Yx_gj_ShowArry[3]= '喷漆且有腻子修复';
							$("#map5_gjia03").attr("src", "local/images/skeleout/skout_15.png");
							break;
						default:
							Yx_gj_ShowArry[3]= '无修复';
							$("#map5_gjia03").attr("src", "local/images/toumingdian.png");	
						break;
					 }	
				break;
			case "5_gjia10"://右B柱 4  区域11
			  	switch (Number(e.detectdefectid)) 
					{
						case 1:						
							if(Yx_gj_ShowArry[4]!="喷漆且有腻子修复")
							Yx_gj_ShowArry[4]= '重新喷漆';
							$("#map5_gjia10").attr("src", "local/images/skelein/skin_10.png");
							break;
						case 2:						
							Yx_gj_ShowArry[4]= '喷漆且有腻子修复';
							$("#map5_gjia10").attr("src", "local/images/skeleout/skout_10.png");
							break;
						default:
							Yx_gj_ShowArry[4]= '无修复';
							$("#map5_gjia10").attr("src", "local/images/toumingdian.png");	
						break;
					 }	
				break;
			case "5_gjia01"://右C柱 5  区域20
				switch (Number(e.detectdefectid)) 
					{
						case 1:						
							if(Yx_gj_ShowArry[5]!="喷漆且有腻子修复")
							Yx_gj_ShowArry[5]= '重新喷漆';
							$("#map5_gjia01").attr("src", "local/images/skelein/skin_07.png");
							break;
						case 2:						
							Yx_gj_ShowArry[5]= '喷漆且有腻子修复';
							$("#map5_gjia01").attr("src", "local/images/skeleout/skout_07.png");
							break;
						default:
							Yx_gj_ShowArry[5]= '无修复';
							$("#map5_gjia01").attr("src", "local/images/toumingdian.png");	
						break;
					 }	
				break;
			case "5_gjia16"://发动机舱左 6  区域2
					switch (Number(e.detectdefectid)) 
					{
						case 1:						
							if(Yx_gj_ShowArry[6]!="喷漆且有腻子修复")
							Yx_gj_ShowArry[6]= '重新喷漆';
							$("#map5_gjia16").attr("src", "local/images/skelein/skin_31.png");
							break;
						case 2:						
							Yx_gj_ShowArry[6]= '喷漆且有腻子修复';
							$("#map5_gjia16").attr("src", "local/images/skeleout/skout_31.png");
							break;
						default:
							Yx_gj_ShowArry[6]= '无修复';
							$("#map5_gjia16").attr("src", "local/images/toumingdian.png");	
						break;
					 }	
				break;				
			case "5_gjia15"://发动机舱右 6
			
					switch (Number(e.detectdefectid)) 
					{
						case 1:						
							if(Yx_gj_ShowArry[6]!="喷漆且有腻子修复")
							Yx_gj_ShowArry[6]= '重新喷漆';
							$("#map5_gjia15").attr("src", "local/images/skelein/skin_23.png");
							break;
						case 2:						
							Yx_gj_ShowArry[6]= '喷漆且有腻子修复';
							$("#map5_gjia15").attr("src", "local/images/skeleout/skout_23.png");
							break;
						default:
							Yx_gj_ShowArry[6]= '无修复';
							$("#map5_gjia15").attr("src", "local/images/toumingdian.png");	
						break;
					 }	
				break;
			case "5_gjia02"://机盖内缘7			
					switch (Number(e.detectdefectid)) 
					{
						case 1:						
							if(Yx_gj_ShowArry[7]!="喷漆且有腻子修复")
							Yx_gj_ShowArry[7]= '重新喷漆';
							$("#map5_gjia02").attr("src", "local/images/skelein/skin_03.png");
							break;
						case 2:						
							Yx_gj_ShowArry[7]= '喷漆且有腻子修复';
							$("#map5_gjia02").attr("src","local/images/skeleout/skout_03.png");
							break;
						default:
							Yx_gj_ShowArry[7]= '无修复';
							$("#map5_gjia02").attr("src", "local/images/toumingdian.png");	
						break;
					 }	
				break;	
			case "5_gjia13"://后备箱 8
			 		switch (Number(e.detectdefectid)) 
					{
						case 1:						
							if(Yx_gj_ShowArry[8]!="喷漆且有腻子修复")
							Yx_gj_ShowArry[8]= '重新喷漆';
							$("#map5_gjia13").attr("src", "local/images/skelein/skin_13.png");
							break;
						case 2:						
							Yx_gj_ShowArry[8]= '喷漆且有腻子修复';
							$("#map5_gjia13").attr("src", "local/images/skeleout/skout_13.png");
							break;
						default:
							Yx_gj_ShowArry[8]= '无修复';
							$("#map5_gjia13").attr("src", "local/images/toumingdian.png");	
						break;
					 }	
				break;
					
			default:
				break;                   
		 }
	
}

//显示隐性损伤的表格展示
function showqimiantable()
{
	for(var j=0;j<Yx_qm_ShowArry.length;j++)
	{
		 switch (j) 
		 {
			case 0:
				if(Yx_qm_ShowArry[j]!=""){
					$("#div_quanchepenqi").css("display","");
				}else
				{
					$("#div_quanchepenqi").css("display","none");
					}
				break;
			case 1:
			   $("#lbl_yx_wg_1").html(Yx_qm_ShowArry[j]);
				break;
			case 2:
			   $("#lbl_yx_wg_2").html(Yx_qm_ShowArry[j]);
				break;
			case 3:
			   $("#lbl_yx_wg_3").html(Yx_qm_ShowArry[j]);
				break;
			case 4:
			   $("#lbl_yx_wg_4").html(Yx_qm_ShowArry[j]);
				break;
			case 5:
			   $("#lbl_yx_wg_5").html(Yx_qm_ShowArry[j]);
				break;
			case 6:
			   $("#lbl_yx_wg_6").html(Yx_qm_ShowArry[j]);
				break;
			case 7:
			   $("#lbl_yx_wg_7").html(Yx_qm_ShowArry[j]);
				break;
			case 8:
			   $("#lbl_yx_wg_8").html(Yx_qm_ShowArry[j]);
				break;
			case 9:
			   $("#lbl_yx_wg_9").html(Yx_qm_ShowArry[j]);
				break;
			case 10:
			   $("#lbl_yx_wg_10").html(Yx_qm_ShowArry[j]);
				break;
			case 11:
			   $("#lbl_yx_wg_11").html(Yx_qm_ShowArry[j]);
				break;
			default:
				break;                   
		 }
	}
}
//显示隐性损伤的漆面修复历史表格
function showgujiatable()
{
	for(var j=0;j<Yx_gj_ShowArry.length;j++)
	{
		 switch (j) 
		 {
			case 0:
				$("#lbl_yx_gj_1").html(Yx_gj_ShowArry[j]);
				break;
			case 1:
			   $("#lbl_yx_gj_2").html(Yx_gj_ShowArry[j]);
				break;
			case 2:
			   $("#lbl_yx_gj_3").html(Yx_gj_ShowArry[j]);
				break;
			case 3:
			   $("#lbl_yx_gj_4").html(Yx_gj_ShowArry[j]);
				break;
			case 4:
			   $("#lbl_yx_gj_5").html(Yx_gj_ShowArry[j]);
				break;
			case 5:
			   $("#lbl_yx_gj_6").html(Yx_gj_ShowArry[j]);
				break;
			case 6:
			   $("#lbl_yx_gj_7").html(Yx_gj_ShowArry[j]);
				break;
			case 7:
			   $("#lbl_yx_gj_8").html(Yx_gj_ShowArry[j]);
				break;
			case 8:
			   $("#lbl_yx_gj_9").html(Yx_gj_ShowArry[j]);
				break;
			default:
				break;                   
		 }
	}
}
//外观显示
//dcm  2012-7-17

function showouthertable(statictypelist,div_id,typelistdic,type) {
    var htmltable = " <table width='100%' border='0' cellpadding='0' cellspacing='0'><tr><td width='35%' class='td_o' >区域名称</td><td width='65%' class='td_o' >结果描述</td></tr>";
    var count = 0;
    for (var i = 0; i < typelistdic.length; i++) {
        if (typelistdic[i]["show_txt"].toString() != "") {
            htmltable += "<tr><td class='td_b' >" + typelistdic[i]["name"].toString() + "</td>";
			var str_type= GetOutherDetectTypeTxt(typelistdic[i]["show_txt"].toString(), statictypelist) ;
			var str_type2=str_type;
			if(str_type2.length>8){
	  			str_type2=str_type2.substr(0,8)+".."
  			}
            htmltable += "<td class='td_w' ><span title='"+str_type+"'>" +str_type2+ "</span><span class='img_chakan_flr'><img ";          
            htmltable += "	onclick='outherimg_show_detect_Area("+type+"," + typelistdic[i]["index"].toString() + ")' ";
            htmltable += " style='cursor:pointer'	src='local/images/imgbtn.png'/></span></td>";
            htmltable += "</tr>";
            count++;
      }
    }
    htmltable += "</table>";
    if (count != 0){
        $("#" + div_id).html(htmltable);
    }
    var marginLeft = $("#div_waiguan_shuoming");
    if (type == 2 && count > 10) {
        marginLeft.attr("class", "x_body_right flr");
    } if (type == 2 && count < 10) {
        marginLeft.attr("class", "x_body_right");
    }
}
function GetOutherDetectTypeTxt(datectlist, statictypelist) {
   
    if (datectlist.toString() == "") return;
    var showcontent = "";
    var typelist = datectlist.split(',');
   
    var show_txt = "";
    for (var j = 0;j < typelist.length;j++){
        if (show_txt == "") { show_txt += typelist[j]; }
        else {
            var ischongfu = false;
            var show_list = show_txt.split(',');
            for (var k = 0; k < show_list.length;k++) {
                if (show_list[k] == typelist[j]) ischongfu = true;
            }
            if (!ischongfu) show_txt += ","+typelist[j];
        }
    }
    var show_list2 = show_txt.split(',');
    var clength = show_list2.length;
    
    if(show_list2.length > 3) clength = 3;
    for (var kk = 0; kk < clength; kk++) {
        for (var i = 0; i < statictypelist.length; i++) {
            if (statictypelist[i]["key"].toString() == show_list2[kk].toString()) {
              if (showcontent != "") showcontent+=",";
              showcontent += statictypelist[i]["name"];
          }
      }
  }

    return showcontent;
 }
 //设置外观位置展示
function show_outher_obj(e){
    for (var i = 0; i < outher_dic_AreaMap.length; i++) {
        //取到区域
        if (e.detectitemid == outher_dic_AreaMap[i]["areamap"]) {
           //设置点位显示
           var liid = "#li" + e.detectitemmapid;
           if (e.detectlevel == 1) {
               $(liid).css("background", "url('/mobile/local/images/showdetect_green.png')  no-repeat center");
           }
           else if (e.detectlevel == 2) {
               $(liid).css("background", "url('/mobile/local/images/showdetect_blue.png')  no-repeat center");
           }
           else if (e.detectlevel == 3) {
               $(liid).css("background", "url('/mobile/local/images/showdetect_red.png')  no-repeat center");
           }
           else {
               $(liid).css("background", "url('/mobile/local/images/showdetect_blue.png')  no-repeat center");
           }
           $(liid).html(e.detectmark);
           $(liid).bind("mouseover", { foo: e.detectitemmapid,type:1 }, cardetect_showdetectview);
           $(liid).bind("mouseout",  cardetect_closedetectview); 
           $(liid).css("cursor", "pointer")
           
           //设置表格显示内容		   
           if (outher_dic_AreaMap[i]["show_txt"].toString() != "") outher_dic_AreaMap[i]["show_txt"] += ",";
           outher_dic_AreaMap[i]["show_txt"] += e.detectdefectid;
           if (outher_dic_AreaMap[i]["show_img"].toString() != "") outher_dic_AreaMap[i]["show_img"] += "$";
           outher_dic_AreaMap[i]["show_img"] += e.detectitemmapid+";"+e.defectpic+";"+e.detectdefectid;
           return null;
        }
    }
}
//设置内饰位置展示
function show_inner_obj(e) {
	
    for (var i = 0; i < inner_dic_AreaMap.length; i++) {
        //取到区域
        if (e.detectitemid == inner_dic_AreaMap[i]["areamap"]) {
            //设置点位显示
		
            var liid = "#li" + e.detectitemmapid;
            if (e.detectlevel == 1) {
                $(liid).css("background", "url('/mobile/local/images/showdetect_green.png')  no-repeat center");
            }
            else if (e.detectlevel == 2) {
                $(liid).css("background", "url('/mobile/local/images/showdetect_blue.png')  no-repeat center");
            }
            else if (e.detectlevel == 3) {
                $(liid).css("background", "url('/mobile/local/images/showdetect_red.png')  no-repeat center");
            }
            else {
                $(liid).css("background", "url('/mobile/local/images/showdetect_blue.png')  no-repeat center");
            }
            $(liid).html(e.detectmark);
            $(liid).bind("mouseover", { foo: e.detectitemmapid, type: 2 }, cardetect_showdetectview);
            $(liid).bind("mouseout", cardetect_closedetectview);
            $(liid).css("cursor", "pointer")
            //设置表格显示内容
            if (inner_dic_AreaMap[i]["show_txt"].toString() != "") inner_dic_AreaMap[i]["show_txt"] += ",";
            inner_dic_AreaMap[i]["show_txt"] += e.detectdefectid;
            if (inner_dic_AreaMap[i]["show_img"].toString() != "") inner_dic_AreaMap[i]["show_img"] += "$";
            inner_dic_AreaMap[i]["show_img"] += e.detectitemmapid + ";" + e.defectpic + ";" + e.detectdefectid;
            return null;
        }
    }
}
//设置骨架位置展示
function show_skeleton_obj(e) {
 for (var i = 0; i < skele_dic_AreaMap.length; i++) {
        //取到区域
        if (e.detectitemmapid == skele_dic_AreaMap[i]["areamap"]) {
            //设置点位显示		
            var liid = "#div_skele_new_" + e.detectitemmapid;
            if (e.detectlevel == 1) {
                $(liid).css("background", "url('/mobile/local/images/showdetect_green.png')  no-repeat center");
            }
            else if (e.detectlevel == 2) {
                $(liid).css("background", "url('/mobile/local/images/showdetect_blue.png')  no-repeat center");
            }
            else if (e.detectlevel == 3) {
                $(liid).css("background", "url('/mobile/local/images/showdetect_red.png')  no-repeat center");
            }
            else {
                $(liid).css("background", "url('/mobile/local/images/showdetect_blue.png')  no-repeat center");
            }
            $(liid).html(e.detectmark);
            $(liid).bind("mouseover", { foo: e.detectitemmapid, type: 3 }, cardetect_showdetectview);
            $(liid).bind("mouseout", cardetect_closedetectview);
            $(liid).css("cursor", "pointer")
			var c= Number(skele_dic_AreaMap[i]["index"]);
			//左侧区域
			if(skele_dic_AreaMap[i]["type"].toString()=="Left")
			{
				//设置表格显示内容				
				if (skele_dic_AreaMap_Left[c]["show_txt"].toString() != "") skele_dic_AreaMap_Left[c]["show_txt"] += ",";
				skele_dic_AreaMap_Left[c]["show_txt"] += e.detectdefectid;
				if (skele_dic_AreaMap_Left[c]["show_img"].toString() != "") skele_dic_AreaMap_Left[c]["show_img"] += "$";
				skele_dic_AreaMap_Left[c]["show_img"] += e.detectitemmapid + ";" + e.defectpic + ";" + e.detectdefectid;
			}else  //右侧区域
			{
				//设置表格显示内容
				if (skele_dic_AreaMap_Right[c]["show_txt"].toString() != "") skele_dic_AreaMap_Right[c]["show_txt"] += ",";
				skele_dic_AreaMap_Right[c]["show_txt"] += e.detectdefectid;
				if (skele_dic_AreaMap_Right[c]["show_img"].toString() != "") skele_dic_AreaMap_Right[c]["show_img"] += "$";
				skele_dic_AreaMap_Right[c]["show_img"] += e.detectitemmapid + ";" + e.defectpic + ";" + e.detectdefectid;
			}
            return null;
        }
    }
}
