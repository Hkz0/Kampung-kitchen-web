<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kampung kitchen</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-sm navbar-dark" style="background-color: lightblue;">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">KAMPUNG KITCHEN</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link active" aria-current="page" href="#!">HOME</a></li>
                        <li class="nav-item"><a class="nav-link" href="#!">MELAYU</a></li>
                        <li class="nav-item"><a class="nav-link" href="#!">CINA</a></li>
                        <li class="nav-item"><a class="nav-link" href="#!">INDIA</a></li>
                        <li class="nav-item"><a class="nav-link" href="#!">SABAH</a></li>
                        <li class="nav-item"><a class="nav-link" href="#!">SARAWAK</a></li>
                    </ul>
                </div>
            </div>
        </nav>

    <!-- Homepage Heading -->
    <div class="container-fluid my-custom">
       <h1>KAMPUNG KITCHEN HOMEPAGE</h1>
   </div>

    <!-- Recipe Cards -->
    <div class="container mt-5">
        <div class="row">
<?php
// Database connection
$servername = "localhost"; // Replace with your server name
$username = "root";        // Replace with your database username
$password = "";            // Replace with your database password
$dbname = "kampung_kitchen"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the recipe_id from the query string
$recipe_id = isset($_GET['recipe_id']) ? intval($_GET['recipe_id']) : 0;

// Fetch the specific recipe
$sql = "SELECT * FROM recipe WHERE recipe_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $recipe_id);
$stmt->execute();
$result = $stmt->get_result();
$recipe = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Details</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <?php if ($recipe): ?>
        <h1 class="text-center mb-4"><?= htmlspecialchars($recipe['name']) ?></h1>
        <div class="card mb-4">
            <img src="<?= htmlspecialchars($recipe['image_url']) ?>" class="card-img-top" alt="Recipe Image" style="height: 400px; object-fit: cover;">
            <div class="card-body">
                <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($recipe['description'])) ?></p>
                <p><strong>Instructions:</strong> <?= nl2br(htmlspecialchars($recipe['instructions'])) ?></p>
                <p><strong>Ingredients:</strong> <?= nl2br(htmlspecialchars($recipe['ingredients'])) ?></p>
                <p>
                    <strong>Prep Time:</strong> <?= $recipe['prep_time'] ?> mins<br>
                    <strong>Cook Time:</strong> <?= $recipe['cook_time'] ?> mins<br>
                    <strong>Servings:</strong> <?= $recipe['servings'] ?>
                </p>
                <p><strong>Created At:</strong> <?= $recipe['created_at'] ?></p>
            </div>
        </div>
    <?php else: ?>
        <p class="text-center">Recipe not found.</p>
    <?php endif; ?>
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
</div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>