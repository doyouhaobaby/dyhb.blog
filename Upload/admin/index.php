<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * �ļ���index.php
        * ˵������̨��ҳ
        * ���ߣ�Сţ��
        * ʱ�䣺2010-07-22 0:17
        * �汾��DoYouHaoBaby-blog �����.����˿�� (�ر��)
        * ��ҳ��www.doyouhaobaby.com
		* ��̳��bbs.56swun.com
@----------------------------------------@
  ---- ����������ջ����������Ĺ� ----
\**=====copyright (c) DoYouHaoBaby=====**/

/** ���ع��ú��� */
require_once("global.php");

if(!defined('DOYOUHAOBABY_ROOT')) {
	exit('hi,friend!');
}

/** ��½��֤ */
CheckLogin();

/** ����˵� */
//��ҳ
$adminitem['index']=array(
			'name'=>$admin_sidebar['0'],
			'start'=>1
		);
$adminitem['index']['submenu'][]=array('name'=>$admin_sidebar['0'], 'do'=>'index');
$adminitem['index']['submenu'][]=array('name'=>$admin_sidebar['47'], 'do'=>'upd');
//����
$adminitem['log']['name']=$admin_sidebar['1'];
$adminitem['log']['submenu'][]=array('name'=>$admin_sidebar['2'], 'do'=>'add', 'default'=>1);
$adminitem['log']['submenu'][]=array('name'=>$admin_sidebar['3'], 'do'=>'ishide');
$adminitem['log']['submenu'][]=array('name'=>$admin_sidebar['4'], 'do'=>'list');
if($dyhb_userid=='1' ){
    $adminitem['log']['submenu'][]=array('name'=>$admin_sidebar['5'], 'do'=>'myfield');
}
$adminitem['log']['submenu'][]=array('name'=>'Tags', 'do'=>'tag');
if(($dyhb_usergroup!='5'&&$dyhb_usergroup!='3')||CheckPermission("cp",$common_permission[42],'0')){
   $adminitem['log']['submenu'][]=array('name'=>$admin_sidebar['6'], 'do'=>'ispage');
   $adminitem['log']['submenu'][]=array('name'=>$admin_sidebar['7'], 'do'=>'trackback');
   $adminitem['log']['submenu'][]=array('name'=>$admin_sidebar['8'], 'do'=>'microlog');
}
//����
if($dyhb_userid=='1'){
	$adminitem['sort']=array(
			'name'=>$admin_sidebar['9'],
			'submenu' => array(
			    	array('name'=>$admin_sidebar['9'], 'do'=>'child', 'default'=>1),
		            array('name'=>$admin_sidebar['10'], 'do'=>'add'),
			)
		);
}
//����
if(($dyhb_usergroup!='5'&&$dyhb_usergroup!='3')||CheckPermission("cp",$common_permission[42],'0')){
	$adminitem['comment']=array(
			'name'=>$admin_sidebar['11'],
			'submenu' => array(
	            array('name'=>$admin_sidebar['12'], 'do'=>'list', 'default'=>1),
		        array('name'=>$admin_sidebar['13'], 'do'=>'blog'),
	            array('name'=>$admin_sidebar['14'], 'do'=>'mp3'),
				array('name'=>$admin_sidebar['15'], 'do'=>'photo'),
				array('name'=>$admin_sidebar['16'], 'do'=>'taotao'),
	            array('name'=>$admin_sidebar['17'], 'do'=>'guestbook'),
			)
		);
}

