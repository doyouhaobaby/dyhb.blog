//show and hide a div
function showdiv(id){
  try{
    var dyhb_div=document.getElementById(id);
    if(dyhb_div){
      if(dyhb_div.style.display=='none'){
        dyhb_div.style.display='block';
      }else{
        dyhb_div.style.display='none';
      }
    }
  }catch(e){}
}
function displayToggle(id, date){
	$("#"+id).toggle();
	$.cookie('dyhb_'+id,$("#"+id).css('display'),{expires:date});
}
function addfile(imgurl,blankurl,filename,width,height,file_id){
	var editor=$('#content').xheditor();
	editor.pasteHTML('<a target=\"_blank\" href=\"'+blankurl+'\" id=\"file_'+file_id+'\"><img src=\"'+imgurl+'\" title=\"'+lang[7]+filename+'\" width=\"'+width+'\" height=\"'+height+'\" border=\"0\"></a>');
}
function addfiley(fileurl,filename,file_id,file_size,file_time,file_down){
	var editor=$('#content').xheditor();
	editor.pasteHTML('<div class=\"filedown\"><a target=\"_blank\" title=\"'+lang[8]+''+filename+'\" href=\"'+fileurl+'\" id=\"file_'+file_id+'\">'+filename+'</a><br>'+lang[9]+'('+file_time+')&nbsp;'+lang[10]+'('+file_size+')</div>');
}
function newpage(){
	var editor=$('#content').xheditor(); 
	editor.pasteHTML('[newpage]');
}
function insertInput (thevalue, InputId){
	$("#"+InputId).val(thevalue);
}
function insertTag (tag){
	var taginput = $("#tag").val();
	if(taginput == ''){
		taginput += tag;
	}else{
		var n = '，' + tag;
		taginput += n;
	}
	$("#tag").val(taginput);
}
function displayMenu(){
   var menu=new Array('index','log','sort','comment','photo','user','link','template','backup','option');
   for(var i in menu){
       $("#sidebar_"+menu[i]).css('display', $.cookie('dyhb_sidebar_'+menu[i]) ? $.cookie('dyhb_sidebar_'+menu[i]) : '');
   }
}
//日志发布验证
function checkform() {
if ($('#title').val() == "") {
    alert(lang[11]);
    return false;
}
var editor=$('#content').xheditor();
var content=editor.getSource();
  if (content == "") {
      alert(lang[12]);
      return false;
  }
}