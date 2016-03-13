<?php

// Global
$statusType = "";
$statusMsg  = "";
$statusQuery= "";
$error      = "none";

// building link
$link       = "top=inventar&sub=artikel";
$link      .= !isset($_GET['cat_id']) ? "" : "&cat_id=" . $_GET['cat_id'];
$link      .= !isset($_GET['active']) ? "" : "&active=" . $_GET['active'];
$link      .= !isset($_GET['inv']) ? "" : "&inv=" . $_GET['inv'];

echo "<h2>Artikel-Verwaltung</h2>";

######
#
# EDIT ARTIKEL
#
######


######
#
# DELETE 2nd PIC
#
######

if ( !isset($_GET['deletePic2'])) {
    // relax and light a spliff :)

} elseif (( $_GET['deletePic2'] > 0 ) && ( $_GET['deletePic2'] < 99999 )) {

    // get artikel infos
    $dbcon  = dbConnect(DBUSER,DBPW);
    $sql    = "select * from artikel where photo2 != '' and id_art=" . $_GET['deletePic2'];
    $result = $dbcon->query($sql);
    dbClose($dbcon);
    $row    = $result->fetch_assoc();

    // check if artikel exist
    if ( $result->num_rows != 0 ) {

        // get Values
        $nameValue  = $row['name'];

        // save id
        $id = $_GET['deletePic2'];

        // delete pic
        unlink("../img/shop/" . $id . "_2.jpg");

        // update db
        $sql    = "update artikel set photo2='' where id_art=" . $id;
        $dbcon  = dbConnect(DBUSER,DBPW);
        $dbcon->query($sql);
        dbClose($dbcon);

        // Status
        $statusType = "succeed";
        $statusMsg  = "Das 2. Foto des Artikel <i>" . $nameValue . "</i> wurde erfolgreich gel&ouml;scht!.</br>";
        $statusMsg .= "(Du siehst den bearbeiteten Artikel direkt unter dieser Meldung)";
        $statusQuery= "select * from artikel where id_art=" . $id;

    } // eo if artikel exist and has 2. pic

} // eo delete 2. pic


######
#
# EDIT 2nd PIC
#
######

if ( !isset($_GET['pic2'])) {
    // relax and light a spliff :)

} elseif (( $_GET['pic2'] > 0 ) && ( $_GET['pic2'] < 99999 )) {

    // get artikel infos
    $dbcon  = dbConnect(DBUSER,DBPW);
    $sql    = "select * from artikel where id_art=" . $_GET['pic2'];
    $result = $dbcon->query($sql);
    dbClose($dbcon);
    $row    = $result->fetch_assoc();

    // check if artikel exist
    if ( $result->num_rows != 0 ) {

        // save id
        $id = $_GET['pic2'];

        // set values
        $nameValue      = $row['name'];
        $imgValue       = $row['photo'];
        $img2Value      = $row['photo2'];

        // Titel
        echo "<b>2. Foto bearbeiten</b></br></br>";

        // SAVE!!!
        if ( !isset($_GET['action']) ) {
            // chill and light a spliff
        } elseif ( $_GET['action'] == "save2pic" ) {

            // Status
            $statusType = "failed";
            $statusMsg  = "Die Bearbeitung des 2. Fotos des Artikel <i>" . $row['name'] . "</i> hat nicht funktioniert! Versuche es erneut.</br>";
            $statusMsg .= "Fehlermeldung: ";
            $error      = "none";


            // check imagewenn kein Fehler
            if ($_FILES['userfile']['name'] != "")  {
                // check image
                $uploaddir  = '/home/ironsmit/public_html/schlosser-stubenwagen.ch/www/img/shop/tmp/';
                $photodir   = '/home/ironsmit/public_html/schlosser-stubenwagen.ch/www/img/shop/';
                $message    = checkImageUpload($uploaddir);
                $error      = $message != "okay" ? "occured" : "none";
                $errorMsg   = $message != "okay" ? "Bild-Upload fehlgeschlagen!" : "none";
            }

            if ( $error == "none" ) {

                // Database
                $sql        = "update artikel set photo2='img/shop/" . $id . "_2.jpg' where id_art=$id";
                $dbcon      = dbConnect(DBUSER,DBPW);
                $dbcon->query($sql);
                dbClose($dbcon);

                // move and rename file
                if ($_FILES['userfile']['name'] != "") {
                    rename($uploaddir . "/" . $_FILES['userfile']['name'],$photodir . "/" . $_POST['id_art'] . "_2.jpg");
                }

                // Status
                $statusType = "succeed";
                $statusMsg  = "Das 2. Foto des Artikel <i>" . $nameValue . "</i> wurde erfolgreich bearbeitet.</br>";
                $statusMsg .= "(Du siehst den bearbeiteten Artikel direkt unter dieser Meldung)";
                $statusQuery= "select * from artikel where id_art=" . $_POST['id_art'];

            } // eoi insertion completed

            // if error
            else {
                $statusMsg  .= $errorMsg;
            }
        } // eoi saving

        ######
        #
        #    STATUS
        #
        ######

        // Status
        if ( $statusType != "" ) {
            printStatus($statusType,$statusMsg);
            echo "</br>";
        }

        if ( $statusQuery != "") {
            $dbcon  = dbConnect(DBUSER,DBPW);
            $result = $dbcon->query($statusQuery);
            dbClose($dbcon);
            printArtikel("","","",$result,"admin");
        }

        if ( (!isset($_GET['action']) ) || ( $error == 'occured' )) {

            // Formular ausgeben
            echo "<form enctype='multipart/form-data' method='post' name='editArt' id='editArt' action='?" . $link . "&pic2=" . $id . "&action=save2pic'>";
                echo "<table><tr>";
                    // photo
                    echo "<td width='190px' valign='top' rowspan='4'>";
                        echo "<img width='170px' src='../" . $imgValue . "' />";
                        if ( $img2Value != "" ) {
                            echo "<img height='150px' src='../" . $img2Value . "' />";
                        }
                        echo "</td>";
                    echo "<td height='20px'>Name</td>";
                    echo "<td>$nameValue</td>";
                echo "</tr><tr>";
                    echo "<input type='hidden' name='MAX_FILE_SIZE' value='4194304' />";
                    echo "<td height='20px'>2. Foto</td>";
                    echo "<td height='20px'><input name='userfile' type='file' /></td>";
                echo "</tr><tr>";
                     echo "<td valign='top' height='20px' colspan='2'><input type='submit' value='Artikel speichern' /></td>";
                echo "</tr><tr>";
                     echo "<td valign='top' colspan='2'></br><a class='not_menu' href='?" . $link ."'>Vorgang abbrechen</a></td>";
                echo "</tr></table>";
                echo "<input type='hidden' id='id_art' name='id_art' size'40' value='" . $id . "' />";
            echo "</form>";
            echo "</br></br>";

        } // eoi formu

    } // eoi artikel exist

} // eoi editing 2nd photo


