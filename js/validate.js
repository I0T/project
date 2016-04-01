/*
class="inp inpInput validate" validateContent='{"ValidateId":"VendorFullName","DisplayId":"VendorFullName","ValidateTypes":"1,2","CompareId":"",
"Content":[{"ErrorMsg":"会员全称不能为空"},{"ErrorMsg":"会员名称最大长度100个字符","MaxLength":100}]}'

*/

//字段验证
var vendorValidate = function(formInput) {
	var msg = "";

	if ($(formInput).attr("validate") != undefined && $(formInput).val().trim().len() == 0) {//为空
		msg = $(formInput).attr("validate");
	} else if ($(formInput).attr("validateNumMsg") != undefined && $(formInput).val().trim().len() != 0 && (!isInteger($(formInput).val().trim()) || (isInteger($(formInput).val().trim()) && eval($(formInput).val()) < 0) ))// 数字
	{
		msg = $(formInput).attr("validateNumMsg");
	} else if ($(formInput).attr("validateLen") != undefined && eval($(formInput).val().trim().len()) > eval($(formInput).attr("validateLen"))) {//长度
		msg = $(formInput).attr("validateLenMsg");
	}
	return msg;

};
//
var showValidateTip = function(formInput) {
	if ($(formInput).attr("validateContent") != undefined) {
		var validateContent = jQuery.parseJSON($(formInput).attr("validateContent"))
		//  alert(validateContent.ValidateId);
		var result = ValidateInput(validateContent);
		validateAllSucceed = result.IsSucceed;
		if (validateAllSucceed == true) {
			$("#" + validateContent.DisplayId).siblings("span.tip").html("<span class='corTip  numIco'></span>");
		} else {
			$("#" + validateContent.DisplayId).siblings("span.tip").html("<span class='errTip  numIco'>" + result.Msg + "</span>");
		}
		return result.IsSucceed;
	}
  return true;
};
// from 验证
var validateForm = function(fromid) {
	var result=true;
	$("#"+fromid+ " .validate:visible").each(function(item) {//所有显示 验证
		if( showValidateTip(this)==false)
		result=false;
	});
	return result;
};

var ValidateInput = function(validateContent) {
	var result = {
		"IsSucceed" : true,
		"Msg" : ""
	}
	if (validateContent.ValidateTypes != undefined) {
		var validateTypes = validateContent.ValidateTypes.split(",");
		var validatContents = validateContent.Content;
		var validateId = validateContent.ValidateId;
		var validateValue = $("#" + validateId).val();
		validateValue=$.trim(validateValue);
		for ( i = 0; i < validateTypes.length; i++) {
			switch (eval(validateTypes[i])) {
				case 1:
					//非空验证
					result.IsSucceed = IsEmpty(validateValue, validatContents[i].ExceptValue);
					result.Msg = validatContents[i].ErrorMsg;
					break;
				case 2:
					//最大长度验证
					result.IsSucceed = CompareStrLength(validateValue, eval(validatContents[i].MaxLength));
					result.Msg = validatContents[i].ErrorMsg;
					break;
				case 22:
					//最小长度验证
					
					result.IsSucceed =! CompareStrLength(validateValue,eval(validatContents[i].MinLength));
					result.Msg = validatContents[i].ErrorMsg;
					break;
				case 3:
					//是否数字
					result.IsSucceed = IsNum(validateValue);
					result.Msg = validatContents[i].ErrorMsg;
					break;
				case 4:
					//是否日期格式
					result.IsSucceed = IsDate(validateValue);
					result.Msg = validatContents[i].ErrorMsg;
					break;
				case 5:
					//时间比较
					result.IsSucceed = CompareDate($("#" + validatContents[i].StartDateID).val(), $("#" + validatContents[i].EndDateID).val());
					result.Msg = validatContents[i].ErrorMsg;
					break;
				case 6:
					//身份证验证
					result.IsSucceed = checkCard(validateValue)[0];
					result.Msg = validatContents[i].ErrorMsg;
					break;
				case 7:
					//座机
					result.IsSucceed = isTel(validateValue);
					result.Msg = validatContents[i].ErrorMsg;
					break;
				case 8:
					//手机
					result.IsSucceed = isMobel(validateValue);
					result.Msg = validatContents[i].ErrorMsg;
					break;
				case 9:
					//邮件
					result.IsSucceed = IsEmail(validateValue);
					result.Msg = validatContents[i].ErrorMsg;
					break;
				case 10:
					//检查radio 必填项
					result.IsSucceed = CheckRadioIsEmpty(validateId, validatContents[i].ExceptValue);
					result.Msg = validatContents[i].ErrorMsg;
					break;
				case 11:
					//检查两个字段比较是否相同
					result.IsSucceed = $("#"+ validateContent.CompareId).val()== validateValue;
					result.Msg = validatContents[i].ErrorMsg;
					break;
				case 12:
					//特殊字符验证
					result.IsSucceed =isLegalString(validateValue);
					result.Msg = validatContents[i].ErrorMsg;
					break;
			}
			if (result.IsSucceed == false)
				break;
		}
	}

	return result;
}; 
function isInteger(str) {
	var regu = /^[-]{0,1}[0-9]{1,}$/;
	return regu.test(str);
}

