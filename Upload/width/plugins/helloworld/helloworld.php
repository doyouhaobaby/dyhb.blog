<?php
//插件基本信息
$PLUGINNAME='Hello';
$PLUGINVER='1.0';
$PLUGINAUTHOR='小牛哥';
$PLUGINURL='http://www.doyouhaobaby.com';
$PLUGINDESCRIPTION='这是一个插件演示，欢迎使用本程序哈！';
//是否允许加载到导航条
$is_allowed_navbar=true;
//是否允许独立页面
$is_self_help=false;

addHooks('width_footer','_helloworld_demo');
function _helloworld_demo(){
  //echo "hello";
}

?>