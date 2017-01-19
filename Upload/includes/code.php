<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * 文件：code.php
        * 说明：验证码
        * 作者：小牛哥
        * 时间：2010-07-22 0:17
        * 版本：DoYouHaoBaby-blog 告别大二.想念丝瓜 (特别版)
        * 主页：www.doyouhaobaby.com
		* 论坛：bbs.56swun.com
@----------------------------------------@
  ---- 西南民大的天空还是蛮明亮的哈 ----
\**=====copyright (c) DoYouHaoBaby=====**/

/** session路径 */
require_once("a.session.php");

/**
  * 产生随即数
  *
  * @param int $len 随机数长度
  * @return string $strs 随即长度
  */
function random($len){
    $srcstr="ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    mt_srand();//配置乱数种子
    $strs="";
    for($i=0;$i <$len;$i++){
         $strs.=$srcstr[mt_rand(0,35)];
    }
    return strtoupper($strs);
}

/** 随机生成的字符串 宽度 高度 */
$str=random(4);
$width =50;
$height = 30;
$_SESSION["code"] = $str;

/** 生成验证码开始 */
@header("Content-Type:image/png");
$im=imagecreate($width,$height);
$back=imagecolorallocate($im,251,198,147);
$pix=imagecolorallocate($im,255,255,255);
$font=imagecolorallocate($im,16,30,34);
mt_srand();
for($i=0;$i <1000;$i++){
   imagesetpixel($im,mt_rand(0,$width),mt_rand(0,$height),$pix);
}
imagestring($im,5, 7, 5,$str, $font);
imagerectangle($im,0,0,$width-1,$height-1,$font);
imagepng($im);
/** 完成后销毁资源 */
imagedestroy($im);

?>