//ý��
if(($dyhb_usergroup!='5'&&$dyhb_usergroup!='3')||CheckPermission("cp",$common_permission[42],'0')){
$adminitem['photo']= array(
			'name'=>$admin_sidebar['18'],
			'submenu' => array(
				array('name'=>$admin_sidebar['19'], 'do'=>'list', 'default'=>1),
				array('name'=>$admin_sidebar['20'], 'do'=>'mp3'),
				array('name'=>$admin_sidebar['21'], 'do'=>'mp3sort'),
			)
		);
}
//�û�
if($dyhb_userid=='1'||($dyhb_usergroup=='1'||CheckPermission("cp",$common_permission[42],'0'))){
$adminitem['user']['name']=$admin_sidebar['22'];
$adminitem['user']['submenu'][]=array('name'=>$admin_sidebar['23'], 'do'=>'list', 'default'=>1);
$adminitem['user']['submenu'][]=array('name'=>$admin_sidebar['24'], 'do'=>'add');
if($dyhb_userid=='1'){
    $adminitem['user']['submenu'][]=array('name'=>$admin_sidebar['25'],'do'=>'usergroup');
}
}
//�ν�
if($dyhb_userid=='1'||($dyhb_usergroup=='1'||CheckPermission("cp",$common_permission[42],'0'))){
$adminitem['link']=array(
			'name'=>$admin_sidebar['26'],
			'submenu' => array(
				array('name'=>$admin_sidebar['26'], 'do'=>'list', 'default'=>1),
				array('name'=>$admin_sidebar['27'], 'do'=>'add'),
			)
		);
}
//��չ
if($dyhb_userid=='1'||($dyhb_usergroup=='1'||CheckPermission("cp",$common_permission[42],'0'))){
      $adminitem['template']=array(
			'name'=>$admin_sidebar['28'],
			'submenu' => array(
				array('name'=>$admin_sidebar['29'], 'do'=>'user', 'default'=>1),
		        array('name'=>$admin_sidebar['30'], 'do'=>'blogcover'),
				array('name'=>$admin_sidebar['31'], 'do'=>'admin'),
				array('name'=>$admin_sidebar['32'], 'do'=>'widget'),
				array('name'=>$admin_sidebar['33'], 'do'=>'plugin'),
			)
	 );
}
//��������
if($dyhb_userid=='1'||($dyhb_usergroup=='1'||CheckPermission("cp",$common_permission[42],'0'))){
     $adminitem['backup']['name']=$admin_sidebar['34'];
     if($dyhb_userid=='1'){
          $adminitem['backup']['submenu'][]=array('name'=>$admin_sidebar['35'], 'do'=>'list', 'default'=>1);
          $adminitem['backup']['submenu'][]=array('name'=>$admin_sidebar['36'], 'do'=>'html');
     }
     $adminitem['backup']['submenu'][]=array('name'=>$admin_sidebar['37'], 'do'=>'cachefile');
     $adminitem['backup']['submenu'][]=array('name'=>$admin_sidebar['38'], 'do'=>'rebuild');
}
//ϵͳ����
if($dyhb_userid=='1'||($dyhb_usergroup=='1'||CheckPermission("cp",$common_permission[42],'0'))){
	$adminitem['option']['name']=$admin_sidebar['39'];
    $adminitem['option']['end']="1";
    $adminitem['option']['submenu'][]=array('name'=>$admin_sidebar['40'], 'do'=>'basic', 'default'=>1);
    $adminitem['option']['submenu'][]=array('name'=>$admin_sidebar['41'], 'do'=>'display');
    $adminitem['option']['submenu'][]=array('name'=>$admin_sidebar['42'], 'do'=>'comment');
    $adminitem['option']['submenu'][]=array('name'=>$admin_sidebar['43'], 'do'=>'file');
    $adminitem['option']['submenu'][]=array('name'=>$admin_sidebar['44'], 'do'=>'safe');
    if($dyhb_userid=='1'){
        $adminitem['option']['submenu'][]=array('name'=>$admin_sidebar['45'], 'do'=>'ad');
        $adminitem['option']['submenu'][]=array('name'=>$admin_sidebar['46'],'do'=>'link');
    }
}

switch($View){
 case "phpinfo":echo phpinfo();break;
 case "photo":$content="photo.php";break;
 case "log":$content="log.php";break;
 case "backup":$content="backup.php";break;
 case "sort":$content="sort.php";break;
 case "mp3":$content="mp3.php";break;
 case "link":$content="link.php";break;
 case "comment":$content="comment.php";break;
 case "option":$content="option.php";break;
 case "template":$content="template.php";break;
 case "user":$content="user.php";break;
 default:include DyhbView('index',1);break;
}
if($content!=""){
    require_once($content);
}

?>