<?php if(!defined('DOYOUHAOBABY_ROOT')){exit('hi,friend！');}
  include DyhbView('header',''); 
?>
<div id="main">
<div class="place">
<?php echo $front_header[1];?>:<a href="./"><?php echo $dyhb_options[blog_title];?></a> &raquo; <a href="./" title="<?php echo $front_header[0];?>"><?php echo $front_header[0];?></a> 
<?php _global_blog_pagenav();?>
</div>
<h2>
<?php 
//判断是否是页面，页面则不用加载相关日志等等
if($ShowLog['ispage']=='0'):?><?php toplog($ShowLog['istop']);?><?php mobilelog($ShowLog['ismobile']);?><?php endif;?><?php echo $ShowLog['title'];?>
</h2>
<div class="edit"><?php showedit($ShowLog['blog_id']);?></div>
<?php 
//判断是否是页面，页面则不用加载相关日志等等
if($ShowLog['ispage']=='0'):?>
<div class="bloger">
post by:  <?php _showlog_user($ShowLog);?>
/来源:<?php _showlog_from($ShowLog);?>
/<?php echo date('Y年m月d日 H:i:s',$ShowLog[dateline]);?>
</div>
<?php endif;?>
<div class="post">
<p><?php echo $ShowLog[content];?>
<?php 
//判断是否是页面，页面则不用加载相关日志等等
if($ShowLog['ispage']=='0'):?>
<?php digg_view($ShowLog['blog_id']);?>
<?php endif?>
<?php doHooks('width_showlog'); ?>
<div style="text-align:center"><?php echo $dyhb_options['ad_showlog'];?></div>
</p>
</div>
<div id="pagenav">
<?php echo $NewPagination;?>
</div>
<?php 
//判断是否是页面，页面则不用加载相关日志等等
if($ShowLog['ispage']=='0'):?>
<p class="post-footer align-left">	
<?php if($ShowLog[tags]):?>标签：<?php echo $ShowLog['tags'];?><br><?endif;?>  
分类：<?php _showlog_sort($ShowLog);?>&nbsp;&nbsp;				
<?php _showlog_comnum($ShowLog);?>&nbsp;&nbsp;
<?php _showlog_tracbacknum($ShowLog);?>&nbsp;&nbsp;
<?php _showlog_viewnum($ShowLog);?>
</p>
<div class='box'>
<h3>相邻日志</h3>
<div class="float-left">上一篇: <?php _showlog_pre();?></div>&nbsp;
<div class="float-right">下一篇: <?php _showlog_next();?></div>
</div><br>	
<div class='box'>
<h3 class='clear'>相关日志</h3>
<ul class='otherul'>
<?php _showlog_relate();?>
</ul>
</div>
<br>
<div class='box'>
<h3 class='clear'>引用通告</h3>
<a name="trackback"></a>
<p><a href="javascript:;" onclick="showajaxdiv('trackback', '<?php echo _showlog_trackback_url();?>', 500);"><img src="images/other/utf8.jpg" width="73px" height="15px" border="0" alt="点击获得Trackback地址,Encode: UTF-8" /></a><br>注意，本博客虽然使用GBK编码，但是trackback接收UTF-8编码数据，其它格式不支持.</p>
<ul class='otherul'>
<?php _showlog_trackback();?>
</ul>
</div>
<?php endif;?>
<br>
<?php if($ShowLog['islock']=='0'):?>
<?php include DyhbView('comlist','');?>
<?php endif;?>
</div>
<?php
include DyhbView('sidebar','');
include DyhbView('footer','');
?>	