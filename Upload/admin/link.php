<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * �ļ���links.php
        * ˵�����νӹ���
        * ���ߣ�Сţ��
        * ʱ�䣺2010-07-22 0:17
        * �汾��DoYouHaoBaby-blog �����.����˿�� (�ر��)
        * ��ҳ��www.doyouhaobaby.com
		* ��̳��bbs.56swun.com
@----------------------------------------@
  ---- ����������ջ����������Ĺ� ----
\**=====copyright (c) DoYouHaoBaby=====**/

CheckPermission("cp",$common_permission[41]);

$LinkId = intval( get_argget('id'));

//�ν��б�
if($view==''||$view=='list'){
    $side_Links=dyhb_onearray($side_LogoLinks,$side_TextLinks);
}
//ɾ���ν�
if($view=="del"&&$LinkId){
	$_Links->DeleteLink($LinkId);
	CacheLinks();
	//���¾�̬��
	  if( $dyhb_options[allowed_make_html]=='1'){
              MakePagenav("link");
	  }
    Header("location:?action=link&del=true");
}
//�����ν�
if($view=="save"){
	
    //�ν�����
    $isdisplay =intval ( get_argpost('isdisplay')) ;
    $link_id =intval ( get_argpost('link_id')) ;
    $name =sql_check ( get_argpost('name'));
    if($name==''){Dyhbmessage("<font color=\"red\"><b>$common_func[246]</b></font>","-1");}//�ж�
    $url =sql_check ( get_argpost('url')) ;
    $description=sql_check ( get_argpost('description')) ;
    $compositor=intval ( get_argpost('compositor')) ;
    $logo  =sql_check ( get_argpost('logo'));

    //��ʽ���
    isurl($url);
    isurl($logo);

    //�ν�����*˳�����Ҫ
    $SaveLinkDate=array(
      'link_id'=>$link_id,
      'name'=>$name,
      'url'=>$url,
      'description'=>$description,
      'logo'=>$logo,
      'isdisplay'=>$isdisplay,
      'compositor'=>$compositor,
     );

    if($link_id>0){//�����ν�
       $_Links->UpdLink($SaveLinkDate,$link_id);
	   CacheLinks();
	   //���¾�̬��
	  if( $dyhb_options[allowed_make_html]=='1'){
              MakePagenav("link");
	  }
       Header("location:?action=link&&id={$link_id}&upd=true");
    }else{//�����ν�	      
       $_Links->AddLink($SaveLinkDate); 
	   CacheLinks();
	   //���¾�̬��
	  if( $dyhb_options[allowed_make_html]=='1'){
              MakePagenav("link");
	  }
       Header("location:?action=link&add=true");
    }
}
       
//�༭�ν�
if($view=="upd"&&$LinkId){
    $UpdLink=$_Links->GetOneLink($LinkId);
}

include DyhbView('link',1);	;

?>