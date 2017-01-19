<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * 文件：user.php
        * 说明：用户管理
        * 作者：小牛哥
        * 时间：2010-07-22 0:17
        * 版本：DoYouHaoBaby-blog 告别大二.想念丝瓜 (特别版)
        * 主页：www.doyouhaobaby.com
		* 论坛：bbs.56swun.com
@----------------------------------------@
  ---- 西南民大的天空还是蛮明亮的哈 ----
\**=====copyright (c) DoYouHaoBaby=====**/

if($view!='upd'&&$view!='save_coolgirl'){
    CheckPermission("cp",$common_permission[6]);
}

$UserGroupId=$UserId = intval( get_argget('id'));

if($view==''||$view=='list'){
   CheckPermission("viewuserlist",$common_permission[5]);
   $TotalUserNum=$DB->getresultnum("SELECT count(user_id) FROM `".DB_PREFIX."user`");
   //用户列表
   if($TotalUserNum){
	  Page($TotalUserNum,$dyhb_options[admin_log_num]);
      $_Bloggers=$DB->gettworow("select *from ".DB_PREFIX."user limit $pagestart,$dyhb_options[admin_log_num]");
   }
}

//获取用户信息,编辑
if($view=='upd'&&$UserId){
	if($UserId=='1'){
	   IsSuperAdmin($common_permission[7],''); 
	}
	if($dyhb_userid!=$UserId){
	    CheckPermission("editotheruser",$common_permission[58]);
	}
	$Bloggers=$_Cools->GetBloggerInfo($UserId);;
	$mwh=unserialize($dyhb_options[icon_width_height]);
	if($UserId=='1'){
	   $filesize=ChangeImgSize("../images/qq/".$Bloggers[bloggerphoto],$mwh[0],$mwh[1]);
	}else{
	   $filesize=ChangeImgSize("../width/upload/".$Bloggers[bloggerphoto],$mwh[0],$mwh[1]);
	}
    $Bloggers['w']=$filesize['w'];
	$Bloggers['h']=$filesize['h'];
	$school=unserialize($Bloggers['school']);
}

if($view=='usergroup'){
	IsSuperAdmin($common_permission[8],'');
    /** 更新用户权限数据 */
    if($view2=='save_pre'&&$UserGroupId){
		/** 权限 */
		if($UserGroupId=='4'){$the_gpname=$common_func[110];  }
		elseif($UserGroupId=='1'){ $the_gpname=$common_func[111]; }
		elseif($UserGroupId=='2'){ $the_gpname=$common_func[112]; }
		elseif($UserGroupId=='3'){ $the_gpname=$common_func[113]; }
		else{ $the_gpname=$common_func[114]; }   
		$dyhb_prefconfig=$_POST['dyhb_prefconfig'];
		$dyhb_prefconfig[gpname]=$the_gpname;
		$dyhb_prefconfig=serialize($dyhb_prefconfig);	
		$_Cools->UpdateOption('dyhb_global_prefconfig'.$UserGroupId,$dyhb_prefconfig);
		//缓存
        CacheOptions();
		header("location:?action=user&do=usergroup&save_pre=true");
   }

   /** 编辑 **/
   if($view2='upd'&&$UserGroupId){
        $dyhb_prefconfig=unserialize($dyhb_options['dyhb_global_prefconfig'.$UserGroupId]);
		$attention_img="<img src=\"../images/other/attention.gif\"/>";
   }  
}


