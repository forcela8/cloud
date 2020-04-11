<?php
require_once "config.php";
$name = $_POST['name'];
$passwordNonHash = $_POST['mdp'];
$mail = $_POST['mail'];

date_default_timezone_set("France/Paris");
$creationDate = date("d-m-Y H:i:s");
$expirationTokenDate = date("d-m-Y H:i:s"); 
$expirationTokenDate = date('d-m-Y H:i:s', strtotime($expirationTokenDate. ' + 1 days')); 


function getUserIpAddr(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function encrypt($ip){
	$ciphering = "AES-128-CTR"; 
  
	// Use OpenSSl Encryption method 
	$iv_length = openssl_cipher_iv_length($ciphering); 
	$options = 0; 
	
	// Non-NULL Initialization Vector for encryption 
	$encryption_iv = '';
	
	// Store the encryption key 
	$encryption_key = ""; 
	
	// Use openssl_encrypt() function to encrypt the data 
	$encryption = openssl_encrypt($ip, $ciphering, $encryption_key, $options, $encryption_iv); 
	return $encryption;
}

function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
    $output = NULL;
    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
        $ip = $_SERVER["REMOTE_ADDR"];
        if ($deep_detect) {
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
    }
    $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
    $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
    $continents = array(
        "AF" => "Africa",
        "AN" => "Antarctica",
        "AS" => "Asia",
        "EU" => "Europe",
        "OC" => "Australia (Oceania)",
        "NA" => "North America",
        "SA" => "South America"
    );
    if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
        if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
            switch ($purpose) {
                case "location":
                    $output = array(
                        "city"           => @$ipdat->geoplugin_city,
                        "state"          => @$ipdat->geoplugin_regionName,
                        "country"        => @$ipdat->geoplugin_countryName,
                        "country_code"   => @$ipdat->geoplugin_countryCode,
                        "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                        "continent_code" => @$ipdat->geoplugin_continentCode
                    );
                    break;
                case "address":
                    $address = array($ipdat->geoplugin_countryName);
                    if (@strlen($ipdat->geoplugin_regionName) >= 1)
                        $address[] = $ipdat->geoplugin_regionName;
                    if (@strlen($ipdat->geoplugin_city) >= 1)
                        $address[] = $ipdat->geoplugin_city;
                    $output = implode(", ", array_reverse($address));
                    break;
                case "city":
                    $output = @$ipdat->geoplugin_city;
                    break;
                case "state":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "region":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "country":
                    $output = @$ipdat->geoplugin_countryName;
                    break;
                case "countrycode":
                    $output = @$ipdat->geoplugin_countryCode;
                    break;
            }
        }
    }
    return $output;
}


