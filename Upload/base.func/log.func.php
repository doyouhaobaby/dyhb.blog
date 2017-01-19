<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * 文件：log.function.php
        * 说明：日志列表处理
        * 作者：小牛哥
        * 时间：2010-07-22 0:17
        * 版本：DoYouHaoBaby-blog 告别大二.想念丝瓜 (特别版)
        * 主页：www.doyouhaobaby.com
		* 论坛：bbs.56swun.com
@----------------------------------------@
  ---- 西南民大的天空还是蛮明亮的哈 ----
\**=====copyright (c) DoYouHaoBaby=====**/

/**
  * 日志缓存瘦身
  *
  * @param array $ListLog
  * @return array
  */
function ThinLog($ListLog,$isCms)
{
	global $dyhb_options;
	$i=0;
	if($ListLog){
	foreach($ListLog as $value){
	   $content=($isCms=='yes')?gbksubstr(strip_tags($value[content]),'0',$dyhb_options['cms_content_num']):gbksubstr(strip_tags($value[content]),'0','400');
	   $ListLog[$i]['content']=$content;
	   $ListLog[$i]['3']=$content;
	   $i++;
	}}
    return $ListLog;
}

/**
 * 标签处理
 *
 */
function TagAction($tags){
global $DB,$dyhb_options,$_Tags;
$a=array();
$i = 0;
$j = 0;
$tagnum = 0;//标签总数
$maxlognum = 0;//标签最多的日志数
$minlognum = 0;//标签最少的日志数
foreach($result=$_Tags->GetTag('','','') as $value){
$taglognum = substr_count($value['blog_id'], ',') - 1;
if($taglognum > $i){
  $maxlognum = $taglognum;
  $i = $taglognum;
}
if($taglognum < $j){
  $minlognum = $taglognum;
}
$j = $taglognum;
$tagnum++;
}
$spread = ($tagnum>12?12:$tagnum);//这里可以换
$rank = $maxlognum-$minlognum;
$rank = ($rank==0?1:$rank);
$rank = $spread/$rank;
//获取草稿id
$hideBlogId = array();
foreach($result=$DB->getonerow("SELECT `blog_id` FROM `".DB_PREFIX."blog` where `isshow`='0' and `ispage`='0'") as $value){
  $hideBlogId[]=$value;
}
//标签颜色
$c=unserialize($dyhb_options['side_tag_color']);
$i=1;
foreach($tags as $value){
  //排除草稿在tag日志数里的统计
  foreach ($hideBlogId as $val){
	$value['blog_id'] = str_replace(','.$val.',', ',', $value['blog_id']);
  }
  if($value['blog_id'] == ','){
	continue;
  }
  $lognum = substr_count($value['blog_id'], ',') - 1;
  $fontsize = 10 + round(($lognum - $minlognum) * $rank);//maxfont:22pt,minfont:10pt
  $a[$i] = array(
  'tag_id' => $value['tag_id'],
  'name'=>$value[name],
  'lognum' => $lognum,
  'fontsize'=>$fontsize,
  'color'=>$c[rand(0, count($c))%count($c)],
  'urlname'=>$value['urlname']
 );
 $i++;
}
 return $a;
}

/**
  * 日志列表分页处理
  *
  * @param string $content
  * @return array[0]
  */
function BreakLog($content){
	 $a = explode('[newpage]',$content,2);
	 return $a[0];
}

/**
  * 单条日志分页处理
  *
  * @param string $content
  * @param int $content
  * @return array
  */
function BreakOneLog(){
  global $IsMobile,$compage_c,$ShowLog,$dyhb_options,$newpage,$blog_id;
  $newpage=$newpage=='0'?'1':$newpage;
  $thevalue=_show_name_url($ShowLog);
  $url=get_rewrite_dir($ShowLog['dateline'],$thevalue[1],$ShowLog[blog_id],$thevalue[0]);
  if($dyhb_options['permalink_structure']!="default"){
	     $url.="?";
  }else{
	 $url.="&";
  }
  $a = explode('[newpage]',$ShowLog[content]);
  for($i=0;$i<=count($a)+1;$i++){if(!empty($a[$i])){
  if(!$IsMobile){
	if($newpage!=$i+1){
        $newpagenav.="<a href=\"{$url}newpage=".($i+1)."&page=".$compage_c."\">".($i+1)."</a>&nbsp;";
	}else{
	    $newpagenav.="<span class=\"current\">".($i+1)."</span>&nbsp";
	}
  }else{
	if($newpage!=$i+1){
        $newpagenav.="<a href=?action=show&id=$blog_id&newpage=".($i+1).">".($i+1)."</a>&nbsp;";
	}else{
	    $newpagenav.="<span class=\"current\">".($i+1)."</span>&nbsp";
	}
  }
 }
}
 $a[newpagenav]=$newpagenav;
 return $a;
}

