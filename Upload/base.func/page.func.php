<?php
/**================[^_^]================**\
      ---- ��Ϊ���Σ�������Ŀ�� ----
@----------------------------------------@
        * �ļ���page.function.php
        * ˵������ҳ
        * ���ߣ�Сţ��
        * ʱ�䣺2010-05-06 20:22
        * �汾��DoYouHaoBaby-blog �����
        * ��ҳ��www.doyouhaobaby.com
@----------------------------------------@
  ---- ����������ջ����������Ĺ� ----
\**=====copyright (c) DoYouHaoBaby=====**/

/**
  * ��ҳ
  *
  * @param int $total��ҳ����
  * @param int $everypagenumÿҳ��Ŀ
  * @param string $url��ַ
  * @return int $page|��ǰҳ��
  * @return int $pagestart|ÿҳ��ʼҳ��
  * @return string $pagination|��ҳHTML����
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

  //ҳ����㣺
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