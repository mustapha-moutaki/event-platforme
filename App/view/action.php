<?php
$conn = mysqli_connect("localhost", "root", "", "events_db");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


if (isset($_POST['input'])) {
    $search = trim($_POST['input']);

    if (!empty($search)) {
    
        $stmt = $conn->prepare("SELECT * FROM events WHERE title LIKE ?");
        $searchTerm = "%" . $search . "%";
        $stmt->bind_param("s", $searchTerm);
    } else {
        $stmt = $conn->prepare("SELECT * FROM events");
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="event bg-white p-4 rounded-lg shadow-md">';
            echo '<h3 class="text-xl font-bold mb-2">' . htmlspecialchars($row['title']) . '</h3>';
            echo '<p class="text-gray-600">' . htmlspecialchars($row['description']) . '</p>';
            echo '</div>';
            echo"nothing here";
        }
    } else {
        echo '<p class="text-center text-gray-500">No events found</p>';
    }

    $stmt->close();
}
$conn->close();
?>
