<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * �ļ���c.function.links.php
        * ˵���������νӷ�װ
        * ���ߣ�Сţ��
        * ʱ�䣺2010-07-22 0:17
        * �汾��DoYouHaoBaby-blog �����.����˿�� (�ر��)
        * ��ҳ��www.doyouhaobaby.com
		* ��̳��bbs.56swun.com
@----------------------------------------@
  ---- ����������ջ����������Ĺ� ----
\**=====copyright (c) DoYouHaoBaby=====**/

class Links{
public $DB;

function __construct($newdb){
  $this->DB=$newdb;
}

/**
 * ����ν�
 *
 */
function AddLink($SaveLinkDate){
 add_sql($SaveLinkDate,'link');
}

/**
 * ���������ν�
 *
 */
function UpdLink($SaveLinkDate,$link_id){
 update_sql($SaveLinkDate,'link_id',$link_id,'link');
}

/**
 * ɾ�������ν�
 *
 */
function DeleteLink($link_id){
	$this->DB->query("delete from `".DB_PREFIX."link` where `link_id`='$link_id'");
}

/**
 * ���������ν�
 *
 */
function GetOneLink($link_id){
  $onelink=$this->DB->getonerow("select *from `".DB_PREFIX."link` where link_id=$link_id");
  if($onelink){
      $onelink['name']=stripslashes($onelink['name']);
      $onelink['description']=stripslashes($onelink['description']);
  }
  return $onelink;
}

/**
 * �����ν�
 *
 */
function GetLinks(){
  $a1=$this->DB->gettworow("select *from `".DB_PREFIX."link` where `logo`='' order by `compositor` asc");
  $a2=$this->DB->gettworow("select *from `".DB_PREFIX."link` where `logo`!='' order by `compositor` asc");
  if($a1){
  $i=0;
  foreach($a1 as $value){
    $a1[$i]['name']=stripslashes($value['name']);
	$a1[$i]['description']=stripslashes($value['description']);
	$i++;
  }}
  if($a2){
  $i=0;
  foreach($a2 as $value){
    $a2[$i]['name']=stripslashes($value['name']);
	$a2[$i]['description']=stripslashes($value['description']);
	$i++;
  }}
  return array($a1,$a2);
}
}

?>