<div class="form">
    <h1>Modifier l'état du ticket</h1>
    <form action="index.php?uc=dashboard&action=validerModifierEtat" method="POST">
        <?php
            echo "<p>Informations relatives au ticket :</p><br>
            <ul style='list-style: none;'>
                <li><strong>Demandeur</strong> : ".User::getLoginByUID($_POST['uid'])."</li>
                <li><strong>Urgence</strong> : ".$niveauxUrgence[$_POST['urgence_level']]."</li>
                <li><strong>Libellé</strong> : ".getLabelNameById($_POST['label_ID'])."</li>
                <li><strong>Description</strong> : ".$_POST['desc']."</li>
            </ul>";
            echo '<input type="hidden" name="uid" value="' . $_POST['uid'] . '">';
            echo '<input type="hidden" name="urgence_level" value="' . $_POST['urgence_level'] . '">';
            echo '<input type="hidden" name="label_ID" value="' . $_POST['label_ID'] . '">';
            echo '<input type="hidden" name="desc" value="' . $_POST['desc'] . '">';
            echo '<input type="hidden" name="date" value="' . $_POST['date'] . '">';
            echo '<input type="hidden" name="status" value="' . $_POST['status'] . '">';
            echo '<input type="hidden" name="tec" value="' . $_POST['tec'] . '">';
            echo '<input type="hidden" name="concernee" value="' . $_POST['concernee'] . '">';
            echo '<input type="hidden" name="IP" value="' . $_POST['IP'] . '">';

            echo "
        <label for='etat'>Modifier l'état du Ticket :</label>
        <select name='etat' id='etat' required>
        <option value='' disabled selected>".$_POST['status']."</option>";
        
                $ticket = new Ticket($_POST['uid'], $_POST['urgence_level'], $_POST['Label_ID'], $_POST['concernee'], $_POST['desc'], $_POST['IP'], $_POST['date'], $_POST['status'], $_POST['tec']);
                foreach ($states as $state) 
                {
                    if ($state != "Ouvert" && $_POST['status'] != $state)
                    { 
                        echo '<option value="' . $state . '">' . $state . '</option>';
                    }
                }
            ?>
        </select>
        
        <input type="submit" value="Modifier">
    </form>
</div>
