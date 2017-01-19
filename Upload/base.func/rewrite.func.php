<?php
/**
  * 日志URL重写
  *
  * @param string $dateline 日志发表时间
  * @param string $catename 日志分类
  * @param int $blog_id 日志的id
  * @param string $post_name 日志的标题
  * @return string
  */
function get_rewrite_dir($dateline, $catename,$blog_id,$post_name,$newpage='',$commentpage='') {
	global $dyhb_options;
	//分页处理
	$newpage=$newpage?"-".$newpage:'';
	$commentpage=$commentpage?"-".$commentpage:'';

	//url语言样式处理
	if( $dyhb_options['url_chinese_english']=='0' ){
	    $post_name=urlencode( $post_name );
	}

	if($dyhb_options['permalink_structure']!="default"){
         return str_replace(array('{y}','{m}','{d}','{category}','{blog_id}','{post_name}'), array(date('Y',$dateline),date('m',$dateline),date('d',$dateline),$catename,($blog_id.$newpage.$commentpage),($post_name.$newpage.$commentpage)),$dyhb_options['permalink_structure']);
	}else{
	     return "?p=".$blog_id;
	}

}
//获取文章衔接地址
function _show_name_url($value){
   $value['urlname']=$value['urlname']?$value['urlname']:$value['title'];
   $thesort="";
   $a=array();
   if($value['sort']){
   foreach($value['sort'] as $val){
       if($val['urlname']){
           $val['name']=$val['urlname'];
       }
       $a[]=$val['name'];
      }
   }
   $thesort=implode('/',$a);
   if($thesort==""){$thesort="default";}
   return array($value[urlname],$thesort);
}

//日志网址
function _showlog_posturl($value,$array=''){
	global $dyhb_options;
	$thevalue=_show_name_url($value);
	$url=get_rewrite_dir($value['dateline'],$thevalue[1],$value['blog_id'],$thevalue['0'],$array['0'],$array['1']);
	return $url;
}


/**
  * 分类URL重写
  *
  * @param array $arraysort 二位分类数据数组
  * @return string
  */
function get_rewrite_sort($arraysort){
   global $dyhb_options;
   if($dyhb_options['permalink_structure']!="default"||$dyhb_options['allowed_make_html']=='1'){
	  $a=array();
      if($arraysort){
       foreach($arraysort as $val){
		 //url语言样式处理
	     if( $dyhb_options['url_chinese_english']=='0' ){
	          $val['name']=urlencode( $val['name'] );
	     }
         if($val['urlname']){		 
			 $val['name']= $val['urlname'] ;
         }
         $a[]=$val['name'];
      }
    }
    $thesort=implode('/',$a);
    if($thesort==""){$thesort="dafault";}
	    $sort_prefix=$dyhb_options['sort_base']?$dyhb_options['sort_base']."/":"category/";
        $sorturl= $sort_prefix.$thesort."/";
   }else{
   	    $url=$arraysort?$arraysort[count($arraysort)-1]['sort_id']:'-1';
        $sorturl= "?s=".$url;
   }
	return $sorturl;
}

/**
  * 标签重写
  *
  * @param array $tagarray 标签一维数组
  * @return string
  */
function get_rewrite_tag($tagarray){
	global $dyhb_options;
	$tag_name=$tagarray['urlname']?$tagarray['urlname']:$tagarray['name'];
	//url语言样式处理
	if( $dyhb_options['url_chinese_english']=='0' ){
	    $tag_name=urlencode( $tag_name );
	}
    if($dyhb_options['permalink_structure']!="default"&&$dyhb_options['allowed_make_html']=='0'){
		 $tag_prefix=$dyhb_options['tag_base']?$dyhb_options['tag_base']:"tag";
         return $tag_prefix."/".$tag_name."/";
	}else{
	     return "?t=".$tag_name;
	}
}

/**
  * 归档重写
  *
  * @param int $year 归档年份
  * @param int $mouth 归档月份
  * @return string
  */
function get_rewrite_record($year,$mouth){
    global $dyhb_options;
    if($dyhb_options['permalink_structure']!="default"&&$dyhb_options['allowed_make_html']=='0'){
        return "record/".$year."/".$mouth."/";
	}else{
	    return "?r=".$year.$mouth;
	}
}

/**
  * 作者重写
  *
  * @param array $author 父分类数组 example:array("1","admin")
  * @return string
  */
function get_rewrite_author($author){
    global $dyhb_options;
    if($dyhb_options['permalink_structure']!="default"&&$dyhb_options['allowed_make_html']=='0'){
        return "author/".$author[1]."/";
	}else{
	    return "?u=".$author[0];
	}
}

/**
  * 伪静态URL分析
  *
  * @param boolean $_UrlIs_xxx 重写类型判断
  * @param string $the_value 返回的分析结构，用于数据处理，查询数据库
  * @return unknow
  */
