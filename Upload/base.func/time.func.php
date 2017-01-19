<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * �ļ���time.function.php
        * ˵����ʱ�䴦����
        * ���ߣ�Сţ��
        * ʱ�䣺2010-07-22 0:17
        * �汾��DoYouHaoBaby-blog �����.����˿�� (�ر��)
        * ��ҳ��www.doyouhaobaby.com
		* ��̳��bbs.56swun.com
@----------------------------------------@
  ---- ����������ջ����������Ĺ� ----
\**=====copyright (c) DoYouHaoBaby=====**/

/**
  * ʱ�䴦��,����unix��һ���µĿ�ͷ�ͽ�β��һ��Ŀ�ͷ�ͽ�β
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
  * ʱ�䴦��,����ͼ�������
  *
  * @param string $format ʱ���ʽ
  * @param string $timestamp ����ʱ��
  * @return array
  */
function get_date($format, $timestamp = '') {
	global $localhost;
	empty($timestamp) && $timestamp = PHP_TIME;
	return gmdate($format, $timestamp + intval($localhost) * 3600);
}

/**
  * ʱ�䴦��,����ĳ��ľ�������
  *
  * @param string $thetime unixʱ��
  * @param string $type link this Y-m-s
  * @return string
  */
function onedate($thetime,$type){
   $time='';
   $time=date("$type",$thetime);
   return $time;
}

/**
  * �������ɺ���
  *
  * @param string $nowtime ��ǰunixʱ��
  * @return string|HTML�����
  */
function Calendar($nowtime){
global $Common_url,$_UrlIsRecord,$dyhb_options,$common_func;
$now_y = onedate($nowtime,'Y');
$now_m = onedate($nowtime,'m');
$now_d = onedate($nowtime,'d');
$time  = onedate($nowtime,'Ymd');

//�����������ֵ�ж���־��ת
if (isset($_GET['r'])){
	$now_y = substr(intval($_GET['r']),0,4);
	$now_m = substr(intval($_GET['r']),4,2);
}
if($dyhb_options['permalink_structure']!='default'&&$_UrlIsRecord&&$dyhb_options['allowed_make_html']=='0'){
	$now_y = substr(intval($Common_url),0,4);
	$now_m = substr(intval($Common_url),4,2);
}

//������ת����
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

//��һ�������ڼ�
$week = @date("w",mktime(0,0,0,$now_m,1,$now_y));
//���������
$lastday = @date("t",mktime(0,0,0,$now_m,1,$now_y));
//���һ�������ڼ�
$lastweek = @date("w",mktime(0,0,0,$now_m,$lastday,$now_y));
($week == 0)?$week=7:$week;
$j = 1;
$w = 7;
$end = false;

//������
for ($i = 1;$i <= 6;$i++){
	if ($end || ($i == 6 && $lastweek==0)){
		break;
	}
	$cal.=   '<tr>';
	//������
	for($j ; $j <= $w; $j++){
		if ($j < $week){
			$cal.=  '<td>&nbsp;</td>';
		} elseif ( $j <= 7 ) {
			$r = $j - $week + 1;

			$now_time = $now_y . $now_m. '0' . $r;
			//���Ϊ���죬����css����
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
				//���Ϊ���죬����css����
				 $t < 10 ? $n_time = $now_y . $now_m . '0' . $t : $now_time = $now_y . $now_m . $t;
				if ($now_time == $time){
					$cal.=  '<td class="day">'. $t .'</td>';
				} else {
					$cal.= '<td>'.$t.'</td>';
				}
			}
		}
	}//��ѭ������

	$cal.=  '</tr>';
	$w += 7;
}//��ѭ������

$cal.= '</table>';
return $cal;
}

?>