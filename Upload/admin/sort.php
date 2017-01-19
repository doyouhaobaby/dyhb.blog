<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * 文件：sort.php
        * 说明：分类管理
        * 作者：小牛哥
        * 时间：2010-07-22 0:17
        * 版本：DoYouHaoBaby-blog 告别大二.想念丝瓜 (特别版)
        * 主页：www.doyouhaobaby.com
		* 论坛：bbs.56swun.com
@----------------------------------------@
  ---- 西南民大的天空还是蛮明亮的哈 ----
\**=====copyright (c) DoYouHaoBaby=====**/

IsSuperAdmin($common_permission[21],'');

$SortId =intval( get_argget('id') );
$ParSortId = intval( get_argget('pid') );

//编辑分类
 if($view=="upd"&&$SortId){
        $UpdSorts=$_Sorts->GetIdSort($SortId);
 }

//删除分类
 if($view=="del"&&$SortId){
	  $parsort=$_Sorts->GetParSort($SortId);
	  if($parsort){
         Dyhbmessage("<font color=\"red\">$common_func[200]</b></font>","?action=sort");
	  }else{
	     $_Sorts->DeleteSort($SortId);
	     CacheSorts();CachethreeSorts();
	     header("Location:?action=sort&delchild=true");
	  }
}

   //保存分类
   if($view=="save"){
	    //分类数据
	    $sort_id = intval( get_argpost('sort_id') );
	    $name = sql_check( get_argpost('name') );
		$keyword = sql_check( get_argpost('keyword') );
		$description = sql_check( get_argpost('description') );
	    if( $name=='' ){ Dyhbmessage("<font color=\"red\"><b>$common_func[201]</b></font>","-1"); }//判断
        $cmsstart =intval( get_argpost('cmsstart') );
	    $cmsend=intval( get_argpost('cmsend') );
		$urlname = sql_check( get_argpost('urlname') );
		$introduce =  get_argpost('introduce') ;
		//别名检查
         if($DB->getonerow("SELECT sort_id FROM ".DB_PREFIX."sort WHERE sort_id != '".intval($sort_id)."' AND name = '$name'")){
	          Dyhbmessage("<font color=\"red\"><b>$common_func[202]</b></font>","-1");
	     }
		 if(!empty($urlname)){
			 if(!preg_match('/^[a-z0-9\-\_]*[a-z\-_]+[a-z0-9\-\_]*$/i',$urlname)){
				 DyhbMessage($common_func[203],'-1');
		     }else{
		       if($DB->getonerow("SELECT sort_id FROM ".DB_PREFIX."sort WHERE sort_id != '".intval($sort_id)."' AND urlname = '$urlname'")){
	             Dyhbmessage("<font color=\"red\"><b>$common_func[203]</b></font>","-1");
	         }
		   }
		}
        $style  =intval( get_argpost('style') ) ;
	    $compositor  =intval( get_argpost('compositor') ) ;
	    $logo  =sql_check ( get_argpost('logo') );
	    $parentsort_id=intval( get_argpost('parentsort_id'));
		$now='1';
		if($parentsort_id!='0'){
		   $thesort=$_Sorts->GetIdSort($parentsort_id);
		   $now=$thesort['now']+1;
		   if($now=='4'){
		       Dyhbmessage("<font color=\"red\"><b>$common_func[204]</b></font>","-1");
		   }
		}
	    //格式检查
        isurl($logo);
        //分类数组*顺序很重要
        $SaveSortDate=array(
		   'sort_id'=>$sort_id,
		   'name'=>$name,
		   'cmsstart'=>$cmsstart,
		   'cmsend'=>$cmsend,
		   'urlname'=>$urlname,
		   'style'=>$style,
		   'compositor'=>$compositor,
		   'logo'=>$logo,
		   'parentsort_id'=>$parentsort_id,
		   'now'=>$now,
		   'introduce'=>$introduce,
		   'keyword'=>$keyword,
		   'description'=>$description
		  );
	   $SaveSortDate=dyhb_addslashes($SaveSortDate);

	   if($sort_id>0){//更新分类
		   $_Sorts->UpdSort($SaveSortDate,$sort_id);
		   CacheSorts();
		   CachethreeSorts();
		   header("Location:?action=sort&do=child&do2=upd&id={$sort_id}&updchild=true");
	    }else{//保存分类
		   $_Sorts->AddSort($SaveSortDate);
		   CacheSorts();
		   CachethreeSorts();
		   header("Location:?action=sort&do=child&addchild=true");
	    }
}
include DyhbView('sort',1);

?>