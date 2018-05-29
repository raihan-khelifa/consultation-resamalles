$(function(){

	//globals
	g_id_malle = null;
	g_jeton_malle = null;

	$("#user_pwd").keyup(function(e) {
		if(e.keyCode == 13){login();}
	});

	$("#button_login").click(function() {
		login();
	});

	$("#button_logout").click(function() {
		document.location.href = "logout.php";
	});

	function login(){

		if( $.trim( $("#user_mail").val() ) == "" ){

			alert("Email vide");
			$("#user_mail").focus();

		}else if( $.trim( $("#user_pwd").val() ) == "" ){

			alert("Mot de passe vide");
			$("#user_pwd").focus();

		}else{

			var client_email = $.trim( $("#user_mail").val() );
			var client_pwd = $.trim( $("#user_pwd").val() );

			$.ajax({
				url: "ajax.login.php",
				type: "post",
				data: { 
					client_email : client_email,
					client_pwd : client_pwd
				},
				success: function(response) {
				    response = $.trim(response);

				   	if( response == "incorrect_user" ){

				   		if( confirm("Cet email ne figure pas dans notre base clients. Voulez-vous créer un compte maintenant ? C'est gratuit.") ){
				   			document.location.href = "http://crdp-cyberservices.ac-clermont.fr";
				   		}

				   	}else if( response == "incorrect_pwd" ){

				   		alert("Mot de passe incorrect.");
				   		$("#user_pwd").val("");

				   	}else if( response == "never_subscribed" ){

				   		if( confirm("Vous n'êtes pas abonné à nos services. Voulez-vous vous abonner maintenant ?") ){
				   			document.location.href = "http://crdp-cyberservices.ac-clermont.fr";
				   		}

				   	}else if( response == "no_previous_subscription" || response == "no_current_subscription" ){

				   		if( confirm("Vous n'êtes plus abonné à nos services depuis quelques temps. Voulez-vous vous abonner maintenant ?") ){
				   			document.location.href = "http://crdp-cyberservices.ac-clermont.fr";
				   		}

				   	}else if( response == "admin" || response == "ok" ){

				   		document.location.href = "index.php";

				   	}
				},
				error: function(xhr, status, error){
					alert("error 777987 " + error);
				}
			});

		}

	}

	$("#button_search").click(function() {
		search();
	});

	$("#search").keyup(function(e) {
		if(e.keyCode == 13){search();}
	});

	function search(){

		var search = $.trim( $("#search").val() );

		if( search != "" ){

			$.ajax({
				url: "php/document/ajax.search.php",
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
			url: "php/document/ajax.see_all.php",
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
		$("#popup_document iframe").attr("src", "php/document/iframe.document_open.php?id_malle=" + g_id_malle + "&jeton_malle=" + g_jeton_malle);
	});

	$("#popup_document").on("click", ".button_close_popup", function(){
		$("#black_screen").hide();
		$("#popup_document").html('<div class="button_close_popup"><i class="fa fa-times-circle"></i></div><iframe></iframe>');
		$("#popup_document").hide();
	});

	$("body").on("click", "#button_add_document", function(){
		$("#black_screen").show();
		$("#popup_document").center().show();
		$("#popup_document iframe").attr("src", "php/document/iframe.document_add.php");
	});

	$("#result_zone").on("click", ".button_modify_document", function(e){
		e.stopPropagation();
		g_id_malle = $(this).parent().parent().parent().attr("data_id_malle");
		g_jeton_malle = $(this).parent().parent().parent().attr("data_jeton_malle");
		$("#black_screen").show();
		$("#popup_document").center().show();
		$("#popup_document iframe").attr("src", "php/document/iframe.document_modify.php?id_malle=" + g_id_malle + "&jeton_malle=" + g_jeton_malle);
	});

	$("#result_zone").on("click", ".button_delete_document", function(e){
		e.stopPropagation();

		if( confirm("Voulez-vous vraiment supprimer cette malle et toutes ses réservations ?") ){

			g_id_malle = $(this).parent().parent().parent().attr("data_id_malle");
			g_jeton_malle = $(this).parent().parent().parent().attr("data_jeton_malle");
			
			$.ajax({
			url: "php/document/ajax.delete_document.php",
			type: "post",
			data: { 
				id_malle : g_id_malle,
				jeton_malle : g_jeton_malle
			},
			success: function(response) {
			    response = $.trim(response);
			    $(".document_zone[data_id_malle='"+g_id_malle+"']").remove();
			},
			error: function(xhr, status, error){
				alert("error 96532 " + error);
			}
		});

		}

	});

	$("#button_my_reservation").click(function() {
		$("#black_screen").show();
		$("#popup_document").center().show();
		$("#popup_document iframe").attr("src", "php/document/iframe.my_reservation.php");
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