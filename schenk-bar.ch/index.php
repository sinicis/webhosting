<!DOCTYPE html>

<html lang="de">
<head>
	<title>SchenkBAR</title>
	<meta name="description" content="SchenkBAR" />
	<meta name="keywords" content="schenkbar schenk bar geschenkideen geschenk geschenke bar" />
	<link rel="stylesheet" type="text/css" href="css/style.css" />
</head>
<body>

	<div class='img'>

		<div class='logo'>
			<table width='100%' align='center'>
				<tr align='center'><td width='100%' align='center'>
					<img src='img/logo.png' width='80%' />
				</td></tr>
				<tr><td>
					Geschenke mit dem gewissen Etwas
				</td></tr>
			</table>
			
        <?php
        # FERIENMELDUNG
        require_once('/home/ironsmit/sec-data/schenk-bar.ch/.sec_data.php');
        include('functions.php');

        // get holiday entries
        $row    = getHoliday();

        // wenn aktiv
        if ( $row['active'] == "yes" ) {

            echo "<table width='100%' align='center'>";
				echo "<tr><td><font color='red'>Ferien vom " . $row['start'] ." - " . $row['end'] . "</font></td></tr>";
			echo "</table>";
        }

        // get notice entries
        $row    = getNotice();

        // wenn aktiv
        if ( $row['active'] == "yes" ) {

            echo "<table width='100%' align='center'>";
				echo "<tr><td><font color='" . $row['color'] . "'>" . $row['notice'] ."</font></td></tr>";
			echo "</table>";

        }
?>
	    </div>

	</div>
	
	<div class='textLeft' align='center'>
		Es erwartet Sie eine </br>geballte Ladung an aussergew&ouml;hnlichen,
		fantasievollen, individuellen, kreativen, k&ouml;stlichen,
		kunstvollen, pers&ouml;nlichen und &uuml;berraschenden Geschenkideen.
	</div>
	<div class='textRight'>
		<table class='textRight'>
			<tr><td colspan='2' align='left'>&Ouml;ffnungszeiten / Infos:</td></tr>
			<tr>
				<!--<td width='10px'></td> -->
				<td height='10' width='6px' align='center' valign='top'>
					Mo</br>Di</br>Mi</br>Do</br>Fr</br>Sa
				</td>			
				<td height='10' align='left' valign='top'>
					14.00-18.00</br>
					14.00-18.00</br>
					09.00-12.00 | 14.00-18.00</br>
					09.00-12.00</br>
					geschlossen</br>
					09.00-13.00 
				</td>
			</tr>
			<tr></tr>
			<tr><td colspan='2' align='left'>
				Parkpl&auml;tze sind bei der Gemeindeverwaltung Hilterfingen vorhanden /
				&Ouml;V-Verbindungen Bus Nr. 21 Richtung Interlaken bis Haltestelle "Hilterfingen Post"
			</td></tr>
		</table>
	
	</div>
	
	<div class="footer">
		<center>
			Bachgasse 9, 3652 Hilterfingen</br>
			<a class='inline' href="mailto:info@schenk-bar.ch">info@schenk-bar.ch</a>
		</center>
	</div>
	
	<div class="ironsmithBox">		
		<div class="sbKontakt">
			s
		</div>
	</div>
	<div class="ironsmithLink">
		&copy; <a class='outline' href='http://www.ironsmith.ch'>ironsmith Webdesign</a> 2015
	</div>
</body>
</html>
