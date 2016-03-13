<!doctype html>
<html lang="de">
	<head>

	
	  <meta charset="utf-8">
	
	  <title>Saurer-Bau GmBH</title>
	  <meta name="description" content="Saurer-Bau GmBH">
	  <meta name="author" content="Simon Isenschmid">
	
	  <link rel="stylesheet" href="css/style.css">
	
	  <!--[if lt IE 9]>
	  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	  <![endif]-->
  
	  
	  
	</head>

	<body>
	<div id="mainWrapper" class="mainWrapper">
		<div id="wrapper" class="wrapper">
		
			<?php include 'functions.php'; ?>
			
			<div id="header" class="header">
				<center>
				<img src='images/logo.jpg' width='600x' />
				</center>
			</div>
	
			<?php displayMenu(); ?>			
			
			<div id="content" class="content">
				
				<?
				$activePage = (isset($_GET['top'])) ? $_GET['top'] : "home";
				displayPage($activePage);
				?>
			
			</div>
			
			<div id="footer" class="footer">
			</div>
		</div>
	
		<!-- download it! html boilerplate; and reset till... -->
		

	
	</body>
</html>
