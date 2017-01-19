<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * 文件：upload.php
        * 说明：文件管理
        * 作者：小牛哥
        * 时间：2010-07-22 0:17
        * 版本：DoYouHaoBaby-blog 告别大二.想念丝瓜 (特别版)
        * 主页：www.doyouhaobaby.com
		* 论坛：bbs.56swun.com
@----------------------------------------@
  ---- 西南民大的天空还是蛮明亮的哈 ----
\**=====copyright (c) DoYouHaoBaby=====**/

require_once('global.php');

if(!defined('DOYOUHAOBABY_ROOT')) {
	exit('hi,friend!');
}

//swfload上传附件
if($view=='swfupload'){
	//CheckPermission("upload",$common_permission[10]);
    // Code for Session Cookie workaround
	if (isset($_POST["PHPSESSID"])) {
		session_id($_POST["PHPSESSID"]);
	} else if (isset($_GET["PHPSESSID"])) {
		session_id($_GET["PHPSESSID"]);
	}

	session_start();

	$POST_MAX_SIZE = ini_get('post_max_size');
	$unit = strtoupper(substr($POST_MAX_SIZE, -1));
	$multiplier = ($unit == 'M' ? 1048576 : ($unit == 'K' ? 1024 : ($unit == 'G' ? 1073741824 : 1)));

	if ((int)$_SERVER['CONTENT_LENGTH'] > $multiplier*(int)$POST_MAX_SIZE && $POST_MAX_SIZE) {
		header("HTTP/1.1 500 Internal Server Error");
		echo "POST exceeded maximum allowed size.";
		exit(0);
	}

// Settings
	$save_path = getcwd() . "/file/";
	$upload_name = "Filedata";
	$max_file_size_in_bytes = 2147483647;				// 2GB in bytes
	$extension_whitelist = array("doc", "txt", "jpg", "gif", "png");	//允许的文件
	$valid_chars_regex = '.A-Z0-9_ !@#$%^&()+={}\[\]\',~`-';				//允许的文件名字符
	
// Other variables	
	$MAX_FILENAME_LENGTH = 260;
	$file_name = "";
	$file_extension = "";
	$uploadErrors = array(
        0=>"文件上传成功",
        1=>"上传的文件超过了 php.ini 文件中的 upload_max_filesize directive 里的设置",
        2=>"上传的文件超过了 HTML form 文件中的 MAX_FILE_SIZE directive 里的设置",
        3=>"上传的文件仅为部分文件",
        4=>"没有文件上传",
        6=>"缺少临时文件夹"
	);

	if (!isset($_FILES[$upload_name])) {
		HandleError("No upload found in \$_FILES for " . $upload_name);
		exit(0);
	} else if (isset($_FILES[$upload_name]["error"]) && $_FILES[$upload_name]["error"] != 0) {
		HandleError($uploadErrors[$_FILES[$upload_name]["error"]]);
		exit(0);
	} else if (!isset($_FILES[$upload_name]["tmp_name"]) || !@is_uploaded_file($_FILES[$upload_name]["tmp_name"])) {
		HandleError("Upload failed is_uploaded_file test.");
		exit(0);
	} else if (!isset($_FILES[$upload_name]['name'])) {
		HandleError("File has no name.");
		exit(0);
	}
	
	$file_size = @filesize($_FILES[$upload_name]["tmp_name"]);
	if (!$file_size || $file_size > $max_file_size_in_bytes) {
		HandleError("File exceeds the maximum allowed size");
		exit(0);
	}
	
	if ($file_size <= 0) {
		HandleError("File size outside allowed lower bound");
		exit(0);
	}


	$file_name = preg_replace('/[^'.$valid_chars_regex.']|\.+$/i', "", basename($_FILES[$upload_name]['name']));
	if (strlen($file_name) == 0 || strlen($file_name) > $MAX_FILENAME_LENGTH) {
		HandleError("Invalid file name");
		exit(0);
	}


	if (file_exists($save_path . $file_name)) {
		HandleError("File with this name already exists");
		exit(0);
	}

   // Validate file extension
	$path_info = pathinfo($_FILES[$upload_name]['name']);
	$file_extension = $path_info["extension"];
	$is_valid_extension = false;
	foreach ($extension_whitelist as $extension) {
		if (strcasecmp($file_extension, $extension) == 0) {
			$is_valid_extension = true;

			break;
		}
	}
	if (!$is_valid_extension) {
		HandleError("Invalid file extension");
		exit(0);
	}

   function HandleError($message) {
	  header("HTTP/1.1 500 Internal Server Error");
	  echo $message;
   }

	if($_FILES['Filedata']['error'] != 4){
    $filepath=UploadFile($_FILES[Filedata][name],$_FILES[Filedata][tmp_name],$_FILES[Filedata][size],$_FILES[Filedata]['error'],$_FILES[Filedata]['type'], unserialize($dyhb_options[all_allowed_filetype]),$IsQQ='0',$dyhb_options['file_store_bydate']);
	$filename=$_FILES[Filedata][name];
	$filesize=$_FILES[Filedata][size];
	//附件数组*顺序很重要
     $SaveFileDate=array(
		     'photosort_id'=>'-1',
		     'path'=>$filepath,
		     'name'=>addslashes(GbkToUtf8($filename,'')),
		     'dateline'=>$localdate,
			 'filetype'=>$_FILES[Filedata]['type'],
		     'size'=>$filesize,
	 );
	 //写入附件
     $File_id=$_Photosorts->AddFile($SaveFileDate);
	}
	//更新静态化
	  if( $dyhb_options[allowed_make_html]=='1'){
              MakePagenav("photo");
	  }
	CacheNewPhoto();
	CachePhotoSorts();

	//处理完毕
    echo "File Received";
	exit(0);
}

if(!ISLOGIN){
   DyhbMessage("你没有登录不能使用文件管理器！",'0');
}

//上传表单
$PhotoSort=$_sidePhotoSorts;

