<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * �ļ���img.function.php
        * ˵����ͼƬ����
        * ���ߣ�Сţ��
        * ʱ�䣺2010-07-22 0:17
        * �汾��DoYouHaoBaby-blog �����.����˿�� (�ر��)
        * ��ҳ��www.doyouhaobaby.com
		* ��̳��bbs.56swun.com
@----------------------------------------@
  ---- ����������ջ����������Ĺ� ----
\**=====copyright (c) DoYouHaoBaby=====**/

/**
 * �����ϴ�
 *
 * @param string $FileName �ļ���
 * @param string $Error ������
 * @param string $TmpFile �ϴ������ʱ�ļ�
 * @param string $FileSize �ļ���С 
 * @param string $FileType �ϴ��ļ������� 
 * @param array $AllowedType �����ϴ����ļ�����
 * @param boolean $IsQQ �Ƿ�Ϊ�ϴ�ͷ��
 * @param boolean $StoreByDate �Ƿ��ļ����´��
 * @return string �ļ�·��
 */

function UploadFile($FileName,$TmpFile,$FileSize,$Error, $FileType, $AllowedType,$IsQQ='0',$StoreByDate='0'){
	global $dyhb_options,$common_func;
	if ($Error == 1){
		Dyhbmessage($common_func[11].ini_get('upload_max_filesize').$common_func[12], 'javascript:history.go(-1);');
	}elseif ($Error > 1){
		Dyhbmessage($common_func[13].$Error, 'javascript:history.go(-1);');
	}
	$extension  = strtolower(substr(strrchr($FileName, "."),1));
	if (!in_array($extension, $AllowedType)){
		   Dyhbmessage($common_func[14],"javascript:history.go(-1);");
	   }
	//�Ƿ񳬹����͹涨�Ĵ�С
	if ($FileSize > $dyhb_options[uploadfile_maxsize])
	   {
		   $result = changeFile($dyhb_options[uploadfile_maxsize]);
		   Dyhbmessage("$common_func[15]{$result}$common_func[16]","javascript:history.go(-1);");
	   }
	//�Ƿ�Ϊͷ��
	if($IsQQ=='0'){
	     if($StoreByDate=='1'){
	           $upload_path = '../width/upload/' . date('Ym') . '/';
			 }
	     else{
			   $upload_path = '../width/upload/default/';
		     }
	   }
	else{
		   $upload_path = '../images/qq/';
	   }
	//����ԭʼ·��
	$file_name = md5($FileName) .'-'. date('YmdHis') .'.'. $extension;
	$file_path = $upload_path . $file_name;
    
	//���ص�·������Ҫ����ˮӡ������ͼ���ļ�����
	$file_return_url=$file_path;
	$imtype = array('jpg','png','jpeg');
    
	if($dyhb_options[upload_file_default]=='1'){
	//��������ͼ(swfupload�޷���������ͼ)
	$thumb_name= "t-".md5($FileName) .'-'. date('YmdHis') .'.'. $extension;
	$thumb_name_path=$upload_path . $thumb_name;
	$blogfile_thumb_width_heigth=unserialize($dyhb_options[blogfile_thumb_width_heigth]);
	$blogicon_thumb_width_heigth=unserialize($dyhb_options[blogicon_thumb_width_heigth]);
    if($dyhb_options[is_makeimage_thumb]=='1'&& in_array($extension, $imtype) && function_exists('ImageCreate')){
         if($IsQQ=='0') {
			 $thumb_width=$blogfile_thumb_width_heigth[0]; 
			 $thumb_heigth=$blogfile_thumb_width_heigth[1];
		 }
		 else {
			 $thumb_width=$blogicon_thumb_width_heigth[0];
			 $thumb_heigth=$blogicon_thumb_width_heigth[1];
		 }
         if(resizeImage($TmpFile, $FileType, $thumb_name_path, $thumb_width, $thumb_heigth)){
               $file_return_url=$thumb_name_path;
		 }
    }
	}

	//����ˮӡ
    if($dyhb_options[is_images_water_mark]=='1'&& in_array($extension, $imtype) ){
		if($dyhb_options[images_water_type]=='1'&&$dyhb_options['images_water_mark_img_imgurl']){
			//ͼ��
            imageWaterMark($TmpFile, $dyhb_options[images_water_position],  array('type' => 'img', 'path'=>$dyhb_options[images_water_mark_img_imgurl]));
	    }elseif($dyhb_options[images_water_type]=='0'&&$dyhb_options['images_water_mark_text_content']){
			//����
            imageWaterMark($TmpFile,$dyhb_options[images_water_position],array('type' => 'text', 'content' => GbkToUtf8($dyhb_options[images_water_mark_text_content],'GBK'), 'textColor' => $dyhb_options[images_water_mark_text_color], 'textFont' => $dyhb_options[images_water_mark_text_fontsize]));
		}
	}
  
    //ԭ�ļ��ϴ�
	if (!is_dir($upload_path)){
		umask(0);
		$result = @mkdir($upload_path, 0777);
		if ($result === false){
			Dyhbmessage("$common_func[17](width/upload)$common_func[18](images/qq)$common_func[19]","javascript:history.go(-1);");
		}
	}
	if (@is_uploaded_file($TmpFile)){
		if (@!move_uploaded_file($TmpFile ,$file_path)){
			@unlink($TmpFile);
			Dyhbmessage("$common_func[17](width/upload)$common_func[18](images/qq)$common_func[19]","javascript:history.go(-1);");
		}
		chmod($upload_path, 0777);
	}
    
	//����URL
	if($IsQQ=='1'){
	    return 	substr($file_return_url,13);
	 }
	 else{
        return 	substr($file_return_url,16); 
	 }
}

