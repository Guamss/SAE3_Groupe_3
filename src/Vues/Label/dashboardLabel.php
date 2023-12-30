<script>
    function addLabel() 
        {
            window.location.href = "index.php?uc=dashboard&action=addLabel";
        }
</script>
        <?php
            $labels = getAllLabels();
            if (!empty($labels))
            {
                echo '
                <div class="element-flexible-lib list-lib">
                <div class="cadre-table-scroll">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Libellé</th>
                            <th>Modifier</th>
                            <th>Archiver</th>
                        </tr>
                    </thead>
                    <tbody>';
                foreach($labels as $id => $label)
                {
                        echo '
                        <tr>
                            <td>' . $label[0] . '</td>
                            <td>
                                <form method="POST" action="index.php?uc=dashboard&action=modifierLabel">
                                    <input type="hidden" name="label_ID" value="' . $id . '">
                                    <button type="submit" style="border-radius: 10px;">
                                        <span class="material-symbols-outlined">
                                            edit
                                        </span>
                                    </button>
                                </form>
                            </td>
                            <td>
                                <form method="POST" action="index.php?uc=dashboard&action=archiverLabel">
                                <input type="hidden" name="label_ID" value="' . $id . '">
                                <button type="submit" style="border-radius: 10px;">
                                    <span class="material-symbols-outlined">
                                        check_circle
                                    </span>
                                </button>
                            </td>
                        </tr>';
                }
            }
        ?>
    </tbody>
</table>
</div>
</div>
<button class="element-flexible-lib button-lib" onclick="addLabel()">Ajouter un libellé</button>
