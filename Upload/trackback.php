<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * �ļ���trackback.php
        * ˵��������ͨ��
        * ���ߣ�Сţ��
        * ʱ�䣺2010-07-22 0:17
        * �汾��DoYouHaoBaby-blog �����.����˿�� (�ر��)
        * ��ҳ��www.doyouhaobaby.com
		* ��̳��bbs.56swun.com
@----------------------------------------@
  ---- ����������ջ����������Ĺ� ----
\**=====copyright (c) DoYouHaoBaby=====**/

/** ���غ��Ĳ��� */
require_once('width.php');
header('Content-type: text/xml');	

/** ȫ��trackback�Ƿ��� */
if ($dyhb_options['enable_trackback']=='0') {
	showXML(GbkToUtf8($common_width[2],'GBK'));
}

/**
  * ���봦��
  * ���ڱ�����������������ģ������ĳ�����utf-8�ı��룬��ô��Ҫ��utf8����վ����ͨ�ţ�Ϊ��ͳһ���룬��ô�����ڷ�������ʱ��
  * �����������ת��Ϊuft8�ı��룬�ڷ��ͳ�ȥ����ô���˵Ľ��ܵ������þ����������ˣ�ͬʱ�Լ������˼ҷ��������ã�������ת��Ϊ�Լ�
  * ����ı��룬���������Ǳ��˷����ģ������Լ����򷢸��Լ��Ķ��������ģ������������!
  * �Լ����룬һ����Ҫ�������룬ͳһת��Ϊutf-8���ʱ���
  *
  */

/** ��ȡ������������ */
$blogid = intval( get_args('id') );
$title     =  GbkToUtf8((html2text(sql_check(get_args('title')))),'');
$excerpt   =  GbkToUtf8((html2text(get_args('excerpt'))),'') ;
$url       = GbkToUtf8(sql_check(get_args('url')),'');
$blogname  =  GbkToUtf8((html2text(sql_check(get_args('blog_name')))),'');
$onlineip=getIp();

/** �Է��͵����ݽ����жϣ���ȷ��д�����ݿ� */
if ($blogid && $title && $url && $blogname){
    $blog = $DB->getonerow('SELECT `blog_id` FROM '.DB_PREFIX."blog WHERE blog_id='".$blogid."'");
	if (empty($blog)){
		showXML(GbkToUtf8($common_width[3],'GBK'));
	}elseif ($blog['istrackback'] == '0'){
		showXML(GbkToUtf8($common_width[4],'GBK'));
	}
	//��������
    $query = 'INSERT INTO `'.DB_PREFIX."trackback` (blog_id, title, dateline, excerpt, url, blogname,ip) VALUES('$blogid', '$title', '$localdate', '$excerpt', '$url', '$blogname','$onlineip')";
    $DB->query($query);
    $DB->query('UPDATE '.DB_PREFIX."blog SET trackbacknum=trackbacknum+1 WHERE blog_id='".$blogid."'");
	CacheBlog();
	showXMLsuccess();
}else{
	showXML(GbkToUtf8($common_width[5],'GBK'));
}

/**
 * ����trackback������Ϣ
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
 * ����trackback��ȷ��Ϣ
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