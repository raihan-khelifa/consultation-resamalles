$(function(){

	//globals
	g_id_malle = null;
	g_jeton_malle = null;


	$("#button_search").click(function() {
		search();
	});

	$("#search").keyup(function(e) {
		if(e.keyCode == 13){search();}
	});

	see_all();
	function see_all(){
			
			
		$.ajax({
			url: "php/document/ajax.see_all_read_only.php",
			type: "post",
			success: function(response) {
			    response = $.trim(response);
			    $("#result_zone").html(response);
			},
			error: function(xhr, status, error){
				alert("error 96532 " + error);
			}
		});
	}



	function search(){

		var search = $.trim( $("#search").val() );

		if( search != "" ){

			$.ajax({
				url: "php/document/ajax.search_read_only.php",
				type: "post",
				data: { 
					search : search
				},
				success: function(response) {
				    response = $.trim(response);
				    //alert(response)
				    $("#result_zone").html(response);
				},
				error: function(xhr, status, error){
					alert("error 778787 " + error);
				}
			});

		}

	}

	$("#button_see_all").click(function() {

		$.ajax({
			url: "php/document/ajax.see_all_read_only.php",
			type: "post",
			success: function(response) {
			    response = $.trim(response);
			    $("#result_zone").html(response);
			},
			error: function(xhr, status, error){
				alert("error 96532 " + error);
			}
		});

	});
	$("#result_zone").on("mouseover", ".document_zone", function(){
		$(this).find(".menu").css("display", "inline-block");
	});

	$("#result_zone").on("mouseout", ".document_zone", function(){
		$(this).find(".menu").css("display", "none");
	});

	$("#result_zone").on("click", ".document_zone", function(){
		g_id_malle = $(this).attr("data_id_malle");
		g_jeton_malle = $(this).attr("data_jeton_malle");
		$("#black_screen").show();
		$("#popup_document").center().show();
		$("#popup_document iframe").attr("src", "php/document/iframe.document_open_read_only.php?id_malle=" + g_id_malle + "&jeton_malle=" + g_jeton_malle);
	});

	$("#popup_document").on("click", ".button_close_popup", function(){
		$("#black_screen").hide();
		$("#popup_document").html('<div class="button_close_popup"><i class="fa fa-times-circle"></i></div><iframe></iframe>');
		$("#popup_document").hide();
	});

	$("#button_todolist").click(function() {
		window.open("todo_list.php?date=" + $("#input_date_tournee").val(), "todo_list");
	});

	$("#input_date_tournee").keyup(function(e) {
		if(e.keyCode == 13){
			window.open("todo_list.php?date=" + $("#input_date_tournee").val(), "todo_list");
		}
	});

	//--------------------------------------
	//-- functions
	//--------------------------------------

	$.fn.center = function () {
		return this.css({
		'left': ($(window).width() / 2) - $(this).width() / 2,
		'top': ($(window).height() / 2) - $(this).height() / 2,
		'position': 'fixed'
		});
	}

});