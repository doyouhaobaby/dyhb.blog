<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * �ļ���backup.php
        * ˵�������ݹ���
        * ���ߣ�Сţ��
        * ʱ�䣺2010-07-22 0:17
        * �汾��DoYouHaoBaby-blog �����.����˿�� (�ر��)
        * ��ҳ��www.doyouhaobaby.com
		* ��̳��bbs.56swun.com
@----------------------------------------@
  ---- ����������ջ����������Ĺ� ----
\**=====copyright (c) DoYouHaoBaby=====**/

$filname =sql_check( get_argget('fil')) ;

//���ݿⱸ���ļ��б�
if($view==''||$view=='list'){
IsSuperAdmin($common_permission[44],'');
$listSql = glob('../width/backup/*.sql');
$BackupFile=array();
$i=0;
if($listSql){
foreach($listSql as $value){
    $BackupFile[$i]['maketime']=$MakeTime = ChangeDate(filemtime($value),'Y-m-d H:i:s');
	$BackupFile[$i]['size']=$Size =  changeFile(filesize($value));
	$BackupFile[$i]['backname']=$BackName = substr(strrchr($value,'/'),1);
	$i++;
}}
}

//ɾ�������ļ�
if($view=="del"){
   IsSuperAdmin($common_permission[45],'');
   $dir = "../width/backup";
   delete_file($dir);
   header("location:?action=backup&delall=true");
}
if($view=="delfil"&&$filname){
   IsSuperAdmin($common_permission[45],'');
   $filurl="../width/backup/".$filname;
   @unlink($filurl) OR DyhbMessage($common_func[248],'');
   header("location:?action=backup&del=true");
}

//��ȡcache�ļ��б�
if($view=="cachefile"){
   $listSql = glob('../width/cache/*.php');
   $CacheFile=array();
   $i=0;
   foreach($listSql as $value){
       $CacheFile[$i]['maketime']=$MakeTime = ChangeDate(filemtime($value),'Y-m-d H:i:s');
	   $CacheFile[$i]['size']=$Size =  changeFile(filesize($value));
	   $CacheFile[$i]['backname']=$BackName = substr(strrchr($value,'/'),1);
	   $i++;
   }
}

//���뱸���ļ�
if($view=="into"&&$filname){
	if(strtolower(substr(strrchr($filname,'.'),1))!='sql'){
		DyhbMessage($common_func[249],"?action=backup");
	}

	//�����ļ�ǰ׺���(������ͨ��ǰ׺�ж����ݿ��Ŀ����Ϊ�˾�������)
	$table=array('blog','comment','file','link','mp3','mp3sort','option','parentsort','photosort','plugin','sort','tag','taotao','trackback','user');
    $filurl="../width/backup/".$filname;
	$tablename=substr(strtolower($filname),0,strpos($filname,'-'));
	if(!in_array(substr($tablename,strlen(DB_PREFIX)),$table)){
	    DyhbMessage($common_func[250],'-1');
	}

	$DB->query("TRUNCATE `$tablename`");
    $intosql=file("$filurl");
	if($intosql){
    foreach($intosql as $value){
		if(trim($value)){
		  $DB->query($value);
		}
     }
   }
  header("location:?action=backup&upd=true");
}

//���»���
//���԰�
$TheCacheFiles=$admin_cache;

//�������л���
if($view=='clearallcache'){
   CheckPermission("cp",$common_permission[46]);
   $the_path="../width/cache";
   delete_file($the_path);
   header("location:?action=backup&do=cachefile&clearallcache=true");
}

