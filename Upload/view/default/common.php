<?php if(!defined('DOYOUHAOBABY_ROOT')){exit('hi,friend！');}
  include DyhbView('header',''); 
?>
<div id='main'>
<div class='place'><?php echo $front_header[1];?>:<a href="./"><?php echo $dyhb_options[blog_title];?></a> &raquo; <a href="./" title="<?php echo $front_header[0];?>"><?php echo $front_header[0];?></a> &raquo;
<?php echo $ModelHead;?>
</div>  
<?php echo $_result;?> 
<?php if($View=='microlog'){ _list_page_taotao('#1b5790','35','#eef5fb','600px','90%','560px','30px');}?>
<!--以上的参数对应为：边框颜色、头像大小、分割线颜色-->
<?php if(($View=='photo'&&$photo_id)||($View=='microlog'&&$taotao_id)||($View=='mp3'&&$mp3_id)||$View=='guestbook'){
include DyhbView('comlist','');
}
?>
</div>
<?php
  include DyhbView('sidebar','');
  include DyhbView('footer','');
?>