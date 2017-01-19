var xml_http_building_link = lang[0];
var xml_http_sending = lang[1];
var xml_http_loading = lang[2];
var xml_http_load_failed = lang[3];
//var xml_http_data_in_processed = '通信成功，数据正在处理中...';

function Ajax(statusId, recvType) {
	var aj = new Object();
	if(dyhb_by_id(statusId)) {
		aj.statusId = dyhb_by_id(statusId);
	} else {
		var divElement = document.createElement("DIV");
		divElement.className = "ajaxmsg";
		divElement.id = statusId;
		document.body.appendChild(divElement);
		aj.statusId = divElement;
	}
	
	aj.targetUrl = '';
	aj.sendString = '';
	aj.recvType = recvType ? recvType : 'HTML';//HTML XML
	aj.resultHandle = null;

	aj.createXMLHttpRequest = function() {
		var request = false;
		if(window.XMLHttpRequest) {
			request = new XMLHttpRequest();
			if(request.overrideMimeType) {
				request.overrideMimeType('text/xml');
			}
		} else if(window.ActiveXObject) {
			var versions = ['Microsoft.XMLHTTP', 'MSXML.XMLHTTP', 'Microsoft.XMLHTTP', 'Msxml2.XMLHTTP.7.0', 'Msxml2.XMLHTTP.6.0', 'Msxml2.XMLHTTP.5.0', 'Msxml2.XMLHTTP.4.0', 'MSXML2.XMLHTTP.3.0', 'MSXML2.XMLHTTP'];
			for(var i=0; i<versions.length; i++) {
				try {
					request = new ActiveXObject(versions[i]);
					if(request) {
						return request;
					}
				} catch(e) {
					//alert(e.message);
				}
			}
		}
		return request;
	}

	aj.XMLHttpRequest = aj.createXMLHttpRequest();

	aj.processHandle = function() {
		aj.statusId.style.display = '';
		if(aj.XMLHttpRequest.readyState == 1) {
			aj.statusId.innerHTML = xml_http_building_link;
		} else if(aj.XMLHttpRequest.readyState == 2) {
			aj.statusId.innerHTML = xml_http_sending;
		} else if(aj.XMLHttpRequest.readyState == 3) {
			aj.statusId.innerHTML = xml_http_loading;
		} else if(aj.XMLHttpRequest.readyState == 4) {
			if(aj.XMLHttpRequest.status == 200) {
				aj.statusId.style.display = 'none';
				if(aj.recvType == 'HTML') {
					aj.resultHandle(aj.XMLHttpRequest.responseText);
				} else if(aj.recvType == 'XML') {
					aj.resultHandle(aj.XMLHttpRequest.responseXML);
				}
			} else {
				aj.statusId.innerHTML = xml_http_load_failed;
			}
		}
	}

	aj.get = function(targetUrl, resultHandle) {
		aj.targetUrl = targetUrl;
		aj.XMLHttpRequest.onreadystatechange = aj.processHandle;
		aj.resultHandle = resultHandle;
		if(window.XMLHttpRequest) {
			aj.XMLHttpRequest.open('GET', aj.targetUrl);
			aj.XMLHttpRequest.send(null);
		} else {
	        aj.XMLHttpRequest.open("GET", targetUrl, true);
	        aj.XMLHttpRequest.send();
		}
	}

	aj.post = function(targetUrl, sendString, resultHandle) {
		aj.targetUrl = targetUrl;
		aj.sendString = sendString;
		aj.XMLHttpRequest.onreadystatechange = aj.processHandle;
		aj.resultHandle = resultHandle;
		aj.XMLHttpRequest.open('POST', targetUrl);
		aj.XMLHttpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		aj.XMLHttpRequest.send(aj.sendString);
	}
	return aj;
}
function dyhb_by_id(id) {
	return document.getElementById(id);
}
function showajaxdiv(action, url, width) {
	var x = new Ajax('statusid', 'XML');
	x.get(url, function(s) {
		if(dyhb_by_id("ajax-div-"+action)) {
			var divElement = dyhb_by_id("ajax-div-"+action);
		} else {
			var divElement = document.createElement("DIV");
			divElement.id = "ajax-div-"+action;
			divElement.className = "ajaxdiv";
			document.body.appendChild(divElement);
		}
		divElement.style.cssText = "width:"+width+"px;";
		var userAgent = navigator.userAgent.toLowerCase();
		var is_opera = (userAgent.indexOf('opera') != -1);
		var clientHeight = scrollTop = 0; 
		if(is_opera) {
			clientHeight = document.body.clientHeight /2;
			scrollTop = document.body.scrollTop;
		} else {
			clientHeight = document.documentElement.clientHeight /2;
			scrollTop = document.documentElement.scrollTop;
		}
		divElement.innerHTML = s.lastChild.firstChild.nodeValue;
		divElement.style.left = (document.documentElement.clientWidth /2 +document.documentElement.scrollLeft - width/2)+"px";
		divElement.style.top = (clientHeight +　scrollTop - divElement.clientHeight/2)+"px";
	});	
}

function setCopy(content){
	if(navigator.userAgent.toLowerCase().indexOf('ie') > -1) {
		clipboardData.setData('Text',content);
		alert (lang[4]);
	} else {
		prompt(lang[5],content); 
	}
}
//约束网页中图片大小
function fiximage(thumbs_size) {
	var max = thumbs_size.split('x');
	var fixwidth = max[0];
	var fixheight = max[1];
	imgs = document.getElementsByTagName('img');
	for(i=0;i<imgs.length;i++) {
		w=imgs[i].width;h=imgs[i].height;
		if(w>fixwidth) { imgs[i].width=fixwidth;imgs[i].height=h/(w/fixwidth);}
		if(h>fixheight) { imgs[i].height=fixheight;imgs[i].width=w/(h/fixheight);}
	}
}

/*
* 功能：UBB编辑控制函数
* 参数: tag 为标签名称 val 为标签参数
* 返回：[标签名称=参数]修饰文字[/标签名称]
* [标签名称]修饰文字[/标签名称]
* [标签名称=参数][/标签名称]
*/
function ubbaction(tag, val){
var tag = tag.toUpperCase();
if(typeof(val) == "undefined"){
val = "";
}
if(val){
val = "=" + val;
}
 
var r = document.selection.createRange().text;
if(tag == "URL"){
val = prompt(lang[6], "http://");
if(val != "http://" && val != ""){
val = "=" + val;
}else{
val = "";
}
}
/*if(tag == "MAIL"){
val = prompt(lang[7], "");
if(val != ""){
val = "=" + val;
}
}*/
rr = "[" + tag + val + "]" + r + "[/" + tag +"]";
if(r){
document.selection.createRange().text = rr;
}else{
document.all.ajax_comment.value += rr;
}
}