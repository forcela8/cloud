<?php
session_start();

function decrypt($encrypt){
	$ciphering = "AES-128-CTR"; 
  
	// Use OpenSSl Encryption method 
	$iv_length = openssl_cipher_iv_length($ciphering); 
	$options = 0; 
	
	$decryption_iv = '1572642154630'; 
	$decryption_key = "CloudFromForcela123456"; 
	$decryption=openssl_decrypt ($encrypt, $ciphering, $decryption_key, $options, $decryption_iv); 
	return $decryption;
}

require_once "config.php";
if($_COOKIE['s_actv'] == true || isset($_COOKIE['s_actv'])){
    $users = $_COOKIE['c_ml'];
    $email = decrypt($users);
    $passwordCrypted = $_COOKIE['c_ps'];
    $passwordNonHash = decrypt($passwordCrypted);
    $sql = "SELECT `email`,`password`,`limite_stockage`,`limite_stockage_unite`,`abonement` FROM user INNER JOIN limit_ftp_user on limit_ftp_user.userId = `user`.IdUser where user.IdUser= ? and user.active = 1 and user.changement_password= 0";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
	$nbRow = mysqli_num_rows($result);
	if($nbRow>0){
        $row = $result->fetch_assoc();
        $user = $row['email'];
		$password = $row['password'];
		$limiteStockage = $row['limite_stockage'];
		$limiteStockageUnite = $row['limite_stockage_unite'];
        $abonement = $row['abonement'];
        if(password_verify($passwordNonHash, $password)){
            session_regenerate_id();
            $_SESSION['username'] = $user;
			$_SESSION['connectedDDB'] = true;
		    $_SESSION['limiteStockage']= $limiteStockage;
			$_SESSION['limiteStockageUnite']= $limiteStockageUnite;
            $_SESSION['abonement']= $abonement;
            header("location: cloud");
        }else{
            session_destroy();
            header("location: login");
        }
    }else{
        session_destroy();
            header("location: login");
    }
}else{
    session_destroy();
    header("location: login");
}
?>