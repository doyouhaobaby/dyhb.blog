<?php 
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * �ļ���newimg.php
        * ˵�����õ�Ƭ���ݴ�����־��ͼƬ
        * ���ߣ�Сţ��
        * ʱ�䣺2010-07-22 0:17
        * �汾��DoYouHaoBaby-blog �����.����˿�� (�ر��)
        * ��ҳ��www.doyouhaobaby.com
		* ��̳��bbs.56swun.com
@----------------------------------------@
  ---- ����������ջ����������Ĺ� ----
\**=====copyright (c) DoYouHaoBaby=====**/

/** ���غ��Ĳ��� */
require_once("common.php");

/** type */
$type=sql_check(get_args('type'));

/** ��ȡ����ͼƬ����ת�룬ת��ΪUTF-8 */
$_sideNewphoto=GbkToUtf8($_sideNewphoto,'GBK');
$_flashLog=GbkToUtf8($_flashLog,'GBK');

/** ����һ���µ��ļ� */ 
$dom = new DOMDocument("1.0",''); 

/** ��ʼ */
header("Content-Type: text/plain"); 

/** �������ڵ� */
$root = $dom->createElement("bcaster"); 
$dom->appendChild($root); 

/** �����Զ�����ʱ�� */
$autoPlayTime = $dom->createAttribute("autoPlayTime"); 
$root->appendChild($autoPlayTime); 
$Value = $dom->createTextNode("3"); 
$autoPlayTime->appendChild($Value); 

switch($type){
case "flashlog":
/**��ѭ��ȡ�����ݡ�*/
if($_flashLog){
     foreach($_flashLog as $value){
        /** �����������ӽڵ㡡*/ 
        $item = $dom->createElement("item"); 
        $root->appendChild($item); 

        /**  ����·���ڵ����ԣ�Ϊ�ڵ㸳ֵ */ 
        $price = $dom->createAttribute("item_url"); 
        $item->appendChild($price); 
        $priceValue = $dom->createTextNode($dyhb_options[blogurl]."/".$value[thumb]); 
        $price->appendChild($priceValue); 

        /** ����URL�ν� */
        $price = $dom->createAttribute("link"); 
        $item->appendChild($price);  
        $priceValue = $dom->createTextNode($dyhb_options[blogurl]."/?p=$value[blog_id]"); 
        $price->appendChild($priceValue);

		/** �������� */
        $price = $dom->createAttribute("itemtitle"); 
        $item->appendChild($price);  
        $priceValue = $dom->createTextNode($value[title]); 
        $price->appendChild($priceValue);
    }
}
break;
default:
/**��ѭ��ȡ�����ݡ�*/
if($_sideNewphoto){
     foreach($_sideNewphoto as $value){
        /** �����������ӽڵ㡡*/ 
        $item = $dom->createElement("item"); 
        $root->appendChild($item); 

        /**  ����·���ڵ����ԣ�Ϊ�ڵ㸳ֵ */ 
        $price = $dom->createAttribute("item_url"); 
        $item->appendChild($price); 
        $priceValue = $dom->createTextNode($dyhb_options[blogurl]."/width/upload/".$value['path']); 
        $price->appendChild($priceValue); 

        /** ����URL�ν� */
        $price = $dom->createAttribute("link"); 
        $item->appendChild($price);  
        $priceValue = $dom->createTextNode($dyhb_options[blogurl]."/?action=photo&id=$value[file_id]"); 
        $price->appendChild($priceValue);

		/** �������� */
        $price = $dom->createAttribute("itemtitle"); 
        $item->appendChild($price);  
        $priceValue = $dom->createTextNode($value[name]); 
        $price->appendChild($priceValue);
    }
}
break;
}
/** ����xml�ļ� */ 
echo $dom->saveXML(); 
 
?>