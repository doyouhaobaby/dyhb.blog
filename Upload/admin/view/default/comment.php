<?php 
//Í·²¿
if(!defined('DOYOUHAOBABY_ROOT')) {exit('hi,friend!');} 
include DyhbView('header',1);
_admin_comment_message();
//²à±ßÀ¸
include DyhbView('sidebar',1);
?>
<div id="dyhb-content">
<div class="title">	
<div id="icon-comment" class="icon32"><br /></div>
<h2><?php echo $admin_content['comment']['0'];?></h2>
</div>
<?php if($view!='reply'):?>
<div class="dashboard-element">
<h3><?php echo $admin_content['comment']['36'];?>(<?php echo $CommentTotal;?>)</h3>
<div class="element-info">
<p style="float:right;">
<?php 
_admin_comment_commentviewby();
?>
</p>
<a class="buttons" href="javascript:showdiv('hidehelpinfor');"><?php echo $admin_common['13'];?></a>
<ul id="hidehelpinfor" style="display:none;">
<li><?php echo $admin_content['comment']['1'];?></li>
<li><?php echo $admin_content['comment']['2'];?></li>
</ul>
</div>
</div>		
<div class="dashboard-element">
<h3><?php echo $admin_content['comment']['4'];?></h3>
<form action="?action=comment&do=prepare" name="thecomment" id="thecomment" method="post">
<?php 
_admin_commentlist_table();
?>
<div class="multipage">
<?php echo $pagination;?>
</div>
<div class="buttonsDiv">
<?php 
_admin_comment_commentlist_action();
?>
</div>
</form>
</div>
<script> 
$(document).ready(function(){
$("#thecomment tbody tr:odd").addClass("odd");
$("#thecomment tbody tr")
.mouseover(function(){$(this).addClass("trover display")})
.mouseout(function(){$(this).removeClass("trover display")});
});
</script>
<?php else:?>
<div class="dashboard-element">
<h3><?php echo $admin_content['comment']['5'];?></h3>
<div class="element-info">
<form action="?action=comment&do=reply&id=<?php echo $UpdComment[comment_id];?>" method="post">
<p><?php echo $admin_content['comment']['6'];?> <a href="<?php if ($UpdComment[blog_id]=='0'){echo "../?action=guestbook";}else{echo "../?p=$UpdComment[blog_id]";}?>" target="_blank"><?php echo $admin_content['comment']['7'];?></a></p>
<p>
<label for="author"><?php echo $admin_content['comment']['8'];?></label>
<input class="formfield shortinput" type="text" name="name"  value="<?php echo $UpdComment[name];?>" />
</p>
<p>
<label for="url"><?php echo $admin_content['comment']['9'];?></label>
<input class="formfield mediuminput" type="text" name="url"  value="<?php echo $UpdComment[url];?>" />
</p>
<p>
<label for="email"><?php echo $admin_content['comment']['10'];?></label>
<input class="formfield mediuminput" type="text" name="email"  value="<?php echo $UpdComment[email];?>" />
</p>
<p>
<label for="comment"><?php echo $admin_content['comment']['11'];?></label>
<textarea class="formarea" type="text" name="comment" ><?php echo $UpdComment[comment];?></textarea>
</p>
<div class="submit">
<input type="submit" value="<?php echo $admin_content['comment']['12'];?>" name="ok"/>
</div>
</form>
</div>
<?endif;?>
</div>
</div>
</div>
</div>
<?php 
//µ×²¿
include DyhbView('footer',1);
?>