//上传附件
if($view == 'upload'){
	CheckPermission("upload",$common_permission[10]);
   //获取表单值
   $newfile = isset($_FILES['newfile']) ?  $_FILES['newfile'] : '';
   $resultinfo="<ul>";
   if($newfile){
	  for ($i = 0; $i < count($newfile['name']); $i++){
		if($newfile['error'][$i] != 4){
			$filepath=UploadFile($newfile['name'][$i],$newfile['tmp_name'][$i],$newfile['size'][$i],$newfile['error'][$i], $newfile['type'][$i], unserialize($dyhb_options[all_allowed_filetype]),$IsQQ='0',$dyhb_options['file_store_bydate']);
			$isImg=strtolower(substr(strrchr($newfile['name'][$i], "."),1));
			//判断是否为图片附件
			if($isImg!='gif'&&$isImg!='jpg'&&$isImg!='jpeg'&&$isImg!='bmp'&&$isImg!='png'){
				$photosort='';
			}else{
                $photosort = intval( get_argpost('photosort_id')) ;
			}
			$filename=$newfile['name'][$i];
			$filesize=$newfile['size'][$i];
			//附件数组*顺序很重要
            $SaveFileDate=array(
		     'photosort_id'=>$photosort,
		     'path'=>$filepath,
		     'name'=>addslashes($filename),
		     'dateline'=>$localdate,
			 'filetype'=>$newfile['type'][$i],
		     'size'=>$filesize,
			 );
			$filetime=date('Y-m-d h:i:s',$localdate);
			//写入附件
             $File_id=$_Photosorts->AddFile($SaveFileDate);
			 //附件大小
			 $max_file_wh=unserialize($dyhb_options[all_width_height]);
			 $now_wh=ChangeImgSize("../width/upload/".$filepath,$max_file_wh[0],$max_file_wh[1]);
			 if($isImg!='gif'&&$isImg!='jpg'&&$isImg!='jpeg'&&$isImg!='bmp'&&$isImg!='png'){
				$filesize=changeFile($filesize);
				$resultinfo.=<<<DYHB
				<li id="filelist">
					<a href="$dyhb_options[blogurl]/width/upload/$filepath" target="_blank" title="$filename"><img src='../images/admin/file.gif' width="80" height="80" border="0"/></a><br>
					<span><a href="upload.php?do=list&type={$isImg}">$isImg</a></span>
					<a href="javascript:parent.addfiley('$dyhb_options[blogurl]/width/upload/$filepath','$filename','','$filesize','$filetime');">$common_func[122]</a><br>
				    <a href="javascript:parent.addfiley('$dyhb_options[blogurl]/file.php?id={$File_id}','$filename','','$filesize','$filetime');">$common_func23]</a>
				</li>
DYHB;
			 }else{
			  $_blank_url=$dyhb_options[blogurl]."/width/upload/{$filepath}";
			  $_blank_url2=$dyhb_options[blogurl]."/file.php?id={$File_id}";
			   //大图
	           $start=strpos($filepath,'/')+1;
			   if(substr($filepath,$start,'2')=='t-'){
              	   $_blank_url2=$_blank_url=$big_file_path=$dyhb_options[blogurl].'/width/upload/'.str_replace('t-','',$filepath);
			   }
              $resultinfo.=<<<DYHB
<li id="filelist">
<a href="$_blank_url" target="_blank" title="$filename"><img src="../width/upload/$filepath" width="80" height="80" border="0"/></a>
<br><a href="javascript:parent.insertInput('$dyhb_options[blogurl]/width/upload/$filepath', 'thumb');">$common_func[124]</a>
<a href="javascript:parent.addfile('$dyhb_options[blogurl]/width/upload/$filepath','{$_blank_url}','$filename','$now_wh[w]','$now_wh[h]','{$File_id}');">$common_func[125]</a><br>
<a href="javascript:parent.addfile('$dyhb_options[blogurl]/file.php?id={$File_id}','{$_blank_url2}','$filename','$now_wh[w]','$now_wh[h]','{$File_id}');">$common_func[123]</a>
</li>
DYHB;
			  }			
		    }
	      }
      }
	  //更新静态化
	  if( $dyhb_options[allowed_make_html]=='1'){
              MakePagenav("photo");
	  }
	  CacheNewPhoto();
	  CachePhotoSorts();
      $resultinfo.="</ul><div class=\"continuefile\"><a href=\"upload.php\">$common_func[126]</a></div>";
}

