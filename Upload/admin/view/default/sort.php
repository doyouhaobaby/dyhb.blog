<?php 
//Í·²¿
if(!defined('DOYOUHAOBABY_ROOT')) {exit('hi,friend!');} 
include DyhbView('header',1);
_admin_sort_message();
include DyhbView('sidebar',1);
?>
<div id="dyhb-content">
<div class="title">
<div id="icon-sort" class="icon32"><br /></div>
<h2><?php echo $admin_content['sort']['0'];?></h2>
</div>
<div class="dashboard-element">
<h3><?php echo $admin_content['sort']['21'];?>(<?php echo count($_sideSorts);?>)</h3>
<div class="element-info">
<a class="buttons" href="javascript:showdiv('hidehelpinfor');"><?php echo $admin_common['13'];?></a>
<ul id="hidehelpinfor" style="display:none;">
<li><?php echo $admin_content['sort']['1'];?></li>
<li><?php echo $admin_content['sort']['2'];?></li>
<li><?php echo $admin_content['sort']['4'];?></li>
<li><?php echo $admin_content['sort']['5'];?>.</li>
<li><?php echo $admin_content['sort']['6'];?></li>
</ul>
</div>
</div>
<div class="box-left">
<div class="dashboard-element">
<h3><?php echo $admin_content['sort']['7'];?></h3>
<div id="thesort">
<?php
  _admin_sortlist_table();
?>
<script> 
$(document).ready(function(){
$("#thesort tbody tr:odd").addClass("odd");
$("#thesort tbody tr")
.mouseover(function(){$(this).addClass("trover display")})
.mouseout(function(){$(this).removeClass("trover display")});
});
</script>
</div>
</div>
</div>
<div class="box-right">
<div class="dashboard-element">
<h3><?php echo $admin_content['sort']['8'];?></h3>
<div class="element-info">
<form action="?action=sort&do=save" method="post">
<input name="sort_id"  type="hidden" value="<?php echo $UpdSorts[sort_id];?>" />
<p>
<label for="compositor"><?php echo $admin_content['sort']['9'];?></label>
<input class="formfield shortinput" type="text" name="compositor"  value="<?php if($view=='upd'){echo $UpdSorts[compositor];}else{echo "0";};?>" />
</p>
<p>
<label for="name"><?php echo $admin_content['sort']['10'];?></label>
<input class="formfield shortinput" type="text" name="name"  value="<?php echo $UpdSorts[name];?>" />
</p>
<p>
<label for="parentsort_id"><?php echo $admin_content['sort']['11'];?></label>
<?php
  _admin_sort_select();
?>
</p>
<p>
<p>
<label for="cmsstart"><?php echo $admin_content['sort']['12'];?></label>
<input class="formfield shortinput"  type="text" name="cmsstart"  value="<?php if($view=='upd'){echo $UpdSorts[cmsstart];}else{echo "5";};?>" />
</p>
<p>
<label for="cmsend"><?php echo $admin_content['sort']['13'];?></label>
<input class="formfield shortinput" type="text" name="cmsend"  value="<?php if($view=='upd'){echo $UpdSorts[cmsend];}else{echo "5";};?>" />
</p>
<p>
<label for="style"><?php echo $admin_content['sort']['14'];?></label>
<input class="formfield shortinput" type="text" name="style"  value="<?php if($view=='upd'){echo $UpdSorts[style];}else{echo "1";};?>" />
</p>
<p>
<label for="urlname"><?php echo $admin_content['sort']['15'];?></label>
<input class="formfield mediuminput" type="text" name="urlname"  value="<?php echo $UpdSorts[urlname];?>" />
</p>
<p>
<label for="logo"><?php echo $admin_content['sort']['16'];?></label>
<input class="formfield mediuminput"  type="text" name="logo"  value="<?php echo $UpdSorts[logo];?>" />
</p>
<p>
<label for="introduce"><?php echo $admin_content['sort']['17'];?></label>
<input class="formfield longinput" type="text" name="introduce"  value="<?php echo $UpdSorts[introduce];?>" />
</p>
<p>
<label for="keyword"><?php echo $admin_content['sort']['18'];?></label>
<input class="formfield longinput" type="text" name="keyword"  value="<?php echo $UpdSorts[keyword];?>" />
</p>
<p>
<label for="description"><?php echo $admin_content['sort']['19'];?></label>
<input class="formfield longinput" type="text" name="description"  value="<?php echo $UpdSorts[description];?>" />
</p>
<div class="submit">
<input  type="submit" value="<?php echo $admin_content['sort']['20'];?>" name="ok"/>
</div>
</form>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<?php 
//µ×²¿
include DyhbView('footer',1);
?>