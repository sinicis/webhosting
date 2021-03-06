<?php


// Include Admin Functions
if (( substr( $_SERVER['REQUEST_URI'], 0, 10 ) == "/dev/admin" ) || ( substr( $_SERVER['REQUEST_URI'], 0, 6 ) == "/admin") ) {
    include('/home/ironsmit/sec-data/schlosser-stubenwagen.ch/.adminFunctions.php');
}

##########################################
#                                        #
#              UTF8-CONVERTER            #
#                                        #
##########################################

function utf8_converter($array) {
    array_walk_recursive($array, function(&$item, $key){
        if(!mb_detect_encoding($item, 'utf-8', true)){
                $item = utf8_encode($item);
        }
    });
 
    return $array;
}


##########################################
#                                        #
#         VERZEICHNIS-LOESCHER           #
#                                        #
##########################################

function recurseRmdir($dir) {
  $files = array_diff(scandir($dir), array('.','..'));
  foreach ($files as $file) {
    (is_dir("$dir/$file")) ? recurseRmdir("$dir/$file") : unlink("$dir/$file");
  }
  return rmdir($dir);
}


##########################################
#                                        #
#              LOGIN/LOGOUT              #
#                                        #
##########################################

function checkLogin($user,$pw) {
    
    if (($pw == ADMPW) && ($user == ADMUSER)) {
        $_SESSION['state'] = "loggedin";
        $_SESSION['user'] = $user;
        $_SESSION['showRId'] = "";
        $_SESSION['showRName'] = "Alle anzeigen";
        $_SESSION['showCId'] = "";
        $_SESSION['showCName'] = "Alle anzeigen";

    } else {
        // thinkabout!
    }
}

function logout() {

    session_unset();
    session_destroy();

    session_start();
    $_SESSION['state'] = 'fuckoff';

} // eof logout


##########################################
#                                        #
#                DATABASE                #
#                                        #
##########################################

function dbConnect($user,$pw) {

	$host 	= 'localhost';

	$dbcon 	= new mysqli($host,$user,$pw,DBNAME);

    if($dbcon->connect_errno > 0){
        die('Verbindungsfehler: ' . $db->connect_erro );
    }

	return $dbcon;
             
} // eof dbConnect

function dbClose($dbcon) {

    $dbcon->close();
    
} // eof dbClose 

##########################################
#                                        #
#                  MENU                  #
#                                        #
##########################################

function displayMenuPoint($page,$activePage,$activeSub,$text,$level) {

    // check if top or sub
    $class 	= $level == "top" ? "menupoint" : "subMenupoint";
    $link	= $level == "top" ? "?top=$page" : "?top=$activePage&sub=$page";

    // add menu to link
    $link   = !isset($_GET['menu']) ? $link : $link . "&menu=" . $_GET['menu'];

    if (( $page == $activePage) || ( $page == $activeSub )) { 
        echo "<div class='" . $class . "Active'><a href='" . $link . "'>" . $text . "</a></div>";
    } else { 
        echo "<div class='" . $class . "'><a href='" . $link . "'>" . $text . "</a></div>";
    }

} // end of function displayMenuPoint

function displayMenu($area) {

    // get current page
    $activePage 	= isset($_GET['top']) ? $_GET['top'] : "home";
    $activeSub 		= isset($_GET['sub']) ? $_GET['sub'] : "none";

    # Menu for UI
    if ($area == "ui") {
        displayMenuPoint("home",$activePage,$activeSub,"Startseite","top");
        displayMenuPoint("me",$activePage,$activeSub,"&Uuml;ber mich","top");
        displayMenuPoint("wagen",$activePage,$activeSub,"Stubenwagen","top");
            if ( $activePage == "wagen") {
                displaySubMenu($activePage,$activeSub);
            } // end of submenu-points
        displayMenuPoint("shop",$activePage,$activeSub,"Zubeh&ouml;r (Shop)","top");
            if ( $activePage == "shop") {
                displaySubMenu($activePage,$activeSub);
            } // end of submenu-points
        displayMenuPoint("info",$activePage,$activeSub,"Mietpreise / Infos","top");	
            if ( $activePage == "info") {
                displaySubMenu($activePage,$activeSub);
            }
        //displayMenuPoint("download",$activePage,$activeSub,"Downloads","top");		
        displayMenuPoint("links",$activePage,$activeSub,"Links","top");		
        displayMenuPoint("kontakt",$activePage,$activeSub,"Kontakt","top");		
        
    # Admin-Menu
    } elseif ($area == "admin") {

        // change default page to dash
        $activePage = $activePage == "home" ? "dash" : $activePage;

        # Check if logged in
        if ( $_SESSION['state'] == "loggedin" ) {

            displayMenuPoint("dash",$activePage,$activeSub,"Dashboard","top");	
            displayMenuPoint("bestellung",$activePage,$activeSub,"Bestellungen","top");		
            if ( $activePage == "bestellung") {
                displaySubMenu($activePage,$activeSub);
            } // end of submenu-points
            displayMenuPoint("reservation",$activePage,$activeSub,"Reservationen","top");		
            if ( $activePage == "reservation") {
                displaySubMenu($activePage,$activeSub);
            } // end of submenu-points
            displayMenuPoint("inventar",$activePage,$activeSub,"Inventar","top");		
            if ( $activePage == "inventar") {
                displaySubMenu($activePage,$activeSub);
            } // end of submenu-points
  //          displayMenuPoint("stats",$activePage,$activeSub,"Statistiken","top");		
            echo "<div class='menupoint'><a href='http://webmail.cyon.ch' target='_blank'>Webmail</a></div>";
            displayMenuPoint("home&action=logout",$activePage,$activeSub,"Logout","top");

        } // eoi loggedin

    } //eow menu

} // end of function displayMenu

