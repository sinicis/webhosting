<?php

// Globale Variabeln
$status     = "proceeding";
$error      = "";

// Verfuegbare Zeitspannen
$fromAll    = array();
$toAll      = array();   
$fromCompAll= array();
$toCompAll  = array();   
$sql        = "select startdate,enddate,startdate_sys,enddate_sys from reservation where sw_id=" . $_GET['sub'] . " order by startdate_sys asc";
$dbcon      = dbConnect(DBUSER,DBPW);
$result     = $dbcon->query($sql);
$count      = $result->num_rows;
dbClose($dbcon);

if ( $count != 0 ) {
    while ($row = $result->fetch_assoc() ) {
        // startdate
        array_push($fromAll,$row['startdate']);
        array_push($fromCompAll,$row['startdate_sys']);

        // enddate
        $enddate = date('d.m.Y', strtotime("+2 weeks", strtotime($row['enddate'])));
        array_push($toAll,$enddate);
        array_push($toCompAll,$row['enddate_sys']);
    }
}

// check if not in maintenance/
$sql        = "select status from stubenwagen where id_sw=" . $_GET['sub'];
$dbcon      = dbConnect(DBUSER,DBPW);
$result     = $dbcon->query($sql);
$row        = $result->fetch_assoc();
$statusMaint= $row['status'];
dbClose($dbcon);


// get max-duration
$sql        = "select name,size.shortcut from stubenwagen join size on stubenwagen.size_id=size.id_size where id_sw=" . $_GET['sub'];
$dbcon      = dbConnect(DBUSER,DBPW);
$result     = $dbcon->query($sql);
$row        = $result->fetch_assoc();
$maxDuration    = $row['shortcut'];
dbClose($dbcon);

