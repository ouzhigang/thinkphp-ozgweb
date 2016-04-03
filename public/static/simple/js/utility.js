//修改日期2011-03-11

//获取URL参数
function getUrlParam(name){  
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");  
    var r = window.location.search.substr(1).match(reg);  
    if (r!=null){
        return unescape(r[2]);
    }
    else{
        return null; 
    } 
}   

//获取jquery.mln的选定值
function getMlnValue(mlnID){
    var items=document.getElementsByName(mlnID);
    for(var i=0;i<items.length;i++){
        if(items[i].name==mlnID){
            return items[i].value;
        }
    }
}

//获取随机颜色
function getRandomColor(){
    //16进制方式表示颜色0-F
	var arrHex = ["0","1","2","3","4","5","6","7","8","9","A","B","C","D","E","F"];
	var strHex = "#";
	var index;
	for(var i = 0; i < 6; i++) {
		//取得0-15之间的随机整数
		index = Math.round(Math.random() * 15);
		strHex += arrHex[index];
	}
	return strHex;
}

//获取当前请求文件名
function getCurrentFile(){
    var thisHREF = document.location.href; 
    var tmpHPage = thisHREF.split( "/" ); 
    var thisHPage = tmpHPage[ tmpHPage.length-1 ];
    thisHPage=thisHPage.substring(0,thisHPage.indexOf("?"))
    return thisHPage;
}

// 1.判断select选项中 是否存在Value="paraValue"的Item
function isSelectExistItem(objSelect, objItemValue) {
    var isExit = false;
    for (var i = 0; i < objSelect.options.length; i++) {
        if (objSelect.options[i].value == objItemValue) {
            isExit = true;
            break;
        }
    }
    return isExit;
}

// 2.向select选项中 加入一个Item          
function addItemToSelect(objSelect, objItemText, objItemValue) {
    //判断是否存在
    if (isSelectExistItem(objSelect, objItemValue)) {
        alert("该Item的Value值已经存在");
    } else {
        var varItem = new Option(objItemText, objItemValue);
        objSelect.options.add(varItem);
        //alert("成功加入");
    }
}

// 3.从select选项中 删除一个Item          
function removeItemFromSelect(objSelect, objItemValue) {
    //判断是否存在
    if (isSelectExistItem(objSelect, objItemValue)) {
        for (var i = 0; i < objSelect.options.length; i++) {
            if (objSelect.options[i].value == objItemValue) {
                objSelect.options.remove(i);
                break;
            }
        }
        //alert("成功删除");
    } else {
        alert("该select中 不存在该项");
    }
}


// 4.删除select中选中的项      
function removeSelectedItemFromSelect(objSelect) {
    var length = objSelect.options.length - 1;
    for (var i = length; i >= 0; i--) {
        if (objSelect.options[i].selected) {
            objSelect.options[i] = null;
        }
    }
}

// 5.修改select选项中 value="paraValue"的text为"paraText"          
function updateItemToSelect(objSelect, objItemText, objItemValue) {
    //判断是否存在
    if (isSelectExistItem(objSelect, objItemValue)) {
        for (var i = 0; i < objSelect.options.length; i++) {
            if (objSelect.options[i].value == objItemValue) {
                objSelect.options[i].text = objItemText;
                break;
            }
        }
        //alert("成功修改");
    } else {
        alert("该select中 不存在该项");
    }
}

// 6.设置select中text="paraText"的第一个Item为选中          
function selectItemByText(objSelect, objItemText) {
    //判断是否存在          
    var isExit = false;
    for (var i = 0; i < objSelect.options.length; i++) {
        if (objSelect.options[i].text == objItemText) {
            objSelect.options[i].selected=true;
            isExit = true;
            break;
        }
    }
    //Show出结果          
    if (isExit) {
        //alert("成功选中");
    } else {
        alert("该select中 不存在该项");
    }
}

