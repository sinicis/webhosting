<?php

// Global
$statusType = "";
$statusMsg  = "";
$statusQuery= "";
$error      = "none";

// building link
$link       = "top=inventar&sub=wagen";
$link      .= !isset($_GET['size_id']) ? "" : "&size_id=" . $_GET['size_id'];
$link      .= !isset($_GET['active']) ? "" : "&active=" . $_GET['active'];
$link      .= !isset($_GET['main']) ? "" : "&main=" . $_GET['main'];

// Titel
echo "<h2>Stubenwagen-Verwaltung</h2>";

######
#
# EDIT WAGEN
#
######


######
#
# EDIT WHOLE WAGEN
#
######


if ( !isset($_GET['edit'])) {
    // relax and light a spliff :)

} elseif (( $_GET['edit'] > 0 ) && ( $_GET['edit'] < 99999 )) {

    // get artikel infos
    $dbcon  = dbConnect(DBUSER,DBPW);
    $sql    = "select * from stubenwagen where id_sw=" . $_GET['edit'];
    $result = $dbcon->query($sql);
    dbClose($dbcon);
    $row    = $result->fetch_assoc();

    // check if artikel exist
    if ( $result->num_rows != 0 ) {


//echo "<pre>"; print_r($row); echo "</pre>";
//echo "<pre>"; print_r($_POST); echo "</pre>";
//echo "<pre>"; print_r($_FILES); echo "</pre>";


        // save id
        $id = $_GET['edit'];

        // set values
        $nameValue      = $row['name'];
        $activeValue    = $row['active'] == "yes" ? "checked" : "";
        $descValue      = $row['description'];
        $imgValue       = $row['mainphoto'];
        $korbValue      = $row['korb'];
        $aussValue      = $row['ausstattung'];
        $vorhangValue   = $row['vorhang'];
        $innenValue     = $row['innen'];
        $leint1Value    = $row['leintuch_1'];
        $leint2Value    = $row['leintuch_2'];
        $duvetValue     = $row['duvet'];
        $duvet1Value    = $row['duvet_1'];
        $duvet2Value    = $row['duvet_2'];
        $matratzeValue  = $row['matratze'];
        $ks1Value       = $row['kopfspuck_1'];
        $ks2Value       = $row['kopfspuck_2'];
        $ks3Value       = $row['kopfspuck_3'];

        // catValue
        $result     = getSize($row['size_id']);
            $rowSize    = $result->fetch_assoc();
            $sizeValue  = $rowSize['size'];
            $sizeIdValue= $rowSize['id_size'];
        }

        // Titel
        echo "<b>Stubenwagen bearbeiten</b></br></br>";

        // SAVE!!!
        if ( !isset($_GET['action']) ) {
            // chill and light a spliff
        } elseif ( $_GET['action'] == "saveEdit" ) {

            // Status
            $statusType = "failed";
            $statusMsg  = "Die Bearbeitung des Stubenwagen <i>" . $_POST['wagenName'] . "</i> hat nicht funktioniert! Versuche es erneut.</br>";
            $statusMsg .= "Fehlermeldung: ";
            $error      = "none";
            $errorMsg   = "none";

            // check error
            // Name is string?
            if ((strpbrk($_POST['wagenName'], '\'";<>')) || (!is_string($_POST['wagenName']))) {
                $error      = "occured";
                $errorMsg   = "Name enth&auml;t unerlaubte Zeichen!";
            } else {
                // Groesse ausgewaehlt?
                if (( $_POST['wagenSize'] == 0 ) || ( $_POST['wagenSize'] == "" )) {
                    $resultSize = getSize($_POST['wagenSize']);
                    if ( $resultSize->num_rows == 0 ) {
                        $error      = "occured";
                        $errorMsg   = "Keine g&uuml;ltige Gr&ouml;sse ausgew&auml;hlt!";
                    }
                } else {
                    // Korb angabe is string?
                    if ((strpbrk($_POST['korb'], '\'";<>')) || (!is_string($_POST['korb']))) {
                        $error      = "occured";
                        $errorMsg   = "Die Angaben zum Korb enthalten unerlaubte Zeichen!";
                    } else {
                        // Innenausstattung angabe is string?
                        if ((strpbrk($_POST['innen'], '\'";<>')) || (!is_string($_POST['innen']))) {
                            $error      = "occured";
                            $errorMsg   = "Die Angaben zur Ausstattung enthalten unerlaubte Zeichen!";
                        } else {
                            // Vorhang angabe is string?
                            if ((strpbrk($_POST['vorhang'], '\'";<>')) || (!is_string($_POST['vorhang']))) {
                                $error      = "occured";
                                $errorMsg   = "Die Angaben zum Vorhang enthalten unerlaubte Zeichen!";
                            } else {
                                // Leintuch 1 angabe is string?
                                if ((strpbrk($_POST['leintuch_1'], '\'";<>')) || (!is_string($_POST['leintuch_1']))) {
                                    $error      = "occured";
                                    $errorMsg   = "Die Angaben zum Leintuch (1. Zeile) enthalten unerlaubte Zeichen!";
                                } else {
                                    // Leintuch 2 angabe is string?
                                    if ((strpbrk($_POST['leintuch_2'], '\'";<>')) || (!is_string($_POST['leintuch_2']))) {
                                        $error      = "occured";
                                        $errorMsg   = "Die Angaben zum Leintuch (2. Zeile) enthalten unerlaubte Zeichen!";
                                    } else {
                                        // Duvet angabe is string?
                                        if ((strpbrk($_POST['duvet'], '\'";<>')) || (!is_string($_POST['duvet']))) {
                                            $error      = "occured";
                                            $errorMsg   = "Die Angaben zum Duvet enthalten unerlaubte Zeichen!";
                                        } else {
                                            // Duvet 1 angabe is string?
                                            if ((strpbrk($_POST['duvet_1'], '\'";<>')) || (!is_string($_POST['duvet_1']))) {
                                                $error      = "occured";
                                                $errorMsg   = "Die Angaben zu den Duvetbez&uuml;gen (1. Zeile) enthalten unerlaubte Zeichen!";
                                            } else {
                                                // Duvet 2 angabe is string?
                                                if ((strpbrk($_POST['duvet_2'], '\'";<>')) || (!is_string($_POST['duvet_2']))) {
                                                    $error      = "occured";
                                                    $errorMsg   = "Die Angaben zu den Duvetbez&uuml;gen (2. Zeile) enthalten unerlaubte Zeichen!";
                                                } else {
                                                    // Matratze angabe is string?
                                                    if ((strpbrk($_POST['matratze'], '\'";<>')) || (!is_string($_POST['matratze']))) {
                                                        $error      = "occured";
                                                        $errorMsg   = "Die Angaben zum Matratze enthalten unerlaubte Zeichen!";
                                                    } else {
                                                        // KS 1 angabe is string?
                                                        if ((strpbrk($_POST['kopfspuck_1'], '\'";<>')) || (!is_string($_POST['kopfspuck_1']))) {
                                                            $error      = "occured";
                                                            $errorMsg   = "Die Angaben zum Kopfspuck-Tuch (1. Zeile) enthalten unerlaubte Zeichen!";
                                                        } else {
                                                            // KS 2 angabe is string?
                                                            if ((strpbrk($_POST['kopfspuck_2'], '\'";<>')) || (!is_string($_POST['kopfspuck_2']))) {
                                                                $error      = "occured";
                                                                $errorMsg   = "Die Angaben zum Kopfspuck-Tuch (2. Zeile) enthalten unerlaubte Zeichen!";
                                                            } else {
                                                                // KS 3 angabe is string?
                                                                if ((strpbrk($_POST['kopfspuck_3'], '\'";<>')) || (!is_string($_POST['kopfspuck_3']))) {
                                                                    $error      = "occured";
                                                                    $errorMsg   = "Die Angaben zum Kopfspuck-Tuch (3. Zeile) enthalten unerlaubte Zeichen!";
                                                                } else {
                                                                    // Zusatzinfos angabe is string?
                                                                    if ((strpbrk($_POST['desc'], '\'";<>')) || (!is_string($_POST['desc']))) {
                                                                        $error      = "occured";
                                                                        $errorMsg   = "Die Zusatzinfos enthalten unerlaubte Zeichen!";
                                                                    }
                                                                }
                                                            } // eo check toy size
                                                        } // eo check body size
                                                    } // eo check inventory
                                                } // eo check porto
                                            } // eo check price
                                        } // eo check error
                                    } // eo check error
                                } // eo check toy size
                            } // eo check body size
                        } // eo check inventory
                    } // eo check porto
                } // eo check price
            } // eo check error

//    echo "<pre>" . $error . "/" . $errorMsg . "</pre>";

            // check imagewenn kein Fehler
            if ( ($error == "none") && ($_FILES['userfile']['name'] != "") ) {
                // check image
                $uploaddir  = '/home/ironsmit/public_html/schlosser-stubenwagen.ch/www/img/mainphoto/tmp/';
                $photodir   = '/home/ironsmit/public_html/schlosser-stubenwagen.ch/www/img/mainphoto/';
                $message    = checkImageUpload($uploaddir);
                $error      = $message != "okay" ? "occured" : "none";
                $errorMsg   = $message != "okay" ? "Bild-Upload fehlgeschlagen!" : "none";
            }

            // wenn kein Fehler
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
                    $sql        = "update stubenwagen set active ='" . $active . "', size_id=" . $_POST['wagenSize'] . ", name='" . utf8_decode($_POST['wagenName']) . "',";
                    $sql       .= "description='" . utf8_decode($_POST['desc']) . "', korb ='" . utf8_decode($_POST['korb']) . "', ausstattung='" . utf8_decode($_POST['innen']) . "',";
                    $sql       .= "vorhang='" . utf8_decode($_POST['vorhang']) . "', leintuch_1='" . utf8_decode($_POST['leintuch_1']) . "' , leintuch_2='" . utf8_decode($_POST['leintuch_2']) . "',";
                    $sql       .= "duvet='" . utf8_decode($_POST['duvet']) . "',duvet_1='" . utf8_decode($_POST['duvet_1']) . "' , duvet_2='" . utf8_decode($_POST['duvet_2']) . "',matratze='" . utf8_decode($_POST['matratze']) . "',";
                    $sql       .= "kopfspuck_1='" . utf8_decode($_POST['kopfspuck_1']) . "' , kopfspuck_2='" . utf8_decode($_POST['kopfspuck_2']) .  "' , kopfspuck_3='" . utf8_decode($_POST['kopfspuck_3']) . "'";
                    $sql       .= " where id_sw=$id";

                    $dbcon      = dbConnect(DBUSER,DBPW);
                    $dbcon->query("set names='utf8'");
                    $dbcon->query($sql);
                    dbClose($dbcon);

                    // move and rename file
                    if ($_FILES['userfile']['name'] != "") {
                        rename($uploaddir . "/" . $_FILES['userfile']['name'],$photodir . "/" . $_POST['id_sw'] . ".jpg");
                    }

                    // Status
                    $statusType = "succeed";
                    $statusMsg  = "Der Stubenwagen <i>" . $_POST['wagenName'] . "</i> wurde erfolgreich bearbeitet.</br>";
                    $statusMsg .= "(Du siehst den bearbeiteten Stubenwagen direkt unter dieser Meldung)";
                    $statusQuery= "select * from artikel where id_sw=" . $_POST['id_sw'];

                } // eoi active checked
            } // eoi insertion completed

            // if error
            else {
                $statusMsg  .= $errorMsg;
            }
        }// eoi saving


        ######
        #     
        #    STATUS
        #     
        ######

        // Status
        if ( $statusType != "" ) {
            printStatus($statusType,$statusMsg);
            echo "</br>";
        }

        if ( $statusQuery != "") {
            $dbcon  = dbConnect(DBUSER,DBPW);
            $result = $dbcon->query($statusQuery);
            dbClose($dbcon);

            // Admin-Part
            printWInfosAdmin($row);

            // restliche Inofs
            printWInfos($row,"admin");

            echo "</br></br>";

        }

        if ( (!isset($_GET['action']) ) || ( $error == 'occured' )) {

            // Formular ausgeben
            echo "<form accept-charset='UTF8' enctype='multipart/form-data' method='post' name='editWagen' id='editWagen' action='?" . $link . "&edit=" . $id . "&action=saveEdit'>";
                echo "<table><tr>";
                    // photo
                    echo "<td width='190px' valign='top' rowspan='15'>";
                        echo "<img width='170px' src='../" . $imgValue . "' />";
                        echo "</td>";
                    echo "<td>Name</td>";
                    echo "<td><input type='text' id='wagenName' name='wagenName' size='40' value='" . $nameValue . "' required /></td>";
                echo "</tr><tr>";
                    echo "<td>Gr&ouml;sse</td>";
                    echo "<td><select name='wagenSize' id='artCat' required>";
                        // get categories
                        $resultSize  = getSize("");
                        echo "<option value='" . $sizeIdValue . "'>" . $sizeValue . "</option>";
                        while ( $rowSize = $resultSize->fetch_assoc() ) {
                            if ( $sizeIdValue != $rowSize['id_size'] ) {
                                echo "<option value='" . $rowSize['id_size'] . "'>" . $rowSize['size'] . "</option>";
                            }
                        }
                    echo "</select></td>";
                echo "</tr><tr>";
                    echo "<td>Aktivieren</td>";
                    echo "<td><input type='checkbox' name='active'  size='40' value='yes' " . $activeValue . " /></td>";
                echo "</tr><tr>";
                    echo "<td>Korb</td>";
                    echo "<td><input type='text' name='korb' id='korb'  size='40' value='" . $korbValue . "' required/></td>";
                echo "</tr><tr>";
                    echo "<td>Vorhang</td>";
                    echo "<td><input type='text' name='vorhang' id='vorhang'  size='40' value='" . $vorhangValue . "' /></td>";
                echo "</tr><tr>";
                    echo "<td>Innenausstattung</td>";
                    echo "<td><input type='text' name='innen' id='innen'  size='40' value='" . $innenValue . "' required /></td>";
                echo "</tr><tr>";
                    echo "<td valign='top'>Fixleint&uuml;cher</td>";
                    echo "<td><input type='text' id='leintuch_1' name='leintuch_1'  size='40' value='" . $leint1Value . "' required /></br>";
                    echo "<input type='text' id='leintuch_2' name='leintuch_2'  size='40' value='" . $leint2Value . "' /></td>";
                echo "</tr><tr>";
                    echo "<td>Duvet</td>";
                    echo "<td><input type='text' name='duvet' id='duvet'  size='40' value='" . $duvetValue . "' required /></td>";
                echo "</tr><tr>";
                    echo "<td valign='top'>Duvetbez&uuml;ge</td>";
                    echo "<td><input type='text' id='duvet_1' name='duvet_1'  size='40' value='" . $duvet1Value . "' required /></br>";
                    echo "<input type='text' id='duvet_2' name='duvet_2'  size='40' value='" . $duvet2Value . "' /></td>";
                echo "</tr><tr>";
                    echo "<td>Matratze</td>";
                    echo "<td><input type='text' id='matratze' name='matratze'  size='40' value='" . $matratzeValue . "' required /></br>";
                echo "</tr><tr>";
                    echo "<td valign='top'>Kopfspuck</td>";
                    echo "<td><input type='text' id='kopfspuck_1' name='kopfspuck_1'  size='40' value='" . $ks1Value . "' required /></br>";
                    echo "<input type='text' id='kopfspuck_2' name='kopfspuck_2'  size='40' value='" . $ks2Value . "' /></br>";
                    echo "<input type='text' id='kopfspuck_3' name='kopfspuck_3'  size='40' value='" . $ks3Value . "' /></td>";
                echo "</tr><tr>";
                    echo "<td>Zusatzinfos</td>";
                    echo "<td><input type='text' name='desc' id='desc'  size='40' value='" . $descValue . "' /></td>";
                echo "</tr><tr>";
                    echo "<td>Photo</td>";
                    echo "<input type='hidden' name='MAX_FILE_SIZE' value='4194304' />";
                    echo "<td><input name='userfile' type='file' /></td>";
                echo "</tr><tr>";
                     echo "<td colspan='2'><input type='submit' value='Stubenwagen speichern' /></td>";
                echo "</tr><tr>";
                     echo "<td valign='top' colspan='2'></br><a class='not_menu' href='?" . $link ."'>Vorgang abbrechen</a></td>";
                echo "</tr></table>";
                echo "<input type='hidden' id='id_sw' name='id_sw' value='" . $id . "' />";
            echo "</form>";
            echo "</br></br>";
        } // eoi formular

    } // eoi artikel exist

