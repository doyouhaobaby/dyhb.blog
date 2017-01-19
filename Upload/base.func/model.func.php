<?php
/**
  * 个人信息模块函数
  *
  * @param int $CoolId 用户ID
  * @return string
  */
function _index_model_user($CoolId){
	 global $_Cools,$common_user,$common_permission,$front_content;
	 /** 权限控制　*/
	 CheckPermission("viewuserdetail",$common_permission['0']);

	 /** 获取用户信息 */
     $show_Blogger=$_Cools->GetBloggerInfo($CoolId);
     if(!$show_Blogger){
          page_not_found();
     }
     $show_coolschool=unserialize($show_Blogger['school']);
     $regitime=date('Y-m-d H:i:s',$show_Blogger[dateline]);
	//用户组判断
    if($show_Blogger[usergroup]=='1'){$usergrounp=$common_user['21'];}
    elseif($show_Blogger[usergroup]=='2'){$usergrounp=$common_user['22'];}
    else{$usergrounp=$common_user['23'];}
	//性别
    if($show_Blogger[sex]=='0'){$sex=$common_user['73'];}
    elseif($show_Blogger[sex]=='1'){$sex=$common_user['74'];}
    else{$sex=$common_user['72'];}
	//是否结婚
    if($show_Blogger[marry]=='0'){$marry=$common_user['79'];}
    elseif($show_Blogger[marry]=='1'){$marry=$common_user['80'];}
    else{$marry=$common_user['72'];}
	$date=date('Y-m-d H:i:s',$show_Blogger[logintime]);
    //头像
    if($show_Blogger[bloggerphoto]){
		//超级管理员专用头像仓库
		if($CoolId=='1'){
		   $ph="<li><img src='images/qq/".$show_Blogger[bloggerphoto]."'></li>";	   
		}else{
		   $ph="<li><img src='width/upload/".$show_Blogger[bloggerphoto]."'></li>";
		}
	}
	//功能
    if(ISLOGIN&&$CoolId==LOGIN_USERID){
    $edituser="<li>welcome $show_Blogger[username]!</li><li><a href='admin/?action=user&do=upd&id=$CoolId'>$common_user[94]</a></li><li><a href='?action=usergroup&id=$show_Blogger[usergroup]'>$common_user[95]</a></li><li><a href='public.php'>$front_content[48]</a></li>";
    }

	//返回的数据变量
    $_result="<div class='show_userinfo'><ul>";
    $_result.=<<<DYHB
{$edituser}{$ph}<li>{$common_user[59]}$show_Blogger[nikename]</li><li>{$common_user[25]}$show_Blogger[description]</li><li>{$common_user[19]}$show_Blogger[email]</li><li>{$common_user[71]}$sex</li><li>{$common_user[75]}$show_Blogger[age]</li><li>QQ:$show_Blogger[qq]</li><li>$common_user[20]:$regitime</li><li>{$common_user[24]}$usergrounp</li><li>$common_user[76]$show_Blogger[work]</li><li>{$common_user[88]}$show_coolschool[0]</li><li>{$common_user[89]}$show_coolschool[1]</li><li>{$common_user[90]}$show_coolschool[2]</li><li>{$common_user[91]}$show_coolschool[3]</li><li>{$common_user[77]}$marry</li><li>{$common_user[81]}$show_Blogger[love]</li><li>{$common_user[92]}$show_Blogger[hometown]</li><li>{$common_user[93]}$show_Blogger[nowplace]</li><li><a href="$show_Blogger[weyaoblog]">{$common_user[85]}</a></li><li><a href="$show_Blogger[homepage]">{$common_user[65]}</a></li><li><a href="mailto:$show_Blogger[msn]">MSN</a></li><li><a href="mailto:$show_Blogger[skype]">Skype</a></li><li><a href="mailto:$show_Blogger[xiaonei]">{$common_user[86]}</a></li><li>{$common_user[96]}{$show_Blogger[logincount]}</li><li>{$common_user[97]}{$date}</li>
DYHB;
       $_result.="</ul></div>";
	   return $_result;
}


/**
  * 标签模块
  *
  * @param array $TagCloud 日志发表时间
  * @return string
  */
function _index_model_tag($TagCloud){
	global $front_content;
     $_result.="<div class='f_tagcloud'>";
     if(empty($TagCloud)){
		  $_result.="$front_content[34]";
	 }else{
	      foreach($TagCloud as $value){
             $url=get_rewrite_tag($value);
             $_result.=<<<DYHB
<a href="$url" title="{$front_content[48]}$value[lognum]"><span style="font-size:$value[fontsize]pt; height:30px; color:#$value[color];">$value[name]</span></a>
DYHB;
          }
     }
    $_result.="</div>";
	return $_result;
}


/**
  * 归档模块
  *
  * @param array $_sideRecord 归档数据
  * @return string
  */
