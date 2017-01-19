<?php 
//Í·²¿
if(!defined('DOYOUHAOBABY_ROOT')) {exit('hi,friend!');} 
include DyhbView('header',1);
 _admin_photo_message();
//²à±ßÀ¸
include DyhbView('sidebar',1);
?>
<div id="dyhb-content">
<div class="title">	
<div id="icon-file" class="icon32"><br /></div>
<h2><?php echo $admin_content['photo']['0'];?></h2>
</div>
<div class="box">
<?php if($view=='list'||$view==''):?>
<div class="dashboard-element">
<h3><?php echo $admin_content['photo']['1'];?></h3>
<div class="element-info">
<iframe width="100%" height="600" frameborder="0" src="upload.php"></iframe>
</div>
</div>
<?php elseif($view=='mp3'&&$view2==''):?>
<div class="dashboard-element">
<h3><?php echo $admin_content['photo']['25'];?>(<?php echo $TotalMp3Num;?>)</h3>
<div class="element-info">
<a class="buttons" href="javascript:showdiv('hidehelpinfor');"><?php echo $admin_common['13'];?></a>
<?php
_admin_photo_viewbysort();
?>
<ul id="hidehelpinfor" style="display:none;">
<li><?php echo $admin_content['photo']['2'];?></li>
<li><?php echo $admin_content['photo']['4'];?></li>
<li><?php echo $admin_content['photo']['5'];?></li>
<li><a class="buttons" href="javascript:showdiv('hidesetplayer');"><?php echo $admin_content['photo']['49'];?></a></li>
</ul>
<div id="hidesetplayer" style="display:none;">
<?php _admin_tpl_setplayer();?>
</div>
</div>
</div>
<div class="dashboard-element">
<h3><?php echo $admin_content['photo']['6'];?></h3>
<form name="mp3s" method="post" action="?action=photo&do=mp3&do2=prepare" name="themp3" id="themp3">
<?php
_admin_photo_mp3list_table();
?>
<div class="multipage">
<?php echo $pagination;?>
</div>
<div class="buttonsDiv">
<?php
_admin_photo_mp3_action();
?>
</div>
</form>
</div>
<script> 
$(document).ready(function(){
$("#themp3 tbody tr:odd").addClass("odd");
$("#themp3 tbody tr")
.mouseover(function(){$(this).addClass("trover display")})
.mouseout(function(){$(this).removeClass("trover display")});
});
</script>
<a class="buttons" href="javascript:showdiv('hidemp3');"><?php echo $admin_content['photo']['7'];?></a><br><br>	
<div id="hidemp3" style="display:none;">
<div class="dashboard-element">
<h3><?php echo $admin_content['photo']['8'];?></h3>
<div class="element-info">
<p>
<span><?php echo $admin_content['photo']['9'];?></span>
</p>
<form id="form" action="?action=photo&do=mp3&do2=add" method="post" >
<p>
<textarea name="mp3" class="formarea">
</textarea></p><p>
<?php
_admin_photo_adminmp3_sort();
?></p><p> 
<div class="submit">
<input  type="submit" value="<?php echo $admin_content['photo']['10'];?>" />
</div>
</p>
</form>
</div>
</div>
</div>
<?php elseif($view=='mp3'&&$view2=='upd'):?>
<div class="dashboard-element">
<h3><?php echo $admin_content['photo']['11'];?></h3>
<script type="text/javascript" src="editor/xheditor-zh-cn.min.js"></script> 
<div class="element-info">
<form action="" method="post">
<p>
<label for="name"><?php echo $admin_content['photo']['12'];?></label>
<input class="formfield mediuminput" name="name"  type="text" value="<?php echo $UpdMp3[name];?>" />
</p><p>
<label for="path"><?php echo $admin_content['photo']['13'];?></label>
<input class="formfield longinput" name="path"  type="text"  value="<?php echo $UpdMp3[path];?>" />
</p><p>
<label for="musicword"><?php echo $admin_content['photo']['14'];?></label>
<textarea  name="musicword" id="musicword" style="width:100%;height:300px"><?php echo $UpdMp3[musicword];?></textarea>
<script type="text/javascript"> 
$('#musicword').xheditor({
tools:'Source,|,Blocktag,Fontface,FontSize,|,Bold,Italic,Underline,Strikethrough,FontColor,BackColor,|,Align,List,Outdent,Indent,Removeformat,|,Link,Unlink,Img',
skin:'default',
emots:{msn:{name:'MSN',count:40,width:22,height:22,line:8}}
});
</script>
</p><p>
<div class="submit">
<input type="submit" name="ok"  value="<?php echo $admin_content['photo']['15'];?>" />
</div>
</p>
</form>
</div>
</div>
<a href="?action=photo&do=mp3"><?php echo $admin_content['photo']['16'];?></a>
<?php elseif($view=='mp3sort'):?>
<div class="dashboard-element">
<h3><?php echo $admin_content['photo']['25'];?>(<?php echo count($Mp3Sort);?>)</h3>
<div class="element-info">
<a class="buttons" href="javascript:showdiv('hidehelpinfor');"><?php echo $admin_common['13'];?></a>
<ul id="hidehelpinfor" style="display:none;">
<li><?php echo $admin_content['photo']['17'];?></li>
<li><?php echo $admin_content['photo']['18'];?></li>
<li><a class="buttons" href="javascript:showdiv('hidesideplayer');"><?php echo $admin_content['photo']['50'];?></a></li>
</ul>
<div id="hidesideplayer" style="display:none;">
<?php _admin_tpl_mp3player();?>
</div>
</div>	
</div>
<div class="box-left">
<div class="dashboard-element">
<div id="themp3sort">
<h3><?php echo $admin_content['photo']['19'];?></h3>
<?php
_admin_photo_mp3sort_table();
?>
<script> 
$(document).ready(function(){
$("#themp3sort tbody tr:odd").addClass("odd");
$("#themp3sort tbody tr")
.mouseover(function(){$(this).addClass("trover display")})
.mouseout(function(){$(this).removeClass("trover display")});
});
</script>
</div>
</div>
</div>
<div class="box-right">
<div class="dashboard-element">
<h3><?php echo $admin_content['photo']['20'];?></h3>
<form action="?action=photo&do=mp3sort&do2=save" method="post">
<div class="element-info">
<input name="mp3sort_id"  type="hidden" value="<?php echo $Mp3SortId;?>" />
<p>
<label><?php echo $admin_content['photo']['21'];?></label> 
<input class="formfield shortinput" name="compositor"  type="text" value="<?php if($View2=='upd'){echo $UpdMp3Sorts[compositor];}else{echo "0";}?>" />
</p>
<p>
<label><?php echo $admin_content['photo']['22'];?></label><?php echo $admin_common['0'];?>
<input class="formfield" name="isdisplay" type="radio"  value="1" <?php if($UpdMp3Sorts[isdisplay]=='1'||$View2==''){echo "checked=\"checked\"";}?>/><?php echo $admin_common['1'];?>
<input class="formfield" name="isdisplay" type="radio"  value="0" <?php if($UpdMp3Sorts[isdisplay]=='0'){echo "checked=\"checked\"";}?>/>
</p>
<p>
<label for="name"><?php echo $admin_content['photo']['23'];?></label>
<input class="formfield mediuminput" name="name"  type="text" value="<?php echo $UpdMp3Sorts[name];?>" />
</p>
<div class="submit">
<input type="submit" value="<?php echo $admin_content['photo']['24'];?>" name="ok"/>
</div>
</form>
</div>
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