if ( !isset($name, $passwordNonHash, $mail) ) {
    header("location: login");
}
if(!filter_var($mail, FILTER_VALIDATE_EMAIL)){
    die ("Veuillez ne pas remplir le formulaire avec la console");
}
// Liste adresse mail bannie
{
    $disposable_list = array('0815.ru0clickemail.com','0-mail.com','example.com','exemple.com','0wnd.net','0wnd.org','10minutemail.com','20minutemail.com','2prong.com','3d-painting.com','4warding.com','4warding.net','4warding.org','9ox.net','a-bc.net','ag.us.to','amilegit.com','anonbox.net','anonymbox.com','antichef.com','antichef.net','antispam.de','baxomale.ht.cx','beefmilk.com','binkmail.com','bio-muesli.net','bobmail.info','bodhi.lawlita.com','bofthew.com','brefmail.com','bsnow.net','bugmenot.com','bumpymail.com','casualdx.com','chogmail.com','cool.fr.nf','correo.blogos.net','cosmorph.com','courriel.fr.nf','courrieltemporaire.com','curryworld.de','cust.in','dacoolest.com','dandikmail.com','deadaddress.com','despam.it','despam.it','devnullmail.com','dfgh.net','digitalsanctuary.com','discardmail.com','discardmail.de','disposableaddress.com','disposeamail.com','disposemail.com','dispostable.com','dm.w3internet.co.ukexample.com','dodgeit.com','dodgit.com','dontreg.com','dontsendmespam.de','dump-email.info','dumpyemail.com','e4ward.com','email60.com','emailias.com','emailias.com','emailinfive.com','emailtemporario.com.br','emailwarden.com','enterto.com','ephemail.net','explodemail.com','fakeinbox.com','fakeinformation.com','fansworldwide.de','fastacura.com','filzmail.com','fizmail.com','frapmail.com','garliclife.com','gelitik.in','get1mail.com','getonemail.com','getonemail.net','girlsundertheinfluence.com','gishpuppy.com','goemailgo.com','great-host.in','greensloth.com','greensloth.com','gsrv.co.uk','guerillamail.biz','guerillamail.com','guerillamail.net','guerillamail.org','guerrillamail.biz','guerrillamail.com','guerrillamail.net','guerrillamail.org','guerrillamailblock.com','haltospam.com','hidzz.com','hotpop.com','ieatspam.eu','ieatspam.info','ihateyoualot.info','imails.info','inboxclean.com','inboxclean.org','incognitomail.com','incognitomail.net','ipoo.org','irish2me.com','jetable.com','jetable.fr.nf','jetable.net','jetable.org','jnxjn.com','junk1e.com','kasmail.com','kaspop.com','klzlk.com','kulturbetrieb.info','kurzepost.de','kurzepost.de','lifebyfood.com','link2mail.net','litedrop.com','lookugly.com','lopl.co.cc','lr78.com','maboard.com','mail.by','mail.mezimages.net','mail4trash.com','mailbidon.com','mailcatch.com','maileater.com','mailexpire.com','mailin8r.com','mailinator.com','mailinator.net','mailinator2.com','mailincubator.com','mailme.lv','mailmoat.com','mailnator.com','mailnull.com','mailzilla.org','mbx.cc','mega.zik.dj','meltmail.com','mierdamail.com','mintemail.com','mjukglass.nu','mobi.web.id','moburl.com','moncourrier.fr.nf','monemail.fr.nf','monmail.fr.nf','mt2009.com','mx0.wwwnew.eu','mycleaninbox.net','myspamless.com','mytempemail.com','mytrashmail.com','netmails.net','neverbox.com','no-spam.ws','nobulk.com','noclickemail.com','nogmailspam.info','nomail.xl.cx','nomail2me.com','nospam.ze.tc','nospam4.us','nospamfor.us','nowmymail.com','objectmail.com','obobbo.com','odaymail.com','onewaymail.com','ordinaryamerican.net','owlpic.com','pookmail.com','privymail.de','proxymail.eu','punkass.com','putthisinyourspamdatabase.com','quickinbox.com','rcpt.at','recode.me','recursor.net','regbypass.comsafe-mail.net','safetymail.info','sandelf.de','saynotospams.com','selfdestructingmail.com','sendspamhere.com','sharklasers.com','shieldedmail.com','hiftmail.com','skeefmail.com','slopsbox.com','slushmail.com','smaakt.naar.gravel','smellfear.com','snakemail.com','sneakemail.com','sofort-mail.de','sogetthis.com','soodonims.com','spam.la','spamavert.com','spambob.net','spambob.org','spambog.com','spambog.de','spambog.ru','spambox.info','spambox.us','spamcannon.com','spamcannon.net','spamcero.com','spamcorptastic.com','spamcowboy.com','spamcowboy.net','spamcowboy.org','spamday.com','spamex.com','spamfree.eu','spamfree24.com','spamfree24.de','spamfree24.eu','spamfree24.info','spamfree24.net','spamfree24.org','spamgourmet.com','spamgourmet.net','spamgourmet.org','spamherelots.com','spamhereplease.com','spamhole.com','spamify.com','spaminator.de','spamkill.info','spaml.com','spaml.de','spammotel.com','spamobox.com','spamspot.com','spamthis.co.uk','spamthisplease.com','speed.1s.fr','suremail.info','tempalias.com','tempe-mail.com','tempemail.biz','tempemail.com','tempemail.net','tempinbox.co.uk','tempinbox.com','tempomail.fr','temporaryemail.net','temporaryinbox.com','tempymail.com','thankyou2010.com','thisisnotmyrealemail.com','throwawayemailaddress.com','tilien.com','tmailinator.com','tradermail.info','trash-amil.com','trash-mail.at','trash-mail.com','trash-mail.de','trash2009.com','trashmail.at','trashmail.com','trashmail.me','trashmail.net','trashmailer.com','trashymail.com','trashymail.net','trillianpro.com','tyldd.com','tyldd.com','uggsrock.com','wegwerfmail.de','wegwerfmail.net','wegwerfmail.org','wh4f.org','whyspam.me','willselfdestruct.com','winemaven.info','wronghead.com','wuzupmail.net','xoxy.net','yogamaven.com','yopmail.com','yopmail.fr','yopmail.net','yuurok.com','zippymail.info','zoemail.com');
    $domain = substr(strrchr($mail, "@"), 1); //extract domain name from email
}


if(in_array($domain, $disposable_list)){ 
    
    die("Veuillez ne pas utilisez une adresse email jetable ou incorrect.");

}else{
    $passwordHash = password_hash ($passwordNonHash, PASSWORD_DEFAULT, ["cost" => 12]);
    $sql = "SELECT * FROM `user` WHERE `email`= ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('s',$mail);
    $stmt->execute();
    $result = $stmt->get_result();
    $nbRow = mysqli_num_rows($result);
    if ($nbRow==0) {
        session_start();
        $ipSession = getUserIpAddr();
        $country = ip_info($ipSession, "City") . ", " .ip_info($ipSession, "Country");
        $countryUser = encrypt($country);
        $ipUser = encrypt($ipSession);
        $user_key = md5(rand(0,1000));
        $_SESSION['keyCode']= $user_key;
        $_SESSION['emailAccount']= $mail;
        $_SESSION['messageMail'] = " Merci de vous être inscrit sur notre cloud!\n\n Lien pour activer votre compte : \n www.website.yj.fr/verify?email=$mail&key=$user_key \n\n Ceci est un message automatique, si vous souhaitez contacter le support technique aller dans la partie contact qui vous renverra vers un chat box.";
        $_SESSION['subMail'] = "Vérification de votre compte";
        $sql = "INSERT INTO `user` (`email`,`password`,`name`,`token`,`expirationToken`,`creationDate`,`ipAdress`,`countriesIP`) values (?,?,?,?,?,?,?,?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param('sssssssss',$mail, $passwordHash, $name, $user_key, $expirationTokenDate, $creationDate, $ipUser, $countryUser);
        $stmt->execute();
        header("location: mail.php");
    }else{
        echo htmlspecialchars("Vous possedez déjà un compte");
    }
}
?>