//�������л���
if($view=="rebuild"){
    CheckPermission("cp",$common_permission[47]);
	//��ȡ��Ҫ����HTML�ĵ�ַ����
}
if($view=="cache"){
	CheckPermission("cp",$common_permission[47]);
	$cachetype=isset($_POST["cachetype"])?dyhb_addslashes(CheckSql(trim($_POST["cachetype"]))):"";
	switch($cachetype){
	  case all:UpdAllCache('','');break;
	  case cacheoptions:CacheOptions();break;
	  case cachetag:CacheTag();break;
	  case cacheblog:CacheBlog();break;
	  case cachenewlog:CacheNewLog();break;
	  case cacheblogger:CacheBlogger();break;
	  case cachecommentlog():CacheCommentLog();break;
	  case cachehotlog:CacheHotLog();break;
	  case cachenewmusic:CacheNewMusic();break;
	  case cacheyearhotlog:CacheYearHotLog();break;
	  case cachenewphoto:CacheNewPhoto();break;
	  case cachemouthhotlog:CacheMouthHotLog();break;
	  case cacherandlog:CacheRandLog();break;
	  case cachesidetao:CacheSideTao();break;
	  case cachenewcomment:CacheNewComment();break;
	  case cachelinks:CacheLinks();break;
	  case cachesorts:CacheSorts();break;
	  case cachethreesorts:CacheThreeSorts();break;
	  case cacherecord:CacheRecord();break;
	  case cachecmsnew:CacheCmsNew();break;
	  case cachecmssort:CacheCmsSort();break;
	  case cachepagenav:CachePageNav();break;
	  case cachephotosorts:CachePhotoSorts();break;
	  case cachenewphoto:CacheNewPhoto();break;
	  case cachemp3sorts:CacheMp3Sorts();break;
	  case cachepluginnav:CachePluginNav();break;
	  case cachepluginlist:CachePluginList();break;
	  case cacheflashlog: CacheFlashLog();break;
  }
  DyhbMessage($common_func[251],'?action=backup&do=rebuild&cachereb=true');
}

