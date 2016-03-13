<?php

// Default definitions
$statusMsg  = "";
$statusType = "";

// check action
$action     = (!isset($_GET['confirm'])) && ( !isset($_GET['decline'])) ? "no" : "yes";

// Zahl- / Sendetag
if (!isset($_GET['action'])) {
    $orderAction = "no"; 
} else {
    $orderAction = $_GET['action'];
}

if ( $action == "yes" ) {
    $actionType = !isset($_GET['confirm']) ? "decline" : "confirm";
    $actionId   = !isset($_GET['confirm']) ? $_GET['decline'] : $_GET['confirm'];
    $error  = "none";

    // get wagen-id
    $sql    = "select art_id, status from bestellung where id_bestellung=" . $actionId;
    $dbcon  = dbConnect(DBUSER,DBPW);
    $result = $dbcon->query($sql);
    $row    = $result->fetch_assoc();
    dbClose($dbcon);

    // check if status is really 'open'
    if ( $row['status'] == "open" ) {

        if ( $actionType == "confirm" ) {

            // make pdf
            makePdf("bestellung","confirmation",$actionId);

            // check if file exists
            if (!file_exists("mailpdf/bestellung_" . $actionId . ".pdf")) {
                echo "da hets e fehler ge!";
                $error  = "occured";
            } // eoi file exists
        } // eoi action is confirm

        // check if no error so far
        if ( $error == "none" ) {

            // send mail
            $exitcode   = sendPdfMail($actionId,"bestellung",$actionType);

            // check if mail sended well
            if ( $exitcode == "failed" ) {
                echo "da hets e fehler ge!";
            } else {
                // change database entry
                adminAction("bestellung",$actionId,$actionType);
            } // eoi mail send okay

        } // eoi no error so far

    } // eoi state is open

} // eoi action is needed


##################################
#
# ZAHLTAG SETZEN
#
###################################

// if setting Termin
if ( $orderAction == "setPaydate" ) {

    // DB-Update
    $dbcon = dbConnect(DBUSER,DBPW);
    $sql = "update bestellung set pDate = '" . $_POST['pDate'] . "', status = 'paid' where id_bestellung = " . $_GET['info'];
    $result = $dbcon->query($sql);
    dbClose($dbcon);

    //Mail versand
    sendPdfMail($_GET['info'],"bestellung","setPaydate");

    // Status
    $statusType     = "succeed";
    $statusMsg      = "Das Datum des Zahlungseingangs (" . $_POST['pDate'] . ") der Bestellung " . $_GET['info'] . " wurde definiert. ";
    $statusMsg     .= "Und der Kunde wurde dar&uuml;ber per Mail informiert.";

} // eo setPaydate

##################################
#
# SENDETAG SETZEN
#
###################################

// if setting Termin
if ( $orderAction == "setSenddate" ) {
    $dbcon = dbConnect(DBUSER,DBPW);
    $sql = "update bestellung set sDate = '" . $_POST['sDate'] . "', status = 'send' where id_bestellung = " . $_GET['info'];
    $result = $dbcon->query($sql);
    dbClose($dbcon);

    //Mail versand
    sendPdfMail($_GET['info'],"bestellung","setSenddate");

    // Status
    $statusType     = "succeed";
    $statusMsg      = "Das Datum der Postaufgabe (" . $_POST['sDate'] . ") der Bestellung " . $_GET['info'] . " wurde definiert. ";
    $statusMsg     .= "Und der Kunde wurde dar&uuml;ber per Mail informiert.";

} // eo setSenddate

##################################
#
# SENDETAG SETZEN
#
###################################

