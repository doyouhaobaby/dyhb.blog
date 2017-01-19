<?php 
//头部
if(!defined('DOYOUHAOBABY_ROOT')) {exit('hi,friend!');} 
include DyhbView('header',1);
//消息
_admin_log_message();
//侧边栏
include DyhbView('sidebar',1);
?>
<div id="dyhb-content">
<div class="title">	
<div id="icon-log" class="icon32"><br /></div>
<h2><?php echo $admin_content['log']['0'];?></h2>
</div>
<?php if($view=="add"||$view=="upd"):?>
<script type="text/javascript" src="editor/xheditor-zh-cn.min.js?v=1.0.0-final"></script>
<div id='automessage' style="padding:5px 30px;color:red;font-weight:bold;"></div>
<?php 
  _admin_log_addlog();
?> 
<?php elseif($view==""||$view=="ispage"||$view=="ishide"||"list"&&$view!="tag"&&$view!="trackback"&&$view!="microlog"&&$view!='myfield'):?>
<div class="dashboard-element">
<h3><?php echo $admin_content['log']['3'];?>(<?php echo $TotalLogNum;?>)</h3>
<div class="element-info">
<a class="buttons" href="javascript:showdiv('hidelogsearch');"><?php echo $admin_content['log']['148'];?></a>
<a class="buttons" href="javascript:showdiv('hide_view_sort_tag');"><?php echo $admin_content['log']['149'];?></a>
<a class="buttons" href="javascript:showdiv('hidehelpinfor');">  <?php echo $admin_common['13'];?> </a>
<?php
//日志查找
_admin_log_subsub();
?>
<ul id="hidehelpinfor" style="display:none;">
<li><?php echo $admin_content['log']['4'];?><a class="buttons" href="?action=option&do=display"><?php echo $admin_content['log']['5'];?></a><?php echo $admin_content['log']['6'];?></li>
<li><?php echo $admin_content['log']['7'];?></li>
<li><?php echo $admin_content['log']['8'];?></li>
<li><?php echo $admin_content['log']['9'];?></li>
</ul>
</div>
</div>
<div class="dashboard-element">
<h3><?php echo $admin_content['log']['10'];?></h3>
<form name="logs" method="post" action="?action=log&do=prepare" name="thelog" id="thelog">
<?php _admin_log_loglist_table(); ?>	
<div class="multipage">
<?php echo $pagination;?>
</div>
<div class="buttonsDiv">
<?php _admin_log_loglist_action(); ?>
</div>
</form>
<script> 
$(document).ready(function(){
$("#thelog tbody tr:odd").addClass("odd");
$("#thelog tbody tr")
.mouseover(function(){$(this).addClass("trover display")})
.mouseout(function(){$(this).removeClass("trover display")});
})
</script>
<?php elseif($view=="myfield"):?>
<div class="dashboard-element">
<h3><?php echo $admin_content['log']['3'];?>(<?php echo count($Yourfield);?>)</h3>
<div class="element-info">
<a class="buttons" href="javascript:showdiv('hidehelpinfor');"><?php echo $admin_common['13'];?></a>
<ul id="hidehelpinfor" style="display:none;">
<li><?php echo $admin_content['log']['11'];?></li>
<li><?php echo $admin_content['log']['12'];?></li>
<li><?php echo $admin_content['log']['13'];?></li>
<li><?php echo $admin_content['log']['14'];?></li>
</ul>
</div>
</div>
<div class="box-left">
<div class="dashboard-element"><div id="thefield">
<h3><?php echo $admin_content['log']['15'];?></h3>
<?php
  _admin_log_filedlist_table();
?>
<script> 
$(document).ready(function(){
$("#thefield tbody tr:odd").addClass("odd");
$("#thefield tbody tr")
.mouseover(function(){$(this).addClass("trover display")})
.mouseout(function(){$(this).removeClass("trover display")});
});
</script>
</div>
</div>
</div>
<div class="box-right">
<div class="dashboard-element">
<form action="?action=log&do=myfield&do2=save" method="post">
<h3><?php echo $admin_content['log']['16'];?></h3>
<div class="element-info">
<p><label for="name"><?php echo $admin_content['log']['17'];?></label>
<input class="formfield" type="text" name="fieldname"  value="<?php echo $fieldname;?>" />
</p><p>
<input class="formfield shortinput" type="hidden" name="old_fieldname"  value="<?php echo $fieldname;?>" /></p>
<div class="submit">
<input type="submit" value="<?php echo $admin_content['log']['28'];?>" />
</div>
</form>
</div>
</div>
<?php elseif($view=="tag"||($view=="tag"&&$TagId&&$view2=="upd")):?>
<div class="dashboard-element">
<h3><?php echo $admin_content['log']['3'];?>(<?php echo $TotalTagNum;?>)</h3>
<div class="element-info">
<a class="buttons" href="javascript:showdiv('hidehelpinfor');"><?php echo $admin_common['13'];?></a>
<ul id="hidehelpinfor" style="display:none;">
<li><?php echo $admin_content['log']['18'];?></li>
<li><?php echo $admin_content['log']['19'];?></li>
</ul>
</div>
</div>
<div class="box-left">
<div class="dashboard-element">
<h3><?php echo $admin_content['log']['20'];?></h3>
<form action="?action=log&do=tag&do2=del" method="post" id="thetag">
<?php
   _admin_log_taglist_table();