######
#
# EDIT WHOLE ARTIKEL
#
######


if ( !isset($_GET['edit'])) {
    // relax and light a spliff :)

} elseif (( $_GET['edit'] > 0 ) && ( $_GET['edit'] < 99999 )) {

    // get artikel infos
    $dbcon  = dbConnect(DBUSER,DBPW);
    $sql    = "select * from artikel where id_art=" . $_GET['edit'];
    $result = $dbcon->query($sql);
    dbClose($dbcon);
    $row    = $result->fetch_assoc();

    // check if artikel exist
    if ( $result->num_rows != 0 ) {

/*
echo "<pre>"; print_r($row); echo "</pre>";
echo "<pre>"; print_r($_POST); echo "</pre>";
echo "<pre>"; print_r($_FILES); echo "</pre>";
*/

        // save id
        $id = $_GET['edit'];

        // set values
        $nameValue      = $row['name'];
        $descValue      = $row['description'];
        $imgValue       = $row['photo'];
        $img2Value      = $row['photo2'];
        $priceValue     = $row['price'];
        $portoValue     = $row['porto'];
        $activeValue    = $row['active'] == "yes" ? "checked" : "";
        $invValue       = $row['inventory'];

        // catValue
        $result     = getCategory($row['cat_id']);
        if ( $row['cat_id'] == 0 ) {
            $catIdValue = "";
            $catValue   = "Bitte ausw&auml;hlen...";
        } else {
            $rowCat     = $result->fetch_assoc();
            $catValue   = $rowCat['category'];
            $catIdValue = $rowCat['id_cat'];
        }

        // sizeValue
        if ($row['cat_id'] == 4) {
            $sizeBValue      = $row['size'];
            $sizeTSValue     = "0";
        } elseif ( ($row['cat_id'] == 3) || ( $row['cat_id'] == 1) ) {
            $sizeTSValue     = $row['size'];
            $sizeBValue      = "0";
        } else {
            $sizeTSValue     = "0";
            $sizeBValue      = "0";
        }

        // Titel
        echo "<b>Artikel bearbeiten</b></br></br>";

        // SAVE!!!
        if ( !isset($_GET['action']) ) {
            // chill and light a spliff
        } elseif ( $_GET['action'] == "saveEdit" ) {

            // Status
            $statusType = "failed";
            $statusMsg  = "Die Bearbeitung des Artikel <i>" . $_POST['artName'] . "</i> hat nicht funktioniert! Versuche es erneut.</br>";
            $statusMsg .= "Fehlermeldung: ";
            $error      = "none";


    //        echo "<pre>";print_r($_POST); echo "</pre>";

            // check error
            // Name is string?
            if ((strpbrk($_POST['artName'], '\'";<>')) || (!is_string($_POST['artName']))) {
                $error      = "occured";
                $errorMsg   = "Name enth&auml;t unerlaubte Zeichen!";
            } else {
                // Kategorie ausgewaehlt?
                if ( $_POST['artCat'] == 0 ) {
                    $resultCat  = getCategory($_POST['artCat']);
                    if ( $resultCat->num_rows == 0 ) {
                        $error      = "occured";
                        $errorMsg   = "Keine Kategorie ausgew&auml;hlt!";
                    }
                } else {
                    // Preis ist eine Zahl?
                    if ((!is_numeric($_POST['price'])) || ( $_POST['price'] == 0 )) {
                        $error      = "occured";
                        $errorMsg   = "Preis ist keine Zahl oder 0!";
                    } else {
                        // Porto ist eine Zahl?
                        if ((!is_numeric($_POST['porto'])) || ( $_POST['porto'] == 0 )) {
                            $error      = "occured";
                            $errorMsg   = "Porto ist keine Zahl oder 0!";
                        } else {
                            // Anzahl ist eine Zahl?
                            if (!is_numeric($_POST['inv'])) {
                                $error      = "occured";
                                $errorMsg   = "Anzahl ist keine Zahl!";
                            } else {
                                // Check size if bodies
                                if (( $_POST['artCat'] == 4 ) && ( $_POST['sizeB'] == "0" )) {
                                    $error      = "occured";
                                    $errorMsg   = "Keine Gr&ouml;sse des Bodies ausgew&auml;hlt!";
                                } else {
                                    // Check size if toy or animal
                                    if ((( $_POST['artCat'] == 1 ) || ( $_POST['artCat'] == 3)) && ( $_POST['sizeTS'] == 0 )) {
                                        $error      = "occured";
                                        $errorMsg   = "Keine Gr&ouml;sse des Tiers/Spielzeug ausgew&auml;hlt!";
                                    } else {
                                        // Name is string?
                                        if ((strpbrk($_POST['desc'], '\'";<>')) || (!is_string($_POST['desc']))) {
                                            $error      = "occured";
                                            $errorMsg   = "Beschreibung enth&auml;t unerlaubte Zeichen!";
                                        } // eo check image
                                    } // eo check toy size
                                } // eo check toy size
                            } // eo check body size
                        } // eo check inventory
                    } // eo check porto
                } // eo check price
            } // eo check error

    //echo "<pre>" . $error . "/" . $errorMsg . "</pre>";

            // check imagewenn kein Fehler
            if ( ($error == "none") && ($_FILES['userfile']['name'] != "") ) {
                // check image
                $uploaddir  = '/home/ironsmit/public_html/schlosser-stubenwagen.ch/www/img/shop/tmp/';
                $photodir   = '/home/ironsmit/public_html/schlosser-stubenwagen.ch/www/img/shop/';
                $message    = checkImageUpload($uploaddir);
                $error      = $message != "okay" ? "occured" : "none";
                $errorMsg   = $message != "okay" ? "Bild-Upload fehlgeschlagen!" : "none";
            }

            // wenn kein Fehler
            if ( $error == "none") {

                // check which size
                if ( $_POST['artCat'] == 4 ) {
                    $size   = $_POST['sizeB'];
                } elseif (( $_POST['artCat'] == 1 ) || ( $_POST['artCat'] == 3 ) ) {
                    $size   = $_POST['sizeTS'];
                } else {
                    $size = "";
                }

                // check active
                if ( !isset($_POST['active']) ) {
                    $active = "no";
                } elseif ( $_POST['active'] == "yes") {
                    $active = "yes";
                }

                // check active
                if (isset($active)) {

                    // Database
                    $sql        = "update artikel set active ='" . $active . "', cat_id=" . $_POST['artCat'] . ", name='" . $_POST['artName'] . "',";
                    $sql       .= "description='" . $_POST['desc'] . "', price ='" . $_POST['price'] . "', porto='" . $_POST['porto'] . "',";
                    $sql       .= "size='" . $size . "', inventory='" . $_POST['inv'] . "' where id_art=$id";
                    $dbcon      = dbConnect(DBUSER,DBPW);
                    $dbcon->query($sql);
                    dbClose($dbcon);

                    // move and rename file
                    if ($_FILES['userfile']['name'] != "") {
                        rename($uploaddir . "/" . $_FILES['userfile']['name'],$photodir . "/" . $_POST['id_art'] . ".jpg");
                    }

                    // Status
                    $statusType = "succeed";
                    $statusMsg  = "Der Artikel <i>" . $_POST['artName'] . "</i> wurde erfolgreich bearbeitet.</br>";
                    $statusMsg .= "(Du siehst den bearbeiteten Artikel direkt unter dieser Meldung)";
                    $statusQuery= "select * from artikel where id_art=" . $_POST['id_art'];

                } // eoi active checked
            } // eoi insertion completed

            // if error
            else {
                $statusMsg  .= $errorMsg;
            }
        }// eoi saving


        ######
        #     
        #    STATUS
        #     
        ######

        // Status
        if ( $statusType != "" ) {
            printStatus($statusType,$statusMsg);
            echo "</br>";
        }

        if ( $statusQuery != "") {
            $dbcon  = dbConnect(DBUSER,DBPW);
            $result = $dbcon->query($statusQuery);
            dbClose($dbcon);
            printArtikel("","","",$result,"admin");
        }

    //    if (( $_GET['action'] == 'newArt' ) || ( $error == 'occured' )) {
        if ( (!isset($_GET['action']) ) || ( $error == 'occured' )) {

            // Formular ausgeben
            echo "<form enctype='multipart/form-data' method='post' name='editArt' id='editArt' action='?" . $link . "&edit=" . $id . "&action=saveEdit'>";
                echo "<table><tr>";
                    // photo
                    echo "<td width='190px' valign='top' rowspan='12'>";
                        echo "<img width='170px' src='../" . $imgValue . "' />";
                        if ( $img2Value != "" ) {
                            echo "<img width='170px' src='../" . $img2Value . "' />";
                        }
                        echo "</td>";
                    echo "<td>Name</td>";
                    echo "<td><input type='text' id='artName' name='artName' size'40' value='" . $nameValue . "' required /></td>";
                echo "</tr><tr>";
                    echo "<td>Kategorie</td>";
                    echo "<td><select name='artCat' id='artCat' required>";
                        // get categories
                        $resultCat  = getCategory("");
                        echo "<option value='" . $catIdValue . "'>" . $catValue . "</option>";
                        while ( $rowCat = $resultCat->fetch_assoc() ) {
                            if ( $catIdValue != $rowCat['id_cat'] ) {
                                echo "<option value='" . $rowCat['id_cat'] . "'>" . $rowCat['category'] . "</option>";
                            }
                        }
                    echo "</select></td>";
                echo "</tr><tr>";
                    echo "<td>Beschreibung</td>";
                    echo "<td><input type='text' name='desc' id='desc' value='" . $descValue . "' /></td>";
                echo "</tr><tr>";
                    echo "<td>Aktivieren</td>";
                    echo "<td><input type='checkbox' name='active' value='yes' " . $activeValue . " /></td>";
                echo "</tr><tr>";
                    echo "<td>Gr&ouml;sse (Tiere/Spielzeug)</td>";
                    echo "<td><input type='text' name='sizeTS' id='sizeTS' value='" . $sizeTSValue . "'/> (Angabe in cm oder m)</td>";
                echo "</tr><tr>";
                    echo "<td>Gr&ouml;sse (Bodies)</td>";
                    echo "<td><select name='sizeB' id='sizeB' required />";
                        if ( $sizeBValue == "0" ) {
                            echo "<option value='0'>Bitte ausw&auml;hlen...</option>";
                        } else {
                            echo "<option value='" . $sizeBValue . "'>" . $sizeBValue . "</option>";
                        }
                        echo "<option value='Langarm Gr. 50'>Langarm Gr. 50</option>";
                        echo "<option value='Langarm Gr. 56'>Langarm Gr. 56</option>";
                        echo "<option value='Langarm Gr. 62'>Langarm Gr. 62</option>";
                        echo "<option value='Langarm Gr. 68'>Langarm Gr. 68</option>";
                        echo "<option value='Langarm Gr. 74'>Langarm Gr. 74</option>";
                        echo "<option value='Langarm Gr. 80'>Langarm Gr. 80</option>";
                        echo "<option value='Kurzarm Gr. 50/56'>Kurzarm Gr. 50/56</option>";
                        echo "<option value='Kurzarm Gr. 62/68'>Kurzarm Gr. 62/68</option>";
                        echo "<option value='Kurzarm Gr. 74/80'>Kurzarm Gr. 74/80</option>";
                    echo "</select></td>";
                echo "</tr><tr>";
                    echo "<td>Preis</td>";
                    echo "<td><input type='number' id='price' name='price' min='0' step='1' value='" . $priceValue . "' required /> CHF</td>";
                echo "</tr><tr>";
                    echo "<td>Porto</td>";
                    echo "<td><input type='number' id='porto' name='porto' min='0' step='1' value='" . $portoValue . "' required /> CHF</td>";
                echo "</tr><tr>";
                    echo "<td>Anzahl</td>";
                    echo "<td><input type='number' id='inv' name='inv' min='0' step='1' value='" . $invValue . "' required /></td>";
                echo "</tr><tr>";
                    echo "<td>Photo</td>";
                    echo "<input type='hidden' name='MAX_FILE_SIZE' value='4194304' />";
                    echo "<td><input name='userfile' type='file' /></td>";
                echo "</tr><tr>";
                     echo "<td><input type='submit' value='Artikel speichern' /></td>";
                echo "</tr><tr>";
                     echo "<td valign='top' colspan='2'></br><a class='not_menu' href='?" . $link ."'>Vorgang abbrechen</a></td>";
                echo "</tr></table>";
                echo "<input type='hidden' id='id_art' name='id_art' size'40' value='" . $id . "' />";
            echo "</form>";
            echo "</br></br>";
        } // eoi formular

    } // eoi artikel exist

} // eoi action=edit


