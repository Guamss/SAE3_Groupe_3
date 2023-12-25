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

function traitementClick()
{
    while (div_element_flexible.firstChild)
    {
        div_element_flexible.removeChild(div_element_flexible.firstChild);
    }
    const enfants_this = this.childNodes

    const div_info_titre = document.createElement("div");
    div_info_titre.setAttribute("class", "infos");

    const div_info_desc = document.createElement("div");
    div_info_desc.setAttribute("class", "infos");

    const div_info_demandeur = document.createElement("div");
    div_info_demandeur.setAttribute("class", "infos");

    const div_info_date = document.createElement("div");
    div_info_date.setAttribute("class", "infos");

    const div_info_etat = document.createElement("div");
    div_info_etat.setAttribute("class", "infos");

    const div_info_urgence = document.createElement("div");
    div_info_etat.setAttribute("class", "infos");

    let dict_div =
        {1 : div_info_titre,
            3 : div_info_desc,
            5 : div_info_demandeur,
            7 : div_info_date,
            9 : div_info_etat,
            11 : div_info_etat};
    let h1 = document.createElement("h1");
    h1.appendChild(document.createTextNode("Informations"));
    div_element_flexible.appendChild(h1);
    for (let i = 3; i<enfants_this.length; i+=2)
    {
        let actual_text = enfants_this[i].innerText;
        let actual_div = dict_div[i]
        let actual_titre = tr_head_enfants[i].innerText;
        let h4 = document.createElement("h4");
        let p = document.createElement("p");

        p.appendChild(document.createTextNode(actual_text));
        h4.appendChild(document.createTextNode(actual_titre));
        actual_div.appendChild(h4);
        actual_div.appendChild(p);
        div_element_flexible.appendChild(actual_div);
        for (let j = 0; j<2; j++)
        {
            let br = document.createElement("br");
            div_element_flexible.appendChild(br);
        }
    }
    div_mere[0].appendChild(div_element_flexible)
}
  </script>
              <?php
                if(isset($_SESSION['user']))
                {
                  $user = unserialize($_SESSION['user']);
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
                            <th>Etat</th>
                          </tr>
                      </thead>
                      <tbody>";
                    foreach($tickets as &$ticket)
                    {
                      $urgence = $ticket->getUrgence();
                      $label = $ticket->getLabelID();
                      $niveauxUrgence = array(
                        1 => 'Urgent',
                        2 => 'Important',
                        3 => 'Moyen',
                        4 => 'Faible');
                      echo '
                      <tr> 
                        <td>'.getLabelNameById($label).'</td>
                        <td class="description-column">'.$ticket->getDescription().'</td>
                        <td>'.$user->getLogin().'</td>
                        <td>'.$ticket->getDate().'</td>
                        <td>'.$ticket->getStatus().'</td>
                        <td>'.$niveauxUrgence[$urgence].'</td>
                      </tr>';
                    }
                    echo '</tbody>
                    </table>';
                  }
                  else
                  {
                    echo "
                    <h2>Besoin d'aide? </h2>
                    <p>Vous n'avez créé aucun ticket jusqu'à présent, vous pouvez créer des tickets et ceci seront affiché sur cette page</p>";
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
                          <th>Etat</th>
                        </tr>
                    </thead>
                    <tbody>";
                  $tickets = Ticket::getLastTickets();
                  foreach($tickets as &$ticket)
                    {
                      $uid = $ticket->getUID();
                      $login = User::getLoginByUID($uid);
                      $label = $ticket->getLabelID();

                      $urgence = $ticket->getUrgence();
                      $niveauxUrgence = array(
                        1 => 'Urgent',
                        2 => 'Important',
                        3 => 'Moyen',
                        4 => 'Faible');
                      echo '
                      <tr> 
                        <td>'.getLabelNameById($label).'</td>
                        <td class="description-column">'.$ticket->getDescription().'</td>
                        <td>'.$login.'</td>
                        <td>'.$ticket->getDate().'</td>
                        <td>'.$ticket->getStatus().'</td>
                        <td>'.$niveauxUrgence[$urgence].'</td>
                      </tr>';
                    }
                    echo '</tbody>
                    </table>';
                }
              ?>
          </table>
      </div>
    </div>
  </div>
