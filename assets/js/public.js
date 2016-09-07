
$(document).ready(function(){
	// 輸入關鍵字
	$(".input_keyword").keyup(function(event) {
		var key_index = $(this).attr('key-index');
		$("#preview_key_" + key_index).html($(this).val());

		preview_modal_textin();
	});

	// 清空關鍵字
	$("#clear_input_keyword").click(function(event) {
		$(".input_keyword").val("");
		$(".preview_key").html("");
	});

	$("#openmodal").click(function(event) {
		preview_modal_textin();
	});

	$('#copymodal').on('shown.bs.modal', function (e) {
		$("#copyresult").select();
	})

	$("#opensavemodal").click(function(event) {
		preview_modal_textin()
		$("#save_result").html($("#preview_text").html());
	});

	// 輸入原文
	$("#createorigintext").keyup(function(event) {
		if ($(this).val() == "") {
			$("#createnewtext").val("");
			$("#createnewtext").attr('disabled', 'disabled');
		} else {
			$("#createnewtext").removeAttr('disabled');
		}
		var newtext = $("#createnewtext").val();
		var star_counting = 0;
		for(var i = 0; newtext.length > i; i++){
		    if(newtext.charAt(i) == "#"){
		    	++star_counting;
		    }
		}
		if (newtext == "" || star_counting == 0) {
			$("#createnewtext").val($(this).val());
		}
	});

	// 顯示已用掉多少個 *
	$("#createnewtext").keyup(function(event) {
		var newtext = $("#createnewtext").val();
		var star_counting = 0;
		for(var i = 0; newtext.length > i; i++){
		    if(newtext.charAt(i) == "#"){
		    	++star_counting;
		    }
		}
		$("#starnumber").html(star_counting.toString());
	});

	// 建立新複製文
	$("#submitnewtext").click(function(event) {
		var origintext = $("#createorigintext").val();
		var newtext = $("#createnewtext").val();
		var refactor_newtext = "";
		var font_counting = 0;
		var star_counting = 0;
		for(var i = 0; newtext.length > i; i++){
			++font_counting;
		    if(newtext.charAt(i) == "#"){
		    	++star_counting;
		    	refactor_newtext += "#" + padLeft(star_counting.toString(), 3);
		    } else {
		    	refactor_newtext += newtext.charAt(i)
		    }
		}
		if (font_counting < 10) {
			$("#errormsg").html("字數不太夠喔！至少 10 個字。");
			$("#errormsg").css('display', 'block');
			return;
		}
		if (star_counting > 20) {
			$("#errormsg").html("* 符號太多了啦！只能 10 個。");
			$("#errormsg").css('display', 'block');
			return;
		}
		if (star_counting == 0) {
			$("#errormsg").html("沒有 * 符號，不行喔。");
			$("#errormsg").css('display', 'block');
			return;
		}
		$("#errormsg").html("");
		$("#errormsg").hide();
		
		var query_data = {	newtext: refactor_newtext, 
							origintext: origintext, 
							grecaptcha: grecaptcha.getResponse()};
		$.ajax({
			type: "POST",
			url: "ajaxcreate",
			data: query_data,
			dataType: "json",
			success: function(data) {
				if( data['status'] == 'success' ){
					alert("儲存成功！");
					location.href = "textno/" + data['newtextid'];
				}
				else {
					$("#errormsg").html(data['errormsg']);
					$("#errormsg").css('display', 'inline-block');
				}
			}
		});
	});

	// 儲存文章
	$("#save_current_article").click(function(event) {
		var query_data = {	newarticle: $("#copyresult").val(),
							copytext_no: $("#copytext_no").val(),
							grecaptcha: grecaptcha.getResponse()};
		$.ajax({
			type: "POST",
			url: "ajaxsavearticle",
			data: query_data,
			dataType: "json",
			success: function(data) {
				if( data['status'] == 'success' ){
					alert("儲存成功！");
					location.reload()
				} else {
					$("#errormsg").html(data['errormsg']);
					$("#errormsg").css('display', 'inline-block');
				}
			}
		});
	});
});

function padLeft(str,lenght){
	if(str.length >= lenght)
		return str;
	else
		return padLeft("0" + str, lenght);
}

function preview_modal_textin() {
	$("#copyresult").val($("#preview_text").text());
}