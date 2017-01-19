<?php
/**================[^_^]================**\
      ---- 因为有梦，所以有目标 ----
@----------------------------------------@
        * 文件：front.tpl.func.php
        * 说明：（前台模板简化处理程序），侧边栏，留言板等等
        * 作者：小牛哥
        * 时间：2010-05-06 20:22
        * 版本：DoYouHaoBaby-blog 概念版
        * 主页：www.doyouhaobaby.com
@----------------------------------------@
  ---- 西南民大的天空还是蛮明亮的哈 ----
\**=====copyright (c) DoYouHaoBaby=====**/

//表情处理
function _smiley_comment(){echo<<<DYHB
<div class="com_smiley"><script type="text/javascript" language="javascript">function smile(tag) {var myField;tag = ' ' + tag + ' ';if (document.getElementById('ajax_comment') && document.getElementById('ajax_comment').type == 'textarea') {myField = document.getElementById('ajax_comment');} else {return false;}if (document.selection) {myField.focus();sel = document.selection.createRange();sel.text = tag;myField.focus();}else if (myField.selectionStart || myField.selectionStart == '0') {var startPos = myField.selectionStart;var endPos = myField.selectionEnd;var cursorPos = endPos;myField.value = myField.value.substring(0, startPos)+ tag + myField.value.substring(endPos, myField.value.length);cursorPos += tag.length;myField.focus();myField.selectionStart = cursorPos;myField.selectionEnd = cursorPos;}else {myField.value += tag;myField.focus();}}</script><a href="javascript:smile('[smile]1[/smile]')"><img src="images/smiley/images/1.gif"  border="0" /></a><a href="javascript:smile('[smile]2[/smile]')"><img src="images/smiley/images/2.gif"  border="0" /></a><a href="javascript:smile('[smile]3[/smile]')"><img src="images/smiley/images/3.gif"  border="0"/></a><a href="javascript:smile('[smile]4[/smile]')"><img src="images/smiley/images/4.gif"  border="0"/></a><a href="javascript:smile('[smile]5[/smile]')"><img src="images/smiley/images/5.gif"  border="0"/></a><a href="javascript:smile('[smile]6[/smile]')"><img src="images/smiley/images/6.gif"  border="0"/></a><a href="javascript:smile('[smile]7[/smile]')"><img src="images/smiley/images/7.gif"  border="0"/></a><a href="javascript:smile('[smile]8[/smile]')"><img src="images/smiley/images/8.gif"  border="0"/></a><a href="javascript:smile('[smile]9[/smile]')"><img src="images/smiley/images/9.gif"  border="0"/></a><a href="javascript:smile('[smile]10[/smile]')"><img src="images/smiley/images/10.gif"  border="0"/></a><a href="javascript:smile('[smile]11[/smile]')"><img src="images/smiley/images/11.gif"  border="0"/></a><a href="javascript:smile('[smile]12[/smile]')"><img src="images/smiley/images/12.gif"  border="0"/></a><a href="javascript:smile('[smile]13[/smile]')"><img src="images/smiley/images/13.gif"  border="0"/></a><a href="javascript:smile('[smile]14[/smile]')"><img src="images/smiley/images/14.gif"  border="0"/></a><a href="javascript:smile('[smile]15[/smile]')"><img src="images/smiley/images/15.gif"  border="0"/></a><a href="javascript:smile('[smile]16[/smile]')"><img src="images/smiley/images/16.gif"  border="0"/></a><a href="javascript:smile('[smile]17[/smile]')"><img src="images/smiley/images/17.gif"  border="0"/></a><a href="javascript:smile('[smile]18[/smile]')"><img src="images/smiley/images/18.gif"  border="0"/></a><a href="javascript:smile('[smile]19[/smile]')"><img src="images/smiley/images/19.gif"  border="0"/></a><a href="javascript:smile('[smile]20[/smile]')"><img src="images/smiley/images/20.gif"  border="0"/></a><a href="javascript:smile('[smile]21[/smile]')"><img src="images/smiley/images/21.gif" border="0"/></a><a href="javascript:smile('[smile]22[/smile]')"><img src="images/smiley/images/22.gif"  border="0"/></a></div>
DYHB;
}
//ubb编辑器
function _ubb_comment(){global$front_content;$ubb_lang=$front_content['ubb'];echo<<<DYHB
<style type="text/css">.com_ubb{height:20px;}.com_ubb select{padding:0px;margin:0;height="10"; background:#e0f2f9;color:#4c5051;}.com_ubb_link{margin:0;padding:0;float:left;}</style><div class="com_ubb"><div class="com_ubb_link"><a href="javascript:ubbaction('u');" title="$ubb_lang[0]"><img src="images/ubb/underline.gif" border="0" /></a><a href="javascript:ubbaction('i');" title="$ubb_lang[1]"><img src="images/ubb/italic.gif" border="0" /></a><a href="javascript:ubbaction('B');" title="$ubb_lang[2]"><img src="images/ubb/bold.gif" border="0" /></a><a href="javascript:ubbaction('URL');" title="$ubb_lang[3]"><img src="images/ubb/url.gif" border="0" /></a><a href="javascript:ubbaction('IMG');" title="$ubb_lang[4]"><img src="images/ubb/insertimage.gif" border="0"/></a></div><select name='ffont' onChange="ubbaction('FONT', this.options[this.selectedIndex].value); this.value = 0;"><option value='0'>$ubb_lang[5]</option><option value="$ubb_lang[0]">$ubb_lang[9]</option><option value="$ubb_lang[6]">$ubb_lang[6]</option><option value="Arial">Arial</option><option value="Book Antiqua">Book Antiqua</option><option value="Century Gothic">Century Gothic</option><option value="Courier New">Courier New</option><option value="Georgia">Georgia</option><option value="Impact">Impact</option><option value="Tahoma">Tahoma</option><option value="Times New Roman">Times New Roman</option><option value="Verdana">Verdana</option></select><select name='fsize' onChange="ubbaction('SIZE', this.options[this.selectedIndex].value); this.value = 0;"><option value="0" selected>$ubb_lang[7]</option><option value="-2">-2</option><option value="-1">-1</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option></select><select name='fcolor' onChange="ubbaction('COLOR', this.options[this.selectedIndex].value); this.value = 0;"><option value='0'>$ubb_lang[8]</option><option value=black style="background-color:black;color:black">Black</option><option value=red style="background-color:red;color:red">Red</option><option value=yellow style="background-color:yellow;color:yellow">Yellow</option><option value=pink style="background-color:pink;color:pink">Pink</option><option value=green style="background-color:green;color:green">Green</option><option value=orange style="background-color:orange;color:orange">Orange</option><option value=purple style="background-color:purple;color:purple">Purple</option><option value=blue style="background-color:blue;color:blue">Blue</option><option value=beige style="background-cOlor:beige;color:beige">Beige</option><option value=brown style="background-color:brown;color:brown">Brown</option><option value=teal style="background-color:teal;color:teal">Teal</option><option value=navy style="background-color:navy;colOr:navy">Navy</option><option value=maroon style="background-color:maroon;color:maroon">Maroon</option><option value=limegreen style="background-color:limegreen;color:limegreen">LimeGreen</option></select><p style="clear:both;"></p></div>
DYHB;
}
//头部
function dyhb_header(){global $dyhb,$dyhb_options,$ShowLog,$BlogId,$View,$photo_id,$taotao_id,$mp3_id,$tag_infor,$ListSort,$the_year,$the_mouth,$the_author,$ShowPhoto,$ShowMp3,$front_common;$taotao_id = intval( get_args('id') );if($View=='photo'&&$photo_id){ $Comment_to_id=$photo_id;}elseif($View=='microlog'&&$taotao_id)   {$Comment_to_id=$taotao_id;}elseif($View=='mp3'&&$mp3_id){$Comment_to_id=$mp3_id;}else{$Comment_to_id=$BlogId?$BlogId:'0';}if($the_year&&$the_mouth){$the_record="{$the_year}{$front_common[0]}{$the_mouth}{$front_common[1]}";}$keyword='';if($ShowLog[tag]){foreach($ShowLog[tag] as $value){$keyword.=$value[name];}}$mp3_des=gbksubstr( strip_tags( $ShowMp3[musicword] ),'0','200' ) ;$keyword.=$ListSort[name].",".$ListSort[keyword].$ShowLog[keyword].$tag_infor[name].",".$tag_infor[keyword].$ShowPhoto[name].$ShowMp3[name].",";$description=gbksubstr($ShowLog[title],'0','200').$ListSort[name].",".$ListSort[description].$ShowLog[description].$tag_infor[name].",".$tag_infor[description].$ShowPhoto[description].$mp3_des.",";$blog_width_height=implode('x',unserialize($dyhb_options[blog_width_height]));
echo<<<DYHB
<meta http-equiv="content-type" content="text/html; charset=gbk" />
<meta name="keywords" content="{$keyword}{$dyhb_options[blog_title]},{$dyhb_options[blog_information]},{$dyhb_options[blog_keyword]}"/>
<meta name="description" content="{$description}{$dyhb_options[blog_title]},{$dyhb_options[blog_information]},{$dyhb_options[blog_description]}"/>
<title>{$ShowMp3[name]}{$ShowPhoto[name]}{$the_author[username]}{$the_record}{$ListSort[name]}{$tag_infor[name]}{$ShowLog[title]} {$dyhb_options[blog_title]}- {$dyhb_options[blog_information]} -Powered by $dyhb_options[prower_blog_name]!</title>
<base href="{$dyhb_options[blogurl]}/" />
{$dyhb_options[blog_othorhead]}
<link rel="alternate" type="application/rss+xml" title="RSS"  href="{$dyhb_options[blogurl]}/rss.php">
<link rel="stylesheet" href="{$dyhb}/style.css" type="text/css" />
<script type="text/javascript" src="images/lang/$dyhb_options[global_lang_select]/lang.js"></script>
<script type="text/javascript" src="images/js/width.js"></script>
<script type="text/javascript" src="images/js/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="images/js/jquery/jquery.form.js"></script>
<script type="text/javascript" src="images/js/templates.js"></script>
<script type="text/javascript"> window.onload=function(){fiximage('$blog_width_height');}</script>
DYHB;
if($dyhb_options['is_jquery_effects']=='1'){
echo "<script type=\"text/javascript\" src=\"images/js/jquery/jquery.lazyload.js\"></script>";
}
echo"<script type=\"text/javascript\">
$(function(){";
if($dyhb_options['is_jquery_effects']=='1'){echo<<<DYHB
$('img').animate({"opacity": 1 });
$('img').hover(function() {
$(this).stop().animate({ "opacity": .5 });
}, function() { $(this).stop().animate({ "opacity": 1 });}
);
$("img").lazyload({
effect : "fadeIn"
});
DYHB;
}
if($dyhb_options[is_outlink_check]=='1'){echo<<<DYHB
$("a[href*=://]:not(a[href^={$dyhb_options[blogurl]}],a[href^=javascript:]),a[href^=#]").click(function(){
var name=$(this).text(); var img=$("a[href*=://]:not(a[href^={$dyhb_options[blogurl]}]>img,a[href^=javascript:])>img,a[href^=#]>img").attr("src"); var url=$(this).attr("href"); var the_url='';
if(name){ the_url="&name="+name;} else{ the_url="&img="+img;}
showajaxdiv("outlink", "{$dyhb_options[blogurl]}/getxml.php?url="+url+the_url, 500); return false;
});
DYHB;
}
echo"});</script>";
if($dyhb_options['is_show_blog_cover']=='1'){echo<<<DYHB
<style type="text/css"> body { background-color: {$dyhb_options[blog_background_color]};background-image: url({$dyhb_options[blog_background_img]});background-repeat: {$dyhb_options[blog_background_img_wide]};background-attachment: {$dyhb_options[blog_background_attachment]};</style>-->
DYHB;
}
echo<<<DYHB
<script type="text/javascript">var DYHB_Comment = {reply : function (cid, coid) {var _ce = document.getElementById(cid), _cp = _ce.parentNode;var _cf = document.getElementById('respond-post-{$Comment_to_id}');var _pi = document.getElementById('comment-parent');if (null == _pi) {_pi = document.createElement('input');_pi.setAttribute('type', 'hidden'); _pi.setAttribute('name', 'parentcomment_id');_pi.setAttribute('id', 'parentcomment_id');var _form = 'form' == _cf.tagName ? _cf : _cf.getElementsByTagName('form')[0];_form.appendChild(_pi);}_pi.setAttribute('value', coid);if (null == document.getElementById('comment-form-place-holder')) {var _cfh = document.createElement('div');_cfh.setAttribute('id', 'comment-form-place-holder');_cf.parentNode.insertBefore(_cfh, _cf);}_ce.appendChild(_cf);document.getElementById('cancel-comment-reply-link').style.display = '';return false;},cancelReply : function () {var _cf = document.getElementById('respond-post-{$Comment_to_id}'), _cfh = document.getElementById('comment-form-place-holder'); var _pi = document.getElementById('parentcomment_id');if (null != _pi) {_pi.parentNode.removeChild(_pi);}if (null == _cfh) {return true;} document.getElementById('cancel-comment-reply-link').style.display = 'none';_cfh.parentNode.insertBefore(_cf, _cfh);return false;}}</script>
DYHB;
doHooks('width_header');}
//导航条
function _global_blog_pagenav(){global $sortid,$_UrlIsCategory,$Loglist_parsort,$userid,$_UrlIsAuthor,$sort_userinfo,$tag,$_UrlIsTag,$tag_infor,$record,$_UrlIsRecord,$the_year,$the_mouth,$ShowLog,$front_common;if($sortid||$_UrlIsCategory){_showlog_sorts($Loglist_parsort);}elseif($userid||$_UrlIsAuthor){echo " &raquo; ";_showlog_user($sort_userinfo); }elseif($tag||$_UrlIsTag){$tag_url=get_rewrite_tag($tag_infor);$tag_name=$tag_infor['urlname']?$tag_infor['urlname']:$tag_infor['name'];echo " &raquo; <a href=\"$tag_url\">$tag_name</a>";}elseif($record||$_UrlIsRecord){	$record_url=get_rewrite_record($the_year,$the_mouth);echo " &raquo; <a href=\"$record_url\">{$the_year}{$front_common[0]}{$the_year}{$front_common[1]}</a>";}else{ _showlog_sorts($ShowLog);}}
function _blog_pagenavbar($current,$narmal){global $dyhb_options,$PageNav,$plugin_navbar,$View,$BlogId,$Plugin,$Mp3Id,$front_header;$ModelList=unserialize($dyhb_options['modellist']);$a=array();if($View==''&&!$Plugin&&!$BlogId&&!$Mp3Id){$css_class=$current;if($dyhb_options['permalink_structure']=="default"){$url="index.php";}else{$url="index.html";}}$a[]=array('css_id'=>$css_class,'url'=>$url,'name'=>$front_header['0']);if($ModelList){foreach($ModelList as $key=>$value){$css_class=$View==$key?$current:$narmal;if($dyhb_options['permalink_structure']=="default"){$url="?action=$key";}else{$url="$key.html";}$a[]=array('css_id'=>$css_class,'url'=>$url,'name'=>$value);}}if($PageNav){foreach($PageNav as $value){$css_class=$BlogId==$value['blog_id']?$current:$narmal;if($dyhb_options['permalink_structure']=="default"||$dyhb_options['allowed_make_html']=='1'){$url="?p=$value[blog_id]";}else{$thename=$value[urlname]?$value[urlname]:$value[title];$url="page/$thename/";}$a[]=array('css_id'=>$css_class,'url'=>$url,'name'=>$value['title']);}}if($plugin_navbar){foreach($plugin_navbar as $value){$css_class=$Plugin==$value['dir']?$current:$narmal;if($dyhb_options['permalink_structure']=="default"||$dyhb_options['allowed_make_html']=='1'){$url="?plugin=$value[dir]";}else{$url="plugin/$value[dir]/";}$a[]=array('css_id'=>$css_class,'url'=>$url,'name'=>$value[name]);}}return $a;}
//编辑
function showedit($blog_id){global$front_content;if(ISLOGIN){echo"<a href=\"admin/?action=log&do=upd&id=$blog_id\">$front_content[0]</a>";}}
//日志置顶
function toplog($istop){global $dyhb_options,$front_content;$img=false;$word=false;if(substr($dyhb_options[blog_top_img_or_word],'0','4')=="http"){ $img=true; }else{ $word=true;}if($istop=='1'){ if($img||$dyhb_options[blog_top_img_or_word]==''){$the_img=$dyhb_options[blog_top_img_or_word]==''?'images/other/import.gif':$dyhb_options[blog_top_img_or_word];echo "<img src='{$the_img}' title='$front_content[1]'>";	}else{echo $dyhb_options[blog_top_img_or_word];}}}
//mobile
function mobilelog($ismobile){if($ismobile=='1'){echo this_is_mobile('','1');}}
//单个日志导航条分类
function _showlog_sort($value){global$front_content;$url=get_rewrite_sort($value['sort']);echo"<a href=\"$url\" title=\"{$front_content[2]} $value[name]\">$value[name]</a>";}
//导航条
function _showlog_sorts($value){global $_Sorts,$front_content;if($value['sort']){foreach($value['sort'] as $val){$the_sort=$_Sorts->GetThreePar($val);$url=get_rewrite_sort($the_sort['sort']);echo "> <a href=\" $url\" title=\"{$front_content[2]} $val[name]\">$val[name]</a>";}}}
//标题
function _showlog_title($value){$url=_showlog_posturl($value);echo "<a href='$url' title=\"$value[title]\">$value[title]</a>";}
//trackback
function _showlog_trackback_url(){global $dyhb_options,$ShowLog;echo $dyhb_options[blogurl]."/getxml.php?tid=".$ShowLog[blog_id];}
//author
function _showlog_user($value){global$front_content;$the_arr=array($value[user_id],$value[reallyuser]);if($value[user_id]=='-1'||$value[reallyuser]==''||$value[user_id]=='-1'){$the_arr=array( '-1','guest' );}$url=get_rewrite_author($the_arr);echo "<a href=\"{$url}\" title=\"{$front_content[3]}$value[user]\">$value[user]</a>";}
//来源
function _showlog_from($value){global$front_content;echo "<a href=\"$value[fromurl]\" title=\"{$front_content[4]} $value[fromurl]\">$value[from]</a>";}
//评论
function _showlog_comnum($value){global$front_content;$url=_showlog_posturl($value);echo "<a href=\"$url#comment\" title=\"$value[title]\">{$front_content[5]}($value[commentnum])</a>";}
//浏览
function _showlog_viewnum($value){global$front_content;$url=_showlog_posturl($value);echo "<a href=\"$url\">{$front_content[6]} ($value[viewnum])</a>";}
//引用
function _showlog_tracbacknum($value){global$front_content;$url=_showlog_posturl($value);echo "<a href=\"$url#trackback\">{$front_content[7]} ($value[trackbacknum])</a>";}
//引用通告
function _showlog_trackback(){global $Trackback;if($Trackback){foreach($Trackback as $value){echo "<li><a href=\"$value[url]\" target=\"_blank\">$value[title]</a></li>";}}}
//相关日志
function _showlog_relate(){global $RelateLog,$dyhb_options;if($RelateLog){foreach($RelateLog as $value){$url=$dyhb_options['permalink_structure']!='default'&&$dyhb_options['allowed_make_html']=='0'?"archives/$value[0]/":"?p=$value[0]";echo "<li><a href=\"$url\" title=\"$value[title]\">$value[title]</a></li>";}}}
//上一条，下一条记录
function _showlog_pre(){global $PreLog,$dyhb_options,$front_content;if($PreLog){$url=$dyhb_options['permalink_structure']!='default'&&$dyhb_options['allowed_make_html']=='0'?"archives/$PreLog[0]/":"?p=$PreLog[0]";echo "<a href=\"$url\">$PreLog[1]</a>";}else{echo $front_content[8];}}
function _showlog_next(){global $NextLog,$dyhb_options;if($NextLog){$url=$dyhb_options['permalink_structure']!='default'&&$dyhb_options['allowed_make_html']=='0'?"archives/$NextLog[0]/":"?p=$NextLog[0]";echo "<a href=\"$url\">$NextLog[1]</a>";}else{echo $front_content[9];}}
//文章列表打包
function _loglist_table(){global $_Loglist,$front_content;echo "<table cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">";if($_Loglist){foreach($_Loglist as $value){$url=_showlog_posturl($value);$urlsort=get_rewrite_sort($value['sort']);$d=date('Y-m-d H:i:s',$value[dateline]);$title=$value[ismobile]=='1'?this_is_mobile($value[title],'1'):$value[title];echo<<<DYHB
<tr><td valign="top"><a href="{$urlsort}"><img border="0" alt="$front_content[10] $value[sortname]" src="$value[logo]" style="margin:0px 2px -3px 0px" width="16" height="16"/></a><a href="$url" title="$front_content[3]:$username $front_content[11]:{$d}">$title</a></td><td valign="top" width="60"><nobr><a href="$url#comment" title="$front_content[5]">$value[commentnum]</a> |<a href="$url#trackback" target="_blank" title="$front_content[12]">$value[trackbacknum]</a> |<span title="$front_content[13]">$value[viewnum]</span></nobr></td></tr>
DYHB;
}}echo "</table>";}
//头像
function _side_blogger(){global $_sideBlogger;$r=<<<DYHB
<style type="text/css">.bloggerinfo{text-align:center;}.bloggerinfo img {border: 5px solid #fff;}</style><div class='bloggerinfo' id="sidebar_blogger"><img src='images/qq/{$_sideBlogger[bloggerphoto]}' width='{$_sideBlogger[w]}' height='{$_sideBlogger[h]}' title='{$_sideBlogger[coolname]}' align='center'><br><a href='mailto:{$_sideBlogger[email]}'>{$_sideBlogger[coolname]}</a><br><a href="?c=1">More>></a><br><span>{$_sideBlogger[description]}</span></div>
DYHB;
return $r;}
//更换模板
function _side_tpl(){global$front_content;$a=listDir2('view');$r="<div class='show_tpl'><select onchange=\"javascript:location.href=this.value;\"><option>$front_content[14]</option>";if($a){foreach($a as $value){$r.="<option value=\"?tpl={$value}\">{$value}</option>";}}$r.="</select></div>";return $r;}
/**
  * 滔滔心情（所有的颜色：既可以是red,也可是#FFF等等）
  * @param string $border 滔滔心情边框颜色
  * @param string $photosize 滔滔心情头像大小
  * @param string $linecolor 滔滔心情分割线颜色
  * @param string $pagecolor 滔滔心情分页条主颜色
  */
function _list_page_taotao($photoborder,$photosize,$linecolor,$ul_width,$ul_li_width,$top_width,$ul_li_padding){global $DB,$dyhb_options,$pagination,$pagestart,$_Cools,$taotao_id,$ShowComment,$ShowComment2,$_Comments,$front_common,$front_content;$taotao_id = intval( get_args('id') );
echo<<<DYHB
<style type="text/css">.taotao_btn {border-right: #7b9ebd 1px solid; padding-right: 2px; border-top: #7b9ebd 1px solid; padding-left: 5px; font-size: 12px; border-left: #7b9ebd 1px solid;cursor: hand; color: black; padding-top: 2px; border-bottom: #7b9ebd 1px solid;background:url('images/other/taotaobutton.gif');height:20px;width:80px;color:#ffffff;}#microlog .gravatar{ border:1px #fff solid; float:left;}#microlog .gravatar img{ border:1px $photoborder solid}#microlog .op{ float:left; height:18px;margin:6px 5px 3px;}#microlog .top{ font-size:12px; text-align:right; border-bottom:1px #F7F7F7 solid; line-height:2;width:$top_width;}#microlog .top a{ padding:0px 5px 0px 17px;}#microlog ul{ margin:5px 0px 3px 25px; width:$ul_width; line-height:1.8;padding:0px;}#microlog ul .li{ margin:20px 0px;padding:$ul_li_padding 0px;border-bottom: #F7F7F7 1px solid;list-style:none;}#microlog ul li{margin:0px 0px; padding:0;}#microlog ul li .gravatar{ margin-top:5px;}#microlog ul li .micro_content{ float:left;font-size:14px; padding:0px;margin:0; width:$ul_li_width; padding:0px 0px 0px 8px;}#microlog ul li{ clear:both; padding:0px; margin:0px;}#microlog ul li .micro_infor {margin:3px 0;vertical-align:middle}#microlog ul li .micro_infor .time{ float:left;line-height:14px;margin:0;}#microlog ul li .micro_infor .micro_reply{ float: right;font-size:12px;line-height:14px;margin:0;}#microlog .time{ font-size:12px; color:#999999; padding-left:43px}
</style>
DYHB;
echo"<div id=\"microlog\"><ul>";
if(!$taotao_id){$totalTaotaonum=$DB->getresultnum("SELECT count(taotao_id) FROM `".DB_PREFIX."taotao`");if($totalTaotaonum>0){Page($totalTaotaonum,$dyhb_options['every_taotao_num'],"?action=microlog");$TaotaoList=$DB->gettworow("select *from `".DB_PREFIX."taotao` order by `dateline` desc limit $pagestart,$dyhb_options[every_taotao_num]");$i=0;foreach($TaotaoList as $value){$b=$_Cools->GetBloggerInfo($value['user_id']);$TaotaoList[$i][dateline]=ChangeDate($value['dateline'],'Y-m-d H:i');$TaotaoList[$i][email]=gravatar($value[email]);$TaotaoList[$i][nikename]=$b[nikename]?$b[nikename]:$b[username];$TaotaoList[$i][commentnum]=get_global_comment_num('taotao_id',$value['taotao_id']);$i++;}}
if(ISLOGIN&&CheckPermission("sendmicrolog",$front_content[15],'0')){echo<<<DYHB
<script type="text/javascript" src="admin/editor/xheditor-zh-cn.min.js"></script>
<form action="?action=microlog&do=add" method="post"><textarea name="content" id="content" style="overflow-y: hidden;width:500px;height:100px;"></textarea><br><input type="submit" value="发布" class='taotao_btn'></form>
<div class="addupload"><a href="javascript:showdiv('hideupload');">($front_content[16])</a></div><br>
<div id="hideupload" style="display:none"><iframe width="500" height="300" frameborder="0" src="admin/upload.php"></iframe></div>
<script type="text/javascript"> 
$('#content').xheditor({
tools:'Source,|,Bold,Italic,Underline,Strikethrough,FontColor,BackColor,|,Emot',
skin:'default',
emots:{msn:{name:'MSN',count:40,width:22,height:22,line:8}}
});
</script><br>
DYHB;
}
if($TaotaoList){foreach($TaotaoList as $value){$value[nikename]=$value[ismobile]=='1'?this_is_mobile($value[nikename],'1'):$value[nikename];$content=$value[ismobile]=='1'?this_is_mobile(stripslashes($value[content]),'2'):stripslashes($value[content]);$del=ISLOGIN&&$value[user_id]==LOGIN_USERID?"&nbsp;&nbsp;<a href='?action=microlog&do=del&id=$value[taotao_id]&uid=$value[user_id]'>$admin_common[3]</a>":'';echo<<<DYHB
<li class="li"><div class="gravatar"><img src="$value[email]" width="{$photosize}" height="{$photosize}" /></div><p class="micro_content"><a href="?c=$value[user_id]">$value[nikename]</a><br />$content</p><div class="clear"></div><div class="micro_infor"><p class="micro_reply">{$del}<a href="?action=microlog&id=$value[taotao_id]#comment">$front_content[18]:(<span>$value[commentnum]</span>)</a></p><p class="time">$value[dateline]</p></div></li>
DYHB;
}}echo"</ul></div><div id=\"pagenav\">$pagination</a><br><br></div>";}else{$ShowTao=$DB->getonerow("select *from `".DB_PREFIX."taotao` where `taotao_id`='$taotao_id'");if(!$ShowTao) {page_not_found();}$b=$_Cools->GetBloggerInfo($ShowTao['user_id']);$ShowTao['email']=gravatar($ShowTao[email]);$date=ChangeDate($ShowTao['dateline'],'Y-m-d H:i');$name=$b[nikename]?$b[nikename]:$b[username];$name=$ShowTao[ismobile]=='1'?this_is_mobile($name,'1'):$name;$content=$ShowTao[ismobile]=='1'?this_is_mobile(stripslashes($ShowTao[content]),'2'):stripslashes($ShowTao[content]);get_global_comments('taotao_id',$taotao_id);$totalcomment=count($ShowComment);echo<<<DYHB
<li class="li"><div class="gravatar"><img src="$ShowTao[email]" width="{$photosize}" height="{$photosize}" /></div><p class="micro_content"><a href="?c=$ShowTao[user_id]">$name</a><br />$content</p><div class="clear"></div><div class="micro_infor"><p class="micro_reply">{$del}<a href="?action=microlog&id=$ShowTao[taotao_id]#comment">$front_content[18]:(<span>$totalcomment</span>)</a></p><p class="time">$date</p></div></li></ul></div>
DYHB;
}echo "<div class='clear:both;'></div>";}
//评论真实地址
function show_log_commenturl($value){global $BlogId,$newpage_c,$compage_c,$taotao_id,$page,$mp3_id,$photo_id,$View,$the_blog_url;if($BlogId){return $the_blog_url."#comment-$value[comment_id]";}elseif($taotao_id&&$View=='microlog'){return "?action=microlog&id=$taotao_id&page=$page#comment-$value[comment_id]";}elseif($photo_id&&$View=='photo'){return "?action=photo&id=$photo_id&page=$page#comment-$value[comment_id]";}elseif($mp3_id&&$View=='mp3'){return "?action=mp3&id=$mp3_id&page=$page#comment-$value[comment_id]"; }else{return "?action=guestbook&page=$compage_c#comment-$value[comment_id]";}}
//评论固定地址
function show_log_commentreply($value){if($value[url]){$result="[url=".$value[url]."]@$value[name]:[/url]";}else{$result="[b]@$value[name]:[/b]";}return trim($result);}
//引用
function show_log_blockurl($value){if($value[url]){$back="[url=".$value[url]."]$value[name][/url]";}else{$back="$value[name]";}$result= "$front_content[7]$value[name]$front_content[19]:[blockquote][b]$back:[/b]".htmlspecialchars(trim(strip_tags($value[comment])))."[/blockquote]";return trim($result);}
//滔滔心情
function _side_microlog(){global $_sideTaotao,$dyhb_options;if(empty($_sideTaotao)){$a="<li>没有心情.</li>";}else{foreach($_sideTaotao as $value){$url=$dyhb_options['permalink_structure']!='default'?"microlog.html":"?action=microlog";$content=$value[ismobile]=='1'?this_is_mobile(strip_tags($value[content]),'1'):strip_tags($value[content]);$a.="<li>$content<p>$value[dateline]</p></li>";}}$a.="<li><a href=\"$url\">more>></a></li>";return $a;}
//登录操作
function _side_login(){global $dyhb_usergroup,$dyhb_options,$DOYOUHAOBABY_VERSION,$front_common,$front_content;if($dyhb_options[is_float_div_action]=='1'){$login="<a href=\"javascript:;\" onclick=\"showajaxdiv('login', 'getxml.php?action=login', 500);\">$front_content[21]</a>";$register="<a href=\"javascript:;\" onclick=\"showajaxdiv('register', 'getxml.php?action=register', 500);\">$front_content[22]</a>";}else{$login="<a href='login.php'>$front_content[21]</a>";$register="<a href=\"register.php\">$front_content[22]</a>";}$result.=<<<DYHB
<li><b>$front_content[23]</b></li><li>$register</li><li><a href="javascript:;" onclick="showajaxdiv('quicklog', 'getxml.php?action=quicklog', 500);">$front_content[24]</a></li><li><a href="public.php">$front_content[32]</a></li><li><a href="?action=usergroup">$front_content[25]</a></li><li><b>$front_content[26]</b></li>
DYHB;
if(ISLOGIN){$u=LOGIN_USERID;$result.=<<<DYHB
<li><a href="admin/?action=log&do=add">$front_content[27]</a></li><li><a href="admin">$front_content[28]</a></li><li><a href="?c={$u}">$front_content[29]</a></li><li><a href="?action=usergroup&id={$dyhb_usergroup}">$front_content[31]</a></li><li><a href="?login_out=true">$front_common[4]</a></li>
DYHB;
}else{$result.="<li>{$login}</li>";}$result.="<li><b>$front_content[30]</b></li>";$result.="<li><a href=\"{$dyhb_options[blog_program_url]}\" title=\"{$dyhb_options[prower_blog_name]}{$DOYOUHAOBABY_VERSION}\">{$dyhb_options[prower_blog_name]}</a></li>";return $result;}
//分类
function _side_sort(){global $_sideSorts,$dyhb_options,$_globalTreeSorts,$front_content;if($dyhb_options['side_sort_tree']){if($_globalTreeSorts){foreach($_globalTreeSorts as $value){$sorticon=$value['logo']?"<img src='$value[logo]' width='16' height='16'>":"<img src='images/other/icon_sort.gif' width='16' height='16'>";$url=get_rewrite_sort($value['sort']);$result.="<li><a href=\"{$url}\">{$sorticon}$value[name]</a><a href=\"rss.php?sort_id=$value[sort_id]\" target=\"_blank\"><img src=\"images/other/icon_rss.gif\"></a></li>";if($value[child]){foreach($value[child] as $val){$sorticon=$val['logo']?"<img src='$val[logo]' width='16' height='16'>":"<img src='images/other/icon_sort.gif' width='16' height='16'>";$url=get_rewrite_sort($val['sort']);$result.="<li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"{$url}\">{$sorticon}$val[name]</a><a href=\"rss.php?sort_id=$val[sort_id]\" target=\"_blank\"><img src=\"images/other/icon_rss.gif\"/></a></li>";if($val[child]){foreach($val[child] as $val2){$sorticon=$val2['logo']?"<img src='$val2[logo]' width='16' height='16'>":"<img src='images/other/icon_sort.gif' width='16' height='16'>";$url=get_rewrite_sort($val2['sort']);$result.="<li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"{$url}\">$sorticon{$val2[name]}</a><a href=\"rss.php?sort_id=$val2[sort_id]\" target=\"_blank\"><img src=\"images/other/icon_rss.gif\"></a></li>";}}}}}}}else{if(empty($_sideSorts)){$result="<li>$front_content[33]</li>";}else{foreach($_sideSorts as $value){$sorticon=$value['logo']?"<img src='$value[logo]' width='16' height='16'>":"<img src='images/other/icon_sort.gif' width='16' height='16'>";$url=get_rewrite_sort($value['sort']);$result.="<li><a href=\"{$url}\">$sorticon{$value[name]}($value[lognum])</a><a href=\"rss.php?sort_id=$value[sort_id]\" target=\"_blank\"><img src=\"images/other/icon_rss.gif\" /></a></li>";
}}}return $result;}
//相册分类
function _side_photosort(){global $_sidePhotoSorts,$front_content;if(empty($_sidePhotoSorts)){$result="<li>$front_content[33]</li>";}else{foreach($_sidePhotoSorts as $value){$result.="<li><a href=\"?action=photo&di=$value[photosort_id]\">$value[name]($value[photonum])</a></li>";}}return $result;}
//音乐分类
function _side_mp3sort(){global $_sideMp3Sorts,$front_content;if(empty($_sideMp3Sorts)){$result="<li>$front_content[33]</li>";}else{foreach($_sideMp3Sorts as $value){$result."<li><a href=\"?action=mp3&ms=$value[mp3sort_id]\">$value[name]($value[mp3num])</a></li>";}}return $result;}
//标签
function _side_hottag(){global $HotTag,$front_content;if(empty($HotTag)){$result="$front_content[34]";}else{foreach($HotTag as $value){$url=get_rewrite_tag($value);$result.="<span style=\"font-size:$value[fontsize]pt; height:30px;\"><a href=\"{$url}\" title=\"$value[lognum] $front_content[35]\">$value[name]</a></span>";}}return $result;}
//归档
function _side_record(){global $_sideRecord;if(empty($_sideRecord)){$result="<li>now record!</li>";}else{foreach($_sideRecord as $value){$result.="<li><a href=\"$value[url]\">$value[record]($value[lognum])</a></li>";}}return $result;}
//最热日志
function _side_hotlog(){global $_sideHotlog;if(empty($_sideHotlog)){$result="<li>no logs</li>";}else{foreach($_sideHotlog as $value){$url=_showlog_posturl($value);$result.="<li><a href=\"$url\">$value[title]</a></li>";}}return $result;}
//最新音乐
function _side_newmusic(){global $_sideNewmusic;if(empty($_sideNewmusic)){$result="<li>no logs</li>";}else{foreach($_sideNewmusic as $value){$result.="<li><a href=\"?m=$value[mp3_id]\">$value[name]</a></li>";}}return $result;}
//评论排行
function _side_commentlog(){global $_sideCommentlog;if(empty($_sideCommentlog)){$result="<li>no logs</li>";}else{foreach($_sideCommentlog as $value){$url=_showlog_posturl($value);$result.="<li><a href=\"$url\">$value[title]</a></li>";}}return $result;}
//当月排行
function _side_mouthlog(){global $_sideMouthHotLog;if(empty($_sideMouthHotLog)){$result="<li>no logs</li>";}else{foreach($_sideMouthHotLog as $value){$url=_showlog_posturl($value);$result.="<li><a href=\"$url\">$value[title]</a></li>";}}return $result;}
//年度排行
function _side_yearlog(){global $_sideYearHotLog;if(empty($_sideYearHotLog)){$result="<li>no logs</li>";}else{foreach($_sideYearHotLog as $value){$url=_showlog_posturl($value);$result.="<li><a href=\"$url\">$value[title]</a></li>";}}return $result;}
//随机日志
function _side_randlog(){global $_sideRandlog;if(empty($_sideRandlog)){$result="<li>no logs</li>";}else{foreach($_sideRandlog as $value){$value[blog_id]=$value[0];$url=_showlog_posturl($value);$result.="<li><a href=\"$url\">$value[title]</a></li>";}}return $result;}
//最新日志
function _side_newlog(){global $_sideNewlog;if(empty($_sideNewlog)){$result="<li>no logs</li>";}else{foreach($_sideNewlog as $value){$url=_showlog_posturl($value);$result.="<li><a href=\"$url\">$value[title]</a></li>";}}return $result;}
//衔接
function _side_link(){global $side_TextLinks,$side_LogoLinks;if($side_TextLinks){foreach($side_TextLinks as $value){if($value['isdisplay']=='1'){$result.="<li><a href=\"$value[url]\" title=\"$value[description]\" target=\"_blank\">$value[name]</a></li>";}}}if($side_LogoLinks){foreach($side_LogoLinks as $value){if($value['isdisplay']=='1'){$result.="<li><a href=\"$value[url]\" title=\"$value[description]\" target=\"_blank\"><img src=\"$value[logo]\"></a></li>";}}}return $result;}
//最新评论
function _side_comment(){global $_sideNewcomment,$dyhb,$dyhb_options,$front_content;if($_sideNewcomment){foreach($_sideNewcomment as $value){$url=$dyhb_options['permalink_structure']!='default'&&$dyhb_options['allowed_make_html']=='0'?"archives/$value[blog_id]/":"?p={$value[blog_id]}";$email=gravatar($value[email]);$result.="<li style=\"clear:both;\"><img src=\"$email\" style=\"float:left;\" width=\"40\" height=\"40\"></img><a href=\"{$value[url]}\">{$value[name]}</a>$front_content[36]<br /> <a href=\"{$url}#comment\">{$value[comment]}</a></li>";}}return $result;}
//最新留言
function _side_guestbook(){global $_sideNewguestbook,$dyhb,$dyhb_options,$front_content;if($_sideNewguestbook){foreach($_sideNewguestbook as $value){$url=$dyhb_options['permalink_structure']!='default'&&$dyhb_options['allowed_make_html']=='0'?"guestbook.html":"?action=guestbook";$email=gravatar($value[email]);$result.="<li style=\"clear:both;\"><img src=\"$email\" style=\"float:left;\" width=\"40\" height=\"40\"></img><a href=\"{$value[url]}\">{$value[name]}</a>{$front_content[36]}<br /> <a href=\"{$url}#comment\">{$value[comment]}</a></li>";}}return $result;}
//最新照片
function _side_newphoto($width,$height,$type){$result="<embed src=\"images/swf/newimg.swf?bcastr_xml_url=newimg.php?type={$type}\" width=\"{$width}\" height=\"{$height}\" loop=\"false\" quality=\"high\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\" salign=\"T\" menu=\"false\" wmode=\"transparent\"></embed>";return $result;}
//侧边栏播放器
function _side_mp3player($color,$width,$height){global $dyhb_options;$result="<embed src=\"$dyhb_options[blogurl]/images/mp3/mp3player.swf\" quality=\"high\" pluginspage=\"http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash\" type=\"application/x-shockwave-flash\" width=\"{$width}\" height=\"{$height}\" bgcolor=\"{$color}\"></embed>";return $result;}
//全站信息
function _side_theblog(){global $Blog,$dyhb_options,$front_content;$result="<li><a href=\"index.php\">{$front_content[38]}($Blog[alllogs])</a></li><li><a href=\"?action=guestbook\">{$front_content[39]}($Blog[allcomment])</a></li><li><a href=\"?action=tag\">{$front_content[40]}($Blog[alltags])</a></li><li><a href=\"?action=trackback\">{$front_content[41]}($Blog[alltrackbacks])</a></li><li><a href=\"?action=photo\">{$front_content[42]}($Blog[allfiles])</a></li><li><a href=\"?action=user\">{$front_content[43]}($Blog[allusers])</a></li><li>{$front_content[44]}($dyhb_options[today_count_num])</li><li>{$front_content[45]}($dyhb_options[all_count_num])</li>";return $result;}
//日志列表与传统
function _narmal_list(){global $TagId,$SortId,$Key,$RecordId,$front_content;$url_narmal='';$url_list='';if($TagId){$url_narmal="?t=$TagId&way=narmal";$url_list="?t=$TagId&way=list";}elseif($SortId){$url_narmal="?s=$SortId&way=narmal";$url_list="?s=$SortId&way=list";}elseif($Key){$url_narmal="?key=$Key&way=narma";$url_list="?key=$Key&way=list";}else{$url_narmal="?way=narmal";$url_list="?way=list";}echo"<a href=\"$url_narmal\">$front_content[46]</a> | <a href=\"$url_list\">$front_content[47]</a>";}
//音乐播放器设置
function _mp3player_show($mp3path){global $dyhb_options;$s=unserialize($dyhb_options['setplayer']);if($dyhb_options['is_oneplay_default']){return "<embed src=\"$dyhb_options[blogurl]/images/mp3/mp3.swf?soundFile= $mp3path&bg=0x$s[0]&leftbg=0x$s[1]&lefticon=0x$s[2]&rightbg=0x$s[3]&rightbghover=0x$s[4]&righticon=0x$s[5]&righticonhover=0x$s[6]&text=0x$s[7]&slider=0x$s[8]&track=0x$s[9]&border=0x$s[10]&loader=0x$s[11]&autostart=$s[12]&loop=$s[13]\" quality=\"high\"pluginspage=\"http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash\"type=\"application/x-shockwave-flash\" width=\"400\" height=\"40\" bgcolor=\"#f8f5ee\"></embed>";}else{return "<embed src=\"$mp3path\"; loop=$s[13] autostart=$s[12] name=bgss width=500 height=100 type=\"audio/mpeg\">";}}
?>