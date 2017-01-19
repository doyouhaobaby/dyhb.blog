<?php
function digg_view($blog_id){
	global $dyhb_options;
    echo " <iframe id=\"digg_content\" name=\"digg_content\" src=\"".$dyhb_options['blogurl']."/images/digg/digg_view.php?id=".$blog_id."\"
    width=\"100%\" height=\"85px\" scrolling=\"auto\"  frameborder=\"0\" allowTransparency=\"true\" ></iframe>";
}
?>