<?php
require_once "connectFTP.php";
if(ftp_login($conftp, $user, $password)){
}else{
    $message = "Une erreur ces produites lors de l'identification. Veuillez réessayer";
    echo htmlspecialchars("<script type='text/javascript'>alert('$message');</script>");
}
ftp_pasv($conftp, true);
$contentsDir = ftp_nlist($conftp, "./htdocs/");
    
        // // Nombre de fichier et dossier
        // $nbDirFile = 0;
        // foreach ($contentsDir as $value) {
        //     if($value != '.' && $value!='..'){
        //         $nbDirFile++;
        //     }
        // }
        // // Fin comptage de fichier et dossier

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
    $file = display_file_ftp($conftp, $contentsDir);
    print_r($file);
    $folder = display_folder_ftp($conftp, $contentsDir);
    print_r($folder[1]);
    $fileFolder = fileFromFolder($conftp, $folder[1]);
    print_r($fileFolder);
?>