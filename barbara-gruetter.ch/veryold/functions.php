<?php

	function menuf() {
	
		if (isset($_GET['menu'])) $menu=$_GET['menu'];
		
			switch($menu) {
			
				case 1 : ?>
					<img border="0" src="images/buttons/blank.png" />
					<img border="0" src="images/buttons/fc1_on.png" />
					<a href="?page=fc_kosten&menu=2"><img border="0" src="images/buttons/fc2.png" /></a>
					<a href="?page=fc_um&menu=3"><img border="0" src="images/buttons/fc3.png" /></a>
					<a href="?page=kontakt&menu=4"><img border="0" src="images/buttons/fc4.png" /></a>
					<img border="0" src="images/buttons/blank.png" />
					<?php
					break;
					
				case 2 : ?>
					<img border="0" src="images/buttons/blank.png" />
					<a href="?page=fc_beratung&menu=1"><img border="0" src="images/buttons/fc1.png" /></a>
					<img border="0" src="images/buttons/fc2_on.png" />
					<a href="?page=fc_um&menu=3"><img border="0" src="images/buttons/fc3.png" /></a>
					<a href="?page=kontakt&menu=4"><img border="0" src="images/buttons/fc4.png" /></a>
					<img border="0" src="images/buttons/blank.png" />
					<?php
					break;
					
				case 3 : ?>
					<img border="0" src="images/buttons/blank.png" />
					<a href="?page=fc_beratung&menu=1"><img border="0" src="images/buttons/fc1.png" /></a>
					<a href="?page=fc_kosten&menu=2"><img border="0" src="images/buttons/fc2.png" /></a>
					<img border="0" src="images/buttons/fc3_on.png" />
					<a href="?page=kontakt&menu=4"><img border="0" src="images/buttons/fc4.png" /></a>
					<img border="0" src="images/buttons/blank.png" />
					<?php
					break;
					
				case 4 : ?>
					<img border="0" src="images/buttons/blank.png" />
					<a href="?page=fc_beratung&menu=1"><img border="0" src="images/buttons/fc1.png" /></a>
					<a href="?page=fc_kosten&menu=2"><img border="0" src="images/buttons/fc2.png" /></a>
					<a href="?page=fc_um&menu=3"><img border="0" src="images/buttons/fc3.png" /></a>
					<img border="0" src="images/buttons/fc4_on.png" />
					<img border="0" src="images/buttons/blank.png" />
					<?php
					break;
											
				default : ?>
					<img border="0" src="images/buttons/blank.png" />
					<a href="?page=fc_beratung&menu=1"><img border="0" src="images/buttons/fc1.png" /></a>
					<a href="?page=fc_kosten&menu=2"><img border="0" src="images/buttons/fc2.png" /></a>
					<a href="?page=fc_um&&menu=3"><img border="0" src="images/buttons/fc3.png" /></a>
					<a href="?page=kontakt&menu=4"><img border="0" src="images/buttons/fc4.png" /></a>
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
					<img border="0" src="images/buttons/tk2_on.png" />
					<a href="?page=tk_kosten&menu=2"><img border="0" src="images/buttons/tk3.png" /></a>
					<a href="?page=tk_um&menu=3"><img border="0" src="images/buttons/tk4.png" /></a>
					<a href="?page=kontakt&menu=4"><img border="0" src="images/buttons/tk5.png" /></a>
					<img border="0" src="images/buttons/blank.png" />
					<?php
					break;
				case 2 : ?>
					<img border="0" src="images/buttons/blank.png" />
					<a href="?page=tk_vorgehen&menu=1"><img border="0" src="images/buttons/tk2.png" /></a>
					<img border="0" src="images/buttons/tk3_on.png" />
					<a href="?page=tk_um&menu=3"><img border="0" src="images/buttons/tk4.png" /></a>
					<a href="?page=kontakt&menu=4"><img border="0" src="images/buttons/tk5.png" /></a>
					<img border="0" src="images/buttons/blank.png" />
					<?php
					break;
				case 3 : ?>
					<img border="0" src="images/buttons/blank.png" />
					<a href="?page=tk_vorgehen&menu=1"><img border="0" src="images/buttons/tk2.png" /></a>
					<a href="?page=tk_kosten&menu=2"><img border="0" src="images/buttons/tk3.png" /></a>
					<img border="0" src="images/buttons/tk4_on.png" />
					<a href="?page=kontakt&menu=4"><img border="0" src="images/buttons/tk5.png" /></a>
					<img border="0" src="images/buttons/blank.png" />
					<?php
					break;
				case 4 : ?>
					<img border="0" src="images/buttons/blank.png" />
					<a href="?page=tk_vorgehen&menu=1"><img border="0" src="images/buttons/tk2.png" /></a>
					<a href="?page=tk_kosten&menu=2"><img border="0" src="images/buttons/tk3.png" /></a>
					<a href="?page=tk_um&menu=3"><img border="0" src="images/buttons/tk4.png" /></a>
					<img border="0" src="images/buttons/tk5_on.png" />
					<img border="0" src="images/buttons/blank.png" />
					<?php
					break;
				default : ?>
					<img border="0" src="images/buttons/blank.png" />
					<a href="?page=tk_vorgehen&menu=1"><img border="0" src="images/buttons/tk2.png" /></a>
					<a href="?page=tk_kosten&menu=2"><img border="0" src="images/buttons/tk3.png" /></a>
					<a href="?page=tk_um&menu=3"><img border="0" src="images/buttons/tk4.png" /></a>
					<a href="?page=kontakt&menu=4"><img border="0" src="images/buttons/tk5.png" /></a>
					<img border="0" src="images/buttons/blank.png" />
					<?php
					break;
				}
	}
		
	function includingf() {
		if (isset($_GET['page'])) $page=$_GET['page'];
	
		switch($page) {
		
			
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
		
		default :
			include 'includes/fc.php';
			break;
		}
	}
	
	function includingt() {
		if (isset($_GET['page'])) $page=$_GET['page'];
	
		switch($page) {
		
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
		default :
			include 'includes/tk.php';
			break;
		}
	}
	
?>