//��̬��
if($view=='html'){
    IsSuperAdmin($common_permission[48],'');
   //��ȡ��Ҫ���µ�HTML����
   $Htmltype = get_argpost('prepare');
   //�ȴ���ȡ���ݵĵ�ַ
   $Wait_to_html='';
   //����Ŀ��
   $Result_to_html='';
   //������Ϣ
   $resultinfo='';
   
   //������ת����
   $type=get_args('type');
   $total = intval( get_args('total') );
   $count = intval( get_args('count') );

    //�����ȡĬ��ֵ
   !$pagesize && $pagesize = 20;
   !$count && $count = 0;

   //������ҳ
   if($Htmltype=='index-x'||$_GET[type]=='index-x'){
	    $resultinfo='';
		$pagesize = intval( get_args('index_pagesize') );
       $Sql="`ispage`='0' and `isshow`='1'";
       
	   //��ȡ����
       if(!$total){
   	        $Totallog=$DB->getresultnum("SELECT count(blog_id) FROM ".DB_PREFIX."blog where $Sql");
   	        Page($Totallog,$dyhb_options['user_log_num'],'');
            $total=$lastpage;
	   }
	    if( $total>1 ){
   	     	for($j=1;$j<$pagesize;$j++){
				if( $total-$count>0 ){
				    $count2=$count+1;
   	   	   	        $Wait_to_html="index.php?page=$count2";
   	                $Result_to_html="index-{$count2}.html";
   	                $resultinfo.=MakeHtmk($Wait_to_html,$Result_to_html,$common_func[252]);
				}
			    $count++;
   	   	    }
		}

	   //��ȡ������
	   if ($total > 0) {
			$percent = ceil(($count/$total)*100);
		} else {
			$percent = 100;	
		}
		$barlen = $percent * 4;
		$url = "?action=backup&do=html&type=index-x&index_pagesize={$pagesize}&total={$total}&count={$count}";
		if ($total > $count) {
			$resultinfo.=$common_func[160].'<br /><div style="height:20px;width:400px;border:2px solid #03c3fa;"><div style="background:#ff4021;width:'.$barlen.'px"></div></div> '.$percent.'%';
			$resultinfo.="<script>setTimeout(\"window.location='$url'\",500);</script>";
			DyhbMessage($resultinfo,'0');
		}else {
			$resultinfo.= "$common_func[161] <b>$total</b>$common_func[162]<br><a href='?action=backup&do=html'>$common_func[163]</a><script>setTimeout(\"window.location='?action=backup&do=html'\",3000);</script>";
			DyhbMessage($resultinfo, '0');
	   }
   }

   //���·����б�
   if($Htmltype=='sort'||$_GET[type]=='sort'){
	    $resultinfo='';
        $Sql="`ispage`='0' and `isshow`='1'";

        //����
	    $sort_id = intval( get_args('s_sort_id') );
		$pagesize = intval( get_args('sort_pagesize') );

       //��Ҫ���ɷ���ID
	   $the_id_array=array();
	   if($sort_id || $sort_id=='-1'){
		  $Sql.="and `sort_id`='$sort_id'";  
	      //��ȡ����
          if(!$total){	    
   	           $Totallog=$DB->getresultnum("SELECT count(blog_id) FROM ".DB_PREFIX."blog where $Sql");
   	           Page($Totallog,$dyhb_options['user_log_num'],'');
               $total=$lastpage;
			   if(!$total) $total='1';
	      }

	      if( $total>=1 ){
   	     	  for($j=1;$j<$pagesize;$j++){
				  if( $total-$count>0 ){
				      $count2=$count+1;
					  //����URL��ȡ
					  if($sort_id>0){
					     $ListSort=$_Sorts->GetIdSort($sort_id);
					  }else{
					     $ListSort='';
					  }
	                  $resultinfo.=MakeSortHtml($ListSort);
			      }
			     $count++;
		     }  
   	   	  }
      
	   //��ȡ������
	   if ($total > 0) {
			$percent = ceil(($count/$total)*100);
		} else {
			$percent = 100;	
		}
		$barlen = $percent * 4;
		$url = "?action=backup&do=html&type=sort&s_sort_id={$sort_id}&sort_pagesize={$pagesize}&total={$total}&count={$count}";
		if ($total > $count&&$count>0) {
			$resultinfo.=$common_func[160].'<br /><div style="height:20px;width:400px;border:2px solid #03c3fa;"><div style="background:#ff4021;width:'.$barlen.'px"></div></div> '.$percent.'%';
			$resultinfo.="<script>setTimeout(\"window.location='$url'\",500);</script>";
			DyhbMessage($resultinfo,'0');
		}else {
			$resultinfo.= "$common_func[161]<b>$common_func[162]<br><a href='?action=backup&do=html'>$common_func[163]</a><script>setTimeout(\"window.location='?action=backup&do=html'\",3000);</script>";
			DyhbMessage($resultinfo, '0');
	   }
	 }else{
	     DyhbMessage($common_func[253],'');
	 }
   }

   //������
  $the_pagenavbar=array('link','tag','record','search','mp3','photo','microlog','guestbook','index');
  if($Htmltype&&in_array($Htmltype,$the_pagenavbar)){
	   $Return_message=MakePagenav($Htmltype);   	  
  }

   //��־��̬����
   if($Htmltype=='post'||$_GET['type']=='post'){
	  $Return_message='';
	 //����
	 $sort_id = intval( get_args('sort_id') );
	 $start = intval( get_args('start') );
     $end = intval( get_args('end') );
	 $pagesize = intval( get_args('log_pagesize') );
		
	 //�����ȡĬ��ֵ
	 !$pagesize && $pagesize = 20;
	 !$count && $count = 0;
		
	 //sql����
	 $Sql = "isshow=1 and password=''";
	 $Sql2="and password=''";
     if($sort_id){ 
		 $Sql.="and sort_id='$sort_id'"; 
		 $Sql2.="and sort_id='$sort_id'";
	  }
      if($start){ 
		  $Sql.="and blog_id>='$start'"; 
		  $Sql2.="and blog_id>='$start'";
	  }
      if($end) { 
		  $Sql.="and blog_id<='$end'";
		  $Sql2.="and blog_id<='$end'";
	  }

	  if (!$total) {
		   $total = $DB->getresultnum("SELECT count(blog_id) FROM ".DB_PREFIX."blog  WHERE $Sql");
	  }
		
	  //���ɾ�̬�ļ�
      $thelogs=$_Logs->GetLog($Sql2 ,$count,$pagesize,$isshow='1',$ispage='0');
	      if($thelogs){
		        foreach($thelogs as $value){	  
		             $resultinfo.=MakePostHtml($value);
		             $count ++;
		        }
	  }
		
	  //��ȡ������
	   if ($total > 0) {
			$percent = ceil(($count/$total)*100);
		} else {
			$percent = 100;	
		}
		$barlen = $percent * 4;
		$url = "?action=backup&do=html&type=post&sort_id={$sort_id}&log_pagesize={$pagesize}&start={$start}&end={$end}&total={$total}&count={$count}";
		if ($total > $count) {
			$resultinfo.=$common_func[160].'<br /><div style="height:20px;width:400px;border:2px solid #03c3fa;"><div style="background:#ff4021;width:'.$barlen.'px"></div></div> '.$percent.'%';
			$resultinfo.="<script>setTimeout(\"window.location='$url'\",500);</script>";
			DyhbMessage($resultinfo,'0');
		}else {
			$resultinfo.= "$common_func[254]<b>$total</b> $common_func[162]<br><a href='?action=backup&do=html'>$common_func[163]</a><script>setTimeout(\"window.location='?action=backup&do=html'\",3000);</script>";
			DyhbMessage($resultinfo, '0');
	   }
    }
       
	//����ҳ��
	if($Htmltype=='htmlfileclear'){
		$filelist= $_POST['filelist'];
		if($filelist){
		foreach ($filelist as $file) {
			$file = DOYOUHAOBABY_ROOT."/".$file;
			if ( is_file($file)) {
				if ( @unlink($file) ) {
					$Return_message.= "{$common_func[255]}$file -- <font color=\"green\">$common_func[256]</font><br />";
				} else {
					$Return_message.= "{$common_func[255]}$file -- <font color=\"red\">$common_func[255]</font><br />";
				}
			} else {
				remove_dir($file);
				$Return_message.= "{$common_func[258]}$file -- <font color=\"green\">$common_func[256]</font><br />";
			}
		  }
	   }
	}
}

