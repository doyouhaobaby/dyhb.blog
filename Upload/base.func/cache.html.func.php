<?php
/**================[^_^]================**\
      ---- 因为有梦，所以有目标 ----
@----------------------------------------@
        * 文件：cache.html.function.php
        * 说明：缓存
        * 作者：小牛哥
        * 时间：2010-05-06 20:22
        * 版本：DoYouHaoBaby-blog 概念版
        * 主页：www.doyouhaobaby.com
@----------------------------------------@
  ---- 西南民大的天空还是蛮明亮的哈 ----
\**=====copyright (c) DoYouHaoBaby=====**/

/**
 * 静态化生成函数
 */
function MakeHtmk($wait,$result,$message,$type=''){
 	 global $dyhb_options,$common_func;
 	 //生成和待处理的路径
 	 $Wait_to_html=$dyhb_options[blogurl]."/".$wait;
	 if( !$type ){
          $Result_to_html="../".$result;
	 }else{
		   switch( $type ){
		      case "post_comment":
			  case "guestbook":
			  $Result_to_html=$result;
			  break;
		   }
	 }
   	 $Htmldate=file_get_contents($Wait_to_html);
   	 $html_write=@fopen($Result_to_html,"w");
   	 if(@fwrite($html_write,$Htmldate)){
		 return "{$common_func[29]}$message <a target='_blank' href='$Result_to_html'>$Result_to_html</a> $common_func[30]<br/>";
	 }else {
		 return "{$common_func[29]}$message $Result_to_html $common_func[31]<br/>";
	 }
	 @fclose($html_write);
}

/**
 * 生成导航条
 */
function MakePagenav($Htmltype,$type=''){
	 global $common_func;
     //导航条URL
	 if($Htmltype!='index'){
   	          $Wait_to_html="index.php?action=$Htmltype";
	 }else{
	          $Wait_to_html="index.php";
	 }
   	 $Result_to_html="$Htmltype.html";
   	 $Return_message=MakeHtmk($Wait_to_html,$Result_to_html,$common_func[32],$type); 
     return $Return_message;
}

/**
 * 生成单个日志
 */
function MakePostHtml($onepost,$type=''){
	 global $DB,$dyhb_options,$common_func;
     //伪静态URL衔接直接用于真静态
	 $onepost[commentpage]='';
	 if( $dyhb_options[log_comment_num] ){
	     $onepost[commentpage]=$commentpage=ceil(round( $onepost[commentnum]/$dyhb_options[log_comment_num] ,'1'));
	 }
	 if( $cnepost[commentpage] =='0' || $onepost[commentpage]=='' ) $onepost[commentpage]='1';

     //返回消息
	 $result='';

	 $the_posturl=_showlog_posturl($onepost);

	 /** /在末尾的位置，获取目录，并且创建，存放html文件 */
	 $last_place=strrpos($the_posturl,'/');
	 $DIR=substr_replace($the_posturl,'',$last_place);
	 make_dir(DOYOUHAOBABY_ROOT."/$DIR");

     //生成一级静态化文件
	 $Wait_to_html="index.php?p=$onepost[blog_id]";
     $Result_to_html=$the_posturl;
     $result.=MakeHtmk($Wait_to_html,$Result_to_html,$common_func[33],$type);

     //子集分页
	 if( $onepost[newpagenum]>1 || $onepost[commentpage]>1 ){
     for($i=1;$i<=$onepost[newpagenum];$i++){
	    for($j=1;$j<=$onepost[commentpage];$j++){
			//生成一级静态化文件
	        $Wait_to_html="index.php?p=$onepost[blog_id]&newpage=$i&page=$j";
			$the_i_j=_showlog_posturl($onepost,array($i,$j));
            $result.=MakeHtmk($Wait_to_html,$the_i_j,$common_func[34],$type);
		  }
	    }
	 }
	 return $result;
}

/**
 * 生成单个分类
 */
