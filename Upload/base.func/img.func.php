<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * 文件：img.function.php
        * 说明：图片处理
        * 作者：小牛哥
        * 时间：2010-07-22 0:17
        * 版本：DoYouHaoBaby-blog 告别大二.想念丝瓜 (特别版)
        * 主页：www.doyouhaobaby.com
		* 论坛：bbs.56swun.com
@----------------------------------------@
  ---- 西南民大的天空还是蛮明亮的哈 ----
\**=====copyright (c) DoYouHaoBaby=====**/

/**
 * 附件上传
 *
 * @param string $FileName 文件名
 * @param string $Error 错误码
 * @param string $TmpFile 上传后的临时文件
 * @param string $FileSize 文件大小 
 * @param string $FileType 上传文件的类型 
 * @param array $AllowedType 允许上传的文件类型
 * @param boolean $IsQQ 是否为上传头像
 * @param boolean $StoreByDate 是否将文件按月存放
 * @return string 文件路径
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
	//是否超过博客规定的大小
	if ($FileSize > $dyhb_options[uploadfile_maxsize])
	   {
		   $result = changeFile($dyhb_options[uploadfile_maxsize]);
		   Dyhbmessage("$common_func[15]{$result}$common_func[16]","javascript:history.go(-1);");
	   }
	//是否为头像
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
	//附件原始路径
	$file_name = md5($FileName) .'-'. date('YmdHis') .'.'. $extension;
	$file_path = $upload_path . $file_name;
    
	//返回的路径和需要创建水印和缩略图的文件类型
	$file_return_url=$file_path;
	$imtype = array('jpg','png','jpeg');
    
	if($dyhb_options[upload_file_default]=='1'){
	//生成缩略图(swfupload无法生成缩略图)
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

	//创建水印
    if($dyhb_options[is_images_water_mark]=='1'&& in_array($extension, $imtype) ){
		if($dyhb_options[images_water_type]=='1'&&$dyhb_options['images_water_mark_img_imgurl']){
			//图像
            imageWaterMark($TmpFile, $dyhb_options[images_water_position],  array('type' => 'img', 'path'=>$dyhb_options[images_water_mark_img_imgurl]));
	    }elseif($dyhb_options[images_water_type]=='0'&&$dyhb_options['images_water_mark_text_content']){
			//文字
            imageWaterMark($TmpFile,$dyhb_options[images_water_position],array('type' => 'text', 'content' => GbkToUtf8($dyhb_options[images_water_mark_text_content],'GBK'), 'textColor' => $dyhb_options[images_water_mark_text_color], 'textFont' => $dyhb_options[images_water_mark_text_fontsize]));
		}
	}
  
    //原文件上传
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
    
	//返回URL
	if($IsQQ=='1'){
	    return 	substr($file_return_url,13);
	 }
	 else{
        return 	substr($file_return_url,16); 
	 }
}

/**
 * 远程文件大小
 *
 * @param string $url 远程文件路径
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
 * 保存远程图片到本地
 *
 * @param string $content 正文内容
 * @return string $content 正文内容
 */
