<?php
echo'
<div class="form" >
    <h1>Modifier le Label</h1>
    <form action="index.php?uc=dashboard&action=validerModifierLabel" method="POST">
    <input type="hidden" name="label_ID" value="' . $_POST['label_ID'] . '">
        <label for="name">Nouveau Nom du Label</label>
        <input type="text" id="name" name="name" required>
        <input type="submit" value="Modifier">
    </form>
</div>';
?>