<?php if(!defined('DOYOUHAOBABY_ROOT')){exit('hi,friend!');};?>
<!-- content结束 -->
</div>
<div class="clear"></div>
<div id="frendlink"><strong>友情链接：</strong>
<?php if($side_LogoLinks):foreach($side_LogoLinks as $value): if($value[isdisplay]=='1'):?>
<a href="<?php echo $value[url];?>" title="<?php echo $value[description];?>"><img src="<?php echo $value[logo];?>"></a>
<?php endif;endforeach;endif;?>
<?php if($side_TextLinks):foreach($side_TextLinks as $value):if($value[isdisplay]=='1'):?>
<a href="<?php echo $value[url];?>" title="<?php echo $value[description];?>" target="_blank" ><?php echo $value[name];?></a>
<?php endif;endforeach;endif;?>
</div>
</div>
<div class="clear"></div>
<!-- wrap主体结束 -->
</div>
<div style="text-align:center;"><?php echo $dyhb_options['ad_footer'];?></div>
<div class="clear"></div>
<div id="footer">
<?php doHooks('width_footer'); ?>
&copy; 2010 <a href="./"><?php echo $dyhb_options[blog_title];?></a>  |  Prowered by: <a href="<?php echo $dyhb_options[blog_program_url];?>" title="<?php echo $dyhb_options[prower_blog_name].$DOYOUHAOBABY_VERSION;?>"><?php echo $dyhb_options[prower_blog_name];?></a>  |  <?php if($dyhb_options[icp]):?><a href='./'><?echo $dyhb_options[icp];?></a><?php endif;?>  |  <?php echo $dyhb_options['visitor_count_html'];?>  |  <a href="?login_out=true">Clean up Cookie</a><br><?php Footer();?>
</div><!-- #footer -->
</body>
</html>