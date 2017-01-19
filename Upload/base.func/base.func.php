<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * �ļ���function.base.php
        * ˵������������
        * ���ߣ�Сţ��
        * ʱ�䣺2010-07-22 0:17
        * �汾��DoYouHaoBaby-blog �����.����˿�� (�ر��)
        * ��ҳ��www.doyouhaobaby.com
		* ��̳��bbs.56swun.com
@----------------------------------------@
  ---- ����������ջ����������Ĺ� ----
\**=====copyright (c) DoYouHaoBaby=====**/
 
/**
  * �����ַ�����ȡ
  *
  * @param string $str
  * @param int $start
  * @param int $len
  * @return $string
  */   
function gbksubstr($str,$start,$len) { 
$strlen=$start+$len; for($i=0;$i<$strlen;$i++) { 
 if(ord(substr($str,$i,1))>0xa0) { 
	@$tmpstr.=substr($str,$i,2); $i++; 
  } 
	else @$tmpstr.=substr($str,$i,1); 
 } 
 return @$tmpstr; 
}

/**
  * ����Ŀ¼
  *
  * @param string $path
  * @return boolean
  */
function make_dir($path) {
	if ( is_dir($path) || strpos($path, '..')!==false ) return false;
	$path = str_replace(DOYOUHAOBABY_ROOT, '', $path);
	$dirs = explode('/', $path);
	$cur_path = DOYOUHAOBABY_ROOT;
	foreach ($dirs as $dir) {
		$cur_path .= $dir.'/';
		if (is_dir($cur_path)) continue;
		@mkdir($cur_path);
		@fopen($cur_path.'index.html','wb');
		@chmod($cur_path, 0777);
	}
}

/**
  * ����ͷ����
  *
  * @param string $Email
  * @return string $ComLogo ͷ��
  */
function gravatar($Email){
    global $dyhb_options;
    $ComLogo="http://www.gravatar.com/avatar/".md5($Email)."?s=$dyhb_options[comment_avatars_size]&amp;d=$dyhb_options[avatar_default]&amp;r=$dyhb_options[avatars_rating]";
    if($dyhb_options['avatar_default']=="mystery"&&!$Email){
    	$ComLogo="http://0.gravatar.com/avatar/a257fba92acca55e85977c9bff627fa2?s=$dyhb_options[comment_avatars_size]&d=http%3A%2F%2F0.gravatar.com%2Favatar%2Fad516503a11cd5ca435acc9bb6523536%3Fs%3D32&r=$dyhb_options[avatars_rating]&forcedefault=1";
    }elseif($dyhb_options['avatar_default']=="blank"&&!$Email){
    	$ComLogo="http://0.gravatar.com/avatar/a257fba92acca55e85977c9bff627fa2?s=$dyhb_options[comment_avatars_size]&amp;d=http%3A%2F%2Flocalhost%2Fwordpress3.0%2Fwp-includes%2Fimages%2Fblank.gif&amp;r=$dyhb_options[avatars_rating]&amp;forcedefault=1";
    }
    return $ComLogo;
}

/**
  * ɾ��Ŀ¼
  *
  * @param string $path
  * @return boolean
  */
function remove_dir($path) {
	if ( substr($path, -1) == '/' ) {
		$path = substr($path, 0, -1);
	}
	if ( $handle = opendir($path) ) {
		while ( false !== ($d = readdir($handle)) ) {
			if ( $d != '.' && $d != '..' ) {
				if ( is_dir($path.'/'.$d) ) {
					remove_dir($path.'/'.$d);
				} else {
					@unlink($path.'/'.$d);
				}
			}
		}
		closedir($handle);
		@rmdir($path);
	}
}

/**
  * ɾ��ָ��Ŀ¼��ȫ���ļ�
  *
  * @param string $path
  * @return boolean
  */
function delete_file($path){   
 $directory=dir($path);   
 while($entry=$directory->read())   {   
 if($entry!="."&&$entry !="..")  {   
   if(is_dir($path."/".$entry))   {   
       delete_file($path."/".$entry);   
    } else{   
         @unlink($path."/".$entry);   
      }   
    }   
   }   
 $directory->close();   
}

