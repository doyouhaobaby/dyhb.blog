<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * 文件：c.function.cools.php
        * 说明：个性设置
        * 作者：小牛哥
        * 时间：2010-07-22 0:17
        * 版本：DoYouHaoBaby-blog 告别大二.想念丝瓜 (特别版)
        * 主页：www.doyouhaobaby.com
		* 论坛：bbs.56swun.com
@----------------------------------------@
  ---- 西南民大的天空还是蛮明亮的哈 ----
\**=====copyright (c) DoYouHaoBaby=====**/

class Cools
{
 public $DB;
 function __construct($newdb){
	$this->DB=$newdb;
}

/**
 * 添加用户
 *
 */
function SaveUser($SaveUserDate){
   add_sql($SaveUserDate,'user');
}

/**
 * 删除用户
 *
 */
function DeleteUser($user_id){
 $this->DB->query("delete from `".DB_PREFIX."user` where `user_id`=$user_id");
}

/**
 * 更新个性数据-模板
 *
 */
function UpdateCools($coolname,$option_id){
   $this->DB->query("UPDATE `".DB_PREFIX."option` SET `value` = '$coolname' WHERE `option_id` = '$option_id'");
}

function UpdateOption($name,$value){
   $this->DB->query("UPDATE `".DB_PREFIX."option` SET `value` = '$value' WHERE `name` = '$name'");
}

/**
 * 获取用户信息
 *
 */
 function GetBloggerInfo($user_id){
    $bloggerinfo=$this->DB->getonerow("SELECT *FROM `".DB_PREFIX."user` where `user_id`=$user_id");
	return dyhb_stripslashes($bloggerinfo);
}

/**
 * 更新用户信息
 *
 */
function UpdateBloggerInfo($SaveCoolDate,$UserId){
   update_sql($SaveCoolDate,'user_id',$UserId,'user');
 }
}

?>