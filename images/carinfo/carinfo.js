// 2012-7-27
//dcm 
//车身基本信息
function setcarinfo(e) {
    if (e.ishavecard == 1) {
        $("#newcarshow").show();
        $("#newcarhide1").hide();
        $("#newcarhide2").hide();
     
        $("#lbl_ishavecard").html("未上牌");
    } else {
        $("#newcarshow").hide();
        $("#newcarhide1").show();
        $("#newcarhide2").show();
   

    }
    //车身形式
    /*
    if(e.bodywork.toString()!=""){
    switch (e.bodywork) 
    {
    case 4:
    $("#lbl_carbodywork").html("三箱/4门/普通");
    break;
    case 12:
    $("#lbl_carbodywork").html("两厢/4门/普通");
    break;
    case 19:
    $("#lbl_carbodywork").html("SUV/4门/普通");
    break;
    case 22:
    $("#lbl_carbodywork").html("MPV/4门/普通");
    break;
    case 24:
    $("#lbl_carbodywork").html("面包/微型轿车/4门/普通");
    break;	
    }	
    }*/
    var lingshitime;
    var content;
    //车辆全称
    if (e.carfullname != null && e.carfullname.toString() != "") {
        $("#lbl_carfullname").html(e.carfullname);
        $("#lbl_carfullname2").html(e.carfullname);
    }
    else $("#lbl_carfullname").html("未填写");

    if (detect_taskid != null && detect_taskid != 0)
        $("#update_title_name").html(e.carfullname);

    //发动机号码
    if (e.engineno != null && e.engineno.toString() != "") {
        $("#lbl_engineno").html(e.engineno);
        $("#lbl_engineno2").html(e.engineno);
    }
    else $("#lbl_engineno").html("未填写");

    //Vin码
    if (e.vin != null && e.vin.toString() != "") {
        $("#lbl_vin").html(e.vin);
        $("#lbl_vin2").html(e.vin);
    }
    else $("#lbl_vin").html("未填写");

    //车辆颜色
    if (e.carbodycolor != null && e.carbodycolor.toString() != "") {
        $("#lbl_carcolor").html("<span class='carinfo_ts'>" + e.carbodycolor + "</span>");
        $("#lbl_carcolor2").html("<span class='carinfo_ts'>" + e.carbodycolor + "</span>");
    }
    else $("#lbl_carcolor").html("颜色未填写");
    //是否新车
    //注册日
    $("#lbl_registerdate").html(carinfo_getdatetime(e.registerdate));

    //出厂日期
    $("#lbl_licenseyear").html(carinfo_getdatetime(e.licenseyear));
    $("#lbl_licenseyear2").html(carinfo_getdatetime(e.licenseyear));

    //环保标准
    var IsEffluentYellowArray = ["", "国3", "国4", "国5", "欧1", "欧2", "欧3", "欧4", "欧5", "国1", "国2"];
    if (e.iseffluentyellow != null && e.iseffluentyellow != "") {
        if (e.iseffluentyellow.indexOf(',') > 0) {
            var strYellowlist = e.iseffluentyellow.split(',');
            switch (strYellowlist[0]) {
                case "4":
                    content = "欧1";
                    break;
                case "5":
                    content = "欧2";
                    break;
                case "6":
                    content = "欧3";
                    break;
                case "7":
                    content = "欧4";
                    break;
                case "8":
                    content = "欧5";
                    break;
                case "1":
                    content = "国3";
                    break;
                case "2":
                    content = "国4";
                    break;
                case "3":
                    content = "国5";
                    break;
                case "9":
                    content = "国1";
                    break;
                case "10":
                    content = "国2";
                    break;
                default:
                    break;
            }
            if (strYellowlist.length > 1)
            { if (strYellowlist[1] == "1") content += ", OBD"; }
        }
        else
            content = IsEffluentYellowArray[Number(e.iseffluentyellow)];
        $("#lbl_iseffluentyellow").html(content);
        $("#lbl_iseffluentyellow2").html(content);
    }

    //车辆所在地
    if (e.licensprovince != null && e.licenscity != null && e.licensprovince.toString() != "" && e.licenscity.toString() != "") {
        var address = SetCarinfoCity(e.licensprovince, e.licenscity, e.cityareaid);
        $("#lbl_licensprovince").html(address);
        $("#lbl_licensprovince2").html(address);
    } else $("#lbl_licensprovince").html("未填写");

    //车牌号码
    if (e.licensecar != null && e.licensecar.toString() != "") {
        $("#lbl_licensecar").html(e.licensecar);

    }
    else $("#lbl_licensecar").html("未填写");

    //变速箱类
    if (e.transmission != null && e.transmission.toString() != "" && e.transmission != -1) {
        switch (Number(e.transmission)) {
            case 1:
                $("#lbl_transmission").html("<span class='carinfo_ts'>手动档</span>");
                $("#lbl_transmission2").html("<span class='carinfo_ts'>手动档</span>");
                break;
            case 2:
                $("#lbl_transmission").html("<span class='carinfo_ts'>自动档</span>");
                $("#lbl_transmission2").html("<span class='carinfo_ts'>自动档</span>");
                break;
            case 3:
                $("#lbl_transmission").html("<span class='carinfo_ts'>手自一体</span>");
                $("#lbl_transmission2").html("<span class='carinfo_ts'>手自一体</span>");
                break;
            case 4:
                $("#lbl_transmission").html("<span class='carinfo_ts'>无级变速</span>");
                $("#lbl_transmission2").html("<span class='carinfo_ts'>无级变速</span>");
                break;
            case 5:
                $("#lbl_transmission").html("<span class='carinfo_ts'>双离合</span>");
                $("#lbl_transmission2").html("<span class='carinfo_ts'>双离合</span>");
                break;
        }
    } else $("#lbl_transmission").html("未填写");
    //排气量
    if (e.exhaust != null && e.exhaust.toString() != "") {
        var pailiangt = e.exhaust.toString().substring(3, 4);
        var showpailiang = e.exhaust.toString().substring(0, 3);
        if (pailiangt == "1") showpailiang += "T";
        $("#lbl_exhaust").html("<span class='carinfo_ts'>" + showpailiang + "升</span>");
        $("#lbl_exhaust2").html("<span class='carinfo_ts'>" + showpailiang + "升</span>");
    } else $("#lbl_exhaust").html("<span class='carinfo_ts'>未填写</span>");

    //表历程
    if (e.mileage != null && e.mileage.toString() != "") {
        $("#lbl_mileage").html(e.mileage + " 公里");
        $("#lbl_mileage2").html(e.mileage + " 公里");
    }
    else $("#lbl_mileage").html("<span class='carinfo_ts'>未填写</span>");

    //车辆标配
    if (e.carconfiginfo != null && e.carconfiginfo.toString() != "") {
        $("#lbl_carconfiginfo").html(e.carconfiginfo);
        $("#lbl_carconfiginfo2").html(e.carconfiginfo);
    }
    //补充配置
    if (e.carotherconfiginfo != null && e.carotherconfiginfo.toString() != "") {
        $("#lbl_Carotherconfiginfo").html(e.carotherconfiginfo);
        $("#lbl_Carotherconfiginfo2").html(e.carotherconfiginfo);
    }
    //是否一手车	
    if (e.isnew != null && e.isnew.toString() != "") {  // e.isnew=2;
        switch (Number(e.isnew)) {
            case 1:
                $("#lbl_isnew").html("一手车");
                $("#carinfo_newcar").css("display", "");
                $("#carinfo_newcar1").css("display", "");
                $("#carinfo_newcar2").css("display", "");
                $("#carinfo_newcar3").css("display", "");
                $("#carinfo_oldcar").css("display", "none");
                $("#carinfo_oldcar1").css("display", "none");
                $("#carinfo_oldcar2").css("display", "none");
                $("#carinfo_oldcar3").css("display", "none");
                //原始购车价
                if (e._orginalprice != null && e._orginalprice.toString() != "" && e._orginalprice.toString() != "-1")
                    $("#lbl_orginalprice").html(",原购车价: <span class='carinfo_ts'>" + e._orginalprice + "</span> 万元");
                break;
            case 2:
                $("#lbl_isnew").html("二手车");
                $("#carinfo_newcar").css("display", "none");
                $("#carinfo_newcar1").css("display", "none");
                $("#carinfo_newcar2").css("display", "none");
                $("#carinfo_newcar3").css("display", "none");
                $("#carinfo_oldcar").css("display", "");
                $("#carinfo_oldcar1").css("display", "");
                $("#carinfo_oldcar2").css("display", "");
                $("#carinfo_oldcar3").css("display", "");
                //过户次数
                if (e.transfercount != null && e.transfercount != "") {
                    if (e.transfercount.toString() != "11")
                        $("#lbl_transfercount").html(",过户 " + e.transfercount + " 次");
                    else $("#lbl_transfercount").html(",过户 10次以上");
                }
                //过户发票
                if (e.transferinvoice != null && e.transferinvoice.toString() != "") {
                    if (e.transferinvoice.toString() == "0")
                        $("#lbl_TransferInvoice").html("丢失");
                    if (e.transferinvoice.toString() == "1")
                        $("#lbl_TransferInvoice").html("有");
                }
                else $("#lbl_TransferInvoice").html("<span class='carinfo_ts'>未填写</span>");
                //过户日期
                if (e.transferdate != null && e.transferdate != "" && e.transferdate.toString() != "1971-01-01 00:00:00") {
                    var alist = e.transferdate.toString().split(' ');
                    if (alist.length > 1)
                        $("#lbl_transferdate").html(alist[0]);
                    else
                        $("#lbl_transferdate").html(e.transferdate.toString().substring(0, 10));
                }
                break;
        }
    } else $("#lbl_isnew").html("<span class='carinfo_ts'>未填写</span>");

    //使用性质
    if (e._carusetype != null && e._carusetype.toString() != "" && e._carusetype.toString() != "-1") {
        var shiyongxingzhi = ["非营运", "营运", "营转非", "租赁", "租赁公司非营运"];
        $("#lbl_carusetype").html(shiyongxingzhi[Number(e._carusetype)]);
    } else $("#lbl_carusetype").html("<span class='carinfo_ts'>未填写</span>");

    //交强险
    if (e.ishavefoassurance != null && e.ishavefoassurance.toString() != "") {

        switch (Number(e.ishavefoassurance)) {
            case 1:
                $("#lbl_IsHaveFoAssurance").html("有");
                //交强险到期日
                lingshitime = carinfo_getdatetime(e.foassurancedateyear);
                if (lingshitime != "未填写")
                    $("#lbl_FoAssuranceDateYear").html("," + lingshitime + "到期");
                break;
            case 0:
                $("#lbl_IsHaveFoAssurance").html("无");
                break;
            default:
                $("#lbl_IsHaveFoAssurance").html("<span class='carinfo_ts'>未填写</span>");
                break;
        }
    } else $("#lbl_IsHaveFoAssurance").html("<span class='carinfo_ts'>未填写</span>");
    //是否有保养记录
    if (e._ishaveassurancerecord != null && e._ishaveassurancerecord.toString() != "") {
        switch (Number(e._ishaveassurancerecord)) {
            case 1:
                $("#lbl_IsHaveAssuranceRecord").html("有");
                //保养公里数
                if (e.maintenancekm != null && e.maintenancekm.toString() != "") {
                    $("#lbl_MaintenanceKM").html(",最后保养 " + e.maintenancekm + " 公里");
                }
                break;
            case 0:
                $("#lbl_IsHaveAssuranceRecord").html("无");
                break;
            case -1:
                $("#lbl_IsHaveAssuranceRecord").html("未填写");
                break;
            default:
                $("#lbl_IsHaveAssuranceRecord").html("<span class='carinfo_ts'>未填写</span>");
                break;
        }
    }
    //购置税
    if (e.ishavepurchasetax != null && e.ishavepurchasetax.toString() != "" && e.ishavepurchasetax.toString() != "-1") {
        var gouzhishui = ["无", "有", "免", "丢失"]
        $("#lbl_IsHavePurchaseTax").html(gouzhishui[Number(e.ishavepurchasetax)]);
    } else $("#lbl_IsHavePurchaseTax").html("<span class='carinfo_ts'>未填写</span>")
    //车船税到期日
    lingshitime = carinfo_getdatetime(e.carshiptaxexpireyear);
    if (lingshitime != "未填写") lingshitime = lingshitime.toString().substring(0, 7) + "到期";
    $("#lbl_CarShipTaxExpireYear").html(lingshitime);

    //年审状况
    lingshitime = carinfo_getdatetime(e.detectionyear);
    if (lingshitime != "未填写") lingshitime = lingshitime.toString().substring(0, 7) + "到期";
    $("#lbl_DetectionYear").html(lingshitime);


    //是否有商业险
    if (e.ishavecomassurance != null && e.ishavecomassurance.toString() != "") {
        switch (Number(e.ishavecomassurance)) {
            case 1:
                //商业险金额
                if (e.comassurancemoney != null && e.comassurancemoney.toString() != "")
                    $("#lbl_ComAssuranceMoney").html(e.comassurancemoney + " 元");
                //商业险到期日
                lingshitime = carinfo_getdatetime(e.ishavecomassuranceyear);
                if (lingshitime != "未填写") lingshitime = lingshitime.toString().substring(0, 7) + "到期";
                //else lingshitime="到期日"+lingshitime;
                $("#lbl_IsHaveComAssuranceYear").html("," + lingshitime);
                break;
            case 0:
                $("#lbl_IsHaveComAssurance").html("无");
                break;
            default:
                $("#lbl_IsHaveComAssurance").html("<span class='carinfo_ts'>未填写</span>");
                break;
        }
    } else $("#lbl_ComAssuranceMoney").html("<span class='carinfo_ts'>未填写</span>");

    //所有人
    if (e.owner != null && e.owner.toString() != "" && e.owner.toString() != "-1") {
        if (e.owner == 1)
            $("#lbl_Owner").html("公户");
        if (e.owner == 0)
            $("#lbl_Owner").html("私户");
    } else $("#lbl_Owner").html("<span class='carinfo_ts'>未填写</span>");
    //车架号:
    if (e.iscompleteenginenumber.toString() != "") {
        var chejiahao = ["原车号清晰", "后打已变更", "锈蚀不清", "损毁变形", "重打未变更"];
        $("#lbl_IsCompleteEngineNumber").html("," + chejiahao[Number(e.iscompleteenginenumber)]);
        $("#lbl_IsCompleteEngineNumber2").html("," + chejiahao[Number(e.iscompleteenginenumber)]);
    } else $("#lbl_IsCompleteEngineNumber").html(",<span class='carinfo_ts'>未填写</span>");
    //发动机号
    if (e.ischangeenginenumber != null && e.ischangeenginenumber.toString() != "" && e.ischangeenginenumber != -1) {
        var fadongjihao = ["原车号清晰", "后打已变更", "锈蚀不清", "损毁变形", "重打未变更", "在不可见位置"];
        $("#lbl_IsChangeEngineNumber").html("," + fadongjihao[Number(e.ischangeenginenumber)]);
        $("#lbl_IsChangeEngineNumber2").html("," + fadongjihao[Number(e.ischangeenginenumber)]);
    }
    //登记证

    if (e.registration != null && e.registration.toString() != "" && e.registration.toString() != "-1") {

        var registrationlist = ["有", "丢失", "未申办"];
        switch (Number(e.registration)) {
            case 0:
                $("#lbl_Registration").html("有");
                break;
            case 1:
                $("#lbl_Registration").html("丢失");
                break;
            case 2:
                $("#lbl_Registration").html("未申办");
                break;
        }

    } else $("#lbl_Registration").html("<span class='carinfo_ts'>未填写</span>");
    //车钥匙 套
    if (e.carkeys != null && e.carkeys.toString() != "" && e.carkeys.toString() != "-1") {
        $("#lbl_CarKeys").html(e.carkeys + "套");
        $("#lbl_CarKeys2").html(e.carkeys + "套");
    } else $("#lbl_CarKeys").html("<span class='carinfo_ts'>未填写</span>");
    //说明书
    if (e.explainbook != null && e.explainbook.toString() != "") {
        if (e.explainbook == 0) {
            $("#lbl_ExplainBook").html("有");
            $("#lbl_ExplainBook2").html("有");
        }
        if (e.explainbook == 1) {
            $("#lbl_ExplainBook").html("丢失");
            $("#lbl_ExplainBook2").html("丢失");
        }
        if (e.explainbook == -1) {
            $("#lbl_ExplainBook").html("<span class='carinfo_ts'>未填写</span>");
            $("#lbl_ExplainBook2").html("<span class='carinfo_ts'>未填写</span>");
        }
    } else $("#lbl_ExplainBook").html("<span class='carinfo_ts'>未填写</span>");
    //行驶本	
    if (e.drivingcertification != null && e.drivingcertification.toString() != "") {
        if (e.drivingcertification == 0)
            $("#lbl_DrivingCertification").html("有");
        if (e.drivingcertification == 1)
            $("#lbl_DrivingCertification").html("丢失");
        if (e.drivingcertification == -1)
            $("#lbl_DrivingCertification").html("<span class='carinfo_ts'>未填写</span>");
    } else $("#lbl_DrivingCertification").html("<span class='carinfo_ts'>未填写</span>");
    //车辆牌证
    if (e.licensecarstatue != null && e.licensecarstatue.toString() != "") {
        if (e.licensecarstatue == 0) {
            $("#lbl_LicenseCarStatue").html("，牌证齐全");

        }
        if (e.licensecarstatue == 1) {
            $("#lbl_LicenseCarStatue").html("，牌证丢失");

        }
    }

    //车辆原色
    if (e.caroriginalcolor != null && e.caroriginalcolor.toString() != "") {
        $("#lbl_CarOriginalColor").html(e.caroriginalcolor + ",颜色变更");
    } else {
        $("#lbl_CarOriginalColor").html(e.carbodycolor + ",颜色未变更");
    }
    //燃油类型
    if (e.fueltype != null && e.fueltype.toString() != "" && e.fueltype.toString() != "-1") {
        var yanroulist = ["汽油", "柴油", "混合", "电池"]
        $("#lbl_FuelType").html(yanroulist[Number(e.fueltype)]);
        $("#lbl_FuelType2").html(yanroulist[Number(e.fueltype)]);
    } else $("#lbl_FuelType").html("<span class='carinfo_ts'>未填写</span>");
    //原始购车发票
    if (e.buyingreceipt != null && e.buyingreceipt.toString() != "" && e.buyingreceipt.toString() != "-1") {
        if (e.buyingreceipt == 0)
            $("#lbl_BuyingReceipt").html("有");
        if (e.buyingreceipt == 1)
            $("#lbl_BuyingReceipt").html("丢失");
        if (e.buyingreceipt == 2)
            $("#lbl_BuyingReceipt").html("有发票未验证");
    } else $("#lbl_BuyingReceipt").html("<span class='carinfo_ts'>未填写</span>");
    //外观非原厂钣金件
    if (e.outersheetmetal != null && e.outersheetmetal.toString() != "") {
        $("#lbl_OuterSheetMetal").html(e.outersheetmetal.toString());
    } else {
        $("#lbl_title_OuterSheetMetal").css("display", "none");
        $("#lbl_content_OuterSheetMetal").css("display", "none");
    }
    //外观补充说明
    if (e.detectouterinfo != null && e.detectouterinfo.toString() != "") {
        $("#lbl_DetectOuterInfo").html(e.detectouterinfo.toString());
    } else {
        $("#lbl_title_DetectOuterInfo").css("display", "none");
        $("#lbl_content_DetectOuterInfo").css("display", "none");
        // $("#div_waiguan_shuoming").hide();   
    }
    //内饰补充说明
    if (e.detectinnerinfo != null || e.chaircontent != null) {
        $("#lbl_DetectInnerInfo").html(e.detectinnerinfo.toString());
        $("#lbl_ChairContent").html(e.chaircontent.toString());
    }
    else {
        $("#tr_title_DetectInnerInfo").css("display", "none");
    }

    //可见性骨架非原厂钣金件
    if (e.skeletonsheetmetal != null || e.gasbagcontent != null) {
        $("#lbl_SkeletonSheetMetal").html(e.skeletonsheetmetal.toString());
        $("#lbl_GasbagContent").html(e.gasbagcontent.toString());
    } else {
        $("#tr_title_SkeletonSheetMetal").css("display", "none");
    }
    //可见性骨架补充说明
    if (e.detectskeletoninfo != null && e.detectskeletoninfo.toString() != "") {
        $("#lbl_DetectSkeletonInfo").html(e.detectskeletoninfo.toString());
    } else {
        $("#table_title_DetectSkeletonInfo").css("display", "none");
    }
    //隐性补充说明
    if (e.explain != null && e.explain.toString() != "") {
        $("#lbl_yinxinExplain").html(e.explain.toString());
    } else {
        $("#table_yinxin_Explain").css("display", "none");
    }
    //是否改装
    if (e.isrefit != null && e.isrefit.toString() != "") {
        if (e.isrefit == 1) {
            $("#lbl_isgaizhuang").html(e.refitcontent.toString());
        } else $("#lbl_isgaizhuang").html("未改装");
    } else {
        $("#lbl_isgaizhuang").html("未改装");
    }
    //是否带牌销售
    if (e.exchangewithlicense != null && e.exchangewithlicense.toString() != "") {
        if (e.exchangewithlicense.toString() == "2")
            $("#lbl_daipai").html(",<span class='carinfo_ts'>带牌销售<span>");

    }
    //定价信息
    if (e.modifyprice != null && e.modifyprice.toString() != "") {
        $("#spanModifyPrice").html(e.modifyprice.toString());
    } else {
        $("#spanModifyPrice").html("暂无定价信息");
    }
    //车身结构
    if (e.carbody != null && e.carbody.toString() != "") {
        $("#lbl_CarBody").html(e.carbody.toString());
    } else {
        $("#lbl_CarBody").html("<span class='carinfo_ts'>未填写</span>");
    }
    //实际行驶里程
    if (e.practicalmileage != null && e.practicalmileage.toString() != "" && e.practicalmileage.toString() != "-1") {
        $("#lbl_PracticalMileage").html(e.practicalmileage.toString() + " 公里");
    } else {
        $("#lbl_PracticalMileage").html("<span class='carinfo_ts'>未填写</span>");
    }
    //新车质保
    if (e.newcarwarranty != null && e.newcarwarranty.toString() != "" && e.newcarwarranty.toString() != "-1") {
        if (e.newcarwarranty == 0)
            $("#lbl_NewCarWarranty").html("保内");
        if (e.newcarwarranty == 1)
            $("#lbl_NewCarWarranty").html("过保");
        if (e.newcarwarranty == 2)
            $("#lbl_NewCarWarranty").html("未查证");
    } else $("#lbl_NewCarWarranty").html("<span class='carinfo_ts'>未填写</span>");
    //综合评级
    if (e.conditiongrade != null && e.conditiongrade.toString() != "" && e.conditiongrade.toString().length == 3) {
        $("#grade_novalue").hide();
        carinfo_setconditongrade(e.conditiongrade.toString());
    }
    else {
        $("#grade_hasvalue").hide();
        $("#grade_novalue").show();
    }
    //现场拍carID
    if (e.xcpcarid != null && e.xcpcarid != -1) {
        $("#xcpReport").css("display", "");
    } else {
        $("#xcpReport").css("display", "none");
    }
    //车位号
    if (e.parkingnumber != null && e.parkingnumber.toString() != "") {
        $("#lbl_parkingnumber").html(e.parkingnumber.toString());
    } else {
        $("#lbl_parkingnumber").html("未填写");
    }
    //动态检测
    if (e.dynamicdetection != null && e.dynamicdetection.toString() != "") {
        $("#lbl_dynamicdetection").html(e.dynamicdetection.toString());
    } else {
        $("#lbl_dynamicdetection").html("未填写");
    }
    //整备费
    if (e.fee != null && e.fee.toString() != "") {
        $("#lbl_fee").html(e.fee.toString());
    } else {
        $("#lbl_fee").html("未填写");
    }
    //保留价
    if (e.dealerprice != null && e.dealerprice.toString() != "") {
        $("#lbl_dealer_price").html(e.dealerprice.toString());
    } else {
        $("#lbl_dealer_price").html("未填写");
    }
    //过户类型
    if (e.transfertype != null && e.transfertype.toString() != "") {
        $("#lbl_transfer_type").html(e.transfertype.toString());
    } else {
        $("#lbl_transfer_type").html("未填写");
    }
    //违章归属
    if (e.lawlessblame != null && e.lawlessblame.toString() != "") {
        $("#lbl_law_less_blame").html(e.lawlessblame.toString());
    } else {
        $("#lbl_law_less_blame").html("未填写");
    }
    //改装归属方
    if (e.modifiedvestingparty != null && e.modifiedvestingparty.toString() != "") {
        $("#lbl_modified_vesting_party").html(e.modifiedvestingparty.toString());
    } else {
        $("#lbl_modified_vesting_party").html("未填写");
    }
    //已拿到手续
    if (e.formalitiesget != null && e.formalitiesget.toString() != "") {
        $("#lbl_formalities_get").html(e.formalitiesget.toString());
    } else {
        $("#lbl_formalities_get").html("未填写");
    }
    //已丢失需补办手续
    if (e.formalitieslost != null && e.formalitieslost.toString() != "") {
        $("#lbl_formalities_lost").html(e.formalitieslost.toString());
    } else {
        $("#lbl_formalities_lost").html("未填写");
    }
    //成交后提供手续
    if (e.formalitiestransaction != null && e.formalitiestransaction.toString() != "") {
        $("#lbl_formalities_transaction").html(e.formalitiestransaction.toString());
    } else {
        $("#lbl_formalities_transaction").html("未填写");
    }
    //补办手续归属方
    if (e.formalitiesretroactive != null && e.formalitiesretroactive.toString() != "") {
        $("#lbl_formalities_retroactive").html(e.formalitiesretroactive.toString());
    } else {
        $("#lbl_formalities_retroactive").html("未填写");
    }
}