//} // eoi action=edit


######
#
# NEW WAGEN
#
######

if ( !isset($_GET['action'])) {
    // relax and light a spliff :)

} elseif (( $_GET['action'] == 'newWagen' ) || ( $_GET['action'] == "saveNewWagen" )) {

    // Titel
    echo "<b>Neuer Stubenwagen</b></br></br>";

    // SAVE!!!
    if ( $_GET['action'] == "saveNewWagen" ) {

        // Status
        $statusType = "failed";
        $statusMsg  = "Die Erstellung des Stubenwagen <i>" . $_POST['wagenName'] . "</i> hat nicht funktioniert! Versuche es erneut.</br>";
        $statusMsg .= "Fehlermeldung: ";

//echo "<pre>"; print_r($_POST); echo "</pre>";
//echo "<pre>"; print_r($_FILES); echo "</pre>";

            // check error
            // Name is string?
            if ((strpbrk($_POST['wagenName'], '\'";<>')) || (!is_string($_POST['wagenName']))) {
                $error      = "occured";
                $errorMsg   = "Name enth&auml;t unerlaubte Zeichen!";
            } else {
                // Groesse ausgewaehlt?
                if (( $_POST['wagenSize'] == 0 ) || ( $_POST['wagenSize'] == "" )) {
                    $resultSize = getSize($_POST['wagenSize']);
                    if ( $resultSize->num_rows == 0 ) {
                        $error      = "occured";
                        $errorMsg   = "Keine g&uuml;ltige Gr&ouml;sse ausgew&auml;hlt!";
                    }
                } else {
                    // Korb angabe is string?
                    if ((strpbrk($_POST['korb'], '\'";<>')) || (!is_string($_POST['korb']))) {
                        $error      = "occured";
                        $errorMsg   = "Die Angaben zum Korb enthalten unerlaubte Zeichen!";
                    } else {
                        // Innenausstattung angabe is string?
                        if ((strpbrk($_POST['innen'], '\'";<>')) || (!is_string($_POST['innen']))) {
                            $error      = "occured";
                            $errorMsg   = "Die Angaben zur Ausstattung enthalten unerlaubte Zeichen!";
                        } else {
                            // Vorhang angabe is string?
                            if ((strpbrk($_POST['vorhang'], '\'";<>')) || (!is_string($_POST['vorhang']))) {
                                $error      = "occured";
                                $errorMsg   = "Die Angaben zum Vorhang enthalten unerlaubte Zeichen!";
                            } else {
                                // Leintuch 1 angabe is string?
                                if ((strpbrk($_POST['leintuch_1'], '\'";<>')) || (!is_string($_POST['leintuch_1']))) {
                                    $error      = "occured";
                                    $errorMsg   = "Die Angaben zum Leintuch (1. Zeile) enthalten unerlaubte Zeichen!";
                                } else {
                                    // Leintuch 2 angabe is string?
                                    if ((strpbrk($_POST['leintuch_2'], '\'";<>')) || (!is_string($_POST['leintuch_2']))) {
                                        $error      = "occured";
                                        $errorMsg   = "Die Angaben zum Leintuch (2. Zeile) enthalten unerlaubte Zeichen!";
                                    } else {
                                        // Duvet  angabe is string?
                                        if ((strpbrk($_POST['duvet'], '\'";<>')) || (!is_string($_POST['duvet']))) {
                                            $error      = "occured";
                                            $errorMsg   = "Die Angaben zum Duvet enthalten unerlaubte Zeichen!";
                                        } else {
                                            // Duvet 1 angabe is string?
                                            if ((strpbrk($_POST['duvet_1'], '\'";<>')) || (!is_string($_POST['duvet_1']))) {
                                                $error      = "occured";
                                                $errorMsg   = "Die Angaben zu den Duvetbez&uuml;gen (1. Zeile) enthalten unerlaubte Zeichen!";
                                            } else {
                                                // Duvet 2 angabe is string?
                                                if ((strpbrk($_POST['duvet_2'], '\'";<>')) || (!is_string($_POST['duvet_2']))) {
                                                    $error      = "occured";
                                                    $errorMsg   = "Die Angaben zu den Duvetbez&uuml;gen (2. Zeile) enthalten unerlaubte Zeichen!";
                                                } else {
                                                    // Matratze angabe is string?
                                                    if ((strpbrk($_POST['matratze'], '\'";<>')) || (!is_string($_POST['matratze']))) {
                                                        $error      = "occured";
                                                        $errorMsg   = "Die Angaben zum Matratze enthalten unerlaubte Zeichen!";
                                                    } else {
                                                        // KS 1 angabe is string?
                                                        if ((strpbrk($_POST['kopfspuck_1'], '\'";<>')) || (!is_string($_POST['kopfspuck_1']))) {
                                                            $error      = "occured";
                                                            $errorMsg   = "Die Angaben zum Kopfspuck-Tuch (1. Zeile) enthalten unerlaubte Zeichen!";
                                                        } else {
                                                            // KS 2 angabe is string?
                                                            if ((strpbrk($_POST['kopfspuck_2'], '\'";<>')) || (!is_string($_POST['kopfspuck_2']))) {
                                                                $error      = "occured";
                                                                $errorMsg   = "Die Angaben zum Kopfspuck-Tuch (2. Zeile) enthalten unerlaubte Zeichen!";
                                                            } else {
                                                                // KS 3 angabe is string?
                                                                if ((strpbrk($_POST['kopfspuck_3'], '\'";<>')) || (!is_string($_POST['kopfspuck_3']))) {
                                                                    $error      = "occured";
                                                                    $errorMsg   = "Die Angaben zum Kopfspuck-Tuch (3. Zeile) enthalten unerlaubte Zeichen!";
                                                                } else {
                                                                    // Zusatzinfos angabe is string?
                                                                    if ((strpbrk($_POST['desc'], '\'";<>')) || (!is_string($_POST['desc']))) {
                                                                        $error      = "occured";
                                                                        $errorMsg   = "Die Zusatzinfos enthalten unerlaubte Zeichen!";
                                                                    }
                                                                } 
                                                            } // eo check toy size
                                                        } // eo check body size
                                                    } // eo check inventory
                                                } // eo check porto
                                            } // eo check price
                                        } // eo check error
                                    } // eo check error
                                } // eo check toy size
                            } // eo check body size
                        } // eo check inventory
                    } // eo check porto
                } // eo check price
            } // eo check error

//    echo "<pre>" . $error . "/" . $errorMsg . "</pre>";

            // check imagewenn kein Fehler
            if ( ($error == "none") && ($_FILES['userfile']['name'] != "") ) {
                // check image
                $uploaddir  = '/home/ironsmit/public_html/schlosser-stubenwagen.ch/www/img/mainphoto/tmp/';
                $photodir   = '/home/ironsmit/public_html/schlosser-stubenwagen.ch/www/img/mainphoto/';
                $message    = checkImageUpload($uploaddir);
                $error      = $message != "okay" ? "occured" : "none";
                $errorMsg   = $message != "okay" ? "Bild-Upload fehlgeschlagen!" : "none";
            }

            // wenn kein Fehler
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
                    $sql        = "insert into stubenwagen values(" . $_POST['id_sw'] . ",'" . $_POST['wagenName'] . "','" . $active . "',";
                    $sql       .= "'" . $_POST['korb'] . "'," .$_POST['wagenSize'] . ",'','" . $_POST['vorhang'] . "','" . $_POST['innen'] . "',";
                    $sql       .= "'" . $_POST['leintuch_1'] . "','" . $_POST['leintuch_2'] . "','" . $_POST['duvet'] . "',";
                    $sql       .= "'" . $_POST['duvet_1'] . "','" . $_POST['duvet_2'] . "','" . $_POST['matratze'] . "','" .$_POST['kopfspuck_1'] . "',";
                    $sql       .= "'" . $_POST['kopfspuck_2'] .  "','" . $_POST['kopfspuck_3'] . "','" . $_POST['desc'] . "','img/mainphoto/" . $_POST['id_sw'] . ".jpg',";
                    $sql       .= "'free','none','01.01.2000')";
                    $dbcon      = dbConnect(DBUSER,DBPW);
                    $dbcon->query($sql);
                    dbClose($dbcon);

                    // move and rename file
                    if ($_FILES['userfile']['name'] != "") {
                        rename($uploaddir . "/" . $_FILES['userfile']['name'],$photodir . "/" . $_POST['id_sw'] . ".jpg");
                    }

                    // Status
                    $statusType = "succeed";
                    $statusMsg  = "Der Stubenwagen <i>" . $_POST['wagenName'] . "</i> wurde erfolgreich erstellt.</br>";
                    $statusMsg .= "(Du siehst den erstellten Stubenwagen direkt unter dieser Meldung)";
                    $statusQuery= "select * from stubenwagen where id_sw=" . $_POST['id_sw'];

                } // eoi active checked
            } // eoi insertion completed

            // if error
            else {
                $statusMsg  .= $errorMsg;
            }
        }// eoi saving

        if ( ($_GET['action'] == "newWagen")  || ( $error == 'occured' )) {

            // get next id
            $dbcon  = dbConnect(DBUSER,DBPW);
            $sql    = "select id_sw from stubenwagen order by id_sw desc limit 1";
            $result = $dbcon->query($sql);
            dbClose($dbcon);
            $row    = $result->fetch_assoc();
            $next_id= $row['id_sw'] + 1;

            // Formular ausgeben
            echo "<form eaccept-charset='UTF8' enctype='multipart/form-data' method='post' name='newWagen' id='newWagen' action='?" . $link . "&action=saveNewWagen'>";
                echo "<table><tr>";
                    // photo
                    echo "<td>Name</td>";
                    echo "<td><input type='text' id='wagenName' name='wagenName' size='40' value='' required /></td>";
                echo "</tr><tr>";
                    echo "<td>Gr&ouml;sse</td>";
                    echo "<td><select name='wagenSize' id='artCat' required>";
                        // get categories
                        $resultSize  = getSize("");
                        echo "<option value=''>Bitte ausw&auml;hlen...</option>";
                        while ( $rowSize = $resultSize->fetch_assoc() ) {
                            echo "<option value='" . $rowSize['id_size'] . "'>" . $rowSize['size'] . "</option>";
                        }
                    echo "</select></td>";
                echo "</tr><tr>";
                    echo "<td>Aktivieren</td>";
                    echo "<td><input type='checkbox' name='active'  size='40' value='yes' /></td>";
                echo "</tr><tr>";
                    echo "<td>Korb</td>";
                    echo "<td><input type='text' name='korb' id='korb'  size='40' value='' required/></td>";
                echo "</tr><tr>";
                    echo "<td>Vorhang</td>";
                    echo "<td><input type='text' name='vorhang' id='vorhang'  size='40' value='' /></td>";
                echo "</tr><tr>";
                    echo "<td>Innenausstattung</td>";
                    echo "<td><input type='text' name='innen' id='innen'  size='40' value='' required /></td>";
                echo "</tr><tr>";
                    echo "<td valign='top'>Fixleint&uuml;cher</td>";
                    echo "<td><input type='text' id='leintuch_1' name='leintuch_1'  size='40' value='' required /></br>";
                    echo "<input type='text' id='leintuch_2' name='leintuch_2'  size='40' value='' /></td>";
                echo "</tr><tr>";
                    echo "<td>Duvet</td>";
                    echo "<td><input type='text' name='duvet' id='duvet'  size='40' value='' required /></td>";
                echo "</tr><tr>";
                    echo "<td valign='top'>Duvetbez&uuml;ge</td>";
                    echo "<td><input type='text' id='duvet_1' name='duvet_1'  size='40' value='' required /></br>";
                    echo "<input type='text' id='duvet_2' name='duvet_2'  size='40' value='' /></td>";
                echo "</tr><tr>";
                    echo "<td>Matratze</td>";
                    echo "<td><input type='text' id='matratze' name='matratze'  size='40' value='' required /></br>";
                echo "</tr><tr>";
                    echo "<td valign='top'>Kopfspuck</td>";
                    echo "<td><input type='text' id='kopfspuck_1' name='kopfspuck_1'  size='40' value='' required /></br>";
                    echo "<input type='text' id='kopfspuck_2' name='kopfspuck_2'  size='40' value='' /></br>";
                    echo "<input type='text' id='kopfspuck_3' name='kopfspuck_3'  size='40' value='' /></td>";
                echo "</tr><tr>";
                    echo "<td>Zusatzinfos</td>";
                    echo "<td><input type='text' name='desc' id='desc'  size='40' value='' /></td>";
                echo "</tr><tr>";
                    echo "<td>Photo</td>";
                    echo "<input type='hidden' name='MAX_FILE_SIZE' value='4194304' />";
                    echo "<td><input name='userfile' type='file' required /></td>";
                echo "</tr><tr>";
                     echo "<td colspan='2'><input type='submit' value='Stubenwagen speichern' /></td>";
                echo "</tr><tr>";
                     echo "<td valign='top' colspan='2'></br><a class='not_menu' href='?" . $link ."'>Vorgang abbrechen</a></td>";
                echo "</tr></table>";
                echo "<input type='hidden' id='id_sw' name='id_sw' value='" . $next_id . "' />";
            echo "</form>";
            echo "</br></br>";
        } // eoi formular

    } // eoi artikel exist