/**
  * ����ָ��Ŀ¼��ȫ��һ��Ŀ¼
  *
  * @param string $dir �ļ���·��
  * @return array $a �ļ�Ŀ¼������
  */
function listDir2($dir){ 
 if(is_dir($dir)){ 
 if ($dh = opendir($dir)) { 
   while (($file= readdir($dh)) !== false){ 
    if((is_dir($dir."/".$file)) && $file!="." && $file!=".."){ 
	  $a[]=$file;
	} 
   } 
   closedir($dh); 
  } 
 }
 return $a;
}

/**
  * ���E-mail��ַ
  *
  * @param string $email
  * @return boolean
  */
function dyhb_email($email){
	global $common_func;
	 if (preg_match("/^[_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3}$/",$email)){
		     return true;
	     } 
	 else {
		     DyhbMessage("<font color=\"#FF0000\"><b>$common_func[39]</b></font>");
	     }
 }


/**
  * widget����
  *
  * @param array $sibebar_sort ����
  * @param array $sidebar_show �������ʾ���
  * @param array $_widgetvalue ��Ҫ���������
  * @param array $isadmin �Ƿ�Ϊ������
  * @return string
  */
function widgets($sibebar_sort,$sidebar_show,$_widgetvalue,$isadmin='0'){    
	$WIDGET=array();
	if($sibebar_sort){
    foreach($sibebar_sort as $key=>$value){
     foreach($_widgetvalue as $key2=>$value2){     			 
		if($key==$key2){
			  foreach($sidebar_show as $key3=>$value3){
				 if($isadmin=='0'){//�Ƿ�Ϊ��̨���Ǻ�̨��������,value3=1��ʾǰ̨�������ʾ
				   if($key2==$key3&&$value3=='1'){
			        $WIDGET[$value2]=$value;
				   }
				 }elseif($isadmin='1'){  
					  //��̨ȫ����ʾ�����ڲ���
			          $WIDGET[$value2]=$value;			   
				   }
			  }             
         }              
	  }
    }
	}
	asort($WIDGET);
    foreach($WIDGET as $key=>$value){
	   $show_sidebar.=$key;
    }
	return $show_sidebar;
}

/**
  * �������URL�Ƿ�����߼�
  *
  * @param string $url
  * @return boolean
  */
function isurl($url) {
	global $common_func;
	if($url){
		    if (!preg_match("#^(http|news|https|ftp|ed2k|rtsp|mms)://#", $url)){
			        DyhbMessage("<font color=\"#FF0000\"><b>$common_func[40]</b></font>");
		        }
		    $key = array("\\",' ',"'",'"','*',',','<','>',"\r","\t","\n",'(',')','+',';');
		    foreach($key as $value){
			        if(strpos($url,$value) !== false){ 
				            DyhbMessage("<font color=\"#FF0000\"><b>$common_func[40]</b></font>");
				            break;
			            }
		        }
	    }
     return true;
}

/**
 * ��ȡurl��ַ
 * �������ں�̨���ӻ��������·�������������ط�û���ã����������͹̶���ַ�������˹���
 *
 * @return string
 */
function getUrl(){
    $url = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    if (isset($_SERVER["QUERY_STRING"])) {
        $url = str_replace("?" . $_SERVER["QUERY_STRING"], "", $url);
    }
    return dirname($url);
}

/**
 * Ѱ�����������в�ͬԪ��
 *
 * @param array $array1
 * @param array $array2
 * @return array
 */
function findArray($array1,$array2){
    $r1 = array_diff($array1, $array2);
    $r2 = array_diff($array2, $array1);
    $r = array_merge($r1, $r2);
    return $r;
}

/**
  * �ϲ�����
  *
  * @param array $one
  * @param array $two
  * @return array
  */  
function dyhb_onearray($one,$two){
     $onearray=array_merge($one,$two);
	 return $onearray;
}

/**
 * ʱ��ת������
 * 
 * @param $now
 * @param $datetemp
 * @param $dstr
 * @return string
 */
