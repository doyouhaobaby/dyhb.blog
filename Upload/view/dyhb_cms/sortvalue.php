<?php 
if(!defined('DOYOUHAOBABY_ROOT')){exit('hi,friend��');}
//�ļ����ֿɱ䣬���п���
//����Ĵ������������������־���࣬��ͬʱ����ҳ����
if($sortid||$_UrlIsCategory){
    $sortvalue_c=$Loglist_child;
}else{
    $sortvalue_c=$_sideSorts;
}
if($sortvalue_c): 
	//�ӷ���
	foreach($sortvalue_c as $value):
	//�ӷ����url��ʽ����Ϊ�漰��α��̬��������Ҫ���ú������д���
	$theurl=get_rewrite_sort($value['sort']);
    //�ӷ������ݵ���(��ҳ������ö���࣬����������ʾһ�£�����һ�����������ƵĻ�ֱ��ѭ����������������ѭ�����������)
	$CMS_NAME='CmsSortName'.$value[sort_id];
    $CMS_1='CmsSort1_'.$value[sort_id];
    $CMS_2='CmsSort2_'.$value[sort_id];
	//ȡ����Ӧ��ֵ,$$CMS_1,$$CMS_2Ϊ����ѭ����־�������ݣ�$Cms_NAMEΪֵ����Ȼֱ����$value['name']Ҳ���ԣ���������ҳ���������ط��������ݾͲ����ˣ���������������õĹ����ٺ�
	//�������������һ����ʽ��ʾ����ô���Ժϲ�������ݵ���
	$CMS_NAME='CmsSortName'.$value[sort_id];
	/**if($$CMS_1&&$$CMS_2){
		 $Cmsbig2=array_merge($$CMS_1,$$CMS_2);
	}elseif($$CMS_1&&!$$CMS_2){
		 $Cmsbig2=$$CMS_1;
	}else{
		$Cmsbig2=$$CMS_2;
	};**/

//����logo
if($value[logo]) $sort_logo=$value[logo];
else $sort_logo= "images/other/weare2.gif";
?>
<div class="index_box">
<h4 class="sort_<?php echo $value[sort_id];?>">
<img src="<?php echo $sort_logo;?>" alt="���ͷ���: <?php echo $value[name];?>"title="�鿴����:<?php echo $value[name];?>"border="0" style="float:left;padding:5px 0 0 20px;" />
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