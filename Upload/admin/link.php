<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * 文件：links.php
        * 说明：衔接管理
        * 作者：小牛哥
        * 时间：2010-07-22 0:17
        * 版本：DoYouHaoBaby-blog 告别大二.想念丝瓜 (特别版)
        * 主页：www.doyouhaobaby.com
		* 论坛：bbs.56swun.com
@----------------------------------------@
  ---- 西南民大的天空还是蛮明亮的哈 ----
\**=====copyright (c) DoYouHaoBaby=====**/

CheckPermission("cp",$common_permission[41]);

$LinkId = intval( get_argget('id'));

//衔接列表
if($view==''||$view=='list'){
    $side_Links=dyhb_onearray($side_LogoLinks,$side_TextLinks);
}
//删除衔接
if($view=="del"&&$LinkId){
	$_Links->DeleteLink($LinkId);
	CacheLinks();
	//更新静态化
	  if( $dyhb_options[allowed_make_html]=='1'){
              MakePagenav("link");
	  }
    Header("location:?action=link&del=true");
}
//保存衔接
if($view=="save"){
	
    //衔接数据
    $isdisplay =intval ( get_argpost('isdisplay')) ;
    $link_id =intval ( get_argpost('link_id')) ;
    $name =sql_check ( get_argpost('name'));
    if($name==''){Dyhbmessage("<font color=\"red\"><b>$common_func[246]</b></font>","-1");}//判断
    $url =sql_check ( get_argpost('url')) ;
    $description=sql_check ( get_argpost('description')) ;
    $compositor=intval ( get_argpost('compositor')) ;
    $logo  =sql_check ( get_argpost('logo'));

    //格式检查
    isurl($url);
    isurl($logo);

    //衔接数组*顺序很重要
    $SaveLinkDate=array(
      'link_id'=>$link_id,
      'name'=>$name,
      'url'=>$url,
      'description'=>$description,
      'logo'=>$logo,
      'isdisplay'=>$isdisplay,
      'compositor'=>$compositor,
     );

    if($link_id>0){//更新衔接
       $_Links->UpdLink($SaveLinkDate,$link_id);
	   CacheLinks();
	   //更新静态化
	  if( $dyhb_options[allowed_make_html]=='1'){
              MakePagenav("link");
	  }
       Header("location:?action=link&&id={$link_id}&upd=true");
    }else{//保存衔接	      
       $_Links->AddLink($SaveLinkDate); 
	   CacheLinks();
	   //更新静态化
	  if( $dyhb_options[allowed_make_html]=='1'){
              MakePagenav("link");
	  }
       Header("location:?action=link&add=true");
    }
}
       
//编辑衔接
if($view=="upd"&&$LinkId){
    $UpdLink=$_Links->GetOneLink($LinkId);
}

include DyhbView('link',1);	;

?>