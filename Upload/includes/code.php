<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * �ļ���code.php
        * ˵������֤��
        * ���ߣ�Сţ��
        * ʱ�䣺2010-07-22 0:17
        * �汾��DoYouHaoBaby-blog �����.����˿�� (�ر��)
        * ��ҳ��www.doyouhaobaby.com
		* ��̳��bbs.56swun.com
@----------------------------------------@
  ---- ����������ջ����������Ĺ� ----
\**=====copyright (c) DoYouHaoBaby=====**/

/** session·�� */
require_once("a.session.php");

/**
  * �����漴��
  *
  * @param int $len ���������
  * @return string $strs �漴����
  */
function random($len){
    $srcstr="ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    mt_srand();//������������
    $strs="";
    for($i=0;$i <$len;$i++){
         $strs.=$srcstr[mt_rand(0,35)];
    }
    return strtoupper($strs);
}

/** ������ɵ��ַ��� ��� �߶� */
$str=random(4);
$width =50;
$height = 30;
$_SESSION["code"] = $str;

/** ������֤�뿪ʼ */
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
/** ��ɺ�������Դ */
imagedestroy($im);

?>