//附件库
if($view == 'list'){
	CheckPermission("viewphotolist",$common_permission['2'],'');
	CheckPermission("downfile",$common_permission[4],'');
	//表单数据
	$type=sql_check( get_argget('type') );//附件类型
	$photosort_id=sql_check( get_argget('photosort_id') );//相册分类
	//附件总量
	$photo_s=$photosort_id?"and `photosort_id`='$photosort_id'":'';
    $photo_s2=$photosort_id?"where `photosort_id`='$photosort_id'":'';
	if($type==''){
		$TotalFileNum=$DB->getresultnum("SELECT count(file_id) FROM `".DB_PREFIX."file` $photo_s2");
	}
	elseif($type=='myphoto'){
		$TotalFileNum=$DB->getresultnum("SELECT count(file_id) FROM `".DB_PREFIX."file` where path REGEXP '(jpg|jpeg|bmp|gif|png)$' $photo_s");
	} 
    elseif($type=='other'){
		$TotalFileNum=$DB->getresultnum("SELECT count(file_id) FROM `".DB_PREFIX."file` where path NOT REGEXP '(jpg|jpeg|bmp|gif|png)$' $photo_s");
	}else{
	    $TotalFileNum=$DB->getresultnum("SELECT count(file_id) FROM `".DB_PREFIX."file` where path  REGEXP '($type)$' $photo_s");
	} 

	if($TotalFileNum){
		Page($TotalFileNum,$dyhb_options['every_file_num']);
	   if($type==''){
		   $AllFiles=$_Photosorts->GetFiles($photosort_id,$pagestart,$dyhb_options['every_file_num']);
	   }elseif($type=='myphoto'){ 
		   $AllFiles=$DB->gettworow("select *from `".DB_PREFIX."file` where path REGEXP '(jpg|jpeg|bmp|gif|png)$' $photo_s order by `dateline` desc limit  $pagestart,$dyhb_options[every_photo_num]");
	   }elseif($type=='other'){
		   $AllFiles=$DB->gettworow("select *from `".DB_PREFIX."file` where path NOT REGEXP '(jpg|jpeg|bmp|gif|png)$' $photo_s order by `dateline` desc limit  $pagestart,$dyhb_options[every_photo_num]");
	   }else{
	       $AllFiles=$DB->gettworow("select *from `".DB_PREFIX."file` where path REGEXP '({$type})$' $photo_s order by `dateline` desc limit  $pagestart,$dyhb_options[every_photo_num]");
	   }
	}
	$resultinfo=<<<DYHB
	<form name="files" method="post" action="upload.php?do=prepare" name="thefile" id="thefile">
	<ul>
DYHB;
	if($AllFiles){
	foreach($AllFiles as $value){
		$isImg=strtolower(substr(strrchr($value[path], "."),1));
        //附件大小
		$max_file_wh=unserialize($dyhb_options[all_width_height]);
		$now_wh=ChangeImgSize("../width/upload/".$value[path],$max_file_wh[0],$max_file_wh[1]);
		if($isImg!='gif'&&$isImg!='jpg'&&$isImg!='jpeg'&&$isImg!='bmp'&&$isImg!='png'){   
			   $filetime=date('Y-m-d h:i:s',$value[dateline]);
			   $filesize=changeFile($value[size]);	   
			   if($isImg!='mp3'&&$isImg!='wma'&&$isImg!='wav'){
			      $action="<a href=\"../width/upload/$value[path]\">$common_func[127]</a>";
				  $file_down="{$dyhb_options[blogurl]}/width/upload/$value[path]";
			   }else{
			      $action="<a href=\"../?action=mp3&fid={$value[file_id]}\" target=\"_blank\">$common_func[128]</a>";
				  $file_down="?action=mp3&fid=$value[file_id]";
			   }
			   $resultinfo.=<<<DYHB
				 <li id="filelist">
			    <a href="{$dyhb_options[blogurl]}/width/upload/$value[path]" target="_blank" title="{$common_func[129]}$value[name]"><img src="../images/admin/file.gif" width="80" height="80" border="0"/></a><br><input name="files[]" type="checkbox" value="$value[file_id]" class="file_ids"><a href="upload.php?do=list&type={$isImg}">$common_func[130]</a><br>
			{$action}
	          <a href="javascript:parent.addfiley('$file_down','$value[name]','$value[file_id]','$filesize','$filetime');">$common_func[131]</a>
				<br><span>$isImg</span>
				    <a href="upload.php?do=upd&id={$value[file_id]}">$common_func[132]</a><br>
				<a href="javascript:parent.addfiley('$file_down','$value[name]','$value[file_id]','$filesize','$filetime');">$common_func[133]</a>
				<a href="javascript:dyhb_act('$value[file_id]', 'file');">$common_func[134]</a>
			 </li>
DYHB;
	   }else{  
			 $thesort=$_Photosorts->GetSorts($value[photosort_id]);//相册分类
			 if($value[photosort_id]=='-1'){$thesort=array('photosort_id'=>'-1','name'=>$common_func[135]);}
			 $_blank_url=$dyhb_options[blogurl]."/width/upload/$value[path]";
			 $_blank_url2=$dyhb_options[blogurl]."/file.php?id=$value[file_id]";
			   //大图
	           $start=strpos($value[path],'/')+1;
			   if(substr($value[path],$start,'2')=='t-'){
              	   $_blank_url2=$_blank_url=$big_file_path=$dyhb_options[blogurl].'/width/upload/'.str_replace('t-','',$value[path]);
			   }
             $resultinfo.=<<<DYHB
			 <li id="filelist">
			    <a href="{$_blank_url}" target="_blank" title="$value[name]"><img src="../width/upload/$value[path]" width="80" height="80" border="0"/></a><br><input name="files[]" type="checkbox" value="$value[file_id]" class="file_ids"><a href="upload.php?do=list&photosort_id=$value[photosort_id]">$thesort[name]</a><br>
	            <a href="javascript:parent.insertInput('$dyhb_options[blogurl]/width/upload/$value[path]', 'thumb');">$common_func[124]</a>
	<a href="javascript:parent.addfile('{$dyhb_options[blogurl]}/width/upload/$value[path]','{$_blank_url}','{$value[name]}','$now_wh[w]','$now_wh[h]','{$value[file_id]}');">$common_func[131]</a>
				<br><a href="upload.php?do=cover&id={$value[file_id]}&url={$dyhb_options[blogurl]}/width/upload/{$value[path]}">$common_func[136]</a>
				    <a href="upload.php?do=upd&id={$value[file_id]}">$common_func[132]</a><br>
				<a href="javascript:parent.addfile('{$dyhb_options[blogurl]}/file.php?id={$value[file_id]}','{$_blank_url2}','{$value[name]}','$now_wh[w]','$now_wh[h]','{$value[file_id]}');">$common_func[133]</a>
				<a href="javascript:dyhb_act('$value[file_id]', 'file');">$common_func[134]</a>
			 </li>
DYHB;
		  }
	}
  }
  $resultinfo.="</ul><input name=\"prepare\" id=\"prepare\" value=\"\" type=\"hidden\" />";
  $resultinfo.="<div class='pagination'>".$pagination."</div>";
  $resultinfo.=<<<DYHB
  <div class="morecontrol"><font size="2">$common_func[137]</font>
	 <a href="javascript:dyhb_fileaction('del');">$common_func[134]</a>&nbsp;&nbsp;
     <a href="javascript:dyhb_fileaction('defaultinsert');">$common_func[138]</a>&nbsp;&nbsp;
	 <a href="javascript:dyhb_fileaction('daolianinsert');">$common_func[139]</a>&nbsp;&nbsp;
     <select name="filesort" id="filesort" onChange="dyhb_changefilesort(this);">
	   <option value="" selected="selected">$common_func[140]</option>
DYHB;
     if($PhotoSort){
	 foreach($PhotoSort as $value){$resultinfo.=<<<DYHB
	   <option value="$value[photosort_id]">$value[name]</option>
DYHB;
      }
	}
	 $resultinfo.=<<<DYHB
<option value="-1">$common_func[125]</option>
</select>&nbsp;&nbsp;&nbsp;
<input onclick="CheckAll('selectAll',this)" type="checkbox" value="on" name="chkall"><font size="2">$admin_common[7]</font>
<input type="checkbox" name="invest" value="checkbox" onClick="CheckAll()"><font size="2">$admin_common[8]</font>
<span><select onchange="javascript:location.href=this.value;"><option value="">$common_func[141]</option>
DYHB;
	//全站附件格式，以及下拉列表
	$all_allowed_filetype=unserialize( $dyhb_options[all_allowed_filetype] );
	if($all_allowed_filetype){foreach($all_allowed_filetype as $value){$resultinfo.= "<option value=\"upload.php?do=list&type=$value\">$value</option>";}}
	$resultinfo.=<<<DYHB
    </select></span>
	</div>
	</form>
DYHB;
}

