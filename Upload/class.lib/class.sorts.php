<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * �ļ���c.function.sorts.php
        * ˵������־����
        * ���ߣ�Сţ��
        * ʱ�䣺2010-07-22 0:17
        * �汾��DoYouHaoBaby-blog �����.����˿�� (�ر��)
        * ��ҳ��www.doyouhaobaby.com
		* ��̳��bbs.56swun.com
@----------------------------------------@
  ---- ����������ջ����������Ĺ� ----
\**=====copyright (c) DoYouHaoBaby=====**/

class Sorts{
  public $DB;
  function __construct($newdb){
	 $this->DB=$newdb;
  }

 /**
  * ���ӷ���
  *
  */
function AddSort($SaveSortDate){
  add_sql($SaveSortDate,'sort');
}

/**
 * ���·���
 *
 */
function UpdSort($SaveSortDate,$sort_id){
  update_sql($SaveSortDate,'sort_id',$sort_id,'sort');
}

/**
 * ɾ������
 *
 */
function DeleteSort($sort_id){
 $this->DB->query("update `".DB_PREFIX."blog` set `sort_id`=-1 where `sort_id`=$sort_id");
 $this->DB->query("delete from `".DB_PREFIX."sort` where `sort_id`='$sort_id'");
}

/**
 * �ض���־����
 *
 */
function GetLogSort($blog_id){
  $logsort=$this->DB->getonerow("select b.name,b.sort_id from `".DB_PREFIX."blog` as a,`".DB_PREFIX."sort` as b  where a.sort_id=b.sort_id and a.blog_id=$blog_id");
  if($logsort){
     $logsort['sortname']=stripslashes($logsort['name']);
  }
  return $logsort;
}

/**
 * ��ȡ���׷���
 *
 */
function GetParSort($pid){
  $P_sort=$this->DB->gettworow("select *from `".DB_PREFIX."sort` where `parentsort_id`='$pid' order by `compositor` desc");
  if($P_sort){
  $i=0;
  foreach($P_sort as $value){
    $P_sort[$i]['name']=stripslashes($value['name']);
	$i++;
  }}
  return $P_sort;
}

/**
 * ��ȡ��������ĸ�����
 *
 */
 function GetThreePar($ListSort){
 	  //������
	   $Loglist_parsort="";
       if($ListSort[now]=="1"){
		  $Loglist_parsort['sort']=array($ListSort);
       }
       else{
		  $_thesort_parsort=$this->GetIdSort($ListSort['parentsort_id']);
		  if($ListSort[now]=="2"){
              $Loglist_parsort['sort']=array($_thesort_parsort,$ListSort);
		  }elseif($ListSort[now]=="3"){
              $_thesort_parsort_parsort=$this->GetIdSort($_thesort_parsort['parentsort_id']);
			  $Loglist_parsort['sort']=array($_thesort_parsort_parsort,$_thesort_parsort,$ListSort);
		  }
       }
       return $Loglist_parsort;
 }

/**
 * ���ݸ���ID��ȡ������
 *
 */
function GetOneParSort($parsort_id){
  $logsort=$this->DB->getonerow("select *from `".DB_PREFIX."sort` where parentsort_id=$parsort_id");
  if($logsort){
      $logsort['name']=stripslashes($logsort['name']);
  }
  return $logsort;
}

/**
 * ����ID��ȡ��������
 *
 */
function GetIdSort($sort_id){
  $idsort=$this->DB->getonerow("select *from`".DB_PREFIX."sort` where sort_id=$sort_id");
  if($idsort){
      $idsort['name']=stripslashes($idsort['name']);
  }
  return $idsort;
}

/**
 * ����
 *
 */
function GetSorts(){
  $sorts =$this->DB->gettworow("select *from `".DB_PREFIX."sort`  order by compositor asc");
  if($sorts){
  $i=0;
  foreach($sorts as $value){
    $sorts[$i]['name']=stripslashes($value['name']);
	$i++;
  }
  }
  return $sorts;
}

}

?>