<?php 
//Í·²¿
if(!defined('DOYOUHAOBABY_ROOT')) {exit('hi,friend!');} 
include DyhbView('header',1);
_admin_user_message();
//²à±ßÀ¸
include DyhbView('sidebar',1);
?>
<div id="dyhb-content">
<div class="title">	
<div id="icon-user" class="icon32"><br /></div>
<h2><?php echo $admin_content['user']['0'];?></h2>
</div>
<?php if($view==''||$view=='list'):?>
<div class="dashboard-element">
<h3><?php echo $admin_content['user']['1'];?>(<?echo $TotalUserNum;?>)</h3>
<div class="element-info">
<a class="buttons" href="javascript:showdiv('hidehelpinfor');"><?php echo $admin_common['13'];?></a>
<ul id="hidehelpinfor" style="display:none;">
<li><?php echo $admin_content['user']['2'];?></li>
<li><?php echo $admin_content['user']['4'];?></li>
</ul>
</div>	
</div>
<div class="dashboard-element">
<h3><?php echo $admin_content['user']['5'];?></h3>
<div id="theuser">
<?php
 _admin_user_userlist_table();
?>	
<div class="multipage">
<?php echo $pagination;?>
</div>
<script> 
$(document).ready(function(){
$("#theuser tbody tr:odd").addClass("odd");
$("#theuser tbody tr")
.mouseover(function(){$(this).addClass("trover display")})
.mouseout(function(){$(this).removeClass("trover display")});
});
</script>
</div>
</div>
<?php elseif($view=='add'||$view=='upd'):?>	
<form action="?action=user&do=save_coolgirl" method="post" enctype="multipart/form-data">
<div class="dashboard-element">
<h3><?php echo $admin_content['user']['6'];?></h3>
<div class="element-info">
<?php _admin_user_userupdate();?>
</div>
</div>
<div class="submit">
<input type="submit" value="<?php echo $admin_content['user']['11'];?>" />
</div>
</form>
<?php elseif($view=='usergroup'&&$view2==''):?>
<div class="dashboard-element">
<h3><?php echo $admin_content['user']['6'];?>(<?php echo count($_BlogUsergroup);?>)</h3>
<div class="element-info">
<a class="buttons" href="javascript:showdiv('hidehelpinfor');"><?php echo $admin_common['13'];?></a>
<ul id="hidehelpinfor" style="display:none;">
<li><?php echo $admin_content['user']['7'];?></li>
<li><?php echo $admin_content['user']['8'];?></li>
</ul>
</div>
</div>		
<div class="dashboard-element">
<h3><?php echo $admin_content['user']['9'];?></h3>
<div id="theusergroup">
<?php
_admin_user_usergroup_table();
?>
<script> 
$(document).ready(function(){
$("#theusergroup tbody tr:odd").addClass("odd");
$("#theusergroup tbody tr")
.mouseover(function(){$(this).addClass("trover display")})
.mouseout(function(){$(this).removeClass("trover display")});
});
</script>
</div>
</div>
<?php elseif($view=='usergroup'&&$view2=='upd'&&$UserGroupId):?>
<form id="form" action="?action=user&do=usergroup&do2=save_pre&id=<?php echo $UserGroupId;?>" method="post">
<?php
  _admin_user_usergroup_option();
?>
<script> 
$(document).ready(function(){
$(".form-table tbody tr:odd").addClass("odd");
$(".form-table tbody tr")
.mouseover(function(){$(this).addClass("trover display")})
.mouseout(function(){$(this).removeClass("trover display")});
});
</script>
<div class="submit">
<input type="submit" value="<?php echo $admin_content['user']['10'];?>" name="ok" />
</form>
<?php endif;?>
</div>
</div>
</div>
</div>
<?php 
//µ×²¿
include DyhbView('footer',1);
?>