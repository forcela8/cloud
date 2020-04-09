<?php
session_start();
require_once "connectFTP.php";
$username = $_SESSION['username'];
$connectDB = $_SESSION['connectedDDB'];
$limiteStockage = $_SESSION['limiteStockage'];
$limiteStockageUnite = $_SESSION['limiteStockageUnite'];
$abonement = $_SESSION['abonement'];
function disconnect(){
    setcookie("s_actv", "", time() - (10 * 365 * 24 * 60 * 60));
    setcookie("c_ml", "", time() - (10 * 365 * 24 * 60 * 60));
    setcookie("c_ps", "", time() - (10 * 365 * 24 * 60 * 60));
}

    if(isset($_POST['disconnect'])){
        header("location: index");
        disconnect();
        
    }

if($connectDB === true){

    if(ftp_login($conftp, $user, $password)){
    }else{
        $message = "Une erreur ces produites lors de l'identification. Veuillez réessayer";
        echo htmlspecialchars('<script type="text/javascript">alert("$message");</script>');
    }
    ftp_pasv($conftp, true);


    $dir = "./htdocs/cloud/";
    $userDir = $dir.$username;

    $fileName;
    $localFileTmp;
    $localFileName;
    $destination;
if(isset($_FILES['picker'])){
    $localFileTmp =  $_FILES['picker']['tmp_name'];
    $localFileName = $_FILES['picker']['name'];
    $destination = $userDir."/" . $localFileName;
}
$isFileExist = false;
if(isset($_POST['upload'])){
    // foreach ($contentsDir as $value) {
    //     if($value != '.' && $value!='..'){
    //         if($value == $localFileName){
    //             $isFileExist = true;
    //         }
    //     }
    // }
    if($isFileExist){
        
    }else{
        if(ftp_put($conftp, $destination, $localFileTmp, FTP_ASCII)){
            echo "ok";
        }
    }
    
}

    function displayDir($conftp, $userDir){
        $nombreDir = "";
        if(@ftp_chdir($conftp, $userDir)) { 
            ftp_cdup($conftp);
            $nombreDir = "1/1";
        } else {
            $nombreDir = "0/1";
        }
        return $nombreDir;
    }

    function getSize($conftp, $userDir, $path){
            $size = ftp_size($conftp, $userDir."/".$path);
            if ($size == -1) {
                $size = "Impossible de récupérer la taille total de votre cloud pour l'instant";
            }
            return $size;
    }

    function userFolderSize($conftp, $userDir){
        $contentsDir = ftp_nlist($conftp, $userDir);
        $size=0;
        $sizeByte=0;
        $nbFichier = 0;
        $unityIEC = "octet";
        foreach ($contentsDir as $value) {
                if($value != '.' && $value!='..'){
                   $nbFichier ++;
                   $sizeByte = $sizeByte + getSize($conftp, $userDir, $value);
                   $size = $size + getSize($conftp, $userDir, $value);
                }
            }
        if($nbFichier<1){
            $size = 0;
            $unityIEC = "Kb";
        }else{
            if($size > 1000 && $size < pow(10, 5)){
                $size = $size/1000;
                $unityIEC = " Kb";
            }else if($size >= pow(10,5) && $size < pow(10,8)){
                $size = $size*pow(10,-6);
                $unityIEC = " Mb";
            } else{
                $size = $size*pow(10, -9);
                $unityIEC = " Gb";
            }
            $size = number_format($size, 2, ',', ' ');
        }
        return array($size, $unityIEC, $sizeByte);
    }


    function calculStockage($limiteStockage, $sizeByte, $limiteStockageUnite, $uniteValeur){
        $stockageReste = $limiteStockage;
        $unite ='';
        $stockageReste = $stockageReste*pow(10,9);
        $stockageReste = $stockageReste - $sizeByte;
        $stockageReste = $stockageReste*pow(10,-9);
        $unite = limiteStockage($stockageReste);
        $stockageReste = convertSize($unite, $stockageReste);
        $stockageReste = number_format($stockageReste, 2, ',', ' ');
        return array($unite, $stockageReste);
    }

    function limiteStockage($size){
        $unite ='Gb';
        if($size<pow(10,-4)){
            $unite='Kb';
        }else if($size<1){
            $unite='Mb';
        }
        return $unite;
    }

    function convertSize($unite, $size){
        $stockage = $size;
        if($unite == 'Kb'){
            $stockage = $stockage*pow(10,6); 
        }else if($unite == 'Mb'){
            $stockage = $stockage*pow(10,3);
        }
        return $stockage;
    }  
    

    list($sizeDir, $unityIEC, $sizeByte) = userFolderSize($conftp, $userDir);
    if($sizeDir==0 && $unityIEC=='Kb'){
        $uniteStockage = "Gb";
        $stockageReste = 1;
    }else{
        list($uniteStockage, $stockageReste) = calculStockage($limiteStockage, $sizeByte, $limiteStockageUnite, $unityIEC);
        if($stockageReste == $limiteStockage){
            $stockageReste = 1;
        }
    }
    echo htmlspecialchars("La taille totale des fichiers est  : ".$sizeDir . $unityIEC)  ;
    echo htmlspecialchars(" Il vous reste : ". $stockageReste . $uniteStockage ." displonible sur votre cloud");

    $cloudExist = displayDir($conftp, $userDir);
    // if($dirExist === true){
    //     echo "Vous avez déjà créer votre espace cloud gratuit";
    // }else{
    //     echo "Vous n'avez pas encore créer votre espace cloud gratuit";
    // }
    if(isset($_POST['createDir'])){
        if($cloudExist == '0/1'){
            ftp_mkdir($conftp, $userDir);
            displayDir($conftp, $userDir);
            echo htmlspecialchars("Espace de stockage créer avec succès") ;
        }else{
            echo htmlspecialchars("Cloud déjà créer");
        }  
    }

    function isFolderFtp($conftp,$value){
        $contentsDir = ftp_nlist($conftp, "./htdocs/" .$value);
        foreach ($contentsDir as $value) {
            if($value != '.' && $value!='..'){
                if(ftp_size($conftp, "./htdocs/". $value) === -1){
                    return true;
                }
            }
        }
    }
    function display_folder_ftp($conftp,$contentsDir){
        $folder = array();
        foreach ($contentsDir as $value) {
            if($value != '.' && $value!='..'){
                if(ftp_size($conftp, "./htdocs/". $value) === -1){
                    array_push($folder, $value);
                }
            }
        }
        return $folder;
    }
    function display_file_ftp($conftp, $contentsDir){
        $file = array();
        foreach ($contentsDir as $value) {
            if($value != '.' && $value!='..'){
                if(ftp_size($conftp, "./htdocs/". $value) !== -1){
                    array_push($file, $value);
                }
            }
        }
        return $file;
    }
    function fileFromFolder($conftp, $folder){
        $contentsDir = ftp_nlist($conftp, "./htdocs/".$folder);
        $file = array();
        foreach ($contentsDir as $value) {
            if($value != '.' && $value!='..'){
                array_push($file, $value);
            }
        }
        return $file;
    }
    // $contentsDir = ftp_nlist($conftp, "./htdocs/cloud-user/");
    
        // // Nombre de fichier et dossier
        // $nbDirFile = 0;
        // foreach ($contentsDir as $value) {
        //     if($value != '.' && $value!='..'){
        //         $nbDirFile++;
        //     }
        // }
        // // Fin comptage de fichier et dossier

        // echo ($nbDirFile) . " Fichier et dossier <br>";

        // // Afficher fichier et directory
        // foreach ($contentsDir as $value) {
        //     if($value != '.' && $value!='..'){
        //         echo $value . "<br>";
        //     }
        // }

    
}else{
    header("location: login");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre espace cloud</title>
</head>
<body>
    <p>Nombre de cloud : <?php echo htmlspecialchars($cloudExist)?></p>
    <p>Il vous reste <?php echo htmlspecialchars($stockageReste." ".$uniteStockage)?> sur vos <?php echo htmlspecialchars($limiteStockage ." " .$limiteStockageUnite)?></p>
    <p>Vous souhaitez augmentez votre l'espace de stockage ?<a href="#"> Passer pour un abonement payant</a></p>
    <form method="POST" enctype="multipart/form-data">
        <input type="submit" name="createDir" id="createDir" value="Créer votre espace de stockage">
        <input type="file" name="picker" id="picker">
        <input type="submit" name="upload" value="Upload votre fichier"/>
        <input type="submit" name="uploadDir" value="Créer un dossier"/>
        <input type="submit" name="disconnect" value="Se déconnecter">
    </form>
    
    <!-- <div id="dialog" class="dialog">
        <div class="dialog-container">
            <div class="title">
                <p class="">Le fichier existe déjà. Voulez-vous le remplacer ?</p>
            </div>
            <div class="container-button">
                <button id="replace" class="replace">Remplacer</button>
                <button id="cancel" class="cancel">Annuler</button> 
            </div>
        </div>
        
    </div> -->
</body>
</html>