function displaySubMenu($activePage,$activeSub) {

    $area = substr( $_SERVER['REQUEST_URI'], 0, 10 ) == "/dev/admin" ? "admin" : "user";

	// Stubenwagen
	if ( $activePage == "wagen" ) {

        // generate link
        $link	= !isset($_GET['sub']) ? "?top=$activePage" : "?top=$activePage&sub=$activeSub";

        // show menupoint
        if (( !isset($_GET['menu'])) || ( $_GET['menu'] == 'show' ) ) {
            echo "<div class='subMenupoint'><a href='" . $link . "&menu=hide'><i>Flotte ausblenden</i></a></div>";
        } else {
            echo "<div class='subMenupoint'><a href='" . $link . "&menu=show'><i>Flotte einblenden</i></a></div>";
        }

        if ((!isset($_GET['menu'])) || ( $_GET['menu'] == "show")) {
        
            // Alle Wagen aus DB:
            $sql    = "select id_sw, name from `stubenwagen` where active = 'yes' order by size_id";
            $dbcon  = dbConnect(DBUSER,DBPW);
            $result = $dbcon->query($sql);
            dbClose($dbcon);

            // displaying the points
            while ($row = $result->fetch_assoc()){
                displayMenuPoint($row['id_sw'],$activePage,$activeSub,$row['name'],"sub");
            }

        } // eoi show wagen

	// Zubehör
	} elseif ( $activePage == "shop" ) {

        $result = getCategory("");

        // displaying the points
        while ($row = $result->fetch_assoc()){

            // wenn nicht deaktiviert
            if ($row['active'] != "no" ) {

                // check if artikel
                $sql    = "select count(id_art) as count from artikel where inventory != 0 and active != 'no' and cat_id=" . $row['id_cat'];
                $dbcon  = dbConnect(DBUSER,DBPW);
                $resultArt = $dbcon->query($sql);
                $rowArt = $resultArt->fetch_assoc();
                dbClose($dbcon);    
                
                // display if artikel
                if ( $rowArt['count'] != 0 ) {
                    displayMenuPoint($row['id_cat'],$activePage,$activeSub,$row['category'],"sub");
                }
            } // eoi category active
	    }

    // Bestellungen
	} elseif ( $activePage == "bestellung" ) {
		displayMenuPoint("open",$activePage,$activeSub,"Neue Bestellungen","sub");
		displayMenuPoint("paid",$activePage,$activeSub,"Zum Verschicken","sub");
		displayMenuPoint("send",$activePage,$activeSub,"Fertige Bestellungen","sub");
		displayMenuPoint("cancelled",$activePage,$activeSub,"Stornierungen","sub");

    // Reservationen
	} elseif ( $activePage == "reservation" ) {
		displayMenuPoint("open",$activePage,$activeSub,"Neue Reservationen","sub");
		displayMenuPoint("confirmed",$activePage,$activeSub,"Best&auml;tigt","sub");
		displayMenuPoint("away",$activePage,$activeSub,"Zurzeit vermietet","sub");
		displayMenuPoint("back",$activePage,$activeSub,"Fertige Reservationen","sub");
		displayMenuPoint("cancelled",$activePage,$activeSub,"Stornierungen","sub");

    } elseif ( $activePage == "wagen" ) {
		displayMenuPoint("wagen_ov",$activePage,$activeSub,"&Uuml;bersicht","sub");
		displayMenuPoint("wagen_neu",$activePage,$activeSub,"Neuen Wagen erfassen","sub");

    } elseif ( $activePage == "info" ) {
		displayMenuPoint("wageni",$activePage,$activeSub,"Stubew&auml;ge","sub");
		displayMenuPoint("shopi",$activePage,$activeSub,"Zuebeh&ouml;r (Shop)","sub");

    } elseif ( $activePage == "inventar" ) {
		displayMenuPoint("overview",$activePage,$activeSub,"&Uuml;bersicht","sub");
		displayMenuPoint("category",$activePage,$activeSub,"Kategorien verwalten","sub");
		displayMenuPoint("categorynew&action=newCat",$activePage,$activeSub . "&action=newCat","Neue Kategorie","sub");
		displayMenuPoint("artikel",$activePage,$activeSub,"Artikel verwalten","sub");
		displayMenuPoint("artikelnew&action=newArt",$activePage,$activeSub . "&action=newArt","Neuer Artikel","sub");
		displayMenuPoint("wagen",$activePage,$activeSub,"Stubenwagen verwalten","sub");
		displayMenuPoint("wagennew&action=newWagen",$activePage,$activeSub . "&action=newWagen","Neuer Stubenwagen","sub");
    }

} // end of function displayMenu

function displayPage($area,$activePage) {

	if ($area == "ui") { 
        $legalPages = array("home","me","wagen","shop","info","download","links","kontakt");
	} elseif ($area == "admin") {
        $legalPages = array("login","dash","bestellung","reservation","wagen","inventar");

        // check if login-window
        if (($activePage == "home") && ($_SESSION['state'] != "loggedin")) {
            $activePage = "login";
        } else {
            $activePage = $activePage == "home" ? "dash" : $activePage;
        }// eoi loggeind

    } //eow area

    // check if legal
    if ( in_array($activePage,$legalPages )) {
		include("includes/$activePage.php");
	} else {
		die;
	}

} // end of function displayPage


##########################################
#                                        #
#              STUBENWAGEN               #
#                                        #
##########################################

function getWInfos($id) {

    $dbcon  = dbConnect(DBUSER,DBPW);
    $sql    = "select * from `stubenwagen` where id_sw = " . $id;
    $result = $dbcon->query($sql);
    dbClose($dbcon);
    
    return $result;

} // eof getWInfos()

function printWInfosAdmin($row) {

    // Name
    echo "<b>" . $row['name'] . "</b></br></br>";

    echo "<table width='100%'>";

        // Hauptbild
        echo "<tr><td valign='top'>";
            echo "<img width='200px' src='../img/mainphoto/" . $row['id_sw'] . ".jpg' />";
        echo "</td>";

            // Hauptbild
        echo "<td width='400px'>";
            if (file_exists('../gallery/' . $row['id_sw'] . '/index.html')) {
                echo "<object data='../gallery/" . $row['id_sw'] . "/index.html' width='100%' height='320px'></object>";
            } else {
                echo "<i>Leider ist die Bildergalerie dieses Stubenwagen zurzeit nicht verf&uuml;gbar - Sorry!</i>";
            }

        echo "</td></tr>";
    echo "</table>";

    echo "</br></br>";

} // eof printWInfosAdmin

