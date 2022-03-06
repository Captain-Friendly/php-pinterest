<?php
    session_start();
    $error = "";

    require 'bd.php';
    if($_SERVER['REQUEST_METHOD'] == "GET"){
        $idImage = $_GET['idImage'];
        $info = InfoImage($idImage);
        $nom = $info['givenName'];
        $authorId = $info['authorId'];
        $username = GetUserAuteur($authorId);
        $description = $info['Description'];
        $extention = $info['extension'];
        $dateFormat = new DateTime($info['date']);
        $date = $dateFormat->format("Y-m-d");
        $error = $_GET['error'];
    }
    else{
        header('Location:index.php'); 
    }
    require 'header.php';
?>
<div class="container">
    <div class="timbre">
        <img class="grandImage" src="fichiers/<?php echo "$idImage.$extention"?>" class="image">
        <p><?php echo $nom?></p>
        <p><?php echo $description?></p>
        <p>Publié par : <?php echo $username?></p>
        <p><?php echo $date?></p>   
        <p>
            <?php 
                if($error != ""){
                    echo "vous pouvez pas commenter un espace vide";
                }
            ?>
        </p>
        
        
        <?php 
            if(isset($_SESSION['user'])){
                $curUser = $_SESSION['user'];
                $curID = trouverID($curUser);
                if($curID == $authorId){
                    echo"
                        <a href='deleteImage.php?&idImage=$idImage'><button>Delete Image</button></a>
                    ";
                }
                echo"<form action='addComment.php' method='POST'>
                <input type='text' name='comm' maxlength='50' placeholder='vous avez 50 caracteres' size='50' required pattern='^(\w| )*[0-9A-Za-zçàéèêôâ](\w| )*$'>
                        <input type='hidden' name='idImage' value='$idImage'>
                        <input type='hidden' name='userID' value='$curID'>
                        <input type='submit' value='Commenter'> <br><br>
                    </form>";
                getCommentUsers($idImage, $curID);
            }else{
                getComments($idImage);
            }
        ?>
                    
        
    </div>
</div>
   
    
<?php require 'footer.php'?>