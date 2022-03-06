<?php
    session_start();
    $prenomNom = "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title><?php echo $titre;?></title> 
</head>
<body>
    <header>
        <h1> TPFinal</h1>
        
        <?php
            if (isset($_SESSION['prenomNom'])) {
                $prenomNom = $_SESSION['prenomNom'];
                echo 
                "<p> $prenomNom </p>
                <a href='deconnecter.php'>
                    <button>
                        Deconnecter
                    </button>
                </a>
                <a href='profil.php'><button>Modifier profil</button></a>";
            }
            else{
                echo"<a href='login.php'>
                <button>
                    Connexion
                </button>
            </a>";
            }
        ?>
        <a href='ajouterImage.php'>
                    <button>
                        Ajouter une Image
                    </button>
                </a>
        <a href='index.php'>
            <button>
                Page principal
            </button>
        </a>
        
        
    </header>
    <main>
    <br>