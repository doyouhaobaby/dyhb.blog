<?php
/**
  * ��־URL��д
  *
  * @param string $dateline ��־����ʱ��
  * @param string $catename ��־����
  * @param int $blog_id ��־��id
  * @param string $post_name ��־�ı���
  * @return string
  */
function get_rewrite_dir($dateline, $catename,$blog_id,$post_name,$newpage='',$commentpage='') {
	global $dyhb_options;
	//��ҳ����
	$newpage=$newpage?"-".$newpage:'';
	$commentpage=$commentpage?"-".$commentpage:'';

	//url������ʽ����
	if( $dyhb_options['url_chinese_english']=='0' ){
	    $post_name=urlencode( $post_name );
	}

	if($dyhb_options['permalink_structure']!="default"){
         return str_replace(array('{y}','{m}','{d}','{category}','{blog_id}','{post_name}'), array(date('Y',$dateline),date('m',$dateline),date('d',$dateline),$catename,($blog_id.$newpage.$commentpage),($post_name.$newpage.$commentpage)),$dyhb_options['permalink_structure']);
	}else{
	     return "?p=".$blog_id;
	}

}
//��ȡ�����νӵ�ַ
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

//��־��ַ
function _showlog_posturl($value,$array=''){
	global $dyhb_options;
	$thevalue=_show_name_url($value);
	$url=get_rewrite_dir($value['dateline'],$thevalue[1],$value['blog_id'],$thevalue['0'],$array['0'],$array['1']);
	return $url;
}


/**
  * ����URL��д
  *
  * @param array $arraysort ��λ������������
  * @return string
  */
function get_rewrite_sort($arraysort){
   global $dyhb_options;
   if($dyhb_options['permalink_structure']!="default"||$dyhb_options['allowed_make_html']=='1'){
	  $a=array();
      if($arraysort){
       foreach($arraysort as $val){
		 //url������ʽ����
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
  * ��ǩ��д
  *
  * @param array $tagarray ��ǩһά����
  * @return string
  */
function get_rewrite_tag($tagarray){
	global $dyhb_options;
	$tag_name=$tagarray['urlname']?$tagarray['urlname']:$tagarray['name'];
	//url������ʽ����
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
  * �鵵��д
  *
  * @param int $year �鵵���
  * @param int $mouth �鵵�·�
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
  * ������д
  *
  * @param array $author ���������� example:array("1","admin")
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
  * α��̬URL����
  *
  * @param boolean $_UrlIs_xxx ��д�����ж�
  * @param string $the_value ���صķ����ṹ���������ݴ�����ѯ���ݿ�
  * @return unknow
  */
function url_analyse(){
	global $dyhb_options,$_UrlIsPage,$_UrlIsPlugin,$_UrlIsCategory,$_UrlIsRecord,$_UrlIsTag,$_UrlIsAuthor,$_UrlIsBlog,$_UrlIsPagenav;
    
    //��ʼurl����
	if($dyhb_options['permalink_structure']!="default"){
		/** ȥ����ַ��ͷ��www */
		if(substr($_SERVER["HTTP_HOST"],'0','4')=='www.'){
		    $_SERVER["HTTP_HOST"]=substr($_SERVER["HTTP_HOST"],'4');
		}
		if(substr($dyhb_options['blogurl'],'0','4')=='www.'){
		    $dyhb_options['blogurl']=substr($dyhb_options['blogurl'],'4');
		}
		$dyhb_options['blogurl']=trim($dyhb_options['blogurl'],'/');
        
		//���������url
        $urlbase= "http://".$_SERVER["HTTP_HOST"].(($_SERVER["SERVER_PORT"]==="80")?"":$_SERVER["SERVER_PORT"]).$_SERVER["REQUEST_URI"];

		//url������ʽ��������ת��Ϊ����
	    if( $dyhb_options['url_chinese_english']=='0' ){
	         $urlbase=urldecode($urlbase);
	    }
        
		//��ȡ����url
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
		

	    //�ж�'/'��ĩβ��һ�γ��ֵ�λ��
        $first_place=strpos($the_url,'/');
        /** ��ȡ���������� */
	    $the_start=substr($the_url,0,$first_place);
        /** �ж��Ƿ��ǵ����� */
		$pagenav_arr=array("tag.html","search.html","guestbook.html","microlog.html","link.html","photo.html","mp3.html","record.html");
        if(in_array($the_url,$pagenav_arr)){
	         $_UrlIsPagenav=true;
        }

	   /** ��ȡ����ֵ���ڲ�ѯ���ݿ� */
	   $the_value='';
	   $a=array();
	   $a=explode("/",$the_url);
	   if(substr($the_url,-1)=='/'){
	       $the_value=$a[count($a)-2];
	   }else{
	       $the_value=$a[count($a)-1];
	   }

	   /** �����ж� */
	   if($the_start=='page'){
            //ҳ��
	        $_UrlIsPage=true;
	   }elseif($the_start=='plugin'){
		    //���
	        $_UrlIsPlugin=true;
	   }elseif(($the_start==$dyhb_options['sort_base']&&$dyhb_options['sort_base']!="")||$the_start=='category'){
		    //����
	        $_UrlIsCategory=true;
	   }elseif($the_start=="record"){
		   //�鵵
	   	    $_UrlIsRecord=true;
	   }elseif(($the_start==$dyhb_options['tag_base']&&$dyhb_options['tag_base']!="")||$the_start=='tag'){
		    //��ǩ
	        $_UrlIsTag=true;
	   }elseif($the_start=='author'){
		    //����
            $_UrlIsAuthor=true;
	   }elseif($the_start==''){
	        //��ҳ
	   }
	   else{
	   	 if(!$_UrlIsPagenav&&$the_url!='3g'){
			 //��־
	   	 	 $_UrlIsBlog=true;
	   	 }
	   }

	   //�鵵
	   if($the_start=='record'){
	   	    $the_value=$a[count($a)-3].$a[count($a)-2];
	   }elseif(@in_array($the_url,$pagenav_arr)){
	   	    $the_value=substr_replace($the_url,'',-5);
	   }
	   //450.html��β��־�ж�
	   if($_UrlIsBlog&&$the_value!='index'){
	      $the_end=$a[count($a)-1];
	      $the_end_a=explode('.',$the_end);
	      if(count($the_end_a)>1){
              $the_value=$the_end_a['0'];
	      }
       }
	   //url������ʽ��������ת��Ϊ����
	    if( $dyhb_options['url_chinese_english']=='0' ){
	         return $the_value;
	    }else{
		     return GbkToUtf8(urldecode($the_value),'');
		}
    };
}

/**
 * д��α��̬����
 *
 * @param string $yes �Ƿ��rewrite
 * @param string $type ����ģʽ
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
			   
			      /** д��.htacess���� */
				  if($type=='1'){
                      $hasWrite = file_put_contents(DOYOUHAOBABY_ROOT. '/.htaccess', "<IfModule mod_rewrite.c>
RewriteEngine On 
RewriteBase {$basePath}
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ {$basePath}index.php/$1 [L]
</IfModule>");
                  }else{
					  //��ǿ������,ʹ��redirectʽrewrite����,��ȻЧ���е����,���Ƕ�fastcgiģʽ�����ԽϺ�
				      $hasWrite = file_put_contents(DOYOUHAOBABY_ROOT. '/.htaccess', "<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase {$basePath}
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . {$basePath}index.php [L]
</IfModule>");
                  } 
	           /** �ж��Ƿ��д */ 
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