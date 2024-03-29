<?php
$action=$_GET['action'];
switch($action)
{
    case 'list' :
        include('Vues/Ticket/dashboard.php');
        break;
    
    case 'form' :
        if (isset($_SESSION['user']) && unserialize($_SESSION['user'])->getRole() == 'user')
        {
            include('Vues/Ticket/formTicket.php');
        }
        else
        {
            header("Location: index.php");
        }
        break;
        
    case 'validerForm' :
        if (isset($_POST['label'], $_POST['description'], $_POST['urgence'], $_POST['concernee']) & isset($_SESSION['user']))
        {
            $urgence = htmlspecialchars($_POST['urgence']);
            $desc = htmlspecialchars($_POST['description']);
            $label = $_POST['label'];
            $concernee = htmlspecialchars($_POST['concernee']);
            $user = unserialize($_SESSION['user']);
            $ticket = new Ticket($user->getUID(), $urgence, $label, $concernee, $desc, $_SERVER['REMOTE_ADDR']);
            $user->createTicket($ticket);
            $_SESSION['user'] = serialize($user);
        }
        else
        {
            header('Location: index.php?uc=dashboard&action=error');
        }
        echo "<div class='messages'>
                <h2>Votre ticket a bien été créé !</h2>
                <br>
                <a href='index.php?uc=dashboard&action=list'>Cliquez pour revenir au tableau de bord</a>
            </div>";
        break;
    
    case 'assignerTicketTec':
        if (isset($_POST['uid'], $_POST['tec'], $_POST['urgence_level'], $_POST['date'], $_POST['label_ID'], $_POST['status'], $_POST['desc'], $_POST['concernee'], $_POST['IP']) && unserialize($_SESSION['user'])->getRole() == 'technician')
        {
            $tec = unserialize($_SESSION['user']);
            $ticket = new Ticket($_POST['uid'], $_POST['urgence_level'], $_POST['label_ID'], $_POST['concernee'], $_POST['desc'], $_POST['IP'], $_POST['date'], $_POST['status'], $_POST['tec']);
            $ticket->setTechnician($tec->getUid());
            echo "<div class='messages'>
                    <p style='color: green;'>Un ticket d'urgence ".$niveauxUrgence[$ticket->getUrgence()]." vous a été attribué!</p>
                    </div>";
            include('Vues/Ticket/dashboard.php');
   
            }
        else
        {
            header('Location : index.php');
        }
        break;

    case 'validerFormWebadmin':
        if (isset($_POST['uid'], $_POST['urgence_level'], $_POST['date'], $_POST['label_ID'], $_POST['status'], $_POST['desc'], $_POST['concernee'], $_POST['IP']) && unserialize($_SESSION['user'])->getRole() == 'webadmin' && !empty($_POST['tec']))
        {
            $ticket = new Ticket($_POST['uid'], $_POST['urgence_level'], $_POST['label_ID'], $_POST['concernee'], $_POST['desc'], $_POST['IP'], $_POST['date'], $_POST['status']);
            $ticket->setTechnician($_POST['tec']);
            echo "<div class='messages'>
                    <p style='color: green;'>Un ticket d'urgence ".$niveauxUrgence[$ticket->getUrgence()]." a été attribué à ".User::getLoginByUID($ticket->getTechnician())."</p>
                </div>";
            include('Vues/Ticket/dashboard.php');
        }
        else
        {
            header('Location: index.php');
        }
        break;
    
    case 'assignerTicketWebadmin':
        if (isset($_POST['uid'], $_POST['urgence_level'], $_POST['date'], $_POST['label_ID'], $_POST['status'], $_POST['desc'], $_POST['concernee'], $_POST['IP']) && unserialize($_SESSION['user'])->getRole() == 'webadmin')
        {
            include("Vues/User/formAssignerTec.php");
        } 
        else
        {
            header('Location: index.php');
        }
        break;

    case 'myTickets' :
        if (isset($_SESSION['user']) && unserialize($_SESSION['user'])->getRole()=='technician')
        {
            include ('Vues/Ticket/myTickets.php');
        }
        else
        {
            header('Location: index.php');
        }
        break;
    
    case 'validerModifierEtat': 
        if (isset($_POST['uid'], $_POST['urgence_level'], $_POST['date'], $_POST['label_ID'], $_POST['status'], $_POST['desc'], $_POST['tec'], $_POST['concernee'], $_POST['IP']) && unserialize($_SESSION['user'])->getRole() == 'technician' && !empty($_POST['etat']))
        {
            $ticket = new Ticket($_POST['uid'], $_POST['urgence_level'], $_POST['label_ID'], $_POST['concernee'], $_POST['desc'], $_POST['IP'], $_POST['date'], $_POST['status'], $_POST['tec']);
            $ticket->setStatus($_POST['etat']);
            echo "<p style='color: green;'>Un ticket d'urgence ".$niveauxUrgence[$ticket->getUrgence()]." a été modifié pour l'état ".$_POST['etat']."</p>";
            include('Vues/Ticket/myTickets.php');
        }
        else
        {
            header('Location: index.php');
        }
        break;
    case 'modifierEtat' :
        if (isset($_POST['uid'], $_POST['urgence_level'], $_POST['date'], $_POST['label_ID'], $_POST['status'], $_POST['desc'], $_POST['concernee'], $_POST['IP']) && unserialize($_SESSION['user'])->getRole() == 'technician')
        {
            include('Vues/Ticket/formModifierEtat.php');
        }
        else
        {
            header('Location: index.php');
        }
        break;

    case 'labels' :
        if (isset($_SESSION['user']) && unserialize($_SESSION['user'])->getRole()=='webadmin')
        {
            include('Vues/Label/dashboardLabel.php');
        }
        else
        {
            header('Location: index.php');
        }
        break;
    
    case 'validerModifierLabel' :
        if (isset($_SESSION['user'], $_POST['name'], $_POST['label_ID']) && unserialize($_SESSION['user'])->getRole()=='webadmin')
        {
            updateLabel($_POST['name'], $_POST['label_ID']);
            include('Vues/Label/dashboardLabel.php');
            echo "<p style='color:green'>Ce label a comme nouveau nom ".$_POST['name']." </p>";
        }
        else
        {
            header('Location: index.php');
        }
        break;
        
    case 'modifierLabel':
        if (isset($_SESSION['user'], $_POST['label_ID']) && unserialize($_SESSION['user'])->getRole()=='webadmin')
        {
            include("Vues/Label/formModifierLabel.php");
        }
        else
        {
            header('Location: index.php');
        }
        break;

    case 'archiverLabel':
        if (isset($_SESSION['user'], $_POST['label_ID']) && unserialize($_SESSION['user'])->getRole()=='webadmin')
        {
            archive($_POST['label_ID']);
            echo "<div class='messages'>
                    <p style='color:green'>Ce libellé a bien été archivé</p>
                </div>";
            include('Vues/Label/dashboardLabel.php');
            
        }
        else
        {
            header('Location: index.php');
        }
        break;
    
    case 'validerAddLabel' :
        if (isset($_SESSION['user'], $_POST['name']) && unserialize($_SESSION['user'])->getRole()=='webadmin' & !empty($_POST['name']))
        {
            $name = htmlspecialchars($_POST['name']);
            addLabel($name);
            echo "<div class='messages'>
                    <p style='color:green'>Le libellé ".$name." a bien été créé</p>
                </div>";
            include("Vues/Label/dashboardLabel.php");
        }
        else
        {
            header('Location: index.php');
        }
        break;
    
    case 'addLabel' :
        if (isset($_SESSION['user']) && unserialize($_SESSION['user'])->getRole()=='webadmin')
        {
            include('Vues/Label/formAjouterLabel.php');
        }
        else
        {
            header('Location: index.php');
        }
        break;

    case 'error' :
        if (isset($_SESSION['user']))
        {
            include('Vues/Ticket/formTicket.php');
            echo "<div class='messages'>
                    <p style='color:red;'>Une erreur est survenue, vérifiez bien votre saisie !</p>
                </div>";
        }
        else
        {
            header('Location: index.php');
        }
        break;
    
    default :
        include('Vues/accueil.php');
        break;
}