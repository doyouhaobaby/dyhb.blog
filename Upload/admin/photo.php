<?php
/**================[^_^]================**\
        ---- 今天天气冷嘻嘻的 ----
@----------------------------------------@
        * 文件：mp3.php
        * 说明：音乐
        * 作者：小牛哥
        * 时间：2010-05-06 20:22
        * 版本：DoYouHaoBaby-blog 概念版
        * 主页：www.doyouhaobaby.com
@----------------------------------------@
  ---- 西南民大的天空还是蛮明亮的哈 ----
\**=====copyright (c) DoYouHaoBaby=====**/

CheckPermission("cp",$common_permission[23]);

//附件管理
if($view==''||$view=='list'){
   #
}

//音乐处理
if($view=='mp3'){
   $mp3sort_id=intval( get_argget('mp3sort_id'));
   $mp3_id=intval( get_argget('mp3_id'));

   //音乐列表
   if($view2==''){
   $mp3sort_c=$mp3sort_id?"where `mp3sort_id`='$mp3sort_id'":'';
   $TotalMp3Num=$DB->getresultnum("SELECT count(mp3_id) FROM `".DB_PREFIX."mp3` $mp3sort_c");
   if($TotalMp3Num){
      Page($TotalMp3Num,$dyhb_options['admin_log_num']);
      $AllMp3s=$_Mp3s->GetMp3s($mp3sort_id,$pagestart,$dyhb_options['admin_log_num']);
   }
   $i=0;
   //处理
   if($AllMp3s){
    foreach($AllMp3s as $value){    
       $themp3sort=$_Mp3s->GetMp3Sorts($value[mp3sort_id]);
	   if($value[mp3sort_id]=='-1'){
	       $AllMp3s[$i][mp3sortname]=$common_func[99];
	   }else{
	       $AllMp3s[$i][mp3sortname]=$themp3sort[name];
	   }
      $i++;
     }
    }
   }   
   //删除音乐
   if($view2=='del'&&$mp3_id){    
	  $_Mp3s->DelMp3($mp3_id);
	  CacheNewMusic();
	  CacheMp3Sorts();
	  //更新静态化
	  if( $dyhb_options[allowed_make_html]=='1'){
              MakePagenav("mp3");
	  }
      Header("location:?action=photo&do=mp3&delOne=true");
    }

   //批量操作
   if($view2=='prepare'){
	 $mp3s_array=isset($_POST['mp3s'])?$_POST['mp3s']:'';
     $mp3act= sql_check( get_argpost('prepare'));
     $mp3sort_id = intval( get_argpost('mp3sort_id'));
     switch ($mp3act){
       case 'del':
	   foreach($mp3s_array as $value){
	      $_Mp3s->DelMp3($value);
	    }
		Header("location:?action=photo&do=mp3&delmp3=true");
        break;
	    case 'changemp3sort':
	    foreach($mp3s_array as $value){
           $_Mp3s->ChangeMp3Sort($value,$mp3sort_id);
	    }
	    CacheNewMusic();
		CacheMp3Sorts();
		//更新静态化
	  if( $dyhb_options[allowed_make_html]=='1'){
              MakePagenav("mp3");
	  }
		Header("location:?action=photo&do=mp3&move=true");
       }
	}

     //编辑音乐
    if($view2=="upd"&&$mp3_id){   
       if(!$_POST[ok]){
       $UpdMp3=$_Mp3s->GetOneMp3($mp3_id);
        }else{
           $name = sql_check( get_argpost('name'));
           $path = sql_check( get_argpost('path'));
		   $musicword =get_argpost('musicword');
           if($name=='')Dyhbmessage("<font color=\"red\"><b>$common_func[226]</b></font>","javascript:history.go(-1);");//判断
           //音乐数组*顺序很重要
          $SaveMp3Date=array(
	         'name'=>$name,
	         'path'=>$path,
			 'musicword'=>$musicword,
           );
        $SaveMp3Date=$SaveMp3Date;
        $_Mp3s->UpdMp3($SaveMp3Date,$mp3_id);
        CacheNewMusic();
		CacheMp3Sorts();
		//更新静态化
	  if( $dyhb_options[allowed_make_html]=='1'){
              MakePagenav("mp3");
	  }
        header("location:?action=photo&do=mp3&do2=upd&mp3_id=$mp3_id&updmp3=true");
      }
	}

    //添加音乐
    if($view2=='add'){
       $mp3s= sql_check( get_argpost('mp3'));
	    $mp3sort_id= intval( get_argpost('mp3sort_id'));
	    if($mp3s){
	       $mp3s = explode("\n",$mp3s);
	          foreach($mp3s as $value){
	             $value = html_clean(str_replace(array("\r","\n"),array('',''),trim($value)));
	              $mp3info=explode("|",$value);
	              $SaveMp3Date=array(             
                  'name'=>addslashes($mp3info[0]),                
                  'path'=>$mp3info[1],                 
                  'dateline'=>$localdate,                
                  'mp3sort_id'=>$mp3sort_id
	             );
	      $_Mp3s->AddMp3($SaveMp3Date);
         }
       }
      CacheNewMusic();
	  CacheMp3Sorts();
	  //更新静态化
	  if( $dyhb_options[allowed_make_html]=='1'){
              MakePagenav("mp3");
	  }
	  header("location:?action=photo&do=mp3&addmp3=true");
     } 
}