function _index_model_record($_sideRecord){
	 global $front_content;
     $_result.="<div class='f_record_box'>";
     if(empty($_sideRecord)){
		 $_result.="<h2>$front_content[49]<h2>";
	 }else{
		 foreach($_sideRecord as $value){
	         $_result.=<<<DYHB
<h2><a href="$value[url]">$value[record]</a>($value[lognum])</h2>
<div class='f_record'>
<ul>
DYHB;
			  if($value['post']){foreach($value['post'] as $val){
				  $the_url=_showlog_posturl($val);
				  $_result.="<li><a href=\"$the_url\">$val[title]</a></li>";
			  }}
           $_result.="</ul></div>";
        }
     }
     $_result.="</div>";
	 return $_result;
}


/**
  * 衔接模块
  *
  * @param array $side_LogoLinks 图片衔接
  * @param array $side_TextLinks 文字衔接
  * @return string
  */
function _index_model_link(){
	global $dyhb_options,$side_LogoLinks,$side_TextLinks,$front_content;
    $_result=<<<DYHB
<div class='f_link'><div class='f_link_head'><p><strong>$front_content[50]</strong></p><p><textarea cols="80" rows="3">&lt;a href='{$dyhb_options[blogurl]}' target='_blank' title='{$dyhb_options[blog_title]}-{$dyhb_options[blog_information]}'&gt;&lt;img src='{$dyhb_options[blog_logo]}' alt='{$dyhb_options[blog_title]}-{$dyhb_options[blog_information]}'&gt;&lt;/a&gt;</textarea></p><p><strong>$front_content[51]</strong></p><p><textarea cols="80" rows="3">&lt;a href='{$dyhb_options[blogurl]}' target='_blank' title='{$dyhb_options[blog_title]}-{$dyhb_options[blog_information]}'&gt;{$dyhb_options[blog_title]}&lt;/a&gt;</textarea></p></div><div class='f_logolink'><h4>$front_content[50]</h4><ul>
DYHB;
  if($side_LogoLinks){
	  foreach($side_LogoLinks as $value){
	     if($value[isdisplay]=='1'){
	         $_result.="<li><a href=\"$value[url]\" title=\"$value[description]\" target=\"_blank\"><img src=\"$value[logo]\"></a></li>";
         }
	  }
   }
   $_result.="</ul></div><div class='f_textlink'><h4>$front_content[51]</h4><ul>";
  if($side_TextLinks){
	  foreach($side_TextLinks as $value){
	      if($value[isdisplay]=='1'){
	          $_result.="<li><a href=\"$value[url]\" title=\"$value[description]\" target=\"_blank\">$value[name]</a></li>";
          }
	  }
   }
    $_result.="</ul></div></div><p style='clear:both;'></p>";
	return $_result;
}


/**
  * 搜索模块表单
  *
  * @param array $_globalTreeSorts 日志分类
  * @return string
  */
function _index_model_search_form(){
   global $_globalTreeSorts,$common_permission,$front_content;
   $search_lang=$front_content['search'];
    /** 是否允许搜索 */
    CheckPermission("allowsearch",$common_permission['1']);
      $_result=<<<DYHB
<form name="search" method="get" action="index.php?action=s"><input type="hidden" name="action" value="s" /><p><label for="key">$search_lang[0]</label><input type="text" name="key" id="key" class="input" /><br><span>$search_lang[1]</span></p><p><label>$search_lang[2]</label><input type="radio" name="type" value="title" checked="checked" />$search_lang[3]<input type="radio" name="type" value="all" />$search_lang[4]</p><p><label>$search_lang[5]</label><select name="sortid" /><option value="0">&gt;&gt;$search_lang[6]</option><option value="-1" >&nbsp;&nbsp;|-$search_lang[7]</option>
DYHB;
   if($_globalTreeSorts){foreach($_globalTreeSorts as $value){
	    $_result.="<option value=\"{$value[sort_id]}\">|--{$value[name]}</option>";
		     if($value[child]){foreach($value[child] as $val){
		         $_result.="<option value=\"{$val[sort_id]}\">&nbsp;&nbsp;&nbsp;&nbsp;|----{$val[name]}</option>";
				     if($val[child]){foreach($val[child] as $val2){
				         $_result.="<option value=\"{$val2[sort_id]}\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|----{$val2[name]}</option>";
	}}}}}}
    $_result.=<<<DYHB
</select></p><p><label>$search_lang[8]</label><select name="time"><option value="all">$search_lang[9]</option><option value="86400">$search_lang[10]</option><option value="172800">$search_lang[11]</option><option value="604800">$search_lang[12]</option><option value="1296000">$search_lang[13]</option><option value="5184000">$search_lang[14]</option><option value="8640000">$search_lang[15]</option><option value="31536000">$search_lang[16]</option></select><input type="radio" name="timetype" value="0" checked="checked" />$search_lang[17]<input type="radio" name="timetype" value="1" />$search_lang[18]</p><p><label>$search_lang[19]</label><select name="orderby"><option value="date">$search_lang[20]</option><option value="viewnum">$search_lang[21]</option><option value="comnum">$search_lang[22]</option></select><input type="radio" name="ascdesc" value="desc" checked="checked"/>$search_lang[23]<input type="radio" name="ascdesc" value="asc" /> $search_lang[24]</p><p><input type="submit" value="$search_lang[25]" class="button" /></p></form>
DYHB;
   return $_result;
}