function carinfo_setconditongrade(conditongrade) {
    var grade = conditongrade.split("|");
    if (grade[0] == 0) {
        $("#img_conditionGrade").attr("src", "/Mobile/local/images/pingji_a.png");
    }
    else if (grade[0] == 1) {
        $("#img_conditionGrade").attr("src", "/Mobile/local/images/pingji_b.png");
    }
    else if (grade[0] == 2) {
        $("#img_conditionGrade").attr("src", "/Mobile/local/images/pingji_c.png");
    }
    else if (grade[0] == 3) {
        $("#img_conditionGrade").attr("src", "/Mobile/local/images/pingji_d.png");
    }
    else if (grade[0] == 4) {
        $("#grade_hasvalue").hide();
        $("#grade_novalue").show();
        $("#grade_novalue").html("需看车");
    }
    if (grade[1] == 0) {
        $("#img_fixGrade").attr("src", "/Mobile/local/images/chengben_a.png");
    }
    else if (grade[1] == 1) {
        $("#img_fixGrade").attr("src", "/Mobile/local/images/chengben_b.png");
    }
    else if (grade[1] == 2) {
        $("#img_fixGrade").attr("src", "/Mobile/local/images/chengben_c.png");
    }
    else if (grade[1] == 3) {
        $("#img_fixGrade").attr("src", "/Mobile/local/images/chengben_d.png");
    }
    else if (grade[1] == 4) {
        $("#grade_hasvalue").hide();
        $("#grade_novalue").show();
        $("#grade_novalue").html("需看车");
    }
}

function carinfo_getdatetime(time) {
    //alert(time)
    if (time == null || time.toString() == "") return "未填写";
    ///-/g, "/"期  //var data=e.registerdate.toString().substring(0,9);//alert(data.replace("/","-"))

    if (detect_taskid != null && detect_taskid != 0) {
        if (time.toString() != "1971-01-01 00:00:00") {
            return time.toString().substring(0, 10);
        } else
            return "未填写";
    } else {
        if (time.toString() != "1971-01-01 00:00:00") {
            return time.toString().substring(0, 10);
        } else return "未填写";
    }
}
function SetCarinfoCity(p, c, q) {
    var content = "";
    for (var i = 0; i < JsonProvince.length; i++) {
        if (JsonProvince[i].id == p) {
            content = JsonProvince[i].name;

        }
    }
    if (content != "北京") {
        content += "省，";
        for (var i = 0; i < JsonCity.length; i++) {
            if (JsonCity[i].id == c) {
                content += JsonCity[i].name;
            }
        }
        content += "市";
    } else {

        content += "市，";
        for (var i = 0; i < JsonCityArea.length; i++) {
            if (JsonCityArea[i].id == q) {
                content += JsonCityArea[i].name;
            }
        }
        //content+="区";
    }
    return content;
}
