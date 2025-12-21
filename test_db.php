<?php
$servername = "localhost";
$username = "pi";
$password = "cambiar0102";
$dbname = "stock";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

// Run a test query
$sql = "SELECT * FROM orders";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"]. " - Name: " . $row["customer_name"]. " - Amount: " . $row["net_amount"]. "<br>";
    }
} else {
    echo "0 results";
}
$conn->close();
?>