// Formular-Ueberpruefung
if ((isset($_GET['action'])) && ($_GET['action'] == "send") && ($statusMaint != "inMaintenance") ) {

    // check AGBs
    if (!isset($_POST['agb'])) {
        $status     = "failed";
        $error      = "Sie m&uuml;ssen die AGBs akzeptieren.";

    } else {
     
        // check if all fields are filled out
        if (!isset($_POST['from']) ||
            !isset($_POST['duration']) ||
            !isset($_POST['firstname']) ||
            !isset($_POST['lastname']) ||
            !isset($_POST['mail']) ||
            !isset($_POST['mail2compare']) ||
            !isset($_POST['street']) ||
            !isset($_POST['streetNbr']) ||
            !isset($_POST['plz']) ||
            !isset($_POST['ort'])) { 

            $status     = "failed";
            $error      = "Sie haben nicht alle Felder ausgef&uuml;llt. (Optional: <i>Bemerkung</i>)";

        } else {

            // converting the dates 
            $duration           = $_POST['duration'];
            $from               = $_POST['from'];
            $fromSql            = $_POST['from'];
            $fromArray          = explode(".",$_POST['from']);
            list($fd,$fm,$fy)   = $fromArray;
            $fromCal            = date_create("$fy-$fm-$fd");
            $fromComp           = "$fy-$fm-$fd";
            $to                 = date('d.m.Y', strtotime("+$duration months", strtotime($from)));
            $toComp             = date('Y-m-d', strtotime("+$duration months", strtotime($from)));
            $today              = date("Y-m-d");
            $todayCal           = date_create("$today");
   
            // calculating the differences
            $tmpDiff            = date_diff($todayCal,$fromCal);
            $diffFromToday      = $tmpDiff->format("%R%a");


            // check if from- and to-date is valid
            if (!checkdate($fm,$fd,$fy)) {
                $status     = "failed";
                $error      = "Das <i>\"Ab\"</i>-Datum ist nicht g&uuml;ltig";

            } else {

                // check if from-date not in past
                if ($diffFromToday < 0) { 
                    $status     = "failed";
                    $error      = "Das von Ihnen gew&auml;hlte <i>\"Ab\"</i>-Datum liegt in der Vergangenheit";

                } else {

                    // check duration
                    if (( $duration < 3 ) || ( $duration > $maxDuration )) {
                        $status     = "failed";
                        $error      = "Die von Ihnen gew&auml;hlte Zeitspanne ist zu kurz oder zu lange (Muss: 3-$maxDuration Monate)";

                    } else {

                        // check timezone
                        if ( count($fromCompAll) == 0 ) {
                            $timeCheck = "succeed";
                        } else {
                            $timeCheck = "succeed";
                            $count = count($fromCompAll);
                            for ($i=0;$i<$count;$i++) {
                                $timeCheck  = ((( $fromComp >= $fromCompAll[$i]) && ($fromComp <= $toCompAll[$i])) || (( $toComp >= $fromCompAll[$i]) && ($toComp <= $toCompAll[$i]))) ? "failed" : $timeCheck;
                            }

                        }

                        if ( $timeCheck == "failed" ) {

                            $status     = "failed";
                            $error      = "Der Stubenwagen ist in dieser Zeitspanne leider nicht verf&uuml;gbar.";

                        } else {

                            // check firstname
                            $string_exp = "/^[éèàäöüÄÖÜA-Za-z .'-]+$/";
                            if (!preg_match($string_exp,$_POST['firstname'])) {
                                $status     = "failed";
                                $error      = "Ihr Vornamen enth&auml;lt unerlaubte Zeichen (Erlaubte Zeichen: A-Z.'- und Leerzeichen)";

                            } else {

                                // check lastname
                                $string_exp = "/^[éèàäöüÄÖÜA-Za-z .'-]+$/";
                                if (!preg_match($string_exp,$_POST['lastname'])) {
                                    $status     = "failed";
                                    $error      = "Ihr Nachnamen enth&auml;lt unerlaubte Zeichen (Erlaubte Zeichen: A-Z.'- und Leerzeichen)";

                                } else {

                                    // check if mail-addresses are the same
                                    if ($_POST['mail'] != $_POST['mail2compare']) {
                                        $status     = "failed";
                                        $error      = "Sie m&uuml;ssen zwei Mal dieselbe E-Mailadresse angeben";

                                    } else {

                                        // check if mailadress is valid
                                        if (!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {
                                            $status     = "failed";
                                            $error      = "Sie m&uuml;ssen zwei Mal dieselbe E-Mailadresse angeben";

                                        } else {

                                            // check phone
                                            if ((!filter_var($_POST['phone'], FILTER_VALIDATE_INT)) || (( strlen($_POST['phone'] < 9) ) && ( strlen($_POST['phone'] > 10))) )   {
                                                $status     = "failed";
                                                $error      = "Ihre Telefonnummer scheint nicht g&uuml;ltig zu sein (Erlaubte Zeichen: 0-9)";

                                            } else {

                                                // check street
                                                $string_exp = "/^[éèàäöüÄÖÜA-Za-z .'-]+$/";
                                                if (!preg_match($string_exp,$_POST['street'])) {
                                                    $status     = "failed";
                                                    $error      = "Ihre Adresse scheint nicht g&uuml;ltig zu sein (Erlaubte Zeichen: A-Z.'- und Leerzeichen)";

                                                } else {

                                                    // check streetNbr
                                                    //$string_exp = "/^[0-9][a-zA-Z]+$/";
                                                    $string_exp = "/^[0-9]+$/";
                                                    if (!preg_match($string_exp,$_POST['streetNbr'])) {
                                                        $status     = "failed";
                                                        $error      = "Ihre Strassennummer scheint nicht g&uuml;ltig zu sein (Erlaubte Zeichen: 0-9)";

                                                    } else {

                                                        // check plz
                                                        $string_exp = "/^[0-9]+$/";
                                                        if ((!preg_match($string_exp,$_POST['plz'])) || (strlen($_POST['plz']) < 4) || (strlen($_POST['plz']) > 5)) {
                                                            $status     = "failed";
                                                            $error      = "Ihre PLZ scheint nicht g&uuml;ltig zu sein (Erlaubte Zeichen: 0-9)";

                                                        } else {

                                                            // check ort
                                                            $string_exp = "/^[éèàäöüÄÖÜA-Za-z .'-]+$/";
                                                            if (!preg_match($string_exp,$_POST['ort'])) {
                                                                $status     = "failed";
                                                                $error      = "Die von Ihnen angegebene Ortschaft (oder PLZ) scheint nicht g&uuml;ltig zu sein (Erlaubte Zeichen: A-Z.'- und Leerzeichen)";


                                                            } else {

                                                                // DB-Eintrag erstellen
                                                                $dbcon  = dbConnect(DBUSER,DBPW);
                                                                $sql    = "insert into reservation (sw_id,status,firstname,lastname,mail,phone,street,streetNbr,plz,ort,startdate,enddate,duration,notes,date,startdate_sys,enddate_sys) values ";
                                                                $sql   .= "(" . $_GET['sub'] . ",'open','" . $_POST['firstname'] . "','" . $_POST['lastname'] . "','" . $_POST['mail'] . "','" . $_POST['phone'] . "',";
                                                                $sql   .= "'" . $_POST['street'] . "'," . $_POST['streetNbr'] . "," . $_POST['plz'] . ",'" . $_POST['ort'] . "',";
                                                                $sql   .= "'" . $fromSql ."','" . $to . "',$duration,'" . htmlspecialchars($_POST['notes'],ENT_QUOTES) . "','" . date("d.m.Y") . "',";
                                                                $sql   .= "'" . $fromComp . "','" . $toComp . "')";
                                                                $dbcon->query($sql);

                                                                // nextfree ermitteln
                                                                calcNextFree($_GET['sub']);

                                                                //  Mail an Claudia versenden versenden
                                                                $id = $dbcon->insert_id;
                                                                sendAdminMail("reservation");
    //                                                            sendClientMail("reservation",$_POST['mail'],$_POST['firstname'],$_POST['lastname']);
                                                                sendPdfMail($id,"booking","new");

                                                                $status   = "succeed";

                                                            } // eoi check ort

                                                        } // eoi check plz

                                                    } // eoi check streetNbr

                                                } // eoi check street

                                            } // eoi check phone

                                        } // eoi mail is valid

                                    } // eoi mails not same

                                } // eoi lastname valid

                            } // eoi firstname valid

                        } // eoi timezone check

                    } // eoi duration okay

                } // eoi from-date not in past

            } // eoi from date is valid

        } // eoi all filled out

    } // eoi agb checked

} // eo form-checking



if ( $statusMaint == "inMaintenance" ) {
    echo "<p>";
    echo "Da dieser Stubenwagen zurzeit gewartet wird k&ouml;nnen Sie ihn zurzeit nicht reservieren.";
    echo "</p>";


} else {
    echo "<p>";
    echo "Sch&ouml;n, dass Sie sich entschieden haben einen meiner Stubenwagen zu mieten. Falls es f&uuml;r Sie noch Unklarheiten geben sollte, dann lesen 
    Sie bitte die <a href='?top=info' class='not_menu'>Informations-Seite</a>. Falls Sie danach doch noch etwas wissen m&ouml;chten, scheuen Sie bitte nicht mich zu kontaktieren.";
    echo "</p>";


    // Formular-Definitionen
    $classFormFront = "formFrontWagen";
    $classFormSecond = "formSecond";

    // Erfolgsmeldung
    if (($status == "succeed") && ($error == "")) {
        echo "<p>";
        echo "<b><font color='green'>Besten Dank! :)</font></b></br>";
        echo "Ihre Reservation wurde soeben erfolgreich abgeschickt, in K&uuml;rze werden Sie eine Best&auml;tigungs-Nachricht erhalten.";
        echo "</p>";

    // Formular ausgeben
    } else {

        // gebuchte Zeiten
        if ( count($fromAll) != 0 ) {

            $count = count($fromAll);
            echo "<b>Achtung: </b></br>";
            echo "Der von Ihnen ausgew&auml;hlten Stubenwagen '" . $row['name'] . "' ist f&uuml;r die folgenden Zeitspannen bereits reserviert:</br></br>";
            for ($i=0;$i<$count;$i++) {
                echo "Zeitspanne ";
                echo $i+1 . ": " . $fromAll[$i] . " - " . $toAll[$i] . "</br>";
            }
            echo "</br>";
        }

        // Fehlerausgabe wenn noetig
        if (($status == "failed") && ($error != "")) {
            echo "<p>";
            echo "<b><font color='red'>Achtung Fehler:</font></b></br>";
            echo $error;
            echo "</p>";
        }

        // Falls abgeschickt: Inhalte sichern
        $toValue            = isset($_POST['to']) ? $_POST['to'] : ""; 
        $fromValue          = isset($_POST['from']) ? $_POST['from'] : ""; 
        $fnValue            = isset($_POST['firstname']) ? $_POST['firstname'] : ""; 
        $lnValue            = isset($_POST['lastname']) ? $_POST['lastname'] : ""; 
        $mailValue          = isset($_POST['mail']) ? $_POST['mail'] : ""; 
        $phoneValue         = isset($_POST['phone']) ? $_POST['phone'] : ""; 
        $mail2compareValue  = isset($_POST['mail2compare']) ? $_POST['mail2compare'] : ""; 
        $streetValue        = isset($_POST['street']) ? $_POST['street'] : "";
        $streetNbrValue     = isset($_POST['streetNbr']) ? $_POST['streetNbr'] : "";
        $plzValue           = isset($_POST['plz']) ? $_POST['plz'] : "";
        $ortValue           = isset($_POST['ort']) ? $_POST['ort'] : "";
        $notesValue         = isset($_POST['notes']) ? $_POST['notes'] : ""; 

        echo "<form name='reserv' id='reserv' method='post' action='?top=wagen&sub=" . $_GET['sub'] . "&view=reserv&action=send'>";

           echo "<table width='500px'>";
                // Header
                echo "<tr>";
                    echo "<td class='top' align='right'><b>Eigenschaft</b></td>";
                    echo "<td class='top'><b>Beschreibung</b></td>";
                echo "</tr>";

                // Wagen
                echo "<tr>";
                    echo "<td class='" . $classFormFront . "' valign='top'>Gew&uuml;nschter Wagen:</td>";
                    echo "<td class='" . $classFormSecond . "'><b>" . $row['name'] . "</b>";
                    echo "</td>";
                echo "</tr>";
                
                // Ab wann?
                echo "<tr>";
                    echo "<td class='" . $classFormFront . "' valign='top'>Ab:</td>";
                    echo "<td class='" . $classFormSecond . "'>";
                        echo "<input type='text' id='from' name='from' size='40' value='" . $fromValue . "' required />";
                    echo "</td>";
                echo "</tr>";
                
                // Bis wann?
                echo "<tr>";
                    echo "<td class='" . $classFormFront . "' valign='top'>Dauer:</td>";
                    echo "<td class='" . $classFormSecond . "'>";
                           echo "<select name='duration' id='duration'>";
                                echo "<option value='none'>--- Bitte ausw&auml;hlen ---</option>";
                                for ($i=3;$i<=$maxDuration;$i++) {
                                    echo "<option value='" . $i . "'>" . $i . " Monate</option>";
                                }
                            echo "</select>";
                    echo "</td>";
                echo "</tr>";
                
                // Vorname
                echo "<tr>";
                    echo "<td class='" . $classFormFront . "' valign='top'>Vorname:</td>";
                    echo "<td class='" . $classFormSecond . "'>";
                        echo "<input type='text' id='firstname' name='firstname' size='40' value='" . $fnValue . "' required />";
                    echo "</td>";
                echo "</tr>";
                
                // Nachname
                echo "<tr>";
                    echo "<td class='" . $classFormFront . "' valign='top'>Nachname:</td>";
                    echo "<td class='" . $classFormSecond . "'>";
                        echo "<input type='text' id='lastname' name='lastname' size='40' value='" . $lnValue . "' required />";
                    echo "</td>";
                echo "</tr>";
                
                // Mailadresse
                echo "<tr>";
                    echo "<td class='" . $classFormFront . "' valign='top'>E-Mail:</td>";
                    echo "<td class='" . $classFormSecond . "'>";
                        echo "<input type='text' id='mail' name='mail' size='40' value='" . $mailValue . "' required />";
                    echo "</td>";
                echo "</tr>";
                
                // Mailadresse bestaetigen
                echo "<tr>";
                    echo "<td class='" . $classFormFront . "' valign='top'>E-Mail best&auml;tigen:</td>";
                    echo "<td class='" . $classFormSecond . "'>";
                        echo "<input type='text' id='mail2compare' name='mail2compare' size='40' value='" . $mail2compareValue . "' required />";
                    echo "</td>";
                echo "</tr>";

                // Telefonnummer
                echo "<tr>";
                    echo "<td class='" . $classFormFront . "' valign='top'>Telefonnummer</td>";
                    echo "<td class='" . $classFormSecond . "'>";
                        echo "+41 <input type='text' id='phone' name='phone' size='35' maxlength='10' value='" . $phoneValue . "' required />";
                    echo "</td>";
                echo "</tr>";

                // Adresse
                echo "<tr>";
                    echo "<td class='" . $classFormFront . "' valign='top'>Strasse + Hausnummer:</td>";
                    echo "<td class='" . $classFormSecond . "'>";
                        echo "<input type='text' id='street' name='street' size='31' value='" . $streetValue . "' required />";
                        echo "<input type='text' id='streetNbr' name='streetNbr' size='5' value='" . $streetNbrValue . "' required />";
                    echo "</td>";
                echo "</tr>";
                
                // Ortschaft / PLZ
                echo "<tr>";
                    echo "<td class='" . $classFormFront . "' valign='top'>PLZ + Ort:</td>";
                    echo "<td class='" . $classFormSecond . "'>";
                        echo "<input type='text' id='plz' name='plz' size='5' value='" . $plzValue . "' required />";
                        echo "<input type='text' id='ort' name='ort' size='31' value='" . $ortValue . "' required />";
                    echo "</td>";
                echo "</tr>";
                
                // Bemerkung
                echo "<tr>";
                    echo "<td class='" . $classFormFront . "' valign='top'>Bemerkung:</td>";
                    echo "<td class='" . $classFormSecond . "'>";
                        echo "<textarea name='notes' id='notes' rows='4' cols='30' value='" . $notesValue . "'></textarea>";
                    echo "</td>";
                echo "</tr>";
                echo "<tr><td></td></tr>";
                
                // AGB
                echo "<tr><td colspan='2'>";
                    echo "<input type='checkbox' name='agb' id='agb' /> Ich best&auml;tige hiermit die <b><a class='not_menu' target='_blank' href='docs/Vertragsbestimmungen_Schlosser-Stubenwagen.pdf'>AVB</a></b> gelesen zu haben";
                echo "</td></tr>";
                echo "<tr><td></td></tr>";

                // Hidden Fields
                echo "<input type='hidden' value='" . $row['name'] . "' id='swName' name='swName' />";
                echo "<input type='hidden' value='" . $_GET['sub'] . "' id='swId' name='swId' />";
                
                // Submitbutton
                echo "<tr><td></td><td>";
                    echo "<input type='submit' value='Reservierung absenden' id='submit' name='submit' />";
                echo "</td></tr>";
            echo "</table>";

        echo "</form>";

     }

}

