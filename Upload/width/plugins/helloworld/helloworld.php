<?php
//���������Ϣ
$PLUGINNAME='Hello';
$PLUGINVER='1.0';
$PLUGINAUTHOR='Сţ��';
$PLUGINURL='http://www.doyouhaobaby.com';
$PLUGINDESCRIPTION='����һ�������ʾ����ӭʹ�ñ��������';
//�Ƿ�������ص�������
$is_allowed_navbar=true;
//�Ƿ��������ҳ��
$is_self_help=false;

addHooks('width_footer','_helloworld_demo');
function _helloworld_demo(){
  //echo "hello";
}

?>