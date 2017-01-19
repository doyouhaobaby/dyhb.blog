<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * 文件：visitor.function.php
        * 说明：访问统计
        * 作者：小牛哥
        * 时间：2010-07-22 0:17
        * 版本：DoYouHaoBaby-blog 告别大二.想念丝瓜 (特别版)
        * 主页：www.doyouhaobaby.com
		* 论坛：bbs.56swun.com
@----------------------------------------@
  ---- 西南民大的天空还是蛮明亮的哈 ----
\**=====copyright (c) DoYouHaoBaby=====**/

/**
 * 访问统计
 *
 */
function visitor(){
	global $dyhb_options,$DB,$localdate;
	$browsers = 'MSIE|Netscape|Opera|Konqueror|Mozilla';//浏览器
    $spiders = 'Bot|Crawl|Spider|slurp|sohu-search|lycos|robozilla';//机器人
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