<?php
session_start();
$mail = $_POST['mailToken'];
require_once "config.php";
if(!isset($mail)){
    header("location: resendMail");
}

$sql = "SELECT * FROM `user` WHERE `email`= ? and `active`=0";
$stmt = $con->prepare($sql);
$stmt->bind_param('s',$mail);
$stmt->execute();
$result = $stmt->get_result();
$nbRow = mysqli_num_rows($result);
if ($nbRow==0) {
    $user_key = md5(rand(0,1000));
    $sql = "UPDATE `user` set `token`=? where `email`='$mail'";
    $stmt->bind_param('s',$user_key);
    $stmt = $con->prepare($sql);
    $stmt->execute();
}

session_regenerate_id();
$_SESSION['emailAccount'] = $mail;
$_SESSION['keyCode']= $user_key;
$_SESSION['messageMail'] = " Merci de vous être inscrit sur notre cloud!\n\n Lien pour activer votre compte : \n www.website.yj.fr/verify?email=$mail&key=$user_key \n\n Ceci est un message automatique, si vous souhaitez contacter le support technique aller dans la partie contact qui vous renverra vers un chat box.";
$_SESSION['subMail'] = "Vérification de votre compte";
header("location: mail");
?>