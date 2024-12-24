<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}
?>

<?php
// Database connection
include 'backend/config.php';

// Get the ethnic_id from the query string (default to 1 for Melayu)
$ethnic_id = isset($_GET['ethnic_id']) ? intval($_GET['ethnic_id']) : 1;

// Fetch recipes for the selected ethnic group
$sql = "SELECT * FROM recipe WHERE ethnic_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $ethnic_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kampung Kitchen - Filtered Recipes</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>



<body>
    <nav class="navbar navbar-expand-sm navbar-dark" style="background-color: lightblue;">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">KAMPUNG KITCHEN</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="?ethnic_id=1">MELAYU</a></li>
                    <li class="nav-item"><a class="nav-link" href="?ethnic_id=2">CINA</a></li>
                    <li class="nav-item"><a class="nav-link" href="?ethnic_id=3">INDIA</a></li>
                    <li class="nav-item"><a class="nav-link" href="?ethnic_id=4">SABAH</a></li>
                    <li class="nav-item"><a class="nav-link" href="?ethnic_id=5">SARAWAK</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid my-custom">
       <h1>KAMPUNG KITCHEN FILTERED PAGE</h1>
   </div>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Filtered Recipes</h1>
        <div class="row">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "
                    <div class='col-md-4 mb-4'>
                        <div class='card'>
                            <img src='" . htmlspecialchars($row['image_url']) . "' class='card-img-top' alt='Recipe Image' style='height: 200px; object-fit: cover;'>
                            <div class='card-body'>
                                <h5 class='card-title'>" . htmlspecialchars($row['name']) . "</h5>
                                <p class='card-text'>" . htmlspecialchars($row['description']) . "</p>
                                <p class='small'>
                                    Prep Time: " . $row['prep_time'] . " mins | Cook Time: " . $row['cook_time'] . " mins<br>
                                    Servings: " . $row['servings'] . "
                                </p>
                            </div>
                        </div>
                    </div>";
                }
            } else {
                echo "<p class='text-center'>No recipes found for the selected ethnic group.</p>";
            }
            ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Close the connection
$stmt->close();
$conn->close();
?>