/**
  * Cms配置
  *
  * @param string $a like this 4,5
  * @return array
  */
function CmsValue($a){
$b=array();
$i=trim($a);
$b[0]=substr($i,0,strpos($i,","));
$b[1]=substr($i,strpos($i,",")+1);
return $b;
}

/**
  * 标签处理
  *
  * @param string $tags
  * @param string $forwho if user前台 esle后台
  * @return string|html
  */
function MakeTag($tags,$forwho){
  if($tags){
    if($forwho=='user'){
    $taginfo='';
    foreach($tags as $value){
	   $url=get_rewrite_tag($value);
       $taginfo.="<a href=\"$url\">$value[name]</a>&nbsp;";
    }
  }else{
	 $taginfo='';
     foreach($tags as $value){
        $taginfo.="<a href=index.php?action=log&tag_id=".$value['tag_id'].">".$value['name']."</a>&nbsp;&nbsp;";
	 }
  }
  return $taginfo;
 }
}

/**
  * 标签排序(热门标签)
  *
  * @param string $tags
  * @return $tags
  */
function sorttag($tags){
   $a=array();
   foreach($tags as $key=>$value){
      $a[] = $value['lognum'];
   }
   array_multisort($a, SORT_DESC, $tags);
   return $tags;
}

/**
  * 高亮标签
  *
  * @param string $tag 标签
  * @param string $content 日志内容
  * @return $content
  */
function highlight_tag($content,$tag) {
	global $dyhb_options;
	$tag = trim($tag);
	//已经存在的标签则不替换
	if(preg_match('/<a[^>]+?'.preg_quote($tag).'[^>]+?>/i',$content)) return $content;
	if(preg_match('/<img[^>]+?'.preg_quote($tag).'[^>]+?>/i',$content)) return $content;
    
	//处理
	if(function_exists('eregi_replace')) {	
		//$tag2=urlencode($tag);
		$content = eregi_replace($tag, "<a href=\"javascript:;\" onclick=\"showajaxdiv('tag', '{$dyhb_options[blogurl]}/getxml.php?tagname={$tag}', 500);\" class=\"tagshow\">".htmlspecialchars($tag)."</a>", $content);	
	} else {
		$content = str_replace($tag, "<a href=\"javascript:;\" onclick=\"showajaxdiv('tag', '{$dyhb_options[blogurl]}/getxml.php?tagname={$tag}', 500);\" class=\"tagshow\">".htmlspecialchars($tag)."</a>", $content);
	}
	return $content;
}

/**
  * 输出下级评论，递归
  *
  * @param array $arr 子分类数组
  * @param int $father 父分类的sort_id
  * @param int $floor 递归深度
  * @param array $ShowComment2 日志所有评论与parentsort_id=0的评论数据共同实现嵌套分页
  * @param int $BlogId 日志的ID
  * @return unknow
  */
