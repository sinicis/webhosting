<?php

// Global
$statusType = "";
$statusMsg  = "";

// Check if on subsite
$sub    = (!isset($_GET['sub'])) ? "" : $_GET['sub'];

// check action
$action     = (!isset($_GET['confirm'])) && ( !isset($_GET['decline'])) ? "no" : "yes";

// Aktionen
if (!isset($_GET['action'])) {
    $invAction = "no";
} else {
    $invAction = $_GET['action'];
}

// TITEL
echo "<h1>Inventar-Verwaltung</h1>";


##############################
#                            #
#          OVERVIEW          #
#                            #
##############################

if ( ($sub == "" ) || ($sub == "overview")) {

    if ( !isset($_GET['action'])) {
        // relax and light a spliff :)

    ########
    #
    # ACTIVATE ALL
    #
    #######

    } elseif ( $_GET['action'] == "activateAll" ) {

        // update db
        $sql    = "update artikel set active='yes' where cat_id=" . $_GET['cat'];
        $dbcon  = dbConnect(DBUSER,DBPW);
        $dbcon->query($sql);

        // info for status
        $result = getCategory($_GET['cat']);
        $row    = $result->fetch_assoc();

        // status
        $statusType = "succeed";
        $statusMsg  = "Alle Artikel der Kategorie <i>" . $row['category'] . "</i> wurden aktiviert!";

    } elseif ( $_GET['action'] == "deactivateAll" ) {

        // update db
        $sql    = "update artikel set active='no' where cat_id=" . $_GET['cat'];
        $dbcon  = dbConnect(DBUSER,DBPW);
        $dbcon->query($sql);

        // info for status
        $result = getCategory($_GET['cat']);
        $row    = $result->fetch_assoc();

        // status
        $statusType = "succeed";
        $statusMsg  = "Alle Artikel der Kategorie <i>" . $row['category'] . "</i> wurden deaktiviert!";

    } // eof actions

    echo "<h2>&Uuml;bersicht</h2>";

    ##############################
    #                            #
    #          STATUS            #
    #                            #
    ##############################

    // Status
    if ( $statusType != "" ) {
        printStatus($statusType,$statusMsg);
        echo "</br>";
    }

    // get Category
    $result = getCategory("");

    // Table header
    echo "<table width='100%'>";
        echo "<tr align='center'>";
            echo "<td class='top'>Kategorie</td>";
            echo "<td class='top'>Artikel (inaktiv/ausverkauft)</td>";
            echo "<td class='top'>Aktionen</td>";
        echo "</tr>";

        // tableDesignCounter
        $tdCount = 1;

        while ($row = $result->fetch_assoc()){

            // tableDesign
            $tdClass = ($tdCount % 2) == 0 ? "class='adminEven'" : "class='adminOdd'";
            $tdCount++;

            // get all
            $category       = $row['category'];
            $idCat          = $row['id_cat'];
            $status         = $row['active'] == "yes" ? "<font color='green'><b>AKTIV</b></font>" : "<font color='red'><b>INAKTIV</b></font>";
            $sqlArt         = "select count(artikel.name) as count from artikel where cat_id=$idCat";
            $sqlAct         = "select count(artikel.name) as count from artikel where cat_id=$idCat and active='no'";
            $sqlOut         = "select count(artikel.name) as count from artikel where cat_id=$idCat and inventory='0'";
            $dbcon          = dbConnect(DBUSER,DBPW);
            $resultArt      = $dbcon->query($sqlArt);
            $rowArt         = $resultArt->fetch_assoc();
            $resultAct      = $dbcon->query($sqlAct);
            $rowAct         = $resultAct->fetch_assoc();
            $resultOut      = $dbcon->query($sqlOut);
            $rowOut         = $resultOut->fetch_assoc();

            // Warn-Meldung
            $warning    = "";
            if ( $rowArt['count'] == 0 ) {
                $warning    = "<font color='red'><b>Keine Artikel!</b></font>";
            } elseif ( $rowAct['count'] == $rowArt['count'] ) {
                $warning    = "<font color='red'><b>Alle Artikel inaktiv!</b></font>";
            } elseif ( $rowAct['count'] == $rowArt['count'] ) {
                $warning    = "<font color='red'><b>Alle Artikel ausverkauft!</b></font>";
            }

            // print all
            echo "<tr align='center'>";
                echo "<td $tdClass>$category</br>$status</td>";
                echo "<td $tdClass>" . $rowArt['count'] . " (" . $rowAct['count'] . "/" . $rowOut['count'] . ") $warning </td>";
                echo "<td $tdClass>";
                    echo "<a class='not_menu' href='?top=inventar&sub=overview&action=activateAll&cat=" . $row['id_cat'] . "'>Alle aktivieren</a></br>";
                    echo "<a class='not_menu' href='?top=inventar&sub=overview&action=deactivateAll&cat=" . $row['id_cat'] . "'>Alle deaktivieren</a>";
                echo "</td>";
            echo "</tr>";

        } // eof printing cats

    echo "</table>";

    // Artikel ohne Kategorie
    $sql    = "select count(id_art) as count where cat_id=0";
    $dbcon  = dbConnect(DBUSER,DBPW);
    $count  = $result->num_rows;
//    $row    = $result->fetch_assoc();

    // show if artikel without category
    //if ( $row['count'] != 0 ) {
    if ( $count != 0 ) {
        echo "</br>";
        echo "<b>ACHTUNG:</b>";
        echo " " . $count . " Artikel sind keiner Kategorie zugewiesen!";
    }

}


