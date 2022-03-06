<?php
    require('bd.php');
    $prenomNom = "";
    $user = "";
    $mdp = "";
    $mail = "";
    $error = "";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if($_POST['redirect'] == ""){
            $error = "";
            $prenomNom = $_POST['prenomNom'];
            $user = $_POST['user'];
            $mdp = $_POST['mdp'];
            $mail = $_POST['mail'];

            $username = UserExiste($user);

            if($username == ""){
                $id = InsererUserIncomplet($prenomNom, $mail,$mdp,$user);
                EmailRegistration($mail,$id,$prenomNom);
                header("Location:login.php?emailsent=yes"); 
            }else{
                $error = "Username est déjà utiliser";
            }
        }
    }
    $titre ='Inscription';
    require 'header.php';
?>
    
    <form action="inscrip.php" method="post">
        <h1>Registration</h1>
        <input type="text" name="prenomNom" placeholder="Prenom et Nom" value="<?php echo $prenomNom?>" pattern="^(\w| )*[0-9A-Za-zçàéèêôâ](\w| )*$" required><br>
        <input type="text" name="user" placeholder="Pseudonyme" value="<?php echo $user?>" required><br>
        <input type="text" name="mdp" placeholder="Mot de passe" value="<?php echo $mdp?>" required><br>
        <input type="email" name="mail" placeholder="Adresse courriel" pattern="[^ @]*@[^ @]*" value="<?php echo $mail?>" required size="30"><br><br>
        <button type="submit">Registrer</button>
    </form>
    <p><?php echo $error;?></p>

<?php require 'footer.php'?>