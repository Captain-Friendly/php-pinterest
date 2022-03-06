<?php
    session_start();

    require 'bd.php';

    if(!isset($_SESSION['user'])){
        header('Location:login.php'); 
    }

    if($_SERVER['REQUEST_METHOD'] == "GET"){
        $idComment = $_GET['idComment'];
        $idImage = $_GET['idImage'];
        DeleteCommentaire($idComment);
        header("Location:gestImage.php?idImage=$idImage"); 
    }
?>