//头像列表
if($view=='listqq'){
	$resultinfo="<ul>";
	$handler = opendir('../images/qq/');
    while( ($filename = readdir($handler)) !== false ) {
     if($filename != '.'&& $filename != '..'){
      $resultinfo.=<<<DYHB
<li id="filelist">
<a href="../images/qq/$filename" target="_blank"><img src="../images/qq/$filename" width="80" height="80" border="0"/></a>
<br><a href="javascript:dyhb_act('$filename', 'delqq');">$common_func[134]</a>
</li>
DYHB;
     }
   }
   $resultinfo.="</ul>";
}

//删除头像
$qqname=sql_check( get_argget('qqname') );
if($view=='delqq'&&$qqname){
	IsSuperAdmin($common_permission[11],'');
	if(file_exists('../images/qq/'.$qqname)){
		@unlink('../images/qq/'.$qqname) OR DyhbMessage($common_func[119],'');
	}
	//删除原图
	$start=strpos($qqname[path],'/')+1;
	$old_file_path='../width/upload/'.str_replace('t-','',$qqname[path]);
	if(substr($qqname[path],$start,'2')=='t-'&& file_exists($old_file_path)){
		@unlink($old_file_path) OR DyhbMessage($common_func[120],'');
	}
	CacheNewPhoto();
	CachePhotoSorts();
	//更新静态化
	  if( $dyhb_options[allowed_make_html]=='1'){
              MakePagenav("photo");
	  }
    DyhbMessage("<font color=green>{$common_func[142]}</font>",'upload.php?do=listqq');
}

//更新信息
if($view=='upd'){
      $id=sql_check( get_argget('id') );
      //获取信息，并判断，如果为未分类，则不用设置封面
	  $the_file=$_Photosorts->GetIdFile($id);
	  if(!$_POST[ok]){
	  $resultinfo=<<<DYHB
	  <br>
<form id="form" action="upload.php?do=upd&id={$id}" method="post" enctype='multipart/form-data'  style="text-align:center;">
<input name="photosort_id"  type="hidden" value="$delphotosortid" />
 <label><font size="2">$common_func[143]</font> </label> 
<input name="name"  type="text" value="$the_file[name]" /><br />
<label><font size="2">$common_func[144]</font></label>
<input name="description"  type="text" value="$the_file[description]" /><br />
<label><font size="2">$common_func[145]</font> </label>
<input type="file" name='newupfile'>
<div align="center">
<input id="button" type="submit"  value="$common_func[132]" name="ok"/>  
</div>
</form>
DYHB;
	  }else{
		 //删除原文件
         CheckPermission("cp",$common_permission[12]);
	     $name = sql_check( get_argpost('name') );
		 $description= sql_check( get_argpost('description') );
		 $file_path=$the_file[path];
		 $file=$_FILES[newupfile];
		 if($file[error]!='4'){
	         if(file_exists('../width/upload/'.$the_file[path])){
		         @unlink('../width/upload/'.$the_file[path]) OR DyhbMessage($common_func[120],'');
	         }
			 //删除原图
			 $start=strpos($the_file[path],'/')+1;
			 $old_file_path='../width/upload/'.str_replace('t-','',$the_file[path]);
			 if(substr($the_file[path],$start,'2')=='t-'&& file_exists($old_file_path)){
			      @unlink($old_file_path) OR DyhbMessage($common_func[120],'');
			 }
		     //上传文件
		     $file_path=UploadFile($file['name'],$file['tmp_name'],$file['size'],$file['error'], $file['type'], unserialize($dyhb_options[all_allowed_filetype]),$IsQQ='0',$dyhb_options['file_store_bydate']);
		 }
		 
         //更新数据
		 $filedate=array('file_id'=>$id,'path'=>$file_path,'size'=>$file['size'],'name'=>$name,'description'=>$description);
         $_Photosorts->UpdFile($filedate,$id);
		 CacheNewPhoto();
		 CachePhotoSorts();
		 //更新静态化
	    if( $dyhb_options[allowed_make_html]=='1'){
              MakePagenav("photo");
	    }
		 DyhbMessage("<font color=green>$common_func[147]</font>",'upload.php?do=list');
	  }
}