function getChild($arr, $father, $floor){
    global $ShowComment2,$_Sorts,$BlogId,$dyhb_options,$common_func;
	//判断是否存在用户自定义评论函数
	if(!function_exists('your_getChild')){
    $floor++;
	/** 嵌套深度判断 */
   if($floor<=$dyhb_options['thread_comments_depth']){
	   /** start */
       echo"<div class=\"comment-children\">
              <ol class=\"comment-list\">";
	   if($arr){
            foreach($arr as $key=>$row){
                if($row['parentcomment_id']== $father){
					/** 基本处理 */
	   	            $email=gravatar($row['email']);
	                $time=date($dyhb_options['commentdateformat'],$row[dateline]);
	                $img=$dyhb_options['show_avatars']=='1'?"<img class=\"avatar\" src=\"$email\" alt=\"{$row[name]}\"/>":'';
	                $noflollw=$dyhb_options['commentsrrlnofollow']=='1'?"external nofollow":'';
					$comment_really_url=show_log_commenturl($row);
					$the_comment_url=show_log_commentreply($row);
					$the_block_url=show_log_blockurl($row);
					$the_css_change=$floor%2=='0'?"comment-level-even":'comment-level-old';
					$the_css_change2=$key%2=='0'?"comment-even":'comment-odd';
					if($row[url]){
				        $the_name="<a href=\"{$row[url]}\" rel=\"{$noflollw}\">{$row[name]}</a>";
				    }else{
				        $the_name="$row[name]";
				    }
                    if(CheckPermission("seeip","隐藏IP！",'0')){
                        $ip_mes="<div class=\"comment-ip\">{$row[ip]}</div>";
			        }
					$the_name=substr($the_name,'0','3')=='[m]'?str_replace("[m]","<img src='images/other/mobile.gif'/>",$the_name):$the_name;
					$comment=$row[ismobile]=='1'?this_is_mobile($row[comment],'2'):$row[comment];

					/** 嵌套最大深度判断 */                     
					$reply=$floor==$dyhb_options['thread_comments_depth']?'':"<div class=\"comment-reply\"><a href=\"\" rel=\"{$noflollw}\" onclick=\"return DYHB_Comment.reply('comment-{$row[comment_id]}', {$row[comment_id]});\">{$common_func[86]}</a></div>";
                echo<<<DYHB
                       <li id="comment-{$row[comment_id]}" class="comment-body comment-child  $the_css_change $the_css_change2 comment-by-author">
					      <a name="comment-{$row[comment_id]}"></a>
                          <div class="comment-author">{$img}<cite class="fn">$the_name</cite></div>
                          <div class="comment-meta"><a href="$comment_really_url">{$time}</a></div>
					      $ip_mes<p>$comment</p>
DYHB;
                getChild($arr, $row["comment_id"], $floor);
                echo<<<DYHB
				   <div class="comment-block"><a rel='{$noflollw}' href="javascript:void(0);" onclick="smile('{$the_comment_url}');">#{$row[comment_id]}</a>
				   <a rel='{$noflollw}' href="javascript:void(0);" onclick="smile('{$the_block_url}');">{$common_func[87]}</a></div>
				   {$reply}</li>
DYHB;
			 }
          }
     }
	 /** end */
     echo  "</ol></div>";
  }
	}else{
	   your_getChild($arr, $father, $floor);
	}
}


/**
  * 输出父评论
  *
  * @param array $arr 父分类数组
  * @param array $ShowComment2 日志所有评论与parentsort_id=0的评论数据共同实现嵌套分页
  * @param int $BlogId 日志的ID
  * @return unknow
  */
function _list_showlog_comment($arr){
	global $ShowComment2,$BlogId,$dyhb_options,$common_func;
	//判断是否存在用户自定义评论函数
	if(!function_exists('your_list_showlog_comment')){
    if($arr){
         foreach($arr as $key=>$row){
			/** 所有评论数据和评论parentsort_id为0的共同作用输出值 */
            if($row["parentcomment_id"] == "0"&&($ShowComment2&&in_array($row,$ShowComment2))){
				 /** 基本数据处理 */
   	             $email=gravatar($row['email']);
	             $time=date($dyhb_options['commentdateformat'],$row[dateline]);
	             $img=$dyhb_options['show_avatars']=='1'?"<img class=\"avatar\" src=\"$email\" alt=\"{$row[name]}\"/>":'';
	             $noflollw=$dyhb_options['commentsrrlnofollow']=='1'?"external nofollow":'';
				 $comment_really_url=show_log_commenturl($row);
				 $the_comment_url=show_log_commentreply($row);
				 $the_block_url=show_log_blockurl($row);
				 $the_css_change=$key%2=='0'?"comment-odd":'comment-even';
				 if($row[url]){
				     $the_name="<a href=\"{$row[url]}\" rel=\"{$noflollw}\">{$row[name]}</a>";
				 }else{
				     $the_name="$row[name]";
				 }
				 $the_name=substr($the_name,'0','3')=='[m]'?str_replace("[m]","<img src='images/other/mobile.gif'/>",$the_name):$the_name;
				 $comment=$row[ismobile]=='1'?this_is_mobile($row[comment],'2'):$row[comment];
				 if(CheckPermission("seeip","隐藏IP！",'0')){
                     $ip_mes="<div class=\"comment-ip\">{$row[ip]}</div>";
			     }
                 echo<<<DYHB
                     <li id="comment-$row[comment_id]" class="comment-body comment-parent $the_css_change comment-by-author">
					   <a name="comment-{$row[comment_id]}"></a>
                       <div class="comment-author">{$img}<cite class="fn">$the_name</cite></div>
                       <div class="comment-meta"><a href="$comment_really_url">$time</a></div>{$ip_mes}<p>$comment</p>
DYHB;
                 getChild($arr, $row["comment_id"], "1");
                 echo<<<DYHB
					  <div class="comment-block"><a rel='{$noflollw}' href="javascript:void(0);" onclick="smile('{$the_comment_url}');">#{$row[comment_id]}</a>
				      <a rel='{$noflollw}' href="javascript:void(0);" onclick="smile('{$the_block_url}');">{$common_func[87]}</a></div>
                      <div class="comment-reply"><a href="" rel="{$noflollw}" onclick="return DYHB_Comment.reply('comment-{$row[comment_id]}', {$row[comment_id]});">{$common_func[86]}</a> </div></li>
DYHB;
           }
       }
    }
	}else{
	   your_list_showlog_comment($arr);
	}
}