function MakeSortHtml($onesort){
	 global $DB,$dyhb_options,$count2,$_Sorts,$count,$common_func;

     //伪静态URL衔接直接用于真静态
	 if($onesort){
        $Loglist_parsort=$_Sorts->GetThreePar($onesort);//父分类
	    $theurl=get_rewrite_sort($Loglist_parsort[sort]);
	 }else{
	    $theurl="category/default/";
		$onesort['sort_id']="-1";
	 }
	 //返回消息
	 $result='';
     
	 /** /在末尾的位置，获取目录，并且创建，存放html文件 */
	 $last_place=strrpos($theurl,'/');
	 $DIR=substr_replace($theurl,'',$last_place);
	 make_dir(DOYOUHAOBABY_ROOT."/$DIR");

	 //生成静态首页
	 if( $count2=='1' ){
	     $Wait_to_html="index.php?s=$onesort[sort_id]&page=1";
   	     $Result_to_html="{$theurl}index.html";
		 $Result_to_html2="{$theurl}index-1.html";
   	     $Return_message.=MakeHtmk($Wait_to_html,$Result_to_html,$common_func[35]);
		 $Return_message.=MakeHtmk($Wait_to_html,$Result_to_html2,$common_func[36]);
	 }
	 
	 if($count2>1){
	    //生成静态化列表文件
	    $Wait_to_html="index.php?s=$onesort[sort_id]&page=$count2";
   	    $Result_to_html="{$theurl}index-{$count2}.html";
   	    $Return_message.=MakeHtmk($Wait_to_html,$Result_to_html,$common_func[36]);
	 }
	 
	 return $Return_message;
}

/**
 * 写入缓存
 */
function MakeCache($cachedateline,$cacheFile){
	global $common_func;
    $cacheDir = DOYOUHAOBABY_ROOT.'/width/cache/';
	$cacheFile=$cacheDir.'c_'.$cacheFile.'.php';
    if(!is_dir($cacheDir)) {
			@ mkdir($cacheDir, 0777);
		}
	if($fp = @ fopen($cacheFile, "wb")){
	@ $fw=fwrite($fp,serialize($cachedateline));
	fclose($fp);
	@ chmod($cacheFile, 0777);
	}else{
        DyhbMessage($common_func[37]);
	}
}

/**
 * 读取缓存
 */
function ReadCache($cacheFile){
	 $cacheDir = DOYOUHAOBABY_ROOT.'/width/cache/';
	 $cacheFile=$cacheDir.'c_'.$cacheFile.'.php';
	 if(@ $fp = fopen($cacheFile, 'r')){
	 @ $data = fread($fp,filesize($cacheFile));
			fclose($fp);
	 }
	 return @unserialize($data);
}

//更新所有缓存
function UpdAllCache($mes,$url){
	global $common_func;
	CacheOptions();CacheTag; CacheBlog();CacheNewLog();CacheBlogger();CacheNewMusic();CacheNewPhoto();
	CacheHotLog(); CacheCommentLog();CacheYearHotLog(); CacheMouthHotLog();CacheRandLog();
    CacheSideTao();CacheNewComment(); CacheNewGuestbook();CacheLinks();
    CacheSorts();CachethreeSorts();CacheRecord();CacheCmsNew();CacheCmsSort();CachePageNav();
	CachePhotoSorts();CacheMp3Sorts();CachePluginNav();CachePluginList();CacheFlashLog();
	if($url=='0'){
       DyhbMessage($mes,'0');
	}else{
	   DyhbMessage($mes.$common_func[38],$url);
	}
}

//程序配置
function CacheOptions(){
	global $DB;
	$dyhb_options=array();
    $i=0;
    foreach(dyhb_stripslashes($DB->gettworow("SELECT *FROM `".DB_PREFIX."option`")) as $value){
           $dyhb_options[$value['name']]=$value['value'];
           $i++;
    }
	MakeCache($dyhb_options,'dyhb_options');
}

//pagenav
function CachePageNav(){
	global $_Logs,$dyhb_options;
	$_PageNav=ThinLog($_Logs->GetLog("order by `dateline` desc,`istop`desc",'','',$isshow='1',$ispage='1'),'');
	MakeCache($_PageNav,'side_pagenav');
}

//导航条插件
function CachePluginNav(){
	global $dyhb_options,$DB;
	 $plugin_navbar=array();
	 $i=0;
	 if(unserialize($dyhb_options[plugin_navbar])){
	    foreach(unserialize($dyhb_options[plugin_navbar]) as $value){
			$plugin_navbar[$i]['dir']=$value;
			$r=$DB->getonerow("select *from `".DB_PREFIX."plugin` where `dir`='$value' and `active`='1'");
			$plugin_navbar[$i]['name']=$r['name'];
			$i++;
		}
	 };
     MakeCache($plugin_navbar,'plugin_navbar');
}

