<?php
/**
 * ���ģ���е�ע��,α��̬����,��̬��
 *
 */
function SmartyUrl(){
    global $dyhb_options;

    //���ģ��ע��
    $output = str_replace(array('?>','<?php',"<?php\r\n?>","<!--{","}-->"),array('','','','',''),ob_get_contents());
   //�Ƿ�����̬��
   if($dyhb_options[allowed_make_html]=='1'&&$dyhb_options['permalink_structure']!="default"){  
	   //�����Ժ�����
       $match=array('/(\.\/|index.php)?\?page=([0-9]+)/i',
		            '/\.html\?newpage=([0-9]+)&page=([0-9])?/i');
       $matchreplace=array('index-\2.html',
		                   '-\1-\2.html');
	   $output = preg_replace($match, $matchreplace, $output);
   }
    
   //ҳ�����
   obclean();
   echo $output;
   exit;
} 
?>