<?php

function displayMenuPoint($page,$activePage,$text) {

	if ($page == $activePage) { ?>
		<div class='menupointActive'><?=$text?></div>
	<? } else { ?>
		<div class='menupoint'><a href='?top=<?=$page?>'><?=$text?></a></div>
	<? }

} // end of function displayMenuPoint

function displayMenu() {
?>
	<div id="menu" class="menu"> <?
	
		$activePage = isset($_GET['top']) ? $_GET['top'] : "home";
		displayMenuPoint("home",$activePage,"Startseite");
		displayMenuPoint("firma",$activePage,"Die Firma");
		displayMenuPoint("hochbau",$activePage,"Hochbau");
		displayMenuPoint("tiefbau",$activePage,"Tiefbau");
		displayMenuPoint("anumbau",$activePage,"Umbau/Anbau");
		displayMenuPoint("kundenmaurerei",$activePage,"Kundenmaurerei");
		displayMenuPoint("umgebung",$activePage,"Umgebungsarbeiten");
		displayMenuPoint("kontakt",$activePage,"Kontakt");		
	
	?></div>
<?
} // end of function displayMenu

function displayPage($activePage) {

	$legalPages = array("home","firma","hochbau","anumbau","kontakt","tiefbau","kundenmaurerei","umgebung");
	if ( in_array($activePage,$legalPages )) {
		include("includes/$activePage.php");
	} else {
		die;
	}

} // end of function displayPage



?>