//} // eoi action=New Stubenwagen


##############################
#                            #
#    STUBENWAGEN ANZEIGEN    #
#                            #
##############################


######
#    
#   STUBENWAGEN LOESCHEN
#    
######

if ( !isset($_GET['delete'])) {
    // chill and light a spliff :)
} else {

    // check id
    if (( $_GET['delete'] > 0) && ( $_GET['delete'] < 99999 ) ) {
        $check_id = $_GET['delete'];

        // get wagen-name
        $result = getWInfos($check_id);
        
        // check if artikel exist
        if ( $result->num_rows != 0 ) {

            $id     = $check_id;
            $row    = $result->fetch_assoc();
            
            // check if reservationen
            $sqlCheck       = "select count(id_reservation) as count from reservation where sw_id=$id and enddate_sys > '" . date('Y-m-d') . "'";
            $dbcon          = dbConnect(DBUSER,DBPW);
            $resultCheck    = $dbcon->query($sqlCheck);
            $rowCheck       = $resultCheck->fetch_assoc();

            // if reservationen
            if ( $rowCheck['count'] != 0 ) {
                $statusType = "failed";
                $statusMsg  = "Der Stubenwagen <i>" . $row['name'] . "</i> kann nicht gel&ouml;scht werden, weil noch " . $rowCheck['count'];
                $statusMsg .= " laufende Reservationen existieren.";

            } else {

                // delete picture
                if ( is_file("../" . $row['mainphoto']) ) {
                    unlink('../' . $row['mainphoto']);
                }

                // delete gallery
                if ( is_dir("../gallery/$id") ) {
                    recurseRmdir("../gallery/$id");
                }

                // delete database-entry
                $sqlFk0     = "SET foreign_key_checks = 0";
                $sqlFk1     = "SET foreign_key_checks = 1";
                $sql        = "delete from stubenwagen where id_sw = $id";
                $dbcon      = dbConnect(DBUSER,DBPW);
                $dbcon->query($sqlFk0);
                $dbcon->query($sql);
                $dbcon->query($sqlFk1);
                dbClose($dbcon);

                // Status
                $statusType = "succeed";
                $statusMsg  = "Der Stubenwagen <i>" . $row['name'] . "</i> wurde erfolgreich gel&ouml;scht!";

            } // eoi reservationen

        } // eoi artikel exist

    } // eoi check id

} // eo delete

