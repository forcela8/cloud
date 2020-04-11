<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
			<style>
			html {
				font-family: Tahoma, Geneva, sans-serif;
				padding: 10px;
			}
			table {
				
				border-collapse: collapse;
				width: 500px;
			}
			th {
				background-color: #54585d;
				border: 1px solid #54585d;
			}
			th:hover {
				background-color: #64686e;
			}
			th a {
				display: block;
				text-decoration:none;
				padding: 10px;
				color: #ffffff;
				font-weight: bold;
				font-size: 13px;
			}
			th a i {
				margin-left: 5px;
				color: rgba(255,255,255,0.4);
			}
			td {
				padding: 10px;
				color: #636363;
				border: 1px solid #dddfe1;
			}
			tr {
				background-color: #ffffff;
			}
			tr .highlight {
				background-color: #f9fafb;
			}
			</style>
</head>
<body>
    <table>
	<?php
        require_once "config.php";
        $orderBy = array('name', 'email', 'rank', 'creationDate', 'active', 'ipAdress');
        $order = 'name';
        if (isset($_GET['orderBy']) && in_array($_GET['orderBy'], $orderBy)) {
            $order = $_GET['orderBy'];
        }
        $sort_order = isset($_GET['order']) && strtolower($_GET['order']) == 'desc' ? 'DESC' : 'ASC';
        if(!isset($_GET['orderBy']) || !isset($_GET['order'])){
			$sql = "SELECT * FROM `user`";
		}else{
			$sql = "SELECT * FROM `user` ORDER BY " .$order . ' '. $sort_order;
		}
            $stmt = $con->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            $nbRow = mysqli_num_rows($result);
            if ($nbRow>0){
				$up_or_down = str_replace(array('ASC','DESC'), array('up','down'), $sort_order); 
				$asc_or_desc = $sort_order == 'ASC' ? 'desc' : 'asc';
	?>
        <tr>
            <th><a href="?orderBy=name&order=<?php echo $asc_or_desc; ?>">Nom Prénom<i class="fas fa-sort<?php echo $column == 'name' ? '-' . $up_or_down : ''; ?>"></a></th>
            <th><a href="?orderBy=email&order=<?php echo $asc_or_desc; ?>">Email<i class="fas fa-sort<?php echo $column == 'email' ? '-' . $up_or_down : ''; ?>"></a></th>
            <th><a href="?orderBy=rank&order=<?php echo $asc_or_desc; ?>">Rang<i class="fas fa-sort<?php echo $column == 'rank' ? '-' . $up_or_down : ''; ?>"></a></th>
            <th><a href="?orderBy=creationDate&order=<?php echo $asc_or_desc; ?>">Date de création<i class="fas fa-sort<?php echo $column == 'creationDate' ? '-' . $up_or_down : ''; ?>"></a></th>
            <th><a href="?orderBy=active&order=<?php echo $asc_or_desc; ?>">Actif<i class="fas fa-sort<?php echo $column == 'active' ? '-' . $up_or_down : ''; ?>"></a></th>
            <th><a href="?orderBy=ipAdress&order=<?php echo $asc_or_desc; ?>">Adresse IP<i class="fas fa-sort<?php echo $column == 'ipAdress' ? '-' . $up_or_down : ''; ?>"></a></th>
        </tr>
        <?php
                while($row = $result->fetch_assoc()){
                    echo "<tr><td>". htmlspecialchars($row['name']). "</td><td>". htmlspecialchars($row['email']) . "</td><td>". htmlspecialchars($row['rank']) . "</td><td>". htmlspecialchars($row['creationDate']). "</td><td>". htmlspecialchars($row['active']). "</td><td>". htmlspecialchars($row['ipAdress']). "<button> ok</button></td>";
                }
                echo "</table>";
            }else{
                echo "0 result";
			}
			
        ?>
    </table>
</body>
</html>