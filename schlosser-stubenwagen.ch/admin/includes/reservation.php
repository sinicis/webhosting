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
    $bookingAction = "no";
} else {
    $bookingAction = $_GET['action'];
}

// if action: yes
if ( $action == "yes" ) {
    $actionType = !isset($_GET['confirm']) ? "decline" : "confirm";
    $actionId   = !isset($_GET['confirm']) ? $_GET['decline'] : $_GET['confirm'];
    $error  = "none";

    // get wagen-id
    $sql    = "select sw_id, cdDate, status from reservation where id_reservation=" . $actionId;
    $dbcon  = dbConnect(DBUSER,DBPW);
    $result = $dbcon->query($sql);
    $row    = $result->fetch_assoc();
    dbClose($dbcon);

    // check if status is really 'open'
    if ( $row['status'] == "open" ) {

        if ( $actionType == "confirm" ) {

            // make pdf
            makePdf("reservation","confirmation",$actionId);

            // check if file exists
            if (!file_exists("mailpdf/reservation_" . $actionId . ".pdf")) {
                echo "da hets e fehler ge!";
                $error  = "occured";
            } // eoi file exists
        } // eoi action is confirm
           
        // check if no error so far
        if ( $error == "none" ) {

            // send mail
            $exitcode   = sendPdfMail($actionId,"booking",$actionType); 

            // check if mail sended well
            if ( $exitcode == "failed" ) {
                echo "da hets e fehler ge!";
            } else {
                // change database entry
                adminAction("reservation",$actionId,$actionType);

                // change the date
                $sql    = "update reservation set cdDate = '" . date('d.m.Y') . "' where id_reservation=$actionId";
                $dbcon  = dbConnect(DBUSER,DBPW);
                $result = $dbcon->query($sql);
                dbClose($dbcon);

                // Status
                $statusType = "succeed";
                $statusMsg  = "Die Reservation wurde erfolgreich best&auml;tigt.";

            } // eoi mail send okay

        } // eoi no error so far

    // if state not open
    } else {

        // Status
        $statusType = "failed";
        $statusMsg  = "Die Reservation wurde bereits am " . $row['cdDate'] . " best&auml;tigt! Der Kunde hat automatisch ein Mail bekommen samt AVB und Mietvertrag.";

    } // eoi not open

} // eoi action is needed (CONFIRM / DECLINE)

##################################
#
# STORNIERUNG
#
###################################

// if setting Termin
if ( $bookingAction == "cancel" ) {

    // get current
    $dbcon = dbConnect(DBUSER,DBPW);
    $result = getBookingEntries("",$_GET['cancel']);
    $row    = $result->fetch_assoc();

    // check if not already cancelled
    if ( $row['cDate'] != "" ) {

        // Status
        $statusType = "failed";
        $statusMsg  = "Die Reservation wurde bereits storniert!";

    } else {

        // update db
        $sql = "update reservation set status='cancelled', cDate = '" . date('d.m.Y') . "' where id_reservation = " . $_GET['cancel'];
        $result = $dbcon->query($sql);
        dbClose($dbcon); 
        
        // send Mail
        sendPdfMail($_GET['cancel'],"booking","cancel"); 

        // Status
        $statusType = "succeed";
        $statusMsg  = "Die Reservation wurde erfolgreich storniert!<br>";
        $statusMsg .= "Der Kunde wurde dar&uuml;ber automatisch per Mail informiert.";

        // calcNextFree
        calcNextFree($row['sw_id']);

    } // eoi not already cancelled

} // eo setTermin

##################################
#
# ABHOLTERMIN SETZEN
#
###################################

// if setting Termin
if ( $bookingAction == "setTermin" ) {

    // get current infos
    $dbcon  = dbConnect(DBUSER,DBPW);
    $result = getBookingEntries("",$_GET['info']);
    $row    = $result->fetch_assoc();

    // compaer
    if ( $row['tDate'] == $_POST['tDate'] . " (" . $_POST['tTime'] . ")" ) {

        // Status
        $statusType = "failed";
        $statusMsg  = "Dieser Abholtermin war bereits so definiert! Kein Mailversand!";

    } else {

        // convert tDate
        $tDate          =  $_POST['tDate'] . " (" . $_POST['tTime'] . ")";
        $tArray         = explode(".",$_POST['tDate']);
        list($d,$m,$y)  = $tArray;
        $tDate_sys      = $y . "-" . $m . "-" . $d . " " . $_POST['tTime']; 

        // Update DB
        $sql = "update reservation set tDate = '" . $tDate . "', tDate_sys='" . $tDate_sys . "' where id_reservation = " . $_GET['info'];
        $result = $dbcon->query($sql);
        dbClose($dbcon); 
        
        // send Mail
        sendPdfMail($_GET['info'],"booking","termin"); 

        // Status
        $statusType = "succeed";
        $statusMsg  = "Der Abholtermin wurde erfolgreich definiert: <b>" . $_POST['tDate'] . " (" . $_POST['tTime'] . ")</b><br>";
        $statusMsg .= "Der Kunde wurde dar&uuml;ber automatisch per Mail benachrichtigt samt Abholadresse und der Bitte AVB/MV mitzubringen.";

    } // eoi differet

} // eo setTermin