function ImgToLocal($content){
  global $dyhb_options,$localdate,$_Photosorts,$common_func;
 if($dyhb_options['is_allow_tolocalimg']=='1') { 
   $content = stripslashes($content); 
   $img_array = array(); 
   preg_match_all("/(src|SRC)=[\"|'| ]{0,}(http:\/\/(.*)\.(gif|jpg|jpeg|bmp|png))/isU",$content,$img_array); 
   $img_array = array_unique($img_array[2]); 
   set_time_limit(0); 
   //是否按月存放
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
	  //将附件信息写入数据库
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
 * 手机版相册生成
 *
 * @param string $targetfile 图片URL路径
 * @param string $thumbwidth 图片宽
 * @param string $thumbheight 图片高
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
 * PHP图片水印 (水印支持图片或文字)支持中文
 *
 * @param  string    $groundImage    背景图片路径
 * @param  intval    $waterPos       水印位置:有10种状态，1-9以外为随机位置；
 *                                   1为顶端居左，2为顶端居中，3为顶端居右；
 *                                   4为中部居左，5为中部居中，6为中部居右；
 *                                   7为底端居左，8为底端居中，9为底端居右；
 * @param  array     $water_arr      参数数组，可包含如下值：
 *----------------------------------------------------------------
 * @param  string    $type       添加水印的类型 ,  'img' => 添加水印图片, 'text' => 添加水印文字,
 * @param  string    $path       添加水印图片时,水印图片的路径,
 * @param  string    $content    添加水印文字的文字内容
 * @param  string    $textColor  添加水印文字的文字颜色
 * @param  string    $textFont   添加水印文字的文字小大
 * @param  string    $textFile   添加水印文字的文字字库路径
 *----------------------------------------------------------------
 * @return mixed    返回TRUE或错误信息，只有当返回TRUE时，操作才是成功的
 * @example
 * <code>
 * imageWaterMark('./apntc.gif', 1,  array('type' => 'img', 'path'=>'')); 添加水印图片
 * imageWaterMark('./apntc.gif', 1, array('type' => 'text', 'content' => '', 'textColor' => '', 'textFont' => ''));  添加水印文字
 * </code>
 */
function imageWaterMark($backgroundPath, $waterPos = 0, $water_arr ){
	  global $common_func;
	  $isWaterImage = FALSE;
	    //读取背景图片
		if(!empty($backgroundPath) && file_exists($backgroundPath)){
			$background_info = @getimagesize($backgroundPath);
			$ground_width = $background_info[0];//取得背景图片的宽
			$ground_height = $background_info[1];//取得背景图片的高
		 
			switch($background_info[2])//取得背景图片的格式
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

	    //设定图像的混色模式
		@imagealphablending($background_im, true);
		if (is_array($water_arr) && !empty($water_arr)) {
			if($water_arr['type'] == 'img' && !empty($water_arr['path'])){
				$isWaterImage = TRUE;
		        $set = 0;
				$offset = isset($water_arr['offset']) && !empty($water_arr['offset']) ? $water_arr['offset'] : 0;
				$water_info = @getimagesize($water_arr['path']);
			    $water_width = $water_info[0];//取得水印图片的宽
				$water_height = $water_info[1];//取得水印图片的高
				switch($water_info[2])//取得水印图片的格式
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
				$temp = @imagettfbbox(ceil($textFont),0,$fontfile,$waterText);//取得使用 TrueType 字体的文本的范围
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
			case 1://1为顶端居左
				$posX = $offset * $set; 
				$posY = ($water_height + $offset) * $set;
			    break;
			case 2://2为顶端居中
				$posX = ($ground_width - $water_width) / 2;
				$posY = ($water_height + $offset) * $set;
				break;
			case 3://3为顶端居右
				$posX = $ground_width - $water_width - $offset * $set;
				$posY = ($water_height + $offset) * $set;
				break;
			case 4://4为中部居左
				$posX = $offset * $set;
				$posY = ($ground_height - $water_height) / 2;
			break;
				case 5://5为中部居中
				$posX = ($ground_width - $water_width) / 2;
				$posY = ($ground_height - $water_height) / 2;
				break;
			case 6://6为中部居右
				$posX = $ground_width - $water_width - $offset * $set;
				$posY = ($ground_height - $water_height) / 2;
				break;
			case 7://7为底端居左
				$posX = $offset * $set;
				$posY = $ground_height - $water_height;
				break;
			case 8://8为底端居中
				$posX = ($ground_width - $water_width) / 2;
				$posY = $ground_height - $water_height;
				break;
			case 9://9为底端居右
				$posX = $ground_width - $water_width - $offset * $set;
				$posY = $ground_height -$water_height;
				break;
			default://随机
				$posX = rand(0,($ground_width - $water_width));
				$posY = rand(0,($ground_height - $water_height));
			    break;
		}
	 
		if($isWaterImage === TRUE) {//图片水印
			@imagealphablending($water_im,true); 
            @imagealphablending($background_im,true); 	
			@imagecopy($background_im, $water_im, $posX, $posY, 0, 0, $water_width,$water_height);//拷贝水印到目标文件
		} else { //文字水印
			if( !empty($textColor) && (strlen($textColor)==7) ) {
				$R = hexdec(substr($textColor,1,2));
				$G = hexdec(substr($textColor,3,2));
				$B = hexdec(substr($textColor,5));
			} else {
			    return $common_func[27];
			}
			@imagettftext($background_im, $textFont, 0, $posX, $posY, @imagecolorallocate($background_im, $R, $G, $B), $fontfile , $waterText);
	    }
	 
		//生成水印后的图片
		@unlink($backgroundPath);
		switch($background_info[2])//取得背景图片的格式
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
 * 图片生成缩略图
 *
 * @param string $img 预缩略的图片
 * @param unknown_type $imgType 上传文件的类型 eg:image/jpeg
 * @param string $thumPatch 生成缩略图路径
 * @param int $max_w 缩略图最大宽度 px
 * @param int $max_h 缩略图最大高度 px
 * @return unknown
 */
function resizeImage($img, $imgType, $thumPatch, $max_w, $max_h){
	global $dyhb_options;
	if($dyhb_options[thumb_is_water_mark]=='1'){
        //缩略图创建水印
        if($dyhb_options[is_images_water_mark]=='1' ){
		      if($dyhb_options[images_water_type]=='1'&&$dyhb_options['images_water_mark_img_imgurl']){
			      //图像
                  imageWaterMark($img, $dyhb_options[images_water_position],  array('type' => 'img', 'path'=>$dyhb_options[images_water_mark_img_imgurl]));
	          }elseif($dyhb_options[images_water_type]=='0'&&$dyhb_options['images_water_mark_text_content']){
			      //文字
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
 * 按照比例改变图片大小
 *
 * @param string $img 图片路径
 * @param int $max_w 最大缩放宽
 * @param int $max_h 最大缩放高
 * @return unknown
 */
function ChangeImgSize ($img,$max_w,$max_h){
	$size = @getimagesize($img);
	$w = $size[0];
	$h = $size[1];
	//计算缩放比例
	@$w_ratio = $max_w / $w;
	@$h_ratio =	$max_h / $h;
	//决定处理后的图片宽和高
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