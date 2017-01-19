<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * 文件：plugin.php
        * 说明：插件管理
        * 作者：小牛哥
        * 时间：2010-07-22 0:17
        * 版本：DoYouHaoBaby-blog 告别大二.想念丝瓜 (特别版)
        * 主页：www.doyouhaobaby.com
		* 论坛：bbs.56swun.com
@----------------------------------------@
  ---- 西南民大的天空还是蛮明亮的哈 ----
\**=====copyright (c) DoYouHaoBaby=====**/

require_once('global.php');

CheckPermission("cp",$common_permission[22]);

$plugin = sql_check( get_argget('plugin'));
$type = CheckSql (sql_check( get_argget('type')));

//遍历Plugin目录中插件
$PluginDir=listDir2('../width/plugins');

if($type==''){$condition="";}
elseif($type=='active'){$condition="where `active`='1'";}
else{$condition="where `active`='0'";}
$PluginList=$DB->gettworow("select *from `".DB_PREFIX."plugin` $condition");

//获取插件列表
if($view==''&&$plugin==''){
if($PluginList){
foreach($PluginList as $value){  
   $is_active=$value[active]=='1'?$common_func[205]:$common_func[206];
   $do_active=$value[active]=='1'?"plugin.php?do=inactive&plugin={$value[dir]}":"plugin.php?do=active&plugin={$value[dir]}";
   $result.=<<<DYHB
   <tr>
        <td class="tdcenter"><a href="plugin.php?plugin={$value[dir]}">{$value[name]}</a></td>
		<td class="tdcenter">
		<a href="{$do_active}">{$is_active}</a>
		</td>
        <td class="tdcenter">{$value[version]}</td>
        <td>
		   {$value[description]}<br />
		   $common_func[225]{$value[author]}
		   <a href="{$value[authorurl]}" target="_blank">$common_func[207]&raquo;</a>		
		</td>
      </tr>
DYHB;
}
}}

//等待操作
if($view=='waittoget'){
	$result="<a href='plugin.php?do=getplugin'>$common_func[208]</a><span>$common_func[210]</span><br>";
	$result.="<a href='plugin.php?do=clearplugin'>$common_func[209]</a><span>$common_func[211]</span>";
}

//扫描博客程序中的插件，并写入数据库中
if ($view == 'getplugin'){   
	if($PluginDir){
	foreach($PluginDir as $value)
	{
		$PluginUrl="../width/plugins/{$value}/{$value}.php";
		if(file_exists($PluginUrl)){
		  include_once($PluginUrl);
		  //如果数据库不存在相同的插件，则插入
		    if(!$r=$DB->getonerow("select *from `".DB_PREFIX."plugin` where `dir`='$value'")){
                 $DB->query("INSERT INTO ".DB_PREFIX."plugin (name,author,authorurl,description,dir,version) VALUES('$PLUGINNAME','$PLUGINAUTHOR','$PLUGINURL','$PLUGINDESCRIPTION','$value','$PLUGINVER')");
			}
		}
	}}
    CacheOptions();
	CachePluginList();
	CachePluginNav();
	DyhbMessage($common_func[224],'plugin.php');
}

//清理博客程序中的插件信息
if($view=='clearplugin'){   
	if($PluginList){
    foreach($PluginList as $value){
		$PluginUrl="../width/plugins/{$value[dir]}/{$value[dir]}.php";
		if(!file_exists($PluginUrl)){
            $DB->query("delete from ".DB_PREFIX."plugin where `dir`='$value[dir]'");
			//移除插件到导航条挂载在外面
	        delPlugin($value['dir'],'navbar');
			delPlugin($value['dir'],'self_help');
		}
	}}
	CacheOptions();
	CachePluginList();
	CachePluginNav();	
	DyhbMessage($common_func[212],'plugin.php');
}