/**
 * Զ���ļ���С
 *
 * @param string $url Զ���ļ�·��
 * @return int filesize
 */
function getFileSize($url){ 
  $url = parse_url($url); 
  if($fp = @fsockopen($url['host'],empty($url['port'])?80:$url['port'],$error)){ 
    fputs($fp,"GET ".(empty($url['path'])?'/':$url['path'])." HTTP/1.1\r\n"); 
    fputs($fp,"Host:$url[host]\r\n\r\n"); 
    while(!feof($fp)){ 
      $tmp = fgets($fp); 
      if(trim($tmp) == ''){ 
      break; 
    }else if(preg_match('/Content-Length:(.*)/si',$tmp,$arr)){ 
     return trim($arr[1]); 
  } 
 } 
   return null; 
 }else{ 
   return null; 
 }} 

/**
 * ����Զ��ͼƬ������
 *
 * @param string $content ��������
 * @return string $content ��������
 */
function ImgToLocal($content){
  global $dyhb_options,$localdate,$_Photosorts,$common_func;
 if($dyhb_options['is_allow_tolocalimg']=='1') { 
   $content = stripslashes($content); 
   $img_array = array(); 
   preg_match_all("/(src|SRC)=[\"|'| ]{0,}(http:\/\/(.*)\.(gif|jpg|jpeg|bmp|png))/isU",$content,$img_array); 
   $img_array = array_unique($img_array[2]); 
   set_time_limit(0); 
   //�Ƿ��´��
   if($dyhb_options['file_store_bydate']=='1'){
	   $upload_path = '../width/upload/' . date('Ym') . '/';
	}else{
	   $upload_path = '../width/upload/default/';
	}
	if (!is_dir($upload_path)){
		umask(0);
		$result = @mkdir($upload_path, 0777);
		if ($result === false){
			Dyhbmessage("$common_func[20](width/upload)$common_func[21]","javascript:history.go(-1);");
		}
	}
   foreach($img_array as $key =>$value) { 
     $value = trim($value); 
     $get_file = @file_get_contents($value); 
	 $extension=strtolower(substr(strrchr($value,'.'),1));
     $file_name = "imgtolocal-". date('YmdHis') .'.'. $extension;
	 $file_path = $upload_path . $file_name;
     if($get_file) { 
        $fp = @fopen($file_path,"w"); 
        @fwrite($fp,$get_file); 
         @fclose($fp); 
      } 
	  $size=getFileSize($value);
	  //��������Ϣд�����ݿ�
      $SaveFileDate=array(
		  'path'=>str_replace("../width/upload/",'',$file_path),
		  'photosort_id'=>'-1',
		  'name'=>"$common_func[22]".$extension,
		  'dateline'=>$localdate,
		  'size'=>$size,
		  'filetype'=>"application/octet-stream"
		);
       $_Photosorts->AddFile($SaveFileDate);
       $content= ereg_replace($value,str_replace("../",'',$file_path),$content);
    }
	 CacheNewPhoto();
	 CachePhotoSorts();
  }
   return $content;
}