function printWInfos($infos,$place) {

    $tdClassFront   = "sw_detail_front";
    $tdClassSecond  = "sw_detail_second";

    // Header
    echo "<table width='100%'>";
        echo "<tr><td class='top' id='top'>";
            echo "<b>Eigenschaft</b>";
        echo "<td class='top' id='top'>";
            echo "<b>Beschreibung</b>";
        echo "</td></tr>";

        // ADMIN-INFOS
        if ( $place == "admin" ) {
            echo "<tr><td class=" . $tdClassFront . " id=" . $tdClassFront . " valign='top' colspan='2'><b>Admin-Infos</b></td>";
            echo "</td></tr>";

            // Aktiv (Wartung)
            $active         = $infos['active'] == "no" ? "<font color='red'><b>INAKTIV</b></font>" : "<font color='green'>AKTIV</font>";
            $active        .= $infos['status'] == "inMaintenance" ? " (<font color='red'><b>IN WARTUNG!</b></font>)" : "";
            echo "<tr><td class=" . $tdClassFront . " id=" . $tdClassFront . " valign='top'>Auf Website</td>";
            echo "<td class=" . $tdClassSecond . " id=" . $tdClassSecond . ">";
                echo $active;
            echo "</td></tr>";

            // Verfuegbarkeit
            $availability   = calcAvailability($infos['nextfree']);
            $availability   = $infos['status'] == "inMaintenance" ? "maint" : $availability;
            if ( $availability == "now" ) {
                $availabilityText = "<img src='../img/goodtick.jpg' /><font color='green'> Sofort verf&uuml;gbar</font>";
            } elseif ( $availability == "maint" ) {
                $availabilityText = "<img src='../img/badtick.jpg' /><font color='red'> In Wartung</font>";
            } else {
                $availabilityText = "<img src='../img/badtick.jpg' /><font color='red'> Verf&uuml;gbar am $availability</font>";
            }
            echo "<tr><td class=" . $tdClassFront . " id=" . $tdClassFront . " valign='top'>Verf&uuml;gbarkeit</td>";
            echo "<td class=" . $tdClassSecond . " id=" . $tdClassSecond . ">";
                echo $availabilityText;
            echo "</td></tr>";

            // Groesse
            $resultSize = getSize($infos['size_id']);
            $rowSize    = $resultSize->fetch_assoc();
            echo "<tr><td class=" . $tdClassFront . " id=" . $tdClassFront . " valign='top'>Gr&ouml;sse</td>";
            echo "<td class=" . $tdClassSecond . " id=" . $tdClassSecond . ">";
                echo $rowSize['size'];
            echo "</td></tr>";

            // AKTIONEN
            $actionAct      = $infos['active'] == "no" ? "activate" : "deactivate";
            $actionActText  = $infos['active'] == "no" ? "aktivieren" : "deaktivieren";
            $actionMain     = $infos['status'] == "inMaintenance" ? "endMaint" : "startMaint";
            $actionMainText = $infos['status'] == "inMaintenance" ? "Wartung beenden" : "Warten";

            // building link
            $link       = "top=inventar&sub=wagen";
            $link      .= !isset($_GET['size_id']) ? "" : "&size_id=" . $_GET['size_id'];
            $link      .= !isset($_GET['active']) ? "" : "&active=" . $_GET['active'];
            $link      .= !isset($_GET['main']) ? "" : "&main=" . $_GET['main'];

            echo "<tr><td class=" . $tdClassFront . " id=" . $tdClassFront . " valign='top'>Aktionen</td>";
            echo "<td class=" . $tdClassSecond . " id=" . $tdClassSecond . ">";
               echo "<a class='not_menu' href='?" . $link . "&" . $actionAct . "=" . $infos['id_sw']. "'>Stubenwagen " . $actionActText . "</a></br>";
               echo "<a class='not_menu' href='?" . $link . "&" . $actionMain . "=" . $infos['id_sw']. "'>" . $actionMainText . "</a></br>";
               echo "<a class='not_menu' href='?" . $link . "&edit=" . $infos['id_sw'] . "'>Stubenwagen bearbeiten</a></br>";
               echo "<a class='not_menu' onclick=\"alert('Willst du den Stubenwagen " . $infos['name'] . " wirklich l&ouml;schen?')\" href='?" . $link . "&delete=" . $infos['id_sw'] . "'>Stubenwagen l&ouml;schen</a></br>";
            echo "</td></tr>";

            // HEADER REST INFOS
            echo "<tr><td class=" . $tdClassFront . " id=" . $tdClassFront . " valign='top' colspan='2'><b>Restliche Infos</b></td>";
            echo "</td></tr>";


        } // eoi admin


        echo "<tr><td class=" . $tdClassFront . " id=" . $tdClassFront . " valign='top'>";
            echo "Korbform" . "</td><td class=" . $tdClassSecond . " id=" . $tdClassSecond . ">";
            echo $infos['korb'];
        echo "</td></tr>";
        echo "<tr><td class=" . $tdClassFront . " id=" . $tdClassFront . " valign='top'>";
            echo "Vorhang" . "</td><td class=" . $tdClassSecond . " id=" . $tdClassSecond . ">";
            echo $infos['vorhang'];
        echo "</td></tr>";
        echo "<tr><td class=" . $tdClassFront . " id=" . $tdClassFront . " valign='top'>";
            echo "Innenausstattung" . "</td><td class=" . $tdClassSecond . " id=" . $tdClassSecond . ">";
            echo $infos['innen'];
        echo "</td></tr>";
        echo "<tr><td class=" . $tdClassFront . " id=" . $tdClassFront . " valign='top'>";
            echo "Fixleint&uuml;cher" . "</td><td class=" . $tdClassSecond . " id=" . $tdClassSecond . ">";
            echo $infos['leintuch_1'];
            if ( $infos['leintuch_2'] != "" ) {
                echo "</br>" . $infos['leintuch_2'];
            }
        echo "</td></tr>";
        echo "<tr><td class=" . $tdClassFront . " id=" . $tdClassFront . " valign='top'>";
            echo "Duvet" . "</td><td class=" . $tdClassSecond . " id=" . $tdClassSecond . ">";
            echo $infos['duvet'];
         //   echo "Duvet clean junior (synthetisch, waschbar bei 95&deg; Celsius)</br>Gewicht: 200g";
        echo "</td></tr>";
        echo "<tr><td class=" . $tdClassFront . " id=" . $tdClassFront . " valign='top'>";
            echo "Duvetbez&uuml;ge" . "</td><td class=" . $tdClassSecond . " id=" . $tdClassSecond . ">";
            echo $infos['duvet_1'];
            if ( $infos['duvet_2'] != "" ) {
                echo "</br>" . $infos['duvet_2'];
            }
        echo "</td></tr>";
        echo "<tr><td class=" . $tdClassFront . " id=" . $tdClassFront . " valign='top'>";
            echo "Matratze" . "</td><td class=" . $tdClassSecond . " id=" . $tdClassSecond . ">";
            echo $infos['matratze'];
        echo "</td></tr>";
        echo "<tr><td class=" . $tdClassFront . " id=" . $tdClassFront . " valign='top'>";
            echo "Kopfspuckt&uuml;cher" . "</td><td class=" . $tdClassSecond . " id=" . $tdClassSecond . ">";
            echo $infos['kopfspuck_1'];
            if ( $infos['kopfspuck_2'] != "" ) {
                echo "</br>" . $infos['kopfspuck_2'];
            }
            if ( $infos['kopfspuck_3'] != "" ) {
                echo "</br>" . $infos['kopfspuck_3'];
            }
        echo "</td></tr>";
        echo "<tr><td class=" . $tdClassFront . " id=" . $tdClassFront . " valign='top'>";
            echo "Zusatzinfos" . "</td><td class=" . $tdClassSecond . " id=" . $tdClassSecond . ">";
            echo $infos['description'];
        echo "</td></tr>";



    echo "</table>";

} // eof printWInfos

function checkFavs() {

    if ($_SESSION['favCount'] == 0) {
        $favCount = $_SESSION['favCount'];
        for ($i=0;$i<6;$i++) {
            $favCount = $_SESSION['favs'][$i] != "-" ? ($favCount+1) : $favCount;
        }
        // reset SESSION-Var if needed
        $_SESSION['favCount'] = $favCount;
    } // if no favs selected

} // eof checkFavs

function printFavs() {

    echo "<table width='100%'>";
        echo "<tr>";
            echo "<td width='60px'>";
                if ($_SESSION['favs'][0] != "-") {
                    $result = getWInfos($_SESSION['favs'][0]);
                    $row    = $result->fetch_assoc();
                    $img    = $row['mainphoto'];
                    $id     = $row['id_sw'];
                    
                    echo "<a href='?top=wagen&sub=" . $id . "'>";
                        echo "<img src='" . $img . "' width='60px'>";
                    echo "</a>";
                }   
            echo "</td>";
            echo "<td width='60px'>";
                if ($_SESSION['favs'][1] != "-") {
                    $result = getWInfos($_SESSION['favs'][1]);
                    $row    = $result->fetch_assoc();
                    $img    = $row['mainphoto'];
                    $id     = $row['id_sw'];
                    
                    echo "<a href='?top=wagen&sub=" . $id . "'>";
                        echo "<img src='" . $img . "' width='60px'>";
                    echo "</a>";
                }   
            echo "</td>";

            echo "<td width='60px'>";
                if ($_SESSION['favs'][2] != "-") {
                    $result = getWInfos($_SESSION['favs'][2]);
                    $row    = $result->fetch_assoc();
                    $img    = $row['mainphoto'];
                    $id     = $row['id_sw'];
                    
                    echo "<a href='?top=wagen&sub=" . $id . "'>";
                        echo "<img src='" . $img . "' width='60px'>";
                    echo "</a>";
                }   
            echo "</td>";
        echo "</tr><tr>";
            echo "<td width='60px'>";
                if ($_SESSION['favs'][3] != "-") {
                    $result = getWInfos($_SESSION['favs'][3]);
                    $row    = $result->fetch_assoc();
                    $img    = $row['mainphoto'];
                    $id     = $row['id_sw'];
                    
                    echo "<a href='?top=wagen&sub=" . $id . "'>";
                        echo "<img src='" . $img . "' width='60px'>";
                    echo "</a>";
                }   
            echo "</td>";

            echo "<td width='60px'>";
                if ($_SESSION['favs'][4] != "-") {
                    $result = getWInfos($_SESSION['favs'][4]);
                    $row    = $result->fetch_assoc();
                    $img    = $row['mainphoto'];
                    $id     = $row['id_sw'];
                    
                    echo "<a href='?top=wagen&sub=" . $id . "'>";
                        echo "<img src='" . $img . "' width='60px'>";
                    echo "</a>";
                }   
            echo "</td>";

            echo "<td width='60px'>";
                if ($_SESSION['favs'][5] != "-") {
                    $result = getWInfos($_SESSION['favs'][5]);
                    $row    = $result->fetch_assoc();
                    $img    = $row['mainphoto'];
                    $id     = $row['id_sw'];
                    
                    echo "<a href='?top=wagen&sub=" . $id . "'>";
                        echo "<img src='" . $img . "' width='60px'>";
                    echo "</a>";
                }   
            echo "</td>";
        echo "</tr>";
    echo "</table>";

} // eof printFavs() 


