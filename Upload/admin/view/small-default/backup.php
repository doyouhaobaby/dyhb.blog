<?php 
//头部
if(!defined('DOYOUHAOBABY_ROOT')) {exit('hi,friend!');} 
include DyhbView('header',1);
?>
<div id="dyhb-content">
<div class="title">
<div id="icon-backup" class="icon32"><br /></div>
<h2><?php echo $admin_content['backup']['0'];?></h2>
</div>
<?php
_admin_backup_message();
?>
<?php if($view==''||$view=='list'):?>
<div class="dashboard-element">
<h3><?php echo $admin_content['backup']['1'];?>(<?php echo count($BackupFile);?>)</h3>
<div class="element-info">
<a class="buttons" href="javascript:showdiv('hidehelpinfor');"><?php echo $admin_common['13'];?></a>
<ul id="hidehelpinfor" style="display:none;">
<li><?php echo $admin_content['backup']['2'];?></li>
<li><?php echo $admin_content['backup']['4'];?></li>
</ul>
</div>
</div>		
<div class="dashboard-element">
<h3><?php echo $admin_content['backup']['5'];?></h3>
<div id="thebackupfile">
<?php
_admin_backup_backupfilelist_table();
?>
<div class="buttonsDiv">
<a class="buttons" href="index.php?action=backup&do=backup_add"><?php echo $admin_content['backup']['6'];?></a>
<a class="buttons" href="javascript:dyhb_act('','backupfile');"><?php echo $admin_content['backup']['7'];?></a>
</div>
</div>
</div>
<script> 
$(document).ready(function(){
$("#thebackupfile tbody tr:odd").addClass("odd");
	$("#thebackupfile tbody tr")
.mouseover(function(){$(this).addClass("trover display")})
.mouseout(function(){$(this).removeClass("trover display")});
});
</script>
<?php elseif($view=='rebuild'):?>
<div class="dashboard-element">
<h3><?php echo $admin_content['backup']['1'];?>(<?php echo count($TheCacheFiles);?>)</h3>
<div class="element-info">
<a class="buttons" href="javascript:showdiv('hidehelpinfor');"><?php echo $admin_common['13'];?></a>
<ul id="hidehelpinfor" style="display:none;">
<li><?php echo $admin_content['backup']['8'];?></li>
<li><?php echo $admin_content['backup']['9'];?></li>
</ul>
</div>
</div>		
<div class="dashboard-element">
<h3><?php echo $admin_content['backup']['10'];?></h3>
<form name= "thecache" id="thecache" method="post" action="?action=backup&do=cache">
<?php
_admin_backup_cachefilerebulid_table();
?>
<div class="buttonsDiv">
<input type="submit"  value="<?php echo $admin_content['backup']['18'];?>" onclick="javascript:dyhb_cacheaction('all')"/>
</div>
</form>
</div>
<script> 
$(document).ready(function(){
$("#thecache tbody tr:odd").addClass("odd");
$("#thecache tbody tr")
.mouseover(function(){$(this).addClass("trover display")})
.mouseout(function(){$(this).removeClass("trover display")});
});
</script>
</div>
<!-----------------备份消息---------------->
<?php elseif($view=='backup_add'):?>
<div class="dashboard-element">
<h3><?php echo $admin_content['backup']['11'];?></h3>
<div class="element-info">
<a class="buttons" href="javascript:showdiv('hidehelpinfor');"><?php echo $admin_common['13'];?></a>
<ul id="hidehelpinfor" style="display:none;">
<li><?php echo $admin_content['backup']['12'];?></li>
</ul>
</div>
</div>		
<div class="dashboard-element">
<h3><?php echo $admin_content['backup']['13'];?></h3>
<?php
_admin_backup_backupmessage();
?>
<div class="buttonsDiv">
<a class="buttons" href="index.php?action=backup"><?php echo $admin_content['backup']['19'];?></a>
</div>
</div>
<?php elseif($view=='cachefile'):?>
<div class="dashboard-element">
<h3><?php echo $admin_content['backup']['1'];?>(<?php echo count($CacheFile);?>)</h3>
<div class="element-info">
<a class="buttons" href="javascript:showdiv('hidehelpinfor');"><?php echo $admin_common['13'];?></a>
<ul id="hidehelpinfor" style="display:none;">
<li><?php echo $admin_content['backup']['14'];?></li>
</ul>
</div>
</div>
<div class="dashboard-element">
<h3><?php echo $admin_content['backup']['15'];?></h3>	
<div id="thecachefilelist">
<?php
 _admin_backup_cachefilelist_table();
?>
<div class="buttonsDiv">
<a class="buttons" href="javascript:dyhb_act('','clearallcache');"><?php echo $admin_content['backup']['16'];?></a>
</div>
</div>
</div>
<script> 
$(document).ready(function(){
$("#thecachefilelist tbody tr:odd").addClass("odd");
$("#thecachefilelist tbody tr")
.mouseover(function(){$(this).addClass("trover display")})
.mouseout(function(){$(this).removeClass("trover display")});
});
</script>
<!---------------静态化---------------->
<?php elseif($view=='html'):?>
<form id="makehtml" method="post"  action="?action=backup&do=html">
<?php
_admin_backup_makehtml_options();
?>
<p>
<div class="buttonsDiv">
<a class="buttons" href="javascript:dyhb_makehtml('htmlfileclear');"><?php echo $admin_content['backup']['17'];?></a>
</div>
</p>
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