function isMobel(value) {
	if (/^13\d{9}$/g.test(value) || (/^15[0-35-9]\d{8}$/g.test(value)) || (/^18[05-9]\d{8}$/g.test(value))) {
		return true;
	} else {
		return false;
	}
}

//固定电话
function isTel(value) {
	if (value == "" || value == undefined)
		return false;
	var reg = /^([0-9]|[\-])+$/g;
	return reg.test(value);
}

String.prototype.trim = function() {
	// 用正则表达式将前后空格
	// 用空字符串替代。
	return this.replace(/(^\s*)|(\s*$)/g, "");
}
//得到字符串的字符长度（一个汉字占两个字符长）
String.prototype.len = function()// 给string增加个len ()方法，计算string的字节数
{
	return this.replace(/[^\x00-\xff]/g, "xx").length;
}
//定义允许含有的字符
function isLegal(str) {
	if (str >= '0' && str <= '9')
		return true;
	if (str >= 'a' && str <= 'z')
		return true;
	if (str >= 'A' && str <= 'Z')
		return true;
	if (str == '_')
		return true;
	if (str == ' ')
		return true;
	var reg = /^[\u4e00-\u9fa5]+$/i;
	if (reg.test(str))
		return true;
	return false;
}

//检测字符串是否含有非法字符
function isAllLegal(str1) {
	if (str1 == "" || str1 == undefined)
		return true;
	for ( n = 0; n < str1.length; n++) {
		if (!isLegal(str1.charAt(n))) {
			return false;
		}
	}
	return true;
}

//字母.数字和下划线
function checkchars(value) {
	if (value == "" || value == undefined)
		return true;
	var reg = /^[a-zA-Z0-9_\-]{1,}$/;
	return value.match(reg);
}

//特殊字符窜验证
function isLegalString(str1) {
	if (str1 == "" || str1 == undefined)
		return true;
	for ( m = 0; m < str1.length; m++) {
		if (isNoLegalChar(str1.charAt(m))) {
			return false;
		}
	}
	return true;
}
//不合法字符验证
function isNoLegalChar(checkedObject) {
	var re = /<|>|'|;|&|#|"|\$|\*|\[|\]|\{|\}|\%|\`|\||\:|\,|\\|\//;
	return re.test(checkedObject);
}
//身份证验证函数
var aCity = {
	11 : "北京",
	12 : "天津",
	13 : "河北",
	14 : "山西",
	15 : "內蒙古",
	21 : "遼寧",
	22 : "吉林",
	23 : "黑龍江",
	31 : "上海",
	32 : "江蘇",
	33 : "浙江",
	34 : "安徽",
	35 : "福建",
	36 : "江西",
	37 : "山東",
	41 : "河南",
	42 : "湖北",
	43 : "湖南",
	44 : "廣東",
	45 : "廣西",
	46 : "海南",
	50 : "重慶",
	51 : "四川",
	52 : "貴州",
	53 : "雲南",
	54 : "西藏",
	61 : "陝西",
	62 : "甘肅",
	63 : "青海",
	64 : "寧夏",
	65 : "新疆",
	71 : "臺灣",
	81 : "香港",
	82 : "澳門",
	91 : "國外"
};