//所有激活的插件
function CachePluginList(){
   global $DB;
   $PluginList=$DB->gettworow("select *from `".DB_PREFIX."plugin` where `active`='1'");
   MakeCache($PluginList,'plugin_list');
}

//最新日志
function CacheNewLog(){
	global $_Logs,$dyhb_options,$IsCacheLog;
	$IsCacheLog=true;
	$_sideNewlog=ThinLog($_Logs->GetLog("order by `dateline` desc,`istop`desc",0,$dyhb_options['new_log_num'],$isshow='1',$ispage='0'),'');
	MakeCache($_sideNewlog,'side_newlog');
}

//热门日志
function CacheHotLog(){
	global $_Logs,$dyhb_options,$IsCacheLog;
	$IsCacheLog=true;
	$_sideHotlog=ThinLog($_Logs->GetLog("order by `viewnum` desc,`istop` desc,`dateline` desc",0,$dyhb_options['hot_log_num'],$isshow='1',$ispage='0'),'');
	MakeCache($_sideHotlog,'side_hotlog');
}

//幻灯片日志
function CacheFlashLog(){
	global $DB,$dyhb_options;
	$_Flashlog=$DB->gettworow("select *from ".DB_PREFIX."blog where isshow=1 and  ispage=0 and password='' and thumb!='' order by `istop` desc,`dateline` desc limit 0,$dyhb_options[flash_log_num]");
	MakeCache($_Flashlog,'flash_log');
}

//评论排行
function CacheCommentLog(){
	global $_Logs,$dyhb_options,$IsCacheLog;
	$IsCacheLog=true;
	$_sideCommentlog=ThinLog($_Logs->GetLog("order by `commentnum` desc,`istop` desc,`dateline` desc",0,$dyhb_options['comment_log_num'],$isshow='1',$ispage='0'),'');
	MakeCache($_sideCommentlog,'side_commentlog');
}

//年度排行
function CacheYearHotLog(){
	global $_Logs,$dyhb_options,$localdate,$IsCacheLog;
	$IsCacheLog=true;
	$SqlTime=dyhb_date(date('Ymd',$localdate));
	$Sql="and `dateline` between\n" .$SqlTime[2]."\nand\n".$SqlTime[3]." order by `viewnum` desc,`istop` desc,`dateline` desc";
    $_sideYearHotLog=ThinLog($_Logs->GetLog($Sql,0,$dyhb_options['year_hot_log_num'],$isshow='1',$ispage='0'),'');
	MakeCache($_sideYearHotLog,'side_yearhotlog');
}

//当月排行
function CacheMouthHotLog(){
	global $_Logs,$dyhb_options,$localdate,$IsCacheLog;
	$IsCacheLog=true;
    $SqlTime=dyhb_date(date('Ymd',$localdate));
	$Sql="and `dateline` between\n" .$SqlTime[0]."\nand\n".$SqlTime[1]." order by `viewnum` desc,`dateline` desc,`istop` desc";
	$_sideMouthHotLog=ThinLog($_Logs->GetLog($Sql,0,$dyhb_options['mouth_hot_log_num'],$isshow='1',$ispage='0'),'');
	MakeCache($_sideMouthHotLog,'side_mouthhotlog');
}

//随机日志
function CacheRandLog(){
	global $_Logs,$dyhb_options,$IsCacheLog;
	$IsCacheLog=true;
	$_sideRandlog=ThinLog($_Logs->GetRandLog(0,$dyhb_options['rand_log_num']),'');
	MakeCache($_sideRandlog,'side_randlog');
}

//微博
function CacheSideTao()
{
	global $DB,$dyhb_options;
	$_Taotao=$DB->gettworow("select *from `".DB_PREFIX."taotao` where `user_id`=1 order by `dateline` desc limit 0,".$dyhb_options['taotao_show_num']);
     if(count($_Taotao)!=0){
	      $i=0;
	      $_sideTaotao=array();
	      foreach($_Taotao as $value){
		       $ttcontent=gbksubstr($value[content],0,$dyhb_options['taotao_cut_num']);
			   $Taotaodateline=Changedate($value['dateline'],'Y-m-d H:i');
		       $_sideTaotao[$i] = array('taotao_id'=>$value[taotao_id],'content'=>$ttcontent,'dateline'=>$Taotaodateline,'ismobile'=>$value[ismobile]);
		       $i++;
	        }
	    }
	MakeCache($_sideTaotao,'side_taotao');
}

