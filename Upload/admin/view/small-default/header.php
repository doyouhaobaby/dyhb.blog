<?php if(!defined('DOYOUHAOBABY_ROOT')) {exit('hi,friend!');}?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php _admin_dyhbheader();?>
</head>
<body> 
<div id="header">
<h1>
<a href="./" title="<?php echo $dyhb_options[prower_blog_name];?>-Control Panel"><?php echo $dyhb_options[prower_blog_name];?></a>
<em class="version"><?php echo $DOYOUHAOBABY_VERSION;?></em>
</h1>
<div class="menu"><a href="?action=option"><img src="../images/admin/icons/setting.gif"/><?php echo $admin_header['0'];?></a><a href="?action=template"><img src="../images/admin/icons/skin.gif"/><?php echo $admin_header['1'];?></a><?php echo $admin_header['2'];?><a href="?action=user&do=upd&id=<?php echo LOGIN_USERID;?>"><?php if($dyhb_nikename){echo $dyhb_nikename;}else{echo $dyhb_username;}?></a> | <a href="../?login_out=true"><?php echo $admin_header['3'];?></a><a href="../index.php" target="_blank"><img src="../images/other/home.gif"/><?php echo $admin_header['4'];?></a></div>
</div>
<div id="menu-div">
<ul class="clear">
<?php foreach($adminitem as $key=>$value):?>
<li <?php if (($key == $View)||($key=='index'&&!$View)):?>class="current"<?php endif;?>><a href="index.php?action=<?php echo $key;?>"><span></span><?php echo $value['name'];?></a></li>
<?php endforeach;?>
</ul>
</div>
<div id="wrapper">
<?php doHooks('admin_main_top');?>
<div id="sidebar">	
<div class="navigation">
<ul>
<?php foreach($adminitem as $key=>$value):?>
<li class="<?if (($key == $View)||($key=='index'&&!$View)):?>active<?php endif;?>">
<div class="dyhb_menu_bar"><a style="float:left;" href="?action=<?php echo $key;?>"><?php echo $value[name];?></a><span style="float:left;padding-top:10px;"  onclick='displayToggle("sidebar_<?php echo $key;?>","365");'><img src="<?php echo $dyhb;?>/images/toggle.gif"/></span></div>
<?php if ( $value['submenu'] && is_array($value['submenu'])):?>
<div id="sidebar_<?php echo $key;?>"  class="<?php if(($key==$View)||$key=='log'||$key=='user'||$key=='option'):?>dyhb_menu_display<?php else:?>dyhb_menu_indisplay<?php endif;?> <?php if( $value[start]):?> dyhb_menu_first<?php endif;?> <?php if( $value['end']):?>dyhb_menu_last<?php endif;?>">
<ul>
<?php foreach($value['submenu'] as $item):?>
<li><a href='?action=<?php echo $key;?>&amp;do=<?php echo $item['do'];?>' class="<?php if ($item['do'] == $view && $key == $View):?>pavigation-select-active<?php endif; ?>"><?php echo  $item[name];?></a></li>
<?php endforeach;?>
</ul>
</div>
<?php endif;?>
</li>
<?php endforeach;?>
</ul>
</div>
</div>
<script type="text/javascript"> 
displayMenu();
</script>