/**
  * 搜索模块
  *
  * @param array $_globalTreeSorts 日志分类
  * @return string
  */
function _index_model_search(){
   global $_globalTreeSorts,$DB,$_Logs,$pagination,$pagestart,$dyhb_options,$common_permission,$front_content;
    /** 是否允许搜索 */
    CheckPermission("allowsearch",$common_permission['1']);
        $Key = CheckSql(get_args('key'));
	    //检查关键字是否为空
		//不用关键字也可以筛选出数据，所以不判断
	    //if($Key==''){
	        //DyhbMessage('关键字不能为空！','');
	    //}
	    //全文，标题
		if($_GET['type']=='all'){
		    /** 是否允许全文搜索 */
            CheckPermission("fulltextsearch",$common_permission['1']);
		}
	    $type_c=$_GET['type']=='all'?'content':'title';
		
	    //分类
	    $sort_c=$_GET['sortid']==('0'||'')?'':"and `sort_id`='$_POST[sortid]'";
	    //排序
        $AscDesc = $_GET['ascdesc'] == 'asc' ? 'asc' : 'desc';
	    $OrderBy = in_array($_GET['orderby'], array('dateline', 'viewnum', 'commmentnum')) ? $_GET['orderby'] : 'dateline';
	    $OrderBy_c="$OrderBy $AscDesc";
        //时间
	    $Time = intval($_GET['time']);
	    if($Time) {
		     $time_c = $_GET['timetype']=='0' ? '<=' : '>=';
		     $time_c .= $localdate - $Time;
		     $time_c = ' AND `dateline` '.$time_c;
	     }
        $Sql="and `$type_c` like '%$Key%' $sort_c $time_c order by `istop` DESC,$OrderBy_c ";
        $TotalLogNum=$DB->getresultnum("SELECT count(blog_id) FROM `".DB_PREFIX."blog` where `$type_c` like '%$Key%' and `isshow`='1' and `ispage`='0' $sort_c $time_c");
        if(!$TotalLogNum){
	             DyhbMessage($front_content['search']['26'],'');
	    }
        if($TotalLogNum){
        Page($TotalLogNum,$dyhb_options['user_log_num'],'');
        $_Loglist=$_Logs->GetLog($Sql,$pagestart,$dyhb_options['user_log_num'],$isshow='1',$ispage='0');
     }
     return $_Loglist;
}

/**
  * 留言板模块
  *
  * @return string
  */
function _index_model_guestbook(){
	global $DB,$_Comments,$dyhb_options,$pagination,$pagestart,$_Cools,$TotalComment,$ShowComment,$ShowComment2,$newpage_c,$compage_c;
    /** 获取留言板评论 */
    get_global_comments('blog_id','');
}


/**
  * 相册模块
  *
  * @param array $_sidePhotoSorts 相册分类
  * @return string
  */
