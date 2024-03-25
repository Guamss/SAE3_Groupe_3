<?php
$labels = getAllLabels();
if(isset($_SESSION['user']))
{
  $user = unserialize($_SESSION['user']);
  $role = $user->getRole();
  switch($role)
  {
    case 'user':
      $tickets = $user->getTickets();
      if (!empty($tickets))
      {
        echo "
        <div class='container ligne'>
        <!--Tableau de bord-->
        <div class='element-flexible list'>
          <h1>Tableau de bord</h1>
          <input type='text' onkeyup='searchTable()' id='searchInput' placeholder='Chercher un ticket...'>
          <div class='cadre-table-scroll'>
            <table id='item-table' class='table table-scroll'>
              <thead>
              <tr id='head-row'>
                <th>Libellé</th>
                <th class='description-column'>Description</th>
                <th>Demandeur</th>
                <th>Concerné</th>
                <th>Date de création</th>
                <th>Niveau d'urgence</th>
                <th>Technicien</th>
                <th>Etat</th>
                <th>Adresse IP</th>
              </tr>
          </thead>
          <tbody>";
        foreach($tickets as &$ticket)
        {
            $urgence = $ticket->getUrgence();
            $label = $ticket->getLabelID();
            $technician_ID = $ticket->getTechnician();
            $technician = $technician_ID == null ? "Aucun technicien" : User::getLoginByUID($technician_ID);
            $id = $ticket->getConcernee();                   
            echo '
            <tr class="new-row"> 
              <td>'.getLabelNameById($label).'</td>
              <td class="description-column">'.$ticket->getDescription().'</td>
              <td>'.User::getLoginByUID($ticket->getUID()).'</td>
              <td>'.User::getLoginByUID($id).'</td>
              <td>'.date("d/m/Y H:i:s",strtotime($ticket->getDate())).'</td>
              <td>'.$niveauxUrgence[$urgence].'</td>
              <td>'.$technician.'</td>
              <td>'.$ticket->getStatus().'</td>
              <td>'.$ticket->getIP().'</td>
            </tr>';
        }
        echo '</tbody>
        </table>';
      }
      else
      {
        echo "<div class='messages'>
                <h2>Besoin d'aide? </h2>
                <p>Vous n'avez pas encore créé de tickets, vous pouvez en créer en cliquant sur \"Créer un ticket\" dans la navbar.<br>
                Ils seront alors affichés sur cette page.</p>
              </div>";
      }
      break;
    case 'sysadmin' :
      $tickets = Ticket::getClosedTickets();
  if (!empty($tickets))
  {
    echo "
    <div class='container ligne'>
    <!--Tableau de bord-->
    <div class='element-flexible list'>
      <h1>Historique des tickets</h1>
      <input type='text' onkeyup='searchTable()' id='searchInput' placeholder='Chercher un ticket...'>
      <div class='cadre-table-scroll'>
        <table id='item-table' class='table table-scroll'>
          <thead>
          <tr id='head-row'>
            <th>Libellé</th>
            <th class='description-column'>Description</th>
            <th>Demandeur</th>
            <th>Concerné</th>
            <th>Date de création</th>
            <th>Niveau d'urgence</th>
            <th>Technicien</th>
            <th>Etat</th>
            <th>Adresse IP</th>
          </tr>
      </thead>
      <tbody>";
    foreach($tickets as &$ticket)
      {
          $uid = $ticket->getUID();
          $login = User::getLoginByUID($uid);
          $label = $ticket->getLabelID();
          $technician_ID = $ticket->getTechnician();
          $technician = $technician_ID == null ? "Aucun technicien" : User::getLoginByUID($technician_ID);
          $urgence = $ticket->getUrgence();
          $id = $ticket->getConcernee();
          echo '
          <tr class="new-row"> 
            <td>'.getLabelNameById($label).'</td>
            <td class="description-column">'.$ticket->getDescription().'</td>
            <td>'.User::getLoginByUID($ticket->getUID()).'</td>
            <td>'.User::getLoginByUID($id).'</td>
            <td>'.date("d/m/Y H:i:s",strtotime($ticket->getDate())).'</td>
            <td>'.$niveauxUrgence[$urgence].'</td>
            <td>'.$technician.'</td>
            <td>'.$ticket->getStatus().'</td>
            <td>'.$ticket->getIP().'</td>
          </tr>';
      }
      echo '</tbody>
      </table>';
  }
  else
  {
    echo '<div class="messages">
    <h2>L\'historique des tickets est vide</h2>
    </div>';
  }
      break;
    case 'webadmin' :
      $tickets = Ticket::getTicketsWithoutTechnician();
        if (!empty($tickets))
        {
          echo "
          <div class='container ligne'>
          <!--Tableau de bord-->
          <div class='element-flexible list'>
            <h1>Les tickets qui n'ont pas de technicien assignés</h1>
            <input type='text' onkeyup='searchTable()' id='searchInput' placeholder='Chercher un ticket...'>
            <div class='cadre-table-scroll'>
              <table id='item-table' class='table table-scroll'>
                <thead>
                <tr id='head-row'>
                  <th>Libellé</th>
                  <th class='description-column'>Description</th>
                  <th>Demandeur</th>
                  <th>Concerné</th>
                  <th>Date de création</th>
                  <th>Niveau d'urgence</th>
                  <th>Adresse IP</th>
                  <th>Assigner à un technicien</th>
                </tr>
            </thead>
            <tbody>";
            foreach($tickets as &$ticket)
            {
                $uid = $ticket->getUID();
                $label = $ticket->getLabelID();
                $urgence = $ticket->getUrgence();
                $login = User::getLoginByUID($uid);
                $id = $ticket->getConcernee();
                echo '
                <tr class="new-row"> 
                  <td>'.getLabelNameById($label).'</td>
                  <td class="description-column">'.$ticket->getDescription().'</td>
                  <td>'.User::getLoginByUID($ticket->getUID()).'</td>
                  <td>'.User::getLoginByUID($id).'</td>
                  <td>'.date("d/m/Y H:i:s",strtotime($ticket->getDate())).'</td>
                  <td>'.$niveauxUrgence[$urgence].'</td>
                  <td>'.$ticket->getIP().'</td>
                  <td>
                    <form method="POST" action="index.php?uc=dashboard&action=assignerTicketWebadmin">
                    <input type="hidden" name="uid" value="' . $ticket->getUID() . '">
                    <input type="hidden" name="urgence_level" value="' . $ticket->getUrgence() . '">
                    <input type="hidden" name="date" value="' . $ticket->getDate() . '">
                    <input type="hidden" name="label_ID" value="' . $ticket->getLabelID() . '">
                    <input type="hidden" name="status" value="' . $ticket->getStatus() . '">
                    <input type="hidden" name="desc" value="' . $ticket->getDescription() . '">
                    <input type="hidden" name="concernee" value="' . $ticket->getConcernee() . '">
                    <input type="hidden" name="IP" value="' . $ticket->getIP() . '">
                    <button type="submit">+</button>
                    </form>
                  </td>
                </tr>';
            }
        }
        else
        {
          echo "<div class='messages'>
                  <h2>Rien ne s'affiche?</h2>
                  <p>Tout les tickets ont déjà un technicien attribué.</p>
                </div>";                         
        }
        break;
      case 'technician' :
        $tickets = Ticket::getTicketsWithoutTechnician();
        if (!empty($tickets))
        {
          echo "
          <div class='container ligne'>
          <!--Tableau de bord-->
          <div class='element-flexible list'>
            <h1>Les tickets qui n'ont pas de technicien assignés</h1>
            <input type='text' onkeyup='searchTable()' id='searchInput' placeholder='Chercher un ticket...'>
            <div class='cadre-table-scroll'>
              <table id='item-table' class='table table-scroll'>
                <thead>
                <tr id='head-row'>
                  <th>Libellé</th>
                  <th class='description-column'>Description</th>
                  <th>Demandeur</th>
                  <th>Concerné</th>
                  <th>Date de création</th>
                  <th>Niveau d'urgence</th>
                  <th>Adresse IP</th>
                  <th>Prendre en charge</th>
                </tr>
            </thead>
            <tbody>";
            foreach($tickets as &$ticket)
            {
                $uid = $ticket->getUID();
                $label = $ticket->getLabelID();
                $urgence = $ticket->getUrgence();
                $login = User::getLoginByUID($uid);
                $id = $ticket->getConcernee();

                echo '
                <tr class="new-row"> 
                  <td>'.getLabelNameById($label).'</td>
                  <td class="description-column">'.$ticket->getDescription().'</td>
                  <td>'.User::getLoginByUID($ticket->getUID()).'</td>
                  <td>'.User::getLoginByUID($id).'</td>
                  <td>'.date("d/m/Y H:i:s",strtotime($ticket->getDate())).'</td>
                  <td>'.$niveauxUrgence[$urgence].'</td>
                  <td>'.$ticket->getIP().'</td>
                  <td>
                    <form method="POST" action="index.php?uc=dashboard&action=assignerTicketTec">
                    <input type="hidden" name="uid" value="' . $ticket->getUID() . '">
                    <input type="hidden" name="tec" value="' . $ticket->getTechnician() . '">
                    <input type="hidden" name="urgence_level" value="' . $ticket->getUrgence() . '">
                    <input type="hidden" name="date" value="' . $ticket->getDate() . '">
                    <input type="hidden" name="label_ID" value="' . $ticket->getLabelID() . '">
                    <input type="hidden" name="status" value="' . $ticket->getStatus() . '">
                    <input type="hidden" name="desc" value="' . $ticket->getDescription() . '">
                    <input type="hidden" name="concernee" value="' . $ticket->getConcernee() . '">
                    <input type="hidden" name="IP" value="' . $ticket->getIP() . '">
                    <button type="submit">+</button>
                    </form>
                  </td>
                </tr>';
            }
        }
        else
        {
          echo "<div class='messages'>
                  <h2>Rien ne s'affiche?</h2>
                  <p>Tout les tickets ont déjà un technicien attribué.</p>
                </div>";
        }
  }
}
else
{
  $tickets = Ticket::getLastTickets();
  if (!empty($tickets))
  {
    echo "
    <div class='container ligne'>
    <!--Tableau de bord-->
    <div class='element-flexible list'>
      <h1>Les 10 derniers tickets</h1>
      <input type='text' onkeyup='searchTable()' id='searchInput' placeholder='Chercher un ticket...'>
      <div class='cadre-table-scroll'>
        <table id='item-table' class='table table-scroll'>
          <thead>
          <tr id='head-row'>
            <th>Libellé</th>
            <th class='description-column'>Description</th>
            <th>Demandeur</th>
            <th>Concerné</th>
            <th>Date de création</th>
          </tr>
      </thead>
      <tbody>";
    foreach($tickets as &$ticket)
      {
          $uid = $ticket->getUID();
          $login = User::getLoginByUID($uid);
          $label = $ticket->getLabelID();
          $id = $ticket->getConcernee();
          echo '
          <tr class="new-row"> 
            <td>'.getLabelNameById($label).'</td>
            <td class="description-column">'.$ticket->getDescription().'</td>
            <td>'.User::getLoginByUID($ticket->getUID()).'</td>
            <td>'.User::getLoginByUID($id).'</td>
            <td>'.date("d/m/Y H:i:s",strtotime($ticket->getDate())).'</td>
          </tr>';
      }
      echo '</tbody>
      </table>';
  }
  else
  {
      echo "<div class='messages'>
      <h2>Rien ne s'affiche?</h2>
      <p>Aucun ticket n'a été créé jusqu'à présent ou tout les tickets ont été fermé par un technicien.</p>
      </div>";
  }
}
?>
        </table>
    </div>
  </div>
</div>

<script>
function searchTable() {
    var input, filter, found, table, tr, td, i, j;
    input = document.getElementById("searchInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("item-table");
    tr = table.getElementsByClassName("new-row");
    tr_head = document.getElementById("head-row");
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td");
        for (j = 0; j < td.length; j++) {
            if (td[j].innerHTML.toUpperCase().indexOf(filter) > -1) {
                found = true;
            }
        }
        if (found) {
            tr_head.style.display = "";
            tr[i].style.display = "";
            found = false;
        } else {
            tr[i].style.display = "none";
            var nbr_none = 0;
            for (x = 0; x < tr.length; x++)
            {
              if (tr[x].style.display == "none")
              {
                console.log(nbr_none)
                nbr_none ++;
              }
            }
            if (nbr_none == td.length)
            {
              tr_head = document.getElementById("head-row");
              tr_head.style.display = "none";
            }
        }
    }
}
</script>