######
#
# NEW ARTIKEL
#
######

if ( !isset($_GET['action'])) {
    // relax and light a spliff :)

} elseif (( $_GET['action'] == 'newArt' ) || ( $_GET['action'] == "saveNewArt" )) {

    // Titel
    echo "<b>Neuer Artikel</b></br></br>";

    // SAVE!!!
    if ( $_GET['action'] == "saveNewArt" ) {

        // Status
        $statusType = "failed";
        $statusMsg  = "Die Erstellung des Artikel <i>" . $_POST['artName'] . "</i> hat nicht funktioniert! Versuche es erneut.</br>";
        $statusMsg .= "Fehlermeldung: ";


//        echo "<pre>";print_r($_POST); echo "</pre>";

        // check error
        // Name is string?
        if ((strpbrk($_POST['artName'], '\'";<>')) || (!is_string($_POST['artName']))) {
            $error      = "occured";
            $errorMsg   = "Name enth&auml;t unerlaubte Zeichen!";
        } else {
            // Kategorie ausgewaehlt?
            if ( $_POST['artCat'] == 0 ) {
                $resultCat  = getCategory($_POST['artCat']);
                if ( $resultCat->num_rows == 0 ) {
                    $error      = "occured";
                    $errorMsg   = "Keine Kategorie ausgew&auml;hlt!";
                }
            } else {
                // Preis ist eine Zahl?
                if ((!is_numeric($_POST['price'])) || ( $_POST['price'] == 0 )) {
                    $error      = "occured";
                    $errorMsg   = "Preis ist keine Zahl oder 0!";
                } else {
                    // Porto ist eine Zahl?
                    if ((!is_numeric($_POST['porto'])) || ( $_POST['porto'] == 0 )) {
                        $error      = "occured";
                        $errorMsg   = "Porto ist keine Zahl oder 0!";
                    } else {
                        // Anzahl ist eine Zahl?
                        if (!is_numeric($_POST['inv'])) {
                            $error      = "occured";
                            $errorMsg   = "Anzahl ist keine Zahl!";
                        } else {
                            // Check size if bodies
                            if (( $_POST['artCat'] == 4 ) && ( $_POST['sizeB'] == "0" )) {
                                $error      = "occured";
                                $errorMsg   = "Keine Gr&ouml;sse des Bodies ausgew&auml;hlt!";
                            } else {
                                // Check size if toy or animal
                                if ((( $_POST['artCat'] == 1 ) || ( $_POST['artCat'] == 3)) && ( $_POST['sizeTS'] == 0 )) {
                                    $error      = "occured";
                                    $errorMsg   = "Keine Gr&ouml;sse des Tiers/Spielzeug ausgew&auml;hlt!";
                                } else {
                                    // Name is string?
                                    if ((strpbrk($_POST['desc'], '\'";<>')) || (!is_string($_POST['desc']))) {
                                        $error      = "occured";
                                        $errorMsg   = "Beschreibung enth&auml;t unerlaubte Zeichen!";
                                     } else {
                                        // check image
                                        $uploaddir  = '/home/ironsmit/public_html/schlosser-stubenwagen.ch/www/img/shop/tmp/';
                                        $photodir   = '/home/ironsmit/public_html/schlosser-stubenwagen.ch/www/img/shop/';
                                        $message    = checkImageUpload($uploaddir);
                                        $error      = $message != "okay" ? "occured" : "none";
                                        $errorMsg   = $message != "okay" ? "Bild-Upload fehlgeschlagen!" : "none";
                                        } // eo check image
                                    } // eo check image
                                } // eo check image
                            } // eo check toy size
                        } // eo check body size
                    } // eo check inventory
                } // eo check porto
            } // eo check price
//        } // eo check error

//echo "<pre>" . $error . "/" . $errorMsg . "</pre>";

        // wenn kein Fehler
        if ( $error == "none") {

            // check which size
            if ( $_POST['artCat'] == 4 ) {
                $size   = $_POST['sizeB'];
            } elseif (( $_POST['artCat'] == 1 ) || ( $_POST['artCat'] == 3 ) ) {
                $size   = $_POST['sizeTS'];
            } else {
                $size = "";
            }

            // check active
            if ( !isset($_POST['active']) ) {
                $active = "no";
            } elseif ( $_POST['active'] == "yes") {
                $active = "yes";
            }

            // check active
            if (isset($active)) {

                // Database
                $sql        = "insert into artikel values (" . $_POST['id_art'] . ",'" . $active . "'," . $_POST['artCat'] . ",'" . $_POST['artName'] . "',";
                $sql       .= "'" . $_POST['desc'] . "','" . $_POST['price'] . "','" . $_POST['porto'] . "','img/shop/" . $_POST['id_art'] . ".jpg','','" . $size . "','" . $_POST['inv'] . "')";
                $dbcon      = dbConnect(DBUSER,DBPW);
                $dbcon->query($sql);
                dbClose($dbcon);

                // move and rename file
                rename($uploaddir . "/" . $_FILES['userfile']['name'],$photodir . "/" . $_POST['id_art'] . ".jpg");

                // Status
                $statusType = "succeed";
                $statusMsg  = "Der Artikel <i>" . $_POST['artName'] . "</i> wurde erfolgreich erstellt.</br>";
                $statusMsg .= "(Du siehst den neu erstellten Artikel direkt unter dieser Meldung)";
                $statusQuery= "select * from artikel where id_art=" . $_POST['id_art'];

            } // eoi active checked
        } // eoi insertion completed

        // if error
        else {
            $statusMsg  .= $errorMsg;
        }
    }// eoi saving


    ######
    #     
    #    STATUS
    #     
    ######

    // Status
    if ( $statusType != "" ) {
        printStatus($statusType,$statusMsg);
        echo "</br>";
    }

    if ( $statusQuery != "") {
        $dbcon  = dbConnect(DBUSER,DBPW);
        $result = $dbcon->query($statusQuery);
        dbClose($dbcon);
        printArtikel("","","",$result,"admin");
    }

    if (( $_GET['action'] == 'newArt' ) || ( $error == 'occured' )) {

        // get next id
        $dbcon  = dbConnect(DBUSER,DBPW);
        $sql    = "select id_art from artikel order by id_art desc limit 1";
        $result = $dbcon->query($sql);
        dbClose($dbcon);
        $row    = $result->fetch_assoc();
        $next_id= $row['id_art'] + 1;

        // Formular ausgeben
        echo "<form enctype='multipart/form-data' method='post' name='newArt' id='newArt' action='?" . $link . "&action=saveNewArt'>";
            echo "<table><tr>";
                echo "<td>Name</td>";
                echo "<td><input type='text' id='artName' name='artName' size'40' required /></td>";
            echo "</tr><tr>";
                echo "<td>Kategorie</td>";
                echo "<td><select name='artCat' id='artCat' required>";
                    // get categories
                    $resultCat  = getCategory("");
                    echo "<option value='0'>Bitte ausw&auml;hlen...</option>";
                    while ( $rowCat = $resultCat->fetch_assoc() ) {
                        echo "<option value='" . $rowCat['id_cat'] . "'>" . $rowCat['category'] . "</option>";
                    }
                echo "</select></td>";
            echo "</tr><tr>";
                echo "<td>Beschreibung</td>";
                echo "<td><input type='text' name='desc' id='desc' /></td>";
            echo "</tr><tr>";
                echo "<td>Aktivieren</td>";
                echo "<td><input type='checkbox' name='active' value='yes' /></td>";
            echo "</tr><tr>";
                echo "<td>Gr&ouml;sse (Tiere/Spielzeug)</td>";
                echo "<td><input type='text' name='sizeTS' id='sizeTS' value='0'/>(Angabe in cm oder m)</td>";
            echo "</tr><tr>";
                echo "<td>Gr&ouml;sse (Bodies)</td>";
                echo "<td><select name='sizeB' id='sizeB' required />";
                    echo "<option value='0'>Bitte ausw&auml;hlen...</option>";
                    echo "<option value='Langarm Gr. 50'>Langarm Gr. 50</option>";
                    echo "<option value='Langarm Gr. 56'>Langarm Gr. 56</option>";
                    echo "<option value='Langarm Gr. 62'>Langarm Gr. 62</option>";
                    echo "<option value='Langarm Gr. 68'>Langarm Gr. 68</option>";
                    echo "<option value='Langarm Gr. 74'>Langarm Gr. 74</option>";
                    echo "<option value='Langarm Gr. 80'>Langarm Gr. 80</option>";
                    echo "<option value='Kurzarm Gr. 50/56'>Kurzarm Gr. 50/56</option>";
                    echo "<option value='Kurzarm Gr. 62/68'>Kurzarm Gr. 62/68</option>";
                    echo "<option value='Kurzarm Gr. 74/80'>Kurzarm Gr. 74/80</option>";
                echo "</select></td>";
            echo "</tr><tr>";
                echo "<td>Preis</td>";
                echo "<td><input type='number' id='price' name='price' min='0' step='1' value='0' required /> CHF</td>";
            echo "</tr><tr>";
                echo "<td>Porto</td>";
                echo "<td><input type='number' id='porto' name='porto' min='0' step='1' value='0' required /> CHF</td>";
            echo "</tr><tr>";
                echo "<td>Anzahl</td>";
                echo "<td><input type='number' id='inv' name='inv' min='0' step='1' value='0' required /></td>";
            echo "</tr><tr>";
                echo "<td>Photo</td>";
                echo "<input type='hidden' name='MAX_FILE_SIZE' value='4194304' />";
                echo "<td><input name='userfile' type='file' required/></td>";
            echo "</tr><tr>";
                 echo "<td><input type='submit' value='Artikel erstellen' /></td>";
            echo "</tr><tr>";
                 echo "<td valign='top' colspan='2'></br><a class='not_menu' href='?" . $link ."'>Vorgang abbrechen</a></td>";
            echo "</tr></table>";
            echo "<input type='hidden' id='id_art' name='id_art' size'40' value='" . $next_id . "' />";
        echo "</form>";
        echo "</br></br>";
    } // eoi formular

} // eoi action=New Art


