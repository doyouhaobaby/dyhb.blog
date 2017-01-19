<?php
/**================[^_^]================**\
      ---- ��Ϊ���Σ�������Ŀ�� ----
@----------------------------------------@
        * �ļ���c.function.logs.php
        * ˵������־��װ
        * ���ߣ�Сţ��
        * ʱ�䣺2010-05-06 20:22
        * �汾��DoYouHaoBaby-blog �����
        * ��ҳ��www.doyouhaobaby.com
@----------------------------------------@
  ---- ����������ջ����������Ĺ� ----
\**=====copyright (c) DoYouHaoBaby=====**/

class Logs{

//db���ݿ�
var $DB;

 function __construct($newdb){
	$this->DB=$newdb;
 }

 /**
  * �����־
  */
 function AddLog($SaveLogDate){
	add_sql($SaveLogDate,'blog');
	$blog_id=$this->DB->insert_id();
	return $blog_id;
 }

/**
 * ������־
 */
function UpdateLog($SaveLogDate,$blog_id){
	update_sql($SaveLogDate,'blog_id',$blog_id,'blog');
}

/**
 * ��ȡ��־
 *
 * @param string $condition sql����
 * @param int $start ��ѯ��ʼ
 * @param int $end ��ѯ����
 * @param int $show 0Ϊ�ݸ壬1Ϊ������־
 * @param int $ispage 1Ϊҳ�棬0Ϊ��ͨ��־
 */
function GetLog($condition = '',$start='',$end='',$show='',$ispage=''){
   global $_Cools,$_Tags,$_Sorts,$isAdmin,$dyhb,$dyhb_options,$IsCacheLog,$IsMobile,$common_func;
   if(CheckPermission("seehiddenentry","����һƪ������־��",'0')&&!$isAdmin&&$IsCacheLog==false){
   	   $isshow="";
   }else{
	   //���ɻ����ļ�������Ҫȡ��������־
       if($IsCacheLog==true){
           $show="1";
       }
   	   if($isAdmin&&$ispage==''){
		  $isshow="`isshow`=$show" ;
	   }else{
   	      $isshow="`isshow`=$show and" ;
	   }
   }
   $ispage=$ispage!=''?"`ispage`=$ispage":'';
   $limit = ($start==''&&$end=='')?'':"LIMIT $start,$end";
   $sql = "SELECT * FROM ".DB_PREFIX."blog  WHERE $isshow $ispage  $condition $limit";
   $log=$this->DB->gettworow($sql);
   $i=0;
   if($log){
     foreach($log as $value){
	   //��̬��ר��
	  if( $dyhb_options[allowed_make_html]=='1' ){
	     //��ҳ����
	     $log[$i]['newpagenum']=count( explode('[newpage]',stripslashes($value['content'])) );
	  }
	   //�Ƿ��Զ�ժҪ
	   if($dyhb_options['is_auto_cut']=='1'){
           $log[$i]['content']=gbksubstr(strip_tags(str_replace('[newpage]','',$value['content'])),'0',$dyhb_options['auto_cut_num']);
	   }else{
		   $log[$i]['content']=BreakLog(stripslashes($value['content']));
	   }
	   if($value[ismobile]=='1'){
		       $log[$i]['content']=this_is_mobile($log[$i]['content'],'2');
	   }  
      //����
	  $log[$i]['title']=$value['title']?stripslashes($value['title']):$common_func[96];
	  //����
	  $blog_id=$log[$i][blog_id];
	  if($log[$i]['password']&&!CheckPermission("seeallprotectedentry","����һƪ������־��",'0')&&!$isAdmin){
	  	   $log[$i]['content']="[$common_func[97]]";
	  }
      $log[$i]['author']=stripslashes($value['author']);
	  //�û�
	  if($value['user_id']){
	    $b=$_Cools->GetBloggerInfo($value['user_id']);
	  }
	  $log[$i]['user']=$b['nikename']?$b['nikename']:($b['username']?$b['username']:$common_func[98]);
	  $log[$i]['reallyuser']=$b['username'];
	  $log[$i]['email']=$b['email'];
	  if($isAdmin){
		$log[$i]['tags']=MakeTag($_Tags->GetTag($value['blog_id'],'',''),'');
	  }else{
        $log[$i]['tags']=MakeTag($_Tags->GetTag($value['blog_id'],'',''),'user');
	  }
	  //����
	  if($value['sort_id']!='-1'){
	    $ts=$_Sorts->GetIdSort($value['sort_id']);
		if($ts){
	       $Loglist_parsort=$_Sorts->GetThreePar($ts);
	       $log[$i]['sort']=$Loglist_parsort['sort'];
		}
	  }else{
	  	$log[$i]['sort']=array(array('sort_id'=>'-1','name'=>$common_func[99],'urlname'=>'default','now'=>'1'));
	  }
	  $log[$i]['name']=$value['sort_id']=='-1'?$common_func[99]:$ts['name'];
	  $log[$i]['sorturlname']=$ts['urlname'];
	  $log[$i]['logo']=$value['sort_id']=='-1'||$ts['logo']==''?"images/other/icon_sort.gif":$ts['logo'];
	  $i++;
   }
   return $log;
}
}


/**
 * ������־
 *
 */
function GetPreLog($blog_id){
	$prelog=$this->DB->getonerow("SELECT  *FROM `".DB_PREFIX."blog` WHERE blog_id>$blog_id and `ispage`='0' and `isshow`='1' ORDER BY blog_id ASC LIMIT 1");
	if($prelog){
		$prelog['content']=stripslashes($prelog['content']);
		$prelog['title']=stripslashes($prelog['title']);
		if($prelog[ismobile]=='1'){
			$prelog['title']=this_is_mobile($prelog['title'],'1');
		}
	}
    return $prelog;
}

function GetNextLog($blog_id){
	$nextlog=$this->DB->getonerow("SELECT blog_id,title FROM `".DB_PREFIX."blog` WHERE blog_id<$blog_id and `ispage`='0' and `isshow`='1' ORDER BY blog_id DESC LIMIT 1");
	if($nextlog){
		$nextlog['content']=stripslashes($nextlog['content']);
		$nextlog['title']=stripslashes($nextlog['title']);
		if($nextlog[ismobile]=='1'){
			$nextlog['title']=this_is_mobile($nextlog['title'],'1');
		}
	}
    return $nextlog;
}

/**
 * �����־
 */
function GetRelateLog($end,$sort_id){
  $relatelog=$this->DB->gettworow("SELECT *
  FROM `".DB_PREFIX."blog` AS t1 JOIN (SELECT ROUND(RAND() *
  (SELECT MAX(blog_id) FROM `".DB_PREFIX."blog`)) AS blog_id) AS t2
  WHERE t1.blog_id >= t2.blog_id and t1.sort_id=$sort_id  and t1.ispage=0 and t1.isshow=1"."
  ORDER BY t1.blog_id ASC LIMIT 0,$end");
  if($relatelog){
  $i=0;
  foreach($relatelog as $value){
    $relatelog[$i]['title']=stripslashes($value['title']);
	$relatelog[$i]['content']=stripslashes($value['content']);
	if($value[ismobile]=='1'){
			$relatelog[$i]['title']=this_is_mobile($relatelog[$i]['title'],'1');
	}
	$relatelog[$i]['author']=stripslashes($value['author']);
    $i++;
  }}
  return $relatelog;
}

/**
 * �����־
 */
function GetRandLog($start,$end){
  global $IsMobile;
  $randlog=$this->DB->gettworow("SELECT *
  FROM `".DB_PREFIX."blog` AS t1 JOIN (SELECT ROUND(RAND() *
 (SELECT MAX(blog_id) FROM `".DB_PREFIX."blog`)) AS blog_id) AS t2
  WHERE t1.blog_id >= t2.blog_id and t1.ispage=0 and t1.isshow=1
  ORDER BY t1.blog_id ASC LIMIT $start,$end");
  if($randlog){
  $i=0;
  foreach($randlog as $value){
	$randlog[$i]['title']=stripslashes($value['title']);
	$randlog[$i]['content']=stripslashes($value['content']);
	if($value[ismobile]=='1'){
			$randlog[$i]['title']=this_is_mobile($randlog[$i]['title'],'1');
	}
	$randlog[$i]['author']=stripslashes($value['author']);
    $i++;
  }}
  return $randlog;
}

/**
 * ������־
 */
function GetOneLog($BlogId){
	global $Common_url,$_Sorts,$dyhb_options,$NewPagination,$_Cools,$newpage,$_Tags,$IsMobile,$common_func;

	/** ������Ҫ��α��̬��ԭ���ж�һ�� */
	$BlogId=$BlogId?$BlogId:$Common_url;

	if(is_numeric($BlogId)){
	   $sql_c="`blog_id`='$BlogId'";
	}else{
	   $sql_c="`title`='$BlogId' or `urlname`='$BlogId'";
	}
	$onelog=$this->DB->getonerow("select *from `".DB_PREFIX."blog` where $sql_c");

	/** �ж��Ƿ���� */
	if(!$onelog){
		  //DyhbMessage('�����ڸ�������','0');
		  page_not_found();
	}else{ 
	     $BlogId=$onelog['blog_id'];  
		 $img_ulr_pre=$IsMobile?"../":'';
		 $onelog['title']=stripslashes($onelog['title']);
		 $onelog['content']=stripslashes($onelog['content']);

		 //��̬��ר��
	     if( $dyhb_options[allowed_make_html]=='1' ){
	         //��ҳ����
	         $onelog['newpagenum']=count( explode('[newpage]',$onelog['content']) );
			 $onelog[commentpage]='';
	         if( $dyhb_options[log_comment_num] ){
	              $onelog[commentpage]=$commentpage=ceil(round( $onelog[commentnum]/$dyhb_options[log_comment_num] ,'1'));
	         }
	         if( $cnelog[commentpage] =='0' || $onelog[commentpage]=='' ) $onelog[commentpage]='1';
	     }

	     //���
         $this->AddView($BlogId);
	     //��־���Ȩ��
	     CheckPermission('visit',$common_permission[51]);
	     //������־Ȩ��
         if($onelog['isshow']=='0'){
	           CheckPermission("seehiddenentry",$common_permission[56]);
         }
         /** ��־���� */
	     if($onelog['password']){
	          if(!CheckPermission("seeallprotectedentry",$common_permission[57],'0')){
		            $this->AuthPassword($onelog['password'], get_cookie("showlog{$BlogId}"), get_argpost('showlogpass'), $BlogId);
		      }
         }
         /** ��־���ദ��,$Show[sort]���ڵ�������α��̬���ݴ����õ� */
         if($onelog['sort_id']!='-1'){
              $_thesort=$_Sorts->GetIdSort($onelog['sort_id']);
              $onelog['name']=$_thesort['name'];
	          $The_parsort=$_Sorts->GetThreePar($_thesort);
	          $onelog['sort']=$The_parsort['sort'];
         }else{
              $onelog['name']=$common_func[99];
              $onelog['sort']=array(array('sort_id'=>'-1','name'=>$common_func[99],'urlname'=>'default','now'=>'1'));
         }

         /** ��־tags */
		 $forwho=$IsMobile===true?"":"user";
		 $onelog['tag']=$_Tags->GetTag($BlogId,'','');
         $onelog['tags']=MakeTag($onelog[tag],$forwho);

         /** user�û���Ϣ���� */
         $B=$_Cools->GetBloggerInfo($onelog['user_id']);
         $onelog['reallyuser']=$B['username'];//����α��̬
         $onelog['user']=$B['nickname']?$B['nickname']:$B['username'];
         if(!$onelog['user']){
	           $onelog['user']=$common_func[98];
         }
         $onelog['email']=$B['email'];
	}

	/** ������־ **/
	return $onelog;
}

/**
 * ɾ����־
 */
function DeleteLog($blog_id){
	$this->DB->query("delete from `".DB_PREFIX."blog` where `blog_id`=$blog_id");
	//�ƶ����ۣ�������תΪ���԰�����
	$this->DB->query("update `".DB_PREFIX."comment` set `blog_id`='0' where `blog_id`='$blog_id'");
    //��ǩ
	$this->DB->query("UPDATE ".DB_PREFIX."tag SET blog_id= REPLACE(blog_id,',$blog_id,',',') WHERE blog_id LIKE '%".$blog_id."%' ");
	$this->DB->query("DELETE FROM ".DB_PREFIX."tag WHERE blog_id=',' ");
}

/**
 * ������־
 *
 * @param array $LogId ��������־ID
 * @param array $ActionDate �����ľ�����Ϣ
 * @param $act ����
 * return none;
 */
function LogAction($LogId,$Act){
  foreach($LogId as $value){
	switch($Act){
     case 'del':$this->DeleteLog($value);break;
	 case 'caogao':$this->DB->query("update `".DB_PREFIX."blog` set `isshow`=0 where `blog_id`=$value");break;
	 case 'caogao':$this->DB->query("update `".DB_PREFIX."blog` set `isshow`=0 where `blog_id`=$value");break;
	 case 'uncaogao':$this->DB->query("update `".DB_PREFIX."blog` set `isshow`=1 where `blog_id`=$value");break;
	 case 'top':$this->DB->query("update `".DB_PREFIX."blog` set `istop`=1 where `blog_id`=$value");break;
	 case 'untop':$this->DB->query("update `".DB_PREFIX."blog` set `istop`=0 where `blog_id`=$value"); break;
	 case 'lock':$this->DB->query("update `".DB_PREFIX."blog` set `islock`=1 where `blog_id`=$value");break;
	 case 'unlock':$this->DB->query("update `".DB_PREFIX."blog` set `islock`=0 where `blog_id`=$value");break;
	 case 'page':$this->DB->query("update `".DB_PREFIX."blog` set `ispage`=1 where `blog_id`=$value");break;
	 case 'unpage': $this->DB->query("update `".DB_PREFIX."blog` set `ispage`=0 where `blog_id`=$value"); break;
	}
  }
}

/**
  * ����ͳ��
  */
 function AddView($blog_id){
     $this->DB->query("update `".DB_PREFIX."blog` set viewnum=viewnum+1 where `blog_id`=".$blog_id."");
  }

/**
 * ������־������֤
 *
 */
function AuthPassword($blogpassword, $cookiepassword, $loginpassword,$blogid) {
	    global $dyhb_premission,$common_func;
		$pwd = $cookiepassword ? $cookiepassword : $blogpassword;
		if ($pwd !== addslashes($loginpassword)) {
			if(!$_POST){
			  echo<<<DYHB
			     <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head><title>$common_func[100]</title><meta http-equiv="content-type" content="application/xhtml+xml; charset=gbk" /><link rel="stylesheet" href="images/common.css" type="text/css" /></head> <body><div id="main"><h1>$common_func[100]</h1><p>$common_func[101]($dyhb_premission[gpname])$common_func[102]</p><form action="" method="post"><div><input type='password' name='showlogpass'><br><input type="submit"  value='$common_func[103]' > </form><a href="./">$common_func[104]</a><div></body></html>
DYHB;
            }else{
				 if(!$loginpassword){
				     DyhbMessage($common_func[105],'');
				 }else{
                     DyhbMessage($common_func[106],'');
				 }
            }
            //if ($cookiepassword) {
			    //setcookie('showlog' . $blogid, ' ', time() - 31536000);
		    //}
		    exit;
	    }else {
			 setcookie('showlog' . $blogid, $loginpassword);
		}
	}
}

?>