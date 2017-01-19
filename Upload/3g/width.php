<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * 文件：width.php
        * 说明：手机公用函数-手机第一版
        * 作者：小牛哥
        * 时间：2010-07-22 0:17
        * 版本：DoYouHaoBaby-blog 告别大二.想念丝瓜 (特别版)
        * 主页：www.doyouhaobaby.com
		* 论坛：bbs.56swun.com
@----------------------------------------@
  ---- 西南民大的天空还是蛮明亮的哈 ----
\**=====copyright (c) DoYouHaoBaby=====**/

/** 系统消息 */
function wap_mes($msg,$link){
	if($link!='0'){
	    $link=$link?$link:'index.php';
	    $back="<div><a href=\"$link\">前往</a></div>";
	}
    echo "<div id=\"m\">";
    echo "<div>$msg</div>$back</div>";
	wap_footer();
	exit();
}

/** 头部 */
function wap_header() {
	global $dyhb_options,$View;
	ob_start();
	$css_i=$View==''?"id=\"active\"":'';
	$paginavbar=array('sort'=>"分类",'record'=>"归档",'tag'=>"标签",'comment'=>"评论",'taotao'=>"心情",'photo'=>'相册');
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
    echo<<<DYHB
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$dyhb_options[blog_title]}</title>
<style type="text/css" id="internalStyle">
body{background-color:#FFFFFF; font-size:14px; margin: 0; padding:0;font-family: Helvetica, Arial, sans-serif;-webkit-text-size-adjust: none;}
ul{list-style:none;padding:2px;}
li{padding:5px 0px;}
a:link,a:visited,a:hover,a:active {text-decoration:none;color:#333;}
#top{background-color:#32598B; padding:1px 8px;}#footer{background-color:#EFEFEF; color:#666666; padding:5px;text-align:center;font-weight:bold;}
#page{text-align:center;font-size:26px; color: #CCCCCC}#page a:link,a:active,a:visited,a:hover{padding:0px 6px;}#m{padding:10px;}
#blogname{font-weight:bold; color:#FFFFFF; font-size:18px;}
.description{color:#B2D281;font-size:12px;}
#navi{background:#d3edb8; padding:5px 3px;}
#navi a{padding:auto 3px;}
.photohead{margin-top:6px;}
#active{color:red;}
.title{font-weight:bold; margin:10px 0px 5px 0px;}.title a:link, a:active,a:visited,a:hover{color:#333360; text-decoration:none}
.info{font-size:12px;color:#999999;}.info2{font-size:12px; border-bottom:#CCCCCC dotted 1px; text-align:right; color:#666666; margin:5px 0px; padding:5px;}
.posttitle{font-size:16px; color:#333; font-weight:bold;}.postinfo{font-size:12px; color: #999999;}
.postcont{border-bottom:1px solid #DDDDDD; padding:12px 0px; margin-bottom:10px;}
.t{font-size:16px; font-weight:bold;}.c{padding:10px;}.l{border-bottom:1px solid #DDDDDD; padding:10px 0px;}.twcont{color:#333; padding-top:12px;}
.twinfo{text-align:right; color:#999999; border-bottom:1px solid #DDDDDD; padding:8px 0px; font-size:12px;}
.comcont{color:#333; padding:6px 0px;}.reply{color:#FF3300; font-size:12px;}
.cominfo{text-align:right; color:#999999; border-bottom:1px solid #DDDDDD; padding:8px 0px;font-size:12px;}
.texts{width:92%; height:200px;}.excerpt{width:92%; height:100px;}
</style>
</head>
<body>
<div id="top">
<div id="blogname">{$dyhb_options[blog_title]}</div>
<p class="description">$dyhb_options[blog_information]<p>
</div>
DYHB;
    echo "<div id=\"navi\">";  
    echo "<a href=\"index.php\"$css_i>首页</a> | ";   
    foreach($paginavbar as $key=>$value){
        $css_c=$key==$View?"id=\"active\"":'';
        echo "<a href=\"index.php?action=$key\" $css_c>$value</a>";
		if($key!=='tag')echo "|";
        if($key=='tag') echo "<br>";
    }
    if(ISLOGIN === true) echo "<a href=\"index.php?action=admin\">管理</a>";
    else echo "<a href=\"index.php?action=login\">登陆</a>";
    echo "</div>";
}

/** 底部 */
function wap_footer(){
	global $dyhb_options;
	echo<<<DYHB
<div id="footer">Powered By $dyhb_options[prower_blog_name]</div>
</body>
</html>
DYHB;
	wap_output();
}

/** 输出 */
function wap_output() {
	$content = ob_get_contents();
	ob_end_clean();
	echo GbkToUtf8($content,'GBK');
}

?>