function _index_model_photo(){
	 global $_sidePhotoSorts,$DB,$pagination,$pagestart,$dyhb_options,$_Photosorts,$dyhb,$photo_id,$ShowComment,$TotalComment,$ShowComment2,$_Comments,$ShowPhoto,$common_permission,$front_content;
	 CheckPermission("viewphotolist",$common_permission['2'],'');
     $photosort_id=intval( get_args('di') );//相册分类
     $photo_id=intval( get_args('id') );//单个相片
     $PhotoSort=$_sidePhotoSorts;//相册分类
     $JustShow=get_args('show');
     /** 基本css */
     $_result=<<<DYHB
<style type="text/css">.photolist{auto;}.photolist ul li{border:2px solid \#d7dee2;float:left;list-style:none;margin:15px;}.photolist ul li img{border:none;padding:5px;}.photolist ul li a:hover{}.photolist ul li  a span{padding:15px;margin-left:20px;}.onephoto{text-align:center;background:\#fff;height:auto;width:auto;padding:30px; }.onephoto a,.onephoto a:visited{color:\#000;}.onephoto a:hover{color:\#73706e;}.prephoto{margin:20px; padding:2px;background:\#d7f0f5;border-right:2px solid \#7fe3f5;}.nextphoto{margin:20px; padding:2px; 0;background:\#d7f0f5;border-left:2px solid \#7fe3f5;}</style>
DYHB;
     
	 /** 相片列表 **/
      if(!$photo_id&&!$JustShow){
		 //相片分类查询
         $photo_s=$photosort_id?"and `photosort_id`='$photosort_id'":'';
         $TotalFileNum=$DB->getresultnum("SELECT count(file_id) FROM `".DB_PREFIX."file` where `path` REGEXP '(jpg|jpeg|bmp|gif|png)$' $photo_s");
         if($TotalFileNum){
              $url_c=$photosort_id?"&di=".$photosort_id:'';
              Page($TotalFileNum,$dyhb_options['every_photo_num'],"?action=photo".$url_c);
             $AllFiles=$DB->gettworow("select *from `".DB_PREFIX."file` where `path` REGEXP '(jpg|jpeg|bmp|gif|png)$' $photo_s order by `dateline` desc limit  $pagestart,$dyhb_options[every_photo_num]");
         }
       $_result.="<div class='photolist' id='photolist'><ul>";
       if($AllFiles){
           foreach($AllFiles as $value){
			   if($dyhb_options['photo_isshow_hide']=='1'){
	               $the_img_url="file.php?id=$value[file_id]";
	           }else{
	              $the_img_url="width/upload/$value[path]";
	           }
			   $_blank_url=$the_img_url;
			   //大图
	           $start=strpos($value[path],'/')+1;
			   if(substr($value[path],$start,'2')=='t-'){
              	   $_blank_url=$big_file_path='width/upload/'.str_replace('t-','',$value[path]);
			   }
              $_result.=<<<DYHB
<li><a href="?action=photo&id=$value[file_id]" title="$value[name]"><img src="$the_img_url" width="120" height="120" border="0" alt="$value[description]"/></a><br><a href="$_blank_url" target="_blank"><span>$front_content[52]</span></a></li>
DYHB;
           }
	   }else{
	         $_result.=$front_content[53];
       }
	    $_result.="</ul></div><div id=\"pagenav\">$pagination</div>";
     }

	/** 输出单个相片 */
    elseif($photo_id){
       //单张照片
       $ShowPhoto=$_Photosorts->GetIdFile($photo_id);
       //附件大小
       $max_file_wh=unserialize($dyhb_options['all_width_height']);
       $now_wh=ChangeImgSize("width/upload/".$ShowPhoto['path'],$max_file_wh['0'],$max_file_wh['1']);
       //上一张,下一张
       $PreFile=$_Photosorts->GetPreFile($photo_id);
       $NextFile=$_Photosorts->GetNextFile($photo_id);
       $Pre_c=$PreFile['file_id']?"&id=$PreFile[file_id]":'';
       $Next_c=$NextFile['file_id']?"&id=$NextFile[file_id]":'';
	   /** 获取相册评论 */
       get_global_comments('file_id',$photo_id);
	   if($dyhb_options['photo_isshow_hide']=='1'){
	        $the_img_url=$dyhb_options[blogurl]."/file.php?id=$ShowPhoto[file_id]";
	   }else{
	        $the_img_url=$dyhb_options[blogurl]."/width/upload/$ShowPhoto[path]";
	   }
	   $_blank_url=$the_img_url;
		//大图
	    $start=strpos($ShowPhoto[path],'/')+1;
	    if(substr($ShowPhoto[path],$start,'2')=='t-'){
             $_blank_url=$big_file_path='width/upload/'.str_replace('t-','',$ShowPhoto[path]);
		}
       $_result.=<<<DYHB
<div class='onephoto' id='onephoto'><div class="prephoto"><a href="?action=photo{$Pre_c}" title="$PreFile[name]">$front_content[54]</a></div><a href="$_blank_url" title="$ShowPhoto[name]" target="_blank"><img src="$the_img_url" width="$now_wh[w]" height="$now_wh[h]" style="border:none;" alt="$ShowPhoto[description]"/></a><div class="nextphoto"><a href="?action=photo{$Next_c}" title="$NextFile[name]">$front_content[55]</a></div></div>
<div class="copy_photo_url">
<p><a href="javascript:;" onclick="showajaxdiv('file', '$dyhb_options[blogurl]/getxml.php?fileid=$photo_id', 500);">$front_content[56]</a></p>
</div>
DYHB;
    }

	/** 相册列表 */
    elseif($JustShow=='photosort'){
      //相册分类
      $PhotoSort['-1']=array('photosort_id'=>'-1','name'=>$front_content[57],'cover'=>'','compositor'=>'0');
      $_result.="<div class='photolist'><ul>";
      if($PhotoSort){
           foreach($PhotoSort as $value){
               $Result=$_Photosorts->GetSorts($value[photosort_id]);//相册封面，没有则调用系统默认的图片
               $cover=$Result[cover]?$Result[cover]:"images/other/photosort.gif";
               $_result.=<<<DYHB
<li><a href="?action=photo&di=$value[photosort_id]" title="$value[name]"><img src="$cover" width="150" height="150" border="0"/></a><br><a href="?action=photo&di=$value[photosort_id]"><span>$value[name]</span></a></li>
DYHB;
            }
	   }
       $_result.="</ul></div>";
    }
    
	/** 其它文件下载 */
    elseif($JustShow&&$JustShow!='photosort'){
		 CheckPermission("downfile","下载站点附件！",'');
	     $TotalZipNum=$DB->getresultnum("SELECT count(file_id) FROM `".DB_PREFIX."file` where `path` REGEXP '($JustShow)$' $photo_s");
         if($TotalZipNum){
              Page($TotalZipNum,$dyhb_options['every_photo_num'],"?action=photo&show=$JustShow");
             $AllFiles=$DB->gettworow("select *from `".DB_PREFIX."file` where `path` REGEXP '($JustShow)$' order by `dateline` desc limit  $pagestart,$dyhb_options[every_photo_num]");
         }
		 $_result=<<<DYHB
<table width='100%'><tr><td class='table-header' width='35%' align='center'>$front_content[58]</td><td class='table-header' width='30%' align='center'>$front_content[59]</td><td class='table-header' width='10%' style='word-break: normal;' align='center'>$front_content[60]</td><td class='table-header' width='25%' style='word-break: normal;' align='center'>$front_content[61]</td></tr>
DYHB;
         if($AllFiles){
             foreach($AllFiles as $value){
               $date=date('Y-m-d H:i:s',$value[dateline]);
			   if($JustShow=='mp3'||$JustShow=='wav'||$JustShow=='wma'){
			       $mp3_play="<a href=\"?action=mp3&fid={$value[file_id]}\" title=\"$front_content[62] {$value[name]}\"><img src=\"images/other/player.gif\"/></a>";
			   }
			   if($dyhb_options['photo_isshow_hide']=='1'){
	                $the_file_url="file.php?id=$value[file_id]";
	           }else{
	                $the_file_url="width/upload/$value[path]";
	           }
			   $_result.=<<<DYHB
<tr><td class="table-entry">{$value[name]}{$mp3_play}</td><td class="table-entry">{$date}</td><td class="table-entry">{$value[size]}</td><td class="table-entry" style="word-break: normal;" align="center"><a href="{$the_file_url}" title="下载 {$value[name]}"><img src="images/other/download.gif"/></a>($value[download])</td></tr>
DYHB;
	         }
	     }else{
		     $_result.= "<tr><td>$front_content[63]</td><td></td><td></td><td></td></tr>";
		 }
		 $_result.="</table><div id=\"pagenav\">{$pagination}</div>";
	}
	$_result.="<p style='clear:both;'></p>";
    //全站附件格式，以及下拉列表
	$all_allowed_filetype=unserialize( $dyhb_options[all_allowed_filetype] );
	$_result.="<div class=\"photo_selec\"><select onchange=\"javascript:location.href=this.value;\"><option value=\"\">$front_content[64]</option>";
    if($all_allowed_filetype){foreach($all_allowed_filetype as $value){$_result.="<option value=\"index.php?action=photo&show=$value\">$value</option>";}}
    $_result.="</select></div><br>";

	return $_result;
}

/**
  * 权限模块
  *
  * @return string
  */
function _index_model_usergroup(){
	 global $_BlogUsergroup,$dyhb_options,$dyhb_premissions,$common_user;
     $usergroup_id=intval( get_args('id') );
         
	 /** 返回数据初始化 */
	 $_result='';

	 /** 权限列表 **/
	 if($usergroup_id==''){
       $_result=<<<DYHB
<table width='100%'><tr><td class='table-header' width='30%' align='center'>$common_user[18]</td><td class='table-header' width='70%' style='word-break: normal;' align='center'>$common_user[98]</td></tr>
DYHB;
	  foreach($_BlogUsergroup as $value){
		  if($value[id]!='4'){
		        $_result.="<tr><td class=\"table-entry\"><a href=\"?action=usergroup&id=$value[id]\">$value[name]</a></td><td class=\"table-entry\">$value[description]</td></tr>";
		  }
      }
	  $_result.="</table>";
	 }else{
		  $dyhb_premissions=unserialize($dyhb_options['dyhb_global_prefconfig'.$usergroup_id]);
	      /** 数据处理 */
          foreach($dyhb_premissions as $key=>$value){
	         if($dyhb_premissions[$key]=='1'){
		          $$key="<font color=\"green\">√</font>";
		     }else{
		          $$key="<font color=\"red\">×</font>";
		     }
	      }
	     $_result.="<div class='f_usergrouppre'><ul>";
         $_result.=<<<DYHB
<b>$common_user[26]</b><br><li>{$common_user[27]}$visit</li><li>{$common_user[28]}$seehiddenentry</li><li>{$common_user[29]}$seehiddencomment</li><li>{$common_user[30]}$seeip</li> <li>{$common_user[31]}$viewuserlist </li><li>{$common_user[32]}$viewphotolist</li><li>{$common_user[33]}$viewuserdetail</li><li>{$common_user[34]}$seeallprotectedentry</li></ul><br><br><ul><b>$common_user[35]</b><br><li>{$common_user[36]}$addentry</li><li>{$common_user[37]}$editentry</li><li>{$common_user[38]}$editotherentry</li><li>{$common_user[39]}$addtag</li><li>{$common_user[40]}$leavemessage</li><li>{$common_user[41]}$sendentry</li><li>{$common_user[42]}$sendmicrolog</li></ul><br><br><ul><b>{$common_user[43]}</b><br><li>{$common_user[44]}$minpostinterval</li><li>{$common_user[45]}$nospam</li> <li>{$common_user[46]}$html</li></ul><br><br><ul><b>$common_user[47]</b><br><li>{$common_user[48]}$editotheruser</li><li>{$common_user[49]}$topentry</li><li>{$common_user[50]}$cp</li></ul><br><br><ul><b>$common_user[51]</b><br><li>{$common_user[52]}$allowsearch</li>   <li>{$common_user[53]}$fulltextsearch</li></ul><br><br><ul><b>$common_user[54]</b><br><li>{$common_user[55]}$upload</li><li>{$common_user[56]}$downfile</li></ul>
DYHB;
		 $_result.="</ul></div>";
     }
      
	  /** 返回数据 */
	  return $_result;
}

/**
  * 引用模板
  *
  * @param array $_sideMp3Sorts 日志分类
  * @return string
  */
function _index_model_trackback(){
	 global $_Trackbacks,$DB,$pagination,$pagestart,$dyhb_options,$front_content;
     $TotalTrackbackNum=$DB->getresultnum("SELECT count(trackback_id) FROM `".DB_PREFIX."trackback`");
     if($TotalTrackbackNum){
        Page($TotalTrackbackNum,$dyhb_options['user_log_num'],"?action=trackback");
        $AllTrackbacks=$_Trackbacks->GetTrackbacks('',$pagestart,$dyhb_options['user_log_num'],true);
     }

	/** 返回结果 */
    $_result=<<<DYHB
<table width='100%'><tr><td class='table-header' width='50%' align='center'>$front_content[65]</td><td class='table-header' width='20%' align='center'>$front_content[66]</td><td class='table-header' width='30%' style='word-break: normal;' align='center'>$front_content[67]</td></tr>
DYHB;
	  if($AllTrackbacks){foreach($AllTrackbacks as $value){
		    $time=date("Y-m-d H:i:s",$value[dateline]);
		    $_result.=<<<DYHB
<tr><td class="table-entry"><a href="?p=$value[blog_id]">$value[title]</a></td><td class="table-entry"><a href="$value[url]">$value[blogname]</a></td><td class="table-entry" style="word-break: normal;" align="center">{$time}</td></tr>
DYHB;
          }
      }else{
		     $_result.= "<tr><td>$front_content[68]</td><td></td><td></td></tr>";
		}
	  $_result.="</table><div id=\"pagenav\">{$pagination}</div>";

	  /** 返回数据 */
	  return $_result;
}


/**
  * 用户模块
  *
  * @param array $_sideMp3Sorts 日志分类
  * @return string
  */
function _index_model_userlist(){
	 global $DB,$pagination,$pagestart,$dyhb_options,$dyhb_premissions,$front_content;
	 CheckPermission("viewuserlist",$dyhb_premissions[5]);
     $TotalUserNum=$DB->getresultnum("SELECT count(user_id) FROM `".DB_PREFIX."user`");
     if($TotalUserNum){
        Page($TotalUserNum,$dyhb_options['user_log_num'],"?action=user");
        $AllUsers=$DB->gettworow("select *from `".DB_PREFIX."user` limit $pagestart,$dyhb_options[user_log_num]");
     }

	/** 返回结果 */
    $_result=<<<DYHB
<table width='100%'><tr><td class='table-header' width='20%' align='center'>$front_content[69]</td><td class='table-header' width='10%' align='center'>$front_content[70]</td><td class='table-header' width='10%' style='word-break: normal;' align='center'>$front_content[71]</td><td class='table-header' width='10%' style='word-break: normal;' align='center'>QQ</td><td class='table-header' width='50%' style='word-break: normal;' align='center'>$front_content[72]</td></tr>
DYHB;
	  if($AllUsers){foreach($AllUsers as $value){
            $the_email=$value['email']?"<a href=\"mailto:{$value[email]}\"><img src=\"images/other/userlist_email.gif\"/></a>":"<img src=\"images/other/userlist_noemail.gif\"/>";
			$the_homepage=$value['homepage']?"<a href=\"{$value[homepage]}\"><img src=\"images/other/userlist_link.gif\"/></a>":"<img src=\"images/other/userlist_nolink.gif\"/>";
			$the_qq=$value['qq']?"<a href=\"http://wpa.qq.com/msgrd?V=1amp;Uin={$value[qq]}amp;Site=ioshenmueMenu=yes\"><img src=\"images/other/userlist_qq.gif\"/></a>":"<img src=\"images/other/userlist_noqq.gif\"/>";
			$the_time=date("Y-m-d H:i:s",$value[dateline]);
		    $_result.=<<<DYHB
<tr><td class="table-entry"><a href="?c=$value[user_id]">$value[username]</a></td><td class="table-entry">$the_email</td><td class="table-entry" style="word-break: normal;" align="center">{$the_homepage}</td><td class="table-entry" style="word-break: normal;" align="center">{$the_qq}</td><td class="table-entry" style="word-break: normal;" align="center">{$the_time}</td></tr>
DYHB;
          }
      }else{
		     $_result.= "<tr><td>$front_content[73]</td><td></td><td></td><td></td><td></td></tr>";
		 }
	  $_result.="</table><div id=\"pagenav\">{$pagination}</div>";

	  /** 返回数据 */
	  return $_result;
}

/**
  * 音乐模块
  *
  * @param array $_sideMp3Sorts 日志分类
  * @return string
  */
function _index_model_mp3list(){
	 global $_sideMp3Sorts,$_Photosorts,$DB,$_Mp3s,$pagination,$pagestart,$dyhb_options,$mp3_id,$upload_mp3_id,$_Comments,$TotalComment,$ShowComment2,$ShowComment,$ShowMp3,$front_content;
	 /** url传值，数据处理 */
     $mp3sort_id = intval( get_args('ms') );
	 $mp3_id = intval( get_args('id') );
	 $upload_mp3_id = intval( get_args('fid') );
     $Mp3Sort=$_sideMp3Sorts;//音乐分类
     $Mp3Sort['-1']=array('mp3sort_id'=>'-1','name'=>$front_content[57],'isdisplay'=>'1','compositor'=>'0');
     $mp3_s=$mp3sort_id?"where `mp3sort_id`='$mp3sort_id'":'';
	 if(!$mp3_id&&!$upload_mp3_id){
     $TotalMp3Num=$DB->getresultnum("SELECT count(mp3_id) FROM `".DB_PREFIX."mp3` $mp3_s");
     if($TotalMp3Num){
        $mp3sort_c=$mp3sort_id?"&ms=$mp3sort_id":'';
        Page($TotalMp3Num,$dyhb_options['user_log_num'],"?action=mp3".$mp3sort_c);
        $AllMp3s=$_Mp3s->GetMp3s($mp3sort_id,$pagestart,$dyhb_options['user_log_num']);
     }
     $i=0;
     //处理
    if($AllMp3s){
        $_result='';
        foreach($AllMp3s as $value){
           $themp3sort=$_Mp3s->GetMp3Sorts($value[mp3sort_id]);
           if($value[mp3sort_id]=='-1'){
	           $AllMp3s[$i][mp3sortname]=$front_content[57];
           }else{
	           $AllMp3s[$i][mp3sortname]=$themp3sort[name];
          }
          $i++;
        }
    }

	/** 返回结果 */
    $_result=<<<DYHB
<table width='100%'><tr><td class='table-header' width='30%' align='center'>{$front_content[86]}</td><td class='table-header' width='30%' align='center'>{$front_content[87]}</td><td class='table-header' width='40%' style='word-break: normal;' align='center'>{$front_content[88]}</td></tr>
DYHB;
	  if($AllMp3s){foreach($AllMp3s as $value){
		    $_result.=<<<DYHB
<tr><td class="table-entry"><a href="?action=mp3&ms=$value[mp3sort_id]">$value[mp3sortname]</a></td><td class="table-entry"><a href="?action=mp3&id=$value[mp3_id]" title="{$front_content[74]} $value[name]">$value[name]</a></td><td class="table-entry" style="word-break: normal;" align="center"><a href="?action=mp3&id=$value[mp3_id]" title="{$front_content[74]} $value[name]"><img src="images/other/player.gif"/></a></td></tr>
DYHB;
          }}else{
		     $_result.= "<tr><td>{$front_content[75]}</td><td></td><td></td></tr>";
	  }
	  $_result.="</table><div id=\"pagenav\">{$pagination}</div>";
	 }else{
		  $_result="<div class='f_showmp3'>";
		  //如果是添加的音乐
		  if($mp3_id){
	          //音乐播放处理
              $ShowMp3=$_Mp3s->GetOneMp3($mp3_id);
		      /** 获取留言板评论 */
              get_global_comments('mp3_id',$mp3_id);
		      $_result.=<<<DYHB
<span class="mp3_title">$ShowMp3[name]</span>
<div class="mp3_content">$ShowMp3[musicword]</div>
<div class="copy_mp3_url">
<p><a href="javascript:;" onclick="showajaxdiv('mp3', '$dyhb_options[blogurl]/getxml.php?mp3id=$mp3_id', 500);">{$front_content[76]}</a></p>
</div>
DYHB;
	          $_result.= _mp3player_show($ShowMp3[path]);
		  }elseif($upload_mp3_id){
		      //获取文件
			  $Mp3File=$_Photosorts->GetIdFile( $upload_mp3_id );
			  if($dyhb_options['photo_isshow_hide']=='1'){
	               $Mp3File_path=$dyhb_options[blogurl]."/file.php?id=$Mp3File[file_id]";
	           }else{
			       $Mp3File_path=$dyhb_options[blogurl]."/width/upload/".$Mp3File[path];
			   }
			   $file_path_type=$dyhb_options['photo_isshow_hide']=='1'?$front_content[77]:$front_content[78];
			   $is_image_leech=$dyhb_options['is_image_leech']=='1'?$front_content[79]:$front_content[80];
               $_result.=<<<DYHB
<span class="mp3_title">$Mp3File[name]</span>
<div class="mp3_content"><b><p>{$front_content[81]}</b><a href="javascript:;" onclick="showajaxdiv('file', '$dyhb_options[blogurl]/getxml.php?fileid={$Mp3File[file_id]}', 500);">{$front_content[82]}</a><br>({$front_content[83]}$file_path_type,$is_image_leech.)<br></p><p class="mp3_ku_list"><b>{$front_content[84]}</b><br><a href="index.php?action=photo&show=mp3">Mp3</a>&nbsp;&nbsp;<a href="index.php?action=photo&show=wma">Wma</a>&nbsp;&nbsp;<a href="index.php?action=photo&show=wav">Wav</a></p></div>
DYHB;
	          $_result.= _mp3player_show($Mp3File_path);
		  }
          $_result.="</div>";
	  }
      $_result.="<br><div class=\"mp3_selec\"><select onchange=\"javascript:location.href=this.value;\"><option value=\"\">{$front_content[85]}</option>";
      if($Mp3Sort){foreach($Mp3Sort as $value){$_result.="<option value=\"index.php?action=mp3&ms=$value[mp3sort_id]\">$value[name]</option>";}}
      $_result.="</select></div><br><br>";

	  /** 返回数据 */
	  return $_result;
}

/**
  * 模块头部
  *
  * @param string $View 全局变量
  * @return string
  */
function _index_model_header(){
	 global $View,$JustShow,$Mp3Id,$CoolId,$dyhb_premissions,$mp3_id,$front_content;
	 $modelhead_lang=$front_content['modelhead'];
	 $photosort_id=intval( get_args('di') );//相册分类
     $photo_id=intval( get_args('id') );//单个相片
	 $taotao_id=intval( get_args('id') );
     $JustShow=get_args('show') ;
	 $usergroup_id=intval( get_args('id') );
	 /** 头部开始 */
     $ModelHead='';
     switch($View){
          case "tag": $ModelHead=$modelhead_lang[0]; break;
          case "link": $ModelHead=$modelhead_lang[1]; break;
          case "search": $ModelHead=$modelhead_lang[2]; break;
          case "record": $ModelHead=$modelhead_lang[3]; break;
          case "mp3": 
			  if($mp3_id==''||$upload_mp3_id==''){ $ModelHead=$modelhead_lang[4]; }
		      else{ $ModelHead="$modelhead_lang[5] &nbsp;<a href=\"?action=mp3\">$modelhead_lang[6]</a>"; }
		  break;
		  case "user": $ModelHead=$modelhead_lang[7]; break;
		  case "trackback": $ModelHead=$modelhead_lang[8]; break;
		  case "usergroup": 
			  if($usergroup_id==''){$ModelHead=$modelhead_lang[9];}
		      else{$ModelHead="$modelhead_lang[10] ({$dyhb_premissions[gpname]}) $modelhead_lang[11]&nbsp;<a href=\"?action=usergroup\">$modelhead_lang[9]</a>";}
		  break;
          case "photo":
              if($JustShow){
			      if($JustShow=='photosort') $ModelHead="$modelhead_lang[15]&nbsp;<a href=\"?action=photo\">$modelhead_lang[13]</a>";
				  else $ModelHead="$modelhead_lang[14]&nbsp;<a href=\"?action=photo\">$modelhead_lang[13]</a>"; 
			  }
		      elseif($photosort_id){ $ModelHead="$modelhead_lang[15]&nbsp<a href=\"?action=photo\">$modelhead_lang[12]</a>"; }
	          elseif($photo_id){ $ModelHead="<a href=\"?action=photo&show=photosort\">$modelhead_lang[15]</a>&nbsp<a href=\"?action=photo\">$modelhead_lang[13]</a>&nbsp;$modelhead_lang[16]";}
              else{ $ModelHead="$modelhead_lang[13]&nbsp;<a href=\"?action=photo&show=photosort\">$modelhead_lang[15]</a>";}
          break;
          case "microlog": 
			  if($taotao_id==''){ $ModelHead=$modelhead_lang[17]; }
		      else{ $ModelHead="$modelhead_lang[18] &nbsp;<a href=\"?action=microlog\">$modelhead_lang[19]</a>"; }
		  break;
          case "guestbook": 
			  $ModelHead=$modelhead_lang[20]; 
		  break;
      }
     if($View==''&&$CoolId){
          $ModelHead=$modelhead_lang[21];
     }

     /** 返回数据 */
     return $ModelHead;
}

?>