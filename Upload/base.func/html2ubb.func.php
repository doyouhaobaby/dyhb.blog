<?php
/**
  * 评论表情处理，Ubb->html
  *
  * @param string $str 要解析的字符串
  * @return string
  */
function ubb2html($str) { 
$match = array( 
   "%\[b\](.*?)\[\/b\]%si",
   "%\[i\](.*?)\[\/i\]%si",
   "%\[u\](.*?)\[\/u\]%si", 
   "%\[url\](.*?)\[\/url\]%si", 
   "%\[url=(.*?)\](.*?)\[\/url\]%si", 
   "%\[img\](.*?)\[\/img\]%si", 
   "%\[font=(.*?)\](.*?)\[\/font\]%si",
   "%\[color=(.*?)\](.*?)\[\/color\]%si",
   "%\[size=(.*?)\](.*?)\[\/size\]%si",
   "%\[smile\](.*?)\[\/smile\]%si",
   "%\[blockquote\](.*?)\[\/blockquote\]%si",
   "%\[strong\](.*?)\[\/strong\]%si"
 ); 
$replace = array( 
   "<b>$1</b>",
   "<i>$1</i>",
   "<u>$1</u>",
   "<a href=\"$1\" target=_blank>$1</a>", 
   "<a href=\"$1\" target=_blank>$2</a>", 
   "<a href=\"$1\" target=\"_blank\"><img src=\"$1\" border=\"0\" onload=\"javascript:if(this.width>200)this.width=150\" title=\"$common_func[27]\"></a>",
   "<font=\"$1\">$2</font>",
   "<font color=\"$1\">$2</font>",
   "<font size=\"$1\" target=_blank>$2</font>",
   "<img src=\"images/smiley/images/$1.gif\" border=\"0\">",
   "<blockquote>$1</blockquote>",
   "<strong>$1</strong>"
); 
$str = preg_replace($match, $replace, $str); 
    return $str; 
}

?>