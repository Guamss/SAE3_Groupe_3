<div class="form">
    <h1>Assigner un technicien</h1>
    <form action="index.php?uc=dashboard&action=validerFormWebadmin" method="POST">
        <?php
            $niveauxUrgence = array(
                1 => 'Urgent',
                2 => 'Important',
                3 => 'Moyen',
                4 => 'Faible');
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
        ?>
        <label for="tec">Sélectionnez un technicien :</label>
        <select name="tec" id="tec" required>
        <option value="" disabled selected>Aucun technicien sélectionné</option>
            <?php
                $ticket = new Ticket($_POST['uid'], $_POST['urgence_level'], $_POST['Label_ID'], $_POST['desc'], $_POST['date'], $_POST['status'], $_POST['tec']);
                $tecs = User::getAllTechnicians();
                foreach ($tecs as $tec) {
                    echo '<option value="' . $tec->getUID() . '">' . $tec->getLogin() . '</option>';
                }
            ?>
        </select>
        
        <input type="submit" value="Envoyer">
    </form>
</div>
