<?php
	//example usage
	require_once("youtubeGrabber.class.php");
	$you = new youtubeGrabber();
	$you->format("mp4");
	$you->filepath("");
	$you->filename("cat");
	$you->download("http://www.youtube.com/watch?v=J---aiyznGQ");
?>