##############################
#                            #
#      ARTIKEL ANZEIGEN      #
#                            #
##############################


######
#    
#   ARTIKEL LOESCHEN
#    
######

if ( !isset($_GET['delete'])) {
    // chill and light a spliff :)
} else {

    // check id
    if (( $_GET['delete'] > 0) && ( $_GET['delete'] < 99999 ) ) {
        $id = $_GET['delete'];

            // get artikel-name
            $result     = getArtikel("",$id,"");
            
            // check if artikel exist
            if ( $result->num_rows != 0 ) {
            $row        = $result->fetch_assoc();

            // delete picture
            unlink('../' . $row['photo']);
            if ( $row['photo2'] != '' ) {
                unlink('../' . $row['photo2']);
            }

            // delete database-entry
            $sql        = "delete from artikel where id_art = $id";
            $dbcon      = dbConnect(DBUSER,DBPW);
            $dbcon->query($sql);
            dbClose($dbcon);

            // Status
            $statusType = "succeed";
            $statusMsg  = "Der Artikel <i>" . $row['name'] . "</i> wurde erfolgreich gel&ouml;scht!";

        } // eoi artikel exist

    } // eoi check id

} // eo delete


######
#    
#   ARTIKEL DEAKTIVIEREN
#    
######

if ( !isset($_GET['deactivate'])) {
    // chill and light a spliff :)
} else {

    // check id
    if (( $_GET['deactivate'] > 0) && ( $_GET['deactivate'] < 99999 ) ) {
        $id = $_GET['deactivate'];

        // info
        $sql    = "select * from artikel where active='yes' and id_art=$id";
        $dbcon  = dbConnect(DBUSER,DBPW);
        $result = $dbcon->query($sql);
        dbClose($dbcon);
        $row    = $result->fetch_assoc();

        if ( $result->num_rows != 0 ) {
            $row        = $result->fetch_assoc();

            // update database-entry
            $sql        = "update artikel set active='no' where id_art = $id";
            $dbcon      = dbConnect(DBUSER,DBPW);
            $dbcon->query($sql);
            dbClose($dbcon);

            // Status
            $statusType = "succeed";
            $statusMsg  = "Der Artikel <i>" . $row['name'] . "</i> wurde erfolgreich deaktiviert!</br>(Du siehst den Artikel direkt unter dieser Anzeige)";
            $statusQuery= "select * from artikel where id_art=$id";

        } // eoi artikel exist

    } // eoi check id

} // eo deactivate

