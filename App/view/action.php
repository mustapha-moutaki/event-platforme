<?php
$conn = mysqli_connect("localhost", "root", "", "events_db");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['input'])) {
    $search = $_POST['input'];
    
    $query = "SELECT * FROM events WHERE title LIKE '%$search%'";
} else {
    
    $query = "SELECT * FROM events WHERE 0";
}

$result = mysqli_query($conn, $query);

if (!$result) {
    echo "Error: " . mysqli_error($conn);
}

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='event'>";
        echo "<h3>" . $row['title'] . "</h3>";
        echo "<p>" . $row['description'] . "</p>";
        echo "</div>";
    }
} else {
}
?>