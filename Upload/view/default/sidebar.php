<?php if(!defined('DOYOUHAOBABY_ROOT')){exit('hi,friend!');}?>
<div id='sidebar'>
<?php
$_sidebar=array();

//search
$sidebar_search=<<<DYHB
<div id="sidebar_search">
<form action='?action=s' method='get' class='searchform'>
<p><input name='action' type='hidden' value="s" />
<input name='key' class='textbox' type='text' title='请输入关键字...' />
<input  class='button' value='搜索' type='submit' /></p>			
</form>
</div>
DYHB;

//MP3播放器(背景，宽，高)
$sidebar_mp3=_side_mp3player("#ffffff","150","240");

//最新照片(宽*高)
$sidebar_newphoto=_side_newphoto("200","150",'');

//侧边栏是通过这种新式出来，找出相似的
foreach($_sideName as $key=>$value){
$value_c="sidebar_".$key;
$_sidebar[$key]="
<h3 onclick='showdiv(\"sidebar_{$key}\");'>$_sideName[$key]</h3><div id=\"sidebar_{$key}\"><ul>"
.$$value_c.
"</ul>
</div>";
}

?>	
<?php echo $dyhb_options['ad_sidebar'];?>
<?php doHooks('width_sidebar'); ?>
<?php echo widgets($_sideSort,$_sideShow,$_sidebar,'0');?>  
<a href='rss.php' target='_blank'><img src='images/other/rss.png'></a>
</div>