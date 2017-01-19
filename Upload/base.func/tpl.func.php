<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * 文件：tpl.function.php
        * 说明：模板函数
        * 作者：小牛哥
        * 时间：2010-07-22 0:17
        * 版本：DoYouHaoBaby-blog 告别大二.想念丝瓜 (特别版)
        * 主页：www.doyouhaobaby.com
		* 论坛：bbs.56swun.com
@----------------------------------------@
  ---- 西南民大的天空还是蛮明亮的哈 ----
\**=====copyright (c) DoYouHaoBaby=====**/

/**
  * 模板函数
  *
  * @param string $thename 文件名like this index show.log
  * @return string $path|url路径
  */	
function DyhbView($thename,$isadmin) {
	global $dyhb,$dyhb_options,$_COOKIE;
	/** 更换模板用 */
	$t_c=get_cookie('tpl')?get_cookie('tpl'):$dyhb_options['user_template'];
	/** 为全局变量$dyhb赋值，与模板调用无关，但也有关，哈哈*/
	if($isadmin==1){
		$dyhb='view/'.$dyhb_options['admin_template'].'/$dyhb';
	}else{
		$dyhb='view/'.$t_c.'/$dyhb';
	}
	/** 加载模板 */
	if($isadmin=='1'){
		$dyhb_path='view/'.$dyhb_options['admin_template'];
	}else{
		$dyhb_path='view/'.$t_c;
	}
	$thename=$thename.'.php';
	$path=$dyhb_path."/".$thename;
	/** 没有加载默认模板 */
	if(!file_exists($path)){
	    if($is_admin=='1'){
		    $path="admin/view/default/".$thename;
		}else{
		    $path="view/default/".$thename;
		}
	}
	//检查模板
	if(file_exists($path)){
		return $path;
	}
	else{
		DyhbMessage($common_func[0]."<font color='red'>{$path}</font>",'0');
	}
}

/**
  * 页面调试
  *
  * @return string 
  */
function Footer() {
	global $DB, $Starttime, $dyhb_options;
	$Microtime = explode(' ', microtime());
	$Totaltime = number_format(($Microtime[1] + $Microtime[0] - $Starttime), 6);
	$Gzip = $dyhb_options['gzipcompress']==1? 'enabled' : 'disabled';
	echo 'Processed in '.$Totaltime.' second(s), '.$DB->querycount.' queries, Gzip '.$Gzip;
}

?>