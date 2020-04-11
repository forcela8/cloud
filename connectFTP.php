

$conftp = ftp_connect($host);
if(!$conftp){
    echo htmlspecialchars("Impossible de se connecter au serveur pour le moment.");
}
?>
