<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * �ļ���log.function.php
        * ˵������־�б���
        * ���ߣ�Сţ��
        * ʱ�䣺2010-07-22 0:17
        * �汾��DoYouHaoBaby-blog �����.����˿�� (�ر��)
        * ��ҳ��www.doyouhaobaby.com
		* ��̳��bbs.56swun.com
@----------------------------------------@
  ---- ����������ջ����������Ĺ� ----
\**=====copyright (c) DoYouHaoBaby=====**/

/**
  * ��־��������
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
 * ��ǩ����
 *
 */
function TagAction($tags){
global $DB,$dyhb_options,$_Tags;
$a=array();
$i = 0;
$j = 0;
$tagnum = 0;//��ǩ����
$maxlognum = 0;//��ǩ������־��
$minlognum = 0;//��ǩ���ٵ���־��
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
$spread = ($tagnum>12?12:$tagnum);//������Ի�
$rank = $maxlognum-$minlognum;
$rank = ($rank==0?1:$rank);
$rank = $spread/$rank;
//��ȡ�ݸ�id
$hideBlogId = array();
foreach($result=$DB->getonerow("SELECT `blog_id` FROM `".DB_PREFIX."blog` where `isshow`='0' and `ispage`='0'") as $value){
  $hideBlogId[]=$value;
}
//��ǩ��ɫ
$c=unserialize($dyhb_options['side_tag_color']);
$i=1;
foreach($tags as $value){
  //�ų��ݸ���tag��־�����ͳ��
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
  * ��־�б��ҳ����
  *
  * @param string $content
  * @return array[0]
  */
function BreakLog($content){
	 $a = explode('[newpage]',$content,2);
	 return $a[0];
}

/**
  * ������־��ҳ����
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
  * Cms����
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
  * ��ǩ����
  *
  * @param string $tags
  * @param string $forwho if userǰ̨ esle��̨
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
  * ��ǩ����(���ű�ǩ)
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
  * ������ǩ
  *
  * @param string $tag ��ǩ
  * @param string $content ��־����
  * @return $content
  */
function highlight_tag($content,$tag) {
	global $dyhb_options;
	$tag = trim($tag);
	//�Ѿ����ڵı�ǩ���滻
	if(preg_match('/<a[^>]+?'.preg_quote($tag).'[^>]+?>/i',$content)) return $content;
	if(preg_match('/<img[^>]+?'.preg_quote($tag).'[^>]+?>/i',$content)) return $content;
    
	//����
	if(function_exists('eregi_replace')) {	
		//$tag2=urlencode($tag);
		$content = eregi_replace($tag, "<a href=\"javascript:;\" onclick=\"showajaxdiv('tag', '{$dyhb_options[blogurl]}/getxml.php?tagname={$tag}', 500);\" class=\"tagshow\">".htmlspecialchars($tag)."</a>", $content);	
	} else {
		$content = str_replace($tag, "<a href=\"javascript:;\" onclick=\"showajaxdiv('tag', '{$dyhb_options[blogurl]}/getxml.php?tagname={$tag}', 500);\" class=\"tagshow\">".htmlspecialchars($tag)."</a>", $content);
	}
	return $content;
}

/**
  * ����¼����ۣ��ݹ�
  *
  * @param array $arr �ӷ�������
  * @param int $father �������sort_id
  * @param int $floor �ݹ����
  * @param array $ShowComment2 ��־����������parentsort_id=0���������ݹ�ͬʵ��Ƕ�׷�ҳ
  * @param int $BlogId ��־��ID
  * @return unknow
  */
function getChild($arr, $father, $floor){
    global $ShowComment2,$_Sorts,$BlogId,$dyhb_options,$common_func;
	//�ж��Ƿ�����û��Զ������ۺ���
	if(!function_exists('your_getChild')){
    $floor++;
	/** Ƕ������ж� */
   if($floor<=$dyhb_options['thread_comments_depth']){
	   /** start */
       echo"<div class=\"comment-children\">
              <ol class=\"comment-list\">";
	   if($arr){
            foreach($arr as $key=>$row){
                if($row['parentcomment_id']== $father){
					/** �������� */
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
                    if(CheckPermission("seeip","����IP��",'0')){
                        $ip_mes="<div class=\"comment-ip\">{$row[ip]}</div>";
			        }
					$the_name=substr($the_name,'0','3')=='[m]'?str_replace("[m]","<img src='images/other/mobile.gif'/>",$the_name):$the_name;
					$comment=$row[ismobile]=='1'?this_is_mobile($row[comment],'2'):$row[comment];

					/** Ƕ���������ж� */                     
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
  * ���������
  *
  * @param array $arr ����������
  * @param array $ShowComment2 ��־����������parentsort_id=0���������ݹ�ͬʵ��Ƕ�׷�ҳ
  * @param int $BlogId ��־��ID
  * @return unknow
  */
function _list_showlog_comment($arr){
	global $ShowComment2,$BlogId,$dyhb_options,$common_func;
	//�ж��Ƿ�����û��Զ������ۺ���
	if(!function_exists('your_list_showlog_comment')){
    if($arr){
         foreach($arr as $key=>$row){
			/** �����������ݺ�����parentsort_idΪ0�Ĺ�ͬ�������ֵ */
            if($row["parentcomment_id"] == "0"&&($ShowComment2&&in_array($row,$ShowComment2))){
				 /** �������ݴ��� */
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
				 if(CheckPermission("seeip","����IP��",'0')){
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
  * ajax������Ϣ
  *
  * @param array $array ���ص��������ݣ���λ���飬ֻ��һ��ֵ
  * @param int $parentcomment_id �������sort_id
  * @return unknow
  */
function _ajax_return_back($array){
	 global $parentcomment_id,$dyhb_options,$common_func;
	 //�ж��Ƿ�����û��Զ������ۺ���
	if(!function_exists('your__ajax_return_back')){
	 if($array){
	      foreach($array as $row){
			  /** �������ݴ��� */
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
			  if($dyhb_options[com_examine]=='1'&&!CheckPermission("minpostinterval","�������Բ���Ҫ��ˣ�",'0')){
				  echo "<p class='ajax_examine_message'>{$common_func[88]}</p>";
			  }
              if(CheckPermission("seeip","����IP��",'0')){
                  $ip_mes="<div class=\"comment-ip\">{$row[ip]}</div>";
			  }
              /** �����ж��Ƕ������� */
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
  * ��ȡվ�������
  *
  * @param array $ShowComment ��������
  * @param array $ShowComment2 ��IDΪ0������
  * @param string $type �������ͣ����԰壬��־��΢���ͣ���ᣬ���֣�
  * @return unknow
  */
function get_global_comments($type,$value){
	  global $ShowComment,$ShowComment2,$dyhb_options,$DB,$_Comments,$pagestart,$newpage_c,$compage_c,$the_blog_url;
	 /**
      * Ƕ�����۴���$TotalCommentΪ��parentcomment_idΪ0���������������ڷ�ҳ
      * $ShowComment2Ϊ�������ҳ�������б�
	  * $ShowCommentΪ���е��������ݣ��������Ϲ�ͬ���Ƕ�����۷�ҳ����
	  *
	  */
      if(CheckPermission("seehiddencomment","����һƪ�������ۣ�",'0')){
   	      $is_show='';
      }else{
   	      $is_show="and `isshow`='1'";
      }
	  //���ͺ�ֵ�ж�
	  $blog_id=$type=='blog_id'&&$value?$value:'0';
	  $taotao_id=$type=='taotao_id'&&$value?$value:'0';
	  $file_id=$type=='file_id'&&$value?$value:'0';
	  $mp3_id=$type=='mp3_id'&&$value?$value:'0';

      //��ҳURL&&���԰������ж�
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
 * ��������
 *
 */
function get_global_comment_num($type,$value){
	global $DB;
	//����������ѯ
    if(CheckPermission("seehiddencomment","����һƪ�������ۣ�",'0')){
   	      $is_show='';
     }else{
   	      $is_show="and `isshow`='1'";
     }
	 $TotalComment=$DB->getresultnum("SELECT count(comment_id) FROM `".DB_PREFIX."comment` where `$type`='$value' $is_show");
	 return $TotalComment;
}

/**
 * ��������
 *
 */
function global_comment_options(){
	 global $Comment_to_id,$BlogId,$View,$photo_id,$mp3_id,$taotao_id,$BlogId,$PhotoId,$Mp3Id,$TaotaoId,$Global_comment_num,$ShowComment;
	  $Global_comment_num=count($ShowComment);
	  //�ָ�������
      $Comment_to_id='';
      //��־ID
      $BlogId=$BlogId?$BlogId:'0';
      //���԰�ID
      $PhotoId=$View=='photo'&&$photo_id?$photo_id:'0';
      //����ID
      $TaotaoId=$View=='microlog'&&$taotao_id?$taotao_id:'0';
      //����ID
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