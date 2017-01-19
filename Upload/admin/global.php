<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * 文件：global.php
        * 说明：后台公用
        * 作者：小牛哥
        * 时间：2010-07-22 0:17
        * 版本：DoYouHaoBaby-blog 告别大二.想念丝瓜 (特别版)
        * 主页：www.doyouhaobaby.com
		* 论坛：bbs.56swun.com
@----------------------------------------@
  ---- 西南民大的天空还是蛮明亮的哈 ----
\**=====copyright (c) DoYouHaoBaby=====**/

//加载公用函数
require_once('../width.php');

//模板基础函数库
$width_file='../view/'.$dyhb_options['user_template'].'/width.php';
if(file_exists($width_file)){
	 require_once('../view/'.$dyhb_options['user_template'].'/width.php');
}
else{
	if(file_exists('../view/default/width.php')){
	    require_once('../view/default/width.php');
	}else{
	    DyhbMessage($common_func[247],'0');
	}
}

//后台模板处理处理函数
require_once(DOYOUHAOBABY_ROOT.'/base.func/admin.tpl.func.php');

// 加载程序语言包
require_once(DOYOUHAOBABY_ROOT."/images/lang/$dyhb_options[global_lang_select]/admin.php");

?>