##########################################
#                                        #
#                GET SIZE                #
#                                        #
##########################################

function getSize($id_size) {

        // condition
        $condition = $id_size != "" ? "where id_size=$id_size" : "";

		// Alle Kategorien aus DB:
        $sql    = "select * from `size` " . $condition;
        $dbcon  = dbConnect(DBUSER,DBPW);
        $result = $dbcon->query($sql);
        dbClose($dbcon);

        return($result);

} // eof getCategory()

##########################################
#                                        #
#              GET CATEGORY              #
#                                        #
##########################################

function getCategory($id_cat) {

        // condition
        $condition = $id_cat != "" ? "where id_cat=$id_cat" : "";

		// Alle Kategorien aus DB:
        $sql    = "select * from `category` " . $condition;
        $dbcon  = dbConnect(DBUSER,DBPW);
        $result = $dbcon->query($sql);
        dbClose($dbcon);

        return($result);

} // eof getCategory()


##########################################
#                                        #
#            GET COUNT INVENTORY         #
#                                        #
##########################################

function getInvCount($artId) {

		// Alle Artikel aus DB:
        $sql    = "select inventory from `artikel` where `id_art` = " . $artId;
        $dbcon  = dbConnect(DBUSER,DBPW);
        $result = $dbcon->query($sql);
        dbClose($dbcon);

        $row        = $result->fetch_assoc();
        $invCount   = $row['inventory'];

        return($invCount);

} // eof getInvCount()


##########################################
#                                        #
#            SET COUNT INVENTORY         #
#                                        #
##########################################

function setInvCount($artId,$invCount) {

		// Alle Artikel aus DB:
        $sql    = "update artikel set inventory = " . $invCount . " where id_art=" . $artId;
        $dbcon  = dbConnect(DBUSER,DBPW);
        $result = $dbcon->query($sql);
        dbClose($dbcon);

} // eof setInvCount()


##########################################
#                                        #
#              GET ARTIKEL               #
#                                        #
##########################################

function getArtikel($catId,$chosenArtId,$rule) {

        // Condition definieren
        $condition = $catId == "" ? "where `active` = 'yes'" : "where `cat_id` = $catId and `active`= 'yes'";
        $condition = $rule == "showalways" ? $condition : $condition . " and `inventory` != 0";
        $condition = $chosenArtId == "" ? $condition : $condition . " and `id_art` = " . $chosenArtId;

		// Alle Artikel aus DB:
        $sql    = "select * from `artikel` " . $condition;
        $dbcon  = dbConnect(DBUSER,DBPW);
        $result = $dbcon->query($sql);
        dbClose($dbcon);

        return($result);

} // eof getArtikel()


##########################################
#                                        #
#              PRINT ARTIKEL             #
#                                        #
##########################################

