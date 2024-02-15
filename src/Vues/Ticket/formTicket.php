<div class="form">
    <h1>Création de Ticket</h1>

    <form action="index.php?uc=dashboard&action=validerForm" method="post">
        <label for="label">Libellé du Ticket :</label>
        <select id="label" name="label" required>
            <option value="" disabled selected>Choisissez un libellé</option>
            <?php
            $labels = getAllLabels();
            foreach ($labels as $id => $label) {
                if ($label[1] == 0) {
                    echo "<option value='" . htmlspecialchars($id) . "'>" . htmlspecialchars($label[0]) . "</option>";
                }
            }
            ?>
        </select>
        <br>
        <label for="urgence">Niveau d'Urgence :</label>
        <select id="urgence" name="urgence" required>
            <option value="" disabled selected>Sélectionnez le niveau d'urgence</option>
            <option value="4" style="color: green;">Faible</option>
            <option value="3" style="color: goldenrod;">Moyen</option>
            <option value="2" style="color: orange;">Important</option>
            <option value="1" style="color: red;">Urgent</option>
        </select>
        <br>
        <label for="concernee">Personne concernée par le problème :</label>
        <select id="concernee" name="concernee">
            <?php
            $username = unserialize($_SESSION['user'])->getLogin();
            $concernees = User::getAllUser();
            foreach ($concernees as $id => $concernee) {
                if ($concernee[0] == $username)
                {
                    $userid = htmlspecialchars($id);
                }
                else
                {
                    echo "<option value='" . htmlspecialchars($id) . "'>" . htmlspecialchars($concernee[0]) . "</option>";
                }
            }
            echo "<option value='".$userid."' selected>".$username."</option>";
            ?>
        </select>
        <br>
        <label for="description">Description du problème :</label>
        <textarea id="description" name="description" rows="4" placeholder="En quoi consiste votre problème ?" required oninput="checkForm()"></textarea>
        <br>
        <input type="submit" value="Envoyer" id="submitButton" class="disabled" disabled>
    </form>
</div>

<script>
    function checkForm() {
        var urgence = document.getElementById('urgence').value;
        var label = document.getElementById('label').value;
        var concernee = document.getElementById('concernee').value;
        var description = document.getElementById('description').value;

        var submitButton = document.getElementById('submitButton');

        if (description !== '') {
            submitButton.disabled = false;
            submitButton.classList.remove('disabled');
        } else {
            submitButton.disabled = true;   
            submitButton.classList.add('disabled');
        }
    }
</script>