######
#
#   ARTIKEL AKTIVIEREN
#
######

if ( !isset($_GET['activate'])) {
    // chill and light a spliff :)
} else {

    // check id
    if (( $_GET['activate'] > 0) && ( $_GET['activate'] < 99999 ) ) {
        $id = $_GET['activate'];

        // info
        $sql    = "select * from artikel where active='no' and id_art=$id";
        $dbcon  = dbConnect(DBUSER,DBPW);
        $result = $dbcon->query($sql);
        dbClose($dbcon);

        if ( $result->num_rows != 0 ) {
            $row        = $result->fetch_assoc();

            // update database-entry
            $sql        = "update artikel set active='yes' where id_art = $id";
            $dbcon      = dbConnect(DBUSER,DBPW);
            $dbcon->query($sql);
            dbClose($dbcon);

            // Status
            $statusType = "succeed";
            $statusMsg  = "Der Artikel <i>" . $row['name'] . "</i> wurde erfolgreich aktiviert!</br>(Du siehst den Artikel direkt unter dieser Anzeige)";
            $statusQuery= "select * from artikel where id_art=$id";

        } // eoi artikel exist

    } // eoi check id

} // eo activate

#####
#
# CONDITION ERARBEITEN
#
#####

// values fuer formular
$catValue       = "";
$catValueText   = "Alle anzeigen";
$actValue       = "";
$actValueText   = "alle anzeigen";
$invValue       = "";

