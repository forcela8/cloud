<?php
require_once "config.php";
if(isset($_POST['newPswd'])){
    $email = $_GET['email'];
    $password = $_POST['password'];
    $passwordHash = password_hash($password, PASSWORD_DEFAULT, ["cost" => 12]);
    if(!isset($email)){
        header("location: index");
    }
    $sql = "UPDATE `user` set `password`=? where `email`=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('ss',$passwordHash, $email);
    if($stmt -> execute()){
        header("location: login");
    }else{
        echo '<script type="text/javascript">alert("Une erreur est survenue. Veuillez contacter le support");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changer votre mot de passe</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
<div class="container-token" id="container-token">
	<div class="cont">
		<form method="POST">
			<h1 class="text titleToken">Entrez le nouveau mot de passe désiré</h1>
            <label class="mdp">Votre nouveau mot de passe </label><input type="password" class="pswd" id="password" placeholder="Nouveau mot de passe" required/>
            <button class="send" id="newPswd" type="submit">Changer de mot de passe</button>
		</form>
    </div>
</div>
</body>
</html>