// if setting Termin
if ( $orderAction == "sendRemember" ) {

    // check if already remembered
    $dbcon = dbConnect(DBUSER,DBPW);
    $sql  = "select rDate from bestellung where id_bestellung = " . $_GET['remember'];
    $result = $dbcon->query($sql);
    dbClose($dbcon);

    $row = $result->fetch_assoc();

    // if done: warning and nothing
    if ( $row['rDate'] != "" ) {
        $statusType     = "failed";
        $statusMsg      = "Der Kunde wurde bereits am " . $row['rDate'] . " dar&uuml;ber informiert, dass noch keine Zahlung ";
        $statusMsg     .= "bei dir eingegangen ist. Eventuell ist es nun an der Zeit die Bestellung zu stornieren.";

    // if not: mailing and db-update
    } else {

        // db update
        $dbcon = dbConnect(DBUSER,DBPW);
        $sql = "update bestellung set rDate = '" . date('d.m.Y'). "' where id_bestellung = " . $_GET['remember'];
        $result = $dbcon->query($sql);
        dbClose($dbcon);
        
        // MAIL VERSAND
        sendPdfMail($_GET['remember'],"bestellung","remember");

        // Status
        $statusType     = "succeed";
        $statusMsg      = "Der Kunde wurde soeben erfolgreich dar&uuml;ber informiert, dass bis heute noch keine Zahlung ";
        $statusMsg     .= "bei dir eingegangen ist und dass du dir vorbeh&auml;tst die Bestellung zu stornieren, falls ";
        $statusMsg     .= "auch in den kommenden 30 Tagen die Zahlung nicht erfolgt ist.";

    }

} // eo sendRemember

##################################
#
# STORNIERUNG (unbezahlt) 
#
###################################

// if setting Termin
if ( $orderAction == "cancelPay" ) {

    // MAILVERSAND
    //sendPdfMail($_GET['cancelPay'],"bestellung","cancelPay");
    // check if already remembered
    $dbcon = dbConnect(DBUSER,DBPW);
    $sql  = "select rDate from bestellung where id_bestellung = " . $_GET['cancelPay'];
    $result = $dbcon->query($sql);
    dbClose($dbcon);

    $row = $result->fetch_assoc();

    // if done: warning and nothing
    if ( $row['rDate'] == "" ) {
        $statusType     = "failed";
        $statusMsg      = "Der Kunde hat noch keine Zahlungserinnerung bekommen. Zuerst muss diese verschickt werden, ";
        $statusMsg     .= "bevor du die Bestellung stornieren kannst.";

    // if not: mailing and db-update
    } else {

        // send mail
        sendPdfMail($_GET['cancelPay'],"bestellung","cancelPay");

        // delete in DB
        $dbcon = dbConnect(DBUSER,DBPW);
        $sql  = "update bestellung set cDate = '" . date('d.m.Y') . "', cReason = 'nicht bezahlt', status = 'cancelled' where id_bestellung = " . $_GET['cancelPay'];
        $result = $dbcon->query($sql);
        dbClose($dbcon);

        // Inv Count vergroessern
    /*    $invCountBefore     = getInvCount($_GET['buy']);
        $invCountAfter      = ($invCountBefore + 1);
        setInvCount($_GET['buy'],$invCountAfter);
    */

        // Status
        $statusType     = "succeed";
        $statusMsg      = "Der Kunde wurde soeben erfolgreich dar&uuml;ber informiert, dass seine Bestellung storniert wurde.</br>";
        $statusMsg     .= "Grund: keine Zahlung erfolgt.";

    } // eoi rememberd

} // eo cancelPay

##################################
#
# STORNIERUNG (Kundenwunsch) 
#
###################################

if ( $orderAction == "cancelClient" ) {

    // send mail
    sendPdfMail($_GET['cancelClient'],"bestellung","cancelClient");

    // delete in DB
    $dbcon = dbConnect(DBUSER,DBPW);
    $sql  = "update bestellung set cDate = '" . date('d.m.Y') . "', cReason = 'Kundenwunsch', status = 'cancelled' where id_bestellung = " . $_GET['cancelClient'];
    $result = $dbcon->query($sql);
    dbClose($dbcon);

    // Inv Count vergroessern
/*    $invCountBefore     = getInvCount($_GET['buy']);
    $invCountAfter      = ($invCountBefore + 1);
    setInvCount($_GET['buy'],$invCountAfter);
*/

    // Status
    $statusType     = "succeed";
    $statusMsg      = "Der Kunde wurde soeben erfolgreich dar&uuml;ber informiert, dass seine Bestellung storniert wurde.</br>";
    $statusMsg     .= "Grund: Kundenwunsch.";

} // eoi cancelClient

// Titel
echo "<h1>Bstelligs-Vrwautig</h1>";

echo "<p><b>Tipp:</b> Um mehr Informationen zu einer Bestellung zu bekommen musst du zuvorderst in der Zeile auf die Zahl klicken.</p>";

// Status
if ( $statusType != "" ) {
    printStatus($statusType,$statusMsg);
}

// Check if on subsite
$sub    = (!isset($_GET['sub'])) ? "" : $_GET['sub'];

// Show just what you need
showOrder($sub);


?>
