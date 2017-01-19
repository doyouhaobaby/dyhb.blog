<?php if(!defined('DOYOUHAOBABY_ROOT')){exit('hi,friend£¡');}
  include DyhbView('header','');
?>
<div id='main'>
<div class="place">
<?php echo $front_header[1];?>:<a href="./"><?php echo $dyhb_options[blog_title];?></a> &raquo; <a href="./" title="<?php echo $front_header[0];?>"><?php echo $front_header[0];?></a> 
<?php _global_blog_pagenav();?>
&raquo; <?php echo $front_header[2];?>:(<?php _narmal_list();?> )
</div>
<div id="index_top">
        <div id="index_topl">
        <?php echo  _side_newphoto("300","250",'flashlog');?>
        </div>
        <div id="index_topr">
          <div class="itop_box">
            <div class="top_news">
			<?php if($CmsNew1):foreach($CmsNew1 as $value):
$url1=_showlog_posturl($value);
$title=gbksubstr($value[title],'0','20');
$content=gbksubstr( $value[content] ,'0','80');
?>
<h1><a  href="<?php echo $url1;?>"><?php echo $title;?></a></h1>
              <p><?php echo $content;?></p>
<?php endforeach;endif;?>
            </div>
            <ul class="sidelist">
<?php if($CmsNew2):foreach($CmsNew2 as $value):
$url2=_showlog_posturl($value);
$title=gbksubstr($value[title],'0','20');
?>
<li><a  title=<?php echo $title;?>" href="<?php echo $url2;?>"><?php echo $title;?></a></li>
<?endforeach;endif;?>
</ul>
          </div>
        </div>
      </div>
<div class="clear"></div><br>
<div class="index_pic box">
 <h3>×îÐÂÍ¼ÎÄ</h3>
   <ul>
<?php if($_sideNewphoto):foreach($_sideNewphoto as $value):
$content=$value[description]?gbksubstr($value[description],'0','20'):$value[name];
$the_img_url=$dyhb_options['photo_isshow_hide']=='1'?"file.php?id=$value[file_id]":"width/upload/$value[path]";
?>
<li><a  title="<?php echo $value[name];?>" href="?action=photo&id=<?php echo $value[file_id];?>"><img src="<?php echo $the_img_url;?>" width="120" height="108" /><?php echo $content;?></a></li>
<?endforeach;endif;?>     
</ul>
</div>
<div class="clear"></div>
<div id="pagenav"><?echo $pagination;?></div>
<!--{<?php
include DyhbView('sortvalue','');
?>}-->
<div id="pagenav"><?echo $pagination;?></div>
</div>
<?php
include DyhbView('sidebar','');
include DyhbView('footer','');
?>