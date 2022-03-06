<?php 

    require 'bd.php';
    require 'header.php';
    
    if(!isset($_SESSION['user'])){
        header('Location:index.php'); 
    }
    $message = "";
    if($_GET['update'] != ""){
        $message ="Profil modifier";
    }

    $info = InfoUser($_SESSION['user']);

    $prenomNom = $info['prenomNom'];
    $mdp = $info['password'];
    $mail = $info['email'];
?>



<form action="updateProfil.php" method="POST">
    <input type="text" name="prenomNom" placeholder="Prenom et Nom" value="<?php echo $prenomNom?>" pattern="^(\w| )*[0-9A-Za-zçàéèêôâ](\w| )*$" required><br>
    <input type="text" name="mdp" placeholder="Mot de passe" value="<?php echo $mdp?>" required><br>
    <input type="email" name="mail" placeholder="Adresse courriel" pattern="[^ @]*@[^ @]*" value="<?php echo $mail?>" required size="30"><br><br>
    <button type="submit">Update</button>
    <?php echo $message;?>
</form>

<?php require 'footer.php';?>