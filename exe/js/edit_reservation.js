$(function(){

	$("#button_home").click(function() {
		document.location.href = "../../index.php";
	}); 

	$("#button_search").click(function() {
		search();
	});

	function seach(){

		if( $.trim($("#seach").val()) == "" ){
			alert("Vous devez saisir au moins un mot");
		}else{
			document.location.href = "index.php";
		}

	}

});