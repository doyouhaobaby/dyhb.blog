<?php if(!defined('DOYOUHAOBABY_ROOT')){exit('hi,friend!');};?>
<!-- content���� -->
</div>
<div style="text-align:center;"><?php echo $dyhb_options['ad_footer'];?></div>
<!--�ײ���ʼ-->
<div id="footer">
<?php doHooks('width_footer'); ?>
<p>&copy; 2010 <strong><?php echo $dyhb_options[blog_title];?></strong> 
| Prowered by: <a href="<?php echo $dyhb_options[blog_program_url];?>" title="<?php echo $dyhb_options[prower_blog_name].$DOYOUHAOBABY_VERSION;?>"><?echo $dyhb_options[prower_blog_name];?></a>
| <?php if($dyhb_options[icp]):?><a href='./'><?php echo $dyhb_options[icp];?></a><?php endif;?>&nbsp;<?php echo $dyhb_options['visitor_count_html'];?>&nbsp;<a href="?login_out=true">Clean up Cookie</a>
<br><?php Footer();?>
</div>	
<!-- wrap������� -->
</div>
</body>
</html>