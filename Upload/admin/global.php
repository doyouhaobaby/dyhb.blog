<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * �ļ���global.php
        * ˵������̨����
        * ���ߣ�Сţ��
        * ʱ�䣺2010-07-22 0:17
        * �汾��DoYouHaoBaby-blog �����.����˿�� (�ر��)
        * ��ҳ��www.doyouhaobaby.com
		* ��̳��bbs.56swun.com
@----------------------------------------@
  ---- ����������ջ����������Ĺ� ----
\**=====copyright (c) DoYouHaoBaby=====**/

//���ع��ú���
require_once('../width.php');

//ģ�����������
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

//��̨ģ�崦������
require_once(DOYOUHAOBABY_ROOT.'/base.func/admin.tpl.func.php');

// ���س������԰�
require_once(DOYOUHAOBABY_ROOT."/images/lang/$dyhb_options[global_lang_select]/admin.php");

?>