//最新评论
function CacheNewComment(){
	global $_Comments,$dyhb_options;
	$_sideNewcomment=$_Comments->GetComment("order by `comment_id` desc",'',$isshow='1','0',$dyhb_options['new_comment_num']);
	$i=0;
	if($_sideNewcomment){
	  foreach($_sideNewcomment as $value){
	    $_sideNewcomment[$i][comment]=gbksubstr(strip_tags($value[comment]),'0',$dyhb_options[user_comment_str_num]);
		$i++;
	  }
	}
	MakeCache($_sideNewcomment,'side_newcomment');
}

//最新留言
function CacheNewGuestbook(){
	global $_Comments,$dyhb_options;
	$_sideNewGuestbook=$_Comments->GetComment("order by `comment_id` desc",'0',$isshow='1','0',$dyhb_options['new_guestbook_num']);
	$i=0;
	if($_sideNewGuestbook){
	  foreach($_sideNewGuestbook as $value){
	    $_sideNewGuestbook[$i][comment]=gbksubstr(strip_tags($value[comment]),'0',$dyhb_options[user_comment_str_num]);
		$i++;
	  }
	}
	MakeCache($_sideNewGuestbook,'side_newguestbook');
}

//友情衔接
function CacheLinks(){
	global $_Links;
	$side_Links=$_Links->GetLinks();
	MakeCache($side_Links,'side_links');
}

/** 分类 */

//用户侧边栏
function CacheSorts(){
	global $_Sorts,$_Logs,$DB;
	$log_sort=$_Sorts->GetSorts();
	 $lognum='';
	 $i=0;
	 $_sideSorts=array();
	 if($log_sort){
	 foreach($log_sort as $value){
		$lognum =$DB->getresultnum("SELECT count(blog_id) FROM `".DB_PREFIX."blog` where `sort_id`='$value[sort_id]'");
		//父分类
        $_thesort=$_Sorts->GetIdSort($value['sort_id']);
	    $The_parsort=$_Sorts->GetThreePar($_thesort);
	    $thesort_struct=$The_parsort['sort'];
		$_sideSorts[$value['sort_id']] = array('sort_id'=>$value['sort_id'],'name'=>$value['name'],'logo'=>$value['logo'],'parentsort_id'=>$value['parentsort_id'],'urlname'=>$value['urlname'],'sort'=>$thesort_struct,'lognum'=>$lognum);
		$i++;
	   }
	  }
	MakeCache($_sideSorts,'side_sorts');
}
//用于三级分类
function CachethreeSorts(){
	 global $DB,$_Sorts;
     //获取顶级父分类
     $sql1="select *from `".DB_PREFIX."sort` where `parentsort_id`=0 order by compositor asc";
     $parent=$DB->gettworow($sql1);
     if($parent){
       foreach($parent as $key=>$value){
	      //获取子分类
          $sql2="select *from `".DB_PREFIX."sort` where `parentsort_id`={$value[sort_id]} order by compositor asc";
          $child=$DB->gettworow($sql2);
          //一级分类的sort
          $The_parsort=$_Sorts->GetThreePar($value);
	      $parent[$key]['sort']=$The_parsort['sort'];
          if($child){
          foreach($child as $key2=>$val){
			  //获取孙分类
              $sql3="select *from `".DB_PREFIX."sort` where `parentsort_id`={$val[sort_id]} order by compositor asc";
	          $shunzi=$DB->gettworow($sql3);
	          //二级分类的sort
              $The_parsort2=$_Sorts->GetThreePar($val);
	          $child[$key2]['sort']=$The_parsort2['sort'];
	          if($shunzi){
	              foreach($shunzi as $key3=>$va){
	              	 //三级分类的sort
                     $The_parsort3=$_Sorts->GetThreePar($va);
	                 $shunzi[$key3]['sort']=$The_parsort3['sort'];
	              }
                  $child[$key2]['child']=$shunzi;
	          }
            }
            $parent[$key]['child']=$child;
          }
     }
   }
   MakeCache($parent,'global_threesorts');
}

