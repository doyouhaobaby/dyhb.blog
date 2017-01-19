<?php if(!defined('DOYOUHAOBABY_ROOT')){exit('hi,friend！');}
global_comment_options();
?>
<div class='box'>
<a name="comment"></a>
<h3>评论(<?php echo $Global_comment_num;?>)</h3>
<ol class="comment-list" id="ajax_parent_back">
<?php _list_showlog_comment($ShowComment);?>
</ol><br>	                      
</div>
<?php if($pagination):?><p id='pagenav'><?php echo $pagination;?></p><?php endif;?>
<?php if($ShowLog['islock']=='0'||$View=='guestbook'||($View=='photo'&&$photo_id)||($View=='microlog'&&$taotao_id)||($View=='mp3'&&$mp3_id)):?>
<div id="respond-post-<?php echo $Comment_to_id?>" class="respond">
<div class="cancel-comment-reply">
<a id="cancel-comment-reply-link" href="" rel="nofollow" style="display:none" onclick="return DYHB_Comment.cancelReply();">取消回复</a>
</div>
<div class='box'>		   
<h3>留言板</h3>
<form action="javascript:alert('success');" method="post" id="ajax_commentform">			
<p>
<?php if(ISLOGIN):?>
<input type="hidden" class="textfield" name="name" id="ajax_name" value="<?php echo $_USERINFOR['dyhb_username'];?>" tabindex="1" />
<input type="hidden" class="textfield" name="email" id="ajax_email" value="<?php echo $_USERINFOR['dyhb_email'];?>" tabindex="2" />
<input type="hidden" class="textfield" name="url" id="ajax_url" value="<?php echo $_USERINFOR['dyhb_homepage'];?>" tabindex="3" />
用户<a href="?c=<?php echo $_USERINFOR['dyhb_userid'];?>"><?php echo $_USERINFOR['dyhb_username'];?></a>,<a href="?login_out=true">登出</a>
<?php else:?>
<label>名字(*)</label>
<input name="name" id="ajax_name" type="text" size="40" value="<?php echo $_Comment_name;?>"/>
<label>Email</label>
<input name="email" id="ajax_email" type="text" size="40" value="<?php echo $_Comment_email;?>" />
<label>主页</label>
<input name="url" id="ajax_url" type="text" size="40" value="<?php echo $_Comment_url;?>" />
<?php endif;?>
<label>留言内容(*)</label>
<?php _ubb_comment();?>
<textarea name="comment" id="ajax_comment" rows="5" cols="5"></textarea>
<?php _smiley_comment();?>
<label><input name="hiddenmessage" type="checkbox" value="yes" tabindex="90" />发送私语</label>
<label><input name="isreplymail" type="checkbox" value="1" tabindex="90" />回复时邮件通知</label>
<br/>
<?php if($dyhb_options[user_code]=='1'):?>
<?php echo EchoCode();?><br><br>
<?php endif;?>
<input class="button submit" type="submit" id="submit" value="提交"/>	
<input class="button" type="reset" value="重置" />	
</p>	
<input name="blog_id"  type="hidden" value="<?php echo $BlogId;?>"/>
<input name="photo_id"  type="hidden" value="<?php echo $PhotoId;?>"/>
<input name="taotao_id"  type="hidden" value="<?php echo $TaotaoId;?>"/>
<input name="mp3_id"  type="hidden" value="<?php echo $Mp3Id;?>"/>
</form>
<script type="text/javascript" src="images/js/ajax.js"></script>
<div><span id="ajax_commentmes"></span></div>
</div>
</div>
<?php endif;?>
<br>
<br>