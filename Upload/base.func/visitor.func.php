<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * �ļ���visitor.function.php
        * ˵��������ͳ��
        * ���ߣ�Сţ��
        * ʱ�䣺2010-07-22 0:17
        * �汾��DoYouHaoBaby-blog �����.����˿�� (�ر��)
        * ��ҳ��www.doyouhaobaby.com
		* ��̳��bbs.56swun.com
@----------------------------------------@
  ---- ����������ջ����������Ĺ� ----
\**=====copyright (c) DoYouHaoBaby=====**/

/**
 * ����ͳ��
 *
 */
function visitor(){
	global $dyhb_options,$DB,$localdate;
	$browsers = 'MSIE|Netscape|Opera|Konqueror|Mozilla';//�����
    $spiders = 'Bot|Crawl|Spider|slurp|sohu-search|lycos|robozilla';//������
	if(!preg_match("/($spiders)/", $_SERVER['HTTP_USER_AGENT']) && preg_match("/($browsers)/", $_SERVER['HTTP_USER_AGENT'])){
	    $onlineip = getIp();
	    $dyhb_visitorip = get_cookie('visitorip');
	    if ($dyhb_visitorip != $onlineip){
		  $Res = set_cookie('visitorip', getIp(),'86400');
		  if ($Res){
			  $nowtime = onedate($localdate,'Ymd');
		      $Start=$DB->getonerow('SELECT value from '.DB_PREFIX."option  where name='daytime' and value=$nowtime");
			  if (!$Start){
				  $DB->query('UPDATE '.DB_PREFIX."option SET value ='$nowtime' where name='daytime'");
				  $DB->query('UPDATE '.DB_PREFIX."option SET value ='1' where name='today_count_num'");
			   }else {
				  $DB->query('UPDATE '.DB_PREFIX."option SET value =value+1 where name='today_count_num'");
			  }
			  $DB->query('UPDATE '.DB_PREFIX."option SET value =value+1 where name='all_count_num'");
              CacheOptions();
		   }
	     }
      }
}

?>