function printArtikel($catId,$chosenArtId,$rule,$result,$place) {

    // Arrays definieren
    $artId      = array();
    $artCatId   = array();
    $artName    = array();
    $artActive  = array();
    $artInv     = array();
    $artDesc    = array();
    $artSize    = array();
    $artImg     = array();
    $artImg2    = array();
    $artPrice   = array();
    $artPorto   = array();

    // Artikel auslesen
    if ( $result == "" ) {
        $result     = getArtikel($catId,$chosenArtId,$rule);
    }

    // If Bodies
    if (( $catId == 4 ) && ( $place != "admin" )) {
        $sql        = "select * from artikel where cat_id=4 order by size asc";
        $sqlSize    = "select distinct(size) as size from artikel where cat_id=4 order by size asc";
        $dbcon      = dbConnect(DBUSER,DBPW);
        $result     = $dbcon->query($sql);
        $resultSize = $dbcon->query($sqlSize);
        $lastSize   = "";
    }

    // Anzahl
    $artCount   = $result->num_rows;

    // Arrays fuellen
    while ($row = $result->fetch_assoc()) {
//        $row    = utf8_converter($rowUe);
        array_push($artId,$row['id_art']);
        array_push($artCatId,$row['cat_id']);
        array_push($artName,$row['name']);
        array_push($artActive,$row['active']);
        array_push($artInv,$row['inventory']);
        array_push($artDesc,$row['description']);
        array_push($artSize,$row['size']);
        array_push($artImg,$row['photo']);
        array_push($artImg2,$row['photo2']);
        array_push($artPrice,$row['price']);
        array_push($artPorto,$row['porto']);
    }

    // Artikel ausgeben
    $tdClassFront   = "sw_detail_front";
    $tdClassSecond  = "sw_detail_second";

    // ImagePath
    $imgPath    = $place == "admin" ? "../" : "";

    // KISSEN UND BODIES ANDERS!!!
    if (( ( $catId == 2 ) || ( $catId == 4 )) && ( $place != "admin") ) {

        $countThisSize = 0;

        // Info-Text
        if ( $catId == 2 ) {
            echo "</br></br>";
            echo "Alle Lagerungskissen kosten " . $artPrice[0] . "CHF + " . $artPorto[0] . "CHF Porto.";
        } elseif ( $catId == 4 ) {
            echo "</br></br>";
            echo "Mit viel Liebe kreiere ich lustige Motive und appliziere sie auf die Baby Bodies. ";
            echo "Die Bodies sind waschbar mit 60 Grad und sind aus 100% Baumwolle.</br></br>";
            echo "Die Bodies kosten " . $artPrice[0] . "CHF + " . $artPorto[0] . "CHF Porto.";
            echo "</br></br>";
        } 
/*
        echo "<table class='sw_overview' width='100%'>";
            echo "<tr>";
*/
//            for ($i=0;$i<$artCount;$i++) {
        for ($i=0;$i<$artCount;$i++) {

            // create table
            if ( ($catId != 4 ) && ($i == 0 )) {
                echo "<table class='sw_overview' width='100%'>";
                    echo "<tr>";
            } elseif (( $catId == 4 ) && ( $artSize[$i] != $lastSize ) ) {

                // close table
                if ( $i != 0 ) {     
                    if ( $countThisSize == 1 ) {
                        echo "<td></td><td></td>";
                    } elseif ( $countThisSize == 2 ) {
                        echo "<td></td>";
                    }
                        echo "</tr>";
                    echo "</table>";
                } // eo close table

                // start next table
                echo "<b>" . $artSize[$i] . "</b>";
                echo "<table class='sw_overview' width='100%'>";
                    echo "<tr>";
                $lastSize       = $artSize[$i];
                $countThisSize  = 0;
                $newSize        = "yes";
            }

                $countThisSize++;

                // generate link
                echo "<td class='sw_overview' valign='top'>";
                    echo "<a target='_blank' href='" . $imgPath . $artImg[$i] . "'>";
                        echo "<img src='" . $imgPath . $artImg[$i] . "' width='180px'/></br>";
                    echo "</a>";
                        if ( $artImg2[$i] != "" ) {
                            echo "<a target='_blank' href='" . $imgPath . $artImg2[$i] . "'>";
                                echo "<img src='" . $imgPath . $artImg2[$i] . "' max-width='180px' width='180px' /></br>";
                            echo "</a>";
                        }
                    echo "<center>";
                         actionButton("center","?top=shop&sub=" . $_GET['sub'] . "&buy=" . $artId[$i],"Jetzt bestellen!");
                    echo "</center>";
                    echo "</br>";
                echo "</td>";

                if ( $artCount == 1 ) {
                    echo "<td></td><td></td>";
                } elseif ( $artCount == 2 ) {
                    echo "<td></td>";
                }

                // Welcher Wert
                $tableCloser    = $catId == 4 ? $countThisSize - 1 : $i;

                if ((($tableCloser + 1) % 3) == 0) {
                    echo "</tr><tr>";
                } // end of close table

                // close table
                if ( ($catId != 4 ) && ($i == ($artCount-1) ) ) {
                        echo "</tr>";
                    echo "</table>";
                }

        } // end of printing wagen of this size


    // IF NOT BODIES OR KISSEN
    } else {
        echo "<table width='100%' class='artikelWrapper' id='artikelWrapper'>";
            for ($i=0;$i<$artCount;$i++) {
                echo "<tr>";
                    echo "<td>";
                        if ( $chosenArtId == "" ) {
                            echo "<b>" . utf8_decode($artName[$i]) . "</b></br></br>";
                        }
                        echo "<table class='artikel' id='artikel' width='550px'>";
                            echo "<tr>";
                                echo "<td class='artikelImg' align='center' valign='top'>";
                                    echo "<a target='_blank' href='" . $imgPath . $artImg[$i] . "'>";
                                        echo "<img src='" . $imgPath . $artImg[$i] . "' width='180px'/></br>";
                                    echo "</a>";
                                        if ( $artImg2[$i] != "" ) {
                                            echo "<a target='_blank' href='" . $imgPath . $artImg2[$i] . "'>";
                                                echo "<img src='" . $imgPath . $artImg2[$i] . "' max-width='180px' width='180px' /></br>";
                                            echo "</a>";
                                        }
                                echo "</td>";
                                echo "<td valign='top'>";
                                    echo "<table class='artikelDesc' width='400px'>";
                                        echo "<tr>";
                                            echo "<td class='top'>Eigenschaft</td>";
                                            echo "<td class='top'>Beschreibung</td>";
                                        echo "</tr>";

                                        // weitere infos wenn admin
                                        if ( $place == "admin" ) {

                                            // building link
                                            $link       = "top=inventar&sub=artikel";
                                            $link      .= !isset($_GET['cat_id']) ? "" : "&cat_id=" . $_GET['cat_id'];
                                            $link      .= !isset($_GET['active']) ? "" : "&active=" . $_GET['active'];
                                            $link      .= !isset($_GET['inv']) ? "" : "&inv=" . $_GET['inv'];

                                            // Werte ermitteln
                                            $inv            = $artInv[$i] == 0 ? "<font color='red'><b>AUSVERKAUFT!</b></font>" : $artInv[$i];
                                            $active         = $artActive[$i] == "no" ? "<font color='red'><b>INAKTIV</b></font>" : "<font color='green'>AKTIV</font>";
                                            $actionAct      = $artActive[$i] == "no" ? "activate" : "deactivate";
                                            $actionActText  = $artActive[$i] == "no" ? "aktivieren" : "deaktivieren";
                                            if ( $artCatId[$i] == 0 ) {
                                                $category   = "<font color='red'><b>keiner Kategorie zugewiesen</b></font>";
                                            } else {
                                                $result         = getCategory($artCatId[$i]);
                                                $rowCat         = $result->fetch_assoc();
                                                $category       = $rowCat['category'];
                                            }

                                            // HEADER ADMIN-INFOS
                                            echo "<tr><td class=" . $tdClassFront . " id=" . $tdClassFront . " valign='top' colspan='2'><b>Admin-Infos</b></td>";
                                            echo "</td></tr>";

                                            // Kategorie ausgeben
                                            echo "<tr>";
                                                echo "<td valign='top' class='" . $tdClassFront . "'>Kategorie:</td>";
                                                echo "<td valign='top' class='" . $tdClassSecond . "' >$category</td>";
                                            echo "</tr>";

                                            // Inventar ausgeben
                                            echo "<tr>";
                                                echo "<td valign='top' class='" . $tdClassFront . "'>an Lager:</td>";
                                                echo "<td valign='top' class='" . $tdClassSecond . "' >$inv</td>";
                                            echo "</tr>";

                                            // Aktiv ausgeben
                                            echo "<tr>";
                                                echo "<td valign='top' class='" . $tdClassFront . "'>Im Shop:</td>";
                                                echo "<td valign='top' class='" . $tdClassSecond . "' >$active</td>";
                                            echo "</tr>";

                                            // Aktionen ausgeben
                                            echo "<tr>";
                                                echo "<td valign='top' class='" . $tdClassFront . "'>Aktionen:</td>";
                                                echo "<td valign='top' class='" . $tdClassSecond . "' >";
                                                   echo "<a class='not_menu' href='?" . $link . "&" . $actionAct . "=" . $artId[$i]. "'>Artikel " . $actionActText . "</a></br>"; 
                                                   echo "<a class='not_menu' onclick=\"alert('Willst du den Artikel " . $artName[$i] . " wirklich l&ouml;schen?')\" href='?" . $link . "&delete=" . $artId[$i]. "'>Artikel l&ouml;schen</a></br>"; 
                                                   echo "<a class='not_menu' href='?" . $link . "&edit=" . $artId[$i]. "'>Artikel bearbeiten</a></br>"; 
                                                   echo "<a class='not_menu' href='?" . $link . "&pic2=" . $artId[$i]. "'>2. Foto bearbeiten</a></br>"; 
                                                   if ( $artImg2[$i] != "" ) {
                                                       echo "<a class='not_menu' href='?" . $link . "&deletePic2=" . $artId[$i]. "'>2. Foto l&ouml;schen</a></br>"; 
                                                   }
                                                echo "</td>";
                                            echo "</tr>";

                                            // HEADER Restliche-INFOS
                                            echo "<tr><td class=" . $tdClassFront . " id=" . $tdClassFront . " valign='top' colspan='2'><b>Restliche Infos</b></td>";
                                            echo "</td></tr>";

                                        } // eoi admin
                                        echo "<tr>";
                                            echo "<td valign='top' class='" . $tdClassFront . "'>";
                                                echo "Beschreibung:";
                                            echo "</td>";
                                            echo "<td valign='top' class='" . $tdClassSecond . "'>";
                                                echo utf8_decode($artDesc[$i]);
                                            echo "</td>";
                                        echo "</tr>";
                                        
                                        // Groesse nur bei Stofftieren anzeigen
                                        if (( $artCatId[$i] == 1 ) || ( $artCatId[$i] == 3 ) || ( $artCatId[$i] == 4 )) {
                                            echo "<tr>";
                                                echo "<td valign='top' class='" . $tdClassFront . "'>";
                                                    echo "Gr&ouml;sse:";
                                                echo "</td>";
                                                echo "<td valign='top' class='" . $tdClassSecond . "'>";
                                                    echo $artSize[$i];
                                                echo "</td>";
                                            echo "</tr>";
                                        } // end of if groesse anzeigen

                                        echo "<tr valign='top'>";
                                            echo "<td valign='top' class='" . $tdClassFront . "'>";
                                                echo "Preis:";
                                            echo "</td>";
                                            echo "<td valign='top' class='" . $tdClassSecond . "'>";
                                                echo $artPrice[$i] . " CHF (+ " . $artPorto[$i] . " CHF Porto)";
                                            echo "</td>";
                                        echo "</tr>";

                                        // only show if shop
                                        if ( $place == "shop" ) {
                                            echo "<tr><td>";
                                                echo "</br>";
                                            echo "</td></tr>";

                                            echo "<tr>";
                                                echo "<td colspan='2' class='containButton' id='containButton'>";
                                                    if ( $chosenArtId == "" ) {
                                                        // Action-Button 
                                                        actionButton("right","?top=shop&sub=" . $_GET['sub'] . "&buy=" . $artId[$i],"Jetzt bestellen!");
                                                    }
                                                echo "</td>";
                                            echo "</tr>";
                                        } // eoi shop

                                    echo "</table>";
                                echo "</td>";
                            echo "</tr>";
                        echo "</table>";
                        echo "</br></br>";
                    echo "</td>";
                echo "</tr>";

            } // eoi not KISSEN ODER BODIES

        }  // eoi print each artikel

    echo "</table>";


} // eof printArtikel()