// 7.设置select中value="paraValue"的Item为选中      
//document.all.objSelect.value = objItemValue;
// 8.得到select的当前选中项的value      
//var currSelectValue = document.all.objSelect.value;
// 9.得到select的当前选中项的text      
//var currSelectText = document.all.objSelect.options
//[document.all.objSelect.selectedIndex].text;
// 10.得到select的当前选中项的Index      
//var currSelectIndex = document.all.objSelect.selectedIndex;
// 11.清空select的项      
//document.all.objSelect.options.length = 0; 

//在输入框内按回车点击制定按钮
//例子: onkeydown="return keyClick('search');
function keyClick(btnID){
    if (event.keyCode == 13){
        event.returnValue=false;
        event.cancel = true;
        document.getElementById(btnID).click();
        return false;
    }
}

//获取元素的全局XY坐标
function getPos(oArg) {
    /*
    getPos(document.getElementById('img1')).x
    getPos(document.getElementById('img1')).y
    */
    var oPos = new Object();
    oPos.x = oArg.offsetLeft;
    oPos.y = oArg.offsetTop;
    while (oArg.tagName.toLocaleLowerCase() != "body" && oArg.tagName.toLocaleLowerCase() != "html") {
        oArg = oArg.offsetParent;
        oPos.x += oArg.offsetLeft;
        oPos.y += oArg.offsetTop;
    }
    return oPos; //返回对象
}

//等比例缩放图片
//<img src="XXXX" alt="自动缩放后的效果" onload="javascript:drawImage(this,'200','200');" />
function drawImage(ImgD, FitWidth, FitHeight) {
    var image = new Image();
    image.src = ImgD.src;
    if (image.width > 0 && image.height > 0) {
        if (image.width / image.height >= FitWidth / FitHeight) {
            if (image.width > FitWidth) {
                ImgD.width = FitWidth;
                ImgD.height = (image.height * FitWidth) / image.width;
            } else {
                ImgD.width = image.width;
                ImgD.height = image.height;
            }
        } else {
            if (image.height > FitHeight) {
                ImgD.height = FitHeight;
                ImgD.width = (image.width * FitHeight) / image.height;
            } else {
                ImgD.width = image.width;
                ImgD.height = image.height;
            }
        }
    }
}

//获取页面编码
function getPageCharset() {
    var charSet = "";
    var oType = getBrowser();
    switch (oType) {
        case "IE":
            charSet = document.charset;
            break;
        case "FIREFOX":
            charSet = document.characterSet;
            break;
        default:
            break;
    }
    return charSet;
}
//获取浏览器类型
function getBrowser() {
    var oType = null;
    if (navigator.userAgent.indexOf("MSIE") != -1) {
        oType = "IE";
    }
    else if (navigator.userAgent.indexOf("Firefox") != -1) {
        oType = "Firefox";
    }
    return oType;
}

//js日期比较(yyyy-mm-dd)
//a大于b返回1
//a小于b返回-1
//a等于b返回0
function compdate(a, b) {
    var arr = a.split("-");
    var starttime = new Date(arr[0], arr[1], arr[2]);
    var starttimes = starttime.getTime();

    var arrs = b.split("-");
    var lktime = new Date(arrs[0], arrs[1], arrs[2]);
    var lktimes = lktime.getTime();

    if (starttimes > lktimes) {
        return 1;
    }
    else if (starttimes < lktimes) {
        return -1;
    }
    else if (starttimes == lktimes) {
        return 0;
    }
    else {
        return 'exception';
    }
}