/**
  * ajax返回消息
  *
  * @param array $array 返回的评论数据，二位数组，只有一个值
  * @param int $parentcomment_id 父分类的sort_id
  * @return unknow
  */
function _ajax_return_back($array){
	 global $parentcomment_id,$dyhb_options,$common_func;
	 //判断是否存在用户自定义评论函数
	if(!function_exists('your__ajax_return_back')){
	 if($array){
	      foreach($array as $row){
			  /** 基本数据处理 */
              $email=gravatar($row['email']);
	          $time=date($dyhb_options['commentdateformat'],$row[dateline]);
	          $img=$dyhb_options['show_avatars']=='1'?"<img class=\"avatar\" src=\"$email\" alt=\"{$row[name]}\"/>":'';
	          $noflollw=$dyhb_options['commentsrrlnofollow']=='1'?"external nofollow":'';
			  $comment_really_url=show_log_commenturl($row);
			  $the_comment_url=show_log_commentreply($row);
			  $the_block_url=show_log_blockurl($row);
			  if($row[url]){
				     $the_name="<a href=\"{$row[url]}\" rel=\"{$noflollw}\">{$row[name]}</a>";
				 }else{
				     $the_name="$row[name]";
				 }
			  if($dyhb_options[com_examine]=='1'&&!CheckPermission("minpostinterval","发布留言不需要审核！",'0')){
				  echo "<p class='ajax_examine_message'>{$common_func[88]}</p>";
			  }
              if(CheckPermission("seeip","隐藏IP！",'0')){
                  $ip_mes="<div class=\"comment-ip\">{$row[ip]}</div>";
			  }
              /** 这里判断是顶级评论 */
	          if($parentcomment_id==''||$parentcomment_id=='0'){
                   echo<<<DYHB
                       <li id="comment-{$row[comment_id]}" class="comment-body comment-parent comment-odd comment-by-author">
					   <a name="comment-{$row[comment_id]}"></a>
                       <div class="comment-author">{$img}<cite class="fn">$the_name</cite></div>
                       <div class="comment-meta"><a href="$comment_really_url">{$time}</a></div>{$ip_mes}<p>$row[comment]</p>
					   <div class="comment-block"><a rel='{$noflollw}' href="javascript:void(0);" onclick="smile('{$the_comment_url}');">#{$row[comment_id]}</a>
				       <a rel='{$noflollw}' href="javascript:void(0);" onclick="smile('{$the_block_url}');">{$common_func[87]}</a></div></li>
DYHB;
              }else{
                   echo<<<DYHB
                       <div class="comment-children">
                           <ol class="comment-list">
                                <li id="comment-{$row[comment_id]}" class="comment-body comment-child comment-level-even comment-odd  comment-by-author">
					               <a name="comment-{$row[comment_id]}"></a>
                                     <div class="comment-author"><img class="avatar" src="$email" alt="{$row[name]}" width="32" height="32" /><cite class="fn">$the_name</cite></div>
                                    <div class="comment-meta"><a href="$comment_really_url">{$time}</a></div>{$ip_mes}<p>$row[comment]</p>
					               <div class="comment-block"><a rel='{$noflollw}' href="javascript:void(0);" onclick="smile('{$the_comment_url}');">#{$row[comment_id]}</a>
				                   <a rel='{$noflollw}' href="javascript:void(0);" onclick="smile('{$the_block_url}');">{$common_func[87]}</a></div> 
                                </li>
                           </ol>
	                   </div>
DYHB;
                  }
         }
    }}else{
	   your__ajax_return_back($array);
	}
}


