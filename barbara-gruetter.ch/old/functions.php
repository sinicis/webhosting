<?php
	function menuf() {
	
		if (isset($_GET['menu'])) $menu=$_GET['menu'];
		
			switch($menu) {
			
				case 1 : ?>
					<img border="0" src="images/buttons/blank.png" />
					<img border="0" src="images/buttons/fc1_on.png" />
					<a href="?page=fc_beratung&menu=2"><img border="0" src="images/buttons/fc2.png" /></a>
					<a href="?page=fc_kosten&menu=3"><img border="0" src="images/buttons/fc3.png" /></a>
					<a href="?page=fc_um&menu=4"><img border="0" src="images/buttons/fc4.png" /></a>
					<a href="?page=kontakt&menu=5"><img border="0" src="images/buttons/fc5.png" /></a>
					<a href="?page=fc_links&menu=6"><img border="0" src="images/buttons/fc6.png" /></a>
					<img border="0" src="images/buttons/blank.png" /><?php
					break;
					
				case 2 : ?>
					<img border="0" src="images/buttons/blank.png" />
					<a href="?page=fc_home&menu=1"><img border="0" src="images/buttons/fc1.png" /></a>
					<img border="0" src="images/buttons/fc2_on.png" />
					<a href="?page=fc_kosten&menu=3"><img border="0" src="images/buttons/fc3.png" /></a>
					<a href="?page=fc_um&menu=4"><img border="0" src="images/buttons/fc4.png" /></a>
					<a href="?page=kontakt&menu=5"><img border="0" src="images/buttons/fc5.png" /></a>
					<a href="?page=fc_links&menu=6"><img border="0" src="images/buttons/fc6.png" /></a>
					<img border="0" src="images/buttons/blank.png" /><?php
					break;
					
				case 3 : ?>
					<img border="0" src="images/buttons/blank.png" />
					<a href="?page=fc_home&menu=1"><img border="0" src="images/buttons/fc1.png" /></a>
					<a href="?page=fc_beratung&menu=2"><img border="0" src="images/buttons/fc2.png" /></a>
					<img border="0" src="images/buttons/fc3_on.png" />
					<a href="?page=fc_um&menu=4"><img border="0" src="images/buttons/fc4.png" /></a>
					<a href="?page=kontakt&menu=5"><img border="0" src="images/buttons/fc5.png" /></a>
					<a href="?page=fc_links&menu=6"><img border="0" src="images/buttons/fc6.png" /></a>
					<img border="0" src="images/buttons/blank.png" /><?php
					break;
					
				case 4 : ?>
					<img border="0" src="images/buttons/blank.png" />
					<a href="?page=fc_home&menu=1"><img border="0" src="images/buttons/fc1.png" /></a>
					<a href="?page=fc_beratung&menu=2"><img border="0" src="images/buttons/fc2.png" /></a>
					<a href="?page=fc_kosten&menu=3"><img border="0" src="images/buttons/fc3.png" /></a>
					<img border="0" src="images/buttons/fc4_on.png" />
					<a href="?page=kontakt&menu=5"><img border="0" src="images/buttons/fc5.png" /></a>
					<a href="?page=fc_links&menu=6"><img border="0" src="images/buttons/fc6.png" /></a>
					<img border="0" src="images/buttons/blank.png" /><?php
					break;
					
				case 5 : ?>
					<img border="0" src="images/buttons/blank.png" />
					<a href="?page=fc_home&menu=1"><img border="0" src="images/buttons/fc1.png" /></a>
					<a href="?page=fc_beratung&menu=2"><img border="0" src="images/buttons/fc2.png" /></a>
					<a href="?page=fc_kosten&menu=3"><img border="0" src="images/buttons/fc3.png" /></a>
					<a href="?page=fc_um&menu=4"><img border="0" src="images/buttons/fc4.png" /></a>
					<img border="0" src="images/buttons/fc5_on.png" />
					<a href="?page=fc_links&menu=6"><img border="0" src="images/buttons/fc6.png" /></a>
					<img border="0" src="images/buttons/blank.png" /><?php
					break;
					
				case 6 : ?>
					<img border="0" src="images/buttons/blank.png" />
					<a href="?page=fc_home&menu=1"><img border="0" src="images/buttons/fc1.png" /></a>
					<a href="?page=fc_beratung&menu=2"><img border="0" src="images/buttons/fc2.png" /></a>
					<a href="?page=fc_kosten&menu=3"><img border="0" src="images/buttons/fc3.png" /></a>
					<a href="?page=fc_um&menu=4"><img border="0" src="images/buttons/fc4.png" /></a>
					<a href="?page=kontakt&menu=5"><img border="0" src="images/buttons/fc5.png" /></a>
					<img border="0" src="images/buttons/fc6_on.png" />
					<img border="0" src="images/buttons/blank.png" /><?php
					break;
											
				default : ?>
					<img border="0" src="images/buttons/blank.png" />
					<a href="?page=fc_home&menu=1"><img border="0" src="images/buttons/fc1.png" /></a>
					<a href="?page=fc_beratung&menu=2"><img border="0" src="images/buttons/fc2.png" /></a>
					<a href="?page=fc_kosten&menu=3"><img border="0" src="images/buttons/fc3.png" /></a>
					<a href="?page=fc_um&menu=4"><img border="0" src="images/buttons/fc4.png" /></a>
					<a href="?page=kontakt&menu=5"><img border="0" src="images/buttons/fc5.png" /></a>
					<a href="?page=fc_links&menu=6"><img border="0" src="images/buttons/fc6.png" /></a>
					<img border="0" src="images/buttons/blank.png" />
					<?php
					break;
				
				}
	}
	
		function menut() {
	
		if (isset($_GET['menu'])) $menu=$_GET['menu'];
		
			switch($menu) {
			
				case 1 : ?>
					<img border="0" src="images/buttons/blank.png" />
					<img border="0" src="images/buttons/tk1_on.png" />
					<a href="?page=tk_vorgehen&menu=2"><img border="0" src="images/buttons/tk2.png" /></a>
					<a href="?page=tk_kosten&menu=3"><img border="0" src="images/buttons/tk3.png" /></a>
					<a href="?page=tk_um&menu=4"><img border="0" src="images/buttons/tk4.png" /></a>
					<a href="?page=kontakt&menu=5"><img border="0" src="images/buttons/tk5.png" /></a>
					<a href="?page=tk_links&menu=6"><img border="0" src="images/buttons/tk6.png" /></a>
					<img border="0" src="images/buttons/blank.png" /><?php
					break;
				case 2 : ?>
					<img border="0" src="images/buttons/blank.png" />
					<a href="?page=tk_home&menu=1"><img border="0" src="images/buttons/tk1.png" /></a>
					<img border="0" src="images/buttons/tk2_on.png" />
					<a href="?page=tk_kosten&menu=3"><img border="0" src="images/buttons/tk3.png" /></a>
					<a href="?page=tk_um&menu=4"><img border="0" src="images/buttons/tk4.png" /></a>
					<a href="?page=kontakt&menu=5"><img border="0" src="images/buttons/tk5.png" /></a>
					<a href="?page=tk_links&menu=6"><img border="0" src="images/buttons/tk6.png" /></a>
					<img border="0" src="images/buttons/blank.png" /><?php
					break;
				case 3 : ?>
					<img border="0" src="images/buttons/blank.png" />
					<a href="?page=tk_home&menu=1"><img border="0" src="images/buttons/tk1.png" /></a>
					<a href="?page=tk_vorgehen&menu=2"><img border="0" src="images/buttons/tk2.png" /></a>
					<img border="0" src="images/buttons/tk3_on.png" />
					<a href="?page=tk_um&menu=4"><img border="0" src="images/buttons/tk4.png" /></a>
					<a href="?page=kontakt&menu=5"><img border="0" src="images/buttons/tk5.png" /></a>
					<a href="?page=tk_links&menu=6"><img border="0" src="images/buttons/tk6.png" /></a>
					<img border="0" src="images/buttons/blank.png" /><?php
					break;
				case 4 : ?>
					<img border="0" src="images/buttons/blank.png" />
					<a href="?page=tk_home&menu=1"><img border="0" src="images/buttons/tk1.png" /></a>
					<a href="?page=tk_vorgehen&menu=2"><img border="0" src="images/buttons/tk2.png" /></a>
					<a href="?page=tk_kosten&menu=3"><img border="0" src="images/buttons/tk3.png" /></a>
					<img border="0" src="images/buttons/tk4_on.png" />
					<a href="?page=kontakt&menu=5"><img border="0" src="images/buttons/tk5.png" /></a>
					<a href="?page=tk_links&menu=6"><img border="0" src="images/buttons/tk6.png" /></a>
					<img border="0" src="images/buttons/blank.png" /><?php
					break;
				case 5 : ?>
					<img border="0" src="images/buttons/blank.png" />
					<a href="?page=tk_home&menu=1"><img border="0" src="images/buttons/tk1.png" /></a>
					<a href="?page=tk_vorgehen&menu=2"><img border="0" src="images/buttons/tk2.png" /></a>
					<a href="?page=tk_kosten&menu=3"><img border="0" src="images/buttons/tk3.png" /></a>
					<a href="?page=tk_um&menu=4"><img border="0" src="images/buttons/tk4.png" /></a>
					<img border="0" src="images/buttons/tk5_on.png" />
					<a href="?page=tk_links&menu=6"><img border="0" src="images/buttons/tk6.png" /></a>
					<img border="0" src="images/buttons/blank.png" /><?php
					break;
				case 6 : ?>
					<img border="0" src="images/buttons/blank.png" />
					<a href="?page=tk_home&menu=1"><img border="0" src="images/buttons/tk1.png" /></a>
					<a href="?page=tk_vorgehen&menu=2"><img border="0" src="images/buttons/tk2.png" /></a>
					<a href="?page=tk_kosten&menu=3"><img border="0" src="images/buttons/tk3.png" /></a>
					<a href="?page=tk_um&menu=4"><img border="0" src="images/buttons/tk4.png" /></a>
					<a href="?page=kontakt&menu=5"><img border="0" src="images/buttons/tk5.png" /></a>
					<img border="0" src="images/buttons/tk6_on.png" />
					<img border="0" src="images/buttons/blank.png" /><?php
					break;
				default : ?>
					<img border="0" src="images/buttons/blank.png" />
					<a href="?page=tk_home&menu=1"><img border="0" src="images/buttons/tk1.png" /></a>
					<a href="?page=tk_vorgehen&menu=2"><img border="0" src="images/buttons/tk2.png" /></a>
					<a href="?page=tk_kosten&menu=3"><img border="0" src="images/buttons/tk3.png" /></a>
					<a href="?page=tk_um&menu=4"><img border="0" src="images/buttons/tk4.png" /></a>
					<a href="?page=kontakt&menu=5"><img border="0" src="images/buttons/tk5.png" /></a>
					<a href="?page=tk_links&menu=6"><img border="0" src="images/buttons/tk6.png" /></a>
					<img border="0" src="images/buttons/blank.png" />
					<?php
					break;
				}
	}
		
	function includingf() {
		if (isset($_GET['page'])) $page=$_GET['page'];
	
		switch($page) {
		
		case 'fc_home' :
			include 'includes/fc_home.php';
			break;	
		case 'fc_beratung' :
			include 'includes/fc_beratung.php';
			break;
		case 'fc_kosten' :
			include 'includes/fc_kosten.php';
			break;
		case 'kontakt' :
			include 'includes/kontakt.php';
			break;
		case 'fc_um' :
			include 'includes/fc_um.php';
			break;
		case 'fc_links' :
			include 'includes/fc_links.php';
			break;
		case 'tk_links' :
			include 'includes/tk_links.php';
			break;		
		default :
			include 'includes/fc_home.php';
			break;
		}
	}
	
	function includingt() {
		if (isset($_GET['page'])) $page=$_GET['page'];
	
		switch($page) {
		
		case 'tk_home' :
			include 'includes/tk_home.php';
			break;
		case 'tk_vorgehen' :
			include 'includes/tk_vorgehen.php';
			break;
		case 'tk_kosten' :
			include 'includes/tk_kosten.php';
			break;
		case 'kontakt' :
			include 'includes/kontakt.php';
			break;
		case 'tk_um' :
			include 'includes/tk_um.php';
			break;
		case 'tk_links' :
			include 'includes/tk_links.php';
			break;
		default :
			include 'includes/tk_home.php';
			break;
		}
	}
	

?>