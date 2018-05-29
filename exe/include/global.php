<?php

//---------------------------
//-- constantes            --
//---------------------------

//constantes
define('ROOT', 'http://localhost/serveurs/1and1/resamalles/exe/');
define('PREFIX', 'pmo_');

//---------------------------
//-- fonctions             --
//---------------------------

//---------------------------
//-- formate_date_html
//---------------------------

function formate_date_html($arg){
$temp = explode("-",$arg);
$arg = $temp[2]."/".$temp[1]."/".$temp[0];
return $arg;
}//fin fonction formate_date_html

//---------------------------
//-- formate_date_mysql
//---------------------------

function formate_date_mysql($arg){

	$temp = explode("/", $arg);
	$arg = $temp[2]."-".$temp[1]."-".$temp[0];
	return $arg;

}//fin fonction formate_date_mysql

//---------------------------
//-- safemode_affichage_html
//---------------------------

function safemode_affichage_html($argument){

	$argument = stripslashes($argument);
	//$argument = utf8_encode($argument);
	return $argument;

}//fin function safemode_affichage_html

//--------------------------------
//-- safemode_affichage_textarea
//--------------------------------

function safemode_affichage_textarea($argument){

	$argument = stripslashes(htmlspecialchars($argument));
	return $argument;

}//fin function safemode_affichage_textarea

//---------------------------
//-- safemode_injection
//---------------------------

function safemode_injection($argument){

	$argument = addslashes($argument);
	$argument = utf8_decode($argument);
	return $argument;

}//fin function safemode_injection

//---------------------------
//-- encodeutf8
//---------------------------

function encodeutf8($argument){

	$argument = utf8_encode($argument);
	return $argument;

}//fin function encodeutf8

//---------------------------
//-- decodeutf8
//---------------------------

function decodeutf8($argument){

	$argument = utf8_decode($argument);
	return $argument;

}//fin function decodeutf8

//---------------------------
//-- get_video_link
//---------------------------

function get_video_url($video_tag, $provider){

	if( $provider == "youtube" ){
		return "https://www.youtube.com/watch?v=".$video_tag;
	}

	if( $provider == "vimeo" ){
		return "https://vimeo.com/".$video_tag;
	}

	if( $provider == "dailymotion" ){
		return "http://www.dailymotion.com/video/".$video_tag;
	}

}//fin function get_video_link

//---------------------------
//-- get_video_embed
//---------------------------

function get_video_embed($video_tag, $provider){

	if( $provider == "youtube" ){
		return "https://www.youtube.com/embed/".$video_tag."?autoplay=1";
	}

	if( $provider == "vimeo" ){
		return "https://player.vimeo.com/video/".$video_tag."?autoplay=1";
	}

	if( $provider == "dailymotion" ){
		return "//www.dailymotion.com/embed/video/".$video_tag."&related=1&autoplay=1";
	}

}//fin function get_video_embed

?>