//�������ݿ���ĳ���
if($view=='backup_add'){
    IsSuperAdmin($common_permission[49],'');
	//���ݿ��ṹ
    function get_table_structure($tablename)
    {
        $result=mysql_query("show create table $tablename");
        while($row=mysql_fetch_row($result))
         {
	        $structure.=$row[1].";";
	        $structure.="\n";
         }
        return $structure;
     }

    //���ݿ����ݱ����ļ�
    function get_values($tablename)
    {
	    global $backupinfo,$common_func;
        $rows = mysql_query("SELECT * FROM $tablename");
        $numfields = mysql_num_fields($rows);
        $numrows = mysql_num_rows($rows);
        while ($row = mysql_fetch_row($rows))
        {
           $start = "";
           @$value.= "INSERT INTO $tablename VALUES(";
           for($i = 0; $i < $numfields; $i++)
           {
              $value .= $start."'".mysql_escape_string($row[$i])."'";
              $start = ",";
           }
           $value .= ");\n";
        }
       $value .= "\n";
       $date=date("Y-m-d-H-i-s", time());
       $filename=$tablename."-TABLE".'-'.$date.".sql";
           $f=fopen("../width/backup/".$filename,"w+");
	       fwrite($f,$value);
	       fclose($f);
	       $backupinfo.="<tr><td><font color=green>$common_func[259]<a href=../width/backup/".$filename."><font color=red>".$filename.".sql</a></font>$common_func[256]</font></td></tr>";
      }

    //���ݿ����ɹ���
    $date=date("Y-m-d-H-i-s", time());
    /*@$tq=mysql_list_tables(DB_NAME);
    while($tr=mysql_fetch_row($tq)){
       $file.=get_table_structure($tr[0])."\n";
       get_values($tr[0])."\n";
    }*/
	//���ݿ��
	$table_a=array('blog','comment','file','link','mp3','mp3sort','option','photosort','plugin','session','sort','tag','taotao','trackback','user');
    foreach($table_a as $value){
       $file.=get_table_structure(DB_PREFIX.$value)."\n";
       get_values(DB_PREFIX.$value)."\n";
    }
    $filename=DB_NAME."-DATEBASE".'-'.$date.".sql";
    $f=@fopen("../width/backup/".$filename,"w+");
    @fwrite($f,$file);
    @fclose($f);
    $backup="<tr><td><font color=green>$common_func[260]<a href=../width/backup/".$filename."><font color=red>".$filename."</a></font>$common_func[256]</font></td></tr>";
    $backupinfo.=$backup;
}

include DyhbView('backup',1);

?>