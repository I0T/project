// dcm  2012-7-27
//显示车身基本图片

function loadimageajax(img, url) {
   // var Img = new Image();
    if (url.toString() == "") return;
   // Img.src = url;
   // Img.onload = function () {
    $("#" + img).attr("src", url);
   // }
}

//设置图片信息
function setcarfileinfo(e, local) {
    //return;
    if (e.length > 0) {
        for (var i = 0; i < e.length; i++) {
            if (!local)
                loadimageajax("carfileinfo_img_" + e[i].FileType, addimg(e[i].FileName));
            else
                loadimageajax("carfileinfo_img_" + e[i].FileType, e[i].FileName);
            for (var j = 0; j < CarInfor_Imglist.length; j++) {
                if (CarInfor_Imglist[j]["areamap"] == e[i].FileType)
                    CarInfor_Imglist[j]["show_txt"] = e[i].FileName;
            }

            /*	   
            switch(Number(e[i].FileType))
            {
            case 6://1	
            if (e[i].FileName.toString() != "")
            {
            if(!local)
            loadimageajax("carfileinfo_img_1",addimg(e[i].FileName));
            // $("#carfileinfo_img_1").attr("src",addimg(e[i].FileName));	
            else
            loadimageajax("carfileinfo_img_1",e[i].FileName);
            //$("#carfileinfo_img_1").attr("src",e[i].FileName);	
            CarInfor_Imglist[0]["show_txt"]=e[i].FileName;
            }
            break;
			 
            case 27://2
            if(e[i].FileName.toString()!="")  
            {
            if(!local)
            loadimageajax("carfileinfo_img_2",addimg(e[i].FileName));
            // $("#carfileinfo_img_2").attr("src",addimg(e[i].FileName));	
            else
            loadimageajax("carfileinfo_img_1",e[i].FileName));
            // $("#carfileinfo_img_2").attr("src",e[i].FileName);	
            CarInfor_Imglist[1]["show_txt"]=e[i].FileName;
            }	
            break;
            case 31://3
            if(e[i].FileName.toString()!="")  
            {
            if(!local)
            $("#carfileinfo_img_3").attr("src",addimg(e[i].FileName));	
            else
            $("#carfileinfo_img_3").attr("src",e[i].FileName);	
            CarInfor_Imglist[2]["show_txt"]=e[i].FileName;
            }	
            break;
            case 5:
            if(e[i].FileName.toString()!="")  
            {
            if(!local)
            $("#carfileinfo_img_4").attr("src",addimg(e[i].FileName));	
            else
            $("#carfileinfo_img_4").attr("src",e[i].FileName);	
            CarInfor_Imglist[3]["show_txt"]=e[i].FileName;
            }
            break;
            case 28:
            if(e[i].FileName.toString()!="")  
            {
            if(!local)
            $("#carfileinfo_img_5").attr("src",addimg(e[i].FileName));	
            else
            $("#carfileinfo_img_5").attr("src",e[i].FileName);	
            CarInfor_Imglist[4]["show_txt"]=e[i].FileName;
            }
            break;
            case 10:
            if(e[i].FileName.toString()!="")  
            {
            if(!local)
            $("#carfileinfo_img_6").attr("src",addimg(e[i].FileName));	
            else
            $("#carfileinfo_img_6").attr("src",e[i].FileName);	
            CarInfor_Imglist[5]["show_txt"]=e[i].FileName;
            }
            break;
            case 7:
            if(e[i].FileName.toString()!="")  
            {
            if(!local)
            $("#carfileinfo_img_7").attr("src",addimg(e[i].FileName));	
            else
            $("#carfileinfo_img_7").attr("src",e[i].FileName);	
            CarInfor_Imglist[6]["show_txt"]=e[i].FileName;
            }
            break;
            case 26:
            if(e[i].FileName.toString()!="")  
            {
            if(!local)
            $("#carfileinfo_img_8").attr("src",addimg(e[i].FileName));	
            else
            $("#carfileinfo_img_8").attr("src",e[i].FileName);	
            CarInfor_Imglist[7]["show_txt"]=e[i].FileName;
            }
            break;
            case 32:
            if(e[i].FileName.toString()!="")  
            {
            if(!local)
            $("#carfileinfo_img_9").attr("src",addimg(e[i].FileName));	
            else
            $("#carfileinfo_img_9").attr("src",e[i].FileName);	
            CarInfor_Imglist[8]["show_txt"]=e[i].FileName;
            }	
            break;
            case 8:
            if(e[i].FileName.toString()!="")  
            {
            if(!local)
            $("#carfileinfo_img_10").attr("src",addimg(e[i].FileName));	
            else
            $("#carfileinfo_img_10").attr("src",e[i].FileName);	
            CarInfor_Imglist[9]["show_txt"]=e[i].FileName;
            }	
            break;
            case 9:
            if(e[i].FileName.toString()!="")  
            {
            if(!local)
            $("#carfileinfo_img_11").attr("src",addimg(e[i].FileName));	
            else
            $("#carfileinfo_img_11").attr("src",e[i].FileName);	
            CarInfor_Imglist[10]["show_txt"]=e[i].FileName;
            }	
            break;
            case 33:
            if(e[i].FileName.toString()!="")  
            {
            if(!local)
            $("#carfileinfo_img_12").attr("src",addimg(e[i].FileName));	
            else
            $("#carfileinfo_img_12").attr("src",e[i].FileName);	
            CarInfor_Imglist[11]["show_txt"]=e[i].FileName;
            }	
            break;
            case 34:
            if(e[i].FileName.toString()!="")  
            {
            if(!local)
            $("#carfileinfo_img_13").attr("src",addimg(e[i].FileName));	
            else
            $("#carfileinfo_img_13").attr("src",e[i].FileName);	
            CarInfor_Imglist[12]["show_txt"]=e[i].FileName;
            }	
            break;
            case 30:
            if(e[i].FileName.toString()!="")  
            {
            if(!local)
            $("#carfileinfo_img_14").attr("src",addimg(e[i].FileName));	
            else
            $("#carfileinfo_img_14").attr("src",e[i].FileName);	
            CarInfor_Imglist[13]["show_txt"]=e[i].FileName;
            }	
            break;
            case 29:
            if(e[i].FileName.toString()!="")  
            {
            if(!local)
            $("#carfileinfo_img_15").attr("src",addimg(e[i].FileName));	
            else
            $("#carfileinfo_img_15").attr("src",e[i].FileName);	
            CarInfor_Imglist[14]["show_txt"]=e[i].FileName;
            }	
            break;
            case 16:
            if(e[i].FileName.toString()!="")  
            {
            if(!local)
            $("#carfileinfo_img_16").attr("src",addimg(e[i].FileName));	
            else
            $("#carfileinfo_img_16").attr("src",e[i].FileName);	
            CarInfor_Imglist[15]["show_txt"]=e[i].FileName;
            }	
            break;
            case 13:
            if(e[i].FileName.toString()!="")  
            {
            if(!local)
            $("#carfileinfo_img_17").attr("src",addimg(e[i].FileName));	
            else
            $("#carfileinfo_img_17").attr("src",e[i].FileName);	
            CarInfor_Imglist[16]["show_txt"]=e[i].FileName;
            }	
            break;s
            case 4:
            if(e[i].FileName.toString()!="")  
            {
            if(!local)
            $("#carfileinfo_img_18").attr("src",addimg(e[i].FileName));	
            else
            $("#carfileinfo_img_18").attr("src",e[i].FileName);	
            CarInfor_Imglist[17]["show_txt"]=e[i].FileName;
            }	
            break;		     
            }*/
        }
    }
}

function addimg(url) {
    url = "http://img1.youxinpai.com/Upload/UppUpload/CheckAuto/" + url;
    //url = "http://img3.youxinpai.com/upload/uppupload" + url;
    url = url.replace(".jpg", "_middle.jpg");
    return url;
}
function addbigimg(url) {
    //url = "http://img3.youxinpai.com/upload/uppupload" + url;
    url = "http://img1.youxinpai.com/Upload/UppUpload/CheckAuto/" + url;
    url = url.replace(".jpg", "_bigger.jpg");
    return url;
}
