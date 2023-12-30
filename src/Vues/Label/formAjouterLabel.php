<?php
echo'
<div class="form" >
    <h1>Ajouter un libellé</h1>
    <form action="index.php?uc=dashboard&action=validerAddLabel" method="POST">
        <label for="name">Nom du libellé</label>
        <input type="text" id="name" name="name" required>
        <input type="submit" value="Ajouter">
    </form>
</div>';
?>