function ChangeDate($datetemp,$dstr='Y-m-d H:i'){
	global $localdate,$common_func;
	$op = '';
	$sec = $localdate-$datetemp;
	$hover = floor($sec/3600);
	if ($hover == 0){
		$min = floor($sec/60);
		if ( $min == 0) {
			$op = $sec.$common_func[41];
		} else {
			$op = "$min $common_func[42]";
		}
	} elseif ($hover < 24){
		$op = "$common_func[43] {$hover} $common_func[44]";
	} else {
		$op = date($dstr,$datetemp);
	}
	return $op;
}

/**
 * ת��������С��λ
 *
 * @param string $fileSize �ļ���С kb
 * @return unknown
 */
function changeFile($fileSize){
	if($fileSize >= 1073741824){
		$fileSize = round($fileSize / 1073741824  ,2) . 'GB';
	} elseif($fileSize >= 1048576){
		$fileSize = round($fileSize / 1048576 ,2) . 'MB';
	} elseif($fileSize >= 1024){
		$fileSize = round($fileSize / 1024, 2) . 'KB';
	} else{
		$fileSize = $fileSize . $common_func[45];
	}
	return $fileSize;
}

/**
 * mobile��ʾ
 *
 * @param int $type 1���⣬2����
 * @param string $value ��Ҫ�����ʾ������
 * @return string ������Ϣ
 */
function this_is_mobile($value,$type){
	global $dyhb_options,$common_func;
    if($type=='1'){
	    return "<img src='{$dyhb_options[blogurl]}/images/other/mobile.gif' border='none'/>".$value;
	}elseif($type=='2'){
	    return $value."<br><font color='red'>$common_func[46]<a href='$dyhb_options[blogurl]/3g'>$common_func[47]</a>$common_func[48]</font>";
	}
}

/**
 * 404
 *
 * @return unknown
 */
function page_not_found() {
	global $dyhb_options,$common_func;
	//header("HTTP/1.1 404 Not Found\n");
	//header("Content-Type: text/html\n");
	//header("Date: ".get_date('D, d M Y H:i:s',PHP_TIME)." GMT\n");
	//echo<<<DYHB
		//<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL $_SERVER[SCRIPT_NAME] was not //found on this server.</p><p>Additionally, a 404 Not Found error was encountered while trying to use an ErrorDocument to handle the request.</p></body></html>
//DYHB;
    $the_file=DOYOUHAOBABY_ROOT."/view/".$dyhb_options[user_template]."/404.php";
    if(file_exists($the_file)){
        include($the_file);
	}else{
	    DyhbMessage("<font color=red>$common_func[49]</font>",'./');
	}
	exit();
}

/**
  * ϵͳ������Ϣ
  *
  * @param string $message
  * @param string $url
  */
function DyhbMessage($message,$url=''){ 
	if($url=='-1'){
		$url="javascript:history.go(-1);";
	}
	elseif($url==''){
		$url=$_SERVER['HTTP_REFERER'];
	}
	echo<<<DYHB
	<html>
    <head>
DYHB;
	 if($url!='0'&&$url!='-1'){echo "<meta http-equiv=\"refresh\" content=\"3;url=$url\">";}
	  echo<<<DYHB
      <meta http-equiv="Content-Type" content="text/html; charset=gbk" /><title>�ˣ�</title><style type="text/css"><!--html{background:#353535;} body{background: #353535;color:#000;font-family:"΢���ź�","Arial","Bitstream Vera Sans";margin:2em auto 0 auto;padding:1em 2em;}.main {text-align:center; background-color:#fff;margin-top:100px;font-size: 14px; width:70%;padding-top:30px;padding-left:10px;padding-bottom:10px;margin:10px 200px;list-style:none;border:3px solid #000;}a,a:visited{color:#b1b2b1;font-size:12px;}a:hover{color:#7dc6fe;}</style></head><body><div class="main"><p class="message">{$message}</p>
DYHB;
	  if($url!='0'){echo "<p><a href=\"$url\">3���Ӻ󼴽����أ����û�з������ֶ����������</a></p>";}
      echo"</div></body></html>";
      exit();
}

?>