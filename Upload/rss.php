<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * �ļ���rss.php
        * ˵����rss���Ĺ���
        * ���ߣ�Сţ��
        * ʱ�䣺2010-07-22 0:17
        * �汾��DoYouHaoBaby-blog �����.����˿�� (�ر��)
        * ��ҳ��www.doyouhaobaby.com
		* ��̳��bbs.56swun.com
@----------------------------------------@
  ---- ����������ջ����������Ĺ� ----
\**=====copyright (c) DoYouHaoBaby=====**/

/** ���غ��Ĳ��� */
require_once("common.php");

/** ��ȡRSS��־���� */
$sort_id=isset($_GET['sort_id'])?intval($_GET['sort_id']):'';
$con=$sort_id?"and `sort_id`='$sort_id'":'';
$Sql="$con order by `istop` desc,`dateline` desc";
$log=$_Logs->GetLog($Sql,'0',$dyhb_options['rss_log_num'],$isshow='1',$ispage='0');

/** ���RSSͷ����Ϣ */
header("Content-Type: application/xml");
$url=getUrl();
$BlogTitle=htmlspecialchars($dyhb_options[blog_title]);
$BlogInformation=htmlspecialchars($dyhb_options['blog_information']);
$Date=date('Y-m-d', $localdate);
echo "<?xml version=\"1.0\" encoding=\"GBK\"?>";
echo<<<DYHB
        <rss version="2.0"
		  xmlns:content="http://purl.org/rss/1.0/modules/content/"
	      xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	      xmlns:dc="http://purl.org/dc/elements/1.1/"
	      xmlns:atom="http://www.w3.org/2005/Atom"
	      xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	      xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
		>
           <channel>
		   <title>DYHB-blog</title>
	       <atom:link href="{$url}/rss.php" rel="self" type="application/rss+xml" />
	       <link>{$url}</link>
	       <description>{$BlogInformation}</description>
	       <language>ch</language>
           <copyright>Powered by {$dyhb_options[prower_blog_name]}. Copyright (C) 2009-2010.</copyright>
           <generator>{$dyhb_options[prower_blog_name]}</generator>
           <lastBuildDate>{$Date}</lastBuildDate>
DYHB;

/** ѭ�������־��Ϣ */
if ($log){
    foreach ($log as $value) {
		$Content=$value['password']?$common_width[6]:$value['content'];
		$Dateline=date('Y-m-d H:i:s',$value['dateline']);
        echo<<<DYHB
		<item>
		<title>{$value[title]}</title>
		<link>?p={$value[blog_id]}</link>
		<comments>?p={$value[blog_id]}#comment</comments>
		<pubDate>$Dateline</pubDate>
		<dc:creator>{$value[email]}{$value[user]}</dc:creator>
		<category><![CDATA[{$value[name]}]]></category>
		<guid isPermaLink="false">{$url}?p={$value[blog_id]}</guid>
		<description><![CDATA[$value[content]]]></description>
		<content:encoded><![CDATA[$value[content]]]></content:encoded>
		<slash:comments>{$value[commentnum]}</slash:comments>
		</item>
DYHB;
    }
}

/** ���RSSβ�� **/
echo<<<DYHB
         </channel>
      </rss>
DYHB;
exit;

?>