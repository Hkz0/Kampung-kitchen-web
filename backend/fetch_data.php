<?php
$sql = "SELECT * FROM Recipes";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"]. " - Name: " . $row["name"]. " - Description: " . $row["description"]. "<br>";
    }
} else {
    echo "0 results";
}
$conn->close();
?>

