<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * �ļ���public.php
        * ˵�����οͷ��������Լ�ǰ̨��������
        * ���ߣ�Сţ��
        * ʱ�䣺2010-07-22 0:17
        * �汾��DoYouHaoBaby-blog �����.����˿�� (�ر��)
        * ��ҳ��www.doyouhaobaby.com
		* ��̳��bbs.56swun.com
@----------------------------------------@
  ---- ����������ջ����������Ĺ� ----
\**=====copyright (c) DoYouHaoBaby=====**/

/** ���غ��Ĳ��� */
require_once("width.php");

/** ��ʱ�ر�Ͷ�� */
if($dyhb_options['allowed_send']=='0'){
    DyhbMessage($common_width[17],'index.php');
}
/** Ͷ��Ȩ�޹��� */
CheckPermission("sendentry",$common_permission[50]);

/** ��Ȩ���� */
$SortId = intval( get_argget('sortid') );

/** ��������,��ȡ�������� */
if($view=="save"){
	//���±�����
    $title = get_argpost( 'title')  ;
    $content = get_argpost( 'content') ; 
	$content=str_replace($dyhb_options[blogurl]."/width/upload","width/upload",$content);//����·������,��ֹ��Ϊ������������ļ�ʧЧ
	$content=str_replace($dyhb_options[blogurl]."/file.php?id=","file.php?id=",$content);//����·������,��ֹ��Ϊ������������ļ�ʧЧ
    $from =sql_check( get_argpost( 'from') );   
    $fromurl=sql_check( get_argpost( 'fromurl')) ;
    $sort_id =intval( get_argpost( 'sort_id') );
    $thumb =sql_check( get_argpost( 'thumb')) ;
    $tag =sql_check( get_argpost( 'tag') );
	$isshow=( $dyhb_options['allowed_shenghe']=='1'&&LOGIN_USERGROUNP!='1'&&LOGIN_USERGROUNP!='2'&&!ISLOGIN )?'0':'1';
    if($thumb)     isurl($thumb);
	if($fromurl)   isurl($fromurl);
	$thumb=str_replace($dyhb_options[blogurl]."/width/upload","width/upload",$thumb);//����·������,��ֹ��Ϊ������������ļ�ʧЧ
	$user_id=!LOGIN_USERID?'-1':LOGIN_USERID;

	/** �������� */
    $SaveLogDate=array('title'=>$title,
                       'dateline'=>$localdate,
                       'content'=>$content,
                       'from'=>$from,
                       'fromurl'=>$fromurl,
                       'user_id'=>$user_id,
                       'sort_id'=>$sort_id,
                       'thumb'=>$thumb,
                       'isshow'=>$isshow
	);
    $SaveBlogId=$_Logs->AddLog($SaveLogDate);
    $_Tags->AddTag($tag,'',$SaveBlogId,'','');

    /** ������־�������� */
    cachenewlog();
    cachehotlog();
    cacheyearhotlog();
    cachemouthhotlog();
    if($dyhb_options['cache_cms_log']=='1'){
	    CacheTag();
        CacheCmsNew();
	    if($sort_id!='-1'&&''){
			$s_value=$_Sorts->GetIdSort($sort_id);
			cacheSort($s_value);
		}
    }
	//���¾�̬��
	 if( $dyhb_options[allowed_make_html]=='1'){
	    //��ȡ��־����
	    $the_log=$_Logs->GetOneLog( $SaveBlogId );
		//���ۺ�������
	    MakePostHtml( $the_log,'post_comment');
	 }
    DyhbMessage($common_width[18],'index.php?p='.$SaveBlogId);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
   <head>
       <title><?php echo $common_width[19];?></title>
       <meta http-equiv="content-type" content="application/xhtml+xml; charset=gbk" />
       <link rel="stylesheet" href="images/common.css" type="text/css" />
   </head>
   <body>
   <!--�༭��-->
<script type="text/javascript" src="images/lang/<?php echo $dyhb_options[global_lang_select];?>/lang.js"></script>
<script type="text/javascript" src="images/js/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="admin/editor/xheditor-zh-cn.min.js"></script> 
<script type="text/javascript" src="images/js/width.js"></script>
      <div id="main">
         <h1><?php echo $common_width[20];?></h1>
            <p><?if($dyhb_options['allowed_shenghe']=='1'&&LOGIN_USERGROUNP!='1'&&LOGIN_USERGROUNP!='2'&&!ISLOGIN):?><?php echo $common_width[21];?><?else:?><?php echo $common_width[36];?><?endif;?>
		    </p>
            <form action="public.php?do=save" method="post" style="text-align:left;" onsubmit="return checkform();">
                <p><label for="title"><?php echo $common_width[22];?></label><input type="text"  name="title" style="width:80%" id="title" onclick="if (this.value=='<?php echo $common_width[37];?>') this.value='';"  value="<?php echo $common_width[37];?>" /><p>
                <p><label for="from"><?php echo $common_width[23];?></label><input  name="from"  type="text" style="width:80%" value="<?php echo $common_width[38];?>"></p>
                <p><label for="fromurl"><?php echo $common_width[24];?></label><input  name="fromurl" type="text" style="width:80%"></p>
                <p><label for="sort_id"><?php echo $common_width[25];?></label>
				<select class="formfield" name="sort_id" id="sort_id">
                   <option value="-1"><?php echo $common_width[26];?></option>
                   <?php if($_globalTreeSorts):foreach($_globalTreeSorts as $value): 
				      $select1=$SortId==$value[sort_id]?'selected':'';
				   ?>
	                    <option value="<?php echo $value[sort_id];?>" <?php echo $select1;?>>|--<?php echo $value[name];?></option>
		                   <?if($value[child]):foreach($value[child] as $val):
						   $select2=$SortId==$val[sort_id]?'selected':'';
						   ?>
		                       <option value="<?php echo $val[sort_id];?>" <?php echo $select2;?>>&nbsp;&nbsp;&nbsp;&nbsp;|----<?php echo $val[name];?></option>
				                   <?php if($val[child]):foreach($val[child] as $val2):
								     $select3=$SortId==$val2[sort_id]?"selected":'';
								   ?>
				                       <option value="<?php echo $val2[sort_id];?>" <?php echo $select3;?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|----<?php echo $val2[name];?></option>
				                   <?endforeach;endif;?>
		                   <?endforeach;endif;?>
                  <?php endforeach;endif;?>
              </select></p>
			  <p><a href="javascript:showdiv('hideupload');"><?php echo $common_width[27];?></a></p>
			  <p><div id="hideupload" style="display:none"><iframe width="700" height="300" frameborder="0" src="admin/upload.php"></iframe></div></p>
              <p><textarea cols="80"  cols="80" id="content" name="content" rows="10" style="width:700px;height:400px;"></textarea>
              <script type="text/javascript"> 
         $('#content').xheditor({
    tools:'Source,|,Blocktag,Fontface,FontSize,|,Bold,Italic,Underline,Strikethrough,FontColor,BackColor,|,Align,List,Outdent,Indent,Removeformat,|,Link,Unlink,Img,Flash,Media,Emot',
	skin:'default',
	emots:{msn:{name:'MSN',count:40,width:22,height:22,line:8}}
});
</script>
</p>
              <p><label for="thumb"><?php echo $common_width[28];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label><input type="text"  name="thumb" id="thumb" style="width:60%"/></p>
              <p><label for="tag"><?php echo $common_width[29];?></label><input type="text"  name="tag" id="tag" style="width:60%"/></p>
			  <p><?php echo $common_width[30];?>:<?php
                       if($HotTag){foreach($HotTag as $value){
						   $tag_c=urlencode($value['tagname']);
                           echo "<a href=\"javascript:insertTag('{$value[name]}');\" title=\"$common_width[31]{$value[lognum]}$common_width[32]\">{$value[name]}</a>&nbsp;";
                       }}
				?></p>
              <p><div style="text-align:center;">
                  <input type="submit" value="<?php echo $common_width[33];?>"/>
             </div></p>
          </form>
          <p><a href='index.php'><?php echo $common_width[34];?></a> | <a href='register.php'><?php echo $common_width[35];?></a></p>
        </div>
    </body>
</html>