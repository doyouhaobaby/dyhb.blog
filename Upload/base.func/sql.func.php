<?php
//插入一条信息
function add_sql($savedate,$table){
   global $DB;
   $Key = array();
   $Value=array();
   foreach ($savedate as $key => $value){
	  $Value[] = $value;
	  $Key[]= $key;
   }
   $Field ="`".implode("`,`", $Key)."`";
   $ValueStr ="'".implode("','",$Value)."'";
   $DB->query("insert into `".DB_PREFIX."$table` ($Field) values($ValueStr)");
}

//更新一条信息
function update_sql($savedate,$xx_id,$xx_value,$table){
   global $DB;
   $SqlAction = array();
   foreach ($savedate as $key => $value){
	  $SqlAction[] = "`$key`='$value'";
   }
   $UpdateStr = implode(',', $SqlAction);
   $DB->query("update `".DB_PREFIX."$table` set $UpdateStr where `$xx_id`='$xx_value'");
 }

?>