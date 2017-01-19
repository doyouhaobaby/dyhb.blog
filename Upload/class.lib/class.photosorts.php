<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * �ļ���c.function.photosorts.php
        * ˵�������
        * ���ߣ�Сţ��
        * ʱ�䣺2010-07-22 0:17
        * �汾��DoYouHaoBaby-blog �����.����˿�� (�ر��)
        * ��ҳ��www.doyouhaobaby.com
		* ��̳��bbs.56swun.com
@----------------------------------------@
  ---- ����������ջ����������Ĺ� ----
\**=====copyright (c) DoYouHaoBaby=====**/

class Photosorts{
  public $DB;

  function __construct($newdb){
	$this->DB=$newdb;
  }

 /**
  * ����������
  *
  */
function AddSort($SavePhotosortDate){
   add_sql($SavePhotosortDate,'photosort');
}

/**
 * ����������
 *
 */
function UpdSort($SaveSortDate,$photosort_id){
 update_sql($SaveSortDate,'photosort_id',$photosort_id,'photosort');
}
	   
/**
 * ɾ��������
 *
 */
function DelSort($photosort_id){     
  $this->DB->query("DELETE FROM `".DB_PREFIX."photosort` where `photosort_id`=$photosort_id ");
  //�ƶ�ͼƬ��Ĭ�����
  $this->DB->query("update `".DB_PREFIX."file` set `photosort_id`='-1' where `photosort_id`='$photosort_id'");
}

/**
 * ������
 *
 */
function GetSorts($photosort_id=''){    
 if($photosort_id==''){
	$sorts =$this->DB->gettworow("select *from `".DB_PREFIX."photosort`  order by compositor asc");
	if($sorts){
	$i=0;
	foreach($sorts as $value){
    $sorts[$i]['name']=stripslashes($value['name']);
	$i++;
   }}
}else{ 
    $sorts =$this->DB->getonerow("select *from `".DB_PREFIX."photosort` where `photosort_id`=$photosort_id");
    $sorts['name']=stripslashes($sorts['name']);
} 
 
 return $sorts;
}
		 
/**
 * ���Ӹ���
 *
 */
function AddFile($SaveFileDate){
 add_sql($SaveFileDate,'file');
 $file_id=$this->DB->insert_id();
 return $file_id;
}

/**
 * ���¸���
 *
 */
function UpdFile($FileDate,$file_id){
 update_sql($FileDate,'file_id',$file_id,'file');
}

/**
 * ɾ������
 *
 */
function DelFile($file_id){      
  $this->DB->query("DELETE FROM ".DB_PREFIX."file where file_id=$file_id ");
  //�ƶ����ۣ�������תΪ���԰�����
  $this->DB->query("update `".DB_PREFIX."comment` set `file_id`='0' where `file_id`='$file_id'");
}

/**
 * �ƶ�����
 *
 */
function ChangeFileSort($file_id,$photosort_id){
	$this->DB->query("update `".DB_PREFIX."file` set `photosort_id`=$photosort_id where `file_id`=$file_id");
}

/**
 * ��ȡ�����ļ�
 *
 */
function GetIdFile($file_id){
  $idfile=$this->DB->getonerow("select *from`".DB_PREFIX."file` where file_id=$file_id");
  if($idfile){
      $idfile['filename']=stripslashes($idfile['name']);
  }
  return $idfile;
}
		
/**
  * ������Ƭ
  *
  */
function GetPreFile($file_id){
  $prefile=$this->DB->getonerow("SELECT  *FROM `".DB_PREFIX."file` WHERE file_id>$file_id and `name` REGEXP '(jpg|jpeg|bmp|gif|png)$' ORDER BY `dateline` DESC LIMIT 1");
  if($prefile){
      $prefile['filename']=stripslashes($prefile['filename']);
  }
  return $prefile;
}

function GetNextFile($file_id){
 $nextfile=$this->DB->getonerow("SELECT *FROM `".DB_PREFIX."file` WHERE file_id<$file_id and `name` REGEXP '(jpg|jpeg|bmp|gif|png)$' ORDER BY `dateline` DESC LIMIT 1");
 if($nextfile){
     $nextfile['name']=stripslashes($nextfile['name']);
 }
 return $nextfile;
}

/**
 * ���и���
 *
 */
function GetFiles($photosort_id,$start,$end,$conditon=''){   
  $photosort=$photosort_id==''?'':"where `photosort_id`='$photosort_id'";
  $conditon=$conditon==''?'':$conditon;
  $limited=($start==''&&$end=='')?'':"limit $start,$end";
  $Sql="select *from `".DB_PREFIX."file` $conditon $photosort order by `dateline` desc $limited";
  $Files =$this->DB->gettworow($Sql);
  if($Files){
  $i=0;
  foreach($Files as $value){
    $Files[$i]['name']=stripslashes($value['name']);
	$i++;
  }
  }
  return $Files;
}

}
?>