/**
 * �ֻ����������
 *
 * @param string $targetfile ͼƬURL·��
 * @param string $thumbwidth ͼƬ��
 * @param string $thumbheight ͼƬ��
 * @return unknown
 */
function Thumb_GD($targetfile, $thumbwidth, $thumbheight) {
	$attachinfo = @getimagesize($targetfile);

	list($img_w, $img_h) = $attachinfo;

	header('Content-type: '.$attachinfo['mime']);

	if($img_w >= $thumbwidth || $img_h >= $thumbheight) {

		if(function_exists('imagecreatetruecolor') && function_exists('imagecopyresampled') && function_exists('imagejpeg')) {

			switch($attachinfo['mime']) {
				case 'image/jpeg':
					$imagecreatefromfunc = function_exists('imagecreatefromjpeg') ? 'imagecreatefromjpeg' : '';
					$imagefunc = function_exists('imagejpeg') ? 'imagejpeg' : '';
					break;
				case 'image/gif':
					$imagecreatefromfunc = function_exists('imagecreatefromgif') ? 'imagecreatefromgif' : '';
					$imagefunc = function_exists('imagegif') ? 'imagegif' : '';
					break;
				case 'image/png':
					$imagecreatefromfunc = function_exists('imagecreatefrompng') ? 'imagecreatefrompng' : '';
					$imagefunc = function_exists('imagepng') ? 'imagepng' : '';
					break;
			}

			$imagefunc = $thumbstatus == 1 ? 'imagejpeg' : $imagefunc;

			$attach_photo = $imagecreatefromfunc($targetfile);

			$x_ratio = $thumbwidth / $img_w;
			$y_ratio = $thumbheight / $img_h;

			if(($x_ratio * $img_h) < $thumbheight) {
				$thumb['height'] = ceil($x_ratio * $img_h);
				$thumb['width'] = $thumbwidth;
			} else {
				$thumb['width'] = ceil($y_ratio * $img_w);
				$thumb['height'] = $thumbheight;
			}
			
			$cx = $img_w;
			$cy = $img_h;

			$thumb_photo = @imagecreatetruecolor($thumb['width'], $thumb['height']);

			@imageCopyreSampled($thumb_photo, $attach_photo ,0, 0, 0, 0, $thumb['width'], $thumb['height'], $cx, $cy);
			clearstatcache();

			if($attachinfo['mime'] == 'image/jpeg') {
				$imagefunc($thumb_photo, null, 90);
			} else {
				$imagefunc($thumb_photo);
			}
		}
	} else {
		readfile($targetfile);
		exit;
	}
}


