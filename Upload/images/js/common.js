//����
//����Ƿ�ѡ��
function issetcheckbox(node) {
	var result = false;
	$('input.'+node).each(function(i){
		if (this.checked) {
			result = true;
		}
	});
	return result;
}
//ȫѡ��ѡ
function CheckAll(value,obj)  
{
var form=document.getElementsByTagName("form")
	for(var i=0;i<form.length;i++)
	{
		 for (var j=0;j<form[i].elements.length;j++)
		 {
				if(form[i].elements[j].type=="checkbox")
				{
				var e = form[i].elements[j];
				if (value=="selectAll"){e.checked=obj.checked}
				else{e.checked=!e.checked;}
			    }
		  }
	}
}
//���ز�����Ϣ
function hideMessage(){
	$(".actived2").hide();
	$(".actived").hide();
	$(".actived3").hide();
	$(".actived_style").hide();
}
function hideMes(){
  setTimeout(hideMessage,6000);
}

//���ɻָ�����ȷ��
function dyhb_act(id, property) 
 {
	switch (property)
	  {
		case 'sort':
		var valuereturn="?action=sort&&do=del&id="+id;
		var msg = lang[13];break;
		case 'link':
		var valuereturn="?action=link&do=del&id="+id;
		var msg = lang[14];break;
		case 'backup':
		var valuereturn="?action=backup&do=into&fil="+id;
		var msg = lang[15];break;
		case 'delbackup':
		var valuereturn="?action=backup&do=delfil&fil="+id;
		var msg = lang[16];break;
		case 'log':
		var valuereturn="?action=log&do=del&id="+id;
		var msg = lang[17];break;
		case 'comment':
		var valuereturn="?action=comment&do=del&id="+id;
		var msg = lang[18];break;
		case 'file':
		var valuereturn="upload.php?do=del&id="+id;
		var msg = lang[19];break;
		case 'photosort':
		var valuereturn="upload.php?do=delphotosort&id="+id;
		var msg = lang[20];break;
        case 'delqq':
		var valuereturn="upload.php?do=delqq&qqname="+id;
		var msg = lang[21];break;
		case 'user':
		var valuereturn="?action=user&do=del&id="+id;
		var msg = lang[22];break;
		case 'trackback':
		var valuereturn="?action=log&do=trackback&do2=del&id="+id;
		var msg = lang[23];break;
		case 'backupfile':
		var valuereturn="?action=backup&do=del";
		var msg = lang[24];break;
		case 'music':
		var valuereturn="?action=photo&do=mp3&do2=del&mp3_id="+id;
		var msg = lang[25];break;
		case 'mp3sort':
		var valuereturn="?action=photo&do=mp3&do2=del&mp3sort_id="+id;
		var msg = lang[26];break;
		case 'clearallcache':
		var valuereturn="?action=backup&do=clearallcache";
		var msg = lang[27];break;
		case 'onemicorlog':
		var valuereturn="?action=log&do=microlog&do2=del&id="+id;
		var msg = lang[28];break;
		case 'widgets':
		var valuereturn="?action=template&do=rebuild";
		var msg = lang[29];break;
		case 'delphoto':
		var valuereturn="?action=user&id="+id+"&do=delphoto";
		var msg = lang[30];break;
		case 'myfield':
		var valuereturn="?action=log&do=myfield&do2=del&name="+id;
		var msg = lang[31];break;
	  }
	if(confirm(msg))
	  {
		window.location = valuereturn;
	  }
	else 
	  {
		return;
	  }
 }

//��־
function dyhb_blogaction(act){
	if (issetcheckbox('blog_ids') == false) {
		alert(lang[32]);
		return;}
	if(act == 'del' && !confirm(lang[33])){return;}
	$("#prepare").val(act);
	$("#thelog").submit();
}
function dyhb_changesort(obj) {
	var sort_id = obj.value;
	if (issetcheckbox('blog_ids') == false) {
		alert(lang[32]);
		return;}
	if($('#sort').val() == '')return;
	$("#prepare").val('changesort');
	$("#thelog").submit();
}
function dyhb_changeauthor(obj) {
	var user_id = obj.value;
	if (issetcheckbox('blog_ids') == false) {
		alert(lang[32]);
		return;}
	if($('#author').val() == '')return;
	$("#prepare").val('changeauthor');
	$("#thelog").submit();
}

