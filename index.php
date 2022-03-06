<?php 
    require 'bd.php';
    require 'header.php';

    $ordre ='date desc';
    if($_GET['desc'] != ""){
        $ordre = 'date';
    }
    echo"<a href='index.php?desc=true'> <button> Du plus vieux</button> </a> 
        <a href='index.php'> <button> Du plus récent</button> </a>";
    AfficherImages($ordre);
?>


<?php require 'footer.php';?> 