/**
 * PHPͼƬˮӡ (ˮӡ֧��ͼƬ������)֧������
 *
 * @param  string    $groundImage    ����ͼƬ·��
 * @param  intval    $waterPos       ˮӡλ��:��10��״̬��1-9����Ϊ���λ�ã�
 *                                   1Ϊ���˾���2Ϊ���˾��У�3Ϊ���˾��ң�
 *                                   4Ϊ�в�����5Ϊ�в����У�6Ϊ�в����ң�
 *                                   7Ϊ�׶˾���8Ϊ�׶˾��У�9Ϊ�׶˾��ң�
 * @param  array     $water_arr      �������飬�ɰ�������ֵ��
 *----------------------------------------------------------------
 * @param  string    $type       ���ˮӡ������ ,  'img' => ���ˮӡͼƬ, 'text' => ���ˮӡ����,
 * @param  string    $path       ���ˮӡͼƬʱ,ˮӡͼƬ��·��,
 * @param  string    $content    ���ˮӡ���ֵ���������
 * @param  string    $textColor  ���ˮӡ���ֵ�������ɫ
 * @param  string    $textFont   ���ˮӡ���ֵ�����С��
 * @param  string    $textFile   ���ˮӡ���ֵ������ֿ�·��
 *----------------------------------------------------------------
 * @return mixed    ����TRUE�������Ϣ��ֻ�е�����TRUEʱ���������ǳɹ���
 * @example
 * <code>
 * imageWaterMark('./apntc.gif', 1,  array('type' => 'img', 'path'=>'')); ���ˮӡͼƬ
 * imageWaterMark('./apntc.gif', 1, array('type' => 'text', 'content' => '', 'textColor' => '', 'textFont' => ''));  ���ˮӡ����
 * </code>
 */
