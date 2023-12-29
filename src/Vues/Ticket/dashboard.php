  <!--Script d'affichage informations relative au ticket courant-->
  <title>Tableau de bord</title>
  <script>
    window.onload = init;

function init()
{
    div_mere = document.getElementsByClassName("container ligne");
    div_element_flexible = document.createElement("div");
    div_element_flexible.setAttribute("class", "element-flexible pannel");
    tr_list = document.getElementsByTagName("tr");
    tr_head_enfants = tr_list[0].childNodes;
    for (let i = 1; i<tr_list.length; i++)
    {
        tr_list[i].onclick = traitementClick;
    }
}

function traitementClick() {
    while (div_element_flexible.firstChild) {
        div_element_flexible.removeChild(div_element_flexible.firstChild);
    }

    const enfants_this = this.childNodes;

    let h1 = document.createElement("h1");
    h1.appendChild(document.createTextNode("Informations"));
    div_element_flexible.appendChild(h1);

    for (let i = 3; i < enfants_this.length; i += 2) {
        let actual_text = enfants_this[i].innerText.trim();
        let actual_titre = tr_head_enfants[i].innerText.trim();

        if (actual_titre !== "Prendre en charge") {
            let h4 = document.createElement("h4");
            let p = document.createElement("p");

            p.appendChild(document.createTextNode(actual_text));
            h4.appendChild(document.createTextNode(actual_titre));

            let actual_div = document.createElement("div");
            actual_div.setAttribute("class", "infos");
            actual_div.appendChild(h4);
            actual_div.appendChild(p);
            div_element_flexible.appendChild(actual_div);

            for (let j = 0; j < 2; j++) {
                let br = document.createElement("br");
                div_element_flexible.appendChild(br);
            }
        }
    }

    div_mere[0].appendChild(div_element_flexible);
}

  </script>
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
                          <div class='cadre-table-scroll'>
                            <table class='table table-scroll'>
                              <thead>
                              <tr>
                                <th>Libellé</th>
                                <th class='description-column'>Description</th>
                                <th>Demandeur</th>
                                <th>Date de création</th>
                                <th>Niveau d'urgence</th>
                                <th>Technicien</th>
                                <th>Etat</th>
                              </tr>
                          </thead>
                          <tbody>";
                        foreach($tickets as &$ticket)
                        {
                          if ($ticket->getStatus() != "Fermé")
                          {
                            $urgence = $ticket->getUrgence();
                            $label = $ticket->getLabelID();
                            $technician_ID = $ticket->getTechnician();
                            $technician = $technician_ID == null ? "Aucun technicien" : User::getLoginByUID($technician_ID);
                            echo '
                            <tr> 
                              <td>'.getLabelNameById($label).'</td>
                              <td class="description-column">'.$ticket->getDescription().'</td>
                              <td>'.$user->getLogin().'</td>
                              <td>'.$ticket->getDate().'</td>
                              <td>'.$niveauxUrgence[$urgence].'</td>
                              <td>'.$technician.'</td>
                              <td>'.$ticket->getStatus().'</td>
                            </tr>';
                          }
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
                    
                    case 'webadmin' :
                      $tickets = Ticket::getTicketsWithoutTechnician();
                        if (!empty($tickets))
                        {
                          echo "
                          <div class='container ligne'>
                          <!--Tableau de bord-->
                          <div class='element-flexible list'>
                            <h1>Les tickets qui n'ont pas de technicien assignés</h1>
                            <div class='cadre-table-scroll'>
                              <table class='table table-scroll'>
                                <thead>
                                <tr>
                                  <th>Libellé</th>
                                  <th class='description-column'>Description</th>
                                  <th>Demandeur</th>
                                  <th>Date de création</th>
                                  <th>Niveau d'urgence</th>
                                  <th>Prendre en charge</th>
                                </tr>
                            </thead>
                            <tbody>";
                            foreach($tickets as &$ticket)
                            {
                              if ($ticket->getStatus() != "Fermé")
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
                                  <td>
                                    <form method="POST" action="index.php?uc=dashboard&action=assignerTicketWebadmin">
                                    <input type="hidden" name="uid" value="' . $ticket->getUID() . '">
                                    <input type="hidden" name="urgence_level" value="' . $ticket->getUrgence() . '">
                                    <input type="hidden" name="date" value="' . $ticket->getDate() . '">
                                    <input type="hidden" name="label_ID" value="' . $ticket->getLabelID() . '">
                                    <input type="hidden" name="status" value="' . $ticket->getStatus() . '">
                                    <input type="hidden" name="desc" value="' . $ticket->getDescription() . '">
                                    <button type="submit">+</button>
                                    </form>
                                  </td>
                                </tr>';
                              }
                            }
                        }
                        else
                        {
                          echo "<h2>Rien ne s'affiche?</h2>
                          <p>Tout les tickets ont déjà un technicien attribué.</p>";
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
                            <div class='cadre-table-scroll'>
                              <table class='table table-scroll'>
                                <thead>
                                <tr>
                                  <th>Libellé</th>
                                  <th class='description-column'>Description</th>
                                  <th>Demandeur</th>
                                  <th>Date de création</th>
                                  <th>Niveau d'urgence</th>
                                  <th>Prendre en charge</th>
                                </tr>
                            </thead>
                            <tbody>";
                            foreach($tickets as &$ticket)
                            {
                              if ($ticket->getStatus() != "Fermé")
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
                                  <td>
                                    <form method="POST" action="index.php?uc=dashboard&action=assignerTicketTec">
                                    <input type="hidden" name="uid" value="' . $ticket->getUID() . '">
                                    <input type="hidden" name="tec" value="' . $ticket->getTechnician() . '">
                                    <input type="hidden" name="urgence_level" value="' . $ticket->getUrgence() . '">
                                    <input type="hidden" name="date" value="' . $ticket->getDate() . '">
                                    <input type="hidden" name="label_ID" value="' . $ticket->getLabelID() . '">
                                    <input type="hidden" name="status" value="' . $ticket->getStatus() . '">
                                    <input type="hidden" name="desc" value="' . $ticket->getDescription() . '">
                                    <button type="submit">+</button>
                                    </form>
                                  </td>
                                </tr>';
                              }
                            }
                        }
                        else
                        {
                          echo "<h2>Rien ne s'affiche?</h2>
                          <p>Tout les tickets ont déjà un technicien attribué.</p>";
                        }
                  }
                }
                else
                {
                  echo "
                  <div class='container ligne'>
                  <!--Tableau de bord-->
                  <div class='element-flexible list'>
                    <h1>Les 10 derniers tickets</h1>
                    <div class='cadre-table-scroll'>
                      <table class='table table-scroll'>
                        <thead>
                        <tr>
                          <th>Libellé</th>
                          <th class='description-column'>Description</th>
                          <th>Demandeur</th>
                          <th>Date de création</th>
                          <th>Niveau d'urgence</th>
                          <th>Technicien</th>
                          <th>Etat</th>
                        </tr>
                    </thead>
                    <tbody>";
                  $tickets = Ticket::getLastTickets();
                  foreach($tickets as &$ticket)
                    {
                      if ($ticket->getStatus() != "Fermé")
                      {
                        $uid = $ticket->getUID();
                        $login = User::getLoginByUID($uid);
                        $label = $ticket->getLabelID();
                        $technician_ID = $ticket->getTechnician();
                        $technician = $technician_ID == null ? "Aucun technicien" : User::getLoginByUID($technician_ID);
                        $urgence = $ticket->getUrgence();
                        echo '
                        <tr> 
                          <td>'.getLabelNameById($label).'</td>
                          <td class="description-column">'.$ticket->getDescription().'</td>
                          <td>'.$login.'</td>
                          <td>'.$ticket->getDate().'</td>
                          <td>'.$niveauxUrgence[$urgence].'</td>
                          <td>'.$technician.'</td>
                          <td>'.$ticket->getStatus().'</td>
                        </tr>';
                      }
                    }
                    echo '</tbody>
                    </table>';
                }
              ?>
          </table>
      </div>
    </div>
  </div>
