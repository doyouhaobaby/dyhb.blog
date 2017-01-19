<?php if(!defined('DOYOUHAOBABY_ROOT')){exit('hi,friend！');}
  include DyhbView('header',''); 
?>
<div id='main'>
<div class='place'><?php echo $front_header[1];?>:<a href="./"><?php  echo $dyhb_options[blog_title];?></a> &raquo; <a href="./" title="<?php echo $front_header[0];?>"><?php  echo $front_header[0];?></a> 
<?php _global_blog_pagenav();?>
&raquo; <?php echo $front_header[2];?>:(<?php  _narmal_list();?> )</div>
<div id="pagenav"><?php echo $pagination;?></div>
<?php if($_Loglist):foreach($_Loglist as $value):?>
<h2><?php toplog($value[istop]);?><?php echo mobilelog($value[ismobile]);?><?php  _showlog_title($value);?></h2>
<div class="edit"><?php  showedit($value['blog_id']);?></div>
<div class="bloger">post by <?php _showlog_user($value);?>
/来源:<?php _showlog_from($value);?>
/<?php  echo date('Y年m月d日 H:i:s',$value[dateline]);?>
</div>
<div class="post"><p><?php  if($value[thumb]):?><img src="<?php  echo $value[thumb];?>"><?php endif;?></p><p><?php echo $value[content];?></p></div>	
<p class="post-footer align-left">
<?php if($value[tags]):?>标签：<?php echo $value['tags'];?><br><?php endif;?>  
分类：<?php  _showlog_sort($value);?>&nbsp;&nbsp;				
<?php _showlog_comnum($value);?>&nbsp;&nbsp;
<?php _showlog_tracbacknum($value);?>&nbsp;&nbsp;
<?php _showlog_viewnum($value);?>
</p>
<?php endforeach;endif;?>
<!--分页条-->
<div id="pagenav"><?php echo $pagination;?></div>
<?php if($dyhb_options['see_the_sortvalue']=='1'):?>
<?php 
if($sortid||$_UrlIsCategory){
include DyhbView('sortvalue','');
}
?>
<?php endif;?>
</div>
<?php 
  include DyhbView('sidebar','');
  include DyhbView('footer','');
?>