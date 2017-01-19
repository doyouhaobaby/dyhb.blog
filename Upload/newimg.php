<?php 
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * 文件：newimg.php
        * 说明：幻灯片数据处理，日志和图片
        * 作者：小牛哥
        * 时间：2010-07-22 0:17
        * 版本：DoYouHaoBaby-blog 告别大二.想念丝瓜 (特别版)
        * 主页：www.doyouhaobaby.com
		* 论坛：bbs.56swun.com
@----------------------------------------@
  ---- 西南民大的天空还是蛮明亮的哈 ----
\**=====copyright (c) DoYouHaoBaby=====**/

/** 加载核心部件 */
require_once("common.php");

/** type */
$type=sql_check(get_args('type'));

/** 读取最新图片并且转码，转化为UTF-8 */
$_sideNewphoto=GbkToUtf8($_sideNewphoto,'GBK');
$_flashLog=GbkToUtf8($_flashLog,'GBK');

/** 创建一个新的文件 */ 
$dom = new DOMDocument("1.0",''); 

/** 开始 */
header("Content-Type: text/plain"); 

/** 创建根节点 */
$root = $dom->createElement("bcaster"); 
$dom->appendChild($root); 

/** 创建自动播放时间 */
$autoPlayTime = $dom->createAttribute("autoPlayTime"); 
$root->appendChild($autoPlayTime); 
$Value = $dom->createTextNode("3"); 
$autoPlayTime->appendChild($Value); 

switch($type){
case "flashlog":
/**　循环取出数据　*/
if($_flashLog){
     foreach($_flashLog as $value){
        /** 创建根结点的子节点　*/ 
        $item = $dom->createElement("item"); 
        $root->appendChild($item); 

        /**  创建路径节点属性；为节点赋值 */ 
        $price = $dom->createAttribute("item_url"); 
        $item->appendChild($price); 
        $priceValue = $dom->createTextNode($dyhb_options[blogurl]."/".$value[thumb]); 
        $price->appendChild($priceValue); 

        /** 创建URL衔接 */
        $price = $dom->createAttribute("link"); 
        $item->appendChild($price);  
        $priceValue = $dom->createTextNode($dyhb_options[blogurl]."/?p=$value[blog_id]"); 
        $price->appendChild($priceValue);

		/** 创建名字 */
        $price = $dom->createAttribute("itemtitle"); 
        $item->appendChild($price);  
        $priceValue = $dom->createTextNode($value[title]); 
        $price->appendChild($priceValue);
    }
}
break;
default:
/**　循环取出数据　*/
if($_sideNewphoto){
     foreach($_sideNewphoto as $value){
        /** 创建根结点的子节点　*/ 
        $item = $dom->createElement("item"); 
        $root->appendChild($item); 

        /**  创建路径节点属性；为节点赋值 */ 
        $price = $dom->createAttribute("item_url"); 
        $item->appendChild($price); 
        $priceValue = $dom->createTextNode($dyhb_options[blogurl]."/width/upload/".$value['path']); 
        $price->appendChild($priceValue); 

        /** 创建URL衔接 */
        $price = $dom->createAttribute("link"); 
        $item->appendChild($price);  
        $priceValue = $dom->createTextNode($dyhb_options[blogurl]."/?action=photo&id=$value[file_id]"); 
        $price->appendChild($priceValue);

		/** 创建名字 */
        $price = $dom->createAttribute("itemtitle"); 
        $item->appendChild($price);  
        $priceValue = $dom->createTextNode($value[name]); 
        $price->appendChild($priceValue);
    }
}
break;
}
/** 保存xml文件 */ 
echo $dom->saveXML(); 
 
?>