######
#    
#   WAGEN IN WARTUNG
#    
######

if (( !isset($_GET['startMaint'])) && (!isset($_GET['endMaint']))) {
    // chill and light a spliff :)
} else {

    if ( !isset($_GET['startMaint'] ) ) {
        $check_id   = $_GET['endMaint'];
        $maintValue = "free";
        $checkValue = "inMaintenance";
        $msgText    = "wird nicht mehr gewartet!";
    } else {
        $check_id   = $_GET['startMaint'];
        $maintValue = "inMaintenance";
        $checkValue = "free";
        $msgText    = "wird jetzt gewartet!";
    } 

    // check id
    if (( $check_id > 0) && ( $check_id < 99999 ) ) {
        $id = $check_id;

        // info
        $sql    = "select * from stubenwagen where status='" . $checkValue . "' and id_sw=$id";
        $dbcon  = dbConnect(DBUSER,DBPW);
        $result = $dbcon->query($sql);
        dbClose($dbcon);

        if ( $result->num_rows != 0 ) {
            $row        = $result->fetch_assoc();

            // update database-entry
            $sql        = "update stubenwagen set status='" . $maintValue . "' where id_sw = $id";
            $dbcon      = dbConnect(DBUSER,DBPW);
            $dbcon->query($sql);
            dbClose($dbcon);

            // Status
            $statusType = "succeed";
            $statusMsg  = "Der Stubenwagen <i>" . $row['name'] . "</i> " . $msgText . "!</br>(Du siehst den Stubenwagen direkt unter dieser Anzeige)";
            $statusQuery= "select * from stubenwagen where id_sw=$id";

        } // eoi stubenwagen exist

    } // eoi check id

} // eo end maint


