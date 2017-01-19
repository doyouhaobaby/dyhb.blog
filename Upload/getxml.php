<?php
/** 加载核心部件 */
require_once('width.php');

/** 消息初始化 */
$html='';
$close=GbkToUtf8( $common_width[42],'GBK' );

/** trackback地址 */
if($_GET['tid']){
	$tid = intval($_GET['tid']);
	$t=GbkToUtf8( $common_width[43],'GBK' );
    $html=<<<DYHB
<h2><a href="javascript:;" onclick="dyhb_by_id('ajax-div-trackback').style.display='none';">$close</a>Trackback</h2>
<div>
<a href="trackback.php?id={$tid}" onclick="setCopy(this.href);return false;" target="_self">$t</a>
</div>
DYHB;
xmlmsg($html);
}

/** 登录框 */
if($_GET['action']){
	  $action = dyhb_addslashes( htmlspecialchars( $_GET['action'] ));
      $l=GbkToUtf8( $common_width[44],'GBK' );
	  $r=GbkToUtf8( $common_width[45],'GBK' );
	  $q=GbkToUtf8( $common_width[46],'GBK' );
	  if($action=='login'){
	      $html.="<h2><a href=\"javascript:;\" onclick=\"dyhb_by_id('ajax-div-login').style.display='none';\">$close</a>$l</h2>";  
		  $html.= GbkToUtf8( _login_html(),'GBK' ); 
	  }elseif($action=='register'){
	      $html.="<h2><a href=\"javascript:;\" onclick=\"dyhb_by_id('ajax-div-register').style.display='none';\">$close</a>$r</h2>"; 
		  $html.= GbkToUtf8( _register_html(),'GBK' );
      }elseif($action=='quicklog'){
	      $html.="<h2><a href=\"javascript:;\" onclick=\"dyhb_by_id('ajax-div-quicklog').style.display='none';\">$close</a>$q</h2>"; 
		  $html.= GbkToUtf8( _quicklog_html(),'GBK' );
	  }
      xmlmsg($html);
}

/** 外部衔接地址 */
if(($_GET['name']||$_GET['img'])&&$_GET['url']){
	$name=GbkToUtf8(dyhb_addslashes(htmlspecialchars( $_GET[name] )),'GBK');
	$img=GbkToUtf8(dyhb_addslashes(htmlspecialchars( $_GET[img] )),'GBK');
	$url=GbkToUtf8(dyhb_addslashes(htmlspecialchars( $_GET[url] )),'GBK');
	$w=GbkToUtf8($common_width[47],'GBK');
	$d=GbkToUtf8($common_width[55],'GBK');
	if($name){
	    $a_in=$name;
	}else{
	    $a_in="<img src=\"$img\"/>";
	}
    $html=<<<DYHB
<h2><a href="javascript:;" onclick="dyhb_by_id('ajax-div-outlink').style.display='none';">$close</a>$w</h2>
<div>
{$d}( <a href="$url" target="_blank">$a_in</a> )
</div>
DYHB;
xmlmsg($html);
}

/** 音乐地址 */
if($_GET['mp3id']){
	$mp3id = intval($_GET['mp3id']);
	//获取文件
	$TheFile=$_Mp3s->GetOneMp3( $mp3id );
	$TheFile_name=GbkToUtf8($TheFile[name],'GBK');
	$TheFile_path=GbkToUtf8($TheFile[path],'GBK');
	$g1=GbkToUtf8($common_width[54],'GBK');
	$g2=GbkToUtf8($common_width[48],'GBK');
	$g3=GbkToUtf8($common_width[49],'GBK');
    $html=<<<DYHB
<h2><a href="javascript:;" onclick="dyhb_by_id('ajax-div-mp3').style.display='none';">$close</a>$g1</h2>
<div>
<a href="{$TheFile_path}" onclick="setCopy(this.href);return false;" target="_self">$g2($TheFile_name)$g3</a>
</div>
DYHB;
xmlmsg($html);
}

/** 附件地址 */
if($_GET['fileid']){
	$fileid = intval($_GET['fileid']);
	//获取文件
	$TheFile=$_Photosorts->GetIdFile( $fileid );
	if($dyhb_options['photo_isshow_hide']=='1'){
	     $TheFile_path=$dyhb_options[blogurl]."/file.php?id=$TheFile[file_id]";
	}else{
		$TheFile_path=$dyhb_options[blogurl]."/width/upload/".$TheFile[path];
	}
	$TheFile_name=GbkToUtf8($TheFile[name],'GBK');
	$g1=GbkToUtf8($common_width[50],'GBK');
	$g2=GbkToUtf8($common_width[48],'GBK');
	$g3=GbkToUtf8($common_width[49],'GBK');
    $html=<<<DYHB
<h2><a href="javascript:;" onclick="dyhb_by_id('ajax-div-file').style.display='none';">$close</a>$g1</h2>
<div>
DYHB;
	if($dyhb_options['is_image_leech']=='1'){
         $html.= "<p><a href=\"{$TheFile_path}\" onclick=\"setCopy(this.href);return false;\" target=\"_self\">{$g2}($TheFile_name){$g3}</a></p>";
	}else{
	     $html.= "<p style=\"text-align:left;\">$common_width[51]{$TheFile_path}</p>";
	}
    $html.= "</div>";
    xmlmsg($html);
}

if($_GET['tagname']){
	$tagname=dyhb_addslashes($_GET['tagname']);
	$g1=GbkToUtf8($common_width[52],'GBK');
    $html=<<<DYHB
<h2><a href="javascript:;" onclick="dyhb_by_id('ajax-div-tag').style.display='none';">$close</a>$g1</h2>
<div>
DYHB;
    $BlogIdStr=$_Tags->GetBlog_idStrByName($tagname);
    if($BlogIdStr === false){
	     $html.=GbkToUtf8($common_width[53],'GBK');
    }else{
         $Sql="and `blog_id` in (".$BlogIdStr.") $isshow ORDER BY `istop` DESC,`dateline` DESC";
         $_Loglist=$_Logs->GetLog($Sql,'','',$isshow='1',$ispage='0');
	     if($_Loglist){
             $html.="<ul>";
		     foreach($_Loglist as $value){
		         $achive_url=_showlog_posturl(GbkToUtf8($value,'GBK'));
				 $title=GbkToUtf8($value[title],'GBK');
				 $html.="<li><a href=\"{$achive_url}\">{$title}</a></li>";
		     }
		     $html.="</ul>";
	     }else{
	         $html.=GbkToUtf8($common_width[53],'GBK');
	     }
   }
   $html.= "</div>";
   xmlmsg($html);
}


/** 消息 */
function xmlmsg($html) {
	@header("Content-Type: text/xml");
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	echo "<root><![CDATA[".$html."]]></root>\n";
}

?>