//js时间比较(yyyy-mm-dd hh:mi:ss)
//a大于b返回1
//a小于b返回-1
//a等于b返回0
function comptime(a, b) {
    //var beginTime = "2009-09-21 00:00:00";
    //var endTime = "2009-09-21 00:00:01";
    var beginTime = a;
    var endTime = b;

    //解决Firefox的问题
    if (getBrowser() == 'Firefox') {
        beginTime = strToDate(beginTime);
        beginTime = beginTime.getMonth() + "/" + beginTime.getDate() + "/" + beginTime.getFullYear() + " " + beginTime.getHours() + ":" + beginTime.getMinutes() + ":" + beginTime.getSeconds();

        endTime = strToDate(endTime);
        endTime = endTime.getMonth() + "/" + endTime.getDate() + "/" + endTime.getFullYear() + " " + endTime.getHours() + ":" + endTime.getMinutes() + ":" + endTime.getSeconds();

    }
    else {
        var beginTimes = beginTime.substring(0, 10).split('-');
        var endTimes = endTime.substring(0, 10).split('-');

        beginTime = beginTimes[1] + '-' + beginTimes[2] + '-' + beginTimes[0] + ' ' + beginTime.substring(10, 19);
        endTime = endTimes[1] + '-' + endTimes[2] + '-' + endTimes[0] + ' ' + endTime.substring(10, 19);

        //alert(beginTime + "aaa" + endTime);
        //alert(Date.parse(endTime));
        //alert(Date.parse(beginTime));		
    }

    var a = (Date.parse(endTime) - Date.parse(beginTime)) / 3600 / 1000;
    if (a < 0) {
        return 1;
    } else if (a > 0) {
        return -1;
    } else if (a == 0) {
        return 0;
    } else {
        return 'exception';
    }
}

//获取字符串的长度
function getStrLen(str) {
    var totallength = 0;
    for (var i = 0; i < str.length; i++) {
        var intCode = str.charCodeAt(i);
        if (intCode >= 0 && intCode <= 128) {
            totallength = totallength + 1; //非中文单个字符长度加 1
        }
        else {
            totallength = totallength + 2; //中文字符长度则加 2
        }
    } //end for  
    return totallength;
}

//IE和firefox通用的复制到剪贴板
function copyToClipboard(txt) {
    txt = encodeURI(txt);
    if (window.clipboardData) {
        window.clipboardData.clearData();
        window.clipboardData.setData("Text", txt);
        alert("复制成功！");
    }
    else if (navigator.userAgent.indexOf("Opera") != -1) {
        window.location = txt;
    }
    else if (window.netscape) {
        try {
            netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
        } catch (e) {
            alert("被浏览器拒绝！\n请在浏览器地址栏输入'about:config'并回车\n然后将'signed.applets.codebase_principal_support'设置为'true'");
        }
        var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);
        if (!clip)
            return;
        var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);
        if (!trans)
            return;
        trans.addDataFlavor('text/unicode');
        var str = new Object();
        var len = new Object();
        var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
        var copytext = txt;
        str.data = copytext;
        trans.setTransferData("text/unicode", str, copytext.length * 2);
        var clipid = Components.interfaces.nsIClipboard;
        if (!clip)
            return false;
        clip.setData(trans, null, clipid.kGlobalClipboard);
        alert("复制成功！");
    }
}

//日期字符转Date
//dateString:String 2008-01-01 00:00:00
function strToDate(dateString) {
    var d = dateString.split(' ');

    var date = d[0];
    var dates = date.split('-');

    var time = null;
    var times = null;
    if (d[1]) {
        time = d[1];
        times = time.split(':');
    }

    var dd = new Date();
    dd.setFullYear(dates[0]);
    dd.setMonth(dates[1]);
    dd.setDate(dates[2]);

    if (times) {
        if (times[0]) {
            dd.setHours(times[0]);
        }
        if (times[1]) {
            dd.setMinutes(times[1]);
        }
        if (times[2]) {
            dd.setSeconds(times[2]);
        }
    }

    return dd;
}

//只允许输入整数
function inputInt(objID) {
    var obj = document.getElementById(objID);
    obj.setAttribute("onkeyup", "this.value=this.value.replace(/[^\\d]/g,'')");
    obj.setAttribute("onbeforepaste", "clipboardData.setData('text',clipboardData.getData('text').replace(/[^\\d]/g,''))");
}

//只允许输入整数或小数
function inputDecimal(objID) {
    var obj = document.getElementById(objID);
    obj.setAttribute("onkeyup", "this.value=this.value.replace(/[^0-9.]/g,'')");
    obj.setAttribute("onbeforepaste", "clipboardData.setData('text',clipboardData.getData('text').replace(/[^0-9.]/g,''))");
}