function checkCard(sId) {
	var iSum = 0
	var info = ""
	var result = new Array();

	if (sId.length != 15 && sId.length != 18) {
		result[0] = false;
		result[1] = "身份證號碼長度錯誤";
		return result;
	}

	if (sId.length == 15) {//15位身份證驗證
		if (isNaN(sId)) {
			result[0] = false;
			result[1] = "身份證號碼格式錯誤";
			return result;
		}
		if (aCity[parseInt(sId.substr(0, 2))] == null) {
			result[0] = false;
			result[1] = "非法地區";
			return result;
		}
		var sBirthday = "19" + sId.substr(6, 2) + "-" + Number(sId.substr(8, 2)) + "-" + Number(sId.substr(10, 2));
		var d = new Date(sBirthday.replace(/-/g, "/"));
		if (sBirthday != (d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate())) {
			result[0] = false;
			result[1] = "非法生日";

			return result;

		}
	} else {//18位身份證驗證
		if (!/^\d{17}(\d|x)$/i.test(sId)) {
			result[0] = false;
			result[1] = "非身份證號碼";
			return result;
		}
		sId = sId.replace(/x$/i, "a");
		if (aCity[parseInt(sId.substr(0, 2))] == null) {
			result[0] = false;
			result[1] = "非法地區";
			return result;
		}
		var sBirthday = sId.substr(6, 4) + "-" + Number(sId.substr(10, 2)) + "-" + Number(sId.substr(12, 2));
		var d = new Date(sBirthday.replace(/-/g, "/"));
		if (sBirthday != (d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate())) {
			result[0] = false;
			result[1] = "非法生日";

			return result;

		}
		for (var i = 17; i >= 0; i--)
			iSum += (Math.pow(2, i) % 11) * parseInt(sId.charAt(17 - i), 11);
		if (iSum % 11 != 1) {
			result[0] = false;
			result[1] = "非法證號";
			return result;

		}
	}
	result[0] = true;

	var Sex_Flag = (sId.length == 15) ? sId.substr(14, 1) : sId.substr(16, 1);
	//男性為奇數，女性為偶數
	result[1] = "合法證件\r\n\r\n證件基本信息為：" + aCity[parseInt(sId.substr(0, 2))] + "," + sBirthday + "," + (Sex_Flag % 2 ? "男" : "女");

	return result;
}

//身份证验证函数

//返回距 1970 年 1 月 1 日之间的毫秒数
function GetTime(date) {

	var arr = date.split("-");
	var time = new Date(arr[0], arr[1], arr[2]);
	return time.getTime();
}

var ValidateDate = function(startDate, endDate) {
	if (startDate == "") {
		alert("开始时间不能为空！");
		return false;
	}
	if (endDate == "") {
		alert("结束时间不能为空！");
		return false;
	}
	var start_time = GetTime(startDate);
	var end_time = GetTime(endDate);

	if (start_time > end_time) {
		alert("开始时间不能大于结束时间，请重新选择");
		return false;
	}

	return true;
};
//非空验证
var IsEmpty = function(validateValue, exceptValue) {
	if (validateValue.length == 0)
		return false;
	if (exceptValue != undefined && validateValue == exceptValue)
		return false;
	return true;
};
//检查radio 必填项
var CheckRadioIsEmpty = function(validateId, exceptValue) {
	if ($("input[name='" + validateId + "']:checked").val() == undefined)
		return false;
	if (exceptValue != undefined && $("input[name='" + validateId + "']:checked").val() == exceptValue)
		return false;
	return true;
};
//验证字符窜最大长度
var CompareStrLength = function(validateValue, maxLength) {
	if (validateValue.len() >= maxLength)
		return false;
	return true;
};
//是否数字
var IsNum = function(validateValue) {
	var regu = /^[0-9]*$/;
	return regu.test(validateValue);
};
//是否日期
var IsDate = function(validateValue) {
	var d = new Date(str)
	return !isNaN(validateValue)
};
//邮箱
var IsEmail = function(validateValue) {
	
	var regu = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((.[a-zA-Z0-9_-]{2,3}){1,2})$/;
	return regu.test(validateValue);
};
var CompareDate = function(startDate, endDate) {
	if (startDate != "" && endDate != "") {
		var start_time = GetTime(startDate);
		var end_time = GetTime(endDate);

		if (start_time > end_time) {

			return false;
		}

	}
	return true;
};
