<?php 
//Í·²¿
if(!defined('DOYOUHAOBABY_ROOT')) {exit('hi,friend!');} 
include DyhbView('header',1);
?>
<div id="dyhb-content">
<div id="icon-link" class="icon32"><br /></div>
<div class="title">		
<h2><?php echo $admin_content['link']['0'];?></h2>
</div>
<?php
_admin_link_message();
?>
<?php if($view==''||$view=='list'):?>
<div class="dashboard-element">
<h3><?php echo $admin_content['link']['1'];?>(<?php echo count($side_Links);;?>)</h3>
<div class="element-info">
<a class="buttons" href="javascript:showdiv('hidehelpinfor');"><?php echo $admin_common['13'];?></a>
<ul id="hidehelpinfor" style="display:none;">
<li><?php echo $admin_content['link']['2'];?></li>
<li><?php echo $admin_content['link']['4'];?></li>
</ul>
</div>	
</div>
<div class="dashboard-element">
<h3><?php echo $admin_content['link']['5'];?></h3>
<div id="thelink">
<?php
_admin_link_table();
?>
<script> 
$(document).ready(function(){
$("#thelink tbody tr:odd").addClass("odd");
$("#thelink tbody tr")
.mouseover(function(){$(this).addClass("trover display")})
.mouseout(function(){$(this).removeClass("trover display")});
});
</script>
</div>
</div>
<?elseif($view=='add'||$view=='upd'):?>
<div class="dashboard-element">
<form action="?action=link&do=save" method="post">
<h3><?php echo $admin_content['link']['6'];?></h3>
<div class="element-info">
<input name="link_id"  type="hidden"  value="<?php echo $LinkId;?>">
<p>
<label for="compositor"><?php echo $admin_content['link']['7'];?></label>
<input class="formfield shortinput" type="text" name="compositor"  value="<?php if($LinkId){echo '0';}else{echo "0";}?>" />
</p>
<p>
<label for="name"><?php echo $admin_content['link']['8'];?></label>
<input class="formfield shortinput" type="text" name="name"  value="<?php echo $UpdLink[name];?>"/>
</p>
<p>
<label for="url"><?php echo $admin_content['link']['9'];?></label>
<input class="formfield mediuminput" type="text" name="url" value="<?php echo $UpdLink[url];?>"/>
</p>
<p>
<label for="logo"><?php echo $admin_content['link']['10'];?></label>
<input class="formfield mediuminput" type="text" name="logo"  value="<?php echo $UpdLink[logo];?>"/>
</p>
<p>
<label for="description"><?php echo $admin_content['link']['11'];?></label>
<input class="formfield mediuminput" type="text" name="description"  value="<?php echo $UpdLink[description];?>"/>
</p>
<p>
<label for="display"><?php echo $admin_content['link']['12'];?> </label><?php echo $admin_common['0'];?>
<input name="isdisplay" type="radio"  value="1" <?php if($UpdLink['isdisplay']==1){echo "checked=\"checked\"" ;}?>/><?php echo $admin_common['1'];?>
<input name="isdisplay" type="radio"  value="0" <?php if($UpdLink['isdisplay']==0){echo "checked=\"checked\"" ;} ?>/>
</p>
<div class="submit">
<input type="submit" value="<?php echo $admin_content['link']['13'];?>" name="ok"/>
</div>
</form>
</div>
</div>
<?php endif;?>
</div>
</div>
</div>
</div>
<?php 
//µ×²¿
include DyhbView('footer',1);
?>