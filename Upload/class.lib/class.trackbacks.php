<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * �ļ���c.function.trackback.php
        * ˵�������÷�װ
        * ���ߣ�Сţ��
        * ʱ�䣺2010-07-22 0:17
        * �汾��DoYouHaoBaby-blog �����.����˿�� (�ر��)
        * ��ҳ��www.doyouhaobaby.com
		* ��̳��bbs.56swun.com
@----------------------------------------@
  ---- ����������ջ����������Ĺ� ----
\**=====copyright (c) DoYouHaoBaby=====**/

class Trackbacks{
 public $DB;
 function __construct($newdb){
	$this->DB=$newdb;
 }

/**
 * ����trackback
 *
 */
function SendTrackback($blogurl, $pingUrl, $blogId, $title, $blogname, $content){  
global $common_func;
$blog_url=getUrl();
$url = $blog_url."/?p=".$blogId;
$hosts = explode("\n", $pingUrl);
$tbmsg = '';
foreach ($hosts as $key => $value){
$host = trim($value);
if(strstr(strtolower($host), "http://") || strstr(strtolower($host), "https://")){
$data ="url=".rawurlencode($url)."&title=".rawurlencode($title)."&blog_name=".rawurlencode($blogname)."&excerpt=".rawurlencode($content);
$result = strtolower($this->SendPacket($host, $data));
if (strstr($result, "<error>0</error>") === false) {
   preg_match("/<message>(.*)<\/message>/Uis",$result,$match);   
   $tbmsg.=GbkToUtf8("{$host}{$common_func[89]}",'GBK');
   $tbmsg.="<font color='red'>$match[0]</font>".GbkToUtf8("{$host}{$common_func[90]}<br>",'GBK');
} else {
   $tbmsg .= GbkToUtf8("<font color='green'>{$host}{$host}{$common_func[91]}</font></font><br>",'GBK');
}}}
 return $tbmsg;
}
//�������ñ�׼��ʽ
function SendPacket($url, $data){
  $uinfo = parse_url($url);
  if (isset($uinfo['query'])){
			$data .= "&".$uinfo['query'];
  }
  if (!$fp = @fsockopen($uinfo['host'], (($uinfo['port']) ? $uinfo['port'] : "80"), $errno, $errstr, 3)){
			return false;
 }
$out = "POST ".$uinfo['path']." HTTP/1.1\r\n";
$out.= "Host: ".$uinfo['host']."\r\n";
$out.= "Content-type: application/x-www-form-urlencoded\r\n";
$out.= "Content-length: ".strlen($data)."\r\n";
$out.= "Connection: close\r\n\r\n";
$out.= $data;
fwrite($fp, $out);
$http_response = '';
while(!feof($fp)){
 $http_response .= fgets($fp, 128);
}
@fclose($fp);
return $http_response;
}

/**
 * ��ȡtrackback
 *
 * @param int $start ��ѯ��ʼ
 * @param int $end ��ѯ����
 * @param int $isadmin falseΪǰ̨��trueΪ��̨
 */
function GetTrackbacks($blog_id='',$start='',$end='',$isadmin){
$condition = $blog_id ? "where `blog_id`=$blog_id" : '';
$limit = ($start==''&&$end=='')?'':"LIMIT $start,$end";
if($isadmin===false){
   $sql="SELECT *FROM `".DB_PREFIX."trackback` $condition ORDER BY `dateline` DESC $limit";
}else{
   $sql = "SELECT *FROM `".DB_PREFIX."trackback`  ORDER BY `dateline` DESC $limit";	
}
  $trackbacks=$this->DB->gettworow($sql);
  if($trackbacks){
  $i=0;
  foreach($trackbacks as $value){
    $trackbacks[$i]['title']=stripslashes($value['title']);
    $trackbacks[$i]['excerpt']=stripslashes($value['excerpt']);
    $trackbacks[$i]['blogname']=stripslashes($value['blogname']);
    $i++;
  }}
  return $trackbacks;
}
	
/**
 * ɾ������ͨ��
 *
 */
function DelTrackback($trackback_id){   
     $sql = "SELECT `blog_id` FROM `".DB_PREFIX."trackback` WHERE `trackback_id`='$trackback_id'";
     $blog = $this->DB->getonerow($sql);
     $this->DB->query("UPDATE `".DB_PREFIX."blog` SET trackbacknum=trackbacknum-1 WHERE `blog_id`='$blog[blog_id]'");
     $this->DB->query("DELETE FROM `".DB_PREFIX."trackback` where `trackback_id`='$trackback_id'");
  }
}

?>