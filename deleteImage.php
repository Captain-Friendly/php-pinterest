<?php
    session_start();
    require 'bd.php';
    if(!isset($_SESSION['user'])){
        header('Location:login.php'); 
    }

    if($_SERVER['REQUEST_METHOD'] == "GET"){
        $idImage = $_GET['idImage'];
        $info = InfoImage($idImage);
        $extension = $info['extension'];
        DeleteImage($idImage);
        console_log('Hello');
        $image = "fichiers/$idImage.$extension";

        unlink($image);
        header('Location:index.php'); 
    }
    header('Location:index.php'); 
?>