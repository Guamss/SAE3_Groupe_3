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
                <table>
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
                    if ($label[1] == 0)
                    {
                        echo '
                        <tr>
                            <td>' . $label[0] . '</td>
                            <td>
                                <form method="POST" action="index.php?uc=dashboard&action=modifierLabel">
                                    <input type="hidden" name="label_ID" value="' . $id . '">
                                    <button type="submit">Modifier</button>
                                </form>
                            </td>
                            <td>
                                <form method="POST" action="index.php?uc=dashboard&action=archiverLabel">
                                <input type="hidden" name="label_ID" value="' . $id . '">
                                <button type="submit">X</button>
                            </td>
                        </tr>';
                    }
                }
            }
        ?>
    </tbody>
</table>

<button class="button" onclick="addLabel()">Ajouter un libellé</button>