// condition erarbeiten
$condition          = "";
$condition_cat      = "";
$condition_active   = "";
$condition_inv      = "";

// condition_cat
if (!isset($_GET['cat_id'])) {
    // do nothing and light a spliff :)
} elseif ( $_GET['cat_id'] != "" ) {
    $condition_cat  = "cat_id = " . $_GET['cat_id'];
    $catValue       = $_GET['cat_id'];
    if ( $catValue == "0" ) {
        $catValueText   = "ohne Kategorie";
    } else {
        $result         = getCategory($catValue);
        $row            = $result->fetch_assoc();
        $catValueText   = $row['category'];
    }
}

// condition_active
if (!isset($_GET['active'])) {
    // do nothing and light a spliff :)
} elseif ( $_GET['active'] != "" ) {
    $condition_active = "active = '" . $_GET['active'] . "'";
    $actValue       = $_GET['active'];
    $actValueText   = $actValue == "no" ? "nur inaktive anzeigen" : "nur aktive anzeigen";
}

// condition_inv
if (!isset($_GET['inv'])) {
    // do nothing and light a spliff :)
} elseif ( $_GET['inv'] == 0 ) {
    $condition_inv  = "inventory = '0'";
    $invValue       = "checked";
}

// condition zusammensetzen
if ( ($condition_cat != "" ) || ( $condition_active != "") || ($condition_inv != "" )) {
    $condition = " where ";

    // if category is set
    if ( $condition_cat != "" ) {
        $condition .= $condition_cat;
        if ( ($condition_active != "") || ($condition_inv != "" )) {
            $condition .= " and ";
            if ( $condition_active != "" ) {
                $condition .= $condition_active;
                if ( $condition_inv != "" ) {
                    $condition .= " and " . $condition_inv;
                }
            } else {
                $condition .= $condition_inv;
            }// eoi if active is set
        } // eoi not only category

    // if category isn't set
    } else {
        if ( $condition_active != "" ) {
            $condition .= $condition_active;
            if ( $condition_inv != "" ) {
                $condition .= " and " . $condition_inv;
            }

        // if only inv is set
        } else {
            $condition .= $condition_inv;
        }
    }
} // eo building condition