//保存个性数据
if($view=="save_coolgirl"){     
     //个性数据
	 $user_id = intval( get_argpost('user_id'));	
	 if($user_id>0){
	    //判断，如果是编辑用户信息，则先获取用户信息
        $ShowBlogger=$_Cools->GetBloggerInfo($user_id);
	 }
	 $username = sql_check( get_argpost('username'));
	 //如果用户没有填写新密码，那么默认从数据库中取出密码，保持不变
     $password = !empty($_POST['password']) ? md5(sql_check( get_argpost('password'))) :$ShowBlogger['password'];
     $usergroup = intval( get_argpost('usergroup'));
	 $email = sql_check( get_argpost('email'));
	 $nikename = sql_check( get_argpost('coolname'));
	 //bloggerphoto
	 $description = sql_check( get_argpost('description'));
	 $sex = intval( get_argpost('sex'));
	 $age = intval( get_argpost('age')) ;
	 $work = sql_check( get_argpost('work'));
     $marry = intval( get_argpost('marry'));
	 $love = sql_check( get_argpost('love'));
	 $msn = sql_check( get_argpost('msn'));
	 $skype = sql_check( get_argpost('skype'));
     $woyaoblog = sql_check( get_argpost('woyaoblog'));
	 $xiaonei = sql_check( get_argpost('xiaonei'));
	 $homepage = sql_check( get_argpost('homepage'));
	 $qq = intval( get_argpost('qq'));
	 $birthday = sql_check( get_argpost('birthday'));
	 $primary = str_replace('*','',sql_check( get_argpost('primary')));//过滤掉*
	 $juniorhigh = str_replace('*','',sql_check( get_argpost('juniorhigh')));
	 $high = str_replace('*','',sql_check( get_argpost('high')));
	 $university = str_replace('*','',sql_check( get_argpost('university')));
	 $school=serialize(array($primary,$juniorhigh,$high,$university));
	 $hometown = sql_check( get_argpost('hometown'));
	 $nowplace = sql_check( get_argpost('nowplace'));
	 //判断用户提交的数据
	 if(!$username){ DyhbMessage($common_func[115],'');}//用户名不能为空
     if($email){ dyhb_email($email);}//检查格式
	 if($msn){dyhb_email($msn);}
	 if($skype){dyhb_email($skype);} 
	 if($woyaoblog){isurl($woyaoblog);}
	 if($xiaonei){dyhb_email($xiaonei);} 
	 if($homepage){isurl($homepage);}
//上传头像
if(($_FILES["bloggerqq"]["name"])==''){  
  if($user_id){  
     $bloggerphoto=$ShowBlogger['bloggerphoto'];
  }
}
else{  
   //如果有新文件上传，则执行上传
   /** 上传前判断权限 */
   CheckPermission("upload",$common_permission[9]);
   //超级管理员的头像上传到images/qq中，头像很重要，其它人的头像上传到其它附件的地方
   $IsQQ=$user_id=='1'?'1':'0';
   $bloggerphoto=UploadFile($_FILES["bloggerqq"]["name"],$_FILES["bloggerqq"]["tmp_name"],$_FILES["bloggerqq"]["size"],$_FILES["bloggerqq"]["error"], $_FILES["bloggerqq"]["type"], unserialize($dyhb_options[qq_allowed_filetype]),$IsQQ,'');
   if($user_id!='1'){
       $SaveFileDate=array(
		     'photosort_id'=>"-1",
		     'path'=>$bloggerphoto,
		     'name'=>addslashes($_FILES["bloggerqq"]["name"]),
		     'dateline'=>$localdate,
			 'filetype'=>$_FILES["bloggerqq"]["type"],
		     'size'=>$_FILES["bloggerqq"]["size"],
			 );
			//写入附件
            $photoid=$_Photosorts->AddFile($SaveFileDate);
    }
}

//判断电子邮件是否存在
if($user_id>='0'){ $email_check_c="and `user_id`!='$user_id'"; }
else{ $email_check_c='';}
if($email!=''&&$R=$DB->getonerow("select *from `".DB_PREFIX."user` where `email`='$email' $email_check_c")){
	 DyhbMessage($common_func[116],'');
}

//个性信息数组
 $SaveCoolDate=array(
  'user_id'=>$user_id,  
  'username'=>$username,  
  'password'=>$password,                                           
  'dateline'=>$localdate,
  'usergroup'=>$usergroup,  
  'nikename' =>$nikename,               
  'email' =>$email,              
  'bloggerphoto' =>$bloggerphoto,                 
  'description'=>$description,                  
  'sex' =>$sex,             
  'age' =>$age,          
  'work'=>$work,                
  'marry' =>$marry,       
  'love' =>$love,            
  'qq'=>$qq, 
  'msn' =>$msn,    
  'skype' =>$skype,      
  'weyaoblog'  =>$woyaoblog,              
  'xiaonei' =>$xiaonei,          
  'homepage' =>$homepage,               
  'birthday'=>$birthday, 
  'school' =>$school,             
  'hometown' =>$hometown,                 
  'nowplace'=>$nowplace);
 
 //如果是编辑用户信息，即$user_id>0,那么执行更新，否则执行添加新的用户
  if($user_id>'0'){ 
	  $_Cools->UpdateBloggerInfo($SaveCoolDate,$user_id);            
  }
  else{
	//判断密码是否为空
	if($password==''){
		  DyhbMessage($common_func[117],'');
	}
	//判断用户是否存在
	if($R=$DB->getonerow("select *from `".DB_PREFIX."user` where `username`='$username'")){
	   DyhbMessage($common_func[118],'');
	}
	$_Cools->SaveUser($SaveCoolDate);
  }
 //更新缓存
 if($user_id=='1'){
     CacheBlogger();
 }
  //根据相应的操作，返回消息
  if($user_id>'0'){header("location:?action=user&do=upd&id=$user_id&upd=true");}
  else{header("location:?action=user&add=true");}
}	
       
//删除头像
if($view=="delphoto"&&$UserId){     
	  $ShowBlogger=$_Cools->GetBloggerInfo(LOGIN_USERID);
	  if($UserId=='1'){
          $photourl="../images/qq/".$ShowBlogger['bloggerphoto'];
	  }else{
	      $photourl="../width/upload/".$ShowBlogger['bloggerphoto'];
	  }
	  if(file_exists($photourl)){
           @unlink($photourl) OR DyhbMessage($common_func[119],''); 
	  }
	  //删除原图
	  $start=strpos($ShowBlogger[path],'/')+1;
	  $old_file_path='../width/upload/'.str_replace('t-','',$ShowBlogger[path]);
	  if(substr($ShowBlogger[path],$start,'2')=='t-'&& file_exists($old_file_path)){
		   @unlink($old_file_path) OR DyhbMessage($common_func[120],'');
	  }
      $DB->query("UPDATE `".DB_PREFIX."user` SET `bloggerphoto`='' where `user_id`=$UserId");
	  CacheBlogger();
	  header("location:?action=user&do=upd&id=$UserId&del=true");
}

//删除用户
if($view=="del"&&$UserId){   
	if($UserId=='1'){
	   DyhbMessage('<font color=red>$common_func[121]</font>','');
	}
	$ShowBlogger=$_Cools->GetBloggerInfo($UserId);
	$_Cools->DeleteUser($UserId);
	CacheBlogger();
    Header("location:?action=user&del=true");
}

//用户列表.添加用户表单
if($view==''||$view=='add'||$view=='upd'||$view=='list'||'usergroup'){
   include DyhbView('user',1);
}

?>