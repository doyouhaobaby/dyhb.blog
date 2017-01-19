<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * 文件：gbk.function.php
        * 说明：gbk与UTF-8编码转换
        * 作者：小牛哥
        * 时间：2010-07-22 0:17
        * 版本：DoYouHaoBaby-blog 告别大二.想念丝瓜 (特别版)
        * 主页：www.doyouhaobaby.com
		* 论坛：bbs.56swun.com
@----------------------------------------@
  ---- 西南民大的天空还是蛮明亮的哈 ----
\**=====copyright (c) DoYouHaoBaby=====**/

/**
  * gbk与UTF-8编码转换（手机版与ajax留言提交）
  *
  * @return string
  */

//编码转换
function GbkToUtf8($string,$type){
  if(is_array($string)){    
   foreach($string as $key=>$value){    
	 if($type=='GBK'){ 
		$string[$key]=GbkToUtf8($value,'GBK');
	 }else{
        $string[$key]=GbkToUtf8($value,'');
	 }
	}
  }else{
	if(is_string($string)){   
	if (function_exists('mb_convert_encoding')){
	  if($type=='GBK'){
		$string=mb_convert_encoding($string,"UTF-8","GBK" );
	}else{
		$string=mb_convert_encoding($string,"GBK","UTF-8");
	  }
	}elseif(function_exists('iconv')){
	if($type=='GBK'){
	  $string=iconv("GBK", "UTF-8",$string);
	}else{
		$string=iconv("UTF-8", "GBK",$string);
	}
  }
}
}
return $string;
}

?>