//封面
if($view=='cover'){
	  //参数
      $url=sql_check( get_argget('url') );
	  $id=sql_check( get_argget('id') );
      //获取信息，并判断，如果为未分类，则不用设置封面
	  $the_file=$_Photosorts->GetIdFile($id);
	  if($the_file[photosort_id]!='-1'){
		   $photosortdate=array('photosort_id'=>$the_file[photosort_id],'cover'=>$url);
	       $_Photosorts->UpdSort($photosortdate,$the_file[photosort_id]); 
		   DyhbMessage("<font color=green>$common_func[148]</font>","upload.php?do=list&photosort_id=".$the_file[photosort_id]);
	  }else{
	       DyhbMessage("<font color=red>$common_func[149]</font>","upload.php?do=list&photosort_id=-1");
	  }
}


//附件整理
if($view=='waittodo'){
	CheckPermission("cp",$common_permission[13]);
    $resultinfo=<<<DYHB
	<p><a href="upload.php?do=clearfile">$common_func[150]</a></p>
	<p><span>$common_func[151]</span></p>	
    <p><b><span>$common_func[152]</span></b></p>
    <p><span>$common_func[153]</span></p>
	<form action="upload.php?do=repairefile" method="post">
	<p><span>$common_func[154] <input type="text" name="start" value="" size="5" /></span></p>
	<p><span>$common_func[155] <input type="text" name="end" value="" size="5" /></span></p>
	<p><span>$common_func[156] <input type="text" name="pagesize" value="200" size="5" /></span></p>
	<p><input type="submit" value="$common_func[157]" /></p>
	</form>
DYHB;
}

//清理附件（删除那些实际中存在的附件，而在数据库中没有记录的附件）
if($view=='clearfile'){
	CheckPermission("cp",$common_permission[14]);

  //清理附件函数
  function clearFile($fileDir){ 
	global $DB;
    $handler = opendir($fileDir);
    while( ($filename = readdir($handler)) !== false ) {
      if($filename != '.'&& $filename != '..'){   
		  
		   //按月归档
		   if(preg_match("/[0-9]{6,}/",$fileDir)){
              $file_path=substr($fileDir,'-7').$filename;
			  //是否是大图判断，缩略图路径获取
		      $thumb_url_path=substr($fileDir,'-7')."t-".$filename;
		   }else//默认目录
		   {
		      $file_path=substr($fileDir,'-8').$filename;
			  //是否是大图判断，缩略图路径获取
		      $thumb_url_path=substr($fileDir,'-8')."t-".$filename;
		   }
           
		   //判断它是否为大图，它是否存在缩略图
		   //$hasThumb=false;
		   $this_is_big=false;
		   $big_file_path='';
		   //是否存在缩略图判断
		   if( substr( $filename,'0','2' ) =='t-'){
		       $hasThumb=true;
			   $big_file_path=substr( $filename,'2' );
		   }
		   
           //是否是大图判断
		   if( file_exists( "../width/upload/".$thumb_url_path ) ){
		       $this_is_big=true;
			   //exit("../width/upload/".$thumb_url_path);
		   }

		   if(!$result=$DB->getonerow("select *from `".DB_PREFIX."file` where `path`='$file_path'")){
			   //删除数据库中没有有信息的数据
			   if($this_is_big===false){
                   @unlink($fileDir.$filename) OR DyhbMessage($common_func[158],"-1");
			   }
			   //删除原图
		       if( $hasThumb===true ){
			        @unlink($fileDir.$big_file_path) OR DyhbMessage($common_func[158],"-1");
		       }
		   }
	   }
	}
 }

 //遍历一个目录下的所有目录
  function   getDir($Dir){   
  $dp   =   opendir($Dir);   
    while(($file   =   readdir($dp))   !==   false)  {   
    if ($file!="."&&$file!=".."&&$file!="")   {   
       if   (is_dir($Dir.$file))   {   
		   clearFile('../width/upload/'.$file.'/');
       }  
    }   
   }
  } 
  //执行删除
  getDir('../width/upload/');
   CacheNewPhoto();
   CachePhotoSorts();
		 //更新静态化
	    if( $dyhb_options[allowed_make_html]=='1'){
              MakePagenav("photo");
	    }
  DyhbMessage($common_func[159],"upload.php?do=waittodo");
}

//修复附件（删除数据库中有信息，而实际上附件已经不存在的附件了）
if($view=='repairefile'){
  CheckPermission("cp",$common_permission[15]);
   //获取值
   $pagesize = intval( get_args('pagesize') );
   $total = intval( get_args('total') );
   $count = intval( get_args('count') );
   $start = intval( get_args('start') );
   $end = intval( get_args('end') );

   //不填，则取默认值
   !$pagesize && $pagesize = 200;
   !$count && $count = 0;
    
	//sql条件
	$Sql = "";

    if($start && $end){ 
		  $Sql.="where file_id>='$start' and file_id<='$end'"; 
	 }elseif($start) { 
		  $Sql.="where file_id>='$start'";
	}elseif($end){
	      $Sql.="where file_id<='$end'";
	}
	
   
   if (!$total) {
		   $total = $DB->getresultnum("SELECT count(file_id) FROM ".DB_PREFIX."file $Sql");
	  }

	//获取附件信息
     $thefiles=$_Photosorts->GetFiles('',$count,$pagesize,$Sql );
	 if($thefiles){
		 foreach($thefiles as $value){	  
		     if(!file_exists('../width/upload/'.$value[path])){
                  $_Photosorts->DelFile($value[file_id]);
				  $total--;
	         }
		     $count ++;
		 }
     }
	 
   //获取进度条
	   if ($total > 0) {
			$percent = ceil(($count/$total)*100);
		} else {
			$percent = 100;	
		}
		$barlen = $percent * 4;
		$url = "upload.php?do=repairefile&pagesize={$pagesize}&start={$start}&end={$end}&total={$total}&count={$count}";
		if ($total > $count) {
			$resultinfo=$common_func[160].'<br /><div style="height:20px;width:400px;border:2px solid #03c3fa;text-align:left;"><div style="background:#ff4021;width:'.$barlen.'px"></div></div> '.$percent.'%';
			$resultinfo.="<script>setTimeout(\"window.location='$url'\",500);</script>";
			DyhbMessage($resultinfo,'0');
		}else {
			$resultinfo= "$common_func[161] <b>$total</b> $common_func[162]<br><a href='upload.php?do=waittodo'>$common_func[163]</a><script>setTimeout(\"window.location='upload.php?do=waittodo'\",3000);</script>";
			CacheNewPhoto();
			CachePhotoSorts();
		 //更新静态化
	    if( $dyhb_options[allowed_make_html]=='1'){
              MakePagenav("photo");
	    }
			DyhbMessage($resultinfo, '0');
	   }
}

