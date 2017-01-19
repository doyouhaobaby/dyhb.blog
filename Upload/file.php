<?php
/**================[^_^]================**\
        ---- 因为有梦，所以有目标 ----
@----------------------------------------@
        * 文件：trackback.php
        * 说明：引用通告
        * 作者：小牛哥
        * 时间：2010-05-06 20:22
        * 版本：DoYouHaoBaby-blog 概念版
        * 主页：www.doyouhaobaby.com
@----------------------------------------@
  ---- 西南民大的天空还是蛮明亮的哈 ----
\**=====copyright (c) DoYouHaoBaby=====**/

/** 加载核心部件 */
require('width.php');

/** url参数 */
$FileId = intval( get_argget('id') );

/** 附件防盗链设置 */
if ($dyhb_options['is_image_leech']=='0') {
	$allow_host = unserialize($dyhb_options[is_leech_domail]);
	$nowurl=parse_url($_SERVER['HTTP_HOST']);
	$referer = parse_url($_SERVER['HTTP_REFERER']);
	$allow_host[]=$nowurl['host'];
	if (!in_array($referer['host'], $allow_host)) {
	     header('Content-Encoding: none');
	     header('Content-Type: image/gif');
	     header('Content-Disposition: inline; filename="no_leech.gif"');
	     $fp=fopen('images/other/no_leech.gif','rb');
	     fpassthru($fp);
	     fclose($fp);
	     exit();
	}
}


/**　附件信息，必要的处理　*/
$TheFile =$DB->getonerow("SELECT file_id,path,filetype,name,dateline FROM ".DB_PREFIX."file WHERE file_id = '$FileId'");
$the_type=substr($TheFile['path'],strpos($TheFile['path'],'.')+1);
if($the_type!="jpeg"&&$the_type!="png"&&$the_type!="jpeg"&&$the_type!="gif"&&$the_type!="bmp"){
   if(!CheckPermission("downfile",$common_permission[4],'0')){
	   echo "{$common_width[56]}($dyhb_premission[gpname]){$common_width[57]}<a href='index.php?action=usergroup&id=$dyhb_usergroup'>{$common_width[58]}</a>";
	   exit();
   }
}

if (!$TheFile) {
	 page_not_found();
} else {
	//附件读取形式，inline直接读取，attachment下载到本地
    $disposition = $dyhb_options['is_image_inline']=='1' ? 'inline' : 'attachment';
	//附件统计下载次数
	$d = false;
	if ( $attachmemts = get_cookie('attachmemts') ) {
		 $idarr = explode(',', $attachmemts);
		 if ( in_array($FileId, $idarr) ) {
			  $d = true;
		 }
	}
	if (!$d) {
		 $DB->query("UPDATE ".DB_PREFIX."file SET download = download + 1 WHERE file_id = '$FileId'");
		 $attachments .= empty($attachments) ? $FileId : ','.$FileId;
		 set_cookie('attachmemts', $attachments);
	}
	$TheFile['filetype'] = $TheFile['filetype'] ? $TheFile['filetype'] : 'application/octet-stream';
	$TheFile['path'] = DOYOUHAOBABY_ROOT."/width/upload/".$TheFile['path'];
	$TheFile['name'] = basename($TheFile['name']);

	//生成附件
	if (is_readable($TheFile['path'])) {
		ob_end_clean();
		header('Cache-control: max-age=31536000');
		header('Expires: ' . get_date('D, d M Y H:i:s',PHP_TIME + 31536000) . ' GMT');
		header('Last-Modified: ' . get_date('D, d M Y H:i:s',$TheFile['dateline']) . ' GMT');
		header('Content-Encoding: none');
		header('Content-type: '.$TheFile['filetype']);
		header('Content-Disposition: '.$disposition.'; filename='.$TheFile[name]);
		header('Content-Length: '.filesize($TheFile['path']));
		$fp = fopen($TheFile['path'], 'rb');
		fpassthru($fp);
		fclose($fp);
		exit;
	} else {
		exit($common_width[59]);
	}
}
?>