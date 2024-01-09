<?php

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

function addLabel($name): void
{
    $request = "CALL addLabel(?);";
    $conn = Connexion::getConn();
    $stmt = $conn->prepare($request);
    $stmt->bind_param("s", $name);
    $stmt->execute();
}

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

function getLabelNameById($id): string
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