##########################################
#                                        #
#              SEND ADMIN MAIL           #
#                                        #
##########################################

function sendAdminMail($type) {

    // Include Admin Functions
    if ( substr( $_SERVER['REQUEST_URI'], 0, 5 ) == "/dev/" ) {
        $to         = "simon@ironsmith.ch";
        $to         = "info@schlosser-stubenwagen.ch";
    } else {
        $to         = "info@schlosser-stubenwagen.ch";
        //$to         = "simon@ironsmith.ch";
    }

    // Wenn neue Bestellung
    if ($type == "bestellung" ) {

        $subject    = "Neue Bestellung";
        $message    = "Salut Claudia<br><br>";
        $message   .= "Yeeeeeeeeah da het &ouml;per &ouml;pis bsteut u wotnis ds Portemonnaie f&uuml;u&auml;e! :D<br><br>";
        $message   .= "H&auml;b &auml; guet&auml; Tag!";

    // Wenn neue Reservation
    } elseif ($type == "reservation") {

        $subject    = "Neue Reservation";
        $message    = "Salut Claudia<br><br>";
        $message   .= "Yeeeeeeeeah da het &ouml;per &auml; Stubewage reserviert... Money, Money, Money! :D<br><br>";
        $message   .= "H&auml;b &auml; guet&auml; Tag!";
    }

    // Default-Headers
    $headers = 'From: webshop@schlosser-stubenwagen.ch' . "\r\n" .
        'Reply-To: info@schlosser-stubenwagen.ch' . "\r\n" .
        'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    // Mail senden
    mail($to, $subject, $message, $headers);

} // eof sendAdminMail


##########################################
#                                        #
#              SEND CLIENT MAIL           #
#                                        #
##########################################

function sendClientMail($type,$mail,$firstname,$lastname) {

    // Definitionen
    $to     = $mail;
    $mType  = $type == "bestellung" ? "Bestellung" : "Reservation";

    // Inhalt
    $subject    = "Ihre $mType";
    $message    = "Guten Tag $firstname $lastname <br><br>";
    $message   .= "Ihre $mType ist erfolgreich bei mir eingegangen. Ich werde diese in den n&auml;chsten Tagen pr&uuml;fen und mich bei Ihnen melden.<br>";
    $message   .= "Falls Sie Fragen haben, dann kontaktieren Sie mich ungescheut via Mail an <a href='mailto:info@schlosser-stubenwagen.ch'>info@schlosser-stubenwagen.ch</a>.<br><br>";
    $message   .= "Besten Dank f&uuml;r Ihr Vertrauen und bis bald.<br><br>";
    $message   .= "Freundliche Gr&uuml;sse<br>";
    $message   .= "Claudia Schlosser";


    // Default-Headers
    $headers = 'From: webshop@schlosser-stubenwagen.ch' . "\r\n" .
        'Reply-To: info@schlosser-stubenwagen.ch' . "\r\n" .
        'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    // Mail senden
    mail($to, $subject, $message, $headers);

} // eof sendAdminMail


##########################################
#                                        #
#              BOOKING PDF               #
#                                        #
##########################################

