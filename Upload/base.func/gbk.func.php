<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * �ļ���gbk.function.php
        * ˵����gbk��UTF-8����ת��
        * ���ߣ�Сţ��
        * ʱ�䣺2010-07-22 0:17
        * �汾��DoYouHaoBaby-blog �����.����˿�� (�ر��)
        * ��ҳ��www.doyouhaobaby.com
		* ��̳��bbs.56swun.com
@----------------------------------------@
  ---- ����������ջ����������Ĺ� ----
\**=====copyright (c) DoYouHaoBaby=====**/

/**
  * gbk��UTF-8����ת�����ֻ�����ajax�����ύ��
  *
  * @return string
  */

//����ת��
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