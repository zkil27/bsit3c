<?php    
$servername = "localhost";
$username = "root";
$password = "";
$database = "db_user";

$connection = new mysqli($servername, $username, $password, $database);

if(isset($_GET['id'])){
    $id =  $_GET['id'];
    $sqldelete = "DELETE FROM tb_users WHERE ID=$id";
    $connection->query($sqldelete);
}

 header('location: index.php');

?>
