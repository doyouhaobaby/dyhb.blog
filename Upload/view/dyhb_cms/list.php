<?php if(!defined('DOYOUHAOBABY_ROOT')){exit('hi,friend��');}
  include DyhbView('header','');
?>
<div id='main'><div class='float-left'><?php echo $front_header[1];?><a href="./"><?php echo $dyhb_options[blog_title];?></a> &raquo; <a href="./" title="<?php echo $front_header[0];?>"><?php echo $front_header[0];?></a> 
<?php _global_blog_pagenav();?>
&raquo; <?php echo $front_header[2];?>:(<?php _narmal_list();?> )</div><br><br>
<h2>��־�б�</h2>
<div id="pagenav"><?php echo $pagination;?></div>
<?php _loglist_table();?>
<!--��ҳ��-->
<div id="pagenav"><?php echo $pagination;?></div>
<?php if( $sortid ):?>
<script type="text/javascript" src="images/js/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="admin/editor/xheditor-zh-cn.min.js"></script> 
<script type="text/javascript" src="images/js/width.js"></script>
<div class="box2">
<a name="quicklog"></a><h3>���ٷ���</h3>
<form method="post"  action="public.php?do=save" onsubmit="return checkform();">
<input type="hidden" name="sort_id" value="<?php echo $sortid;?>"/>
<p><label for="title">���⣺</label>
<input type="text" name="title" id="title" size="70"/></p>
<?php if( !ISLOGIN ):
if($dyhb_options[is_float_div_action]=='1'){
$login="<a href=\"javascript:;\" onclick=\"showajaxdiv('login', '{$dyhb_options[blogurl]}/getxml.php?action=login', 500);\">�����¼</a>";
$register="<a href=\"javascript:;\" onclick=\"showajaxdiv('register', '{$dyhb_options[blogurl]}/getxml.php?action=register', 500);\">�û�ע��</a>";
}else{
$login="<a href='{$dyhb_options[blogurl]}/login.php'>�����¼</a>";
$register="<a href=\"{$dyhb_options[blogurl]}/register.php\">�û�ע��</a>";
}
?>
<p>�㻹û�е�¼����¼������ܿ���Ͷ����־��<?php echo $login;?> | <?php echo $register;?></p>
<?php elseif( !CheckPermission("sendentry","��û��Ȩ��Ͷ�壡",'0') ):?>
<p>��û��Ͷ��Ȩ�ޣ������ڵ��û���(<?php echo $dyhb_premission[gpname];?>)û��Ȩ�޷��ʣ�<a href='<?php echo $dyhb_options[blogurl];?>?action=usergroup&id=<?php echo $dyhb_usergroup;?>'>����鿴���Ȩ��</a></p>
<?php else:?>
<p><label for="content">���ݣ�<a href="javascript:showdiv('hideupload');">(��Ӹ���)</a></label>
<div id="hideupload" style="display:none"><iframe width="700" height="300" frameborder="0" src="admin/upload.php"></iframe></div>
<textarea name="content" id="content" style="width:530px;"/></textarea>
<script type="text/javascript"> 
 $('#content').xheditor({
    tools:'Source,|,Blocktag,Fontface,FontSize,|,Bold,Italic,Underline,Strikethrough,FontColor,BackColor,|,Align,List,Outdent,Indent,Removeformat,Img,Emot',
	skin:'default',
	emots:{msn:{name:'MSN',count:40,width:22,height:22,line:8}}
});
</script>
</p>
<?php endif;?>
<p>
<input type="submit" class="button submit"  value="��������" <?php if( !CheckPermission("sendentry","��û��Ȩ��Ͷ�壡",'0') || !ISLOGIN ):?>disabled<?php endif;?>>
</p>
</form>
</div>
<?php endif;?>
</div> 
<?php
include DyhbView('sidebar','');
include DyhbView('footer','');
?>