// Function makePdf
function makePdf($area,$type,$id) {

    // important variables
    $_SESSION['type']       = $type;
    $_SESSION['id']         = $id;
//    $mailPdfDir             = "/home/ironsmit/ss_dev/admin/mailpdf";

    $pdfDir = $area == "bestellung" ? "admin/mailpdf" : "mailpdf";

    // get the HTML
    ob_start();
    include('includes/pdf_templates/' . $area . '.php');
    $content = ob_get_clean();

    // convert to PDF
    require_once('library/html2pdf/html2pdf.class.php');
    try
    {
        $html2pdf = new HTML2PDF('P','A4','de',true,'UTF-8',array(20, 20, 20, 20));
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        //$html2pdf->Output("mailpdf/". $area . "_" . $id . ".pdf","F");
        $html2pdf->Output($pdfDir . "/". $area . "_" . $id . ".pdf","F");
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} // end of makeBookingPdf

##########################################
#                                        #
#              SEND PDFMAIL              #
#                                        #
##########################################


function sendPdfMail($id,$area,$type) {

    // Include Admin Functions
    if (( substr( $_SERVER['REQUEST_URI'], 0, 10 ) != "/dev/admin" ) && ( substr( $_SERVER['REQUEST_URI'], 0, 6 ) != "/admin") ) {
        include('/home/ironsmit/sec-data/schlosser-stubenwagen.ch/.adminFunctions.php');
    }

    // global vars
    if ((( $area == "bestellung") && ( $type == "confirm" )) || (( $area == "booking" ) && ( $type == "new" ))) {
        $msgDir = "admin/mailmsg/";
        $pdfDir = "admin/mailpdf/";
    } else {
        $msgDir = "../admin/mailmsg/";
        $pdfDir = "../admin/mailpdf/";
    }

    // get database entry
    if ($area == "booking") {
        $result     = getBookingEntries("",$id);
    } elseif ($area == "bestellung") {
        $result     = getOrderEntries("",$id);
    } // eo area

    // get the infos
    $row        = $result->fetch_assoc();
    $to         = $row['mail'];
    $name       = $row['firstname'] . " " . $row['lastname'];
    $footer     = "$msgDir/footer"; 

    // RESERVATIONEN
    if ($area == "booking") {

        // change content and subject
        if ($type == "confirm" ) {

            // definitions
            $subject            = "Bestätigung Ihrer Stubenwagen-Reservation";
            $attachement        = "$pdfDir/reservation_" .$id . ".pdf";
            $attachementName    = "Reservationsbestaetigung.pdf";
            $attachementAVB     = "$pdfDir/AVB.pdf";
            $attachementMV      = "$pdfDir/MV.pdf";

            // make content
            $body   = "Guten Tag " . $row['firstname'] . " " . $row['lastname'] . "<br><br>";
            $body  .= "Hiermit best&auml;tige ich Ihre Reservation des Stubenwagens <i>" . $row['name'] . "</i> f&uuml;r die Zeit vom " . $row['startdate'] . " bis " . $row['enddate'] .".<br><r>";
            $body  .= "F&uuml;r die Vereinbarung des Abholtermins bitte ich Sie, sich mit mir via <a href='mailto:info@schlosser-stubenwagen.ch'>info@schlosser-stubenwagen.ch</a> ";
            $body  .= "(oder per Telefon) in Verbindung zu setzen.<br><br>";
            $body  .= "Alle weiteren Informationen zu Ihrer Reservation, sowie Mietvertrag und AVBs finden Sie im Anhang.<br><br>";
            $body  .= file_get_contents($footer);
        
        // change content and subject
        } elseif ($type == "cancel" ) {

            // definitions
            $subject            = "Stornierung Ihrer Stubenwagen-Reservation";

            // make content
            $body   = "Guten Tag " . $row['firstname'] . " " . $row['lastname'] . "<br><br>";
            $body  .= "Sie haben am " . $row['date'] . " bei mir den Stubenwagens <i>" . $row['name'] . "</i> f&uuml;r die Zeit vom " . $row['startdate'] . " bis " . $row['enddate'] ." reserviert.<br><br>";
            $body  .= "Auf Ihren pers&ouml;nlichen Wunsch hin habe ich die Reservation soeben storniert.<br><br>";
            $body  .= file_get_contents($footer);

        // change content and subject
        } elseif ($type == "new" ) {

            // definitions
            $subject            = "Ihre Stubenwagen-Reservation";

            // make content
            $body   = "Guten Tag " . $row['firstname'] . " " . $row['lastname'] . "<br><br>";
            $body  .= "Es freut mich, dass Sie sich daf&uuml;r entschieden haben bei mir einen Stubenwagen zu reservieren.<br><br>";
            $body  .= "Ihre Reservation des Stubenwagens <i>" . $row['name'] . "</i> f&uuml;r die Zeit vom " . $row['startdate'] . " bis " . $row['enddate'] ." ";
            $body  .= "ist erfolgreich bei mir eingetroffen. Ich werde diese in den n&auml;chsten Tagen bearbeiten und mich bei Ihnen melden.<br><br>";
            $body  .= "Falls Sie Fragen haben, dann kontaktieren Sie mich via Mail an <a href='mailto:info@schlosser-stubenwagen.ch'>info@schlosser-stubenwagen.ch</a><br><br>";
            $body  .= "Besten Dank f&uuml;r Ihr Vertrauen und bis bald!<br><br>";
            $body  .= file_get_contents($footer);

        // change content and subject
        } elseif ($type == "termin" ) {

            // definitions
            $subject            = "Bestätigung Abholtermin";
            $attachementAVB     = "$pdfDir/AVB.pdf";
            $attachementMV      = "$pdfDir/MV.pdf";

            // make content
            $body   = "Guten Tag " . $row['firstname'] . " " . $row['lastname'] . "<br><br>";
            $body  .= "Gerne best&auml;tige ich Ihnen den abgemachten Abholtermin vom " . $row['tDate'] . ".<br><br>";
            $body  .= "Bitte bringen Sie zur Abholung die ausgef&uuml;llten und unterschriebenen Exemplare des Mietvertrags und der AVBs mit.<br><br>";
            $body  .= "Abholadresse:<br>";
            $body  .= "Schulhaus Burgiwil<br>";
            $body  .= "3664 Burgistein<br><br>";
            $body  .= "Besten Dank f&uuml;r Ihr Vertrauen und bis bald!<br><br>";
            $body  .= file_get_contents($footer);

        }
/*
        // Wenn abgelehnt
        } elseif ($type == "decline") {

            // definitions
            $subject    = "Ihre Reservation bei schlosser-stubenwagen.ch";
            $bodyFile1  = "$msgDir/reservation_decline_1"; 
            $bodyFile2  = "$msgDir/reservation_decline_2"; 

            // make content
            $body   = "Guten Tag " . $row['firstname'] . " " . $row['lastname'] . "<br><br>";
            $body  .= file_get_contents($bodyFile1);
            $body  .= file_get_contents($bodyFile2);
            $body  .= file_get_contents($footer);
        }
*/
    // BESTELLUNGEN
    } elseif ($area == "bestellung") {

        // Datumsformate
        $dateArray = explode("-",$row['date']);
        list($y,$m,$d)      = $dateArray;
        $bDate              = "$d.$m.$y";

        // change content and subject
        if ($type == "confirm" ) {

            // definitions
            $subject            = "Bestätigung Ihrer Bestellung";
            $bodyFile1          = "$msgDir/bestellung_confirm_1"; 
            $attachement        = "$pdfDir/bestellung_" .$id . ".pdf";
            $attachementName    = "Bestellungsbestaetigung und Rechnung.pdf";

            // make content
            $body   = "Guten Tag " . $row['firstname'] . " " . $row['lastname'] . "<br><br>";
            $body  .= "Vielen Dank f&uuml;r Ihre Bestellung des Artikels  <i>" . $row['name'] . "</i> (" . $row['category'] . ").<br><br>";
            $body  .= "Falls Sie Fragen haben, dann kontaktieren Sie mich via Mail an <a href='mailto:info@schlosser-stubenwagen.ch'>info@schlosser-stubenwagen.ch</a>. ";
            $body  .= "Alle weiteren Informationen, sowie die Rechnung finden Sie im Anhang.<br><br>";
            $body  .= "Falls Sie kein E-Banking-Zugang haben, stelle ich Ihnen gerne einen Einzahlungsschein zu.<br><br>";
            $body  .= "Vielen Dank f&uuml;r Ihr Vertrauen!<br><br>";
            $body  .= file_get_contents($footer);

        // Wenn Erinnerung
        } elseif ($type == "remember") {

            // definitions
            $subject    = "Zahlungserinnerung";
            $bodyFile1  = "$msgDir/bestellung_remember_1"; 
            $attachement        = "$pdfDir/bestellung_" .$id . ".pdf";
            $attachementName    = "Rechnung.pdf";

            // make content
            $body   = "Guten Tag " . $row['firstname'] . " " . $row['lastname'] . "<br><br>";
            $body  .= "Sie haben auf meiner Website <i>www.schlosser-stubenwagen.ch</i> am $bDate den Artikel <i>" . $row['name'] . "</i> (" . $row['category'] . ") erworben.<br><br>";
            $body  .= file_get_contents($bodyFile1);
            $body  .= file_get_contents($footer);

        // wenn Stornierung (nicht bezahlt)
        } elseif ($type == "cancelPay") {

            // definitions
            $subject    = "Stornierung Ihrer Bestellung";

            // make content
            $body   = "Guten Tag " . $row['firstname'] . " " . $row['lastname'] . "<br><br>";
            $body  .= "Sie haben auf meiner Website <i>www.schlosser-stubenwagen.ch</i> am $bDate den Artikel <i>" . $row['name'] . "</i> (" . $row['category'] . ") erworben.<br><br>";
            $body  .= "Am " . $row['rDate'] . " habe ich Sie dar&uuml;ber informiert, dass Sie den Rechnungsbetrag noch nicht bezahlt haben und Sie darauf hingewiesen, ";
            $body  .= "dass ich Ihre Bestellung stornieren werde, wenn die Zahlung nicht innert den n&auml;chsten 30 Tagen erfolgt.<br><br>";
            $body  .= "Die Stornierung ist nun erfolgt.<br><br>";
            $body  .= "Besten Dank f&uuml;r Ihr Verst&auml;ndnis.<br><br>";
            $body  .= file_get_contents($footer);

        // wenn Stornierung
        } elseif ($type == "cancelClient") {

            // definitions
            $subject    = "Stornierung Ihrer Bestellung";

            // make content
            $body   = "Guten Tag " . $row['firstname'] . " " . $row['lastname'] . "<br><br>";
            $body  .= "Sie haben auf meiner Website <i>www.schlosser-stubenwagen.ch</i> am $bDate den Artikel <i>" . $row['name'] . "</i> (" . $row['category'] . ") erworben.<br><br>";
            $body  .= "Auf Ihren pers&ouml;nlichen Wunsch hin habe ich die Bestellung soeben storniert.<br><br>";
            $body  .= file_get_contents($footer);

        // wenn Zahlungseingang
        } elseif ($type == "setPaydate") {

            // definitions
            $subject    = "Zahlungsbestätigung";

            // make content
            $body   = "Guten Tag " . $row['firstname'] . " " . $row['lastname'] . "<br><br>";
            $body  .= "Am " . $row['pDate'] . " ist Ihre Zahlung f&uuml;r den Artikel <i>" . $row['name'] . "</i> (" . $row['category'] . ") auf meinem Konto eingetroffen - Besten Dank!<br><br>";
            $body  .= "Innert den n&auml;chsten zwei Wochen werde ich Ihren Artikel auf der Post aufgeben. ";
            $body  .= "Falls es bei der Lieferung zu Verz&ouml;gerungen kommen sollte, werde ich Sie fr&uuml;hzeitig dar&uuml;ber informieren.<br><br>";
            $body  .= file_get_contents($footer);

        // wenn Zahlungseingang
        } elseif ($type == "setSenddate") {

            // definitions
            $subject    = "Sendebestätigung";
            $bodyFile1  = "$msgDir/bestellung_senddate_1";

            // make content
            $body   = "Guten Tag " . $row['firstname'] . " " . $row['lastname'] . "<br><br>";
            $body  .= "Der von Ihnen bestellte Artikel <i>" . $row['name'] . "</i> (" . $row['category'] . ") wurde am " . $row['sDate'] . " auf der Post aufgegeben ";
            $body  .= "und sollte in den n&auml;chsten Tagen bei Ihnen eintreffen.<br><br>";
            $body  .= "Besten Dank f&uuml;r Ihren Einkauf! Es w&uuml;rde mich freuen, wenn Sie wieder einmal meine Website besuchen.<br><br>";
            $body  .= file_get_contents($footer);

        } // eoi remember

    } // eo area

    // Mail senden
    require_once('library/PHPMailer/class.phpmailer.php');

    // set mail together
    $email = new PHPMailer();
    $email->SMTPDebug = true;
    $email->From      = 'info@schlosser-stubenwagen.ch';
    $email->FromName  = 'Schlosser Stubenwagenvermietung';
    $email->AddAddress($to);
    $email->Subject     = "=?UTF-8?Q?" . quoted_printable_encode($subject) . "?=";
    $email->MsgHTML($body);
    if ( isset($attachement) ) {
        $email->AddAttachment($attachement,$attachementName);
    } 
    if ( isset($attachementAVB) ) {
        $email->AddAttachment($attachementAVB,"AVB.pdf");
    }
    if ( isset($attachementMV) ) {
        $email->AddAttachment($attachementMV,"Mietvertrag.pdf");
    }

    // send and return failure
    if(!$email->Send()) {
        return "error";
    } 

} // eof sendAdminMail

##########################################
#                                        #
#              GET FREE ID               #
#                                        #
##########################################

function getFreeId($field,$table) {

    $dbcon  = dbConnect(DBUSER,DBPW);
    $sql    = "select max($field) as highestId from $table";
    $result = $dbcon->query($sql);
    dbClose($dbcon);

    $row    = $result->fetch_assoc();
    $freeId = $row['highestId'] + 1;

    return $freeId;

} // eof getFreeId

##########################################
#                                        #
#              ACTION BUTTON             #
#                                        #
##########################################


function actionButton($side,$link,$text) {

    $divClass   = $side == "left" ? "actionLeft" : "actionRight";

    // define divClass
    if ( $side == "center" ) {
        $divClass   = "actionCenter";
    } elseif ( $side == "left" ) {
        $divClass   = "actionLeft";
    } else {
        $divClass   = "actionRight";
    }

    echo "<a href='" . $link . "' class='not_menu'>";
        echo "<div class='" . $divClass . "' id='" . $divClass . "'>";
            echo "<table width='100%'>";
                echo "<tr>";
                    if ( $side == "left" ) {
                        echo "<td style='line-height:0'>";
                            echo "<img src='img/arrowLeft.png' />";
                        echo "</td>";
                    }
                    echo "<td valign='center'>";
                        echo "<b>" . $text . "</b>";
                    echo "</td>";
                    if ( ($side == "right") || ( $side == "center") ) {
                        echo "<td style='line-height:0'>";
                            echo "<img src='img/arrowRight.png' />";
                        echo "</td>";
                    }
                echo "</tr>";
            echo "</table>";
        echo "</div>";
    echo "</a>";

} // eof actionButton

##########################################
#                                        #
#            NEXT FREE DATE              #
#                                        #
##########################################


function calcNextFree($id) {

    // get date
    $today      = date("Y-m-d");
    $nextfree   = "unset";

    // get all reservation out of database and then start calculation
    $dbcon  = dbConnect(DBUSER,DBPW);
    $sql    = "select startdate_sys, enddate_sys from reservation where sw_id=$id and status != 'cancelled' order by startdate_sys asc";
    $result = $dbcon->query($sql);
    dbClose($dbcon);

    // if empty
    $resCount   = $result->num_rows;
    if ($resCount == 0 ) {
        $nextfree = "01.01.2000";
    } else {

        // make arrays and fill
        $startdate  = array();
        $enddate    = array();
        while ( $row = $result->fetch_assoc() ) {
            array_push($startdate,$row['startdate_sys']);
            array_push($enddate,$row['enddate_sys']);
        }

        // first check if available asap
        if ( $startdate[0] > date('Y-m-d', strtotime("+3 months 2 weeks")) ) {
            $nextfree = "01.01.2000";

        // wenn nur eine Reservation
        } elseif ( $resCount == 1 ) {

            // dann ist es: 1. Endtermin + 2 Wochen
            $nextfree    = date('d.m.Y', strtotime("+2 weeks", strtotime($enddate[0])));

        // wenn mehr als eine Reservation
        } else {

            // check each date
            for ( $i=0; $i<$resCount; $i++ ) {

                // wenn noch nicht die letzte Reservation
                if ( $i != ($resCount - 1) ) {

                    if ( $enddate[$i] < date('Y-m-d', strtotime("-3 months 2 weeks", strtotime($startdate[$i+1]))) ) {
                        // set nextfree
                        $nextfree           = date('d.m.Y', strtotime("+2 weeks", strtotime($enddate[$i])));
                        $i  = $resCount;

                    } // eoi nextfree defined

                // wenn beim letzten einfach setzen
                } else {
                    $nextfree           = date('d.m.Y', strtotime("+2 weeks", strtotime($enddate[$i])));
                }// eoi nicht letzter eintrag

            } // end of checking each reservation

        } // end of asap available

    } // eoi empty

    // update database
    $dbcon  = dbConnect(DBUSER,DBPW);
    $sql    = "update stubenwagen set nextfree = '" . $nextfree . "' where id_sw=" . $id;
    //echo $sql;
    $result = $dbcon->query($sql);
    dbClose($dbcon);

} // eof calcNextFree



##########################################
#                                        #
#           AVAILABILITY CHECK           #
#                                        #
##########################################

function calcAvailability($nextfree) {

    $today  = date('Y-m-d');

    $dateArray          = explode(".",$nextfree);
    list($d,$m,$y)      = $dateArray;
    $calcNextfree       = $y . "-" . $m . "-" . $d;

    if ( $calcNextfree < $today ) {
        return "now";
    } else {
        return $nextfree;
    }

    // check if in maintenance
    

} // eof function calcAvailability


##########################################
#                                        #
#           STATUS PRINTER               #
#                                        #
##########################################

function printStatus($type,$msg) {

    $title  = $type == "succeed" ? "Erfolg" : "Achtung";

    echo "<div class='status_" . $type . "' id='status_" . $type . "'>";
        echo "<b>$title</b></br>";
        echo $msg;
    echo "</div>";

} // eof printStatus

##########################################
#                                        #
#          CHECK IMAGE UPLOAD            #
#                                        #
##########################################

function checkImageUpload($uploaddir) {

    $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

        $message = 'Error uploading file';
        switch( $_FILES['userfile']['error'] ) {
            case UPLOAD_ERR_OK:
                $message = false;;
                break;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $message .= ' - file too larg bytes.';
                break;
            case UPLOAD_ERR_PARTIAL:
                $message .= ' - file upload was not completed.';
                break;
            case UPLOAD_ERR_NO_FILE:
                $message .= ' - zero-length file uploaded.';
                break;
            default:
                $message .= ' - internal error #'.$_FILES['userfile']['error'];
                break;
        }
        if( !$message ) {
            if( !is_uploaded_file($_FILES['userfile']['tmp_name']) ) {
                $message = 'Error uploading file - unknown error.';
            } else {
                // Let's see if we can move the file...
                if( !move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile) ) { // No error supporession so we can see the underlying error.
                    $message = 'Error uploading file - could not save upload (this will probably be a permissions problem in '.$uploaddir.')';
                } else {
                    $message = 'okay';
                }
            }
        }

    return $message;

} // eof checkImageUpload()

?>