######
#    
#   WAGEN DEAKTIVIEREN
#    
######

if ( !isset($_GET['deactivate'])) {
    // chill and light a spliff :)
} else {

    // check id
    if (( $_GET['deactivate'] > 0) && ( $_GET['deactivate'] < 99999 ) ) {
        $id = $_GET['deactivate'];

        // info
        $sql    = "select * from stubenwagen where active='yes' and id_sw=$id";
        $dbcon  = dbConnect(DBUSER,DBPW);
        $result = $dbcon->query($sql);
        dbClose($dbcon);

        if ( $result->num_rows != 0 ) {
            $row        = $result->fetch_assoc();

            // update database-entry
            $sql        = "update stubenwagen set active='no' where id_sw = $id";
            $dbcon      = dbConnect(DBUSER,DBPW);
            $dbcon->query($sql);
            dbClose($dbcon);

            // Status
            $statusType = "succeed";
            $statusMsg  = "Der Stubenwagen <i>" . $row['name'] . "</i> wurde erfolgreich deaktiviert!</br>(Du siehst den Stubenwagen direkt unter dieser Anzeige)";
            $statusQuery= "select * from stubenwagen where id_sw=$id";

        } // eoi stubenwagen exist

    } // eoi check id

} // eo deactivate

######
#
#   WAGEN AKTIVIEREN
#
######