function imageWaterMark($backgroundPath, $waterPos = 0, $water_arr ){
	  global $common_func;
	  $isWaterImage = FALSE;
	    //��ȡ����ͼƬ
		if(!empty($backgroundPath) && file_exists($backgroundPath)){
			$background_info = @getimagesize($backgroundPath);
			$ground_width = $background_info[0];//ȡ�ñ���ͼƬ�Ŀ�
			$ground_height = $background_info[1];//ȡ�ñ���ͼƬ�ĸ�
		 
			switch($background_info[2])//ȡ�ñ���ͼƬ�ĸ�ʽ
			{
				case 1:
					$background_im = @imagecreatefromgif($backgroundPath);break;
				case 2:
					$background_im = @imagecreatefromjpeg($backgroundPath);break;
				case 3:
					$background_im = @imagecreatefrompng($backgroundPath);break;
				default:
					die($formatMsg);
			}
		} else {
	        return $common_func[23];
	    }

	    //�趨ͼ��Ļ�ɫģʽ
		@imagealphablending($background_im, true);
		if (is_array($water_arr) && !empty($water_arr)) {
			if($water_arr['type'] == 'img' && !empty($water_arr['path'])){
				$isWaterImage = TRUE;
		        $set = 0;
				$offset = isset($water_arr['offset']) && !empty($water_arr['offset']) ? $water_arr['offset'] : 0;
				$water_info = @getimagesize($water_arr['path']);
			    $water_width = $water_info[0];//ȡ��ˮӡͼƬ�Ŀ�
				$water_height = $water_info[1];//ȡ��ˮӡͼƬ�ĸ�
				switch($water_info[2])//ȡ��ˮӡͼƬ�ĸ�ʽ
				{
					case 1:
						$water_im = @imagecreatefromgif($water_arr['path']);
						break;
					case 2:
						$water_im = @imagecreatefromjpeg($water_arr['path'])
						;break;
					case 3:
						$water_im = @imagecreatefrompng($water_arr['path']);
						break;
					default:
						return $common_func[24];
		 	    } 
			} elseif ($water_arr['type'] === 'text' && $water_arr['content'] !='') {
			    $fontfile =  isset($water_arr['fontFile']) && !empty($water_arr['fontFile']) ?  $water_arr['fontFile'] : 'simkai.ttf';
			    $fontfile = 'C:\WINDOWS\Fonts\\' . $fontfile ;
				$waterText = $water_arr['content'];
				$set = 1;
				$offset = isset($water_arr['offset']) && !empty($water_arr['offset']) ? $water_arr['offset'] : 5;
				$textColor =  !isset($water_arr['textColor']) || empty($water_arr['textColor']) ? '#FF0000' :  $water_arr['textColor']; 
				$textFont =  !isset($water_arr['textFont']) || empty($water_arr['textFont']) ? 20 :  $water_arr['textFont']; 
				$temp = @imagettfbbox(ceil($textFont),0,$fontfile,$waterText);//ȡ��ʹ�� TrueType ������ı��ķ�Χ
			    $water_width = $temp[2] - $temp[6];
			    $water_height = $temp[3] - $temp[7];
			    unset($temp);
			} else {
				return $common_func[25];
			}
		} else {
			return false;
		}
	 
	    if( ($ground_width< $water_width) || ($ground_height<$water_height) ) {
			return $common_func[26];
	    }
		
		switch($waterPos)
		{
			case 1://1Ϊ���˾���
				$posX = $offset * $set; 
				$posY = ($water_height + $offset) * $set;
			    break;
			case 2://2Ϊ���˾���
				$posX = ($ground_width - $water_width) / 2;
				$posY = ($water_height + $offset) * $set;
				break;
			case 3://3Ϊ���˾���
				$posX = $ground_width - $water_width - $offset * $set;
				$posY = ($water_height + $offset) * $set;
				break;
			case 4://4Ϊ�в�����
				$posX = $offset * $set;
				$posY = ($ground_height - $water_height) / 2;
			break;
				case 5://5Ϊ�в�����
				$posX = ($ground_width - $water_width) / 2;
				$posY = ($ground_height - $water_height) / 2;
				break;
			case 6://6Ϊ�в�����
				$posX = $ground_width - $water_width - $offset * $set;
				$posY = ($ground_height - $water_height) / 2;
				break;
			case 7://7Ϊ�׶˾���
				$posX = $offset * $set;
				$posY = $ground_height - $water_height;
				break;
			case 8://8Ϊ�׶˾���
				$posX = ($ground_width - $water_width) / 2;
				$posY = $ground_height - $water_height;
				break;
			case 9://9Ϊ�׶˾���
				$posX = $ground_width - $water_width - $offset * $set;
				$posY = $ground_height -$water_height;
				break;
			default://���
				$posX = rand(0,($ground_width - $water_width));
				$posY = rand(0,($ground_height - $water_height));
			    break;
		}
	 
		if($isWaterImage === TRUE) {//ͼƬˮӡ
			@imagealphablending($water_im,true); 
            @imagealphablending($background_im,true); 	
			@imagecopy($background_im, $water_im, $posX, $posY, 0, 0, $water_width,$water_height);//����ˮӡ��Ŀ���ļ�
		} else { //����ˮӡ
			if( !empty($textColor) && (strlen($textColor)==7) ) {
				$R = hexdec(substr($textColor,1,2));
				$G = hexdec(substr($textColor,3,2));
				$B = hexdec(substr($textColor,5));
			} else {
			    return $common_func[27];
			}
			@imagettftext($background_im, $textFont, 0, $posX, $posY, @imagecolorallocate($background_im, $R, $G, $B), $fontfile , $waterText);
	    }
	 
		//����ˮӡ���ͼƬ
		@unlink($backgroundPath);
		switch($background_info[2])//ȡ�ñ���ͼƬ�ĸ�ʽ
		{
			case 1:
				@imagegif($background_im,$backgroundPath);
				break;
			case 2:
				@imagejpeg($background_im,$backgroundPath);
				break;
			case 3:
				@imagepng($background_im,$backgroundPath);
				break;
			default:
				die($errorMsg);
		}
		
		if(isset($water_im)) {
			@imagedestroy($water_im);
		}
		
		@imagedestroy($background_im);
}


