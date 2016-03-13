<?php
// Alle Kategorien auslesen
$result     = getCategory("");
$catId      = array();
$catName    = array();
$catImg     = array();
$i          = 0;

// Fill up the arrays
while ( $row = $result->fetch_assoc() ) {

    // wenn nicht deaktiviert
    if ($row['active'] != "no" ) {

        // check if artikel
        $sql    = "select count(id_art) as count from artikel where inventory != 0 and active != 'no' and cat_id=" . $row['id_cat'];
        $dbcon  = dbConnect(DBUSER,DBPW);
        $resultArt = $dbcon->query($sql);
        $rowArt = $resultArt->fetch_assoc();
        dbClose($dbcon);

        // display if artikel
        if ( $rowArt['count'] != 0 ) {
            $i++;
            array_push($catId,$row['id_cat']);
            array_push($catName,$row['category']);
            array_push($catImg,$row['photo']);
        } // eoi artikel vorhanden
    } // eoi not inactive
}

// Anzhal Kategorien
$catCount   = $i;

// Alle Artikel auslesen
$result     = getArtikel("","","");
$artCount   = $result->num_rows;
$artId      = array();
$artName    = array();
$artDesc    = array();
$artPrice   = array();
$artImg     = array();

// Arrays fuellen
while ($row = $result->fetch_assoc()) {
    array_push($artId,$row['id_art']);
    array_push($artName,$row['name']);
    array_push($artDesc,$row['description']);
    array_push($artImg,$row['photo']);
    array_push($artPrice,$row['price']);
}

// ---------------------------------------
// Titel:
// ---------------------------------------
// a) wenn nichts gewaehlt
if ((!isset($_GET['buy'])) || (!in_array($_GET['buy'],$artId))) {
    if ((!isset($_GET['sub'])) || (!in_array($_GET['sub'],$catId))) {
        $title = "Shop";
// b) wenn Kategorie gewaehlt
    } else {
        $pos    = array_search($_GET['sub'],$catId);
        $title  = "Shop (<i>" . $catName[$pos] ."</i>)";
    }
// c) wenn Artikel gewaehlt
} else {
    $pos    = array_search($_GET['buy'],$artId);
    $title  = "Bestellung f&uuml;r <i>" . $artName[$pos] ."</i>";
}


// Titel ausgeben
echo "<h1>" . $title . "</h1>";
echo "<p>";

// wenn Stofftiere, dann Infos:
if ( !isset($_GET['sub']) ) {
    // chillout my friend and light a spliff :)
} elseif ( $_GET['sub'] == 1 ) {
    
    if (!isset($_GET['buy'])) {

        echo "Die lustigen Stofftiere kommen aus der Tilda Kollektion. Mit viel Liebe werden sie von Dora Gfeller hergestellt. ";
        echo "Bei den Giraffen, Elefanten und dem Hasen k&ouml;nnen Sie die Farben der Kleider auf Wunsch w&auml;hlen ";
        echo "(Im Bestellformular: Feld <i>Bemerkung</i>), das heisst in den rot, blau, gr&uuml;n, gelb, rosa, usw.. T&ouml;nen.</br></br>";

        echo "Lassen Sie sich &uuml;berraschen, jedes dieser Tiere ist ein Einzelst&uuml;ck zum verlieben.</br></br>";

    }

    echo "<b>Hinweis:</b> Lieferfrist ca. 1 bis 2 Wochen / Alle Preise exkl. Porto + Verpackung";

// Wenn Stofftiere
} elseif ( $_GET['sub'] > 1 ) {
    echo "<b>Hinweis:</b> Lieferfrist ca. 1 bis 2 Wochen / Alle Preise exkl. Porto + Verpackung";
}

// ---------------------------------------
// Inhalt:
// ---------------------------------------

// nichts gewaehlt
if ((!isset($_GET['buy'])) || (!in_array($_GET['buy'],$artId))) {
    if ((!isset($_GET['sub'])) || (!in_array($_GET['sub'],$catId))) {
        echo "Sch&ouml;n haben Sie den Weg in meinen Shop gefunden. Zurzeit kann ich Ihnen insgesamt " . $artCount . " Artikel in " . $catCount ." verschiedenen Kategorien anbieten und hoffe, dass das Richtige f&uuml;r Sie dabei ist.</br></br>";

    echo "<b>Hinweis:</b> Lieferfrist ca. 1 bis 2 Wochen / Alle Preise exkl. Porto + Verpackung";

        echo "<table width='100%'>";
            echo "<tr>";

            for ($i=0;$i<$catCount;$i++) {

                echo "<td class='sw_overview' valign='top'>";
                    echo "<a class='not_menu' href='?top=shop&sub=" . $catId[$i]  . "'>";
                        echo "<img src='" . $catImg[$i] . "' width='180px'/></br>";
                        echo "<b>" . $catName[$i] . "</b></br></br>";
                    echo "</a>";
                echo "</td>";

                if ((($i + 1) % 3) == 0) {
                    echo "</tr><tr>";
                }
            } 
            echo "</tr>";
        echo "</table>";

// Kategorie gewaehlt
    } else {
        // Artikel ausgeben
        printArtikel($_GET['sub'],"","","","shop");
    }

// Artikel gewaehlt
} else {
    include('buy.php');
} // eo which page

?>
