<?php 
    session_start();

    require 'bd.php';

    if(!isset($_SESSION['user'])){
        header('Location:login.php'); 

    }

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $comm = $_POST['comm'];
        $idImage = $_POST['idImage'];
        $userID = $_POST['userID'];
        AjouterCommentaire($comm, $userID, $idImage);
        header("Location:gestImage.php?idImage=$idImage"); 
    }

?>