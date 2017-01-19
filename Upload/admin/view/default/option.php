<?php 
//头部
if(!defined('DOYOUHAOBABY_ROOT')) {exit('hi,friend!');} 
include DyhbView('header',1);
_color_block_js();
_admin_option_message();
//侧边栏
include DyhbView('sidebar',1);
?>
<div id="dyhb-content">
<div class="title">	
<div id="icon-option" class="icon32"><br /></div>
<h2><?php echo $admin_content['option']['0'];?></h2>
</div>
<div class="box">
<div class="element-info">
<!--基本设置-->
<?php if($view==''||$view=="basic"):?>
<form id="form" action="?action=option&do=save_basic" method="post">
<?php
_admin_option_basic_table();
?>
<div class="submit">
<input type="submit" value="<?php echo $admin_content['option']['1'];?>" name="ok" />
</div>
</form>
<!--日志列表设置-->
<?php elseif($view=="display"):?>
<form id="form" action="?action=option&do=save_display" method="post">
<?php
_admin_option_display_table();
?>
<div class="submit">
<input type="submit" value="<?php echo $admin_content['option']['1'];?>" name="ok" />
</div>
</form>
<!--评论设置-->
<?php elseif($view=="comment"):?>
<form action="?action=option&&do=save_comment" method="post">
<?php
_admin_option_comment_table();
?>
<div class="submit">
<input type="submit" value="<?php echo $admin_content['option']['1'];?>" name="ok" />
</div>
</form>
<!--ad设置-->
<?php elseif($view=="ad"):?>
<form action="?action=option&do=save_ad" method="post">
<?php
_admin_option_ad_table();
?>
<div class="submit">
<input type="submit" value="<?php echo $admin_content['option']['1'];?>" name="ok" />
</div>
</form>
<!--附件设置-->
<?php elseif($view=="file"):?>
<form id="form" action="?action=option&do=save_file" method="post">
<?php
_admin_option_file_table();
?>
<div class="submit">
<input type="submit" value="<?php echo $admin_content['option']['1'];?>" name="ok" />
</div>
</form>
<!--安全设置-->
<?php elseif($view=="safe"):?>
<form id="form" action="?action=option&do=save_safe" method="post">
<?php
_admin_option_safe_table();
?>
<div class="submit">
<input type="submit" value="<?php echo $admin_content['option']['1'];?>" name="ok" />
</div>
</form>
<!--固定衔接设置-->
<?php elseif($view=="link"):?>
<form id="form" action="?action=option&do=save_link" method="post">
<?php
_admin_option_link();
?>
<div class="submit">
<input type="submit" value="<?php echo $admin_content['option']['1'];?>" name="ok" />
</div>
</form>
<?php endif;?>
</div>
</div>
</div>
</div>
<?php 
//底部
include DyhbView('footer',1);
?>