<?php
/**================[^_^]================**\
      ---- 因为有梦，所以有目标 ----
@----------------------------------------@
        * 文件：c.function.logs.php
        * 说明：日志封装
        * 作者：小牛哥
        * 时间：2010-05-06 20:22
        * 版本：DoYouHaoBaby-blog 概念版
        * 主页：www.doyouhaobaby.com
@----------------------------------------@
  ---- 西南民大的天空还是蛮明亮的哈 ----
\**=====copyright (c) DoYouHaoBaby=====**/

class Logs{

//db数据库
var $DB;

 function __construct($newdb){
	$this->DB=$newdb;
 }

 /**
  * 添加日志
  */
 function AddLog($SaveLogDate){
	add_sql($SaveLogDate,'blog');
	$blog_id=$this->DB->insert_id();
	return $blog_id;
 }

/**
 * 更新日志
 */
function UpdateLog($SaveLogDate,$blog_id){
	update_sql($SaveLogDate,'blog_id',$blog_id,'blog');
}

/**
 * 获取日志
 *
 * @param string $condition sql条件
 * @param int $start 查询开始
 * @param int $end 查询数量
 * @param int $show 0为草稿，1为公开日志
 * @param int $ispage 1为页面，0为普通日志
 */
function GetLog($condition = '',$start='',$end='',$show='',$ispage=''){
   global $_Cools,$_Tags,$_Sorts,$isAdmin,$dyhb,$dyhb_options,$IsCacheLog,$IsMobile,$common_func;
   if(CheckPermission("seehiddenentry","这是一篇隐藏日志！",'0')&&!$isAdmin&&$IsCacheLog==false){
   	   $isshow="";
   }else{
	   //生成缓存文件，则不需要取出隐藏日志
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
	   //静态化专用
	  if( $dyhb_options[allowed_make_html]=='1' ){
	     //分页数量
	     $log[$i]['newpagenum']=count( explode('[newpage]',stripslashes($value['content'])) );
	  }
	   //是否自动摘要
	   if($dyhb_options['is_auto_cut']=='1'){
           $log[$i]['content']=gbksubstr(strip_tags(str_replace('[newpage]','',$value['content'])),'0',$dyhb_options['auto_cut_num']);
	   }else{
		   $log[$i]['content']=BreakLog(stripslashes($value['content']));
	   }
	   if($value[ismobile]=='1'){
		       $log[$i]['content']=this_is_mobile($log[$i]['content'],'2');
	   }  
      //标题
	  $log[$i]['title']=$value['title']?stripslashes($value['title']):$common_func[96];
	  //密码
	  $blog_id=$log[$i][blog_id];
	  if($log[$i]['password']&&!CheckPermission("seeallprotectedentry","这是一篇隐藏日志！",'0')&&!$isAdmin){
	  	   $log[$i]['content']="[$common_func[97]]";
	  }
      $log[$i]['author']=stripslashes($value['author']);
	  //用户
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
	  //分类
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
 * 相邻日志
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
 * 相关日志
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
 * 随机日志
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
 * 单条日志
 */
function GetOneLog($BlogId){
	global $Common_url,$_Sorts,$dyhb_options,$NewPagination,$_Cools,$newpage,$_Tags,$IsMobile,$common_func;

	/** 这里主要是伪静态的原因，判断一下 */
	$BlogId=$BlogId?$BlogId:$Common_url;

	if(is_numeric($BlogId)){
	   $sql_c="`blog_id`='$BlogId'";
	}else{
	   $sql_c="`title`='$BlogId' or `urlname`='$BlogId'";
	}
	$onelog=$this->DB->getonerow("select *from `".DB_PREFIX."blog` where $sql_c");

	/** 判断是否存在 */
	if(!$onelog){
		  //DyhbMessage('不存在该条数据','0');
		  page_not_found();
	}else{ 
	     $BlogId=$onelog['blog_id'];  
		 $img_ulr_pre=$IsMobile?"../":'';
		 $onelog['title']=stripslashes($onelog['title']);
		 $onelog['content']=stripslashes($onelog['content']);

		 //静态化专用
	     if( $dyhb_options[allowed_make_html]=='1' ){
	         //分页数量
	         $onelog['newpagenum']=count( explode('[newpage]',$onelog['content']) );
			 $onelog[commentpage]='';
	         if( $dyhb_options[log_comment_num] ){
	              $onelog[commentpage]=$commentpage=ceil(round( $onelog[commentnum]/$dyhb_options[log_comment_num] ,'1'));
	         }
	         if( $cnelog[commentpage] =='0' || $onelog[commentpage]=='' ) $onelog[commentpage]='1';
	     }

	     //点击
         $this->AddView($BlogId);
	     //日志浏览权限
	     CheckPermission('visit',$common_permission[51]);
	     //隐藏日志权限
         if($onelog['isshow']=='0'){
	           CheckPermission("seehiddenentry",$common_permission[56]);
         }
         /** 日志加密 */
	     if($onelog['password']){
	          if(!CheckPermission("seeallprotectedentry",$common_permission[57],'0')){
		            $this->AuthPassword($onelog['password'], get_cookie("showlog{$BlogId}"), get_argpost('showlogpass'), $BlogId);
		      }
         }
         /** 日志分类处理,$Show[sort]用于导航条和伪静态数据处理用的 */
         if($onelog['sort_id']!='-1'){
              $_thesort=$_Sorts->GetIdSort($onelog['sort_id']);
              $onelog['name']=$_thesort['name'];
	          $The_parsort=$_Sorts->GetThreePar($_thesort);
	          $onelog['sort']=$The_parsort['sort'];
         }else{
              $onelog['name']=$common_func[99];
              $onelog['sort']=array(array('sort_id'=>'-1','name'=>$common_func[99],'urlname'=>'default','now'=>'1'));
         }

         /** 日志tags */
		 $forwho=$IsMobile===true?"":"user";
		 $onelog['tag']=$_Tags->GetTag($BlogId,'','');
         $onelog['tags']=MakeTag($onelog[tag],$forwho);

         /** user用户信息处理 */
         $B=$_Cools->GetBloggerInfo($onelog['user_id']);
         $onelog['reallyuser']=$B['username'];//用于伪静态
         $onelog['user']=$B['nickname']?$B['nickname']:$B['username'];
         if(!$onelog['user']){
	           $onelog['user']=$common_func[98];
         }
         $onelog['email']=$B['email'];
	}

	/** 返回日志 **/
	return $onelog;
}

/**
 * 删除日志
 */
function DeleteLog($blog_id){
	$this->DB->query("delete from `".DB_PREFIX."blog` where `blog_id`=$blog_id");
	//移动评论，将评论转为留言板内容
	$this->DB->query("update `".DB_PREFIX."comment` set `blog_id`='0' where `blog_id`='$blog_id'");
    //标签
	$this->DB->query("UPDATE ".DB_PREFIX."tag SET blog_id= REPLACE(blog_id,',$blog_id,',',') WHERE blog_id LIKE '%".$blog_id."%' ");
	$this->DB->query("DELETE FROM ".DB_PREFIX."tag WHERE blog_id=',' ");
}

/**
 * 操作日志
 *
 * @param array $LogId 操作的日志ID
 * @param array $ActionDate 操作的具体信息
 * @param $act 操作
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
  * 增加统计
  */
 function AddView($blog_id){
     $this->DB->query("update `".DB_PREFIX."blog` set viewnum=viewnum+1 where `blog_id`=".$blog_id."");
  }

/**
 * 加密日志访问验证
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