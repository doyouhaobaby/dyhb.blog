<?php if(!defined('DOYOUHAOBABY_ROOT')){exit('hi,friend！');}
//BBS模板
include DyhbView('header',''); 
?>
<div id='main'>
<div class='place'>当前位置:<a href="./"><?php echo $dyhb_options[blog_title];?></a> &raquo; <a href="./" title="首页">首页</a> 
<?php _global_blog_pagenav();?>
&raquo; 浏览模式:(<?php _narmal_list();?> )
</div>
<!--BBS分类开始-->
<?php if($_globalTreeSorts):foreach($_globalTreeSorts as $value):
$logo1=$value['logo']?$value[logo]:'images/other/weare2.gif';
$url1=get_rewrite_sort($value['sort']);
?>
<div class="box2">
<h3>
<img src="<?php echo $logo1;?>" alt="日志分类：<?php echo $value[name];?>" tilte="查看日志分类： <?php echo $value[name];?>"/>
<a href="<?php echo $url1;?>"><?php echo $value[name];?></a>
<a href="public.php?sortid=<?php echo $value[sort_id];?>"><img src="images/other/post.gif" title="发表帖子" alt="发表帖子" /></a>
<img id="category_<?php echo $value[sort_id];?>_img" src="images/other/collapsed.gif" title="收起/展开" alt="收起/展开" onclick="showdiv('category_<?php echo $value[sort_id];?>');" />
</h3>
<?php
if($value[child]):
?>
<table width="98%" cellspacing="0" cellpadding="0" id="category_<?php echo $value[sort_id];?>">
 <?php foreach($value[child] as $val):
$logo2=$val['logo']?$val[logo]:"images/other/weare2.gif";
$url2=get_rewrite_sort($val['sort']);
?>
<tr class="bbs_list">
<td class="table-entry">
<div class="b_none fire">
<a href="<?php echo $url2;?>"><b><img src="<?php echo $logo2;?>" alt="日志分类：<?php echo $val[name];?>" tilte="查看日志分类： <?php echo $value[name];?>"/><?php echo $val[name];?></b></a>
<a href="public.php?sortid=<?php echo $val[sort_id];?>"><img src="images/other/post.gif" title="发表帖子" alt="发表帖子" /></a>
<br /><span><?php echo $val[introduce];?></span><br />
</div>
</td>
<td class="table-entry">
<?php $c="CmsSort1_".$val[sort_id];
$c=$$c;
$url3=get_rewrite_sort($c[0][sort]);
if($c):?>
<div class="oneline">
<span class="name">发表：</span><a href="<?php echo $url3;?>">
<span class="word-break-all"><?php echo $c[0][title];?></span></a>
</div>
<div class="oneline">
<span class="name">作者：</span>
<a href="?c=<?php echo $c[0][user_id];?>"><?php echo $c[0][user];?></a>
</div>
<div class="oneline">
<span class="name">时间：</span>
<a><?php echo ChangeDate($c[0][dateline],'Y-m-d H:i:s');?></a>
</div>
<?php endif;?>
</td>
</tr>
<?php endforeach;?>
</table>
<?php
//end
endif;
?>
</div>
<br>
<?php endforeach;endif;?>
<!--BBS分类结束-->
</div>
<?php
include DyhbView('sidebar','');
include DyhbView('footer','');
?>