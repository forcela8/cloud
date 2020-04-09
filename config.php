<?php 
session_start();
{
    $user = "websemwd_root";
    $pswd = "basile@13";
    $dbName = "websemwd_website";
}

$con = mysqli_connect("localhost", $user, $pswd, $dbName);
$con->set_charset("utf8");
if (!$con) {
    echo htmlspecialchars("Erreur : Impossible de se connecter." . PHP_EOL, ENT_QUOTES);
    exit;
}
?>