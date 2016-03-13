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
include('functions.php'); 
require_once('/home/ironsmit/sec-data/schlosser-stubenwagen.ch/.sec_data.php'); 

// set charset
$sqlChar    = "set names utf8";
$dbcon      = dbConnect(DBUSER,DBPW);
$dbcon->query($sqlChar);
mysqli_set_charset($dbcon, 'utf8');
dbClose($dbcon);

# Debug-Options
error_reporting(E_ALL);
ini_set("display_errors", 1);

# Session
session_start();
?>
	
<body>
	<div id="mainWrapper" class="mainWrapper">
		
        <div id="header" class="header">
            <center>
            <img src='img/logo-web.png' height='100px' />
            <!-- Schlossers Stubenwagen-Vermietung --> 
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
                    <? displayMenu("ui"); ?>
                </div>	

            <!-- // Favoriten-DIV                      // -->
                <!-- <div id='favs' class='favs'> -->
                    <?/*
                    echo "<font color='#f08080'><b>Ihre Favoriten</b></font>";
                    if ( $_SESSION['favCount'] == 0 ) {
                        echo "<p><i>Sie haben noch keine Favoriten ausgew&auml;hlt...</i></p>";
                    } else {
                        printFavs();
                    }*/
                    ?>
                <!-- <div> -->

            </div>
        
            <div class="contentRightWrapper">
            
                <div id="breadcrumps" class="breadcrumps">
                    <!-- Startseite / <a href="index_alt.php" class="not_menu">Stubenwagen</a> -->
                </div>
                
                <div id="content" class="content">
                
                    <? // include content
                    $top = isset($_GET['top']) ? $_GET['top'] : "home";
                    displayPage("ui",$top); 
                    ?>
                    
                </div>
            </div>

        </div>
        <div id="footer" class="footer">
            <?php echo "made with <3 by <a href='http://ironsmith.ch' class='ironsmith' target='_blank'>ironsmith.ch</a>"; ?>
        </div>

        
    </div>
		
<? include('footer.ink'); ?>

</body>