/**
  * 获取站点的留言
  *
  * @param array $ShowComment 所有评论
  * @param array $ShowComment2 父ID为0的评论
  * @param string $type 评论类型（留言板，日志，微博客，相册，音乐）
  * @return unknow
  */
function get_global_comments($type,$value){
	  global $ShowComment,$ShowComment2,$dyhb_options,$DB,$_Comments,$pagestart,$newpage_c,$compage_c,$the_blog_url;
	 /**
      * 嵌套评论处理，$TotalComment为父parentcomment_id为0的评论数量，用于分页
      * $ShowComment2为父分类分页的数据列表
	  * $ShowComment为所有的评论数据，两者相结合共同输出嵌套评论分页数据
	  *
	  */
      if(CheckPermission("seehiddencomment","这是一篇隐藏评论！",'0')){
   	      $is_show='';
      }else{
   	      $is_show="and `isshow`='1'";
      }
	  //类型和值判断
	  $blog_id=$type=='blog_id'&&$value?$value:'0';
	  $taotao_id=$type=='taotao_id'&&$value?$value:'0';
	  $file_id=$type=='file_id'&&$value?$value:'0';
	  $mp3_id=$type=='mp3_id'&&$value?$value:'0';

      //分页URL&&留言板数量判断
	  $the_guestbook;
	  if($blog_id){
		 $the_url=$the_blog_url;
	  }elseif($blog_id=="0"&&$taotao_id=="0"&&$file_id=="0"&&$mp3_id=="0"){
	     $the_url="?action=guestbook";
		 $the_guestbook="and `taotao_id`='0' and `file_id`='0' and `mp3_id`='0'";
	  }
	  else{
         $the_url="";
      }
      $TotalComment=$DB->getresultnum("SELECT count(comment_id) FROM `".DB_PREFIX."comment` where `$type`='$value' $the_guestbook $is_show and `parentcomment_id`='0'");
      if($TotalComment){
          Page($TotalComment,$dyhb_options['log_comment_num'],"$the_url");
          $ShowComment2=$_Comments->GetComment("and `parentcomment_id`='0' order by `comment_id` desc",$blog_id,$isshow=1,$pagestart,$dyhb_options['log_comment_num'],$taotao_id,$file_id,$mp3_id);
      }
      $ShowComment=$_Comments->GetComment("order by `comment_id` desc",$blog_id,$isshow=1,'','',$taotao_id,$file_id,$mp3_id); 
}

/**
 * 评论总数
 *
 */
function get_global_comment_num($type,$value){
	global $DB;
	//评论总数查询
    if(CheckPermission("seehiddencomment","这是一篇隐藏评论！",'0')){
   	      $is_show='';
     }else{
   	      $is_show="and `isshow`='1'";
     }
	 $TotalComment=$DB->getresultnum("SELECT count(comment_id) FROM `".DB_PREFIX."comment` where `$type`='$value' $is_show");
	 return $TotalComment;
}

/**
 * 评论配置
 *
 */
function global_comment_options(){
	 global $Comment_to_id,$BlogId,$View,$photo_id,$mp3_id,$taotao_id,$BlogId,$PhotoId,$Mp3Id,$TaotaoId,$Global_comment_num,$ShowComment;
	  $Global_comment_num=count($ShowComment);
	  //恢复框类型
      $Comment_to_id='';
      //日志ID
      $BlogId=$BlogId?$BlogId:'0';
      //留言板ID
      $PhotoId=$View=='photo'&&$photo_id?$photo_id:'0';
      //滔滔ID
      $TaotaoId=$View=='microlog'&&$taotao_id?$taotao_id:'0';
      //音乐ID
      $Mp3Id=$View=='mp3'&&$mp3_id?$mp3_id:'0';
      if($PhotoId){
           $Comment_to_id=$PhotoId;
      }elseif($TaotaoId){
           $Comment_to_id=$TaotaoId; 
      }elseif($Mp3Id){
           $Comment_to_id=$Mp3Id;
      }else{
           $Comment_to_id=$BlogId;
      }
}

?>