<?php 
if(!defined('DOYOUHAOBABY_ROOT')){exit('hi,friend！');}
//文件名字可变，可有可无
//下面的大量文字描述是针对日志分类，但同时和首页相似
if($sortid||$_UrlIsCategory){
    $sortvalue_c=$Loglist_child;
}else{
    $sortvalue_c=$_sideSorts;
}
if($sortvalue_c): 
	//子分类
	foreach($sortvalue_c as $value):
	//子分类的url样式，因为涉及到伪静态，所以需要调用函数进行处理
	$theurl=get_rewrite_sort($value['sort']);
    //子分类数据调用(首页如果调用都差不多，这里给大家演示一下，即可一个个整，相似的话直接循环输出，如下面就是循环输出的例子)
	$CMS_NAME='CmsSortName'.$value[sort_id];
    $CMS_1='CmsSort1_'.$value[sort_id];
    $CMS_2='CmsSort2_'.$value[sort_id];
	//取得相应的值,$$CMS_1,$$CMS_2为两个循环日志数据数据，$Cms_NAME为值，当然直接是$value['name']也可以，但是在首页或者其它地方调用数据就不行了，所以这个还是有用的哈，嘿嘿
	//这里如果数据以一种样式显示，那么可以合并另个数据调用
	$CMS_NAME='CmsSortName'.$value[sort_id];
	/**if($$CMS_1&&$$CMS_2){
		 $Cmsbig2=array_merge($$CMS_1,$$CMS_2);
	}elseif($$CMS_1&&!$$CMS_2){
		 $Cmsbig2=$$CMS_1;
	}else{
		$Cmsbig2=$$CMS_2;
	};**/

//分类logo
if($value[logo]) $sort_logo=$value[logo];
else $sort_logo= "images/other/weare2.gif";
?>
<div class="index_box">
<h4 class="sort_<?php echo $value[sort_id];?>">
<img src="<?php echo $sort_logo;?>" alt="博客分类: <?php echo $value[name];?>"title="查看分类:<?php echo $value[name];?>"border="0" style="float:left;padding:5px 0 0 20px;" />
<span><a href="<?php echo $theurl;?>"><!--<?php echo $value[name];?>--><?php echo $$CMS_NAME;?></a></span>
</h3>
<ul class="box2">
<!--<?if($Cmsbig2):foreach($Cmsbig2 as $value):?><li><a href="" title="<?php echo $value[title];?>"><?php echo $value[title];?></a></li><?endforeach;endif;?>-->
<?php if($$CMS_1):foreach($$CMS_1 as $value):$achive_url=_showlog_posturl($value); ?>
<li><a href="<?php echo $achive_url;?>" title="<?php echo $value[title];?>" style="color:#569602;"><?php echo $value[title];?></a></li>
<?php endforeach;endif;?>
<?php if($$CMS_2):foreach($$CMS_2 as $value):$achive_url2=_showlog_posturl($value); ?>
<li><a href="<?php echo $achive_url2;?>" title="<?php echo $value[title];?>"><?php echo $value[title];?></a></li>
<?php endforeach;endif;?>
</ul>
</div>
<?php endforeach;endif;?>