##############################
#                            #
#    KATEGORIE VERWALTUNG    #
#                            #
##############################

if ( ( $sub == "category" ) || ( $sub == "categorynew") ) {
    echo "<h2>Kategorie-Verwaltung</h2>";

    // Kategorien auslesen

if ( !isset($_GET['action']) ) {
    # chill relax and light a spliff :)

######
#
# ACTIVATE
#
######

} elseif ( $_GET['action'] == 'activate' )  {

    // update db
    $sql    = "update category set active = 'yes' where id_cat=" . $_GET['cat'];
    $dbcon  = dbConnect(DBUSER,DBPW);
    $dbcon->query($sql);
    dbClose($dbcon);

    // get name
    $result = getCategory($_GET['cat']);
    $row    = $result->fetch_assoc();

    // write status
    $statusType = "succeed";
    $statusMsg  = "Die Kategorie <i>" . $row['category'] . "</i> wurde aktiviert!</br>";
    $statusMsg .= "<b>Achtung:</b> Die Kategorie wird erst angezeigt, wenn sie Artikel zugewiesen hat!";

######
#
# DEACTIVATE
#
######

} elseif ( $_GET['action'] == 'deactivate' )  {

    // get artikels
    $sql    = "select count(id_art) as count from artikel where inventory != 0 and active = 'yes' and cat_id=" . $_GET['cat'];
    $dbcon  = dbConnect(DBUSER,DBPW);
    $result = $dbcon->query($sql);
    $row    = $result->fetch_assoc();
    dbClose($dbcon);

    // if 0 artikel
    if ( $row['count'] == 0 ) {

        // update db
        $sql    = "update category set active = 'no' where id_cat=" . $_GET['cat'];
        $sqlArt = "update artikel set active='no' where cat_id=". $_GET['cat'];
        $dbcon  = dbConnect(DBUSER,DBPW);
        $dbcon->query($sql);
        $dbcon->query($sqlArt);

        // get catname
        $result = getCategory($_GET['cat']);
        $row    = $result->fetch_assoc();
        dbClose($dbcon);

        // write status
        $statusType = "succeed";
        $statusMsg  = "Die Kategorie <i>" . $row['category'] . "</i> wurde deaktiviert!</br>";

    // if not 0 artikel
    } else {

        // Infos fuer Meldung
        $count      = $row['count'];
        $result     = getCategory($_GET['cat']);
        $row        = $result->fetch_assoc();

        // Meldung ausgeben 
        echo "<div class='status_failed' id='status_failed'>";
            echo "<b>ACHTUNG</b></br>";
            echo "Im Shop sind noch " . $count . " Artikel vorhanden, welcher der Kategorie <i>" . $row['category'] . "</i> zugewiesen sind! ";
            echo "Wenn du die Kategorie trotzdem deaktivierst, werden die Artikel nicht mehr im Shop angezeigt!</br></br>";
            echo "Hier klicken: <a href='?top=inventar&sub=category&action=forceDeactivate&cat=" . $row['id_cat'] . "' class='not_menu'>Definitiv deaktivieren</a></br>";
        echo "</div>";
        echo "</br>";

    } // end of if not 0 artikel

} elseif ( $_GET['action'] == 'forceDeactivate' )  {

    // update db
    $sql    = "update category set active = 'no' where id_cat=" . $_GET['cat'];
    $sqlArt = "update artikel set active='no' where cat_id=". $_GET['cat'];
    $dbcon  = dbConnect(DBUSER,DBPW);
    $dbcon->query($sql);
    $dbcon->query($sqlArt);

    // get catname
    $result = getCategory($_GET['cat']);
    $row    = $result->fetch_assoc();
    dbClose($dbcon);

    // write status
    $statusType = "succeed";
    $statusMsg  = "Die Kategorie <i>" . $row['category'] . "</i> wurde deaktiviert!</br>";

######
#
# DELETE
#
######

} elseif ( $_GET['action'] == 'delete' )  {

    // get artikels
    $sql    = "select count(id_art) as count from artikel where cat_id=" . $_GET['cat'];
    $dbcon  = dbConnect(DBUSER,DBPW);
    $result = $dbcon->query($sql);
    $row    = $result->fetch_assoc();
    dbClose($dbcon);

    // if 0 artikel
    if ( $row['count'] == 0 ) {

        // get catname
        $result = getCategory($_GET['cat']);
        $row    = $result->fetch_assoc();

        // update db
        $sqlFk0 = "SET foreign_key_checks = 0";
        $sqlFk1 = "SET foreign_key_checks = 1";
        $sql    = "delete from category where id_cat=" . $_GET['cat'];
        $sqlArt = "update artikel set cat_id=0, active='no' where cat_id=". $_GET['cat'];
        $dbcon  = dbConnect(DBUSER,DBPW);
        $dbcon->query($sqlFk0);
        $dbcon->query($sql);
        $dbcon->query($sqlArt);
        $dbcon->query($sqlFk1);

        // write status
        $statusType = "succeed";
        $statusMsg  = "Die Kategorie <i>" . $row['category'] . "</i> wurde gel&ouml;scht!</br>";

    // if not 0 artikel
    } else {

        // Infos fuer Meldung
        $count      = $row['count'];
        $result     = getCategory($_GET['cat']);
        $row        = $result->fetch_assoc();

        // Meldung ausgeben 
        echo "<div class='status_failed' id='status_failed'>";
            echo "<b>ACHTUNG</b></br>";
            echo "Der Kategorie <i>" . $row['category'] . "</i> sind noch Artikel zugewiesen! ";
            echo "Wenn du die Kategorie trotzdem l&ouml;scht, werden die Artikel keiner Kategorie mehr zugewiesen sein!</br></br>";
            echo "Hier klicken: <a href='?top=inventar&sub=category&action=forceDelete&cat=" . $row['id_cat'] . "' class='not_menu'>Definitiv l&ouml;schen</a></br>";
        echo "</div>";
        echo "</br>";

    } // end of if not 0 artikel

} elseif ( $_GET['action'] == 'forceDelete' )  {

    // get catname
    $result = getCategory($_GET['cat']);
    $row    = $result->fetch_assoc();

    // update db
        $sqlFk0 = "SET foreign_key_checks = 0";
        $sqlFk1 = "SET foreign_key_checks = 1";
        $sql    = "delete from category where id_cat=" . $_GET['cat'];
        $sqlArt = "update artikel set cat_id=0, active='no' where cat_id=". $_GET['cat'];
        $dbcon  = dbConnect(DBUSER,DBPW);
        $dbcon->query($sqlFk0);
        $dbcon->query($sql);
        $dbcon->query($sqlArt);
        $dbcon->query($sqlFk0);

    // write status
    $statusType = "succeed";
    $statusMsg  = "Die Kategorie <i>" . $row['category'] . "</i> wurde gel&ouml;scht!</br>";

######
#
# RENAME
#
######

} elseif (( $_GET['action'] == 'rename' ) || ( $_GET['action'] == "saveRename" )) {

    $id = $_GET['action'] == 'rename' ? $_GET['cat'] : $_POST['id_cat'];

    // get infos
    $result = getCategory($id);
    $row    = $result->fetch_assoc();

    // SAVE!!!
    if ( $_GET['action'] == "saveRename" ) {

        $error  = "occured";

        // check fields
        if (!is_string($_POST['catName'])) {

            $statusType = "failed";
            $statusMsg  = "Der neue Name <i>" . $_POST['catName'] . "</i> enth&auml;lt unerlaubte Zeichen.";
        } elseif ( $_POST['catName'] == $row['category'] ) {
            $statusType = "failed";
            $statusMsg  = "Der neue und alte Name ist identisch -> keine &Auml;nderung!.";
        } else {
            $error      = "none";
            $statusType = "succeed";
            $statusMsg  = "Der neue Name <i>" . $_POST['catName'] . "</i> wurde &uuml;bernommen!";
        }// eoi string

        if ( $error == "none" ) {
            $sql    = "update category set category = '" . $_POST['catName'] . "' where id_cat=" . $id;
            $dbcon  = dbConnect(DBUSER,DBPW);
            $dbcon->query($sql);
            dbClose($dbcon);
        } // eo insertion

    } // eoi saving

    //if (( $_GET['action'] == 'rename' ) || ( $error == 'occured' )) {
    if ( $_GET['action'] == 'rename' ) {

        // Formular ausgeben
        echo "<form accept-charset='UTF-8' method='post' name='renameCat' id='renameCat' action='?top=inventar&sub=category&action=saveRename'>";
            echo "<table><tr>";
                echo "<td>Aktueller Name</td>";
                echo "<td>" . $row['category'] . "</td>";
            echo "</tr><tr>";
                echo "<td>Neuer Name</td>";
                echo "<td><input type='text' id='catName' name='catName' size'40' required /></td>";
            echo "</tr><tr>";
                echo "<td></td>";
                echo "<td><input type='submit' value='Kategorie umbenennen' /></td>";
            echo "</tr></table>";
            echo "<input type='hidden' name='id_cat' id='id_cat' value='" . $_GET['cat'] . "' />";
        echo "</form>";
        echo "</br></br>";
    } // eoi formular

######
#
# NEW PIC
#
######

} elseif (( $_GET['action'] == 'newPic' ) || ( $_GET['action'] == "saveNewPic" )) {

    $id = $_GET['action'] == 'newPic' ? $_GET['cat'] : $_POST['id_cat'];

    // get infos
    $result = getCategory($id);
    $row    = $result->fetch_assoc();

    // SAVE!!!
    if ( $_GET['action'] == "saveNewPic" ) {

        $error  = "occured";

        // check fields
        $uploaddir  = '/home/ironsmit/public_html/schlosser-stubenwagen.ch/www/img/category/tmp/';
        $photodir   = '/home/ironsmit/public_html/schlosser-stubenwagen.ch/www/img/category/';
        $message    = checkImageUpload($uploaddir);
        $error      = $message != "okay" ? "occured" : "none";

        // if no error
        if ( $error == "none" ) {

            // move and rename file
            rename($uploaddir . "/" . $_FILES['userfile']['name'],$photodir . "/" . $_POST['id_cat'] . ".jpg");

            // status
            $statusType = "succeed";
            $statusMsg  = "Der Upload des neuen Bilds f&uuml;r die Kategorie <i>" . $row['category'] . "</i> hat geklappt!";

        // if error
        } else {

            // status
            $statusType = "failed";
            $statusMsg  = "Der Upload des neuen Bilds f&uuml;r die Kategorie <i>" . $row['category'] . "</i> hat nicht geklappt - versuche es nochmal!";

        } // eoi error

    } // eoi saving

    if ( $_GET['action'] == 'newPic' ) {

        // Formular ausgeben
        echo "<form accept-charset='UTF-8' enctype='multipart/form-data' method='post' name='newPic' id='newPic' action='?top=inventar&sub=category&action=saveNewPic'>";
            echo "<table><tr>";
                echo "<td>Name</td>";
                echo "<td>" . $row['category'] . "</td>";
            echo "</tr><tr>";
                echo "<td>Neues Bild</td>";
                echo "<input type='hidden' name='MAX_FILE_SIZE' value='4194304' />";
                echo "<td><input name='userfile' type='file' required/></td>";
            echo "</tr><tr>";
                echo "<td></td>";
                echo "<td><input type='submit' value='Bild hochladen' /></td>";
            echo "</tr></table>";
            echo "<input type='hidden' name='id_cat' id='id_cat' value='" . $_GET['cat'] . "' />";
        echo "</form>";
        echo "</br></br>";
    } // eoi formular


######
#
# NEW CATEGORY
#
######

} elseif (( $_GET['action'] == 'newCat' ) || ( $_GET['action'] == "saveNewCat" )) {

    // Titel
    echo "<b>Neue Kategorie erstellen</b></br></br>";

    // SAVE!!!
    if ( $_GET['action'] == "saveNewCat" ) {

        // Status
        $statusType = "failed";
        $statusMsg  = "Die Erstellung der Kategorie <i>" . $_POST['catName'] . "</i> hat nicht funktioniert! Versuche es erneut.";

        // check error
        if (is_string($_POST['catName'])) {
            // check image
            $uploaddir  = '/home/ironsmit/public_html/schlosser-stubenwagen.ch/www/img/category/tmp/';
            $photodir   = '/home/ironsmit/public_html/schlosser-stubenwagen.ch/www/img/category/';
            $message    = checkImageUpload($uploaddir);
            $error      = $message != "okay" ? "occured" : "none";
        }

        if ( $error == "none") {

            // check active
            if ( !isset($_POST['active']) ) {
                $active = "no";
            } elseif ( $_POST['active'] == "yes") {
                $active = "yes";
            }

            // check active
            if (isset($active)) {

                // Database
                $sql        = "insert into category values (" . $_POST['id_cat'] . ",'" . $_POST['catName'] . "','img/category/" . $_POST['id_cat'] . ".jpg','" . $active . "')";
                $dbcon      = dbConnect(DBUSER,DBPW);
                $dbcon->query($sql);
                dbClose($dbcon);

                // move and rename file
                rename($uploaddir . "/" . $_FILES['userfile']['name'],$photodir . "/" . $_POST['id_cat'] . ".jpg");

                // Status
                $statusType = "succeed";
                $statusMsg  = "Die Kategorie <i>" . $_POST['catName'] . "</i> wurde erfolgreich erstellt.";

            } // eoi active checked
        } // eoi insertion completed
    }// eoi saving


    if (( $_GET['action'] == 'newCat' ) || ( $error == 'occured' )) {
        // get next id
        $dbcon  = dbConnect(DBUSER,DBPW);
        $sql    = "select id_cat from category order by id_cat desc limit 1";
        $result = $dbcon->query($sql);
        dbClose($dbcon);
        $row    = $result->fetch_assoc();
        $next_id= $row['id_cat'] + 1;

        // Formular ausgeben
        echo "<form accept-charset='UTF-8' enctype='multipart/form-data' method='post' name='newCat' id='newCat' action='?top=inventar&sub=category&action=saveNewCat'>";
            echo "<table><tr>";
                echo "<td>Name</td>";
                echo "<td><input type='text' id='catName' name='catName' size'40' required /></td>";
            echo "</tr><tr>";
                echo "<td>Aktivieren</td>";
                echo "<td><input type='checkbox' name='active' value='yes' /></td>";
            echo "</tr><tr>";
                echo "<td>Photo</td>";
                echo "<input type='hidden' name='MAX_FILE_SIZE' value='4194304' />";
                echo "<td><input name='userfile' type='file' required/></td>";
            echo "</tr><tr>";
                echo "<td></td>";
                echo "<td><input type='submit' value='Kategorie erstellen' /></td>";
            echo "</tr><tr>";
                echo "<td colspan='2'></br><a href='?top=inventar&sub=category' class='not_menu' >Vorgang abbrechen</a></td>";
            echo "</tr></table>";
            echo "<input type='hidden' id='id_cat' name='id_cat' size'40' value='" . $next_id . "' />";
        echo "</form>";
        echo "</br></br>";
    } // eoi formular

}

##############################
#                            #
#          STATUS            #
#                            #
##############################

// Status
if ( $statusType != "" ) {
    printStatus($statusType,$statusMsg);
    echo "</br>";
}

######
#
# OVERVIEW
#
######

    // Titel
    echo "<b>Alle Kategorien</b></br></br>";

    $result     = getCategory("");

    // Table-Header
    $i  = 0;
    echo "<table width='100%'>";
        echo "<tr>";
        while ($row = $result->fetch_assoc()) {
            $i++;
            echo "<td class='sw_overview' valign='top'>";
                echo "<img src='../" . $row['photo'] . "' width='180px'/></br>";
                echo "<b>" . $row['category'] . "</b></br>";

                // AKTIV / INAKTIV ?
                if ( $row['active'] == "yes" ) {
                    echo "<font color='green'><b>AKTIV</b></font></br>";
                } else {
                    echo "<font color='red'><b>INAKTIV</b></font></br>";
                }

                // ARTIKEL
                $sqlArt     = "select count(id_art) as count from artikel where cat_id=" . $row['id_cat'];
                $sqlAct     = "select count(id_art) as count from artikel where cat_id=" . $row['id_cat'] . " and active = 'no'";
                $sqlInv     = "select count(id_art) as count from artikel where cat_id=" . $row['id_cat'] . " and inventory = 0";
                $dbcon      = dbConnect(DBUSER,DBPW);
                $resultArt  = $dbcon->query($sqlArt);
                $resultAct  = $dbcon->query($sqlAct);
                $resultInv  = $dbcon->query($sqlInv);
                $rowArt     = $resultArt->fetch_assoc();
                $rowAct     = $resultAct->fetch_assoc();
                $rowInv     = $resultInv->fetch_assoc();
                
                // Artikel ausgabe
                echo "Artikel " . $rowArt['count'] . "(" . $rowAct['count'] . "/" .  $rowInv['count'] . ")</br>";

                // AKTIV / INAKTIV ?
                if ( $row['active'] == "yes" ) {
                    echo "<a href='?top=inventar&sub=category&action=deactivate&cat=" . $row['id_cat'] . "' class='not_menu'>Deaktivieren</a></br>";
                } else {
                    echo "<a href='?top=inventar&sub=category&action=activate&cat=" . $row['id_cat'] . "' class='not_menu'>Aktivieren</a></br>";
                }
                echo "<a href='?top=inventar&sub=category&action=newPic&cat=" . $row['id_cat'] . "' class='not_menu'>Neues Bild</a></br>";
                echo "<a href='?top=inventar&sub=category&action=rename&cat=" . $row['id_cat'] . "' class='not_menu'>Umbenennen</a></br>";
                echo "<a href='?top=inventar&sub=category&action=delete&cat=" . $row['id_cat'] . "' class='not_menu'>Kategorie l&ouml;schen</a></br></br>";
            echo "</td>";

            if (($i % 3) == 0) {
                echo "</tr><tr>";
            }
        }
        echo "</tr>";
    echo "</table>";


} // end of Kategorie Verwaltung



##############################
#                            #
#      ARTIKEL VERWALTUNG    #
#                            #
##############################

if ( ($sub == "artikel" ) || ($sub == "artikelnew" )){

    include('artikel-mgmt.php');

} // eoi artikel


##############################
#                            #
#      WAGEN VERWALTUNG      #
#                            #
##############################

if ( ($sub == "wagen" ) || ($sub == "wagennew" )){

    include('wagen-mgmt.php');

} // eoi wagen

##############################
#                            #
#    OVERVIEW                #
#                            #
##############################

// Titel
//echo "<h1>Inventar-Verwaltung</h1>";
?>