//音乐分类处理
if($view=='mp3sort'){
    $Mp3SortId = intval( get_argget('mp3sort_id'));
	$Mp3Sort=$_Mp3s->GetMp3Sorts('');//音乐分类 
    //音乐
    $Mp3playerOptions=unserialize($dyhb_options['mp3player_options']);
    //删除音乐分类
    if($view2=="del"&&$Mp3SortId){  
	   $_Mp3s->DeleteMp3Sort($Mp3SortId);
	   CacheMp3Sorts();
	   header("Location:?action=photo&do=mp3sort&delmp3sort=true");
    }
    //保存数据
    if($view2=="save"){   
	    //音乐分类数据
	    $mp3sort_id = intval( get_argpost('mp3sort_id'));
	    $name = sql_check( get_argpost('name'));
	    if( $name=='' ){ Dyhbmessage("<font color=\"red\"><b>$common_func[227]</b></font>","-1"); }//判断
	    $compositor  = intval( get_argpost('compositor')) ;
	    $isdisplay  = intval( get_argpost('isdisplay')) ;

        //音乐分类数组*顺序很重要
        $SaveMp3SortDate=array(
		    'mp3sort_id'=>$mp3sort_id,
		     'name'=>$name,
		     'isdisplay'=>$isdisplay,
		     'compositor'=>$compositor,
		  );

	     if($mp3sort_id>0){//更新分类
		     $_Mp3s->UpdMp3Sort($SaveMp3SortDate,$mp3sort_id);
             header("Location:?action=photo&do=mp3sort&do2=upd&mp3sort_id={$mp3sort_id}&updmp3sort=true");
			 CacheMp3Sorts();
	     }else{//保存分类	
		     $_Mp3s->AddMp3Sort($SaveMp3SortDate);
             CacheMp3Sorts();
			 header("Location:?action=photo&do=mp3sort&addmp3sort=true");
	      }
       }

       //编辑音乐
       if($view2=="upd"&&$Mp3SortId){
           $UpdMp3Sorts=$_Mp3s->GetMp3Sorts($Mp3SortId);
        }

       //保存音乐数据调用
       if($view2=="option"){
         //基本配置
         $showdisplay =sql_check( get_argpost('showdisplay'));
         $playlist = sql_check( get_argpost('playlist'));
         $autostart = sql_check( get_argpost('autostart'));
         $mp3player_options=serialize(array($showdisplay,$playlist,$autostart));
         $_Cools->UpdateCools($mp3player_options,'57');

         //播放器xml文件内容
         $mp3player_xml="<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
         $mp3player_xml.="<player showDisplay=\"$showdisplay\" showPlaylist=\"$playlist\" autoStart=\"$autostart\">\n";
         foreach($Mp3Sort as $value){   
           if($value['isdisplay']=='1')//只调用允许的数据和为分类的数据
	        {   
		       $theMp3=$_Mp3s->GetMp3s($value['mp3sort_id'],'','');
		       foreach($theMp3 as $val){
				   $mp3player_xml.="<song path=\"$val[path]\" title=\"$val[name]\" />\n";
			   }      
		     }
	      }
         $mp3player_xml.="</player>";
         $mp3plaer_xml_path='../images/mp3/mp3player.xml';
         if(!is_writable($mp3plaer_xml_path)){
	          DyhbMessage("<font color='red'>$common_func[228]</font>",'');
          }else{

	     //将文件写入xml播放器列表
         $fp=fopen($mp3plaer_xml_path,"wb+");
         //gbk编码转utf8
          fwrite($fp,GbkToUtf8($mp3player_xml,'GBK'));
	      CacheOptions();
		  header("Location:?action=photo&do=mp3sort&option=true");
       }
      }

      //保存播放器设置
      if($view2=='setplayer'){

        //获取数据
        $f_bg = sql_check( get_argpost('f_bg'));//中间背景色
        $f_leftbg=sql_check( get_argpost('f_leftbg')) ;//左部背景色
        $f_lefticon=sql_check( get_argpost('f_lefticon')) ;//左部标志色
        $f_rightbg=sql_check( get_argpost('f_rightbg'));//右部背景色
        $f_rightbghover=sql_check( get_argpost('f_rightbghover'));//右部背景接触色
        $f_righticon=sql_check( get_argpost('f_righticon'));//右部标志色
        $f_righticonhover=sql_check( get_argpost('f_righticonhover'));//右部标志接触色
        $f_text=sql_check( get_argpost('f_text'));//文字色
        $f_slider=sql_check( get_argpost('f_slider')) ;//滑块色
        $f_track=sql_check( get_argpost('f_track'));//轨道色
        $f_border=sql_check( get_argpost('f_border')) ;//边框色
        $f_loader=sql_check( get_argpost('f_loader'));//加载条色
        $f_auto= sql_check( get_argpost('f_auto'));//自动播放
        $f_loop= sql_check( get_argpost('f_loop'));//循环播放
        $setplay=serialize(array($f_bg,$f_leftbg,$f_lefticon,$f_rightbg,$f_rightbghover,$f_righticon,$f_righticonhover,$f_text,$f_slider,$f_track,$f_border,$f_loader,$f_auto,$f_loop));
        $_Cools->UpdateCools($setplay,'94');
        CacheOptions();
		header("Location:?action=photo&do=mp3&setplayer=true");
      }
}

include DyhbView('photo',1);

?>