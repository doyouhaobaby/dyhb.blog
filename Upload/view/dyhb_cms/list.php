<?php if(!defined('DOYOUHAOBABY_ROOT')){exit('hi,friend！');}
  include DyhbView('header','');
?>
<div id='main'><div class='float-left'><?php echo $front_header[1];?><a href="./"><?php echo $dyhb_options[blog_title];?></a> &raquo; <a href="./" title="<?php echo $front_header[0];?>"><?php echo $front_header[0];?></a> 
<?php _global_blog_pagenav();?>
&raquo; <?php echo $front_header[2];?>:(<?php _narmal_list();?> )</div><br><br>
<h2>日志列表</h2>
<div id="pagenav"><?php echo $pagination;?></div>
<?php _loglist_table();?>
<!--分页条-->
<div id="pagenav"><?php echo $pagination;?></div>
<?php if( $sortid ):?>
<script type="text/javascript" src="images/js/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="admin/editor/xheditor-zh-cn.min.js"></script> 
<script type="text/javascript" src="images/js/width.js"></script>
<div class="box2">
<a name="quicklog"></a><h3>快速发表</h3>
<form method="post"  action="public.php?do=save" onsubmit="return checkform();">
<input type="hidden" name="sort_id" value="<?php echo $sortid;?>"/>
<p><label for="title">标题：</label>
<input type="text" name="title" id="title" size="70"/></p>
<?php if( !ISLOGIN ):
if($dyhb_options[is_float_div_action]=='1'){
$login="<a href=\"javascript:;\" onclick=\"showajaxdiv('login', '{$dyhb_options[blogurl]}/getxml.php?action=login', 500);\">管理登录</a>";
$register="<a href=\"javascript:;\" onclick=\"showajaxdiv('register', '{$dyhb_options[blogurl]}/getxml.php?action=register', 500);\">用户注册</a>";
}else{
$login="<a href='{$dyhb_options[blogurl]}/login.php'>管理登录</a>";
$register="<a href=\"{$dyhb_options[blogurl]}/register.php\">用户注册</a>";
}
?>
<p>你还没有登录，登录后你才能快速投稿日志！<?php echo $login;?> | <?php echo $register;?></p>
<?php elseif( !CheckPermission("sendentry","你没有权限投稿！",'0') ):?>
<p>你没有投稿权限，你所在的用户组(<?php echo $dyhb_premission[gpname];?>)没有权限访问！<a href='<?php echo $dyhb_options[blogurl];?>?action=usergroup&id=<?php echo $dyhb_usergroup;?>'>点击查看你的权限</a></p>
<?php else:?>
<p><label for="content">内容：<a href="javascript:showdiv('hideupload');">(添加附件)</a></label>
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
<input type="submit" class="button submit"  value="发布文章" <?php if( !CheckPermission("sendentry","你没有权限投稿！",'0') || !ISLOGIN ):?>disabled<?php endif;?>>
</p>
</form>
</div>
<?php endif;?>
</div> 
<?php
include DyhbView('sidebar','');
include DyhbView('footer','');
?>