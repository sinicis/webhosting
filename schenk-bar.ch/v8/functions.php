<?php


##########################################
#                                        #
#              LOGIN/LOGOUT              #
#                                        #
##########################################


function checkLoginState() {

    $sql    = "select token from session";
    $dbcon  = dbConnect(DBUSER,DBPW);
    $result = $dbcon->query($sql);
    $row    = $result->fetch_assoc();
    dbClose($dbcon);
    $token  = $row['token'];

    $logon = $token == $_SESSION['token'] ? "true" : "false";

    return $logon;

}


function checkLogin($user,$pw) {


    if (($pw == ADMPW) && ($user == ADMUSER)) {
        //$token = bin2hex(random_bytes(80));
        $token = bin2hex(openssl_random_pseudo_bytes(32));
        $_SESSION['token'] = $token;
        $_SESSION['user'] = $user;

        $sql    = "update session set token ='" . $token . "' where 1=1";
        $dbcon  = dbConnect(DBUSER,DBPW);
        $result = $dbcon->query($sql);
        dbClose($dbcon);

    } else {
        $_SESSION['token'] = "";
        $_SESSION['user'] = "";
    }
}

##########################################
#                                        #
#                DATABASE                #
#                                        #
##########################################

function dbConnect($user,$pw) {

    $host   = 'localhost';

    $dbcon  = new mysqli($host,$user,$pw,DBNAME);

    if($dbcon->connect_errno > 0){
        die('Verbindungsfehler: ' . $db->connect_erro );
    }

    return $dbcon;

} // eof dbConnect

function dbClose($dbcon) {

    $dbcon->close();

} // eof dbClose

##########################################
#                                        #
#                HOLIDAYS                #
#                                        #
##########################################

function getHoliday() {

    // get holiday entries
    $sql    = "select * from holiday";
    $dbcon  = dbConnect(DBUSER,DBPW);
    $result = $dbcon->query($sql);
    $row    = $result->fetch_assoc();
    dbClose($dbcon);

    return $row;

}


?>
