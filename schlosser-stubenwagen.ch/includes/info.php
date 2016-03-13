<?php
$subsite        = !isset($_GET['sub']) ? "" : $_GET['sub'];
?>

<h1>Informationen</h1>

<!-- // Infos zu den Stubenwagen                                                  -->
<h2>Stubenwagen (Preise)</h2>
<?php
if ( $subsite != "wageni" ) {
    echo "<a href='?top=info&sub=wageni' class='not_menu'><i>Klicken Sie hier um mehr Informationen zu bekommen...</br></i></a>";
} else {
?> 
    <p>
    Die Mindestmietdauer betr&auml;gt 3 Monate und je nach Gr&ouml;sse der Stubenwagen k&ouml;nnen sie bis zu 6 Monate lang 
    gemietet werden. Die Stubenwagen k&ouml;nnen nicht geliefert werden, sondern m&uuml;ssen in Burgistein bei Thun abgeholt 
    werden.</br>
    </p>
    <h3>Preise / Bezahlung</h3>
    <p>
    Die Bezahlung erfolgt bar bei der Abholung des Stubenwagens. 
    </p>

    <table style="width: 500px;" border="0">
        <tr>
            <td>Miete (3 Monate)</td>
            <td style="text-align: right;"><strong>CHF 200</strong></td>
        </tr>
        <tr>
            <td>jeder weitere Monat</td>
            <td style="text-align: right;"><strong>CHF 50</strong></td>
        </tr>
        <tr>
            <td>Kaution</td>
            <td style="text-align: right;"><strong>CHF 50</strong></td>
        </tr>
    </table>

    <h3>Ablauf Miete</h3>
    <p>
    Sie k&ouml;nnen Ihren gemieteten Stubenwagen eine Woche vor Beginn der Reservation bei mir abholen. Falls dies ausnahmsweise 
    nicht m&ouml;glich w&auml;re, werde ich mich mit Ihnen fr&uuml;hzeitig in Verbindung setzen.</br></br>
    <b>Hinweis:</b> Das Start- und Enddatum Ihrer Mietzeit wird dadurch nicht verschoben.</br>
    <p>

    <h4>Abholung</h4>
    <p>
    Die Stubenwagen werden in meinem Atelier in Burgistein abgeholt. Ich bitte Sie sich im Voraus mit mir in Verbindung zu setzen um einen Termin abzumachen.</br></br>
    (Hier gehts zur <a href='?top=kontakt' class='not_menu'>Adresse und den Kontaktdaten</a>).
    </p>

    <h4>Fr&uuml;hgeburt</h4>
    <p>
    Falls Ihr Kind unerwartet deutlich fr&uuml;her zur Welt kommt als zum Beginn Ihrer Miete, steht bei mir
    noch ein "Notfall-Stubenwagen" bereit, welchen Sie benutzen d&uuml;rfen, bis der von Ihnen reservierte Stubenwagen wieder 
    zur Verf&uuml;gung steht.</br></br>

    <b>Hinweis:</b> Dadurch verschiebt sich jedoch auch das Ende der Mietdauer entsprechend nach vorne.
    </p>

    <h4>R&uuml;ckgabe</h4>
    <p>
    Am Ende der Mietdauer d&uuml;rfen Sie den Stubenwagen mit den ungewaschenen Stoffteilen zur&uuml;ckbringen.</br></br>
    (R&uuml;ckgabe an selber Adresse wie Abholung)
    </p>
<?
}
?>

<!-- // Infos zum Shop                                                  -->
<h2>Zubeh&ouml;r (Shop)</h2>
<?php
if ( $subsite != "shopi" ) {
    echo "<a href='?top=info&sub=shopi' class='not_menu'><i>Klicken Sie hier um mehr Informationen zu bekommen...</br></i></a>";
} else {
?> 
    <p>
    Die Zubeh&ouml;r-Artikel k&ouml;nnen Sie in meinem <a href='?top=shopi' class='not_menu'>Shop</a> kaufen. 
    </p>

    <h3>Stofftiere</h3>
    <h4>Herkunft/Herstellung</h4>
    <p>
    Die lustigen Stofftiere kommen aus der Tilda Kollektion. Mit viel Liebe werden sie von Dora Gfeller hergestellt. 
    </p>

    <h4>Bezahlung</h4>
    <p>
    Sie bekommen die Rechnung zusammen mit einer Best&auml;tigungs-Mail. Sobald die Zahlung bei mir eingegangen ist, beginnt die Lieferfrist.
    </p>

    <h4>Lieferfrist</h4>
    <p>
    Die Lieferfrist beträgt ca. 1 bis 2 Wochen. Bei Verz&ouml;gerungen der Lieferung werde ich Sie fr&uuml;hzeitig informieren.
    </p>

    <h4>Farbwahl der Tierli-Chleider</h4>
    Bei den Giraffen, Elefanten und dem Hasen k&ouml;nnen Sie die Farben der Kleider auf Wunsch w&auml;hlen 
    (Im Bestellformular: Feld <i>Bemerkung</i>), das heisst in den rot, blau, gr&uuml;n, gelb, rosa, usw.. T&ouml;nen.</br>
    </p>
 
<?
}
?>
