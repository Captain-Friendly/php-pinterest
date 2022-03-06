<?php
    session_start();
    require 'bd.php';
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $prenomNom = $_POST['prenomNom'];
        $mdp = $_POST['mdp'];
        $mail = $_POST['mail'];
        $user = $_SESSION['user'];
        UpdateUser($prenomNom, $mdp,$mail,$user);
        header('Location:profil.php?update=true'); 
    }
    else{
        header('Location:index.php'); 
    }
?>