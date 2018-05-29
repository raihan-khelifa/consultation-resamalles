$(function(){

	g_id_malle = null;
	g_jeton_malle = null;
	g_id_periode = null;

	$(".button_reservation").click(function() {

		g_id_malle = $(this).parent().parent().parent().attr("data_id_malle");
		g_jeton_malle = $(this).parent().parent().parent().attr("data_jeton_malle");
		g_id_periode = $(this).parent().parent().attr("data_id_periode");
		var status = $(this).parent().parent().parent().attr("data_user_status");

		if( status == "client" ){
			document.location.href = "execute.reservation1.php?id_malle="+g_id_malle+"&jeton_malle="+g_jeton_malle+"&id_periode="+g_id_periode;
		}else{
			document.location.href = "execute.choose_client.php?id_malle="+g_id_malle+"&jeton_malle="+g_jeton_malle+"&id_periode="+g_id_periode;
		}

	});	

	$("#button_back_to_document").click(function() {

		window.history.back();

	});	

	var getUrlParameter = function getUrlParameter(sParam) {
		var sPageURL = decodeURIComponent(window.location.search.substring(1)), sURLVariables = sPageURL.split('&'), sParameterName, i;

		for (i = 0; i < sURLVariables.length; i++) {

			sParameterName = sURLVariables[i].split('=');

			if (sParameterName[0] === sParam) {
				return sParameterName[1] === undefined ? true : sParameterName[1];
			}
		}
	};

	$("#client_zone #id_client").change(function(){

		document.location.href = "execute.reservation1.php?id_malle="+getUrlParameter('id_malle')+"&jeton_malle="+getUrlParameter('jeton_malle')+"&id_periode="+getUrlParameter('id_periode')+"&id_client="+$(this).val();

	});

	$("#college_retrait").change(function(){
		$("#retrait_college").prop("checked", true)
	});	

	$("#college_retour").change(function(){
		$("#retour_college").prop("checked", true)
	});	

	$("#form_reservation1").submit(function(){

		var retrait = $("input[name=retrait]:checked").val();
		var college = $('#college_retrait').val();

		if( retrait=="college" && college=="0" ){

			alert("Vous devez sélectionnez un collège...");
			return false;

		}else{

			return true;

		}

	});

	$("#form_reservation2").submit(function(){

		var retrait = $("input[name=retour]:checked").val();
		var college = $('#college_retour').val();

		if( retrait=="college" && college=="0" ){

			alert("Vous devez sélectionnez un collège...");
			return false;

		}else{

			return true;

		}

	});

	$("#button_back_reservation1").click(function() {
		document.location.href = "execute.reservation1.php";
	});	

	$("#button_cancel_reservation").click(function() {

		if( confirm("Voulez-vous vraiment annuler la réservation en cours ?") ){

			$.ajax({
				url: "ajax.reservation_delete.php",
				type: "post",
				success: function(response) {
				    response = $.trim(response);
				    var temp = response.split(";")
				    var id_malle = temp[0];
				    var id_periode = temp[1];
				    var counter = window.parent.return_reservation_counter();
			    	window.parent.refresh_my_reservation_counter( counter - 1 );
			    	window.parent.refresh_available_flag( id_malle, id_periode, "yes" );
				    window.parent.close_popup();
				},
				error: function(xhr, status, error){
					alert("error 6658 " + error);
				}
			});

		}

	});


	$("#reservation_list .button_cancel_reservation").click(function() {

		if( confirm("Voulez-vous vraiment annuler la réservation en cours ?") ){

			var id_reservation = $(this).prev().attr("data_id_reservation");
			var id_malle = $(this).prev().attr("data_id_malle");
			var id_periode = $(this).prev().attr("data_id_periode");
			var reservation_zone = $(this).parent();

			$.ajax({
				url: "ajax.my_reservation_delete.php",
				type: "post",
				data: { 
					id_reservation : id_reservation
				},
				success: function(response) {
				    response = $.trim(response);
				    if(response == "ok"){
				    	reservation_zone.remove();
				    	var counter = $(".my_reservation_counter").html();
				    	$(".my_reservation_counter").html( counter - 1 );
				    	window.parent.refresh_my_reservation_counter( counter - 1 );
				    	window.parent.refresh_available_flag( id_malle, id_periode, "yes" );
				    }				    
				},
				error: function(xhr, status, error){
					alert("error 78787 " + error);
				}
			});

		}		

	});
	 	

});