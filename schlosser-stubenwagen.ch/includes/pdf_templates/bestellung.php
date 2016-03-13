<?php
// Globale Definitionen
$type       = $_SESSION['type'];
$id         = $_SESSION['id'];
$porto      = 7;

// Select Statement
$dbcon      = dbConnect(DBUSER,DBPW);
$sql        = "select * from bestellung join artikel on bestellung.art_id=artikel.id_art where id_bestellung = " . $id;
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
    <bookmark title="Brief" level="0" ></bookmark>

    <!-- Logo -->
    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 14px">
        <tr>
            <td style="width: 65%;"></td>
            <td style="width: 35%;">
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
        echo "<b>Best&auml;tigung und Rechnung Ihrer Bestellung bei <i>www.schlosser-stubenwagen.ch</i></b>";
    }
    echo "<br><br>";
    ?>
   
    Vielen Dank f&uuml;r Ihre Bestellung,<br><br>

    Ihre Bestellung werden Sie nach erfolgter Zahlung innert 1 bis 2 Wochen auf dem Postweg erhalten.<br><br>

    <b>Kosten&uuml;bersicht:</b><br><br>
    
    <table cellspacing="0" style="width: 100%; border: solid 1px black; font-size: 11pt;">
    <tr><td>
        <table cellspacing="0" style="width: 100%; background: #f08080; text-align: center;">
            <tr>
                <th style="width: 75%; text-align: left;">Artikel</th>
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
                <td style="width: 75%; text-align: left"><?=$row['name'];?></td>
                <td style="width: 10%; text-align: center;">1</td>
                <td style="width: 15%; text-align: right;">CHF <?=$row['price'];?></td>
            </tr>
            <tr height='20px'>
                <td style="width: 75%; text-align: left">Porto</td>
                <td style="width: 10%; text-align: center;">1</td>
                <td style="width: 15%; text-align: right;">CHF <?=$porto;?></td>
            </tr>
        </table>
    </td></tr>
    <tr><td>
        <table cellspacing="0" style="width: 100%; background: #f3c1ab;">
            <tr>
                <th style="width: 85%; text-align: left;">Total: </th>
                <? $total = $porto + $row['price']; ?>
                <th style="width: 15%; text-align: right;">CHF <?=$total;?></th>
            </tr>
        </table>
    </td></tr>
    </table>
    <br><br>

    <!-- Weiterer Text -->
    <table cellspacing="0" style="width: 90%; text-align: left; font-size: 11pt;">
        <tr><td colspan='3'><b>Kontoangaben:</b><br><br></td></tr>
        <tr><td colspan='3'></td></tr>
        <tr>
            <td style="width: 40%; text-align: left;">Raiffeisenbank G&uuml;rbe</td>
            <td style="width: 10%"></td>
            <td style="width: 50%; text-align: left;">CH64 8009 8000 0047 5757 6 (IBAN)</td>
        </tr>
        <tr>
            <td style="width: 40%; text-align: left;">Dorfstrasse 7</td>
            <td style="width: 10%"></td>
            <td style="width: 30%; text-align: left;">Claudia Schlosser</td>
        </tr>
        <tr>
            <td style="width: 40%; text-align: left;">3634 Thierachern</td>
            <td style="width: 10%"></td>
            <td style="width: 50%; text-align: left;">H&ouml;henweg 21</td>
        </tr>
        <tr>
            <td style="width: 40%; text-align: left;"></td>
            <td style="width: 10%"></td>
            <td style="width: 50%; text-align: left;">3661 Uetendorf</td>
        </tr>
    </table>
    <br><br>

    Bei Einzahlung am Postschalter fallen zus&auml;tzliche CHF 1.50 Geb&uuml;hren an.<br>
    Bei Fragen und Unklarheiten stehe ich Ihnen selbstverst&auml;ndlich jederzeit zur Verf&uuml;gung.<br><br><br>

    Besten Dank f&uuml;r Ihr Vertrauen!<br><br>
    <br><br>

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
