<?php 
//头部
if(!defined('DOYOUHAOBABY_ROOT')) {exit('hi,friend!');} 
include DyhbView('header',1);
?>
<div id="dyhb-content">
<div class="title">	
<div id="icon-index" class="icon32"><br /></div>
<h2><?php echo $admin_content['index']['0'];?></h2>
</div>
<?php if($view==''||$view=='index'):?>
<div class="dashboard-element">
<h3><?php if($dyhb_nikename){echo $dyhb_nikename;}else{echo $dyhb_username;}?> <?php echo $admin_content['index']['1'];?></h3>
<div class="element-info">
<form method="post" action="?action=log&do=microlog&do2=add">
<p><?php echo $admin_content['index']['2'];?><a class="buttons" href="<?php echo $dyhb_options[blog_program_url];?>" target="_blank"><?php echo $dyhb_options[prower_blog_name];?></a><?php echo $admin_content['index']['3'];?><a class="buttons" href="index.php?action=option#prower_blog_name"><?php echo $admin_content['index']['4'];?></a><?php echo $admin_content['index']['5'];?></p>
<?php if(CheckPermission("cp","服务器消息！",'0')):?>
<div class="wait_time">
<p><?php echo $admin_content['index']['6'];?>  ( <a href="?action=log&do=add"><?php echo $admin_content['index']['7'];?></a> | <a href="?action=backup&do=rebuild"><?php echo $admin_content['index']['8'];?></a> | <a href="?action=comment"><?php echo $admin_content['index']['9'];?></a> | <a href="?action=template"><?php echo $admin_content['index']['10'];?></a> | <a href="?action=option"><?php echo $admin_content['index']['11'];?></a> )<br><textarea  name="content"></textarea></p>
</div>
<?php 
 _admin_button_make($admin_content['index']['12']);
?>
</form>
<?php endif;?>
</div>
</div>
<div class="dashboard-element">
<h3><?php echo $admin_content['index']['13'];?></h3>
<div class="element-info">
<ul>
<?php
   _admin_index_blogmessage();
?>
</ul>
</div>
</div>
<?php if(CheckPermission("cp","服务器消息！",'0')):?>
<div class="dashboard-element">
<h3><?php echo $admin_content['index']['14'];?></h3>
<div class="element-info">
<ul>
<?php
    _admin_index_blogserver();
?>
</ul>
</div>
</div>
<?php endif;?>
<?php else:?>
<div class="dashboard-element">
<h3><?php echo $admin_content['index']['36'];?></h3>
<div class="element-info" id="dyhb_blog_update_information">
</div>
<script src="http://blog.56swun.com/includes/dyhb_blog_update.php"></script>
</div>
<?php endif;?>
</div>
</div>
<?php 
//底部
include DyhbView('footer',1);
?>