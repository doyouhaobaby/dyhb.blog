<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * 文件：function.base.php
        * 说明：插件处理函数
        * 作者：小牛哥
        * 时间：2010-07-22 0:17
        * 版本：DoYouHaoBaby-blog 告别大二.想念丝瓜 (特别版)
        * 主页：www.doyouhaobaby.com
		* 论坛：bbs.56swun.com
@----------------------------------------@
  ---- 西南民大的天空还是蛮明亮的哈 ----
\**=====copyright (c) DoYouHaoBaby=====**/

/**
  * 将插件挂到导航条或者允许独立
  * $type=navbar 导航条 self_help独立页面
  *
  */
function addPlugin($name,$type){
   global $dyhb_options,$_Cools;
   if($type=='navbar'){
     $a=unserialize($dyhb_options[plugin_navbar]);
     if (!@in_array($name,$a)){
		$a[] = $name;
	 }
     $_Cools->UpdateCools(serialize($a),'87');
   }
   elseif($type=='self_help'){
     $b=unserialize($dyhb_options[plugin_self_help]);
     if (!@in_array($name,$b)){
		$b[] = $name;
	 }
     $_Cools->UpdateCools(serialize($b),'88');
   }
   return true;
}

/**
  * 将插件移除导航条或者独立
  * $type=navbar 导航条 self_help独立页面
  *
  */
function delPlugin($name,$type){
   global $dyhb_options,$_Cools;
   if($type=='navbar'){
   	  $a=unserialize($dyhb_options[plugin_navbar]);
	  if($a){
      foreach($a as $key=>$value){
         if (@in_array($name, $a)&&$name==$value){
		    unset($a[$key]);
	   }
     }}
     $_Cools->UpdateCools(serialize($a),'87');
   }
   elseif($type=='self_help'){
   	  $b=unserialize($dyhb_options[plugin_self_help]);
	  if($b){
      foreach($b as $key=>$value){
         if (@in_array($name, $b)&&$name==$value){
		    unset($b[$key]);
	   }
     }}
     $_Cools->UpdateCools(serialize($b),'88');
   }
   return true;
}

/**
 * 该函数在插件中调用,挂载插件函数到预留的钩子上
 *
 * @param string $hook
 * @return boolearn
 */
function addHooks($thehook, $userFunction){
	global $DyhbHooks;
	if (!@in_array($userFunction, $DyhbHooks[$thehook])){
		$DyhbHooks[$thehook][] = $userFunction;
	}
	return true;
}

/**
 * 执行挂在钩子上的函数
 *
 * @param string $thehook
 */
function doHooks($thehook){
	//func_get_args(获取当前函数的参数列表，返回数组)
	//array_slice(返回指定数量的数据元素，返回数组)
	global $DyhbHooks;
	$args = array_slice(func_get_args(), 1);
	//$numargs = func_num_args();
	if (isset($DyhbHooks[$thehook])){
		foreach ($DyhbHooks[$thehook] as $userfunction){
			call_user_func_array($userfunction, $args);
		}
	}
}


?>