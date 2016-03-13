<?php

function legalPage($activeTop,$activeSub) {
	
	$legality = "yes";
	
	$legalTops = array("landing","tk","fc","p","start");
	$legalSubs = array("home","vorgehen","beratung","kosten","person","kontakt","links","coaching","");
	
	if (!in_array($activeTop,$legalTops)) {
		$legality = "no";
	} elseif (!in_array($activeSub,$legalSubs)) {
		$legality = "no";
	}
	
	return $legality;
	
} // end of function legal Page

function displayMenuPoint($activeTop,$page,$activeSub,$text,$pos) {

	if ((($pos == "One") || ($pos == "Last")) && ($activeTop != "tk")) {
		$size = "Five";
	}
	if ($page == $activeSub) { ?>
		<div class='menuPoint<?=$pos?><?=$size?>Active'><?=$text?></div>
	<? } else { ?>
		<div class='menuPoint<?=$pos?><?=$size?>'><a href='?top=<?=$activeTop?>&sub=<?=$page?>'><?=$text?></a></div>
	<? }

} // end of function displayMenuPoint

function displayMenu($activeTop,$activeSub) {
	
	$legality = legalPage($activeTop,$activeSub);
	$showMenuBy = array("tk","fc","p");
	
	if ((in_array($activeTop,$showMenuBy)) && ($legality == "yes")) {
		
		$activeSub = isset($_GET['sub']) ? $_GET['sub'] : "home";
		displayMenuPoint($activeTop,"home",$activeSub,"Home","One");
		if ($activeTop == "fc") {
			displayMenuPoint($activeTop,"beratung",$activeSub,"Beratung","");
		} elseif ($activeTop == "tk") {
			displayMenuPoint($activeTop,"vorgehen",$activeSub,"Vorgehen","");
		} else {
			displayMenuPoint($activeTop,"coaching",$activeSub,"Coaching","");
		}
		displayMenuPoint($activeTop,"kosten",$activeSub,"Kosten","");
		displayMenuPoint($activeTop,"person",$activeSub,"&Uuml;ber mich","");
		$pos = $activeTop != "tk" ? "Last" : "";
		displayMenuPoint($activeTop,"kontakt",$activeSub,"Kontakt",$pos);
		if ($activeTop == "tk") {
			displayMenuPoint($activeTop,"links",$activeSub,"Links","");	
		}
		
	} // end of if right page

} // end of function displayMenu

function displayPage($activeTop,$activeSub) {

	$legality = legalPage($activeTop,$activeSub);

	if ($legality == "no") {
		include("includes/error.php");
	} else {
		$pathChangeBy = array("tk","fc","p");
		if (in_array($activeTop,$pathChangeBy)) {
			$path = "includes/" . $activeTop . "/" . $activeSub . ".php";
			include($path);
		} else {
			$path = "includes/" . $activeTop . ".php";
			include($path);
		}
	} // end of if page is legal

} // end of function displayPage

function displayTitle($activeTop) {

	if (($activeTop == "start") || ($activeTop == "landing"))  {
		echo "<h1>Tiere als Wegbegleiter</h1>";
	} elseif ($activeTop == "tk") {
		echo "<h1>Tierkommunikation</h1>";
	} elseif ($activeTop == "fc") {
		echo "<h1>Foodcoaching</h1>";
	} elseif ($activeTop == "p") {
		echo "<h1>Pferdegest&uuml;tztes Coaching</h1>";
	}

} // end of function displayTitle

function displayLinks($activeTop) {
			
	$legality = legalPage($activeTop,$activeSub);

	if ($legality == "no") {
		include("includes/error.php");
	} else {
		
		// check what's left and what's right
		if ( $activeTop == "tk" ) {
			$left = "p";
			$right = "fc";
		} elseif ( $activeTop == "fc" ) {
			$left = "tk";
			$right = "p";
		} elseif ( $activeTop == "p" ) {
			$left = "fc";
			$right = "tk";
		}

		echo "<table width='780px'>";
			echo "<tr>";
				echo "<td width='330px'><a href='?top=" . $left . "'><div id='" . $left . "Left' class='" . $left . "Left'></div></a></td>";
				echo "<td width='35px'><a href='index.php'><div class='goHome' id='goHome'></div></a></td>";
				echo "<td width='10px'></td>";
				echo "<td width='35px'><a href='?top=landing'><div class='goLanding' id='goLanding'></div></a></td>";
				echo "<td width='350px'><a href='?top=" . $right . "'><div id='" . $right . "Right' class='" . $right . "Right'></div></a></td>";
			echo "</tr>";
		echo "</table>";
		
	} // end of if page is legal

} // end of function displayLinks

?>