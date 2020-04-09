<?php
session_start();

$email = $_SESSION['emailAccount'];
$key = $_SESSION['keyCode'];

if(!isset($email, $key)){
    header("location: login");
}

$to = $email;
$msg = $_SESSION['messageMail'];
$subj = $_SESSION['subMail'];
$msg = wordwrap($msg,70);
$headers = "From: DO-NOT-REPLY <no-reply@website.yj.fr>";


mail($to, $subj, $msg, $headers);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css?family=Abril+Fatface|Open+Sans|Roboto&display=swap|Vollkorn&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Confirmation de mail</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
<div class="container-token" id="container-token">
	<div class="cont">
            <h1 class="text">Vérifier dès maintenant votre boîte mail</h1>
            <div class="mail-cont">
            <a href="https://mail.google.com/mail/u/0/#inbox" class="mail"><img src="https://img.icons8.com/color/55/000000/gmail.png"></a>    
            <a href="https://outlook.office365.com/mail/inbox" class="mail"><img src="https://img.icons8.com/color/55/000000/microsoft-outlook-2019--v2.png"></a>    
            <a href="https://login.yahoo.com" class="mail"><img src="https://img.icons8.com/color/55/000000/yahoo.png"></a>    
            </div>
            
    </div>
</div>
</body>
</html>