//我的相册
if($view=='myphoto'){   
	$resultinfo.=<<<DYHB
    <ul>
       <li id="filelist">
	      <a href="upload.php?do=list&photosort_id=-1"  title="$common_func[135]"><img src="../images/admin/photosort.gif" width="170" height="170" border="0"/></a><br>
	      <a href="upload.php?do=updsort&id=-1">$common_func[135]</a>
	      <a href="javascript:dyhb_act('-1', 'photosort');">$common_func[134]</a>
       </li>
DYHB;
	if($PhotoSort){
	foreach($PhotoSort as $value){
	$Result=$_Photosorts->GetSorts($value[photosort_id]);//相册封面，没有则调用系统默认的图片
	$cover=$Result[cover]?$Result[cover]:"../images/admin/photosort.gif";
    $resultinfo.=<<<DYHB
		<li id="filelist">
		    <a href="upload.php?do=list&photosort_id=$value[photosort_id]"  title="$value[sortname]"><img src="$cover" width="170" height="170" border="0"/></a>
		    <br><a href="upload.php?do=updsort&id=$value[photosort_id]">$value[name]</a>				  
		    <a href="javascript:dyhb_act('$value[photosort_id]', 'photosort');">$common_func[134]</a>
		</li>
DYHB;
     }  
    }
   $resultinfo.=<<<DYHB
	  </ul>
	   <div class="continuefile">
	        <form name="photosort" method="post" action="upload.php?do=addphotosort" id="photosort">
	            <input name="name" type="text" size="6"><br><input type="submit" value="$common_func[164]">
	        </form>
	   </div>
DYHB;
}

//添加相册
if($view=='addphotosort'){
	IsSuperAdmin($common_permission[16],'');
	//相册数据
	 $photosort_id = intval( get_argpost('photosort_id') );
	 $sortname = sql_check( get_argpost('name') );
	 if($sortname==''){DyhbMessage("<font color=\"red\"><b>$common_func[165]</b></font>","-1");}//判断
     $cover = sql_check( get_argpost('cover') );
	 $compositor= intval( get_argpost('compositor') );
	 //格式检查
     isurl($cover);
     //相册数组*顺序很重要
     $SavePhotosortDate=array(
		 'photosort_id'=>$photosort_id,
		  'name'=>addslashes($sortname),
		  'cover'=>$cover,
		  'compositor'=>$compositor,
		 );
	 $SavePhotosortDate=dyhb_addslashes($SavePhotosortDate);

	 if($photosort_id>0){//更新相册
		 $_Photosorts->UpdSort($SavePhotosortDate,$photosort_id);
	 }
	 else{//保存相册	      
		 $_Photosorts->AddSort($SavePhotosortDate);      
	 }
     CacheNewPhoto();
	 CachePhotoSorts();     
	if($photosort_id>0){
          DyhbMessage("<font color=\"green\"><b>$common_func[166]</b></font>","upload.php?do=myphoto");
      }else{
           DyhbMessage("<font color=\"green\"><b>$common_func[167]</b></font>","upload.php?do=myphoto");
     }
}

//删除相册分类
$delphotosortid=intval( get_argget('id') );
if($view=='delphotosort'&&$delphotosortid){ 
	IsSuperAdmin($common_permission[17],'');
	if($delphotosortid=='-1'){
	  DyhbMessage("<font color=\"red\"><b>$common_func[168]</b></font>","upload.php?do=myphoto");
	}
    $_Photosorts->DelSort($delphotosortid);
	CacheNewPhoto();
	CachePhotoSorts();
	DyhbMessage("<font color=\"green\"><b>$common_func[169]</b></font>","upload.php?do=myphoto");
}

//编辑相册分类
if($view=='updsort'&&$delphotosortid){
	IsSuperAdmin($common_permission[18],'');
	if($delphotosortid=='-1'){
	   DyhbMessage("<font color=\"red\"><b>$common_func[170]</b></font>","upload.php?do=myphoto");
	}
    $UpdSort=$_Photosorts->GetSorts($delphotosortid);
	$resultinfo=<<<DYHB
	  <br>
      <form id="form" action="upload.php?do=addphotosort" method="post" style="text-align:center;">
		 <input name="photosort_id"  type="hidden" value="$delphotosortid" />
         <label><font size="2">$common_func[171]</font> </label> 
         <input name="compositor"  type="text" value="$UpdSort[compositor]" /><br />
         <label><font size="2">$common_func[172] </font></label>
         <input name="name"  type="text" value="$UpdSort[name]" /><br />
		 <label><font size="2">$common_func[173]</font> </label>
         <input name="cover"  type="text" value="$UpdSort[cover]" /><br />	<br />
         <div align="center">
             <input id="button" type="submit"  value="$common_func[146]" />  
         </div>
       </form>
DYHB;
}

