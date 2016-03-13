<?php
include 'functions.php';
$activeTop = (isset($_GET['top'])) ? $_GET['top'] : "start";
$activeSub = (isset($_GET['sub'])) ? $_GET['sub'] : "home";
?>

<!DOCTYPE html>

<html lang="de">
<head>
	<title>Barbara Gr&uuml;tter - Tierkommunikation - Food Coaching - Pferdegest&uuml;tztes Coaching</title>
	<meta name="description" content="Barbara Grütter - Tierkommunikation und Food Coaching" />
	<meta name="keywords" content="barbara grütter gruetter tierkommunikation hund katze food coaching pferd gesundheit tierhilfe tier tiere tierkrankheit krank gesundheit thun schweiz bern hünibach thunersee beratung tierfreund kommunikation kommunikatorin tierkommunikatorin" />
	<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
	
	<div class="top">
		<? displayTitle($activeTop); ?>
	</div>
	
	<div class="all">
		<? displayMenu($activeTop,$activeSub); ?>
		
		<div class="content">
			<? displayPage($activeTop,$activeSub); ?>	
		</div>
		
		<? if (( $activeTop == "tk" ) | ( $activeTop == "fc" ) | ( $activeTop == "p" )) { ?>
			<div class="links">
				<? displayLinks($activeTop); ?>
			</div>		
		<? } ?>
	</div>



	<div class="footer">
		<h3>Barbara Gr&uuml;tter &bull; 033 243 52 56 &bull; info@barbara-gruetter.ch</h3>
	</div>
	<div class="ironsmith">
		&copy; <a href='http://www.ironsmith.ch'>ironsmith Webdesign</a> 2014
	</div>
</body>
</html>