//禁用
if($view == 'inactive'&&$plugin){
	$DB->query("update `".DB_PREFIX."plugin` set `active`='0' where `dir`='$plugin'");
	//移除插件到导航条挂载在外面
	delPlugin($value['dir'],'navbar');
	delPlugin($value['dir'],'self_help');
	CacheOptions();
	CachePluginList();
	CachePluginNav();	
	DyhbMessage($common_func[213],'plugin.php?type=inactive');
}

//激活
if($view == 'active'&&$plugin){
	$DB->query("update `".DB_PREFIX."plugin` set `active`='1' where `dir`='$plugin'");
	require_once("../width/plugins/{$plugin}/{$plugin}.php");
	if($is_allowed_navbar){
	   //安装插件到导航条挂载上
	   addPlugin($plugin,'navbar');
	}
	if($is_self_help){
	   //允许插件拥有独立的页面
	   addPlugin($plugin,'self_help');
	}
	CacheOptions();
	CachePluginList();
	CachePluginNav();
	DyhbMessage($common_func[214],'plugin.php?type=active');
}

//加载插件配置页面,以及保存插件
if ($view == ''&& $plugin){   
	$PluginUrl="../width/plugins/{$plugin}/{$plugin}_set.php";
    if(file_exists($PluginUrl)){
	   require_once($PluginUrl);
	   if(empty($_POST)){
	    //include DyhbView('pluginset',1);
	  }
	}else{
	  DyhbMessage($common_func[215],'');
	}
}

//保存插件设置
if ($view == ''&& $plugin&&!empty($_POST)){
	require_once("../width/plugins/{$plugin}/{$plugin}_set.php");
    if(function_exists(admin_plugin_save)){
	   admin_plugin_save();
	}
	DyhbMessage($common_func[216],"plugin.php?plugin={$plugin}");
}

?>

<?
//插件列表->设置
if((($view==''||'waittoget')&&$plugin=='')||($view == ''&& $plugin)):?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"  dir="ltr" lang="zh-CN">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gbk" />
<title><?=$common_func[217];?></title>
<link rel="stylesheet" type="text/css" href="../images/admin/upload.css">
<script type="text/javascript" src="../images/js/common.js"></script>
<script type="text/javascript" src="../images/js/width.js"></script>
<script type="text/javascript" src="../images/js/jquery/jquery-1.2.6.js"></script>
<body>
<div class="uploadhead">
	<span <?php if(($view==''||$view=='plugin')&&!$type&&$plugin==''){echo "class=\"current\"";}?>><a href="plugin.php"><?=$common_func[218];?></a></span>
	<span <?php if($type=='active'){echo "class=\"current\"";}?>><a href="plugin.php?type=active"><?=$common_func[219];?></a></span>
	<span <?php if($type=='inactive'){echo "class=\"current\"";}?>><a href="plugin.php?type=inactive"><?=$common_func[220];?></a></span>
	<span <?php if($view=='waittoget'){echo "class=\"current\"";}?>><a href="plugin.php?do=waittoget"><?=$common_func[221];?></a></span>
	<span <?php if($plugin!=''){echo "class=\"current\"";}?>><?=$common_func[222];?></span>
</div>
<!--------------------------插件设置--------------------------------->
<?if($view == ''&& $plugin):?>
<div id="pluginset">
<? if(function_exists(admin_plugin_set)){admin_plugin_set();}else{echo "<span>$common_func[223]</span>";};?>
</div>
<!--------------------------插件列表--------------------------------->
<? else: ?>
<table width="100%" id="pluginlist">
  <tbody>		
    <?echo $result;?>
  </tbody>
</table>
<?endif;?>
</body>
<script>
$(document).ready(function(){
	$("#pluginlist tbody tr:odd").addClass("trcolor");
	$("#pluginlist tbody tr")
		.mouseover(function(){$(this).addClass("trhover")})
		.mouseout(function(){$(this).removeClass("trhover")});
});
hideMes();
</script>
</html>
<?endif;?>