##################################
#
# WURDE ABGEHOLT / oder doch nicht zurueck
#
###################################

// if setting Termin
if (( $bookingAction == "isAway" ) || ( $bookingAction == "isNotBack" )) {

    $id = $bookingAction == "isAway" ? $_GET['away'] : $_GET['notBack'];

    // get current infos
    $dbcon  = dbConnect(DBUSER,DBPW);
    $result = getBookingEntries("",$id);
    $row    = $result->fetch_assoc();

    // update db
    $sql = "update reservation set status = 'away' where id_reservation = " . $id;
    $result = $dbcon->query($sql);
    dbClose($dbcon); 

    // Status
    $statusType = "succeed";
    $statusMsg  =  $bookingAction == "isAway" ? "Der Stubenwagen <i>" . $row['name'] . "</i> ist jetzt ausser Hause!" : "Der Stubenwagen <i>" . $row['name'] . " ist noch vermietet!";

} // eo setTermin


##################################
#
# IST ZURUECK
#
###################################

// if setting Termin
if ( $bookingAction == "isBack" ) {

    // get current infos
    $dbcon  = dbConnect(DBUSER,DBPW);
    $result = getBookingEntries("",$_GET['back']);
    $row    = $result->fetch_assoc();

    // update db
    $sql = "update reservation set status = 'back' where id_reservation = " . $_GET['back'];
    $result = $dbcon->query($sql);
    dbClose($dbcon); 

    // Status
    $statusType = "succeed";
    $statusMsg  = "Der Stubenwagen <i>" . $row['name'] . "</i> ist wieder Zuhause! :)";

} // eo setTermin

##################################
#
# NICHT ABGEHOLT
#
###################################

// if setting Termin
if ( $bookingAction == "isNotAway" ) {

    // get current infos
    $dbcon  = dbConnect(DBUSER,DBPW);
    $result = getBookingEntries("",$_GET['notAway']);
    $row    = $result->fetch_assoc();

    // update db
    $sql = "update reservation set status = 'confirmed' where id_reservation = " . $_GET['notAway'];
    $result = $dbcon->query($sql);
    dbClose($dbcon); 

    // Status
    $statusType = "succeed";
    $statusMsg  = "Der Stubenwagen <i>" . $row['name'] . "</i> wurde noch nicht abgeholt! :)";

} // eo setTermin

##################################
#
# WAGEN AUSWAHL
#
###################################

// if setting Termin
if ( $bookingAction == "setShow" ) {

    // Update SESSION
    if ( $_POST['show'] == "" ) {
        $_SESSION['showRId'] = "";
        $_SESSION['showRName'] = "Alle anzeigen";
    } else {
        $result = getWInfos($_POST['show']);
        $row = $result->fetch_assoc();
        $_SESSION['showRId'] = $_POST['show'];
        $_SESSION['showRName'] = $row['name'];
    }

    // Status
    $statusType = "succeed";
    $statusMsg  = $_POST['show'] == "" ? "Du siehst jetzt die Reservationen aller Stubenwagen!" : "Du siehst jetzt nur noch die Reservationen des Stubenwagens <i>" . $row['name'] . "</i>!";

} // eo setTermin

##############################
#                            #
#    OVERVIEW                #
#                            #
##############################

// Titel
echo "<h1>Reservations-Verwaltung</h1>";

echo "<p><b>Tipp:</b> Um mehr Informationen zu einer Reservation zu bekommen musst du zuvorderst in der Zeile auf die Zahl klicken.</p>";

##############################
#                            #
#    WAGEN AUSWAHL           #
#                            #
##############################

// alle wagen aus db
$dbcon  = dbConnect(DBUSER,DBPW);
$sql    = "select id_sw, name from `stubenwagen` where active = 'yes'";
$result = $dbcon->query($sql);
dbClose($dbcon);

// link
$formLink   = (!isset($_GET['sub'])) ? "?top=reservation" : "?top=reservation&sub=" . $_GET['sub'];

// anzeige (auswahl)
echo "Was soll angezeigt werden?";
echo "<form name='wagenauswahl' id='wagenauswahl' method='post' action='" . $formLink . "&action=setShow'>";
    echo "<select name='show'>";
        echo "<option value='" . $_SESSION['showRId'] . "'>" . $_SESSION['showRName'] . "</option>";
        if ( $_SESSION['showRId'] != "" ) {
            echo "<option value=''>Alle anzeigen</option>";
        }
        // Punkt fuer jeden Wagen
        while ( $row=$result->fetch_assoc() ) {
            if ( $row['id_sw'] != $_SESSION['showRId'] ) {
                echo "<option value='" . $row['id_sw'] ."'>" . $row['name'] . "</option>";
            }
        }
    echo "</select>";

    // Submitbutton
    echo "<tr><td></td><td>";
        echo "<input type='submit' value='anzeigen' id='submit' name='submit' />";
    echo "</td></tr>";

echo "</form>";

// Status
if ( $statusType != "" ) {
    echo "</br>";
    printStatus($statusType,$statusMsg);
}

// Show just what you need
showBooking($sub);

?>
