//dcm
//2012-7-14
//行车电脑	
//显示行车电脑
function Setdiagdatainfo(e) {
	
    //是否有行车电脑数据
    if (e == null) return;
    //显示违章信息
    if (e.Lawless.toString() != "") {
        $("#spanLawless").html(e.Lawless.toString());
    }
    if (e.DiagData.toString() != ""){
        var DiagDatalist = GetNewList(e.DiagData);
        var htmltable = "<table width='100%' border='0' cellspacing='0' cellpadding='0' style='margin-bottom:0px;border-bottom:0px  '>";
        var trindex = 0;
        var zuihouhang = DiagDatalist.length % 3;
        var fujiatable = "<table width='100%'  border='0' cellspacing='0' cellpadding='0' style='margin-top:0px;border-top:0px'>";
        var lieshu = DiagDatalist.length - zuihouhang;
        for (var i = 0; i < DiagDatalist.length; i++) {
            if (i < lieshu) {
                if (trindex == 0) htmltable += "<tr>";
                var kv = DiagDatalist[i];
                var title;
                var content;
                if (DiagDatalist[i].indexOf(";S;") != -1) {
                    var pos = kv.indexOf(";S;");
                    if (kv.substring(0, pos).Trim().indexOf(",") > -1) {
                        title = kv.substring(0, pos).Trim().substring(0, kv.substring(0, pos).Trim().indexOf(","))
                        content = kv.substring(pos + 3).replace(",", "");
                    } else {
                        title = kv.substring(0, pos).Trim();
                        content = ""; ;
                    }
                }
                else {
                    var pos = kv.indexOf(",");
                    title = kv;
                    content = kv;
                }
                if (title == "" || title == "undefined") title = "&nbsp;&nbsp;&nbsp;";
                if (content == "" || content == "undefined") content = "&nbsp;&nbsp;&nbsp;";
                // alert(title + "  " + content);
                htmltable += "<td class='td_b div_diagdata_width_title'>" + title + ":</td>";
                htmltable += "<td class='td_w div_diagdata_width_content' >" + content + "</td>";
                trindex++;
                if (trindex != 0 && trindex % 3 == 0) {
                    htmltable += "</tr>";
                    trindex = 0;
                }
            }
            else {
                var kv1 = DiagDatalist[i];
                var title1;
                var content1;

                if (DiagDatalist[i].indexOf(";S;") != -1) {
                    var pos = kv1.indexOf(";S;");
                    if (kv1.substring(0, pos).Trim().indexOf(",") > -1) {
                        title1 = kv1.substring(0, pos).Trim().substring(0, kv1.substring(0, pos).Trim().indexOf(","))
                        content1 = kv1.substring(pos + 3).replace(",", "");


                    } else {
                        title1 = kv1.substring(0, pos).Trim();
                        content1 = kv1.substring(pos + 3).replace(",", "");
                    }


                }
                else {
                    var pos = kv1.indexOf(",");
                    title1 = kv1;
                    content1 = pos;
                }
                if (title1 == "" || title1 == "undefined") title1 = "&nbsp;&nbsp;&nbsp;";
                if (content1 == "" || content1 == "undefined") content1 = "&nbsp;&nbsp;&nbsp;";
                fujiatable += "<td class='td_b div_diagdata_width_title' >" + title1 + ":</td>";
                fujiatable += "<td  class='td_w div_diagdata_width_content' >" + content1 + "</td>";
            }

        }
        if(zuihouhang==2)
            fujiatable += "<td class='td_b div_diagdata_width_title' >&nbsp;&nbsp;&nbsp;</td><td class='td_w div_diagdata_width_content'>&nbsp;&nbsp;</td></table>";
        if (zuihouhang == 1)
            fujiatable += "<td class='td_b div_diagdata_width_title' >&nbsp;&nbsp;&nbsp;</td><td class='td_w div_diagdata_width_content'>&nbsp;&nbsp;</td><td class='td_b div_diagdata_width_title' >&nbsp;</td><td class='td_w div_diagdata_width_content'>&nbsp;</td></table>";
        htmltable += "</table>" + fujiatable;
        $("#div_diagdatainfo").html(htmltable);
    } else {
        $("#div_diagdatainfo").html("本车没进行 行车电脑检测");
    }

}
function  GetNewList( content) 
{
            var contentList =content.split(";ss;");        
            var resultList=new Array();
            for (var i = 0; i < contentList.length; i++)
            {
                var  result = "";
                if (contentList[i].toString().indexOf(";S;")>0)
                {
                    if (contentList[i].toString().length>4){

                        result = contentList[i];
                    }
                }
                var obj = result.Trim();
                if (/.*[\u4e00-\u9fa5]+.*$/.test(obj)) {
                    resultList.push(result.Trim());
                }               
            }
            return resultList;
}

