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
<ul class="header_menu">
<?php foreach($adminitem as $key=>$value):?>
<li <?php if (($key == $View)||($key=='index'&&!$View)):?>class="current"<?php endif;?>><a href="index.php?action=<?php echo $key;?>"><b><?php echo $value['name'];?></b></a></li>
<?php endforeach;?>
</ul>
<div id="wrapper">
<?php doHooks('admin_main_top');?>