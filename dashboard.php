<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kampung Kitchen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-sm navbar-dark" style="background-color: lightblue;">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">KAMPUNG KITCHEN</a>
        </div>
    </nav>

    <!-- Homepage Heading -->
    <div class="container mt-4 text-center">
        <h1>KAMPUNG KITCHEN HOMEPAGE</h1>
    </div>

    <!-- Recipe Cards -->
    <div class="container mt-5">
        <div class="row">
            <?php
            // Establishing database connection
            $conn = new mysqli("localhost", "root", "", "kampung_kitchen");


            // Checking connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Query to fetch recipes
            $sql = "SELECT * FROM recipes";
            $result = $conn->query($sql);

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
                echo "<p class='text-center'>No recipes available at the moment.</p>";
            }

            // Closing the connection
            $conn->close();
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