//相册分类
function CachePhotoSorts(){
	global $_Photosorts,$DB;
	$PhotoSort=$_Photosorts->GetSorts();
	$photonum='';
	 $i=0;
	 $_sidePhotoSorts=array();
	 if($PhotoSort){
	      foreach($PhotoSort as $value){
		    $photonum = $DB->getresultnum("SELECT count(file_id) FROM `".DB_PREFIX."file` where `photosort_id`='$value[photosort_id]'");
		    $_sidePhotoSorts[$i] = array('photosort_id'=>$value[photosort_id],'name'=>$value[name],'photonum'=>$photonum);
		    $i++;
	       }
	  }
	MakeCache($_sidePhotoSorts,'side_photosorts');
}

//最新照片
function CacheNewPhoto(){
    global $DB,$dyhb_options;
	$_sidePhoto=$DB->gettworow("select *from `".DB_PREFIX."file` where `name` REGEXP '(jpg|jpeg|gif|png)$' order by `dateline` desc limit 0,$dyhb_options[new_photo_num]");
	MakeCache($_sidePhoto,'side_newphoto');
}

//音乐分类
function CacheMp3Sorts(){
	global $_Mp3s,$DB;
	$Mp3Sort=$_Mp3s->GetMp3Sorts('');//音乐分类
	 $mp3num='';
	 $i=0;
	 $_sideMp3Sorts=array();
	 if($Mp3Sort){
	      foreach($Mp3Sort as $value){
			$mp3num=$DB->getresultnum("select *from `".DB_PREFIX."mp3` where `mp3sort_id`='$value[mp3sort_id]'");
		    $_sideMp3Sorts[$i]['mp3num'] = $DB->getresultnum("SELECT count(mp3_id) FROM `".DB_PREFIX."mp3` where `mp3sort_id`='$value[mp3sort_id]'");;
		    $_sideMp3Sorts[$i] = array('mp3sort_id'=>$value[mp3sort_id],'name'=>$value[name],'mp3num'=>$mp3num);
		    $i++;
	       }
	  }
	MakeCache($_sideMp3Sorts,'side_mp3sorts');
}
//最新音乐
function CacheNewMusic(){
    global $DB,$dyhb_options;
	$_sideMusic=$DB->gettworow("select *from `".DB_PREFIX."mp3` order by `dateline` desc limit 0,$dyhb_options[new_music_num]");
	MakeCache($_sideMusic,'side_newmusic');
}

//Blogger
function CacheBlogger(){
	global $DB,$dyhb_options;
	$_sideBlogger=$DB->getonerow("select *from `".DB_PREFIX."user` where `user_id`='1'");
	$mwh=unserialize($dyhb_options[icon_width_height]);
	$filesize=ChangeImgSize("../images/qq/".$_sideBlogger[bloggerphoto],$mwh[0],$mwh[1]);
    $_sideBlogger['w']=$filesize['w'];
	$_sideBlogger['h']=$filesize['h'];
	MakeCache($_sideBlogger,'side_blogger');
}

//record
function CacheRecord(){
	 global $_Logs,$common_func;
	 $Sql="order by `dateline` desc,`istop` desc";
     $_Record=$_Logs->GetLog($Sql,'','',$isshow='1',$ispage='0');
     $record = 'xxxx_x';
	 $i= 0;
	 $lognum = 1;
	 $_sideRecord= array();
	 if($_Record){
     foreach($_Record as $value){
     $from_time=date('Y_n',$value['dateline']);
     if ($record!=$from_time){
		$p = $i-1;
		if($p!=-1){
			$_sideRecord[$p]['lognum'] = $lognum;
		}
		$url=get_rewrite_record(date("Y",$value['dateline']),date("m",$value['dateline']));
		$SqlTime=dyhb_date(date("Ym"),$value['dateline']);
		$Sql="and `dateline` between\n" .$SqlTime[0]."\nand\n".$SqlTime[1]." order by `istop` desc,`dateline` desc";
		$_Loglist=ThinLog($_Logs->GetLog($Sql,'','',$isshow='1',$ispage='0'),'');
		$_sideRecord[$i] = array('record_id'=>date("Yn",$value['dateline']),'record'=>date("Y{$common_func[1]}n{$common_func[2]}",$value['dateline']),'url'=>"$url","post"=>$_Loglist);
		$i++;
		$lognum = 1;
	}else{
		$lognum++;
		continue;
	}
	    $record=$from_time;
	}
 $j = $i-1;
 if($j>=0){
	$_sideRecord[$j]['lognum'] = $lognum;
 }
}   
    //获取日志数据
	$i=0;
    if($_sideRecord){
	    foreach($_sideRecord as $value){
		    //获取归档日志数据
		    $url=get_rewrite_record(substr($value[record_id],'0','4'),substr($value[record_id],'4','2'));
	        $SqlTime=dyhb_date($value[record_id]);
	        $Sql="and `dateline` between\n" .$SqlTime[0]."\nand\n".$SqlTime[1]." order by `istop` desc,`dateline` desc";
	        $_Loglist=ThinLog($_Logs->GetLog($Sql,'','',$isshow='1',$ispage='0'),'');
            $_sideRecord[$i][post]=$_Loglist;
            $i++;
		}
	}
	MakeCache($_sideRecord,'side_record');
}

