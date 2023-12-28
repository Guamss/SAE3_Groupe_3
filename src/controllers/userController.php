<?php
$action = empty($_GET['action']) ?  "formInscription" : $_GET['action'];
switch($action)
{

    case 'formTec':
        if (isset($_SESSION["user"]) && unserialize($_SESSION['user'])->getRole() == 'webadmin') 
        {
            include('Vues/User/formCreationTec.php');
        } 
        else 
        {
            header('Location: index.php'); 
        }
        break;
    
    case 'validerFormTec' :
        if ((isset($_SESSION["user"], $_POST['pwd'], $_POST['login']) && unserialize($_SESSION['user'])->getRole() == 'webadmin'))
        {
            include('Modeles/rc4.php');
            $_POST['pwd'] = rc4Encrypt($_POST['pwd']);
            $conn = Connexion::getConn();
            $stmt = $conn->prepare('INSERT INTO User (role, login, password) VALUES (?, ?, ?);');
            $login = htmlspecialchars($_POST['login']);
            $pwd = htmlspecialchars($_POST['pwd']);
            $role = "technician";
            $stmt->bind_param("sss", $role, $login, $pwd);
            if ($stmt->execute())
            {
                echo "<div class='messages'>
                <h2>Le technicien ".$_POST['login']." a bien été créé.</h2>
                <br>
                <a href='index.php'>Cliquez pour revenir à l'accueil</a>
            </div>";
            }
            else
            {
                header('Location: index.php?uc=inscription&action=errorConnexion');
            }
        }
        else 
        {
            header('Location: index.php');
        }
        break;

    case 'formConnexion' :
        if (empty($_SESSION["user"]))
        {
            include('Vues/User/formConnexion.php');
        }
        else
        {
            header('Location: index.php');
        }
        break;

    case 'validerFormConnexion' :
        if (isset($_POST['login'], $_POST['pwd'], $_POST['captcha'], $_POST['num1'], $_POST['num2']) && (empty($_SESSION["user"]) && !empty($_POST["pwd"]) && !empty($_POST["login"]) && !empty($_POST["num1"]) & !empty($_POST["num2"]) & !empty($_POST["captcha"])))
        {
            include("Modeles/rc4.php");
            $num1 = $_POST["num1"];
            $num2 = $_POST["num2"];
            $captcha = $_POST['captcha'];
            if ($num1+$num2 == $captcha)
            {
                $_POST['pwd'] = rc4Encrypt($_POST['pwd']);
                $conn = Connexion::getConn();
                $stmt = $conn->prepare('SELECT UID, role FROM User WHERE login=? AND password=?;');
                $stmt->bind_param("ss", $_POST['login'], $_POST['pwd']);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows == 1)
                {
                    $row = $result->fetch_assoc();
                    $uid = $row['UID'];
                    $role = $row['role'];
                }
                else
                {
                    header('Location: index.php?uc=inscription&action=errorConnexion');
                }
            }
            else
            {
                header('Location: index.php?uc=inscription&action=errorConnexion');
            }
        }
        else
        {
            header('Location: index.php?uc=inscription&action=errorConnexion');
        }
        $stmt->close();
        if ($result->num_rows == 1)
        {
            $_SESSION['user'] = serialize(new User($uid, $_POST['login'], $role));
        }
        echo "<div class='messages'>
                <h2>Heureux de vous revoir ".$_POST['login']." !</h2>
                <br>
                <a href='index.php'>Cliquez pour revenir à l'accueil.</a>
            </div>";
        break;

    case 'formInscription' :
        if (empty($_SESSION['user']))
        {
            include('Vues/User/formInscription.php');
        }
        else
        {
            header('Location: index.php');
        }
        break;
        
    case 'validerFormInscription' :
        if (isset($_POST['login'], $_POST['pwd'], $_POST['captcha'], $_POST['num1'], $_POST['num2']) && (empty($_SESSION["user"]) && !empty($_POST["pwd"]) && !empty($_POST["login"]) && !empty($_POST["num1"]) & !empty($_POST["num2"]) & !empty($_POST["captcha"])))
        {
            include("Modeles/rc4.php");
            $num1 = $_POST["num1"];
            $num2 = $_POST["num2"];
            $captcha = $_POST['captcha'];
            if ($num1+$num2 == $captcha)
            {
                $_POST['pwd'] = rc4Encrypt($_POST['pwd']);
                $conn = Connexion::getConn();
                $stmt = $conn->prepare('INSERT INTO User (role, login, password) VALUES (?, ?, ?);');
                $login = htmlspecialchars($_POST['login']);
                $pwd = htmlspecialchars($_POST['pwd']);
                $role = "user";
                $stmt->bind_param("sss", $role, $login, $pwd);
                if ($stmt->execute())
                {
                    $stmt = $conn->prepare('SELECT UID FROM User WHERE login=? AND password=? AND role=?');
                    $stmt->bind_param("sss", $login, $pwd, $role);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows == 1)
                    {
                        $row = $result->fetch_assoc();
                        $uid = $row['UID'];
                    }
                    $stmt->close();
                    $_SESSION['user'] = serialize(new User($uid, $login, $role));
                    echo "<div class='messages'>
                            <h2>Votre compte a bien été créé !</h2>
                            <br>
                            <a href='index.php'>Cliquez pour revenir à l'accueil.</a>
                        </div>";
                }
                else
                {
                    if ($conn->errno == 1062)
                    {
                        echo "<div class='messages'>Erreur : Le login est déjà utilisé. Veuillez en choisir un autre.</div>";
                    }
                    else
                    {
                        echo "<div class='messages'>Une erreur s'est produite</div>";
                    }
                }
            }
            else
            {
                header('Location: index.php?uc=inscription&action=errorInscription');
            }
        }
        else
        {
            header('Location: index.php?uc=inscription&action=errorInscription');
        }

        break;
        
    case 'errorInscription':
        echo "<div class='messages'>
                <p style='color:red;'>Une erreure est survenue, vérifiez bien votre saisie !</p>
            </div>";
        include('Vues/User/formInscription.php');
        break;
    
    case 'errorConnexion':
        echo "<div class='messages'>
                <p style='color:red;'>Nous n'avons pas trouvé votre utilisateur !</p>
            </div>";
        include('Vues/User/formConnexion.php');
        break;

    default :
        include('Vues/accueil.php');
        break;
        
}