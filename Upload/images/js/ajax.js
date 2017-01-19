//添加留言
var options = {
    url: 'index.php?action=addcom',
	beforeSubmit: function () {
	if (($("#ajax_name").val() == ""||$("#ajax_name").val() >15)&&$("#ajax_name").val()!=undefined) {
        $("#ajax_commentmes").text(lang[46]).show().fadeOut(3000);
        return false;
      }
      if ($("#ajax_comment").val() == ""||$("#ajax_comment").val()<30||$("#ajax_comment").val()>700) {
        $("#ajax_commentmes").text(lang[47]).show().fadeOut(3000);
        return false;
      }
	  //电子邮件验证
	 function checkEmail (str){
	   isEmail1=/^\w+([\.\-]\w+)*\@\w+([\.\-]\w+)*\.\w+$/;
	   return (isEmail1.test(str));
     }
	  if($("#ajax_email").val() != ""&&$("#ajax_email").val()!=undefined){
	  if (!checkEmail($("#ajax_email").val())) {
        $("#ajax_commentmes").text(lang[48]).show().fadeOut(3000);
        return false;
      }
	  }
	 //url格式验证
	 function IsURL(str_url){
      var strRegex = "^((https|http|ftp|rtsp|mms)?://)"
       + "?(([0-9a-z_!~*'().&=+$%-]+: )?[0-9a-z_!~*'().&=+$%-]+@)?" //ftp的user@
          + "(([0-9]{1,3}\.){3}[0-9]{1,3}" // IP形式的URL- 199.194.52.184
          + "|" // 允许IP和DOMAIN（域名）
          + "([0-9a-z_!~*'()-]+\.)*" // 域名- www.
          + "([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\." // 二级域名
          + "[a-z]{2,6})" // first level domain- .com or .museum
          + "(:[0-9]{1,4})?" // 端口- :80
          + "((/?)|" // a slash isn't required if there is no file name
          + "(/[0-9a-z_!~*'().;?:@&=+$,%#-]+)+/?)$";
          var re=new RegExp(strRegex);
		  return (re.test(str_url));
    }
	if($("#ajax_url").val() != ""&&$("#ajax_url").val() !=undefined){
	  if (!IsURL($("#ajax_url").val())) {
        $("#ajax_commentmes").text(lang[49]).show().fadeOut(3000);
        return false;
      }
	  }
   //验证完毕，发送数据
   $("#ajax_commentmes").text(lang[50]).show();
	},
    success: function (responseText){
		if(responseText!=lang[51]&&responseText!=lang[52]&&responseText!=lang[53]&&responseText!=lang[54]){
			parentcomment_id=$("#parentcomment_id").val();
	        if(!parentcomment_id){
		        $("#ajax_parent_back").prepend(responseText).show('slow');
		    }else{
		        $("#parentcomment_id").before(responseText).show('slow');
		    }
		    //$("#ajax_commentform").clearForm();
		    $("#ajax_commentmes").text(lang[55]).show().fadeOut(3000);
		}else{
			if(responseText==lang[52]){
			   $("#ajax_commentmes").text(lang[56]).show().fadeOut(4000);
			}
			if(responseText==lang[54]){
			   $("#ajax_commentmes").text(lang[57]).show().fadeOut(4000);
			}
			if(responseText==lang[51]){
			   $("#ajax_commentmes").text(lang[58]).show().fadeOut(4000);
			}
			if(responseText==lang[53]){
			   $("#ajax_commentmes").text(lang[59]).show().fadeOut(4000);
			}
        }
	 }
	};
$(document).ready(function(){
 $("#ajax_commentform").ajaxForm(options);
});