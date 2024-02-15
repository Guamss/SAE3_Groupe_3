<?php
echo'
<div class="form" >
    <h1>Modifier le libellé</h1>
    <form action="index.php?uc=dashboard&action=validerModifierLabel" method="POST">
    <input type="hidden" name="label_ID" value="' . $_POST['label_ID'] . '">
        <label for="name">Nouveau nom du libellé</label>
        <input type="text" id="name" name="name" required>
        <input type="submit" value="Modifier">
    </form>
</div>';
?>