<div class="form">
    <h1>Création de Ticket</h1>

    <form action="index.php?uc=dashboard&action=validerForm" method="post">
        <label for="label">Libellé du Ticket :</label>
            <select id="label" name="label" required>
                <option value="" disabled selected>Choisissez un libellé</option>
                <?php
                $labels = getAllLabels();
                var_dump($labels);
                foreach ($labels as $id => $name)
                {
                    echo "<option value='" . htmlspecialchars($id) . "'>" . htmlspecialchars($name) . "</option>";
                }
                ?>
            </select>
        <br>
        <label for="urgence">Niveau d'Urgence :</label>
            <select id="urgence" name="urgence" required>
                <option value="" disabled selected>Sélectionnez le niveau d'urgence</option>
                <option value="4" style="color: green;">Faible</option>
                <option value="3" style="color:goldenrod;">Moyen</option>
                <option value="2" style="color: orange;">Important</option>
                <option value="1" style="color: red;">Urgent</option>
            </select>
        <br>
        <label for="description">Description du problème :</label>
            <textarea id="description" name="description" rows="4" placeholder="En quoi consiste votre problème ?" required></textarea>
        <br>
        <input type="submit" value="Envoyer">
    </form>
</div>
