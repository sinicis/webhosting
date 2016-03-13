<?php

##########################################
#                                        #
#                  MENU                  #
#                                        #
##########################################

function displayMenuPoint($page,$activePage,$text) {

    // check if top or sub
    $class  =  "menupoint";
    $link   = "?top=$page";


    if (( $page == $activePage) || ( $page == $activeSub )) {
        echo "<a class='menu' href='" . $link . "'><div class='" . $class . "Active'>" . $text . "</div></a>";
    } else {
        echo "<a class='menu' href='" . $link . "'><div class='" . $class . "'>" . $text . "</div></a>";
    }

} // end of function displayMenuPoint


function displayMenu() {

    // get current page
    $activePage     = isset($_GET['top']) ? $_GET['top'] : "home";

    displayMenuPoint("home",$activePage,"Home","top");
    displayMenuPoint("offer",$activePage,"Angebote","top");
    displayMenuPoint("work",$activePageb,"Referenzen","top");
    displayMenuPoint("me",$activePage,"&Uuml;ber mich","top");
    displayMenuPoint("kontakt",$activePage,"Kontakt","top");

} // end of function displayMenu


?>