if ( !isset($_GET['activate'])) {
    // chill and light a spliff :)
} else {

    // check id
    if (( $_GET['activate'] > 0) && ( $_GET['activate'] < 99999 ) ) {
        $id = $_GET['activate'];

        // info
        $sql    = "select * from stubenwagen where active='no' and id_sw=$id";
        $dbcon  = dbConnect(DBUSER,DBPW);
        $result = $dbcon->query($sql);
        dbClose($dbcon);

        if ( $result->num_rows != 0 ) {
            $row        = $result->fetch_assoc();

            // update database-entry
            $sql        = "update stubenwagen set active='yes' where id_sw = $id";
            $dbcon      = dbConnect(DBUSER,DBPW);
            $dbcon->query($sql);
            dbClose($dbcon);

            // Status
            $statusType = "succeed";
            $statusMsg  = "Der Stubenwagen <i>" . $row['name'] . "</i> wurde erfolgreich aktiviert!</br>(Du siehst den Stubenwagen direkt unter dieser Anzeige)";
            $statusQuery= "select * from stubenwagen where id_sw=$id";

        } // eoi stubenwagen exist

    } // eoi check id

} // eo activate

#####
#
# CONDITION ERARBEITEN
#
#####

// values fuer formular
$sizeValue      = "";
$sizeValueText  = "Alle anzeigen";
$actValue       = "";
$actValueText   = "alle anzeigen";
$mainValue      = "";