//批量操作
if($view=='prepare'){
	CheckPermission("cp",$common_permission[19]);
	$files_array=isset($_POST['files'])?$_POST['files']:'';
	$fileact= sql_check(get_argpost('prepare')) ;
	$filesort = sql_check(get_argpost('filesort'));
	switch ($fileact){
	    case 'del':
		foreach($files_array as $value){
           $DelFile=$_Photosorts->GetIdFile($value);
	       $DelFilePath='../width/upload/'.$DelFile[path];
	       if(file_exists($DelFilePath))
	         {
		       @unlink($DelFilePath) or DyhbMessage($common_func[158], "javascript:history.go(-1);");
	         }
	        $_Photosorts->DelFile($DelFile[file_id]);
		}
		CacheNewPhoto();
		CachePhotoSorts();
		DyhbMessage($common_func[174], 'upload.php?do=list');
        break;
		//批量常规链插入
		$back='';
		case 'defaultinsert':
		foreach($files_array as $value){
			echo "<script type=\"text/javascript\">";
		   $the_File=$_Photosorts->GetIdFile($value);
		   $isImg=strtolower(substr(strrchr($the_File[path], "."),1));
		   if($isImg!='gif'&&$isImg!='jpg'&&$isImg!='jpeg'&&$isImg!='bmp'&&$isImg!='png'){ 
			  $filesize=changeFile($the_File[size]);
			  $date=date('Y-m-d H:i:s',$the_File[dateline]);
			  if($isImg!='mp3'&&$isImg!='wma'&&$isImg!='wav'){
			     $down="{$dyhb_options[blogurl]}/width/upload/{$the_File[path]}";
			  }else{
			     $down="?action=mp3&fid=$value";
			  }
		      echo "parent.addfiley('$down','{$the_File[name]}','{$value}','{$filesize}','{$date}')";
		   }else{
			   $max_file_wh=unserialize($dyhb_options[all_width_height]);
               $now_wh=ChangeImgSize("../width/upload/".$the_File[path],$max_file_wh[0],$max_file_wh[1]);
			   $_blank_url=$dyhb_options[blogurl]."/width/upload/$the_File[path]";
			   //大图
	           $start=strpos($the_File[path],'/')+1;
			   if(substr($the_File[path],$start,'2')=='t-'){
              	   $_blank_url=$big_file_path=$dyhb_options[blogurl].'/width/upload/'.str_replace('t-','',$the_File[path]);
			   }
		     echo "parent.addfile('{$dyhb_options[blogurl]}/width/upload/{$the_File[path]}','$_blank_url','{$the_File[name]}','$now_wh[w]','$now_wh[h]','$value');";
		   }
		   $back.="$common_func[131]($isImg){$common_func[175]}$the_File[name]{$common_func[176]}<br>";
		   echo "</script>";
		}
		DyhbMessage($common_func[177].$back, 'upload.php?do=list');
		break;
		//批量防盗链插入
		$back="";
		case 'daolianinsert':
		foreach($files_array as $value){
		   echo "<script type=\"text/javascript\">";
		   $the_File=$_Photosorts->GetIdFile($value);
		   $isImg=strtolower(substr(strrchr($the_File[path], "."),1));
		   if($isImg!='gif'&&$isImg!='jpg'&&$isImg!='jpeg'&&$isImg!='bmp'&&$isImg!='png'){ 
			  $filesize=changeFile($the_File[size]);
			  $date=date('Y-m-d H:i:s',$the_File[dateline]);
			  if($isImg!='mp3'&&$isImg!='wma'&&$isImg!='wav'){
			     $down="{$dyhb_options[blogurl]}/file.php?id=$value}";
			  }else{
			     $down="?action=mp3&fid=$value";
			  }
		      echo "parent.addfiley('$down','{$the_File[name]}','{$value}','{$filesize}','{$date}')";
		   }else{
			   $max_file_wh=unserialize($dyhb_options[all_width_height]);
               $now_wh=ChangeImgSize("../width/upload/".$the_File[path],$max_file_wh[0],$max_file_wh[1]);
			   $_blank_url=$dyhb_options[blogurl]."/file.php?id=$the_File[file_id]";
			   //大图
	           $start=strpos($the_File[path],'/')+1;
			   if(substr($the_File[path],$start,'2')=='t-'){
              	   $_blank_url=$big_file_path=$dyhb_options[blogurl].'/width/upload/'.str_replace('t-','',$the_File[path]);
			   }
		     echo "parent.addfile('{$dyhb_options[blogurl]}/file.php?id={$the_File[file_id]}','$_blank_url','{$the_File[name]}','$now_wh[w]','$now_wh[h]','$value');";
		   }
		   $back.="$common_func[131]($isImg)$common_func[175]$the_File[name]$common_func[176]<br>";
		   echo "</script>";
		}
		DyhbMessage($common_func[178].$back, 'upload.php?do=list');
		break;
		case 'changefilesort':
		foreach($files_array as $value){
           $_Photosorts->ChangeFileSort($value,$filesort);
		}
		CacheNewPhoto();
		CachePhotoSorts();
	    DyhbMessage($common_func[179], 'upload.php?do=list');
	 }
}