?>
<div class="multipage">
    <?php echo $pagination;?>
</div>
<div class="buttonsDiv">
<input class="buttons" type="submit" value="<?php echo $admin_content['log']['21'];?>" />
</form>
<script> 
$(document).ready(function(){
$("#thetag tbody tr:odd").addClass("odd");
$("#thetag tbody tr")
.mouseover(function(){$(this).addClass("trover display")})
.mouseout(function(){$(this).removeClass("trover display")});
});
</script>
</div>
</div>
</div>
<div class="box-right">
<div class="dashboard-element">
<form action="?action=log&do=tag&do2=save" method="post">
<h3><?php echo $admin_content['log']['22'];?></h3>
<div class="element-info">
<p>
<label for="name"><?php echo $admin_content['log']['23'];?></label>
<input class="formfield shortinput" type="text" name="name" value="<?php echo $UpdTag[name];?>" />
</p>
<p>
<label for="urlname"><?php echo $admin_content['log']['24'];?></label>
<input class="formfield shortinput" type="text" name="urlname" value="<?php echo $UpdTag[urlname];?>" /><br />
<span><?php echo $admin_content['log']['25'];?></span>
</p>
<p>
<label for="keyword mediuminput"><?php echo $admin_content['log']['26'];?></label>
<input class="formfield" type="text" name="keyword" value="<?php echo $UpdTag[keyword];?>" />
</p>
<p>
<label for="description"><?php echo $admin_content['log']['27'];?></label>
<input class="formfield mediuminput" type="text" name="description" value="<?php echo $UpdTag[description];?>" />
</p>
<input type="hidden" name="tag_id" value="<?php echo $UpdTag[tag_id];?>" />
<div class="submit">
 <input type="submit" value="<?php echo $admin_content['log']['28'];?>" />
 </div>
</div>
</form>
</div>
</div>
<?elseif($view=='trackback'):?>
<div class="dashboard-element">
<h3><?php echo $admin_content['log']['3'];?>(<?php echo $TotalTraNum;?>)</h3>		
<div class="element-info">
<a class="buttons" href="javascript:showdiv('hidehelpinfor');"><?php echo $admin_common['13'];?></a>
<ul id="hidehelpinfor" style="display:none;">
<li><?php echo $admin_content['log']['29'];?><a class="buttons" href="?action=option&do=display"><?php echo $admin_content['log']['30'];?></a></li>
<li><?php echo $admin_content['log']['31'];?></li>
<li><?php echo $admin_content['log']['32'];?></li>
</ul>
</div>
</div>
<div class="dashboard-element">
<h3><?php echo $admin_content['log']['33'];?></h3>
<form  method="post" action="?action=log&do=trackback&do2=prepare" name="thetrackback" id="thetrackback">
<?php
	_admin_log_trackbacklist_table();
?>
<div class="multipage">
<?php echo $pagination;?>
</div>
<div class="buttonsDiv">
<a class="buttons" href="javascript:dyhb_trackbackaction('del');"><?php echo $admin_content['log']['34'];?></a>
</div>
</form>
<script> 
$(document).ready(function(){
$("#thetrackback tbody tr:odd").addClass("odd");
$("#thetrackback tbody tr")
.mouseover(function(){$(this).addClass("trover display")})
.mouseout(function(){$(this).removeClass("trover display")});
});
</script>
<?elseif($view=='microlog'):?>
<div class="dashboard-element">
<h3><?php echo $admin_content['log']['3'];?>(<?php echo $TotalMicrologNum;?>)</h3>
<div class="element-info">
<a class="buttons" href="javascript:showdiv('hidehelpinfor');"><?php echo $admin_common['13'];?></a>
<ul id="hidehelpinfor" style="display:none;">
<li><?php echo $admin_content['log']['35'];?></li>
<li><?php echo $admin_content['log']['36'];?></li>
<li><?php echo $admin_content['log']['37'];?></li>
</ul>
</div>
</div>		
<div class="dashboard-element">
<h3><?php echo $admin_content['log']['38'];?></h3>
<form  method="post" action="?action=log&do=microlog&do2=del" id="themicrolog">
<?php
 _admin_log_microloglist_table();
?>
<div class="multipage">
<?php echo $pagination;?>
</div>
<div class="buttonsDiv">
<input class="buttons" type="submit" value="<?php echo $admin_content['log']['39'];?>" />
</div>
</form>
</div>
<script> 
$(document).ready(function(){
$("#themicrolog tbody tr:odd").addClass("odd");
$("#themicrolog tbody tr")
.mouseover(function(){$(this).addClass("trover display")})
.mouseout(function(){$(this).removeClass("trover display")});
});
</script>
<?endif;?>
</div>
</div>
</div>
<?php 
//底部
include DyhbView('footer',1);
?>