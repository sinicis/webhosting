<?php

// global stuff
$id             = $_SESSION['id'];
$type           = $_SESSION['type'];
$kautionCosts   = 50;
$basicCosts     = 200;

// Select Statement
$dbcon      = dbConnect(DBUSER,DBPW);
$sql        = "select * from reservation join stubenwagen on reservation.sw_id=stubenwagen.id_sw where id_reservation = " . $id;
$result     = $dbcon->query($sql);
$row        = $result->fetch_assoc();
dbClose($dbcon);
?>

<style type="text/css">
<!--
table { vertical-align: top; }
tr    { vertical-align: top; }
td    { vertical-align: top; }
}
-->
</style>

<page backcolor="#FEFEFE" footer="datum;stunde;seite" style="font-size: 11pt">
    <bookmark title="Reservartionsbestaetigung" level="0" ></bookmark>

    <!-- Logo -->
    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 14px">
        <tr>
            <td style="width: 75%;"></td>
            <td style="width: 25%;">
                <img style="width: 100%;" src="/home/ironsmit/ss_dev/img/logo-web.png" alt="Logo"><br>
            </td>
        </tr>
    </table>
    <br><br>
   
    <!-- Kundenanschrift -->
    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 11pt;">
        <tr>
            <td style="width:65%;"></td>
            <td style="width:35%">
                <?php
                echo $row['firstname'] . " " . $row['lastname'] . "<br>";
                echo $row['street'] . " " . $row['streetNbr'] . "<br>";
                echo $row['plz'] . " " . $row['ort'] . "<br>";
                ?>
            </td>
        </tr>
    </table>
    <br><br>

    <!-- Datumzeile -->
    <table cellspacing="0" style="width: 100%; text-align: left;font-size: 11pt">
        <tr>
            <td style="width:65%;"></td>
            <td style="width:35%; ">Uetendorf, der <?php echo date('d.m.Y'); ?></td>
        </tr>
    </table>
    <br><br>

    <? 
    if ( $type == "confirmation" ) {
        echo "<b>Best&auml;tigung Ihrer Reservation des Stubenwagen <i>" . $row['name'] . "</i></b>";
    }
    echo "<br><br>";
    ?>
   
    Vielen Dank f&uuml;r Ihre Reservation!<br><br>

   <b>Ihre Reservation:</b> 
<br><br>

    <table cellspacing="0" style="width: 100%; border: solid 1px black; font-size: 11pt;">
    <tr><td>
        <table cellspacing="0" style="width: 100%; background: #fff;">
            <tr height='20px'>
                <td style="width: 21%;  background: #f08080;  text-align: left">Stubenwagen</td>
                <td style="width: 1%;"></td>
                <td style="width: 78%; text-align: left;"><?=$row['name'];?></td>
            </tr>
            <tr height='20px'>
                <td style="width: 21%; background: #f08080;  text-align: left">Mietzeit</td>
                <td style="width: 1%;"></td>
                <td style="width: 78%; text-align: left;"><?=$row['startdate'];?> - <?=$row['enddate'];?> (<?=$row['duration'];?> Monate)</td>
            </tr>
        </table>
    </td></tr>
    </table>

<br>
   <b>Kosten&uuml;bersicht:</b> 
<br><br>
    
    <table cellspacing="0" style="width: 100%; border: solid 1px black; font-size: 11pt;">
    <tr><td>
        <table cellspacing="0" style="width: 100%; background: #f08080; text-align: center;">
            <tr>
                <th style="width: 75%; text-align: left;">Rechnungsposten</th>
                <th style="width: 10%; text-align: center;">Anzahl</th>
                <th style="width: 15%; text-align: right">Preis</th>
            </tr>
            <tr>
                <td></td>
            </tr>
        </table>
    </td></tr>
    <tr><td>
        <table cellspacing="0" style="width: 100%; background: #fff;">
            <tr height='20px'>
                <td style="width: 75%; text-align: left">Stubenwagen Basismiete (3 Monate)</td>
                <td style="width: 10%; text-align: center;">1</td>
                <td style="width: 15%; text-align: right;"><?=$basicCosts;?></td>
            </tr>
            <tr height='20px'>
                <td style="width: 75%; text-align: left">Miete weitere Monate</td>
                <td style="width: 10%; text-align: center;"><?=($row['duration']-3);?></td>
                <td style="width: 15%; text-align: right;"><? echo ($row['duration']-3)*50;?></td>
            </tr>
            <tr height='20px'>
                <td style="width: 75%; text-align: left">Kaution</td>
                <td style="width: 10%; text-align: center;">1</td>
                <td style="width: 15%; text-align: right;"><?=$kautionCosts;?></td>
            </tr>
        </table>
    </td></tr>
    <tr><td>
        <table cellspacing="0" style="width: 100%; background: #f3c1ab;">
            <tr>
                <th style="width: 85%; text-align: left;">Total: </th>
                <? $totalCosts = $kautionCosts + $basicCosts + ($row['duration']-3)*50; ?>
                <th style="width: 15%; text-align: right;">CHF <?=$totalCosts;?></th>
            </tr>
        </table>
    </td></tr>
    </table>

    <br>

    <!-- Weiterer Text -->
    Die Bezahlung des Rechnungsbetrags von CHF <?=$totalCosts;?> erfolgt bar bei der Abholung des Stubenwagens. Den Abholtermin werden wir noch gemeinsam bestimmen. 
    Anschliessend werde ich Ihnen diesen Termin per Mail best&auml;tigen.
    <br><br>

    Bei Fragen und Unklarheiten stehe ich Ihnen selbstverst&auml;ndlich jederzeit zur Verf&uuml;gung.<br><br><br>

    Besten Dank f&uuml;r Ihr Vertrauen!<br><br>
    <br><br>

<br><br><br><br><br>


    Freundliche Gr&uuml;sse<br><br><br>

    Claudia Schlosser

    <!-- Eigene Kontaktangaben -->
    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 11pt;">
        <tr>
            <td style="width:60%;"></td>
            <td style="width:40%; ">
                Schlosser Stubenwagenvermietung<br>
                info@schlosser-stubenwagen.ch<br>
                H&ouml;henweg 21<br>
                3661 Uetendorf<br>
            </td>
        </tr>
    </table>
</page>