//删除附件
$DelFileid=intval( get_argget('id')) ;
if ($view == 'del'&&$DelFileid){
	CheckPermission("cp",$common_permission[19]);
	$DelFile=$_Photosorts->GetIdFile($DelFileid);
	//删除原图
	$start=strpos($DelFile[path],'/')+1;
	$old_file_path='../width/upload/'.str_replace('t-','',$DelFile[path]);
	if(substr($DelFile[path],$start,'2')=='t-'&& file_exists($old_file_path)){
		@unlink($old_file_path) OR DyhbMessage($common_func[120],'');
	}
	$DelFilePath='../width/upload/'.$DelFile[path];
	if(file_exists($DelFilePath)){
		@unlink($DelFilePath) or DyhbMessage($common_func[58], "-1");
	}
	$_Photosorts->DelFile($DelFile[file_id]);
	CacheNewPhoto();
	CachePhotoSorts();
	//更新静态化
	    if( $dyhb_options[allowed_make_html]=='1'){
              MakePagenav("photo");
	    }
	DyhbMessage("{$common_func[134]}$DelFile[name]{$common_func[176]}", 'upload.php?do=list');
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"  dir="ltr" lang="zh-CN">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gbk" />
<title>upload</title>
<link rel="stylesheet" type="text/css" href="../images/admin/upload.css">
<script type="text/javascript" src="../images/lang/<?php echo $dyhb_options[global_lang_select];?>/lang.js"></script>
<script type="text/javascript" src="../images/js/common.js"></script>
<script type="text/javascript" src="../images/js/width.js"></script>
<script type="text/javascript" src="../images/js/jquery/jquery-1.4.2.min.js"></script>
<?php if($dyhb_options[upload_file_default]!='1'):?>
<link href="../images/swfupload/css/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../images/swfupload/swfupload/swfupload.js"></script>
<script type="text/javascript" src="../images/swfupload/js/swfupload.queue.js"></script>
<script type="text/javascript" src="../images/swfupload/js/fileprogress.js"></script>
<script type="text/javascript" src="../images/swfupload/js/handlers.js"></script>
<?php
//上传的文件格式
$allallowtype=unserialize($dyhb_options[all_allowed_filetype]);
$allallowtype2=array();
if($allallowtype){
    foreach($allallowtype as $value){
	   $allallowtype2[]="*.".$value;
	}
}
$allallowtypestr=implode(';',$allallowtype2);
unset($allallowtype,$allallowtype2);
?>
<script type="text/javascript">
		var swfu;
		window.onload = function() {
			var settings = {
				flash_url : "../images/swfupload/swfupload/swfupload.swf",
				upload_url: "upload.php?do=swfupload",	// Relative to the SWF file
				post_params: {"PHPSESSID" : "<?php echo session_id(); ?>"},
				file_size_limit : "100 MB",
				file_types : "<?php echo $allallowtypestr;?>",
				file_types_description : "some type Files",
				file_upload_limit : 100,
				file_queue_limit : 0,
				custom_settings : {
					progressTarget : "fsUploadProgress",
					cancelButtonId : "btnCancel"
				},
				debug: false,
				auto_upload:false,

				// Button settings
				button_image_url: "../images/swfupload/images/TestImageNoText_65x29.png",	// Relative to the Flash file
				button_width: "65",
				button_height: "29",
				button_placeholder_id: "spanButtonPlaceHolder",
				button_text: '<span class="theFont">浏览</span>',
				button_text_style: ".theFont { font-size: 16; }",
				button_text_left_padding: 12,
				button_text_top_padding: 3,
				
				// The event handler functions are defined in handlers.js
				file_queued_handler : fileQueued,
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				upload_start_handler : uploadStart,
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : uploadSuccess,
				upload_complete_handler : uploadComplete,
				queue_complete_handler : queueComplete	// Queue plugin event
			};

			swfu = new SWFUpload(settings);
	     };
</script>
<?php endif;?>
<body>
<div class="uploadhead">
	<span <?php if($view==''||$view=='upload'){echo "class=\"current\"";}?>><a href="upload.php"><?php echo $common_func[181];?></a></span>
	<span <?php if($view=='list'&&$type==''){echo "class=\"current\"";}?>><a href="upload.php?do=list"><?php echo $common_func[182];?></a></span>
	<span <?php if($view=='list'&&$type=='other'){echo "class=\"current\"";}?>><a href="upload.php?do=list&type=other"><?php echo $common_func[183];?></a></span>
	<span <?php if($view=='list'&&$type=='myphoto'){echo "class=\"current\"";}?>><a href="upload.php?do=list&type=myphoto"><?php echo $common_func[184];?></a></span>
	<span <?php if($view=='listqq'){echo "class=\"current\"";}?>><a href="upload.php?do=listqq"><?php echo $common_func[185];?></a></span>
	<span <?php if($view=='myphoto'){echo "class=\"current\"";}?>><a href="upload.php?do=myphoto"><?php echo $common_func[186];?></a></span>
	<span <?php if($view=='waittodo'){echo "class=\"current\"";}?>><a href="upload.php?do=waittodo"><?php echo $common_func[187];?></a></span>
</div>
<?php if($view==''):?>
<?php if($dyhb_options[upload_file_default]=='1'):?>
<form enctype="multipart/form-data" method="post" name="upload" action="upload.php?do=upload">
<div id="uploadbody">
	<p>
	<div class="list_input">
	  <?php for($i=1;$i<=$dyhb_options[file_input_num];$i++):?>
	  <input type="file" name="newfile[]">
	  <?php endfor;?>
	</div>
	<br>
	<div class="list_input">
	  <span><?php echo $common_func[188];?>
	  <select name="photosort_id">
	  <option value="-1"><?php echo $common_func[189];?></option>
	  <?php foreach($PhotoSort as $value){echo<<<DYHB
	  <option value="$value[photosort_id]">$value[name]</option>
DYHB;
}
?>
	  </select>
	  </span>
	</div>
	<br>
	<div style="text-align:center;">
	<input type="submit"  value="<?php echo $common_func[190];?>"/>
	<input type="reset"  value="<?php echo $common_func[191];?>" />	
	</div>
	</p>
</div>
</form>
<?php else:?>
<div id="content">
	<form action="upload.php" method="post" enctype="multipart/form-data" onclick="swfu.startUpload();">
		<p><?php echo $common_func[192];?></p>
		<div class="fieldset flash" id="fsUploadProgress">
			<span class="legend"><?php echo $common_func[193];?></span>
	    </div>
		<div id="divStatus"><?php echo $common_func[194];?></div>
			<div>
				<span id="spanButtonPlaceHolder"></span>
				<input id="btnCancel" type="button" value="<?php echo $common_func[195];?>" onclick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px; font-size: 8pt; height: 29px;" />
		</div> 
</form>
</div>
<?php endif;?>
<div style="float:right;background:#f2f6f9;width:120px;font-size:12px;margin-right:20px;padding:5px;">
<p><?php echo $common_func[196];?></p>
	    <select name="filetype">
		  <option value="-1"><?php echo $common_func[197];?></option>
		  <?php foreach(unserialize($dyhb_options[all_allowed_filetype]) as $value){echo<<<DYHB
	      <option>$value</option>
DYHB;
}
?>
</select>
</div>
<?php else:?>
<?php echo $resultinfo;?>
<?php endif;?>
</body>
</html>