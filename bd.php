<?PHP

use GuzzleHttp\Psr7\Query;
use \PHPMailer\PHPMailer\PHPMailer;
    use \PHPMailer\PHPMailer\Exception;
    require 'vendor/autoload.php';
    function getPDO(){
        $host = '127.0.0.1'; // 127.0.0.1 si BD et application sur même serveur
        $db = 'TPFinal'; // nom de la base de données
        $user = 'julian';
        $pass = '1234';
        $charset = 'utf8';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try{
            $pdo = new PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            console_log("Huston we have a problem");
        }
        return $pdo;
    }

    function validerRegex($string, $regex){
        if (!preg_match($regex, $string)){
            return false;
        }
        return true;

    }

    function connection($user, $password){
        $pdo = getPdo();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username=? and password=? AND status=1');
        $stmt->execute([$user,$password]);
        $row = $stmt->fetch();
        return $row;
    }

    function UserExiste($user){
        $pdo = getPdo();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username=?');
        $stmt-> execute([$user]);
        $row = $stmt->fetch();
        return $row['prenomNom'];
    }

    function InsererUserIncomplet($prenomNom, $email,$password,$username){ 
        $pdo = getPDO();
        try{
            $sql = "INSERT into users (prenomNom, email, password, username) VALUES(?,?,?,?)";
            $stmt = $pdo -> prepare($sql);
            $stmt-> execute([$prenomNom, $email, $password, $username]);

            $stmt2 = $pdo->prepare("SELECT id from users where username=?");
            $stmt2-> execute([$username]);
            $row = $stmt2->fetch();
            return $row['id'];

        }catch (Exception $e) {
            console_log("Huston we have a problem");
            exit;
        }
    }

    function EmailRegistration($email, $id, $prenomNom){
        
        $mail = new PHPMailer(TRUE);
        try {
            // origine
            $mail->setFrom('juliandelapaz2001@hotmail.com', 'Julian the developer');

            // destination
            $mail->addAddress($email, $prenomNom);

            // sujet
            $mail->Subject = 'Inscription au TPFinal';

            // le message comme tel
            $mail->Body = "Bonjour $prenomNom,\n 
            veuillez cliquer le lien ci-dessous pour terminer vôtre inscription au site TPFinal\n 
            http://144.217.86.130/TPFinal/login.php?confirmationID=$id";

            // important pour les caractères accentués
            $mail->CharSet = 'UTF-8';
            
            // envoi du courriel
            $mail->send();

        } catch (Exception $e) {
            console_log($e->errorMessage());
        } catch (\Exception $e) {
            console_log($e->getMessage());
        }
    }
    
    function UsgerVerifier($id){
        $pdo = getPDO();
        try{
            $stmt= $pdo->prepare("UPDATE users SET status=1 WHERE id=?");
            $stmt->execute([$id]);
        }catch(Exception $ex){
            console_log("Huston we have a problem");
        }
    }

    function UpdateUser($prenomNom,$mdp, $mail, $username){
        $pdo = getPDO();
        try{
            $stmt= $pdo->prepare("UPDATE users SET prenomNom=?,email=?,password=? WHERE username=?");
            $stmt->execute([$prenomNom, $mail,$mdp, $username]);
        }catch(Exception $ex){
            console_log("Huston we have a problem");
        }
    }

    function trouverNomPrenom($username){
        $pdo = getPDO();
        $stmt = $pdo->prepare('SELECT prenomNom FROM users WHERE username=?');
        $stmt-> execute([$username]);
        $row = $stmt->fetch();
        return $row['prenomNom'];
    }

    function trouverID($username){
        $pdo = getPDO();
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username=?');
        $stmt-> execute([$username]);
        $row = $stmt->fetch();
        return $row['id'];
    }

    function InfoUser($username){
        $pdo = getPDO();
        $stmt = $pdo->prepare('SELECT prenomNom,email,password FROM users WHERE username=?');
        $stmt-> execute([$username]);
        $row = $stmt->fetch();
        return $row;
    }

    function InsererEtDonnerId($givenName, $authorID,$extension,$description){
        $pdo = getPDO();
        try{
            $sql = "INSERT into Images (givenName, authorId, extension,Description) VALUES(?,?,?,?)";
            $stmt = $pdo -> prepare($sql);
            $stmt-> execute([$givenName,$authorID, $extension,$description]);


            $idImage = $pdo->lastInsertId();
            return $idImage;

        }catch (Exception $e) {
            console_log("Huston we have a problem");
            exit;
        }
    }

    function AfficherImages($order){
        $pdo = getPDO();
        //$order = 'date';
        $stmt = $pdo->query("SELECT * FROM Images order by $order");
        echo"<div class='container'>";
        while($row = $stmt->fetch()){
            $dateFormat = new DateTime($row['date']);
            $date = $dateFormat->format("Y-m-d");
            $id = $row['IdImage'];
            $nom = $row['givenName'];
            $extension = $row['extension'];
            $idAuthor = $row['authorId'];
            $username = GetUserAuteur($idAuthor);
            $description = $row['Description'];

            $filepath = "fichiers/$id.$extension";
            echo "<div class='timbre'>
                    <a href='gestImage.php?idImage=$id'>
                        <img src=".$filepath." class='image'/>
                    </a>
                    <p>$nom</p>
                    <p>$description</p>
                    <p>Publié par: $username</p>
                    <p>$date</p>

                    </div>";
        }
        echo"</div>";
    }

    function GetUserAuteur($idAuthor){
        $pdo = getPdo();
        $stmt = $pdo->prepare('SELECT username FROM users WHERE id=?');
        $stmt-> execute([$idAuthor]);
        $row = $stmt->fetch();
        return $row['username'];
    }

    function InfoImage($idImage){
        $pdo = getPDO();
        $stmt = $pdo->prepare('SELECT * FROM Images WHERE IdImage=?');
        $stmt-> execute([$idImage]);
        $row = $stmt->fetch();
        return $row;
    }

    function DeleteImage($idImage){
        $pdo = getPdo();
        try{
            $sql = "DELETE FROM Commentaires WHERE idImage = ?";
            $stmt= $pdo->prepare($sql);
            $stmt->execute([$idImage]);


            $sql = "DELETE FROM Images WHERE IdImage = ?";
            $stmt= $pdo->prepare($sql);
            $stmt->execute([$idImage]);
        }catch(Exception $ex){
            console_log("huston, we have a problem here");
        }
    }

    function getComments($idImage){
        $pdo = getPDO();
        
        $stmt = $pdo->prepare("SELECT * FROM Commentaires where IdImage = ? order by date desc");
        $stmt-> execute([$idImage]);
        echo"<div class='commentaires'>";

        while($row = $stmt->fetch()){
            $commentaire = $row['commentaire'];
            $authorID = $row['authorId'];
            $username = GetUserAuteur($authorID);

            echo "<div class='comment'> <p id='user'>$username: $commentaire</p> </div><br>";
        }
        echo"</div>";
    }

    function getCommentUsers($idImage, $idUser){
        $pdo = getPDO();
        
        $stmt = $pdo->prepare("SELECT * FROM Commentaires where IdImage = ? order by date desc");
        $stmt-> execute([$idImage]);
        echo"<div class='commentaires'>";

        while($row = $stmt->fetch()){
            $commentaire = $row['commentaire'];
            $idCommentaire = $row['idCommentaire'];
            $authorID = $row['authorId'];
            $username = GetUserAuteur($authorID);
            if($idUser == $authorID){
                echo "<div class='comment'> 
                        <p id='user'>$username: $commentaire</p> 
                        <a href='deleteComment.php?idComment=$idCommentaire&idImage=$idImage'><button>delete comment</button></a>
                      </div><br>";
            }else{
                echo "<div class='comment'> <p id='user'>$username: $commentaire</p> </div><br>";
                
            }

        }
        echo"</div>";
    }

    function DeleteCommentaire($idComment){
        $pdo = getPdo();
        try{
            $sql = "DELETE FROM Commentaires WHERE idCommentaire = ?";
            $stmt= $pdo->prepare($sql);
            $stmt->execute([$idComment]);
        }catch(Exception $ex){
            console_log("huston, we have a problem here");
        }
    }

    function AjouterCommentaire($commentaire,$authorID,$idImage){
        $pdo = getPDO();
        try{
            $sql = "INSERT into Commentaires (commentaire, authorId, IdImage) VALUES(?, ?,?)";
            $stmt = $pdo -> prepare($sql);
            $stmt-> execute([$commentaire,$authorID, $idImage]);

        }catch (Exception $e) {
            console_log("Huston we have a problem");
            exit;
        }
    }
    function console_log($output, $with_script_tags = true) {
        $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . ');';
        if ($with_script_tags) {
            $js_code = '<script>' . $js_code . '</script>';
        }
        echo $js_code;
    }
    //DELETE from Commentaires where idCommentaire=7
?>