<?php  
            require_once "config.php";
            $email = $_GET['email'];
            $key = $_GET['key'];
            $sql = "SELECT * FROM `user` WHERE `email`=? and `active`=0 and `token`=?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param('ss',$email,$key);
            $stmt->execute();
            $result = $stmt->get_result();
            $nbRow = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
        <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Confirmation de mail</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
<div class="container-token" id="container-token">
	<div class="cont">
        <?php if ($nbRow>0): ?>
            <?php
                $stockageBase = 1;
                $stockageUnite = "Gb";
                $abonement = "Gratuit";
                $row = $result->fetch_assoc();
                $id = $row['iduser'];
                $Date = date('d-m-Y H:i:s');
                $expirationToken = $row['expirationToken'];
                if ($expirationToken < $Date) {
                        header("location: resendMail");
                    } else{
                        $sql = "UPDATE `user` set `active`=true where `email`= ?";
                        $stmt = $con->prepare($sql);
                        $stmt->bind_param('s',$email);
                        if($stmt->execute()){
                            $sql = "INSERT INTO `limit_ftp_user` (`limite_stockage`,`limite_stockage_unite`,`abonement`,`userId`) values (?,?,?,?)";
                            $stmt = $con->prepare($sql);
                            $stmt->bind_param('issi', $stockageBase, $stockageUnite,$abonement, $id);
                            $stmt->execute();
                        }
                    }
            ?>
            <h1 class="texte">Compte activer avec succès</h1>
            <a href="index" class="but">Revenir à la page d'acceuil</a>
        <?php else: ?>
            <h1 class="textActive">Compte déjà activer ou n'existe pas encore.</h1>
            <a href="login" class="createAccount inline but">Créer un compte</a>
            <p class="para inline">ou</p>
            <a href="index" class="backIndex inline but">Revenir à la page d'accueil</a>
            <?php endif; ?>
    </div>
</div>
</body>
</html>