/**
 * ͼƬ��������ͼ
 *
 * @param string $img Ԥ���Ե�ͼƬ
 * @param unknown_type $imgType �ϴ��ļ������� eg:image/jpeg
 * @param string $thumPatch ��������ͼ·��
 * @param int $max_w ����ͼ����� px
 * @param int $max_h ����ͼ���߶� px
 * @return unknown
 */
function resizeImage($img, $imgType, $thumPatch, $max_w, $max_h){
	global $dyhb_options;
	if($dyhb_options[thumb_is_water_mark]=='1'){
        //����ͼ����ˮӡ
        if($dyhb_options[is_images_water_mark]=='1' ){
		      if($dyhb_options[images_water_type]=='1'&&$dyhb_options['images_water_mark_img_imgurl']){
			      //ͼ��
                  imageWaterMark($img, $dyhb_options[images_water_position],  array('type' => 'img', 'path'=>$dyhb_options[images_water_mark_img_imgurl]));
	          }elseif($dyhb_options[images_water_type]=='0'&&$dyhb_options['images_water_mark_text_content']){
			      //����
                  imageWaterMark($img,$dyhb_options[images_water_position],array('type' => 'text', 'content' => GbkToUtf8($dyhb_options[images_water_mark_text_content],'GBK'), 'textColor' => $dyhb_options[images_water_mark_text_color], 'textFont' => $dyhb_options[images_water_mark_text_fontsize]));
		      }
	     }
	}

	$size = ChangeImgSize($img,$max_w,$max_h);
    $newwidth = $size['w'];
	$newheight = $size['h'];
	$w =$size['rc_w'];
	$h = $size['rc_h'];
	if ($w <= $max_w && $h <= $max_h){
		return false;
	}
	if ($imgType == 'image/pjpeg' || $imgType == 'image/jpeg'){
		if(function_exists('imagecreatefromjpeg')){
			$img = imagecreatefromjpeg($img);
		}else{
			return false;
		}
	} elseif ($imgType == 'image/x-png' || $imgType == 'image/png') {
		if (function_exists('imagecreatefrompng')){
			$img = imagecreatefrompng($img);
		}else{
			return false;
		}
	}
	if (function_exists('imagecopyresampled')){
		$newim = imagecreatetruecolor($newwidth, $newheight);
		imagecopyresampled($newim, $img, 0, 0, 0, 0, $newwidth, $newheight, $w, $h);
	} else {
		$newim = @imagecreate($newwidth, $newheight);
		@imagecopyresized($newim, $img, 0, 0, 0, 0, $newwidth, $newheight, $w, $h);
	}
	if ($imgType == 'image/pjpeg' || $imgType == 'image/jpeg'){
		if(!imagejpeg($newim,$thumPatch)){
			return false;
		}
	} elseif ($imgType == 'image/x-png' || $imgType == 'image/png') {
		if (!imagepng($newim,$thumPatch)){
			return false;
		}
	}
	@ImageDestroy ($newim);
	return true;
}

/**
 * ���ձ����ı�ͼƬ��С
 *
 * @param string $img ͼƬ·��
 * @param int $max_w ������ſ�
 * @param int $max_h ������Ÿ�
 * @return unknown
 */
function ChangeImgSize ($img,$max_w,$max_h){
	$size = @getimagesize($img);
	$w = $size[0];
	$h = $size[1];
	//�������ű���
	@$w_ratio = $max_w / $w;
	@$h_ratio =	$max_h / $h;
	//����������ͼƬ��͸�
	if( ($w <= $max_w) && ($h <= $max_h) ){
		$tn['w'] = $w;
		$tn['h'] = $h;
	} else if(($w_ratio * $h) < $max_h){
		$tn['h'] = ceil($w_ratio * $h);
		$tn['w'] = $max_w;
	} else {
		$tn['w'] = ceil($h_ratio * $w);
		$tn['h'] = $max_h;
	}
	$tn['rc_w'] = $w;
	$tn['rc_h'] = $h;
	return $tn ;
}


?>