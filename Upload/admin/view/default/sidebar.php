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