// condition erarbeiten
$condition          = "";
$condition_size     = "";
$condition_active   = "";
$condition_main     = "";

// condition_cat
if (!isset($_GET['size_id'])) {
    // do nothing and light a spliff :)
} elseif ( $_GET['size_id'] != "" ) {
    $condition_size  = "size_id = " . $_GET['size_id'];
    $sizeValue       = $_GET['size_id'];
    if ( $sizeValue == "" ) {
        $sizeValueText   = "Alle anzeigen";
    } else {
        $result         = getSize($sizeValue);
        $row            = $result->fetch_assoc();
        $sizeValueText  = $row['size'];
    }
}

// condition_active
if (!isset($_GET['active'])) {
    // do nothing and light a spliff :)
} elseif ( $_GET['active'] != "" ) {
    $condition_active = "active = '" . $_GET['active'] . "'";
    $actValue       = $_GET['active'];
    $actValueText   = $actValue == "no" ? "nur inaktive anzeigen" : "nur aktive anzeigen";
}

// condition_main
if (!isset($_GET['main'])) {
    // do nothing and light a spliff :)
} elseif ( $_GET['main'] == 1 ) {
    $condition_main  = "status = 'inMaintenance'";
    $mainValue      = "checked";
}

// condition zusammensetzen
if ( ($condition_size != "" ) || ( $condition_active != "") || ($condition_main != "" )) {
    $condition = " where ";

    // if size is set
    if ( $condition_size != "" ) {
        $condition .= $condition_size;
        if ( ($condition_active != "") || ($condition_main != "" )) {
            $condition .= " and ";
            if ( $condition_active != "" ) {
                $condition .= $condition_active;
                if ( $condition_main != "" ) {
                    $condition .= " and " . $condition_main;
                }
            } else {
                $condition .= $condition_main;
            }// eoi if active is set
        } // eoi not only size

    // if size isn't set
    } else {
        if ( $condition_active != "" ) {
            $condition .= $condition_active;
            if ( $condition_main != "" ) {
                $condition .= " and " . $condition_main;
            }

        // if only main is set
        } else {
            $condition .= $condition_main;
        }
    }
} // eo building condition


