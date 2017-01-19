<?php
require_once('width.php');

//不用cookie对访问次数进行限制
$blog_id = intval( get_argget('blog_id') );

if ( $dyhb_options[allowed_make_html]=='1' ) {
	/*$view = false;
	if ($posts = get_cookie('posts')) {
		$postarray = explode(',',$posts);
		if (in_array($blog_id, $postarray)) {
			$view = true;
		}
	}
	if (!$view) {*/
		$DB->query("UPDATE ".DB_PREFIX."blog SET viewnum = viewnum + 1 WHERE blog_id = '$blog_id'");
		/*$posts = empty($posts) ? $blog_id : ','.$blog_id;
		set_cookie('posts',$posts,'86400');*/
	}	
}
?>