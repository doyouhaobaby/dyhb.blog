<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * �ļ���c.function.cools.php
        * ˵������������
        * ���ߣ�Сţ��
        * ʱ�䣺2010-07-22 0:17
        * �汾��DoYouHaoBaby-blog �����.����˿�� (�ر��)
        * ��ҳ��www.doyouhaobaby.com
		* ��̳��bbs.56swun.com
@----------------------------------------@
  ---- ����������ջ����������Ĺ� ----
\**=====copyright (c) DoYouHaoBaby=====**/

class Cools
{
 public $DB;
 function __construct($newdb){
	$this->DB=$newdb;
}

/**
 * ����û�
 *
 */
function SaveUser($SaveUserDate){
   add_sql($SaveUserDate,'user');
}

/**
 * ɾ���û�
 *
 */
function DeleteUser($user_id){
 $this->DB->query("delete from `".DB_PREFIX."user` where `user_id`=$user_id");
}

/**
 * ���¸�������-ģ��
 *
 */
function UpdateCools($coolname,$option_id){
   $this->DB->query("UPDATE `".DB_PREFIX."option` SET `value` = '$coolname' WHERE `option_id` = '$option_id'");
}

function UpdateOption($name,$value){
   $this->DB->query("UPDATE `".DB_PREFIX."option` SET `value` = '$value' WHERE `name` = '$name'");
}

/**
 * ��ȡ�û���Ϣ
 *
 */
 function GetBloggerInfo($user_id){
    $bloggerinfo=$this->DB->getonerow("SELECT *FROM `".DB_PREFIX."user` where `user_id`=$user_id");
	return dyhb_stripslashes($bloggerinfo);
}

/**
 * �����û���Ϣ
 *
 */
function UpdateBloggerInfo($SaveCoolDate,$UserId){
   update_sql($SaveCoolDate,'user_id',$UserId,'user');
 }
}

?>