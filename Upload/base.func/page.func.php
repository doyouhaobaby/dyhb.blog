<?php
/**================[^_^]================**\
      ---- 因为有梦，所以有目标 ----
@----------------------------------------@
        * 文件：page.function.php
        * 说明：分页
        * 作者：小牛哥
        * 时间：2010-05-06 20:22
        * 版本：DoYouHaoBaby-blog 概念版
        * 主页：www.doyouhaobaby.com
@----------------------------------------@
  ---- 西南民大的天空还是蛮明亮的哈 ----
\**=====copyright (c) DoYouHaoBaby=====**/

/**
  * 分页
  *
  * @param int $total分页总数
  * @param int $everypagenum每页数目
  * @param string $url地址
  * @return int $page|当前页码
  * @return int $pagestart|每页开始页码
  * @return string $pagination|分页HTML代码
  */
function Page($totle,$everypagenum,$url=''){
    global $page,$pagestart,$pagination,$_SERVER,$lastpage,$IsMobilePagenav,$common_func;
    $GLOBALS["everypagenum"]=$everypagenum;
    //url.page
   if(!$page) $page=1;
   if(!$url){
	 $url=$_SERVER["REQUEST_URI"];
   } 
   $parse_url=parse_url($url);
   @ $url_query=$parse_url["query"]; 
   if($url_query){
	$url_query=ereg_replace("(^|&)page=$page","",$url_query);
	$url=str_replace($parse_url["query"],$url_query,$url);
    if($url_query) $url.="&page"; else $url.="page";
   }
   else {$url.="?page";}

  //页码计算：
  if($everypagenum !='0'){
  $lastpage=ceil($totle/$everypagenum); 
  $page=min($lastpage,$page);
  $prepg=$page-1; 
  $nextpg=($page==$lastpage ? 0 : $page+1); 
  $pagestart=($page-1)*$everypagenum;
  }

  //start it
  if($IsMobilePagenav){
	  $num='2';
	  $first_n="F";
	  $prepg_n="P";
	  $nextpg_n="N";
	  $lastpage_n="L";
  }else{
      $num='3';
	  $first_n="&laquo; First";
	  $prepg_n="&#8249; Prev";
	  $nextpg_n="Next &#8250;";
	  $lastpage_n="Last &raquo;";
  }
  if(!$IsMobilePagenav) $pagination="<span class=\"infor\">Total: $totle</span>";
  //<span class=\"infor\">Page ".($totle?($pagestart+1):0)." of ".min($pagestart+$everypagenum,$totle)."</span>";
  if($lastpage<=1) return false;
  if ($page > $num) $pagination .= "<a href=\"$url=1\" title=\"$common_func[80]\">$first_n</a>";
  if($page!=1) $pagination.="<a href=\"$url=$prepg\" title=\"$common_func[81]\">$prepg_n</a>";
  for($i = $page-$num;$i <= $page+$num && $i <= $lastpage;$i++){
     if ($i > 0){
		if($i==$page){
			$pagination.="<span class=\"current\">$i</span>\n";
		}else {
			$pagination.="<a href=\"$url=$i\">$i</a>\n";
		}
     }
  }
 if($page!=$lastpage) $pagination.="<a href=\"$url=$nextpg\" title=\"$common_func[82]\">$nextpg_n</a>";
 if ($page + $num < $lastpage) $pagination .= "<a href=\"$url=$lastpage\" title=\"$common_func[83]\">$lastpage_n</a>";
 if ($lastpage <= 1) $pagination = '';
}

?>