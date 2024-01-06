<?php
echo'
<div class="form" >
    <h1>Ajouter un libellé</h1>
    <form action="index.php?uc=dashboard&action=validerAddLabel" method="POST">
        <label for="name">Nom du libellé</label>
        <input type="text" id="name" name="name"  oninput="checkForm()" required>
        <input type="submit" class="disabled" id="submitButton" value="Ajouter" disabled>
    </form>
</div>
';
?>
<script>
    function checkForm() {
        var name = document.getElementById('name').value;

        var submitButton = document.getElementById('submitButton');

        if (name.trim() !== '') 
        {
            submitButton.disabled = false;
            submitButton.classList.remove('disabled');
        } 
        else 
        {
            submitButton.disabled = true;
            submitButton.classList.add('disabled');
        }
    }
</script>