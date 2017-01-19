<?php 
//Í·²¿
if(!defined('DOYOUHAOBABY_ROOT')) {exit('hi,friend!');} 
include DyhbView('header',1);
_color_block_js();
_admin_tpl_message();
//²à±ßÀ¸
include DyhbView('sidebar',1);
?>
<div id="dyhb-content">
<div class="title">
<div id="icon-template" class="icon32"><br /></div>
<h2><?php echo $admin_content['template']['0'];?></h2>
</div>	
<?php if($view==''||$view=='admin'||$view=='user'): ?>
<div class="dashboard-element">
<h3><?php echo $admin_content['template']['1'];?></h3>
<div class="element-info">
<table>
<div class="template_now">
<div class="pic">
<img alt="<?php echo $TEMPLATE_NAME;?>" src="<?php if($view=="admin"){echo "view/$dyhb_options[admin_template]/helloworld.png";}else{echo "../view/$dyhb_options[user_template]/helloworld.png"; }?>" border="0" />
</div>
<div class="info">
<ul>
<?php _admin_tpl_description();?>
</ul>
</div>
</div>
</table>
</div>
</div>
<div class="dashboard-element">
<h3><?php echo $admin_content['template']['2'];?></h3>
<div class="element-info"><table>
<div class="tpl">
<ul>
<?php echo $templateinfo;?>
</ul>
</div></table></div></div>
<?php elseif($view=='widget'):?>
<div class="dashboard-element">
<h3><?php echo $admin_content['template']['4'];?><?php _admin_tpl_widget_select();?></h3>
<div class="element-info">
<?php _admin_widget_list();?>
</div>
</div>
<?php elseif($view=='blogcover'):?>
<?php echo _admin_tpl_option_cover();?>
<?php elseif($view=='plugin'):?>
<div class="dashboard-element">
<h3><?php echo $admin_content['template']['5'];?></h3>
<div class="element-info">
<iframe width="100%" height="600" frameborder="0" src="plugin.php"></iframe>
</div>
</div>
<?php endif;?>
</div>
</div>
</div>
<?php 
//µ×²¿
include DyhbView('footer',1);
?>