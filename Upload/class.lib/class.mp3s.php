<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * �ļ���c.function.mp3.php
        * ˵��������
        * ���ߣ�Сţ��
        * ʱ�䣺2010-07-22 0:17
        * �汾��DoYouHaoBaby-blog �����.����˿�� (�ر��)
        * ��ҳ��www.doyouhaobaby.com
		* ��̳��bbs.56swun.com
@----------------------------------------@
  ---- ����������ջ����������Ĺ� ----
\**=====copyright (c) DoYouHaoBaby=====**/

class Mp3s{
 //$db
 public $DB;

 function __construct($newdb){
	$this->DB=$newdb;
 }

/**
 * �������ַ���
 *
 */
function AddMp3Sort($SaveMp3sortDate){
  add_sql($SaveMp3sortDate,'mp3sort');
}

/**
 * �������ַ���
 *
 */
function UpdMp3Sort($SaveMp3SortDate,$mp3sort_id){
  update_sql($SaveMp3SortDate,'mp3sort_id',$mp3sort_id,'mp3sort');
}
	   
/**
 * ɾ�����ַ���
 *
 */
function DeleteMp3Sort($mp3sort_id){     
  $this->DB->query("DELETE FROM `".DB_PREFIX."mp3sort` where `mp3sort_id`=$mp3sort_id ");
  //�ƶ�������Ĭ�Ϸ���
  $this->DB->query("update `".DB_PREFIX."mp3` set `mp3sort_id`='-1' where `mp3sort_id`='$mp3sort_id'");
}

/**
 * ���ַ���
 *
 */
function GetMp3Sorts($mp3sort_id=''){    
  if($mp3sort_id==''){
	 $mp3sorts =$this->DB->gettworow("select *from `".DB_PREFIX."mp3sort`  order by `compositor` asc");
	 if($mp3sorts){
	 $i=0;
	 foreach($mp3sorts as $value){
      $mp3sorts[$i]['mp3sortname']=stripslashes($value['mp3sortname']);
	  $i++;
    }}
  }else{ 
      $mp3sorts =$this->DB->getonerow("select *from `".DB_PREFIX."mp3sort` where `mp3sort_id`=$mp3sort_id");
	  $mp3sorts['mp3sortname']=stripslashes($mp3sorts['mp3sortname']);
  }	  
  return $mp3sorts;
}
		 
/**
 * ����mp3
 *
 */
function AddMp3($SaveMp3Date){
   add_sql($SaveMp3Date,'mp3');
}
	 
/**
 * ���������ν�
 *
 */
function UpdMp3($SaveMp3Date,$mp3_id){
  update_sql($SaveMp3Date,'mp3_id',$mp3_id,'mp3');
}

/**
 * ɾ������
 *
 */
function DelMp3($mp3_id){      
	$this->DB->query("DELETE FROM `".DB_PREFIX."mp3` where `mp3_id`=$mp3_id ");
	//�ƶ����ۣ�������תΪ���԰�����
	$this->DB->query("update `".DB_PREFIX."comment` set `mp3_id`='0' where `mp3_id`='$mp3_id'");
}

 /**
  * �ƶ�����
  *
  */
function ChangeMp3Sort($mp3_id,$mp3sort_id){
	$this->DB->query("update `".DB_PREFIX."mp3` set `mp3sort_id`=$mp3sort_id where `mp3_id`=$mp3_id");
}

/**
 * ��ȡ��������
 *
 */
function GetOneMp3($mp3_id){
	$idmp3=$this->DB->getonerow("select *from`".DB_PREFIX."mp3` where `mp3_id`=$mp3_id");
	if($idmp3){
	    $idmp3['mp3name']=stripslashes($idmp3['mp3name']);
	}
	return $idmp3;
}

/**
  * ��������
  *
  */
 function GetMp3s($Mp3sort_id,$start,$end){   
	$Mp3sort=$Mp3sort_id?"where `mp3sort_id`='$Mp3sort_id'":'';
	$limited=($start==''&&$end=='')?'':"limit $start,$end";
	$Mp3s=$this->DB->gettworow("select *from `".DB_PREFIX."mp3` $Mp3sort order by `dateline` desc $limited");
	if($Mp3s){
	$i=0;
	foreach($Mp3s as $value){
     $Mp3s[$i]['name']=stripslashes($value['name']);
	 $i++;
   }}
	return $Mp3s;
  }
}

?>