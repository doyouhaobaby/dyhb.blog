<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * �ļ���function.base.php
        * ˵�������������
        * ���ߣ�Сţ��
        * ʱ�䣺2010-07-22 0:17
        * �汾��DoYouHaoBaby-blog �����.����˿�� (�ر��)
        * ��ҳ��www.doyouhaobaby.com
		* ��̳��bbs.56swun.com
@----------------------------------------@
  ---- ����������ջ����������Ĺ� ----
\**=====copyright (c) DoYouHaoBaby=====**/

/**
  * ������ҵ������������������
  * $type=navbar ������ self_help����ҳ��
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
  * ������Ƴ����������߶���
  * $type=navbar ������ self_help����ҳ��
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
 * �ú����ڲ���е���,���ز��������Ԥ���Ĺ�����
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
 * ִ�й��ڹ����ϵĺ���
 *
 * @param string $thehook
 */
function doHooks($thehook){
	//func_get_args(��ȡ��ǰ�����Ĳ����б���������)
	//array_slice(����ָ������������Ԫ�أ���������)
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