//��̬����
function dyhb_makehtml(type){
	if(confirm(lang[34])){
       $("#prepare").val(type);
	   $("#makehtml").submit();
	}
}

//comment
function dyhb_commentaction(act){
	if (issetcheckbox('comment_ids') == false) {
		alert(lang[35]);
		return;}
	if(act == 'del' && !confirm(lang[36])){return;}
	if(act == 'tomessage' && !confirm(lang[37])){return;}
	$("#prepare").val(act);
	$("#thecomment").submit();
}
//����
function dyhb_fileaction(act){
	if (issetcheckbox('file_ids') == false) {
		alert(lang[38]);
		return;}
	if(act == 'del' && !confirm(lang[39])){return;}
	$("#prepare").val(act);
	$("#thefile").submit();
}
function dyhb_changefilesort(obj) {
	var file_id = obj.value;
	if (issetcheckbox('file_ids') == false) {
		alert(lang[38]);
		return;}
	if($('#filesort').val() == '')return;
	$("#prepare").val('changefilesort');
	$("#thefile").submit();
}
//����
function dyhb_trackbackaction(act){
	if (issetcheckbox('trackback_ids') == false) {
		alert(lang[41]);
		return;}
	if(act == 'del' && !confirm(lang[42])){return;}
	$("#prepare").val(act);
	$("#thetrackback").submit();
}
//����
function dyhb_mp3action(act){
	if (issetcheckbox('mp3_ids') == false) {
		alert(lang[43]);
		return;}
	if(act == 'del' && !confirm(lang[44])){return;}
	$("#prepare").val(act);
	$("#themp3").submit();
}
function dyhb_changemp3sort(obj) {
	var mp3_id = obj.value;
	if (issetcheckbox('mp3_ids') == false) {
		alert(lang[43]);
		return;}
	if($('#mp3sort').val() == '')return;
	$("#prepare").val('changemp3sort');
	$("#themp3").submit();
}
//����
function dyhb_cacheaction(act){
    $("#cachetype").val(act);
	$("#thecache").submit();
}

//�Զ�����
function autosave(){
	    var editor=$('#content').xheditor();
		var blog_id=$.trim($("#blog_id").val());
		var title = $.trim($("#title").val());
		var dateline=$.trim($("#dateline").val());
		var content=$.trim(content=editor.getSource());
		var from=$.trim($("#from").val());
		var fromurl=$.trim($("#fromurl").val());
		var urlname=$.trim($("#urlname").val());
		var user_id=$.trim($("#user_id").val());
		var keyword=$.trim($("#keyword").val());
		var description=$.trim($("#description").val());
		var sort_id=$.trim($("#sort_id").val());
		var thumb=$.trim($("#thumb").val());
		var password=$.trim($("#password").val());
		var islock=$("#hidemorecondition input[name=islock][checked]").val();
		var ispage=$("#hidemorecondition input[name=ispage][checked]").val();
		var istrackback=$("#hidemorecondition input[name=istrackback][checked]").val();
		var istop=$("#hidemorecondition input[name=istop][checked]").val();
		var is_autosave=$("#hidemorecondition input[name=is_autosave][checked]").val();
		var tag=$.trim($("#tag").val());
		if( content=='' ||is_autosave=='1'){setTimeout("autosave()",60000); return;}
        $.post( 
           '?action=log&do=save&type=autosave', 
          {       
			      blog_id:blog_id,
                  title:title,
			      dateline:dateline,
		          content:content,
		          from:from,
				  fromurl:fromurl,
                  user_id:user_id,	          
		          sort_id:sort_id,
		          thumb:thumb,
		          password:password,
		          isshow:'0',
		          islock:islock,
		          ispage:ispage,
				  tag:tag,
				  istop:istop,
		          istrackback:istrackback,
				  keyword:keyword,
				  description:description,
				  urlname:urlname
          },
         function (responseText) //�ش����� 
         {
		  $('#blog_id').val(responseText);
		  var digital = new Date();
		  var hours = digital.getHours();
		  var mins = digital.getMinutes();
		  var secs = digital.getSeconds();
		  $('#automessage').html(lang[45]+hours+":"+mins+":"+secs);
         } 
         );
	   setTimeout("autosave()",60000);
}