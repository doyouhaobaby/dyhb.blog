<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * �ļ���tpl.function.php
        * ˵����ģ�庯��
        * ���ߣ�Сţ��
        * ʱ�䣺2010-07-22 0:17
        * �汾��DoYouHaoBaby-blog �����.����˿�� (�ر��)
        * ��ҳ��www.doyouhaobaby.com
		* ��̳��bbs.56swun.com
@----------------------------------------@
  ---- ����������ջ����������Ĺ� ----
\**=====copyright (c) DoYouHaoBaby=====**/

/**
  * ģ�庯��
  *
  * @param string $thename �ļ���like this index show.log
  * @return string $path|url·��
  */	
function DyhbView($thename,$isadmin) {
	global $dyhb,$dyhb_options,$_COOKIE;
	/** ����ģ���� */
	$t_c=get_cookie('tpl')?get_cookie('tpl'):$dyhb_options['user_template'];
	/** Ϊȫ�ֱ���$dyhb��ֵ����ģ������޹أ���Ҳ�йأ�����*/
	if($isadmin==1){
		$dyhb='view/'.$dyhb_options['admin_template'].'/$dyhb';
	}else{
		$dyhb='view/'.$t_c.'/$dyhb';
	}
	/** ����ģ�� */
	if($isadmin=='1'){
		$dyhb_path='view/'.$dyhb_options['admin_template'];
	}else{
		$dyhb_path='view/'.$t_c;
	}
	$thename=$thename.'.php';
	$path=$dyhb_path."/".$thename;
	/** û�м���Ĭ��ģ�� */
	if(!file_exists($path)){
	    if($is_admin=='1'){
		    $path="admin/view/default/".$thename;
		}else{
		    $path="view/default/".$thename;
		}
	}
	//���ģ��
	if(file_exists($path)){
		return $path;
	}
	else{
		DyhbMessage($common_func[0]."<font color='red'>{$path}</font>",'0');
	}
}

/**
  * ҳ�����
  *
  * @return string 
  */
function Footer() {
	global $DB, $Starttime, $dyhb_options;
	$Microtime = explode(' ', microtime());
	$Totaltime = number_format(($Microtime[1] + $Microtime[0] - $Starttime), 6);
	$Gzip = $dyhb_options['gzipcompress']==1? 'enabled' : 'disabled';
	echo 'Processed in '.$Totaltime.' second(s), '.$DB->querycount.' queries, Gzip '.$Gzip;
}

?>