<?php


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

    $request = "SELECT * FROM Label";
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
            $labels[$id] = $name;
        }
    }
    return $labels;
}


?>