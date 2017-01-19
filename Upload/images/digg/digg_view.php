<?php
/**================[^_^]================**\
      ---- 因为有梦，所以有目标 ----
@----------------------------------------@
        * 文件：code.php
        * 说明：验证码
        * 作者：小牛哥
        * 时间：2010-05-06 20:22
        * 版本：DoYouHaoBaby-blog 概念版
        * 主页：www.doyouhaobaby.com
@----------------------------------------@
  ---- 西南民大的天空还是蛮明亮的哈 ----
\**=====copyright (c) DoYouHaoBaby=====**/

/** 加载核心文件 */
require('../../width.php');
if(!defined('DOYOUHAOBABY_ROOT')) {exit('hi,friend!');}

//参数和日志数据
$id = intval($_GET['id']);
$bloginfor=$DB->getonerow("select bad,good from ".DB_PREFIX."blog where `blog_id`='$id'");

//顶记录，踩记录，总记录
$goodpost=$bloginfor['good'];
$badpost=$bloginfor['bad'];
$postnum=$goodpost+$badpost;
    
//进度条
if($postnum>0){
	 $goodper=number_format($goodpost/$postnum,3)*100;
	 $badper=100-$goodper;
}else{
	 $goodper=$badper=0;
}

//动作记录，更新数据库
$action = addslashes($_GET['action']);
$url='digg_view.php?id='.$id;
if($action == 'good'){
	  $DB->query("update ".DB_PREFIX."blog set good=good+1 where `blog_id`='$id'");
	  header("Location: $url");
}else if($action=='bad'){
	  $DB->query("update ".DB_PREFIX."blog set bad=bad+1 where `blog_id`='$id'");
	  header("Location: $url");
} 
	
//digg界面
$digg = '';
$digg = '<div class="diggbox digg_good" onmousemove="this.style.backgroundPosition=\'left bottom\';" onmouseout="this.style.backgroundPosition=\'left top\';" onclick="postDigg(\'good\','.$id.')"><div class="digg_act">'.$common_func[108].'</div><div class="digg_num">('.$goodpost.')</div><div class="digg_percent"><div class="digg_percent_bar"><span style="width:'.$goodper.'%"></span></div><div class="digg_percent_num">'.$goodper.'%</div></div></div><div class="diggbox digg_bad" onmousemove="this.style.backgroundPosition=\'right bottom\';" onmouseout="this.style.backgroundPosition=\'right top\';" onclick="postDigg(\'bad\','.$id.')"><div class="digg_act">'.$common_func[109].'</div><div class="digg_num">('.$badpost.')</div><div class="digg_percent"><div class="digg_percent_bar"><span style="width:'.$badper.'%"></span></div><div class="digg_percent_num">'.$badper.'%</div></div></div>';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>digg</title>
<style>*{border:0px;padding:0px;}body {text-align:center;}#digg {margin:0 auto;width:420px;overflow:hidden;}.diggbox {cursor:pointer;float:left;height:51px;margin-right:8px;overflow:hidden;width:195px;}.diggbox .digg_act {float:left;font-size:14px;font-weight:bold;height:29px;line-height:31px;overflow:hidden;text-indent:32px;}.diggbox .digg_num {float:left;line-height:29px;text-indent:5px;}.diggbox .digg_percent {text-align:left;clear:both;overflow:hidden;padding-left:10px;width:180px;}.diggbox .digg_percent .digg_percent_bar {background:#E8E8E8 none repeat scroll 0 0;border-right:1px solid #CCCCCC;float:left;height:7px;margin-top:3px;overflow:hidden;width:100px;}.diggbox .digg_percent .digg_percent_num {float:left;font-size:10px;padding-left:10px;}.diggbox .digg_percent .digg_percent_bar span {background:#000000 none repeat scroll 0 0;display:block;height:5px;overflow:hidden;}.digg_good {background:transparent url(./newdigg-bg.png) no-repeat scroll left top;}.digg_bad {background:transparent url(./newdigg-bg.png) no-repeat scroll right top;}.digg_good .digg_act {color:#CC3300;}.digg_good .digg_num {color:#CC6633;}.digg_bad .digg_act {color:#3366CC;}.digg_bad .digg_num {color:#3399CC;}.digg_good .digg_percent .digg_percent_bar span {background:#FFC535 none repeat scroll 0 0;border:1px solid #E37F24;}.digg_bad .digg_percent .digg_percent_bar span {background:#94C0E4 none repeat scroll 0 0;border:1px solid #689ACC;}</style>
<script>
//读写cookie函数
function GetCookie(c_name)
{
	if (document.cookie.length > 0)
	{
		c_start = document.cookie.indexOf(c_name + "=")
		if (c_start != -1)
		{
			c_start = c_start + c_name.length + 1;
			c_end   = document.cookie.indexOf(";",c_start);
			if (c_end == -1)
			{
				c_end = document.cookie.length;
			}
			return unescape(document.cookie.substring(c_start,c_end));
		}
	}
	return null
}

function SetCookie(c_name,value,expiredays)
{
	var exdate = new Date();
	exdate.setDate(exdate.getDate() + expiredays);
	document.cookie = c_name + "=" +escape(value) + ((expiredays == null) ? "" : ";expires=" + exdate.toGMTString()); //使设置的有效时间正确。增加toGMTString()
}

function postDigg(ac, aid)
{
	var saveid = GetCookie('diggid');
	if(saveid != null)
	{
		var saveids = saveid.split(',');
		var hasid = false;
		saveid = '';
		j = 1;
		for(i=saveids.length-1;i>=0;i--)
		{
			if(saveids[i]==aid && hasid) continue;
			else {
				if(saveids[i]==aid && !hasid) hasid = true;
				saveid += (saveid=='' ? saveids[i] : ','+saveids[i]);
				j++;
				if(j==20 && hasid) break;
				if(j==19 && !hasid) break;
			}
		}
		if(hasid) { alert('<?=$common_func[107];?>'); return false; }
		else saveid += ','+aid;
		SetCookie('diggid',saveid,1);

	} else {
		SetCookie('diggid',aid,1);
	}
	location.href='digg_view.php?action='+ac+'&id='+aid;
}
</script>
<body>
<div id="digg">
<?php
echo $digg;
?>
</div>
</body>
</html>