<? 

// get all valid ids (only actives)
$allIds = array();
$dbcon  = dbConnect(DBUSER,DBPW);
$sql    = "select id_sw from `stubenwagen` where active = 'yes'";
$result = $dbcon->query($sql);
dbClose($dbcon);
while ( $row = $result->fetch_assoc() ) {
    array_push($allIds,$row['id_sw']);
}

// #######################################
// ############## OVERVIEW ###############
// #######################################

if (!isset($_GET['sub'])) { 

	echo "<h1>Stubenwagen-Flotte</h1>";
    
    # alle groessen aus db
    $dbcon  = dbConnect(DBUSER,DBPW);
    $sql    = "select id_size, size from `size` order by shortcut desc";
    $result = $dbcon->query($sql);

    # define arrays
    $sizeId = array();
    $size   = array();
    $sizeCount =  $result->num_rows;

    # fill up arrays
    while($row = $result->fetch_assoc()){
        array_push($sizeId, $row['id_size']);
        array_push($size, $row['size']);
    }

    # alle wagen aus db
    $dbcon  = dbConnect(DBUSER,DBPW);
    $sql    = "select id_sw, name, mainphoto from `stubenwagen` where active = 'yes'";
    $result = $dbcon->query($sql);

    # get count
    $wagenCount =  $result->num_rows;
   
    # Fehlermeldung
	if ( $wagenCount == 0 ) { ?>

		<p>
		Es tut uns leid, aber zurzeit k&ouml;nnen wir Ihnen meine Stubenwagen-Flotte
		nicht pr&auml;sentieren.</br></br>
		Wir werden schon in K&uuml;rze wieder f&uuml;r Sie da sein!
		</p>

    <?
    # Ausgabe der Flotte
     } else { ?>
	
		<p>
		Meine Flotte umfasst zurzeit <?=$wagenCount?> Stubenwagen und ich bin sicher, 
		dass mindestens einer davon Ihren Geschmack treffen wird. Nicht alle Stubenwagen 
        k&ouml;nnen aufgrund der Gr&ouml;sse gleich lang benutzt werden.
		</p>

        <?php 
        $s = 0;
        for ($s=0;$s<$sizeCount;$s++) {

            # alle wagen aus db
            $dbcon  = dbConnect(DBUSER,DBPW);
            $sql    = "select id_sw, name, mainphoto, status, nextfree from `stubenwagen` where active = 'yes' and size_id=" . $sizeId[$s];
            $result = $dbcon->query($sql);

            # get count
            $wagenCount =  $result->num_rows;

            # define arrays
            $id     = array();
            $name   = array();
            $img    = array();
            $status = array();
            $nextfree = array();

            # fill up arrays
            while($row = $result->fetch_assoc()){
                array_push($id, $row['id_sw']);
                array_push($name, $row['name']);
                array_push($img, $row['mainphoto']);
                array_push($status, $row['status']);
                array_push($nextfree, $row['nextfree']);
            }

            // Print all wagen from this size
            if ( $wagenCount != 0 ) {
                echo "<h2>Geeignet f&uuml;r " . $size[$s] . "</h2>";
                echo "<table class='sw_overview' width='100%'>";
                    echo "<tr>";

                    for ($i=0;$i<$wagenCount;$i++) {

                        // generate link
                        $link   = !isset($_GET['menu']) ? "?top=wagen&sub=" . $id[$i]  . "'" : "?top=wagen&sub=" . $id[$i]  . "&menu=" . $_GET['menu'] . "'";
                        echo "<td class='sw_overview'>";
                            echo "<a class='not_menu' href='" . $link . "'>";
                                echo "<img src='" . $img[$i] . "' width='180px'/></br>";
                                echo $name[$i] . "</br>";

                                // Status
                                echo "<table align='center'>";
                                    echo "<tr>";

                                    // check if available
                                    $availability = calcAvailability($nextfree[$i]);
                                    $availability   = $status[$i] == "inMaintenance" ? "maint" : $availability; 

                                    // choose content
                                    if ( $availability == "now" ) {
                                        echo "<td><img src='img/goodtick.jpg' /></td>";
                                        echo "<td valign='center'><font color='green'>";
                                            echo "Sofort verf&uuml;gbar";
                                        echo "</font></td>";
                                    } elseif ( $availability == "maint" ) {
                                        echo "<td><img src='img/badtick.jpg' /></td>";
                                        echo "<td valign='center'><font color='red'>";
                                            echo "Wird gewartet";
                                        echo "</font></td>";
                                    } else {
                                        echo "<td><img src='img/badtick.jpg' /></td>";
                                        echo "<td valign='center'><font style='size: 10pt;' color='#FDD250'>";
                                            echo "Verf&uuml;gbar am " . $availability;
                                        echo "</font></td>";
                                    }
                                    echo "</tr>";
                                echo "</table>";
                                echo "</br>";
                            echo "</a>";
                        echo "</td>";

                        if ( $wagenCount == 1 ) {
                            echo "<td></td><td></td>";
                        } elseif ( $wagenCount == 2 ) {
                            echo "<td></td>";
                        }
            
                        if ((($i + 1) % 3) == 0) {
                            echo "</tr><tr>";
                        } // end of close table

                    } // end of printing wagen of this size

                    echo "</tr>";
                echo "</table>";

                // clear arrays
                unset($id);
                unset($name);
                unset($img);

            } // eoi wagen vorhanden

        } // end of for each size
		
    } // end of if wagen 



// #######################################
// ############### DETAIL ################
// #######################################

} else { 

    // check if id is valid
	if ((!isset($_GET['sub'])) || (!in_array($_GET['sub'],$allIds))) {
        echo "Fehler aufgetreten - Sorry!";
    } else {
	
        // get all infos to the wagen
        $result = getWInfos($_GET['sub']);
        $row    = $result->fetch_assoc();

        // check which view
        $view           = !isset($_GET['view']) ? "gallery" : $_GET['view'];
        $galleryStyle   = $view == "gallery" ? "Active" : "";
        $infoStyle      = $view == "info" ? "Active" : "";
        $reservStyle    = $view == "reserv" ? "Active" : "";

        // Title + Short Description
        // Status
        echo "<table align='left'>";
           echo "<tr>";
                echo "<td>";
                        echo "<h1>Stubenwagen <i>" . $row['name'] . "</i> (</h1>";
                echo "</td>";

                // check if available
                $availability = calcAvailability($row['nextfree']);
                $availability   = $row['status'] == "inMaintenance" ? "maint" : $availability; 

                // choose content
                if ( $availability == "now" ) {
                    echo "<td><img src='img/goodtick.jpg' /></td>";
                    echo "<td valign='center'><font color='green'>";
                        echo "Sofort verf&uuml;gbar";
                    echo "</font></td>";
                } elseif ( $availability == "maint" ) {
                    echo "<td><img src='img/badtick.jpg' /></td>";
                    echo "<td valign='center'><font color='red'>";
                        echo "Wird gewartet";
                    echo "</font></td>";
                } else {
                    echo "<td><img src='img/badtick.jpg' /></td>";
                    echo "<td valign='center'><font style='size: 10pt;' color='#FDD250'>";
                        echo "Verf&uuml;gbar am " . $availability;
                    echo "</font></td>";
                }


/*
            // choose content
            if ( $row['nextfree'] == "now" ) {
                echo "<td><img src='img/goodtick.jpg' /></td>";
                echo "<td valign='center'><font color='green'>";
                    echo "Sofort verf&uuml;gbar";
                echo "</font></td>";
            } else {
                echo "<td><img src='img/badtick.jpg' /></td>";
                echo "<td valign='center'><font style='size: 10pt;' color='#FDD250'>";
                    echo "Verf&uuml;gbar am " . $row['nextfree'];
                echo "</font></td>";
            }
*/
              echo "<td><h1>)</h1></td>";
            echo "</tr>";
        echo "</table>";


        // Inserting Gallery
        if ($view != "reserv") {  

            // print gallery if exists
            if (file_exists('gallery/' . $row['id_sw'] . '/index.html')) {
                echo "<object data='gallery/" . $row['id_sw'] . "/index.html' width='100%' height='400px'></object>";
            } else {
                echo "<i>Leider ist die Bildergalerie dieses Stubenwagen zurzeit nicht verf&uuml;gbar - Sorry!</i>";
            }

            echo "</br></br>";

            // Action-Button
            if ( $row['status'] != "inMaintenance" ) {
                actionButton("right","?top=wagen&sub=" . $_GET['sub'] . "&view=reserv","Jetzt reservieren!");
            }
            // Action-Button
            $link = !isset($_GET['menu']) ? "?top=wagen" : "?top=wagen&menu=" . $_GET['menu'];
            actionButton("left",$link,"Zur&uuml;ck zur Flotte!");
            echo "</br></br>";
            echo "</br></br>";

            // Printing Infos
            printWInfos($row,"");


        } elseif ($view == "reserv") {

            echo "</br></br></br>";
            include('reserv.php');

            echo "</br></br>";

            // Action-Button
            $link = !isset($_GET['menu']) ? "?top=wagen" : "?top=wagen&menu=" . $_GET['menu'];
            actionButton("left",$link,"Zur&uuml;ck zur Flotte!");

        } // end of showing content

    } // eoi id is valid

} // end of if detail view?>
