<?php
  session_start();
  require('bd.php');
  $error = "";
  

  if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user = $_POST['user'];
    $psw = $_POST['psw'];

    $values = connection($user, $psw);
    $id = $values['id'];

    if($id == null){
      $error = "pseudo et/ou mot de passe invalide(s)";
    } 
    else{
      $_SESSION['prenomNom'] = trouverNomPrenom($user);
      $_SESSION['user'] = $user;
      header('Location:index.php'); 
    }
  }
  
  if($_SERVER['REQUEST_METHOD'] == "GET"){
    if($_GET['emailsent'] == 'yes'){
      $error = "le email a étais envoyer, si vous ne le trouvez pas dans votre inbox principal, il se peut qu'il soit dans le junk mail folder";
    }elseif(isset($_GET['confirmationID'])){
      $id = $_GET['confirmationID'];
      UsgerVerifier($id);
      $error = "Vous êtes maintenant inscrit à TPFinal, vous pouvez maintenant vous connecter!";
    }
    else{
      $error ="";
    }
  }
  $titre = 'Login';
  require 'header.php';
?>

<form action="login.php" method="post">

  <div class="container">
    <label for="user"><b>Username</b></label>
    <input type="text" placeholder="Enter Username" name="user">
    <br><br>
    <label for="psw"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="psw">
        
    <br><br>
    <button type="submit">Login</button>
    <button type="submit" formaction="inscrip.php" name="redirect" value="fromLogin">New user?</button>
    <br><br><br>
    <p><?php echo $error;?></p>
  </div>
</form>

<?php require 'footer.php'?>