#####
#
# FORMULAR SUCHEN
#
#####

// Titel
echo "<b>Stubenwagen anzeigen/suchen</b></br></br>";

// alle Groessen aus db
$dbcon  = dbConnect(DBUSER,DBPW);
$sql    = "select id_size, size from size";
$result = $dbcon->query($sql);
dbClose($dbcon);

// anzeige (auswahl)
echo "Welche Artikel sollen angezeigt werden?</br></br>";

//echo "<pre>"; print_r($_SESSION); echo "</pre>";

echo "<table width='100%'>";

    // Header
    echo "<tr>";
        echo "<td>Gr&ouml;sse</td>";
        echo "<td>inaktiv/aktiv</td>";
        echo "<td>in Wartung</td>";
    echo "</tr><tr>";

    echo "<form name='sizeselect' id='sizeselect' method='get' action='?'>";
        
        // Kategorie
        echo "<td>";
            echo "<input type='hidden' name='top' value='inventar' />";
            echo "<input type='hidden' name='sub' value='wagen' />";

            echo "<select name='size_id'>";
                echo "<option value='" . $sizeValue . "'>" . $sizeValueText . "</option>";
                if ( $sizeValue != "" ) {
                    echo "<option value=''>Alle anzeigen</option>";
                }
                // Punkt fuer jede Kategorie
                while ( $row=$result->fetch_assoc() ) {
                if ( $row['id_size'] != $sizeValue ) {
                        echo "<option value='" . $row['id_size'] ."'>" . $row['size'] . "</option>";
                    }
                }
            echo "</select>";
        echo "</td>";

        // inaktiv/aktiv/alle
        echo "<td>";
            echo "<select name='active'>";
                echo "<option value='" . $actValue . "'>" . $actValueText . "</option>";
                if ( $actValue != '' ) { echo "<option value=''>alle anzeigen</option>"; }
                if ( $actValue != 'no' ) {echo "<option value='no'>nur inaktive anzeigen</option>"; }
                if ( $actValue != 'yes' ) {echo "<option value='yes'>nur aktive anzeigen</option>"; }
            echo "</select>";
        echo "</td>";

        // nur ausverkauft
        echo "<td>";
            echo "<input type='checkbox' name='main' id='main' value='1' $mainValue /> nur in Wartung anzeigen";
        echo "</td>";

        // Submitbutton
        echo "<td>";
            echo "<input type='submit' value='anzeigen' id='submit' name='submit' />";
        echo "</td>";

    echo "</form>";
echo "</tr></table>";
    
echo "</br>";


######
#    
#   STATUS
#    
######

if ( (!isset($_GET['delete'])) && (!isset($_GET['deactivate'] )) && (!isset($_GET['activate'])) && (!isset($_GET['endMaint'])) && (!isset($_GET['startMaint'])) ) {
    // chill na light a spliff :)
    if ( !isset($_GET['action']) ) {
        // chill na light a spliff :)
    } elseif ( $_GET['action'] == "saveNewWagen" ) {

        // Status
        if ( $statusType != "" ) {
            printStatus($statusType,$statusMsg);
            echo "</br>";
        }

        if ( $statusQuery != "") {
            $dbcon  = dbConnect(DBUSER,DBPW);
            $result = $dbcon->query($statusQuery);
            $row    = $result->fetch_assoc();
            dbClose($dbcon);

            // Admin-Part
            printWInfosAdmin($row);

            // restliche Inofs
            printWInfos($row,"admin");

            echo "</br></br>";

        }
    }
} else {
    if ( !isset($_GET['action'])) {

        // Status
        if ( $statusType != "" ) {
            printStatus($statusType,$statusMsg);
            echo "</br>";
        }

        if ( $statusQuery != "") {
            $dbcon  = dbConnect(DBUSER,DBPW);
            $result = $dbcon->query($statusQuery);
            $row    = $result->fetch_assoc();
            dbClose($dbcon);

            // Admin-Part
            printWInfosAdmin($row);

            // restliche Inofs
            printWInfos($row,"admin");

            echo "</br></br>";

        }

    }
}


#####
#
# WAGEN AUSGEBEN
#
#####

$sql    = "select * from stubenwagen" . $condition;
$dbcon  = dbConnect(DBUSER,DBPW);
$result = $dbcon->query($sql);
dbClose($dbcon);

// print wagen
while ( $row = $result->fetch_assoc() ) {

    // Admin-Part
    printWInfosAdmin($row);

    // Printing Infos
    printWInfos($row,"admin");

    echo "</br></br>";

} // eo printing wagen

?>
