<h1>Dashboard<h1>

<table width='100%'>
    <tr><td valign='top'>
        <h2>Bestellungen</h2>
        <?php
        dashboard("bestellung","*","status","open","Neue Bestellungen");
        dashboard("bestellung","*","status","paid","Bestellungen zum Verschicken");
        dashboard("bestellung","*","status","send","Verkaufte Artikel");
        ?>
    </td>
    <td valign='top'>
        <h2>Reservationen</h2>
        <?
        dashboard("reservation","*","status","open","Reservationen zu best&auml;tigen");
        dashboard("reservation","*","status","confirmed","Abholtermine zu definieren");
        dashboard("reservation","*","status","away","Stubenwagen zurzeit vermietet");
        dashboard("reservation","*","status","back","Fertige Reservationen");
        ?>
    </td>
    </tr>
</table>

<?php 
echo "<h2>N&auml;chste Abholtermine</h2>";

// getEntries
$dbcon  = dbConnect(DBUSER,DBPW);
$sql    = "select id_reservation, sw_id, reservation.status, stubenwagen.name, startdate, enddate, mail, ";
$sql   .= "firstname, lastname, plz, ort, street, streetNbr, date, cdDate, tDate_sys, tDate, notes from reservation ";
$sql   .= "join stubenwagen on reservation.sw_id=stubenwagen.id_sw where reservation.status = 'confirmed' ";
$sql   .= "and tDate_sys >= '" . date('Y-m-d') . " 00:00' order by tDate_sys asc limit 5";
$result = $dbcon->query($sql);
$count  = $result->num_rows;
dbClose($dbcon);

// check if content
if ( $count == 0 ) {
    echo "<p>Keine Abholtermine...</p>";
} else {
    // printTableHeader
    echo "<table width='100%'>";
        echo "<tr align='center'>";
            echo "<td class='top' width='25px' align='center'>Nr.</td>";
            echo "<td class='top' width='150px'>Abholtermin</td>";
            echo "<td class='top' width='150px'>Miete</td>";
            echo "<td class='top' width='150px'>Kunde</td>";
        echo "</tr>";

        // TableDesingCounter
        $tdCount = 1;

        // printEntries
        while ( $row = $result->fetch_assoc()  ) {

            // tableDesign
            $tdClass = ($tdCount % 2) == 0 ? "class='adminEven'" : "class='adminOdd'";
            $tdCount++;

            echo "<tr align='center'>";

                // Nummer
                echo "<td $tdClass>" . $row['id_reservation'] . "</td>";

                // Abholtermin
                echo "<td $tdClass>" . $row['tDate'] . "</br>";
                echo "<a class='not_menu' href='?top=reservation&sub=away&info=" . $row['id_reservation'] . "&away=" . $row['id_reservation'] . "&action=isAway'>(Abholung erfolgt!)</td>";

                // Reservation
                echo "<td $tdClass><a class='not_menu' target='_blank' href='http://www.schlosser-stubenwagen.ch/index.php?top=wagen&sub=" . $row['sw_id'] . "'>" . $row['name'] . "</a></br>";
                echo "(" . $row['startdate'] . " - " . $row['enddate'] . ")</br>";
                echo "Reserviert: " . $row['date'] . "</br>";

                // Kunde
                echo "<td $tdClass>" . $row['firstname'] . " " . $row['lastname'] . "</br>";
                echo "" . $row['street'] . " " . $row['streetNbr'] . "</br>";
                echo $row['plz'] . " " . $row['ort'] . "</td>";

            echo "</tr>";

        } // eof printingEntries

    echo "</table>";

} // eoi entries vorhanden

// Zurzeit vermiete Stubenwaegen
showBooking("away",""); 
?>

<h2>Webmail-Zugang</h2>
<?php
echo "<table><tr>";
    echo "<td width='100px' >Maillink:</td>";
    echo "<td><a class='not_menu' href='" . WEBMAIL_URL . "' target='_blank'>" . WEBMAIL_URL . "</a></td>";
echo "</tr><tr>";
    echo "<td width='100px' >Maildresse:</td>";
    echo "<td>" . WEBMAIL_ADDRESS . "</td>";
echo "</tr><tr>";
    echo "<td width='100px' >Mailpasswort:</td>";
    echo "<td>" . WEBMAIL_PW . "</td>";
echo "</tr><tr>";
echo "</table>";
?>
