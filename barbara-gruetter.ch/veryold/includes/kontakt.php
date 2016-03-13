<?php 
if (isset($_GET['top'])) $top=$_GET['top'];
if (isset($_GET['menu'])) $menu=$_GET['menu']; 

if ($top = "1") {?>
<form action="includes/fc_contact.php" method="post">
<?php }
elseif ($top = "2") { ?>
<form action="includes/tk_contact.php" method="post">
<?php } ?>
	<p>Angaben zu Ihrer Person:</p>
	<table>
		<tr>
			<td width=100px>Name:</td>
			<td width=100px><input type="text" name="name"><td/>
		</tr>
		<tr>
			<td width=100px>Strasse:</td>
			<td width=100px><input type="text" name="strasse"></td>
		</tr>
		<tr>
			<td width=100px>PLZ:</td>
			<td width=100px><input type="number" name="plz"></td>
		</tr>
		<tr>
			<td width=100px>Wohnort:</td>
			<td width=100px><input type="text" name="ort"></td>
		</tr>
		<tr>
			<td width=100px>Mailadresse:</td>
			<td width=100px><input type="text" name="mail"></td>
		</tr>
		<tr>
			<td width=100px>Telefonnummer:</td>
			<td width=100px><input type="number" name="telefon"></td>
		</tr>
	</table>
	<p>Angaben zu Ihrem Tier:</p>
	<table>
		<tr>
			<td width=100px>Tierart:</td>
			<td width=100px>
				<input type="radio" name="art" value="Hund">Hund
				<input type="radio" name="art" value="Katze">Katze
			<td/>
		</tr>
		<tr>
			<td width=100px>Name:</td>
			<td width=100px><input type="text" name="tiername"><td/>
		</tr>
		<tr>
			<td width=100px>Rasse:</td>
			<td width=100px><input type="text" name="rasse"></td>
		</tr>
		<tr>
			<td width=100px>Alter:</td>
			<td width=100px><input type="text" name="alter"></td>
		</tr>
	</table>
	<p>Ihre persönliche Nachricht:</p>
	<table>
		<tr>
			<td width=100px>Betreff:</td>
			<td width=100px><input type="text" name="betreff"></td>
		</tr>
		<tr>
			<td width=100px>Nachricht:</td>
			<td width=100px><textarea name="nachricht" cols="50" rows="10"></textarea></td>
		</tr>
		<tr>
			<td width=100px></td>
			<td width=75px align="center"><input type="submit" value="Senden"></td>
		</tr>
	</table>
	
	<br /><br />


	
	
</form>