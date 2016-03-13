<?php

// Globale Variabeln
$status     = "proceeding";
$error      = "";

// Artikelname
$result = getArtikel($_GET['sub'],$_GET['buy'],"");
$row        = $result->fetch_assoc();
$artName    = $row['name'];
$price      = $row['price'];
$porto      = $row['porto'];

// Formular-Ueberpruefung
if ((isset($_GET['action'])) && ($_GET['action'] == "send")) {

    // check if all fields are filled out
    if (!isset($_POST['firstname']) ||
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

                                            // Inventory-Anzahl ueberpruefen
                                            $invCount    = getInvCount($_GET['buy']);
                                            if ($invCount == 0) {
                                                $status     = "failed";
                                                $error      = "Der von Ihnen ausgewählte Artikel wurde in der Zwischenzeit gebucht und ist nicht mehr verf&uuml;gbar - Tut mir leid!";

                                            } else {

                                                // Inventory-Anzahl verkleinern
                                                $invCountBefore     = getInvCount($_GET['buy']);
                                                $invCountAfter      = ($invCountBefore - 1);
                                                setInvCount($_GET['buy'],$invCountAfter);
    
                                                // DB-Eintrag erstellen
                                                $dbcon  = dbConnect(DBUSER,DBPW);
                                                $sql    = "insert into bestellung (art_id,status,firstname,lastname,mail,phone,street,streetNbr,plz,ort,notes,date) values ";
                                                $sql   .= "(" . $_GET['buy'] . ",'open','" . $_POST['firstname'] . "','" . $_POST['lastname'] . "','" . $_POST['mail'] . "','" . $_POST['phone'] . "','";
                                                $sql   .= $_POST['street'] . "','" . $_POST['streetNbr'] . "','" . $_POST['plz'] . "','" .$_POST['ort'] . "','";
                                                $sql   .= htmlspecialchars($_POST['notes'],ENT_QUOTES) . "','" . date("Y-m-d") . "')";
                                                $dbcon->query($sql);
                                                  
                                                // Mailversand 
                                                $id = $dbcon->insert_id;
                                                sendAdminMail("bestellung");

                                                // make pdf and send it
                                                makePdf("bestellung","confirmation",$id);
                                                sendPdfMail($id,"bestellung","confirm");

                                                $status   = "succeed";
                                
                                            } // eoi artikel is available

                                        } // eoi check ort   
                                
                                    } // eoi check plz   
                                
                                } // eoi check streetNbr   
                            
                            } // eoi check street   
                            
                        } // eoi check phone   

                    } // eoi mail is valid

                } // eoi mails not same

            } // eoi lastname valid

        } // eoi firstname valid

    } // eoi all filled out

} // eo form-checking

// Artikel nochmals ausgeben
//echo "<h4>Artikelbeschreibung</h4>"; 
//printArtikel($_GET['sub'],$_GET['buy'],"showalways");

// Formular-Definitionen
$classFormFront = "formFront";
$classFormSecond = "formSecond";

// Erfolgsmeldung
if (($status == "succeed") && ($error == "")) {
    echo "<p>";
    echo "<b><font color='green'>Besten Dank! :)</font></b></br>";
    echo "Ihre Bestellung wurde soeben erfolgreich abgeschickt, in K&uuml;rze werden Sie eine Best&auml;tigungs-Nachricht erhalten.";
    echo "</p>";

// Formular ausgeben
} else {

    // Fehlerausgabe wenn noetig
    if (($status == "failed") && ($error != "")) {
        echo "<p>";
        echo "<b><font color='red'>Achtung Fehler:</font></b></br>";
        echo $error;
        echo "</p>";
    }

    // Falls abgeschickt: Inhalte sichern
    $countValue         = isset($_POST['count']) ? $_POST['count'] : ""; 
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

    echo "<form name='reserv' id='reserv' method='post' action='?top=shop&sub=" . $_GET['sub'] . "&buy=" . $_GET['buy'] ."&action=send'>";

       echo "<table width='450px'>";
            // Header
            echo "<tr>";
                echo "<td class='top' align='right'><b>Eigenschaft</b></td>";
                echo "<td class='top'><b>Beschreibung</b></td>";
            echo "</tr>";

            // Welcher Artikel
            echo "<tr>";
                echo "<td class='" . $classFormFront . "' valign='top'>Gew&uuml;nschter Artikel</td>";
                echo "<td class='" . $classFormSecond . "'>";
                    echo "<b>" . $artName . "</b>";
                echo "</td>";
            echo "</tr>";

            // Welcher Preis
            echo "<tr>";
                echo "<td class='" . $classFormFront . "' valign='top'>Preis</td>";
                echo "<td class='" . $classFormSecond . "'>";
                    echo "<b>CHF " . $price . " (CHF " . $porto . " Porto)</b>";
                echo "</td>";
            echo "</tr>";
            
            // Vorname
            echo "<tr>";
                echo "<td class='" . $classFormFront . "' valign='top'>Vorname</td>";
                echo "<td class='" . $classFormSecond . "'>";
                    echo "<input type='text' id='firstname' name='firstname' size='40' value='" . $fnValue . "' required />";
                echo "</td>";
            echo "</tr>";
            
            // Nachname
            echo "<tr>";
                echo "<td class='" . $classFormFront . "' valign='top'>Nachname</td>";
                echo "<td class='" . $classFormSecond . "'>";
                    echo "<input type='text' id='lastname' name='lastname' size='40' value='" . $lnValue . "' required />";
                echo "</td>";
            echo "</tr>";
            
            // Mailadresse
            echo "<tr>";
                echo "<td class='" . $classFormFront . "' valign='top'>E-Mail</td>";
                echo "<td class='" . $classFormSecond . "'>";
                    echo "<input type='text' id='mail' name='mail' size='40' value='" . $mailValue . "' required />";
                echo "</td>";
            echo "</tr>";
            
            // Mailadresse bestaetigen
            echo "<tr>";
                echo "<td class='" . $classFormFront . "' valign='top'>E-Mail best&auml;tigen</td>";
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
                echo "<td class='" . $classFormFront . "' valign='top'>Strasse + Hausnummer</td>";
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

            // Hidden Fields
            echo "<input type='hidden' value='" . $row['name'] . "' id='artName' name='artName' />";
            echo "<input type='hidden' value='" . $_GET['buy'] . "' id='artId' name='artId' />";
            
            // Submitbutton
            echo "<tr><td colspan='2' style='text-align: right;'>";
                echo "<input type='submit' value='Bestellung absenden' id='submit' name='submit' />";
            echo "</td></tr>";
        echo "</table>";

    echo "</form>";

}

// Action-Button
echo "</br>";
actionButton("left","?top=shop&sub=" . $_GET['sub'],"Zur&uuml;ck zur Auswahl");
/*
echo "<a href='?top=shop&sub=" . $_GET['sub'] . "' class='not_menu'>";
    echo "<div class='actionLeft' id='actionLeft'>";
        echo "<table width='100%'>";
            echo "<tr>";
                echo "<td style='line-height:0'>";
                    echo "<img src='img/arrowLeft.png' />";
                echo "</td>";
                echo "<td valign='center'>";
                    echo "<b>Zur&uuml;ck zur Auswahl</b>";
                echo "</td>";
            echo "</tr>";
        echo "</table>";
    echo "</div>";
echo "</a>";
*/
