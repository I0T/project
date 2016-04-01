﻿// dcm
//2012-7-27
//工况附件显示

//附件说明
function setcarattachment(e)
{
	 if (e.lightingstatus == -1) $("#lbl_attachment_Lighting").html("正常");
	 if (e.lightingstatus == 1) $("#lbl_attachment_Lighting").html("损坏<br />"+e.lightingcontent.toString());
	 if (e.lightingstatus == 2) $("#lbl_attachment_Lighting").html("丢失<br />"+e.lightingcontent.toString());                  
	 if (e.toolsstatus == -1) $("#lbl_attachment_Tools").html("正常");
	 if (e.toolsstatus== 1) $("#lbl_attachment_Tools").html("损坏<br />"+e.toolscontent.toString());
	 if (e.toolsstatus == 2) $("#lbl_attachment_Tools").html("丢失<br />"+e.toolscontent.toString());
	 if (e.sparetirestatus == -1) $("#lbl_attachment_SpareTire").html("正常");
	 if (e.sparetirestatus == 1) $("#lbl_attachment_SpareTire").html("损坏<br />"+e.sparetirecontent.toString());
	 if (e.sparetirestatus == 2) $("#lbl_attachment_SpareTire").html("丢失<br />"+e.sparetirecontent.toString());
	 if (e.handdoorstatus == -1) $("#lbl_attachment_HandDoor").html("正常");
	 if (e.handdoorstatus == 1) $("#lbl_attachment_HandDoor").html("损坏<br />"+e.handdoorcontent.toString());
	 if (e.handdoorstatus == 2) $("#lbl_attachment_HandDoor").html("丢失<br />"+e.handdoorcontent.toString());
	 if (e.keystatus == -1) $("#lbl_attachment_Key").html("正常");
	 if (e.keystatus == 1) $("#lbl_attachment_Key").html("损坏<br />"+e.keycontent.toString());
	 if (e.keystatus == 2) $("#lbl_attachment_Key").html("丢失<br />"+e.keycontent.toString());	 
}
//显示工况
function getStatus(status)
{
	var AttachmentArry = ["无异常","异响", "故障", "漏油"];
	var ids = status.split(',');
	var result = "";
	for (var i = 0; i < ids.length; i++)
	{
		if (result.length == 0)
			result = AttachmentArry[Number(ids[i])];
		else
			result += "," + AttachmentArry[Number(ids[i])];
	}
	return result;
}
function getStatus1(status)
{
	var AttachmentArry = ["无异常","蓝烟", "黑烟", "漏气"];
	var ids = status.split(',');
	var result = "";
	for (var i = 0; i < ids.length; i++)
	{
		if (result.length == 0)
			result = AttachmentArry[Number(ids[i])];
		else
			result += "," + AttachmentArry[Number(ids[i])];
	}
	return result;
}


function setcarcondition1(e)
{
		var content="";	
		//起动机
		if (e.startstatus.toString()!=""&&e.startstatus.toString()!="-1")
		{
			 content  = getStatus(e.startstatus.toString());
			 if (e.startcontent !="请填写异常描述"&&e.startcontent.toString()!="")				
				$("#lbl_gk_1").html(content + "<br />" + e.startcontent.toString());
				else  $("#lbl_gk_1").html(content );
		}
		//发动机
		
		if (e.launchstatus.toString()!=""&&e.launchstatus.toString()!="-1")
		{		
			 content = getStatus(e.launchstatus.toString());
			 if ( e.launchcontent !="请填写异常描述"&&e.launchcontent.toString()!="")	
			 {	
			    if(e.launchcontent.toString().indexOf(";$;") != -1)
				{
				  var clist=c.split(';$;');
				  if(clist.length>1)
				  {
					  var video="";
					  if(detect_taskid!=null&&detect_taskid!=0){
					  video="<audio src='http://test.checkauto.com.cn/UserData/Images/"+clist[0]+"' controls='controls'></audio>";
					  }else
					  { video="<audio src='"+clist[0]+"' controls='controls'></audio>";}
					  $("#lbl_gk_2").html(content + "<br />" +clist[0]+video);
				  }
                  
				}else 
                $("#lbl_gk_2").html(content + "<br />" + e.launchcontent.toString());
			}
		}
		
		//变速箱
		if (e.gearboxstatus.toString()!=""&&e.gearboxstatus.toString()!="-1")
		{
			 content  = getStatus(e.gearboxstatus.toString());
			 if ( e.gearboxcontent !="请填写异常描述"&&e.gearboxcontent.toString()!="")				
				$("#lbl_gk_3").html(content + "<br />" + e.gearboxcontent.toString());
		}
		//避震器
		
		if (e.dampingstatus.toString()!=""&&e.dampingstatus.toString()!="-1")
		{
			
			 content  = getStatus(e.dampingstatus.toString());
			 if ( e.dampingcontent !="请填写异常描述"&&e.dampingcontent.toString()!="")				
				$("#lbl_gk_4").html(content + "<br />" + e.dampingcontent.toString());
		}
		//底盘
		if (e.endcarstatus.toString()!=""&&e.endcarstatus.toString()!="-1")
		{
			
			 content  = getStatus(e.endcarstatus.toString());
			 if ( e.endcarcontent !="请填写异常描述"&&e.endcarcontent.toString()!="")				
				$("#lbl_gk_5").html(content + "<br />" + e.endcarcontent.toString());
		}
	    //制动器
		if (e.brakestatus.toString()!=""&&e.brakestatus.toString()!="-1")
		{
			
			 content  = getStatus(e.brakestatus.toString());
			 if ( e.brakecontent !="请填写异常描述"&& e.brakecontent.toString()!="" )				
				$("#lbl_gk_6").html(content + "<br />" + e.brakecontent.toString());
		}
		//排气系统
		if (e.exhauststatus.toString()!=""&&e.exhauststatus.toString()!="-1")
		{
			
			 content  = getStatus1(e.exhauststatus.toString());
			 if ( e.exhaustcontent !="请填写异常描述"&&e.exhaustcontent.toString()!="")				
				$("#lbl_gk_7").html(content + "<br />" + e.exhaustcontent.toString());
		}
		//电器系统			
		if (e.electricalstatus.toString()!=""&&e.electricalstatus.toString()!="-1")
		{
			if (e.electricalstatus.toString()=="1")
			 content  = "异常";
			 if ( e.electricalcontent !="请填写异常描述"&&e.electricalcontent.toString()!="")				
				$("#lbl_gk_8").html(content + "<br />" + e.electricalcontent.toString());
		}
		//补充说明
		if (e.explain.toString()!="")
		{
			$("#lbl_gk_9").html( e.explain.toString());
		}
	else
		{
		 $("#lbl_gk_9").html("&nbsp;&nbsp;");
		}
		
				
}