$(function(){
	var click_flg = false;
	$(".mod a").click(function(){
		if(click_flg == false){
			click_flg = true;
			$(".mod_photo_entry").before("<div class=\"mod_photo_entry\"><img src='"+$(this).attr("href")+"' alt='"+$(this).attr("title")+"'><p class=\"mod_th_font_small_c\">"+$(this).attr("title")+"</p>");
			$(".mod_photo_entry:last").stop(true, false).fadeOut("fast",function(){
			$(this).remove();click_flg = false;});
			return false;
		}
		else{return false;}
	});
});
