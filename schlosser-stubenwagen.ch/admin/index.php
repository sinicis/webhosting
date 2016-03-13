<?php 

######################################################
#                                                    #
#           WWW.SCHLOSSER-STUBENWAGEN.CH             #
#               made with <3 by Simu                 #
#                                                    #
######################################################

# Include Header-File
include('header.ink');

# Include Sec-File & Functions
include('../functions.php'); 
require_once('/home/ironsmit/sec-data/schlosser-stubenwagen.ch/.sec_data.php'); 

# Debug-Options
error_reporting(E_ALL);
ini_set("display_errors", 1);

# Start Session
session_start();
if (!isset($_SESSION['state'])) {
    $_SESSION['state'] = 'fuckoff';
    $_SESSION['user'] = '';
    $_SESSION['action'] = '';
}

# Check Login
if ((isset($_GET['action'])) && ($_GET['action'] == "send")) {
    checkLogin($_POST['user'],$_POST['pw']);
}

# Check Logout
if ((isset($_GET['action'])) && ($_GET['action'] == "logout")) {
    logout();
}


?>
	
<body>
	<div id="mainWrapper" class="mainWrapper">
		<!--<div id="wrapper" class="wrapper">-->
		
			<div id="headerAdmin" class="headerAdmin">
				<center>
                    <img src='../img/logo-web.png' height='100px' />
				</center>
			</div>

			<div id="subheader" class="subheader">
				Der zuverl&auml;ssige Partner mit grossem Interesse am Wohl Ihres Kindes - Nur wer gut schl&auml;ft, wird einmal gross und stark....
			</div>

			<div id="contentWrapper" class="contentWrapper">
			
                <div class="contentLeftWrapper">

                <!-- // Menu-DIV                      // -->
                    <div id='menu' class='menu'>
                        <div class='menupointBlank'></div>
                        <? displayMenu("admin"); ?>
                    </div>

            </div>

				
            <div class="contentRightWrapper">
				
					<div id="breadcrumps" class="breadcrumps">
					</div>
					
					<div id="content" class="content">
					
						<? // include content
						$top = isset($_GET['top']) ? $_GET['top'] : "home";
						displayPage("admin",$top); 
						?>
						
					</div>
				</div>

			</div>
			
            <div id="footer" class="footer">
                <?php echo "made with <3 by <a href='http://ironsmith.ch' class='ironsmith' target='_blank'>ironsmith.ch</a>"; ?>
            </div>
    <!--</div>-->
		
		</div>
		
<? include('footer.ink'); ?>
