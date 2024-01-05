<?php

if (isset($_SESSION['user']) && unserialize($_SESSION['user'])->getRole()=='technician')
{
    $user = unserialize($_SESSION['user']);
    $tickets = Ticket::getTicketsWithTechnician($user->getUID());
    if (!empty($tickets))
    {
    echo "
    <div class='container ligne'>
    <!--Tableau de bord-->
    <div class='element-flexible list'>
        <h1>Vos tickets attribués</h1>
        <div class='cadre-table-scroll'>
        <table class='table table-scroll'>
            <thead>
            <tr>
            <th>Libellé</th>
            <th class='description-column'>Description</th>
            <th>Demandeur</th>
            <th>Date de création</th>
            <th>Niveau d'urgence</th>
            <th>Etat</th>
            <th>Adresse IP</th>
            <th>Modifier l'état</th>
            </tr>
        </thead>
        <tbody>";
        foreach($tickets as &$ticket)
        {
                $uid = $ticket->getUID();
                $label = $ticket->getLabelID();
                $urgence = $ticket->getUrgence();
                $login = User::getLoginByUID($uid);
                echo '
                <tr> 
                    <td>'.getLabelNameById($label).'</td>
                    <td class="description-column">'.$ticket->getDescription().'</td>
                    <td>'.$login.'</td>
                    <td>'.$ticket->getDate().'</td>
                    <td>'.$niveauxUrgence[$urgence].'</td>
                    <td>'.$ticket->getStatus().'</td>
                    <td>'.$ticket->getIP().'</td>
                    <td>
                    <form method="POST" action="index.php?uc=dashboard&action=modifierEtat">
                    <input type="hidden" name="uid" value="' . $ticket->getUID() . '">
                    <input type="hidden" name="tec" value="' . $ticket->getTechnician() . '">
                    <input type="hidden" name="urgence_level" value="' . $ticket->getUrgence() . '">
                    <input type="hidden" name="date" value="' . $ticket->getDate() . '">
                    <input type="hidden" name="label_ID" value="' . $ticket->getLabelID() . '">
                    <input type="hidden" name="status" value="' . $ticket->getStatus() . '">
                    <input type="hidden" name="desc" value="' . $ticket->getDescription() . '">
                    <input type="hidden" name="concernee" value="' . $ticket->getConcernee() . '">
                    <input type="hidden" name="IP" value="' . $ticket->getIP() . '">
                    
                    <button type="submit">Modifier</button>
                    </form>
                    </td>
                </tr>';
        }
        echo "</tbody>
        </table>
        </div>
        </div>";
    }
    else
    {
        echo "
        <div class='messages'>
            <h2>Vous n'avez aucun ticket attribué</h2>
        </div>";
    }
}
?>