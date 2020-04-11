<?php 
session_start();

$con = mysqli_connect("localhost", $user, $pswd, $dbName);
$con->set_charset("utf8");
if (!$con) {
    echo htmlspecialchars("Erreur : Impossible de se connecter." . PHP_EOL, ENT_QUOTES);
    exit;
}
?>
