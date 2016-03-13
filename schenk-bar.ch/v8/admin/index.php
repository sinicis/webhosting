<?php

session_start();

require_once('/home/ironsmit/sec-data/schenk-bar.ch/.sec_data.php');
include('../functions.php');

if ( $_GET['action'] == "login" ) {
    checkLogin($_POST['user'],$_POST['pw']);
} elseif ( $_GET['action'] == "logout" ) {
    $_SESSION['token'] = "";
}

# check if logged in
$logon = checkLoginState();

##########################################
#                                        #
#             LOGIN-FORMULAR             #
#                                        #
##########################################

if ( $logon == "false" ) {

    echo "<form action='index.php?action=login' method='POST'>";
    echo "<table border='1px solid red'>";
        echo "<tr><td>";
            echo "<input name='user' id='user' type='text' />";
        echo "</td></tr>";
        echo "<tr><td>";
            echo "<input name='pw' id='pw' type='password' />";
        echo "</td></tr>";
        echo "<tr><td>";
            echo "<input name='send' id='send' type='submit' value='Login' />";
        echo "</td></tr>";
    echo "</table>";
    echo "</form>";

##########################################
#                                        #
#             FERIEN-FORMULAR            #
#                                        #
##########################################

} elseif ( $logon == "true" ) {

    // wenn deaktivieren
    if ( $_GET['action'] == "deactivate" ) {
        $sql    = "update holiday set active = 'no' where 1=1";
        $dbcon  = dbConnect(DBUSER,DBPW);
        $result = $dbcon->query($sql);
        dbClose($dbcon);
    } elseif ( $_GET['action'] == "activate" ) {
        $sql    = "update holiday set active = 'yes', start='" . $_POST['start'] . "', end='" . $_POST['end'] . "'";
        $dbcon  = dbConnect(DBUSER,DBPW);
        $result = $dbcon->query($sql);
        dbClose($dbcon);
    }

    // get holiday entries
    $row    = getHoliday();

    // wenn aktiv
    if ( $row['active'] == "yes" ) {
        echo "Ferien werden angezeigt: " .$row['start'] . " - " . $row['end'] . "</br>";
        echo "<a href='?action=deactivate'>nicht mehr anzeigen</a>";
        echo "</br></br>";
    
    // wenn inaktiv
    } else {
        echo "Es werden keine Ferien angezeigt.</br></br>";

        echo "<form action='index.php?action=activate' method='POST'>";
        echo "<table border='1px solid red'>";
            echo "<tr>";
                echo "<td>Start:</td>";
                echo "<td><input name='start' id='start' type='text' /></td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td>Ende:</td>";
                echo "<td><input name='end' id='end' type='text' /></td>";
            echo "</tr>";
            echo "<tr><td colspan='2'>";
                echo "<input name='send' id='send' type='submit' value='Anzeigen' />";
            echo "</td></tr>";
        echo "</table>";
        echo "</form>";
    }

echo "<a href='?action=logout'>Logout</a>";

} // eoi logged in


?>