#####
#
# FORMULAR SUCHEN
#
#####

// Titel
echo "<b>Artikel anzeigen/suchen</b></br></br>";

// alle wagen aus db
$dbcon  = dbConnect(DBUSER,DBPW);
$sql    = "select id_cat, category from `category`";
$result = $dbcon->query($sql);
dbClose($dbcon);

// anzeige (auswahl)
echo "Welche Artikel sollen angezeigt werden?</br></br>";

//echo "<pre>"; print_r($_SESSION); echo "</pre>";

echo "<table width='100%'>";

    // Header
    echo "<tr>";
        echo "<td>Kategorie</td>";
        echo "<td>inaktiv/aktiv</td>";
        echo "<td>ausverkauft</td>";
    echo "</tr><tr>";

    echo "<form name='catselect' id='catselect' method='get' action='?top=inventar&sub=artikel'>";
        
        // Kategorie
        echo "<td>";
            echo "<input type='hidden' name='top' value='inventar' />";
            echo "<input type='hidden' name='sub' value='artikel' />";

            echo "<select name='cat_id'>";
                echo "<option value='" . $catValue . "'>" . $catValueText . "</option>";
                if ( $catValue != "" ) {
                    echo "<option value=''>Alle anzeigen</option>";
                }
                // Punkt fuer nicht zugewiesene
                if ( $catValue != "0" ) {
                        echo "<option value='0'>ohne Kategorie</option>";
                }
                // Punkt fuer jede Kategorie
                while ( $row=$result->fetch_assoc() ) {
                if ( $row['id_cat'] != $catValue ) {
                        echo "<option value='" . $row['id_cat'] ."'>" . $row['category'] . "</option>";
                    }
                }
            echo "</select>";
        echo "</td>";

        // inaktiv/aktiv/alle
        echo "<td>";
            echo "<select name='active'>";
                echo "<option value='" . $actValue . "'>" . $actValueText . "</option>";
                if ( $actValue != '' ) { echo "<option value=''>alle anzeigen</option>"; }
                if ( $actValue != 'no' ) {echo "<option value='no'>nur inaktive anzeigen</option>"; }
                if ( $actValue != 'yes' ) {echo "<option value='yes'>nur aktive anzeigen</option>"; }
            echo "</select>";
        echo "</td>";

        // nur ausverkauft
        echo "<td>";
            echo "<input type='checkbox' name='inv' id='inv' value='0' $invValue /> nur ausverkaufte anzeigen";
        echo "</td>";

        // Submitbutton
        echo "<td>";
            echo "<input type='submit' value='anzeigen' id='submit' name='submit' />";
        echo "</td>";

    echo "</form>";
echo "</tr></table>";
    
echo "</br>";

//echo "<pre>";print_r($_SERVER);echo "</pre>";


######
#    
#   STATUS
#    
######

if ( (!isset($_GET['deactivate'] )) && (!isset($_GET['activate'])) && (!isset($_GET['deletePic2'])) ) {
    // chill na light a spliff :)
} else {
    if ( !isset($_GET['action'])) {

        // Status
        if ( $statusType != "" ) {
            printStatus($statusType,$statusMsg);
            echo "</br>";
        }

        if ( $statusQuery != "") {
            $dbcon  = dbConnect(DBUSER,DBPW);
            $result = $dbcon->query($statusQuery);
            dbClose($dbcon);
            printArtikel("","","",$result,"admin");
        }
    }
}


#####
#
# ARTIKEL AUSGEBEN
#
#####

$sql    = "select * from artikel" . $condition;
$dbcon  = dbConnect(DBUSER,DBPW);
$result = $dbcon->query($sql);
dbClose($dbcon);

printArtikel("","","",$result,"admin");

?>
