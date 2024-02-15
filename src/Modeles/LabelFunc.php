<?php
/**
 * Archive le label avec l'ID spécifié en mettant le champ 'archivé' à 1.
 *
 * @param int $id L'ID du label à archiver.
 *
 * @return void
 */
function archive($id): void
{
    $request = "UPDATE Label
                SET archivé = 1
                WHERE Label_ID = ?";
    $conn = Connexion::getConn();
    $stmt = $conn->prepare($request);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

/**
 * Ajoute un nouveau label avec le nom spécifié en appelant la procédure addLabel.
 *
 * @param string $name Le nom du label à ajouter.
 *
 * @return void
 */
function addLabel($name): void
{
    $request = "CALL addLabel(?);";
    $conn = Connexion::getConn();
    $stmt = $conn->prepare($request);
    $stmt->bind_param("s", $name);
    $stmt->execute();
}

/**
 * Met à jour le nom du label avec l'ID spécifié.
 *
 * @param string $name Le nouveau nom du label.
 * @param int    $id   L'ID du label à mettre à jour.
 *
 * @return void
 */
function updateLabel($name, $id): void
{
    $request = "UPDATE Label
                SET name = ?
                WHERE Label_ID = ?";
    $conn = Connexion::getConn();
    $stmt = $conn->prepare($request);
    $stmt->bind_param("si", $name, $id);
    $stmt->execute();
}

/**
 * Récupère le nom du label associé à l'ID spécifié.
 *
 * @param int $id L'ID du label à récupérer.
 *
 * @return string|null Le nom du label, ou null si l'ID n'existe pas.
 */
function getLabelNameById($id): ?string
{
    $request = "SELECT name FROM Label WHERE Label_ID=?";
    $conn = Connexion::getConn();
    $stmt = $conn->prepare($request);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1)
    {
        while($row = $result->fetch_assoc())
        {
            $name = $row['name'];
        }
        return $name;
    }
    return null;
}

/**
 * Récupère tous les labels non archivés de la base de données.
 *
 * @return array Un tableau associatif contenant les informations sur chaque label.
 *               La clé est l'ID du label, la valeur est un tableau contenant le nom et l'état d'archivage.
 */
function getAllLabels(): array
{
    $labels = array();

    $request = "SELECT * FROM Label
                WHERE archivé=0";
    $conn = Connexion::getConn();
    $stmt = $conn->prepare($request);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0)
    {
        while($row = $result->fetch_array())
        {
            $name = $row['name'];
            $id = $row['Label_ID'];
            $archive = $row['archivé'];
            $labels[$id] = array($name, $archive);
        }
    }
    return $labels;
}
?>
