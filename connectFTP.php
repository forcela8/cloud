<?php
{
    $host = "ftpupload.net";
    $user = "epiz_25277764";
    $password = "mqbpbgRleHput";
}

$conftp = ftp_connect($host);
if(!$conftp){
    echo htmlspecialchars("Impossible de se connecter au serveur pour le moment.");
}
?>