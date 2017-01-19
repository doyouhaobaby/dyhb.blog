<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * 文件：time.function.php
        * 说明：时间处理函数
        * 作者：小牛哥
        * 时间：2010-07-22 0:17
        * 版本：DoYouHaoBaby-blog 告别大二.想念丝瓜 (特别版)
        * 主页：www.doyouhaobaby.com
		* 论坛：bbs.56swun.com
@----------------------------------------@
  ---- 西南民大的天空还是蛮明亮的哈 ----
\**=====copyright (c) DoYouHaoBaby=====**/

/**
  * 时间处理,返回unix中一个月的开头和结尾，一年的开头和结尾
  *
  * @param string $t like this 201200 and 20120000
  * @return array
  */
function dyhb_date($t){
    $a=array();
	if($t.length==4){$mouth=substr($t,4);}
	else{$mouth=substr($t,4,2);}
	$year=substr($t,0,4);
    $a[0]=$startday=mktime(0,0,0,$mouth,1,$year);
    $a[1]=$endday=mktime(0,0,0,$mouth+1,1,$year);
	$a[2]=$startyear=mktime(0,0,0,1,1,$year);
	$a[3]=$endyear=mktime(0,0,0,1,1,$year+1);
    return $a;
}

/**
  * 时间处理,用于图像防盗链
  *
  * @param string $format 时间格式
  * @param string $timestamp 具体时间
  * @return array
  */
function get_date($format, $timestamp = '') {
	global $localhost;
	empty($timestamp) && $timestamp = PHP_TIME;
	return gmdate($format, $timestamp + intval($localhost) * 3600);
}

/**
  * 时间处理,返回某天的具体日期
  *
  * @param string $thetime unix时间
  * @param string $type link this Y-m-s
  * @return string
  */
function onedate($thetime,$type){
   $time='';
   $time=date("$type",$thetime);
   return $time;
}

/**
  * 日历生成函数
  *
  * @param string $nowtime 当前unix时间
  * @return string|HTML代码块
  */
function Calendar($nowtime){
global $Common_url,$_UrlIsRecord,$dyhb_options,$common_func;
$now_y = onedate($nowtime,'Y');
$now_m = onedate($nowtime,'m');
$now_d = onedate($nowtime,'d');
$time  = onedate($nowtime,'Ymd');

//根据浏览器的值判断日志跳转
if (isset($_GET['r'])){
	$now_y = substr(intval($_GET['r']),0,4);
	$now_m = substr(intval($_GET['r']),4,2);
}
if($dyhb_options['permalink_structure']!='default'&&$_UrlIsRecord&&$dyhb_options['allowed_make_html']=='0'){
	$now_y = substr(intval($Common_url),0,4);
	$now_m = substr(intval($Common_url),4,2);
}

//日历跳转计算
	if ($now_m == 1) {
		$lastyear = $now_y-1;
		$lastmonth = 12;
		$nextmonth = $now_m+1;
		$nextyear = $now_y;
	} elseif ($now_m == 12) {
		$lastyear = $now_y;
		$lastmonth = $now_m - 1;
		$nextyear = $now_y + 1;
		$nextmonth = 1;
	} else {
		$lastmonth = $now_m - 1;
		$nextmonth = $now_m + 1;
		$lastyear = $nextyear = $now_y;
	}

($nextmonth < 10)?$nextmonth = '0'.$nextmonth:$nextmonth;
($lastmonth < 10)?$lastmonth = '0'.$lastmonth:$lastmonth;


$last_y_url =get_rewrite_record($now_y - 1,$now_m);
$next_y_url =get_rewrite_record($now_y + 1,$now_m);
$last_m_url =get_rewrite_record($lastyear - 1,$lastmonth);
$next_m_url =get_rewrite_record($nextyear,$nextmonth);
$now_y2=$now_y.$common_func[1];
$now_m2=$now_m.$common_func[2];

$cal=
"<table class=\"calendarhead\" cellspacing=\"0\">
<tr>
<td>
<a href=\"$last_y_url\">&laquo;</a>  </a>$now_y2<a href=\"$next_y_url\"> &raquo; </a>
</td>
<td>
<a href=\"$last_m_url\"> &laquo; </a>$now_m2<a href=\"$next_m_url\"> &raquo; </a>
</td>
</tr>
</table>
<table class=\"calendar\" cellspacing=\"0\">
<tr>
<td class=\"mon\">$common_func[4]</td>
<td class=\"week\">$common_func[5]</td>
<td class=\"week\">$common_func[6]</td>
<td class=\"week\">$common_func[7]</td>
<td class=\"week\">$common_func[8]</td>
<td class=\"week\">$common_func[9]</td>
<td class=\"sun\">$common_func[10]</td>
</tr>";

//第一天是星期几
$week = @date("w",mktime(0,0,0,$now_m,1,$now_y));
//当天的天数
$lastday = @date("t",mktime(0,0,0,$now_m,1,$now_y));
//最后一天是星期几
$lastweek = @date("w",mktime(0,0,0,$now_m,$lastday,$now_y));
($week == 0)?$week=7:$week;
$j = 1;
$w = 7;
$end = false;

//生成行
for ($i = 1;$i <= 6;$i++){
	if ($end || ($i == 6 && $lastweek==0)){
		break;
	}
	$cal.=   '<tr>';
	//生成列
	for($j ; $j <= $w; $j++){
		if ($j < $week){
			$cal.=  '<td>&nbsp;</td>';
		} elseif ( $j <= 7 ) {
			$r = $j - $week + 1;

			$now_time = $now_y . $now_m. '0' . $r;
			//如果为当天，给点css属性
			if ($now_time == $time){
				$cal.= '<td class="day">'. $r .'</td>';
			} else {
				$cal.=  '<td>'. $r .'</td>';
			}
		}else{
			$t = $j - ($week - 1);
			if ($t > $lastday){
				$end = true;
				$cal.=  '<td>&nbsp;</td>';
			} else {
				//如果为当天，给点css属性
				 $t < 10 ? $n_time = $now_y . $now_m . '0' . $t : $now_time = $now_y . $now_m . $t;
				if ($now_time == $time){
					$cal.=  '<td class="day">'. $t .'</td>';
				} else {
					$cal.= '<td>'.$t.'</td>';
				}
			}
		}
	}//内循环结束

	$cal.=  '</tr>';
	$w += 7;
}//外循环结束

$cal.= '</table>';
return $cal;
}

?>