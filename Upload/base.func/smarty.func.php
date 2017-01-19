<?php
/**
 * 清除模板中的注释,伪静态规则,静态化
 *
 */
function SmartyUrl(){
    global $dyhb_options;

    //清除模板注释
    $output = str_replace(array('?>','<?php',"<?php\r\n?>","<!--{","}-->"),array('','','','',''),ob_get_contents());
   //是否开启静态化
   if($dyhb_options[allowed_make_html]=='1'&&$dyhb_options['permalink_structure']!="default"){  
	   //方便以后升级
       $match=array('/(\.\/|index.php)?\?page=([0-9]+)/i',
		            '/\.html\?newpage=([0-9]+)&page=([0-9])?/i');
       $matchreplace=array('index-\2.html',
		                   '-\1-\2.html');
	   $output = preg_replace($match, $matchreplace, $output);
   }
    
   //页面输出
   obclean();
   echo $output;
   exit;
} 
?>