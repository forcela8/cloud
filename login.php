<?php
session_start();
function encrypt($passwordNonHash){
	$ciphering = "AES-128-CTR"; 
  
	// Use OpenSSl Encryption method 
	$iv_length = openssl_cipher_iv_length($ciphering); 
	$options = 0; 
	
	// Non-NULL Initialization Vector for encryption 
	$encryption_iv = '';
	
	// Store the encryption key 
	$encryption_key = ""; 
	
	// Use openssl_encrypt() function to encrypt the data 
	$encryption = openssl_encrypt($passwordNonHash, $ciphering, $encryption_key, $options, $encryption_iv); 
	return $encryption;
}

if(isset($_COOKIE['s_actv'])){
	header("location: getLoggedIn");
}else{
	if(isset($_POST['submit'])){
		require_once "config.php";
		$user = $_POST['username'];
		$passwordNonHash = $_POST['passwd'];
		$passwordHash = password_hash ($passwordNonHash, PASSWORD_DEFAULT, ["cost" => 12]);
		$sql = "SELECT `iduser`,`password`,`limite_stockage`,`limite_stockage_unite`,`abonement`,`rank` FROM user INNER JOIN limit_ftp_user on limit_ftp_user.userId = `user`.IdUser where user.email= ? and user.active = 1 and user.changement_password= 0";
		$stmt = $con->prepare($sql);
		$stmt->bind_param('s',$user);
		$stmt->execute();
		$result = $stmt->get_result();
		$nbRow = mysqli_num_rows($result);
		if ($nbRow > 0) : 	
			$row = $result->fetch_assoc();
			$id = $row['iduser'];
			$rank = $row['rank'];
			$password = $row['password'];
			$limiteStockage = $row['limite_stockage'];
			$limiteStockageUnite = $row['limite_stockage_unite'];
			$abonement = $row['abonement'];
			if(password_verify($passwordNonHash, $password)){
				if($rank == "admin"){
					$encryptPassword = encrypt($passwordNonHash);
					$encryptMail = encrypt($user);
					setcookie("s_actv", true, time() + (10 * 365 * 24 * 60 * 60));
					setcookie("c_ml", $id, time() + (10 * 365 * 24 * 60 * 60));
					setcookie("c_ps", $encryptPassword, time() + (10 * 365 * 24 * 60 * 60));

					$_SESSION['username'] = $user;
					$_SESSION['connectedDDB'] = true;
					$_SESSION['limiteStockage']= $limiteStockage;
					$_SESSION['limiteStockageUnite']= $limiteStockageUnite;
					$_SESSION['abonement']= $abonement;
					header("location: adminPanel.php");
				}else{
					$encryptPassword = encrypt($passwordNonHash);
					$encryptId = encrypt($id);
					setcookie("s_actv", true, time() + (10 * 365 * 24 * 60 * 60));
					setcookie("c_ml", $encryptId, time() + (10 * 365 * 24 * 60 * 60));
					setcookie("c_ps", $encryptPassword, time() + (10 * 365 * 24 * 60 * 60));
						
					$_SESSION['username'] = $user;
					$_SESSION['connectedDDB'] = true;
					$_SESSION['limiteStockage']= $limiteStockage;
					$_SESSION['limiteStockageUnite']= $limiteStockageUnite;
					$_SESSION['abonement']= $abonement;
	?>
	<script type="text/javascript">window.location.href='cloud';</script>
		<?php } } else {?>
			<script>alert("Votre mot de passe est incorrect");</script>
		<?php }?>
	<?php else:  ?>
		<script>alert("Votre nom d'utilisateur est incorrect ou a été suspendu");</script>
	
	<?php endif;?>
	<?php } }?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
        <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">
        <script src="https://kit.fontawesome.com/a23930e38d.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Page de connexion</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
<div class="container" id="container">
	<div class="form-container sign-up-container">
		<form action="createAccount.php" method="POST">
			<h1>Créer un compte</h1>
			<div class="social-container">
				<a href="#" class="social social-fb"><i class="fab fa-facebook-f icon-2x"></i></a>
				<a href="#" class="social social-google"><i class="fab fa-google"></i></a>
				<a href="#" class="social social-lk"><i class="fab fa-linkedin-in icon-2x"></i></a>
			</div>
			<h1 class="or">Ou connectez-vous avec vos identifiants</h1>
			<input type="text" name="name" id="name" placeholder="Nom et prénom" required/>
			<input type="email" name="mail" id="mail" placeholder="Email" required/>
			<input class="pass" name="mdp" id="mdp" type="password" placeholder="Mot de passe" required/>
			<button class="sign-in" type="submit" >Inscription</button>
		</form>
	</div>
	<div class="form-container sign-in-container">
		<form method="POST">
			<h1>Connexion</h1>
			<div class="social-container">
				<a href="#" class="social social-fb"><i class="fab fa-facebook-f icon-2x"></i></a>
				<a href="#" class="social social-google"><i class="fab fa-google"></i></a>
				<a href="#" class="social social-lk"><i class="fab fa-linkedin-in icon-2x"></i></a>
			</div>
			<h1 class="or">Ou connectez-vous avec vos identifiants</h1>
			<input type="email" name="username" id="username" placeholder="Email " required/>
			<input class="pass" name="passwd" id="passwd" type="password" placeholder="Mot de passe" autocomplete="on" required/>	
            <button class="sign-in" type="submit" name="submit" id="submit">Connexion</button>
            <a href="forgetPassword.php" class="forgetPass">Mot de passe oublié ?</a>
		</form>
	</div>
	<div class="overlay-container">
		<div class="overlay">
			<div class="overlay-panel overlay-left">
				<h1>Vous possédez déjà un compte ?</h1>
				<p>Connectez-vous pour avoir accès à votre panel personnel</p>
				<button class="ghost" id="signIn">Connexion</button>
			</div>
			<div class="overlay-panel overlay-right">
				<h1>Pas encore de compte ?</h1>
				<p>Rejoignez-nous pour disposer de votre espace de stockage gratuit.</p>
				<button class="ghost" id="signUp">Créer un compte</button>
			</div>
		</div>
	</div>
</div>
<script>
    const signUpButton = document.getElementById('signUp');
const signInButton = document.getElementById('signIn');
const container = document.getElementById('container');

signUpButton.addEventListener('click', () => {
	container.classList.add("right-panel-active");
});

signInButton.addEventListener('click', () => {
	container.classList.remove("right-panel-active");
});


</script>
</body>
</html>
