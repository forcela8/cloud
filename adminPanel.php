<?php
    echo htmlspecialchars("Membre inscrit sur le cloud :", ENT_QUOTES);
    require_once "config.php";
    $user = $_POST['username'];
    $passwordNonHash = $_POST['passwd'];
    $sql = "SELECT * FROM `user`";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('s',$user);
    $stmt->execute();
    $result = $stmt->get_result();
    $nbRow = mysqli_num_rows($result);
    if ($nbRow > 0){
        $row = $result->fetch_assoc();
        $username = $row['email'];
        $name = $row['name'];
        echo htmlspecialchars("Il y a " + $nbRow +" utilisateurs inscrit sur le cloud");
        echo htmlspecialchars("Membres  :", ENT_QUOTES);
        echo  htmlspecialchars($username + " "+ $name + "<br>", ENT_QUOTES);
    }

?>