//blog
function CacheBlog(){
	global $DB;
	$AllLogs=$DB->getresultnum("SELECT count(blog_id) FROM ".DB_PREFIX."blog");
	$AllComments=$DB->getresultnum("SELECT count(comment_id) FROM ".DB_PREFIX."comment");
	$AllTags=$DB->getresultnum("SELECT count(tag_id) FROM ".DB_PREFIX."tag");
	$AllTrackbacks=$DB->getresultnum("SELECT count(trackback_id) FROM ".DB_PREFIX."trackback");
	$AllFiles=$DB->getresultnum("SELECT count(file_id) FROM ".DB_PREFIX."file");
	$AllUsers=$DB->getresultnum("SELECT count(user_id) FROM ".DB_PREFIX."user");
	$_sideBlog=array('alllogs'=>$AllLogs,'allcomment'=>$AllComments,'alltags'=>$AllTags,'alltrackbacks'=>$AllTrackbacks,'allfiles'=>$AllFiles,'allusers'=>$AllUsers);
	MakeCache($_sideBlog,'side_blog');
}

//标签云
function CacheTag(){
	Global $_Tags,$dyhb_options;
	//如果为侧边栏，则取出一定数量的标签，否则为标签云
	$AllTag=TagAction($_Tags->GetTag('','',''));
    $sort_t=sorttag($AllTag);
	$hottag=array();
	$h_n=$dyhb_options['hot_tag_num']<=count($AllTag)?$dyhb_options['hot_tag_num']:count($AllTag);
	$i=0;
	foreach($sort_t as $value){
	   $hottag[]=$value;
	   $i++;
	   if($i>$h_n) break;
	}
	$_theTag=array($AllTag,$hottag);
	MakeCache($_theTag,'the_tag');
}

/**
 * cms
 */

//cms最新日志
function CacheCmsNew(){
	global $DB,$dyhb_options,$_Logs;
	$CmsNew=CmsValue($dyhb_options['cms_new_log_num']);
	$CmsNew1=ThinLog($_Logs->GetLog("order by `dateline` desc,`istop` desc",'0',$CmsNew['0'],$isshow='1',$ispage='0'),'yes');
	$CmsNew2=ThinLog($_Logs->GetLog("order by `dateline` desc,`istop` desc",$CmsNew['0'],$CmsNew['1'],$isshow='1',$ispage='0'),'yes');
	$_CmsNew=array($CmsNew1,$CmsNew2);
	MakeCache($_CmsNew,'cms_newlog');
}

//分类

function cacheSort($value){
   global $_Logs;
   $CmsSortName="CmsSortName".$value[sort_id];
   $$CmsSortName=$value[name];
   $CmsSort1="CmsSort1_".$value[sort_id];
   $CmsSort2="CmsSort2_".$value[sort_id];
   $Sql="and sort_id=".$value['sort_id']."\norder by `istop` desc,`dateline` desc";
   $$CmsSort1=ThinLog($_Logs->GetLog($Sql,0,$value[cmsstart],$isshow='1',$ispage='0'),'yes');
   $$CmsSort2=ThinLog($_Logs->GetLog($Sql,$value[cmsstart],$value[cmsend],$isshow='1',$ispage='0'),'yes');
   $_CmsBigSort=array($$CmsSortName,$$CmsSort1,$$CmsSort2);
   MakeCache($_CmsBigSort,'cms_bigsort_'.$value[sort_id]);
}

function CacheCmsSort(){
	global $DB,$_Logs;
	$log_sort=$DB->gettworow("select * from `".DB_PREFIX."sort`");
	if($log_sort){
	foreach($log_sort as $value){
		cacheSort($value);
	 }
   }
}

?>