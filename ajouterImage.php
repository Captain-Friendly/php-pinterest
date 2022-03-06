<?php
    session_start();

    if(!isset($_SESSION['user'])){
        header('Location:login.php'); 
    }
    $error = "";
    require 'bd.php';
    
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $authorID = trouverID($_SESSION['user']);
        $nom = $_POST['nomImage'];
        $allowedExts = array("gif", "jpeg", "jpg", "png");
        $extension = strtolower(end(explode(".", $_FILES["fichier"]["name"])));
        $description = $_POST['description'];

        if (($_FILES["fichier"]["size"] > 5000000)){
            $error = 'Taille trop grande';
        }else {
            if(in_array($extension, $allowedExts)){
                if ($_FILES["fichier"]["error"] > 0) {
                    $error = 'fichier trop grand';
                }
                else{
                    try{
                        $newfilename = InsererEtDonnerId($nom,$authorID,$extension,$description) . '.' . $extension;
                        move_uploaded_file($_FILES["fichier"]["tmp_name"], "fichiers/" . $newfilename);
                        header('Location:index.php'); 
                    }catch(Exception $ex){
                        $error = "Probleme avec le transfert de l'image";
                    }
                }
            }
            else{
                $error = 'extension invalide';
            }
        }
    }
    console_log("alright");
    require 'header.php';
?>
<h1>Ajouter un Image</h1>
<form action="ajouterImage.php" method="post" enctype="multipart/form-data">
    Image : <input name="fichier" size="35" type="file"><br><br>
    <br>
    <input type="text" name="nomImage" placeholder="Nom de l'image" required><br>
    <input type="text" name="description" placeholder="Description" ><br><br>
    <button type="submit">Ajouter une Image</button><br>
  <br>
</form>
<p><?php echo $error;?></p>
    
<?php require 'footer.php';?>