function url_analyse(){
	global $dyhb_options,$_UrlIsPage,$_UrlIsPlugin,$_UrlIsCategory,$_UrlIsRecord,$_UrlIsTag,$_UrlIsAuthor,$_UrlIsBlog,$_UrlIsPagenav;
    
    //开始url分析
	if($dyhb_options['permalink_structure']!="default"){
		/** 去掉网址开头的www */
		if(substr($_SERVER["HTTP_HOST"],'0','4')=='www.'){
		    $_SERVER["HTTP_HOST"]=substr($_SERVER["HTTP_HOST"],'4');
		}
		if(substr($dyhb_options['blogurl'],'0','4')=='www.'){
		    $dyhb_options['blogurl']=substr($dyhb_options['blogurl'],'4');
		}
		$dyhb_options['blogurl']=trim($dyhb_options['blogurl'],'/');
        
		//构造浏览器url
        $urlbase= "http://".$_SERVER["HTTP_HOST"].(($_SERVER["SERVER_PORT"]==="80")?"":$_SERVER["SERVER_PORT"]).$_SERVER["REQUEST_URI"];

		//url语言样式处理，编码转化为中文
	    if( $dyhb_options['url_chinese_english']=='0' ){
	         $urlbase=urldecode($urlbase);
	    }
        
		//获取基本url
		$blog_url_base=$dyhb_options['blogurl'];
		$the_http='http://';
		$the_url2='';
		if(substr($dyhb_options['blogurl'],'0','7')=='http://'){
		    $blog_url_base=substr($dyhb_options['blogurl'],'7');
		}

		$zimulu=explode('/',$blog_url_base);
		$erjiyumin=explode('.',$blog_url_base);
		if(count($zimulu)>=2){
			$the_url2=$the_http.$zimulu['1'].'.'.$zimulu['0'];
		}
		
		if(count($erjiyumin)>=3){
		    $the_url2=$the_http.$erjiyumin['1'].'.'.$erjiyumin['2'].'/'.$erjiyumin['0'];
		}

        $the_url=str_replace($dyhb_options['blogurl']."/",'',$urlbase);
		$the_url=str_replace($the_url2."/",'',$the_url);
		

	    //判断'/'在末尾第一次出现的位置
        $first_place=strpos($the_url,'/');
        /** 获取分析的数据 */
	    $the_start=substr($the_url,0,$first_place);
        /** 判断是否是单行条 */
		$pagenav_arr=array("tag.html","search.html","guestbook.html","microlog.html","link.html","photo.html","mp3.html","record.html");
        if(in_array($the_url,$pagenav_arr)){
	         $_UrlIsPagenav=true;
        }

	   /** 获取返回值用于查询数据库 */
	   $the_value='';
	   $a=array();
	   $a=explode("/",$the_url);
	   if(substr($the_url,-1)=='/'){
	       $the_value=$a[count($a)-2];
	   }else{
	       $the_value=$a[count($a)-1];
	   }

	   /** 其它判断 */
	   if($the_start=='page'){
            //页面
	        $_UrlIsPage=true;
	   }elseif($the_start=='plugin'){
		    //插件
	        $_UrlIsPlugin=true;
	   }elseif(($the_start==$dyhb_options['sort_base']&&$dyhb_options['sort_base']!="")||$the_start=='category'){
		    //分类
	        $_UrlIsCategory=true;
	   }elseif($the_start=="record"){
		   //归档
	   	    $_UrlIsRecord=true;
	   }elseif(($the_start==$dyhb_options['tag_base']&&$dyhb_options['tag_base']!="")||$the_start=='tag'){
		    //标签
	        $_UrlIsTag=true;
	   }elseif($the_start=='author'){
		    //作者
            $_UrlIsAuthor=true;
	   }elseif($the_start==''){
	        //首页
	   }
	   else{
	   	 if(!$_UrlIsPagenav&&$the_url!='3g'){
			 //日志
	   	 	 $_UrlIsBlog=true;
	   	 }
	   }

	   //归档
	   if($the_start=='record'){
	   	    $the_value=$a[count($a)-3].$a[count($a)-2];
	   }elseif(@in_array($the_url,$pagenav_arr)){
	   	    $the_value=substr_replace($the_url,'',-5);
	   }
	   //450.html结尾日志判断
	   if($_UrlIsBlog&&$the_value!='index'){
	      $the_end=$a[count($a)-1];
	      $the_end_a=explode('.',$the_end);
	      if(count($the_end_a)>1){
              $the_value=$the_end_a['0'];
	      }
       }
	   //url语言样式处理，编码转化为中文
	    if( $dyhb_options['url_chinese_english']=='0' ){
	         return $the_value;
	    }else{
		     return GbkToUtf8(urldecode($the_value),'');
		}
    };
}

/**
 * 写入伪静态规则
 *
 * @param string $yes 是否打开rewrite
 * @param string $type 那种模式
 * @return void
 */
function MakeRewrite($yes,$type){
	 global $dyhb_options;
     /** start now */
  if($yes){
	   if (!file_exists(DOYOUHAOBABY_ROOT. '/.htaccess')) {
             if (is_writeable(DOYOUHAOBABY_ROOT)) {
                  $Blog_url=$dyhb_options['blogurl'];
			      $t=parse_url($Blog_url);
                  $basePath = empty($t['path']) ? '/' : $t['path'];
                  $basePath = rtrim($basePath, '/') . '/';
			   
			      /** 写入.htacess规则 */
				  if($type=='1'){
                      $hasWrite = file_put_contents(DOYOUHAOBABY_ROOT. '/.htaccess', "<IfModule mod_rewrite.c>
RewriteEngine On 
RewriteBase {$basePath}
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ {$basePath}index.php/$1 [L]
</IfModule>");
                  }else{
					  //增强兼容性,使用redirect式rewrite规则,虽然效率有点地下,但是对fastcgi模式兼容性较好
				      $hasWrite = file_put_contents(DOYOUHAOBABY_ROOT. '/.htaccess', "<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase {$basePath}
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . {$basePath}index.php [L]
</IfModule>");
                  } 
	           /** 判断是否可写 */ 
			   if (false === $hasWrite) {
				   return false;
				   exit();
               }
		     }
	 }
  }else if (file_exists(DOYOUHAOBABY_ROOT . '/.htaccess')) {
        @unlink(DOYOUHAOBABY_ROOT . '/.htaccess');
  }
  return true;
}

?>