<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * �ļ���c.function.mysql.php
        * ˵�������ݿ��װ
        * ���ߣ�Сţ��
        * ʱ�䣺2010-07-22 0:17
        * �汾��DoYouHaoBaby-blog �����.����˿�� (�ر��)
        * ��ҳ��www.doyouhaobaby.com
		* ��̳��bbs.56swun.com
@----------------------------------------@
  ---- ����������ջ����������Ĺ� ----
\**=====copyright (c) DoYouHaoBaby=====**/

class Mysql{ 

  //jquery��ѯ����
  var $querycount = 0;
   
  function __construct (){
	$num_args = func_num_args();		  
	if($num_args > 0){
		$args = func_get_args();
		$this->host = $args[0];
		$this->user = $args[1];
		$this->pass = $args[2];
		$this->connect();
	}
  }
	
  /**
   *�������ݿ�
   */
  function connect(){
	global $common_func;
    if (!$this->db = @mysql_connect ($this->host,$this->user,$this->pass)){
	   $this->showerror($common_func[92]);
	}
  }

  /**
   *ѡ�����ݿ�
   */
  function selectdb ($thedb){
	global $common_func;
    if (!@mysql_select_db ($thedb, $this->db)){
	    $this->showerror($common_func[93]);
     }
  }

  /**
   *���Ͳ�ѯ���
   */
  function query($thequery){
	global $common_func;
    if (!@ mysql_query ($thequery, $this->db)){
	   $this->showerror($common_func[94]);
    }
	$this->querycount++;
  }

  /**
   *���ӱ���
   */
  function setchar($chartype){
     $this->query("SET NAMES $chartype");
  }

  /**
   *������һ������ID
   */
 function insert_id(){
	 return @mysql_insert_id($this->db);
 }

  /**
   *�ͷ��ڴ�
   */
  function free($queryr){
	  return @mysql_free_result($queryr);
  }

  /**
   *��ȡ�ֶ�
   */
  function getthefield($table){
      $fields=mysql_list_fields(DB_NAME,DB_PREFIX.$table,$this->db);
	  $columns  = @mysql_num_fields($fields); 
      $a=array();
      for ($i=0;$i<$columns;$i++){ 
          $a[]= @mysql_field_name($fields,$i); 		
      } 
	  return $a;
  }

  /**
   *���ؼ�¼����
   */
  function getresultnum($thequery){
   global $common_func;
   if (!$resultquery = @mysql_query ($thequery)){
	 $this->showerror($common_func[94]);
   }else{
	 $r=mysql_fetch_array($resultquery);
	 $this->free($resultquery);
	 return $r['0'];												
   }
 }
  
  /**
   *����һά����
   */
  function getonerow ($thequery){
	global $common_func;
    if (!$resultquery = @mysql_query ($thequery)){
		$this->showerror($common_func[94]);
    }else{
		$returnarray = array ();
		while ($row = @mysql_fetch_array ($resultquery)){
			$returnarray = array_merge ($returnarray,$row);
		}
		$this->free($resultquery);
		return $returnarray;
   } 
 }
		
 /**
   *���ض�ά����
   */	
 function gettworow($thequery){
  global $common_func;
  if (!$resultquery = @mysql_query ($thequery)){
	$this->showerror($common_func[94]);
  }else{
	$returnarray = array ();
	$i=0;
	while ($row = @mysql_fetch_array ($resultquery)){
		$returnarray[$i]=$row;
		$i++;
	}
	$this->free($resultquery);
	return $returnarray;
 }
}

 /**
   *��ȡ����
   */
 function showerror($errortype){
    $errorstring= @mysql_errno() . ": " . @mysql_error()."<br>".$errortype;
	DyhbMessage($errorstring,'0');
	exit();
}

/**
  *�ر����ݿ�
  */
function __destruct(){
 global $common_func;
 if (!@mysql_close ($this->db)){   
	$this->showerror($common_func[95]);
  }
 }

}
	
?>