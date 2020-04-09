<?php
if(isset($_POST['sub'])){
    session_start();
    $email = $_POST['mail'];
    if(!isset($email)){
        header("location: login");
    }
    $_SESSION['emailAccount'] = $email;
    $_SESSION['messageMail'] = " Si vous n'avez pas fait de demande, veuillez contacter le support le plus rapidement possible.\n\n Pour changer votre mot de passe, cliquez sur le lien : \n www.website.yj.fr/changePassword.php?email=$email \n\n Ceci est un message automatique, si vous souhaitez contacter le support technique aller dans la partie contact qui vous renverra vers un chat box.";
    $_SESSION['subMail'] = "Demande de changement de mot de passe";
    header("location: mail");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
        <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Changer votre mot de passe</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
<div class="container-token" id="container-token">
	<div class="cont">
		<form method="POST">
            <h1 class="text titleToken">Vous avez oublié votre mot de passe ?</h1>
            <p>Pas de problème... Demandez en un nouveau.</p>
            <input type="email" id="mail" placeholder="Email" required/>
            <button class="send" id="sub" type="submit">Envoyer</button>
		</form>
    </div>
</div>
</body>
</html>