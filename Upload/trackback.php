<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * 文件：trackback.php
        * 说明：引用通告
        * 作者：小牛哥
        * 时间：2010-07-22 0:17
        * 版本：DoYouHaoBaby-blog 告别大二.想念丝瓜 (特别版)
        * 主页：www.doyouhaobaby.com
		* 论坛：bbs.56swun.com
@----------------------------------------@
  ---- 西南民大的天空还是蛮明亮的哈 ----
\**=====copyright (c) DoYouHaoBaby=====**/

/** 加载核心部件 */
require_once('width.php');
header('Content-type: text/xml');	

/** 全局trackback是否开启 */
if ($dyhb_options['enable_trackback']=='0') {
	showXML(GbkToUtf8($common_width[2],'GBK'));
}

/**
  * 编码处理
  * 关于编码我是这样这样想的，如果你的程序不是utf-8的编码，那么你要和utf8的网站进行通信，为了统一编码，那么我们在发送数据时，
  * 我们想把数据转化为uft8的编码，在发送出去，那么别人的接受到的引用就是正常的了，同时自己接受人家发来的引用，将数据转化为自己
  * 程序的编码，这样不论是别人发来的，还是自己程序发给自己的都是正常的，不会出现乱码!
  * 自己乱码，一定不要别人乱码，统一转化为utf-8国际编码
  *
  */

/** 获取发送来的数据 */
$blogid = intval( get_args('id') );
$title     =  GbkToUtf8((html2text(sql_check(get_args('title')))),'');
$excerpt   =  GbkToUtf8((html2text(get_args('excerpt'))),'') ;
$url       = GbkToUtf8(sql_check(get_args('url')),'');
$blogname  =  GbkToUtf8((html2text(sql_check(get_args('blog_name')))),'');
$onlineip=getIp();

/** 对发送的数据进行判断，正确则写入数据库 */
if ($blogid && $title && $url && $blogname){
    $blog = $DB->getonerow('SELECT `blog_id` FROM '.DB_PREFIX."blog WHERE blog_id='".$blogid."'");
	if (empty($blog)){
		showXML(GbkToUtf8($common_width[3],'GBK'));
	}elseif ($blog['istrackback'] == '0'){
		showXML(GbkToUtf8($common_width[4],'GBK'));
	}
	//插入引用
    $query = 'INSERT INTO `'.DB_PREFIX."trackback` (blog_id, title, dateline, excerpt, url, blogname,ip) VALUES('$blogid', '$title', '$localdate', '$excerpt', '$url', '$blogname','$onlineip')";
    $DB->query($query);
    $DB->query('UPDATE '.DB_PREFIX."blog SET trackbacknum=trackbacknum+1 WHERE blog_id='".$blogid."'");
	CacheBlog();
	showXMLsuccess();
}else{
	showXML(GbkToUtf8($common_width[5],'GBK'));
}

/**
 * 发送trackback错误消息
 *
 * @param string $message
 * @param string $error
 * @return unknown
 */
function showXML($message, $error = 1){
	header('Content-type: text/xml');
	echo<<<DYHB
	<\?xml version="1.0" encoding="utf-8"\?>
	<response>
	<error>{$error}</error>
	<message>{$message}</message>
	</response>
DYHB;
	exit;
}

/**
 * 发送trackback正确消息
 *
 * @return unknown
 */
function showXMLsuccess() {
	header("Content-type:application/xml");	
	echo<<<DYHB
	<\?xml version="1.0" encoding="utf-8"\?>
    <response>
    <error>0</error>
    </response>
DYHB;
	exit;
}

?>