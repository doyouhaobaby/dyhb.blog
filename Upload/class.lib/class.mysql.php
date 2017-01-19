<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * 文件：c.function.mysql.php
        * 说明：数据库封装
        * 作者：小牛哥
        * 时间：2010-07-22 0:17
        * 版本：DoYouHaoBaby-blog 告别大二.想念丝瓜 (特别版)
        * 主页：www.doyouhaobaby.com
		* 论坛：bbs.56swun.com
@----------------------------------------@
  ---- 西南民大的天空还是蛮明亮的哈 ----
\**=====copyright (c) DoYouHaoBaby=====**/

class Mysql{ 

  //jquery查询数量
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
   *连接数据库
   */
  function connect(){
	global $common_func;
    if (!$this->db = @mysql_connect ($this->host,$this->user,$this->pass)){
	   $this->showerror($common_func[92]);
	}
  }

  /**
   *选择数据库
   */
  function selectdb ($thedb){
	global $common_func;
    if (!@mysql_select_db ($thedb, $this->db)){
	    $this->showerror($common_func[93]);
     }
  }

  /**
   *发送查询语句
   */
  function query($thequery){
	global $common_func;
    if (!@ mysql_query ($thequery, $this->db)){
	   $this->showerror($common_func[94]);
    }
	$this->querycount++;
  }

  /**
   *连接编码
   */
  function setchar($chartype){
     $this->query("SET NAMES $chartype");
  }

  /**
   *返回上一部操作ID
   */
 function insert_id(){
	 return @mysql_insert_id($this->db);
 }

  /**
   *释放内存
   */
  function free($queryr){
	  return @mysql_free_result($queryr);
  }

  /**
   *获取字段
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
   *返回记录总数
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
   *返回一维数组
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
   *返回二维数组
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
   *获取错误
   */
 function showerror($errortype){
    $errorstring= @mysql_errno() . ": " . @mysql_error()."<br>".$errortype;
	DyhbMessage($errorstring,'0');
	exit();
}

/**
  *关闭数据库
  */
function __destruct(){
 global $common_func;
 if (!@mysql_close ($this->db)){   
	$this->showerror($common_func[95]);
  }
 }

}
	
?>